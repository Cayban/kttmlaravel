<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\IpRecord;

/* ─────────────────────────────────────────────────────
   MAINTENANCE / DEBUG MODE GUARD HELPER
   Returns a redirect response if maintenance or debug
   mode is ON and the current user is NOT a developer.
   Returns null if the user may pass through freely.
───────────────────────────────────────────────────── */
$maintenanceGuard = function (\Illuminate\Http\Request $request) {
    // Developer always passes through — never blocked
    if ($request->session()->get('user_role') === 'developer') {
        return null;
    }
    // Also pass through if actively impersonating (dev session in new tab)
    if ($request->session()->get('impersonating')) {
        return null;
    }
    try {
        $flags = DB::table('system_settings')
            ->whereIn('key', ['maintenance_mode', 'debug_mode', 'scheduled_at'])
            ->pluck('value', 'key');

        $maintOn  = ($flags->get('maintenance_mode', '0') === '1');
        $debugOn  = ($flags->get('debug_mode',       '0') === '1');
        $schedVal = $flags->get('scheduled_at');

        // Auto-activate if scheduled time has passed and maintenance isn't on yet
        if (!$maintOn && !$debugOn && $schedVal) {
            try {
                $schedTime = \Carbon\Carbon::parse($schedVal);
                if ($schedTime->isPast()) {
                    // Flip maintenance_mode ON
                    $existing = DB::table('system_settings')
                        ->where('key', 'maintenance_mode')->first();
                    if ($existing) {
                        DB::table('system_settings')
                            ->where('key', 'maintenance_mode')
                            ->update(['value' => '1', 'updated_at' => now()]);
                    } else {
                        DB::table('system_settings')->insert([
                            'key'        => 'maintenance_mode',
                            'value'      => '1',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    // Clear the schedule — it has now activated
                    DB::table('system_settings')
                        ->where('key', 'scheduled_at')->delete();

                    // Log the auto-activation
                    try {
                        DB::table('maintenance_logs')->insert([
                            'action'     => 'Maintenance auto-activated from schedule',
                            'by'         => 'System',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } catch (\Exception $e) {}

                    $maintOn = true;
                }
            } catch (\Exception $e) {}
        }

        if ($maintOn || $debugOn) {
            $mode = $debugOn ? 'debug' : 'maintenance';
            // Fetch scheduled end time to show on maintenance page
            $scheduledAt = DB::table('system_settings')
                ->where('key', 'scheduled_at')
                ->value('value');
            return redirect('/maintenance')->with([
                'maintenance_mode' => $mode,
                'scheduled_at'     => $scheduledAt,
            ]);
        }
    } catch (\Exception $e) {
        // If table doesn't exist yet, allow access — fail open
    }
    return null;
};

/* ─────────────────────────────────────────────────────
   PRESENCE TRACKER HELPER
   Upserts a row in active_sessions for every tracked
   page hit. Developers are never recorded.
   Online window: 5 minutes of inactivity = offline.
───────────────────────────────────────────────────── */
$trackPresence = function (\Illuminate\Http\Request $request, string $forceRole) {
    // Never track developers (real or impersonating)
    if ($request->session()->get('user_role') === 'developer') return;

    try {
        $sessionId = $request->session()->getId();

        // Role is determined by the ROUTE being hit, not the session.
        // /home → always 'admin'  |  /guest* → always 'guest'
        // This prevents a logged-in admin navigating to /guest
        // from being counted as an admin in the guest column.
        $actorRole = $forceRole;
        $userId    = ($forceRole === 'admin')
                        ? $request->session()->get('user_id')
                        : null;   // guests never have a user_id, even if session has one

        DB::table('active_sessions')->upsert(
            [
                'session_id'   => $sessionId,
                'role'         => $actorRole,
                'user_id'      => $userId,
                'last_seen_at' => now(),
                'created_at'   => now(),
            ],
            ['session_id'],                         // unique key to match on
            ['role', 'user_id', 'last_seen_at']    // columns to update on conflict
        );

        // Prune stale sessions older than 5 minutes to keep the table lean
        DB::table('active_sessions')
            ->where('last_seen_at', '<', now()->subMinutes(5))
            ->delete();

    } catch (\Exception $e) {
        // Fail silently — presence tracking must never break a page load
        \Log::info('Presence tracker error: ' . $e->getMessage());
    }
};



Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});

/* ─────────────────────────────────────────────────────
   ACCOUNT LOGIN
   Step 1: User submits email + password → validate against accounts table.
   On success: store account_id in session, redirect to profile select.
───────────────────────────────────────────────────── */
Route::post('/login', function (Request $request) {
    $data = $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    // Find account by email
    $account = DB::table('accounts')
        ->where('email', $data['email'])
        ->first();

    if (!$account) {
        return response()->json([
            'success' => false,
            'message' => 'No account found with that email address.',
        ], 401);
    }

    // Verify password against PostgreSQL bcrypt hash (compatible with PHP password_verify)
    if (!password_verify($data['password'], $account->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password.',
        ], 401);
    }

    // Store account in session
    $request->session()->put('account_id',    $account->id);
    $request->session()->put('account_email', $account->email);

    // Check if this account belongs exclusively to a developer —
    // if so, skip profile select and log them in directly to /dev
    $devUser = DB::table('users')
        ->where('account_id', $account->id)
        ->where('role', 'developer')
        ->where('is_active', true)
        ->first();

    if ($devUser) {
        // Set full session just like profile/login does
        $request->session()->put('user_id',           $devUser->id);
        $request->session()->put('user_name',         $devUser->name);
        $request->session()->put('user_role',         $devUser->role);
        $request->session()->put('user_avatar_color', $devUser->avatar_color);
        $request->session()->put('user_avatar_image', $devUser->avatar_image ?? null);

        return response()->json([
            'success'  => true,
            'redirect' => '/dev',
        ]);
    }

    // Non-developer account — go to profile select as normal
    return response()->json([
        'success'  => true,
        'redirect' => '/profile/select',
    ]);
});

/* ─────────────────────────────────────────────────────
   PROFILE SELECTION PAGE
   Step 2: Show profiles that belong to this account.
   Requires a valid account_id in session.
───────────────────────────────────────────────────── */
Route::get('/profile/select', function (Request $request) use ($maintenanceGuard) {
    // Guard: must have logged in via account first
    if (!$request->session()->has('account_id')) {
        return redirect('/')->with('login_error', 'Please sign in first.');
    }

    // Maintenance / debug mode block
    if ($redir = $maintenanceGuard($request)) return $redir;

    $accountId = $request->session()->get('account_id');

    $profiles = DB::table('users')
        ->where('account_id', $accountId)
        ->where('is_active', true)
        ->where('role', '!=', 'developer')   // developers have their own login at /dev/login
        ->orderByRaw("CASE role WHEN 'admin' THEN 0 ELSE 1 END")
        ->get();

    return view('profile_select', [
        'profiles' => $profiles,
    ]);
});

/* ─────────────────────────────────────────────────────
   PROFILE LOGIN
   Step 3: User picks a profile card and enters its password.
   On success: store full user session, redirect to /home.
───────────────────────────────────────────────────── */
Route::post('/profile/create', function (Request $request) {
    try {
        if (!$request->session()->has('account_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please sign in again.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|min:2|max:255',
            'password' => 'required|string|min:8|max:255',
            'avatar'   => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first() ?: 'Please check the form fields and try again.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $accountId = (int) $request->session()->get('account_id');
        $role      = $request->session()->get('user_role', 'admin');
        $name      = trim($data['name']);

        $duplicate = DB::table('users')
            ->where('account_id', $accountId)
            ->whereRaw('LOWER(name) = ?', [Str::lower($name)])
            ->exists();

        if ($duplicate) {
            return response()->json([
                'success' => false,
                'message' => 'A profile with that name already exists for this account.',
            ], 422);
        }

        $avatarFilename = null;

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $file = $request->file('avatar');
            $avatarDir = storage_path('app/public/avatars');

            if (!is_dir($avatarDir)) {
                mkdir($avatarDir, 0755, true);
            }

            $extension = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
            $avatarFilename = 'profile_' . $accountId . '_' . time() . '_' . Str::lower(Str::random(6)) . '.' . $extension;
            $file->move($avatarDir, $avatarFilename);
        }

        $userId = DB::table('users')->insertGetId([
            'account_id'     => $accountId,
            'name'           => $name,
            'password'       => password_hash($data['password'], PASSWORD_BCRYPT),
            'role'           => $role === 'developer' ? 'admin' : $role,
            'avatar_color'   => $request->session()->get('user_avatar_color', '#A52C30'),
            'avatar_image'   => $avatarFilename,
            'is_active'      => true,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile created successfully.',
            'profile' => [
                'id'           => $userId,
                'name'         => $name,
                'avatar_image' => $avatarFilename,
            ],
        ]);
    } catch (\Throwable $e) {
        \Log::error('Profile creation failed: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());

        return response()->json([
            'success' => false,
            'message' => 'Server error while creating the profile.',
        ], 500);
    }
});

Route::post('/profile/login', function (Request $request) {
    // Guard: must have a valid account session
    if (!$request->session()->has('account_id')) {
        return response()->json([
            'success' => false,
            'message' => 'Session expired. Please sign in again.',
        ], 401);
    }

    $data = $request->validate([
        'profile_id' => 'required|integer',
        'password'   => 'required|string',
    ]);

    $accountId = $request->session()->get('account_id');

    // Fetch the user profile — must belong to the current account
    $user = DB::table('users')
        ->where('id', $data['profile_id'])
        ->where('account_id', $accountId)
        ->where('is_active', true)
        ->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Profile not found.',
        ], 404);
    }

    // Verify profile password
    if (!password_verify($data['password'], $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Incorrect password. Please try again.',
        ], 401);
    }

    // Store user session
    $request->session()->put('user_id',           $user->id);
    $request->session()->put('user_name',         $user->name);
    $request->session()->put('user_role',         $user->role);
    $request->session()->put('user_base_role',    $user->base_role   ?? $user->role);
    $request->session()->put('user_custom_role',  $user->custom_role ?? null);
    $request->session()->put('user_avatar_color', $user->avatar_color);
    $request->session()->put('user_avatar_image', $user->avatar_image ?? null);
    $request->session()->put('session_started_at', now()->format('M d, Y · h:i A'));
    // Redirect based on base role
    $redirect = match($user->base_role ?? $user->role) {
        'admin'     => '/home',
        'developer' => '/dev',
        default     => '/home',
    };

    return response()->json([
        'success'  => true,
        'redirect' => $redirect,
    ]);
});

/* ─────────────────────────────────────────────────────
   DEVELOPER LOGIN PAGE
   Separate login for developers — skips profile select.
   Authenticates directly against users table (role=developer).
───────────────────────────────────────────────────── */
Route::get('/dev/login', function (Request $request) {
    // Already logged in as developer → go straight to dev dashboard
    if ($request->session()->has('user_id') &&
        $request->session()->get('user_role') === 'developer') {
        return redirect('/dev');
    }
    return view('dev_login');
})->name('dev.login');

Route::post('/dev/login', function (Request $request) {
    try {
        $email    = trim($request->input('email', ''));
        $password = $request->input('password', '');

        if (!$email || !$password) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter your email and password.',
            ], 422);
        }

        // Find developer user directly by email (stored on accounts table)
        // Developer's account email is used to look up their user record
        $account = DB::table('accounts')->where('email', $email)->first();

        if (!$account) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        // Verify account password
        if (!password_verify($password, $account->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        // Get the developer profile tied to this account
        $user = DB::table('users')
            ->where('account_id', $account->id)
            ->where('role', 'developer')
            ->where('is_active', true)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No developer profile found for this account.',
            ], 403);
        }

        // Set full session
        $request->session()->put('account_id',         $account->id);
        $request->session()->put('account_email',      $account->email);
        $request->session()->put('user_id',            $user->id);
        $request->session()->put('user_name',          $user->name);
        $request->session()->put('user_role',          $user->role);
        $request->session()->put('user_avatar_color',  $user->avatar_color);
        $request->session()->put('user_avatar_image',  $user->avatar_image ?? null);

        return response()->json([
            'success'  => true,
            'redirect' => '/dev',
        ]);

    } catch (\Exception $e) {
        \Log::error('Dev login error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong. Please try again.',
        ], 500);
    }
});

/* ─────────────────────────────────────────────────────
   PROFILE PAGE
   Shows the logged-in user's profile details.
───────────────────────────────────────────────────── */
Route::get('/profile', function (Request $request) {
    if (!$request->session()->has('user_id')) {
        return redirect('/')->with('login_error', 'Please sign in first.');
    }
    return view('profile');
})->name('profile');

/* ─────────────────────────────────────────────────────
   CHANGE PASSWORD
   Updates the profile password. Verifies current first.    
───────────────────────────────────────────────────── */
Route::post('/profile/change-password', function (Request $request) {
    if (!$request->session()->has('user_id')) {
        return response()->json(['success' => false, 'message' => 'Session expired.'], 401);
    }

    $data = $request->validate([
        'current_password' => 'required|string',
        'new_password'     => 'required|string|min:8',
    ]);

    $user = DB::table('users')->where('id', $request->session()->get('user_id'))->first();

    if (!$user || !password_verify($data['current_password'], $user->password)) {
        return response()->json(['success' => false, 'message' => 'Current password is incorrect.'], 401);
    }

    DB::table('users')->where('id', $user->id)->update([
        'password'   => password_hash($data['new_password'], PASSWORD_BCRYPT),
        'updated_at' => now(),
    ]);

    return response()->json(['success' => true, 'message' => 'Password updated successfully.']);
});
/* ─────────────────────────────────────────────────────
   CHANGE NAME
   Updates the profile display name.
   Validates, checks for duplicates within the account,
   updates DB and session.
───────────────────────────────────────────────────── */
Route::post('/profile/change-name', function (Request $request) {
    if (!$request->session()->has('user_id')) {
        return response()->json(['success' => false, 'message' => 'Session expired.'], 401);
    }

    $data = $request->validate([
        'name' => 'required|string|min:2|max:60',
    ]);

    $newName  = trim($data['name']);
    $userId   = $request->session()->get('user_id');
    $user     = DB::table('users')->where('id', $userId)->first();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }

    // No change needed
    if (strtolower($user->name) === strtolower($newName)) {
        return response()->json(['success' => false, 'message' => 'This is already your current name.'], 422);
    }

    // Check for duplicate name within the same account
    $duplicate = DB::table('users')
        ->where('account_id', $user->account_id)
        ->where('id', '!=', $userId)
        ->whereRaw('LOWER(name) = ?', [strtolower($newName)])
        ->exists();

    if ($duplicate) {
        return response()->json(['success' => false, 'message' => 'Another profile in this account already uses that name.'], 422);
    }

    DB::table('users')->where('id', $userId)->update([
        'name'       => $newName,
        'updated_at' => now(),
    ]);

    // Update session so the UI reflects change immediately
    $request->session()->put('user_name', $newName);

    return response()->json(['success' => true, 'message' => 'Name updated successfully.', 'name' => $newName]);
});

/* ─────────────────────────────────────────────────────
   CHANGE BASE ROLE
   Updates the user's base role (admin or staff only).
   Developer role cannot be changed here.
───────────────────────────────────────────────────── */
Route::post('/profile/change-base-role', function (Request $request) {
    if (!$request->session()->has('user_id')) {
        return response()->json(['success' => false, 'message' => 'Session expired.'], 401);
    }
    $data = $request->validate(['base_role' => 'required|string|in:admin,staff']);
    $newBaseRole = $data['base_role'];
    $userId      = $request->session()->get('user_id');
    $user        = DB::table('users')->where('id', $userId)->first();
    if (!$user) return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    if ($user->role === 'developer') return response()->json(['success' => false, 'message' => 'Developer role cannot be changed here.'], 403);
    DB::table('users')->where('id', $userId)->update([
        'base_role'  => $newBaseRole,
        'role'       => $newBaseRole,
        'updated_at' => now(),
    ]);
    $request->session()->put('user_base_role', $newBaseRole);
    $request->session()->put('user_role',      $newBaseRole);
    return response()->json(['success' => true, 'message' => 'Base role updated.', 'base_role' => $newBaseRole]);
});

/* ─────────────────────────────────────────────────────
   CHANGE CUSTOM ROLE
   Sets or clears the user's custom role (free-text, nullable).
   Base role is untouched.
───────────────────────────────────────────────────── */
Route::post('/profile/change-custom-role', function (Request $request) {
    if (!$request->session()->has('user_id')) {
        return response()->json(['success' => false, 'message' => 'Session expired.'], 401);
    }
    $data          = $request->validate(['custom_role' => 'nullable|string|max:50']);
    $newCustomRole = !empty($data['custom_role']) ? trim($data['custom_role']) : null;
    if ($newCustomRole && !preg_match('/^[a-zA-Z0-9 _\-]+$/', $newCustomRole)) {
        return response()->json(['success' => false, 'message' => 'Custom role contains invalid characters.'], 422);
    }
    $userId = $request->session()->get('user_id');
    $user   = DB::table('users')->where('id', $userId)->first();
    if (!$user) return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    DB::table('users')->where('id', $userId)->update([
        'custom_role' => $newCustomRole,
        'updated_at'  => now(),
    ]);
    $request->session()->put('user_custom_role', $newCustomRole);
    return response()->json(['success' => true, 'message' => 'Custom role updated.', 'custom_role' => $newCustomRole]);
});

/* ─────────────────────────────────────────────────────
   UPLOAD AVATAR
   Accepts an image file, stores it in storage/avatars,
   updates the users table and session.
   NOTE: Entire route is wrapped in try/catch to always
   return JSON — never an HTML error page.
───────────────────────────────────────────────────── */
Route::post('/profile/upload-avatar', function (Request $request) {
    // ── Always respond with JSON, never an HTML page ──────────────────────
    try {

        // ── 1. Manual CSRF check (catches 419 before it becomes an HTML page)
        $tokenFromForm   = $request->input('_token') ?? $request->header('X-CSRF-TOKEN') ?? '';
        $tokenFromCookie = $request->session()->token() ?? '';
        if (!hash_equals($tokenFromCookie, $tokenFromForm)) {
            return response()->json([
                'success' => false,
                'message' => 'Security token mismatch. Please refresh the page and try again.',
            ], 419);
        }

        // ── 2. Session guard ───────────────────────────────────────────────
        if (!$request->session()->has('user_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please log in again.',
            ], 401);
        }

        // ── 3. File presence & validity ────────────────────────────────────
        if (!$request->hasFile('avatar') || !$request->file('avatar')->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'No valid file received.',
            ], 422);
        }

        $file         = $request->file('avatar');
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $maxBytes     = 2 * 1024 * 1024; // 2 MB

        // ── 4. MIME type check ─────────────────────────────────────────────
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return response()->json([
                'success' => false,
                'message' => 'Only JPG, PNG, or WEBP images are allowed.',
            ], 422);
        }

        // ── 5. File size check ─────────────────────────────────────────────
        if ($file->getSize() > $maxBytes) {
            return response()->json([
                'success' => false,
                'message' => 'File exceeds the 2MB size limit.',
            ], 422);
        }

        // ── 6. Fetch user record ───────────────────────────────────────────
        $userId = $request->session()->get('user_id');
        $user   = DB::table('users')->where('id', $userId)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        // ── 7. Ensure storage directory exists ─────────────────────────────
        $avatarDir = storage_path('app/public/avatars');
        if (!is_dir($avatarDir)) {
            mkdir($avatarDir, 0755, true);
        }

        // ── 8. Delete previous avatar file if present ──────────────────────
        if (!empty($user->avatar_image)) {
            $oldPath = $avatarDir . DIRECTORY_SEPARATOR . $user->avatar_image;
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        // ── 9. Move uploaded file to avatars directory ─────────────────────
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
        $filename  = $userId . '_' . time() . '.' . $extension;

        if (!$file->move($avatarDir, $filename)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save the file. Check storage folder permissions.',
            ], 500);
        }

        // ── 10. Persist to DB ──────────────────────────────────────────────
        DB::table('users')->where('id', $userId)->update([
            'avatar_image' => $filename,
            'updated_at'   => now(),
        ]);

        // ── 11. Update session so UI reflects new photo immediately ────────
        $request->session()->put('user_avatar_image', $filename);

        // ── 12. Build public URL (storage:link already confirmed present) ──
        $publicPath = public_path('storage/avatars/' . $filename);
        $url = file_exists($publicPath)
            ? asset('storage/avatars/' . $filename)
            : url('/profile/avatar/' . $filename);

        return response()->json([
            'success'  => true,
            'message'  => 'Profile picture updated successfully.',
            'filename' => $filename,
            'url'      => $url,
        ]);

    } catch (\Exception $e) {
        \Log::error('Avatar upload failed: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage(),
        ], 500);
    }
});

/* ─────────────────────────────────────────────────────
   AVATAR SERVE FALLBACK
   Serves avatar directly from storage if symlink missing.
───────────────────────────────────────────────────── */
Route::get('/profile/avatar/{filename}', function ($filename) {
    // Sanitize — only allow safe filenames
    if (!preg_match('/^[0-9]+_[0-9]+\.(jpg|jpeg|png|webp)$/i', $filename)) {
        abort(404);
    }
    $path = storage_path('app/public/avatars/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    $mime = mime_content_type($path) ?: 'image/jpeg';
    return response()->file($path, ['Content-Type' => $mime]);
});

/* ═════════════════════════════════════════════════════
   DEVELOPER ROUTES
   All routes below require an active session with
   role = 'developer'. Any other role is redirected.
═════════════════════════════════════════════════════ */

// ── Dev route guard helper ─────────────────────────
$devGuard = function (\Illuminate\Http\Request $request) {
    if (!$request->session()->has('user_id')) {
        return redirect('/')->with('login_error', 'Please sign in first.');
    }
    if ($request->session()->get('user_role') !== 'developer') {
        return redirect('/home');
    }
    return null;
};

/* ─────────────────────────────────────────────────────
   DEV HOME / DASHBOARD
───────────────────────────────────────────────────── */
Route::get('/dev', function (\Illuminate\Http\Request $request) use ($devGuard) {
    if ($redir = $devGuard($request)) return $redir;

    // ── System health: all users with last login ───
    $users = DB::table('users')
        ->select('id', 'name', 'role', 'is_active', 'avatar_color', 'avatar_image', 'last_login_at', 'updated_at')
        ->orderByRaw("CASE role WHEN 'admin' THEN 0 ELSE 1 END")
        ->get();

    // ── Storage usage for avatars ──────────────────
    $avatarDir   = storage_path('app/public/avatars');
    $storageBytes = 0;
    $storageFiles = 0;
    if (is_dir($avatarDir)) {
        foreach (glob($avatarDir . '/*') as $f) {
            if (is_file($f)) { $storageBytes += filesize($f); $storageFiles++; }
        }
    }
    $storageMB = round($storageBytes / 1024 / 1024, 2);

    // ── Recent activity logs ───────────────────────
    $recentActivity = collect();
    try {
        $recentActivity = \App\Models\ActivityLog::orderBy('created_at', 'desc')
            ->limit(15)
            ->get(['id', 'record_id', 'record_title', 'action', 'user_name', 'created_at'])
            ->map(fn($l) => [
                'id'           => $l->id,
                'record_id'    => $l->record_id,
                'record_title' => $l->record_title,
                'action'       => $l->action,
                'user_name'    => $l->user_name,
                'timestamp'    => $l->created_at->toIso8601String(),
                'time_ago'     => $l->created_at->diffForHumans(),
            ]);
    } catch (\Exception $e) {
        \Log::info('Dev dashboard activity log error: ' . $e->getMessage());
    }

    // ── KPI counts ────────────────────────────────
    $onlineWindow = now()->subMinutes(5);
    $kpis = [
        'total_records'   => IpRecord::count(),
        'registered'      => IpRecord::where('status', 'Registered')->count(),
        'pending'         => IpRecord::where('status', '!=', 'Registered')->count(),
        'total_users'     => DB::table('users')->where('role', '!=', 'developer')->count(),
        'active_users'    => DB::table('users')->where('is_active', true)->where('role', '!=', 'developer')->count(),
        // Real-time presence: counts from active_sessions (5-min window, developers excluded)
        'active_admins'   => DB::table('active_sessions')
                                ->where('role', 'admin')
                                ->where('last_seen_at', '>=', $onlineWindow)
                                ->count(),
        'active_guests'   => DB::table('active_sessions')
                                ->where('role', 'guest')
                                ->where('last_seen_at', '>=', $onlineWindow)
                                ->count(),
    ];

    $user = (object)[
        'name'         => session('user_name', 'Developer'),
        'role'         => 'developer',
        'avatar_color' => session('user_avatar_color', '#A52C30'),
        'avatar_image' => session('user_avatar_image', null),
    ];

    // ── System flags (maintenance / debug / schedule) ──
    $systemFlags = [];
    try {
        $flags = DB::table('system_settings')
            ->whereIn('key', ['maintenance_mode', 'debug_mode', 'scheduled_at'])
            ->pluck('value', 'key')
            ->toArray();
        $systemFlags = [
            'maintenance_mode' => ($flags['maintenance_mode'] ?? '0') === '1',
            'debug_mode'       => ($flags['debug_mode']       ?? '0') === '1',
            'scheduled_at'     => $flags['scheduled_at']      ?? null,
        ];
    } catch (\Exception $e) {
        \Log::info('system_settings table not ready: ' . $e->getMessage());
    }

    // ── Maintenance log (last 20 toggle events) ────────
    $maintenanceLogs = [];
    try {
        $maintenanceLogs = DB::table('maintenance_logs')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(fn($l) => [
                'action'   => $l->action,
                'by'       => $l->by,
                'time_ago' => \Carbon\Carbon::parse($l->created_at)->diffForHumans(),
            ])->toArray();
    } catch (\Exception $e) {
        \Log::info('maintenance_logs table not ready: ' . $e->getMessage());
    }

    return view('dev_home', compact('user', 'users', 'kpis', 'recentActivity', 'storageMB', 'storageFiles', 'systemFlags', 'maintenanceLogs'));
})->name('dev.home');

/* ─────────────────────────────────────────────────────
   DEV RECORDS — Read-only view
───────────────────────────────────────────────────── */
Route::get('/dev/records', function (\Illuminate\Http\Request $request) use ($devGuard) {
    if ($redir = $devGuard($request)) return $redir;

    $allRecords = IpRecord::orderByDesc('date_registered_deposited')
        ->get()
        ->map(fn($r) => [
            'id'                  => $r->record_id,
            'title'               => $r->ip_title,
            'type'                => $r->category,
            'owner'               => $r->owner_inventor,
            'campus'              => $r->campus,
            'college'             => $r->college,
            'program'             => $r->program,
            'class_of_work'       => $r->class_of_work,
            'date_creation'       => $r->date_creation,
            'status'              => $r->status,
            'registered'          => $r->date_registered_deposited,
            'registration_number' => $r->registration_number,
            'gdrive_link'         => $r->gdrive_link,
            'remarks'             => $r->remarks,
        ])->toArray();

    $campuses = collect($allRecords)->pluck('campus')->filter()->unique()->sort()->values()->all();
    $types    = collect($allRecords)->pluck('type')->filter()->unique()->sort()->values()->all();
    $statuses = collect($allRecords)->pluck('status')->filter()->unique()->sort()->values()->all();
    $colleges = IpRecord::select('college')->whereNotNull('college')->distinct()->pluck('college')->filter()->sort()->values()->all();
    $programs = IpRecord::select('program')->whereNotNull('program')->distinct()->pluck('program')->filter()->sort()->values()->all();

    $recent = IpRecord::orderByDesc('date_registered_deposited')
        ->limit(10)
        ->get()
        ->map(fn($r) => [
            'id'     => $r->record_id,
            'title'  => $r->ip_title,
            'type'   => $r->category,
            'status' => $r->status,
        ])->toArray();

    $user = (object)[
        'name'         => session('user_name', 'Developer'),
        'role'         => 'developer',
        'avatar_color' => session('user_avatar_color', '#A52C30'),
        'avatar_image' => session('user_avatar_image', null),
    ];

    return view('dev_records', compact('user', 'allRecords', 'campuses', 'types', 'statuses', 'colleges', 'programs', 'recent'));
})->name('dev.records');

/* ─────────────────────────────────────────────────────
   DEV PROFILE PAGE
───────────────────────────────────────────────────── */
Route::get('/dev/profile', function (\Illuminate\Http\Request $request) use ($devGuard) {
    if ($redir = $devGuard($request)) return $redir;
    return view('dev_profile');
})->name('dev.profile');

/* ─────────────────────────────────────────────────────
   DEV API: RESET USER PASSWORD
───────────────────────────────────────────────────── */
Route::post('/dev/users/{id}/reset-password', function (\Illuminate\Http\Request $request, $id) use ($devGuard) {
    if ($request->session()->get('user_role') !== 'developer') {
        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }

    $data = $request->validate(['new_password' => 'required|string|min:8']);

    // Prevent developer from resetting another developer's password
    $target = DB::table('users')->where('id', $id)->first();
    if (!$target) {
        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }
    if ($target->role === 'developer') {
        return response()->json(['success' => false, 'message' => 'Cannot reset another developer account.'], 403);
    }

    DB::table('users')->where('id', $id)->update([
        'password'   => password_hash($data['new_password'], PASSWORD_BCRYPT),
        'updated_at' => now(),
    ]);

    return response()->json(['success' => true, 'message' => 'Password reset successfully.']);
});

/* ─────────────────────────────────────────────────────
   DEV API: TOGGLE USER ACTIVE STATUS
───────────────────────────────────────────────────── */
Route::patch('/dev/users/{id}/toggle-active', function (\Illuminate\Http\Request $request, $id) use ($devGuard) {
    if ($request->session()->get('user_role') !== 'developer') {
        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }

    $target = DB::table('users')->where('id', $id)->first();
    if (!$target) {
        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }
    if ($target->role === 'developer') {
        return response()->json(['success' => false, 'message' => 'Cannot modify another developer account.'], 403);
    }

    $newStatus = !$target->is_active;
    DB::table('users')->where('id', $id)->update([
        'is_active'  => $newStatus,
        'updated_at' => now(),
    ]);

    return response()->json([
        'success'   => true,
        'is_active' => $newStatus,
        'message'   => 'User ' . ($newStatus ? 'activated' : 'deactivated') . ' successfully.',
    ]);
});

/* ─────────────────────────────────────────────────────
   DEV API: USER LOGIN HISTORY
───────────────────────────────────────────────────── */
Route::get('/dev/users/{id}/login-history', function (\Illuminate\Http\Request $request, $id) use ($devGuard) {
    if ($request->session()->get('user_role') !== 'developer') {
        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }

    try {
        $logs = \App\Models\ActivityLog::where('user_name', function($q) use ($id) {
                $q->select('name')->from('users')->where('id', $id)->limit(1);
            })
            ->orderByDesc('created_at')
            ->limit(20)
            ->get(['action', 'record_title', 'created_at'])
            ->map(fn($l) => [
                'action'       => $l->action,
                'record_title' => $l->record_title,
                'timestamp'    => $l->created_at->toIso8601String(),
                'time_ago'     => $l->created_at->diffForHumans(),
            ]);

        $user = DB::table('users')->where('id', $id)->select('name', 'last_login_at')->first();

        return response()->json([
            'success'       => true,
            'user_name'     => $user->name ?? '—',
            'last_login_at' => $user->last_login_at ?? null,
            'logs'          => $logs,
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

/* ═════════════════════════════════════════════════════
   MAINTENANCE PAGE
   Public — shown to blocked admins and guests.
   Developer is never redirected here.
═════════════════════════════════════════════════════ */
Route::get('/maintenance', function (\Illuminate\Http\Request $request) {
    // If dev navigates here manually, send them back to dev panel
    if ($request->session()->get('user_role') === 'developer') {
        return redirect('/dev');
    }
    // Fetch current flags to decide what to show
    $mode        = 'maintenance';
    $scheduledAt = null;
    try {
        $flags = DB::table('system_settings')
            ->whereIn('key', ['maintenance_mode', 'debug_mode', 'scheduled_at'])
            ->pluck('value', 'key');
        if (($flags->get('debug_mode', '0') === '1')) $mode = 'debug';
        $scheduledAt = $flags->get('scheduled_at');
    } catch (\Exception $e) {}

    return view('maintenance', [
        'mode'        => session('maintenance_mode', $mode),
        'scheduledAt' => session('scheduled_at', $scheduledAt),
    ]);
})->name('maintenance');

/* ═════════════════════════════════════════════════════
   DEV SYSTEM CONTROL ROUTES
   All require active developer session.
═════════════════════════════════════════════════════ */

/* ─────────────────────────────────────────────────────
   TOGGLE MAINTENANCE MODE
───────────────────────────────────────────────────── */
Route::post('/dev/system/maintenance', function (\Illuminate\Http\Request $request) use ($devGuard) {
    if ($request->session()->get('user_role') !== 'developer') {
        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }

    $enabled  = (bool) $request->input('enabled', false);
    $devName  = $request->session()->get('user_name', 'Developer');
    $actionLbl = 'Maintenance Mode ' . ($enabled ? 'ON' : 'OFF');

    try {
        // Upsert the flag
        $existing = DB::table('system_settings')->where('key', 'maintenance_mode')->first();
        if ($existing) {
            DB::table('system_settings')
                ->where('key', 'maintenance_mode')
                ->update(['value' => $enabled ? '1' : '0', 'updated_at' => now()]);
        } else {
            DB::table('system_settings')->insert([
                'key'        => 'maintenance_mode',
                'value'      => $enabled ? '1' : '0',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Log the event
        DB::table('maintenance_logs')->insert([
            'action'     => $actionLbl,
            'by'         => $devName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success'   => true,
            'message'   => $actionLbl,
            'log_entry' => [
                'action' => $actionLbl,
                'by'     => $devName,
            ],
        ]);
    } catch (\Exception $e) {
        \Log::error('Maintenance toggle error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
    }
});

/* ─────────────────────────────────────────────────────
   TOGGLE DEBUG MODE
───────────────────────────────────────────────────── */
Route::post('/dev/system/debug', function (\Illuminate\Http\Request $request) use ($devGuard) {
    if ($request->session()->get('user_role') !== 'developer') {
        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }

    $enabled   = (bool) $request->input('enabled', false);
    $devName   = $request->session()->get('user_name', 'Developer');
    $actionLbl = 'Debug Mode ' . ($enabled ? 'ON' : 'OFF');

    try {
        $existing = DB::table('system_settings')->where('key', 'debug_mode')->first();
        if ($existing) {
            DB::table('system_settings')
                ->where('key', 'debug_mode')
                ->update(['value' => $enabled ? '1' : '0', 'updated_at' => now()]);
        } else {
            DB::table('system_settings')->insert([
                'key'        => 'debug_mode',
                'value'      => $enabled ? '1' : '0',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('maintenance_logs')->insert([
            'action'     => $actionLbl,
            'by'         => $devName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success'   => true,
            'message'   => $actionLbl,
            'log_entry' => [
                'action' => $actionLbl,
                'by'     => $devName,
            ],
        ]);
    } catch (\Exception $e) {
        \Log::error('Debug toggle error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
    }
});

/* ─────────────────────────────────────────────────────
   SET SCHEDULED MAINTENANCE
───────────────────────────────────────────────────── */
Route::post('/dev/system/schedule', function (\Illuminate\Http\Request $request) use ($devGuard) {
    if ($request->session()->get('user_role') !== 'developer') {
        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }

    $data = $request->validate([
        'scheduled_at' => 'required|date|after:now',
    ]);

    try {
        $existing = DB::table('system_settings')->where('key', 'scheduled_at')->first();
        if ($existing) {
            DB::table('system_settings')
                ->where('key', 'scheduled_at')
                ->update(['value' => $data['scheduled_at'], 'updated_at' => now()]);
        } else {
            DB::table('system_settings')->insert([
                'key'        => 'scheduled_at',
                'value'      => $data['scheduled_at'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $devName = $request->session()->get('user_name', 'Developer');
        DB::table('maintenance_logs')->insert([
            'action'     => 'Scheduled Maintenance set: ' . \Carbon\Carbon::parse($data['scheduled_at'])->format('M d, Y H:i'),
            'by'         => $devName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Maintenance scheduled.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
    }
});

/* ─────────────────────────────────────────────────────
   CLEAR SCHEDULED MAINTENANCE
───────────────────────────────────────────────────── */
Route::post('/dev/system/schedule/clear', function (\Illuminate\Http\Request $request) use ($devGuard) {
    if ($request->session()->get('user_role') !== 'developer') {
        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }

    try {
        DB::table('system_settings')->where('key', 'scheduled_at')->delete();

        $devName = $request->session()->get('user_name', 'Developer');
        DB::table('maintenance_logs')->insert([
            'action'     => 'Scheduled Maintenance cleared',
            'by'         => $devName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Schedule cleared.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
    }
});

/* ─────────────────────────────────────────────────────
   ACTIVATE SCHEDULED MAINTENANCE NOW
   Called by JS countdown when timer hits zero.
   Flips maintenance_mode ON and clears the schedule.
───────────────────────────────────────────────────── */
Route::post('/dev/system/schedule/activate', function (\Illuminate\Http\Request $request) {
    if ($request->session()->get('user_role') !== 'developer') {
        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }

    try {
        // Turn maintenance mode ON
        $existing = DB::table('system_settings')
            ->where('key', 'maintenance_mode')->first();
        if ($existing) {
            DB::table('system_settings')
                ->where('key', 'maintenance_mode')
                ->update(['value' => '1', 'updated_at' => now()]);
        } else {
            DB::table('system_settings')->insert([
                'key'        => 'maintenance_mode',
                'value'      => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Clear the schedule — it has now fired
        DB::table('system_settings')
            ->where('key', 'scheduled_at')->delete();

        // Log it
        $devName = $request->session()->get('user_name', 'System');
        try {
            DB::table('maintenance_logs')->insert([
                'action'     => 'Maintenance auto-activated — scheduled time reached',
                'by'         => $devName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {}

        return response()->json(['success' => true, 'message' => 'Maintenance mode activated.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
    }
});
/* Stores impersonation context in session.
   Returns a redirect URL the frontend opens in new tab.
───────────────────────────────────────────────────── */
/* Stores impersonation context in session.
   Returns a redirect URL the frontend opens in new tab. */
Route::post('/dev/impersonate/{id}', function (\Illuminate\Http\Request $request, $id) use ($devGuard) {
    if ($request->session()->get('user_role') !== 'developer') {
        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }

    $target = DB::table('users')->where('id', $id)->first();

    if (!$target) {
        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }
    if ($target->role === 'developer') {
        return response()->json(['success' => false, 'message' => 'Cannot impersonate another developer.'], 403);
    }
    if (!$target->is_active) {
        return response()->json(['success' => false, 'message' => 'Cannot impersonate an inactive user.'], 403);
    }

    // Save the current real dev session values so we can restore on exit
    $request->session()->put('impersonating',            true);
    $request->session()->put('impersonating_user_id',    $target->id);
    $request->session()->put('impersonating_name',       $target->name);
    $request->session()->put('impersonating_role',       $target->role);
    $request->session()->put('impersonating_color',      $target->avatar_color);
    $request->session()->put('impersonating_avatar',     $target->avatar_image ?? null);
    $request->session()->put('real_user_id',             $request->session()->get('user_id'));
    $request->session()->put('real_user_name',           $request->session()->get('user_name'));
    $request->session()->put('real_user_role',           $request->session()->get('user_role'));
    $request->session()->put('real_avatar_color',        $request->session()->get('user_avatar_color'));
    $request->session()->put('real_avatar_image',        $request->session()->get('user_avatar_image'));

    // Switch active session to the impersonated user
    $request->session()->put('user_id',           $target->id);
    $request->session()->put('user_name',         $target->name);
    $request->session()->put('user_role',         $target->role);
    $request->session()->put('user_avatar_color', $target->avatar_color);
    $request->session()->put('user_avatar_image', $target->avatar_image ?? null);

    $devName = $request->session()->get('real_user_name', 'Developer');
    \Log::info("Developer [{$devName}] started impersonating [{$target->name}] (ID:{$target->id})");

    // Redirect target depends on role
    $redirect = match($target->role) {
        'admin'  => '/home',
        'guest'  => '/guest',
        default  => '/home',
    };

    return response()->json([
        'success'  => true,
        'redirect' => $redirect,
    ]);
});

/* ─────────────────────────────────────────────────────
   IMPERSONATE USER — Exit
   Restores the original developer session.
───────────────────────────────────────────────────── */
Route::post('/dev/impersonate/exit', function (\Illuminate\Http\Request $request) {
    // Restore the real developer session
    $request->session()->put('user_id',           $request->session()->get('real_user_id'));
    $request->session()->put('user_name',         $request->session()->get('real_user_name'));
    $request->session()->put('user_role',         $request->session()->get('real_user_role'));
    $request->session()->put('user_avatar_color', $request->session()->get('real_avatar_color'));
    $request->session()->put('user_avatar_image', $request->session()->get('real_avatar_image'));

    // Clear all impersonation flags
    $request->session()->forget([
        'impersonating',
        'impersonating_user_id',
        'impersonating_name',
        'impersonating_role',
        'impersonating_color',
        'impersonating_avatar',
        'real_user_id',
        'real_user_name',
        'real_user_role',
        'real_avatar_color',
        'real_avatar_image',
    ]);

    return redirect('/dev');
});

/* ─────────────────────────────────────────────────────
   DEV RECORD DETAIL — Read-only full view of a record
───────────────────────────────────────────────────── */
Route::get('/dev/recorddetail/{id}', function (\Illuminate\Http\Request $request, $id) use ($devGuard) {
    if ($redir = $devGuard($request)) return $redir;

    $id       = trim(urldecode($id));
    $recordDb = App\Models\IpRecord::whereRaw('TRIM(record_id) = ?', [$id])->first();
    $record   = null;

    if ($recordDb) {
        $record = [
            'id'                  => $recordDb->record_id,
            'title'               => $recordDb->ip_title,
            'type'                => $recordDb->category,
            'owner'               => $recordDb->owner_inventor,
            'campus'              => $recordDb->campus,
            'college'             => $recordDb->college             ?? null,
            'program'             => $recordDb->program             ?? null,
            'class_of_work'       => $recordDb->class_of_work       ?? null,
            'date_creation'       => $recordDb->date_creation       ?? null,
            'status'              => $recordDb->status,
            'registered'          => $recordDb->date_registered_deposited,
            'registration_number' => $recordDb->registration_number,
            'gdrive_link'         => $recordDb->gdrive_link,
            'remarks'             => $recordDb->remarks,
        ];
    }

    // Fetch activity log for this record
    $activityLog = collect();
    try {
        $activityLog = \App\Models\ActivityLog::where('record_id', $id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get(['action', 'changes', 'created_at', 'user_name', 'record_title'])
            ->map(fn($l) => [
                'action'    => $l->action,
                'user_name' => $l->user_name ?? '—',
                'time_ago'  => $l->created_at->diffForHumans(),
                'timestamp' => $l->created_at->format('M d, Y · h:i A'),
            ]);
    } catch (\Exception $e) {
        \Log::info('Dev record detail activity log error: ' . $e->getMessage());
    }

    $user = (object)[
        'name'         => session('user_name', 'Developer'),
        'role'         => 'developer',
        'avatar_color' => session('user_avatar_color', '#3B82F6'),
        'avatar_image' => session('user_avatar_image', null),
    ];

    $recordId = $id;
    return view('dev_recorddetail', compact('user', 'record', 'recordId', 'activityLog'));
})->name('dev.recorddetail');

/**
 * ✅ GUEST RECORD DETAIL PAGE
 */
Route::get('/guestrecorddetail/{id}', function ($id) {
    $id = trim(urldecode($id));
    $recordDb = App\Models\IpRecord::whereRaw('TRIM(record_id) = ?', [$id])->first();
    $record = null;
    if ($recordDb) {
        $record = [
            'id'                  => $recordDb->record_id,
            'title'               => $recordDb->ip_title,
            'type'                => $recordDb->category,
            'owner'               => $recordDb->owner_inventor,
            'campus'              => $recordDb->campus,
            'college'             => $recordDb->college        ?? null,
            'program'             => $recordDb->program        ?? null,
            'class_of_work'       => $recordDb->class_of_work  ?? null,
            'date_creation'       => $recordDb->date_creation  ?? null,
            'status'              => $recordDb->status,
            'registered'          => $recordDb->date_registered_deposited,
            'registration_number' => $recordDb->registration_number,
            'gdrive_link'         => $recordDb->gdrive_link,
            'remarks'             => $recordDb->remarks,
        ];
    }
    $user = (object)[
        'name' => 'Guest Viewer',
        'role' => 'Guest',
    ];
    $recordId = $id;
    return view('guestrecorddetail', compact('user', 'record', 'recordId'));
});

Route::get('/calendar', function () {
    $user = (object)[
        'name'         => session('user_name', 'KTTM User'),
        'role'         => session('user_role', 'staff'),
        'avatar_color' => session('user_avatar_color', '#A52C30'),
    ];

    $allRecords = IpRecord::orderByDesc('date_registered_deposited')
        ->get()
        ->map(fn($r) => [
            'id'         => $r->record_id,
            'title'      => $r->ip_title,
            'type'       => $r->category,
            'status'     => $r->status,
            'registered' => $r->date_registered_deposited,
        ])
        ->toArray();

    // Load ALL tasks so the calendar JS can render chips without extra API calls
    $allTasks = DB::table('calendar_tasks')
        ->orderBy('task_date')
        ->get()
        ->map(fn($t) => [
            'id'         => $t->id,
            'title'      => $t->title,
            'task_date'  => $t->task_date,
            'category'   => $t->category,
            'status'     => $t->status,
            'author'     => $t->author,
            'notes'      => $t->notes,
        ])
        ->toArray();

    return view('calendar', compact('user', 'allRecords', 'allTasks'));
})->name('calendar');

/* ─────────────────────────────────────────────────────
   CALENDAR TASKS API
   All routes return JSON. CSRF is handled via the
   meta[name=csrf-token] tag already in the blade.
───────────────────────────────────────────────────── */

/**
 * GET /api/calendar-tasks
 * Fetch tasks filtered by ?date=YYYY-MM-DD  OR  ?month=YYYY-MM
 */
Route::get('/api/calendar-tasks', function (Request $request) {
    $query = DB::table('calendar_tasks');

    if ($request->filled('date')) {
        $query->whereDate('task_date', $request->query('date'));
    } elseif ($request->filled('month')) {
        // expects YYYY-MM
        [$y, $m] = explode('-', $request->query('month'));
        $query->whereYear('task_date', (int)$y)
              ->whereMonth('task_date', (int)$m);
    }

    $tasks = $query->orderBy('task_date')->get();

    return response()->json(['tasks' => $tasks]);
});

/**
 * POST /api/calendar-tasks
 * Create a new task
 * Body: { title, task_date, category, author?, notes? }
 */
Route::post('/api/calendar-tasks', function (Request $request) {
    $data = $request->validate([
        'title'     => 'required|string|max:255',
        'task_date' => 'required|date',
        'category'  => 'required|in:deadline,registration,review,submission',
        'author'    => 'nullable|string|max:255',
        'notes'     => 'nullable|string',
    ]);

    $id = DB::table('calendar_tasks')->insertGetId([
        'title'      => $data['title'],
        'task_date'  => $data['task_date'],
        'category'   => $data['category'],
        'status'     => 'pending',
        'author'     => $data['author'] ?? null,
        'notes'      => $data['notes'] ?? null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $task = DB::table('calendar_tasks')->find($id);

    return response()->json([
        'success' => true,
        'message' => 'Task saved successfully.',
        'task'    => $task,
    ], 201);
});

/**
 * PATCH /api/calendar-tasks/{id}/done
 * Mark a task as done
 */
Route::patch('/api/calendar-tasks/{id}/done', function (Request $request, $id) {
    $affected = DB::table('calendar_tasks')
        ->where('id', (int)$id)
        ->update([
            'status'     => 'done',
            'updated_at' => now(),
        ]);

    if (!$affected) {
        return response()->json(['success' => false, 'message' => 'Task not found.'], 404);
    }

    return response()->json(['success' => true, 'message' => 'Task marked as done.']);
});

/**
 * PATCH /api/calendar-tasks/{id}/status
 * Update status to any valid value: pending | done | in_progress | cancelled
 */
Route::patch('/api/calendar-tasks/{id}/status', function (Request $request, $id) {
    $data = $request->validate([
        'status' => 'required|in:pending,done,in_progress,cancelled',
    ]);

    $affected = DB::table('calendar_tasks')
        ->where('id', (int)$id)
        ->update([
            'status'     => $data['status'],
            'updated_at' => now(),
        ]);

    if (!$affected) {
        return response()->json(['success' => false, 'message' => 'Task not found.'], 404);
    }

    return response()->json(['success' => true, 'message' => 'Task status updated.']);
});

/**
 * DELETE /api/calendar-tasks/{id}
 * Delete a task permanently
 */
Route::delete('/api/calendar-tasks/{id}', function (Request $request, $id) {
    $affected = DB::table('calendar_tasks')
        ->where('id', (int)$id)
        ->delete();

    if (!$affected) {
        return response()->json(['success' => false, 'message' => 'Task not found.'], 404);
    }

    return response()->json(['success' => true, 'message' => 'Task deleted.']);
});

/**
 * ✅ LANDING PAGE
 */
Route::get('/', function (Request $request) {
    // Optional filters via query string: ?year=2025&month=3
    $year  = $request->query('year');
    $month = $request->query('month');

    $baseQuery = function ($q) use ($year, $month) {
        if ($year) {
            $q->whereYear('date_registered_deposited', $year);
        }
        if ($month) {
            $q->whereMonth('date_registered_deposited', $month);
        }
        return $q;
    };

    // Get IP type counts from database (optionally filtered by year)
    $patentCount    = $baseQuery(IpRecord::where('category', 'Patent'))->count();
    $copyrightCount = $baseQuery(IpRecord::where('category', 'Copyright'))->count();
    $utilityCount   = $baseQuery(IpRecord::where('category', 'Utility Model'))->count();
    $designCount    = $baseQuery(IpRecord::where('category', 'Industrial Design'))->count();
    $totalCount     = $baseQuery(IpRecord::query())->count();

    // Calculate percentages
    $total = $patentCount + $copyrightCount + $utilityCount + $designCount;
    $patentPercent    = $total > 0 ? round(($patentCount / $total) * 100) : 0;
    $copyrightPercent = $total > 0 ? round(($copyrightCount / $total) * 100) : 0;
    $utilityPercent   = $total > 0 ? round(($utilityCount / $total) * 100) : 0;
    $designPercent    = $total > 0 ? round(($designCount / $total) * 100) : 0;

    // Build available years list for selector (Postgres-compatible)
    $years = IpRecord::whereNotNull('date_registered_deposited')
        ->selectRaw("EXTRACT(YEAR FROM date_registered_deposited)::int as y")
        ->distinct()
        ->orderByDesc('y')
        ->pluck('y')
        ->map(fn($y) => (int)$y)
        ->toArray();

    $stats = [
        'active'    => $totalCount,
        'patent'    => ['count' => $patentCount, 'percent' => $patentPercent],
        'copyright' => ['count' => $copyrightCount, 'percent' => $copyrightPercent],
        'utility'   => ['count' => $utilityCount, 'percent' => $utilityPercent],
        'design'    => ['count' => $designCount, 'percent' => $designPercent],
    ];

    // if this is an AJAX request (frontend filter), return just the raw numbers
    if ($request->ajax() || $request->query('ajax')) {
        return response()->json($stats);
    }

    // Fetch scheduled maintenance time to show banner on landing page
    $scheduledAt = null;
    try {
        $schedRow = DB::table('system_settings')->where('key', 'scheduled_at')->first();
        if ($schedRow && !empty($schedRow->value)) {
            $parsed = \Carbon\Carbon::parse($schedRow->value);
            if ($parsed->isFuture()) {
                $scheduledAt = $parsed->toIso8601String();
            }
        }
    } catch (\Exception $e) {
        // system_settings table not ready — silently skip
    }

    // send selected month back to view so the dropdown can pick it up
    return view('welcome', [
        'stats'         => $stats,
        'years'         => $years,
        'selectedYear'  => $year,
        'selectedMonth' => $month,
        'scheduledAt'   => $scheduledAt,
    ]);
});

/**
 * ✅ HOME / DASHBOARD
 * Updated: now fetches today's tasks from calendar_tasks
 */
Route::get('/home', function (
    \Illuminate\Http\Request $request
) use ($maintenanceGuard, $trackPresence) {

    // Maintenance / debug mode block
    if ($redir = $maintenanceGuard($request)) return $redir;

    // Record this admin's presence (5-min online window)
    $trackPresence($request, 'admin');

    // allow optional calendar month/year via query string
    $now = \Carbon\Carbon::now();
    $calMonth = (int) $request->query('month', $now->month);
    $calYear  = (int) $request->query('year',  $now->year);

    // clamp values to reasonable range
    if ($calMonth < 1 || $calMonth > 12) {
        $calMonth = $now->month;
    }
    if ($calYear < 1900 || $calYear > ($now->year + 10)) {
        $calYear = $now->year;
    }

    $kpis = [
        'my_open'         => IpRecord::where('status', '!=', 'Registered')->count(),
        'needs_attention' => IpRecord::where('status', 'Close to Expiration')->count(),
        'due_soon'        => IpRecord::where('status', 'Close to Expiration')->count(),
        'total_records'   => IpRecord::count(),
    ];

    $recent = IpRecord::orderByDesc('date_registered_deposited')
        ->limit(10)
        ->get()
        ->map(function ($r) {
            return [
                'id'      => $r->record_id,
                'title'   => $r->ip_title,
                'type'    => $r->category,
                'status'  => $r->status,
                'updated' => $r->date_registered_deposited,
            ];
        })
        ->toArray();

    $allRecords = IpRecord::orderByDesc('date_registered_deposited')
        ->get()
        ->map(function ($r) {
            return [
                'id'         => $r->record_id,
                'title'      => $r->ip_title,
                'type'       => $r->category,
                'owner'      => $r->owner_inventor,
                'campus'     => $r->campus,
                'status'     => $r->status,
                'registered' => $r->date_registered_deposited,
                'registration_number'  => $r->registration_number,
                'gdrive_link'=> $r->gdrive_link,
                'remarks'    => $r->remarks,
            ];
        })
        ->toArray();

    // ── Status-specific record sets for the Status Breakdown popup ──
    $statusFields = fn($r) => [
        'id'                  => $r->record_id,
        'title'               => $r->ip_title,
        'type'                => $r->category,
        'owner'               => $r->owner_inventor,
        'campus'              => $r->campus,
        'college'             => $r->college,
        'program'             => $r->program,
        'class_of_work'       => $r->class_of_work,
        'date_creation'       => $r->date_creation,
        'status'              => $r->status,
        'registered'          => $r->date_registered_deposited,
        'registration_number' => $r->registration_number,
        'gdrive_link'         => $r->gdrive_link,
        'remarks'             => $r->remarks,
    ];

    $unregisteredRecords  = IpRecord::where('status', 'Unregistered')
        ->orderByDesc('date_registered_deposited')->get()->map($statusFields)->toArray();

    $underReviewRecords   = IpRecord::where('status', 'Under Review')
        ->orderByDesc('date_registered_deposited')->get()->map($statusFields)->toArray();

    $recentlyFiledRecords = IpRecord::where('status', 'Recently Filed')
        ->orderByDesc('date_registered_deposited')->get()->map($statusFields)->toArray();

    // ── Fetch today's tasks from calendar_tasks for the notification bell ──
    $todayTasks = DB::table('calendar_tasks')
        ->whereDate('task_date', $now->toDateString())
        ->orderBy('status') // pending first
        ->get()
        ->map(fn($t) => [
            'id'        => $t->id,
            'title'     => $t->title,
            'task_date' => $t->task_date,
            'category'  => $t->category,
            'status'    => $t->status,
            'author'    => $t->author,
            'notes'     => $t->notes,
        ])
        ->toArray();

    $scheduledAt = null;
    try {
        $schedRow = DB::table('system_settings')->where('key', 'scheduled_at')->first();
        if ($schedRow && !empty($schedRow->value)) {
            $parsed = \Carbon\Carbon::parse($schedRow->value);
            if ($parsed->isFuture()) {
                $scheduledAt = $parsed->toIso8601String();
            }
        }
    } catch (\Exception $e) {
        // system_settings table not ready — silently skip
    }

    $user = (object)[
        'name'         => session('user_name', 'KTTM User'),
        'role'         => session('user_role', 'staff'),
        'avatar_color' => session('user_avatar_color', '#A52C30'),
    ];

    return view('home', compact(
        'user', 'kpis', 'recent', 'allRecords',
        'calMonth', 'calYear', 'todayTasks',
        'unregisteredRecords', 'underReviewRecords', 'recentlyFiledRecords',
        'scheduledAt'
    ));
})->name('home');

// AJAX endpoint for calendar partial – returns rendered table only
Route::get('/home/calendar', function (\Illuminate\Http\Request $request) {
    $now = \Carbon\Carbon::now();
    $calMonth = (int) $request->query('month', $now->month);
    $calYear  = (int) $request->query('year',  $now->year);
    if ($calMonth < 1 || $calMonth > 12) {
        $calMonth = $now->month;
    }
    if ($calYear < 1900 || $calYear > ($now->year + 10)) {
        $calYear = $now->year;
    }
    return view('partials.home_calendar', compact('calMonth','calYear'));
});

/**
 * ✅ RECORDS PAGE (shared URI for guests & authenticated users)
 */
Route::get('/ip-records', function () {

    $allRecords = IpRecord::orderByDesc('date_registered_deposited')
        ->get()
        ->map(function ($r) {
            return [
                'id'                  => $r->record_id,
                'title'               => $r->ip_title,
                'type'                => $r->category,
                'owner'               => $r->owner_inventor,
                'owner_inventor'      => $r->owner_inventor,
                'campus'              => $r->campus,
                'college'             => $r->college,
                'program'             => $r->program,
                'class_of_work'       => $r->class_of_work,
                'date_creation'       => $r->date_creation,
                'status'              => $r->status,
                'registered'          => $r->date_registered_deposited,
                'registration_number' => $r->registration_number,
                'gdrive_link'         => $r->gdrive_link,
                'remarks'             => $r->remarks,
            ];
        })
        ->toArray();

    $campuses = collect($allRecords)->pluck('campus')->filter()->unique()->sort()->values()->all();
    $types    = collect($allRecords)->pluck('type')->filter()->unique()->sort()->values()->all();
    $statuses = collect($allRecords)->pluck('status')->filter()->unique()->sort()->values()->all();
    $colleges = IpRecord::select('college')->whereNotNull('college')->distinct()->pluck('college')->filter()->sort()->values()->all();
    $programs = IpRecord::select('program')->whereNotNull('program')->distinct()->pluck('program')->filter()->sort()->values()->all();

    // if the visitor is authenticated we show the full records page
    if (\Illuminate\Support\Facades\Auth::check()) {
        $user = (object)[
            'name'         => session('user_name', 'KTTM User'),
            'role'         => session('user_role', 'staff'),
        'avatar_color' => session('user_avatar_color', '#A52C30'),
        ];
        return view('records', compact('user', 'allRecords', 'campuses', 'types', 'statuses', 'colleges', 'programs'));
    }

    // guest experience: render guest-specific view
    return view('guestrecords', compact('allRecords', 'campuses', 'types', 'statuses', 'colleges', 'programs'));
})->name('records');


/**
 * ✅ STAFF-ONLY RECORDS ROUTE
 */
Route::get('/records', function () {
    $perPage = 100;
    $recordsPage = IpRecord::orderByRaw("CAST(SUBSTRING(record_id FROM '[0-9]+$') AS INTEGER) ASC")
        ->limit($perPage)
        ->get();

    $allRecords = $recordsPage
        ->map(fn($r) => [
            'id'                  => $r->record_id,
            'title'               => $r->ip_title,
            'type'                => $r->category,
            'owner'               => $r->owner_inventor,
            'campus'              => $r->campus,
            'college'             => $r->college,
            'program'             => $r->program,
            'class_of_work'       => $r->class_of_work,
            'date_creation'       => $r->date_creation,
            'status'              => $r->status,
            'registered'          => $r->date_registered_deposited,
            'registration_number' => $r->registration_number,
            'gdrive_link'         => $r->gdrive_link,
            'remarks'             => $r->remarks,
        ])
        ->toArray();

    // derive complete filter lists from the DB
    $campuses = IpRecord::select('campus')->whereNotNull('campus')->distinct()->pluck('campus')->filter()->sort()->values()->all();
    $types    = collect(
                    IpRecord::select('category')->whereNotNull('category')->distinct()->pluck('category')->all()
                )->merge(['Copyright', 'Industrial Design', 'Patent', 'Trademark', 'Utility Model'])
                 ->unique()->sort()->values()->all();
    $statuses = IpRecord::select('status')->whereNotNull('status')->distinct()->pluck('status')->filter()->sort()->values()->all();
    $colleges = IpRecord::select('college')->whereNotNull('college')->distinct()->pluck('college')->filter()->sort()->values()->all();
    $programs = IpRecord::select('program')->whereNotNull('program')->distinct()->pluck('program')->filter()->sort()->values()->all();

    $user = (object)[
        'name'         => session('user_name', 'KTTM User'),
        'role'         => session('user_role', 'staff'),
        'avatar_color' => session('user_avatar_color', '#A52C30'),
    ];

    return view('records', compact('user', 'allRecords', 'campuses', 'types', 'statuses', 'colleges', 'programs'));
})->name('records.staff');

// export records as CSV (optional date filter)
Route::get('/records/export', function (Request $request) {
    $query = App\Models\IpRecord::orderByDesc('date_registered_deposited');
    $hasDateFilter = false;
    $start = null;
    $end = null;

    if ($request->filled('start') && $request->filled('end')) {
        $start = $request->input('start');
        $end   = $request->input('end');
        $hasDateFilter = true;
        try {
            $query->whereDate('date_registered_deposited', '>=', $start)
                  ->whereDate('date_registered_deposited', '<=', $end);
        } catch (\Exception $e) {
            $hasDateFilter = false;
        }
    }
    $records = $query->get();

    $columns = ['Record ID','IP Title','Category','Owner','Campus','Status','Date Registered','IPOPHL ID','GDrive Link','Remarks'];
    $callback = function () use ($records, $columns) {
        $out = fopen('php://output', 'w');
        fputcsv($out, $columns);
        foreach ($records as $r) {
            fputcsv($out, [
                $r->record_id,
                $r->ip_title,
                $r->category,
                $r->owner_inventor,
                $r->campus,
                $r->status,
                $r->date_registered_deposited,
                $r->registration_number,
                $r->gdrive_link,
                $r->remarks,
            ]);
        }
        fclose($out);
    };

    if ($hasDateFilter && $start && $end) {
        $filename = 'KTTM_Records_' . $start . '_to_' . $end . '.csv';
    } else {
        $filename = 'KTTM_Full_Records.csv';
    }

    return response()->stream($callback, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ]);
})->name('records.export');

// API: paginated records for client-side rendering (filters supported)
Route::get('/api/records', function (Request $request) {
    $page = max(1, (int) $request->query('page', 1));
    $perPage = (int) $request->query('per_page', 100);
    if ($perPage < 5) $perPage = 5;
    if ($perPage > 500) $perPage = 500;

    $query = App\Models\IpRecord::query();

    if ($request->filled('q')) {
        $q = trim(strtolower($request->query('q')));
        $query->whereRaw('LOWER(ip_title) LIKE ?', ["%{$q}%"])
              ->orWhereRaw('LOWER(owner_inventor) LIKE ?', ["%{$q}%"]);
    }

    if ($request->filled('type'))    { $query->where('category', $request->query('type')); }
    if ($request->filled('status'))  { $query->where('status', $request->query('status')); }
    if ($request->filled('campus'))  { $query->where('campus', $request->query('campus')); }
    if ($request->filled('college')) { $query->where('college', $request->query('college')); }
    if ($request->filled('program')) { $query->where('program', $request->query('program')); }
    if ($request->filled('start'))   { $query->whereDate('date_registered_deposited', '>=', $request->query('start')); }
    if ($request->filled('end'))     { $query->whereDate('date_registered_deposited', '<=', $request->query('end')); }

    $paginated = $query->orderByRaw("CAST(SUBSTRING(record_id FROM '[0-9]+$') AS INTEGER) ASC")
        ->paginate($perPage, ['*'], 'page', $page);

    return response()->json($paginated);
})->name('api.records');

/**
 * ✅ NEW RECORD FORM PAGE
 */
Route::get('/ipassets/create', function () {

    $allRecords = IpRecord::orderByDesc('date_registered_deposited')
        ->get()
        ->map(function ($r) {
            return [
                'id'         => $r->record_id,
                'title'      => $r->ip_title,
                'type'       => $r->category,
                'owner'      => $r->owner_inventor,
                'campus'     => $r->campus,
                'status'     => $r->status,
                'registered' => $r->date_registered_deposited,
                'registration_number'  => $r->registration_number,
                'gdrive_link'=> $r->gdrive_link,
                'remarks'    => $r->remarks,
            ];
        })
        ->toArray();

    $campuses = collect($allRecords)->pluck('campus')->filter()->unique()->sort()->values()->all();
    $types    = ['Copyright', 'Industrial Design', 'Patent', 'Trademark', 'Utility Model'];
    $statuses = ['Recently Filed', 'Registered', 'Unregistered', 'Under Review', 'Needs Attention', 'Returned'];

    $user = (object)[
        'name'         => session('user_name', 'KTTM User'),
        'role'         => session('user_role', 'staff'),
        'avatar_color' => session('user_avatar_color', '#A52C30'),
    ];

    $nextNumber = 1;
    $allForId = IpRecord::where('record_id', 'like', 'KTTM-%')->get();
    foreach ($allForId as $record) {
        if (preg_match('/KTTM-(\d+)$/', $record->record_id, $m)) {
            $num = (int)$m[1];
            if ($num >= $nextNumber) {
                $nextNumber = $num + 1;
            }
        }
    }
    $nextRecordId = 'KTTM-' . str_pad((string)$nextNumber, 3, '0', STR_PAD_LEFT);

    return view('Newrecord', compact('user', 'campuses', 'types', 'statuses', 'nextRecordId'));
})->name('ipassets.create');

// API: search existing records by title substring
Route::get('/ipassets/check-title', function (Request $request) {
    $q = trim($request->query('title', ''));
    if ($q === '') {
        return response()->json([]);
    }

    $matches = IpRecord::where('ip_title', 'like', '%' . $q . '%')
        ->orderBy('date_registered_deposited', 'desc')
        ->limit(5)
        ->get(['record_id', 'ip_title'])
        ->map(function ($r) {
            return [
                'record_id' => $r->record_id,
                'ip_title'   => $r->ip_title,
            ];
        });

    return response()->json($matches);
});

/**
 * ✅ STORE NEW RECORD
 */
Route::post('/ipassets', function (Request $request) {

    $data = $request->validate([
        'title'       => 'required|string|max:1024',
        'type'        => 'required|string|max:255',
        'status'      => 'required|string|max:255',
        'campus'      => 'required|string|max:255',
        'registered'  => 'nullable|date',
        'registration_number'   => 'nullable|string|max:255',
        'gdrive_link' => 'nullable|url|max:2048',
        'remarks'     => 'nullable|string',
        'inventors'   => 'nullable|json',
    ]);

    if (!empty($data['title'])) {
        $exists = IpRecord::whereRaw('LOWER(ip_title) = ?', [strtolower($data['title'])])->exists();
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['title' => 'A record with this title already exists. Please verify or edit the existing record.']);
        }
    }

    return DB::transaction(function () use ($request, $data) {

        $allRecords = IpRecord::where('record_id', 'like', 'KTTM-%')
            ->lockForUpdate()
            ->get();

        $nextNumber = 1;
        foreach ($allRecords as $record) {
            if (preg_match('/KTTM-(\d+)$/', $record->record_id, $m)) {
                $num = (int)$m[1];
                if ($num >= $nextNumber) {
                    $nextNumber = $num + 1;
                }
            }
        }
        $recordId = 'KTTM-' . str_pad((string)$nextNumber, 3, '0', STR_PAD_LEFT);

        $ownerSummary = null;
        $invArr = [];

        if (!empty($data['inventors'])) {
            $invArr = json_decode($data['inventors'], true);
            if (is_array($invArr)) {
                $names = [];
                foreach ($invArr as $item) {
                    $n = is_array($item) ? trim($item['name'] ?? '') : trim((string)$item);
                    if ($n !== '') $names[] = $n;
                }
                $ownerSummary = implode('; ', $names);
            } else {
                $invArr = [];
            }
        }

        $record = new IpRecord();
        $record->record_id              = $recordId;
        $record->ip_title               = $data['title'];
        $record->category               = $data['type'];
        $record->owner_inventor = $ownerSummary ?? ($request->input('owner') ?? '');
        $record->campus                 = $data['campus'];
        $record->status                 = $data['status'];
        $record->date_registered_deposited = $data['registered'] ?? null;
        $record->registration_number              = $data['registration_number'] ?? null;
        $record->gdrive_link            = $data['gdrive_link'] ?? null;
        $record->remarks                = $data['remarks'] ?? null;
        $record->save();

        if (!empty($invArr) && is_array($invArr)) {

            $rows = [];
            foreach ($invArr as $item) {
                $name   = is_array($item) ? trim($item['name']   ?? '') : trim((string)$item);
                $gender = is_array($item) ? trim($item['gender'] ?? '') : '';

                if ($name === '') continue;

                $rows[] = [
                    'record_id'        => $recordId,
                    'contributor_name' => $name,
                    'gender'           => $gender !== '' ? ucfirst(strtolower($gender)) : null,
                ];
            }

            if (!empty($rows)) {
                DB::table('ip_contributors')->insert($rows);
            }
        }

        return redirect()->route('records.staff')
            ->with('success', "Record created successfully: {$recordId}");
    });
})->name('ipassets.store');

/**
 * ✅ UPDATE RECORD
 */
Route::post('/records/{id}/update', function (Request $request, $id) {

    $id = trim(urldecode($id));
    $record = IpRecord::whereRaw('TRIM(record_id) = ?', [$id])->first();

    if (!$record) {
        return response()->json([
            'success' => false,
            'message' => "Record not found: {$id}",
        ], 404);
    }

    $changes = [];

    if ($request->has('title')) {
        $old = $record->ip_title; $new = $request->input('title');
        if ($old !== $new) $changes['Title'] = ['old' => $old, 'new' => $new];
        $record->ip_title = $new;
    }
    if ($request->has('type')) {
        $old = $record->category; $new = $request->input('type');
        if ($old !== $new) $changes['Category'] = ['old' => $old, 'new' => $new];
        $record->category = $new;
    }
    if ($request->has('owner')) {
        $old = $record->owner_inventor; $new = $request->input('owner');
        if ($old !== $new) $changes['Owner'] = ['old' => $old, 'new' => $new];
        $record->owner_inventor = $new;
    }
    if ($request->has('campus')) {
        $old = $record->campus; $new = $request->input('campus');
        if ($old !== $new) $changes['Campus'] = ['old' => $old, 'new' => $new];
        $record->campus = $new;
    }
    if ($request->has('status')) {
        $old = $record->status; $new = $request->input('status');
        if ($old !== $new) $changes['Status'] = ['old' => $old, 'new' => $new];
        $record->status = $new;
    }
    if ($request->has('registration_number')) {
        $old = $record->registration_number; $new = $request->input('registration_number');
        if ($old !== $new) $changes['IPOPhl ID'] = ['old' => $old, 'new' => $new];
        $record->registration_number = $new;
    }
    if ($request->has('gdrive_link')) {
        $old = $record->gdrive_link; $new = $request->input('gdrive_link');
        if ($old !== $new) $changes['GDrive Link'] = ['old' => $old, 'new' => $new];
        $record->gdrive_link = $new;
    }
    if ($request->has('remarks')) {
        $old = $record->remarks; $new = $request->input('remarks');
        if ($old !== $new) $changes['Remarks'] = ['old' => $old, 'new' => $new];
        $record->remarks = $new;
    }
    if ($request->filled('registered')) {
        $old = $record->date_registered_deposited; $new = $request->input('registered');
        if ($old !== $new) $changes['Date Registered'] = ['old' => $old, 'new' => $new];
        $record->date_registered_deposited = $new;
    }

    $record->save();

    if (!empty($changes)) {
        try {
            \App\Models\ActivityLog::create([
                'record_id'    => $record->record_id,
                'record_title' => $record->ip_title,
                'action'       => 'Modified',
                'changes'      => $changes,
                'user_name'    => session('user_name', 'KTTM User'),
            ]);
        } catch (\Exception $e) {
            \Log::warning('Failed to log activity: ' . $e->getMessage());
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Record updated successfully',
        'id'      => $record->record_id,
    ]);
})->name('records.update');


/**
 * ✅ RECORD CHANGES PAGE
 */
Route::get('/record-changes/{id}', function (Request $request, $id) {
    $id = trim(urldecode($id));

    $recordDb = IpRecord::whereRaw('TRIM(record_id) = ?', [$id])->first();
    $recordArr = null;
    if ($recordDb) {
        $recordArr = [
            'id'                  => $recordDb->record_id,
            'title'               => $recordDb->ip_title,
            'type'                => $recordDb->category,
            'owner'               => $recordDb->owner_inventor,
            'campus'              => $recordDb->campus,
            'college'             => $recordDb->college,
            'program'             => $recordDb->program,
            'class_of_work'       => $recordDb->class_of_work,
            'date_creation'       => $recordDb->date_creation,
            'status'              => $recordDb->status,
            'registration_number' => $recordDb->registration_number,
            'registered'          => $recordDb->date_registered_deposited,
            'gdrive_link'         => $recordDb->gdrive_link,
            'remarks'             => $recordDb->remarks,
        ];
    }

    $user = (object)[
        'name'         => session('user_name', 'KTTM User'),
        'role'         => session('user_role', 'staff'),
        'avatar_color' => session('user_avatar_color', '#A52C30'),
    ];

    return view('Modifiedrecords', [
        'user'     => $user,
        'record'   => $recordArr,
        'recordId' => $id,
    ]);
});


/**
 * ✅ API: record change history for a specific record id
 */
Route::get('/api/records/{id}/changes', function (Request $request, $id) {
    $id = trim(urldecode($id));

    $recordDb = IpRecord::whereRaw('TRIM(record_id) = ?', [$id])->first();
    $recordData = null;
    if ($recordDb) {
        $recordData = [
            'id'                  => $recordDb->record_id,
            'title'               => $recordDb->ip_title,
            'type'                => $recordDb->category,
            'owner'               => $recordDb->owner_inventor,
            'campus'              => $recordDb->campus,
            'college'             => $recordDb->college,
            'program'             => $recordDb->program,
            'class_of_work'       => $recordDb->class_of_work,
            'date_creation'       => $recordDb->date_creation,
            'status'              => $recordDb->status,
            'registration_number' => $recordDb->registration_number,
            'registered'          => $recordDb->date_registered_deposited,
            'gdrive_link'         => $recordDb->gdrive_link,
            'remarks'             => $recordDb->remarks,
        ];
    }

    $events = collect();
    try {
        $events = \App\Models\ActivityLog::where('record_id', $id)
            ->orderBy('created_at', 'desc')
            ->get(['action', 'changes', 'created_at', 'user_name', 'record_title'])
            ->map(function ($log) {
                return [
                    'action'    => $log->action,
                    'timestamp' => $log->created_at->toIso8601String(),
                    'actor'     => $log->user_name ?? null,
                    'changes'   => $log->changes ?? [],
                    'summary'   => $log->record_title ?? null,
                ];
            });
    } catch (\Exception $e) {
        \Log::info('Error fetching activity logs for changes API: ' . $e->getMessage());
    }

    return response()->json([
        'record' => $recordData,
        'events' => $events,
    ]);
});

/**
 * ✅ GUEST ACCESS
 */
Route::match(['get', 'post'], '/guest', function () use ($maintenanceGuard, $trackPresence) {
    $request = request();
    if ($redir = $maintenanceGuard($request)) return $redir;

    // Record this guest's presence (5-min online window)
    $trackPresence($request, 'guest');

    $records = IpRecord::orderByDesc('date_registered_deposited')
        ->get()
        ->map(function ($r) {
            return [
                'id'         => $r->record_id,
                'title'      => $r->ip_title,
                'type'       => $r->category,
                'owner'      => $r->owner_inventor,
                'campus'     => $r->campus,
                'status'     => $r->status,
                'registered' => $r->date_registered_deposited,
                'registration_number'  => $r->registration_number,
                'gdrive_link'=> $r->gdrive_link,
                'remarks'    => $r->remarks,
            ];
        })
        ->toArray();

    $guest = (object)[
        'name' => 'Guest Viewer',
        'role' => 'Guest',
    ];

    $collection      = collect($records);
    $totalRecords    = $collection->count();
    $pendingCount    = $collection->filter(fn($r) => strtolower($r['status'] ?? '') === 'pending')->count();
    $registeredCount = $collection->filter(fn($r) => strtolower($r['status'] ?? '') === 'registered')->count();
    $campusCount     = $collection->pluck('campus')->filter()->unique()->count();

    return view('guest', compact('guest', 'records', 'totalRecords', 'pendingCount', 'registeredCount', 'campusCount'));
});

Route::get('/how-to-file', function () { return view('Howtofile'); });
Route::get('/about', function () { return view('about'); });

Route::get('/guest/records', function () use ($trackPresence) {
    // Record this guest's presence (5-min online window)
    $trackPresence(request(), 'guest');
    $allRecords = IpRecord::orderByDesc('date_registered_deposited')
        ->get()
        ->map(function ($r) {
            return [
                'id'                  => $r->record_id,
                'title'               => $r->ip_title,
                'type'                => $r->category,
                'owner'               => $r->owner_inventor,
                'campus'              => $r->campus,
                'college'             => $r->college,
                'program'             => $r->program,
                'class_of_work'       => $r->class_of_work,
                'date_creation'       => $r->date_creation,
                'status'              => $r->status,
                'registered'          => $r->date_registered_deposited,
                'registration_number' => $r->registration_number,
                'gdrive_link'         => $r->gdrive_link,
                'remarks'             => $r->remarks,
            ];
        })
        ->toArray();

    $campuses = collect($allRecords)->pluck('campus')->filter()->unique()->sort()->values()->all();
    $types    = collect($allRecords)->pluck('type')->filter()->unique()->sort()->values()->all();
    $statuses = collect($allRecords)->pluck('status')->filter()->unique()->sort()->values()->all();
    $colleges = IpRecord::select('college')->whereNotNull('college')->distinct()->pluck('college')->filter()->sort()->values()->all();
    $programs = IpRecord::select('program')->whereNotNull('program')->distinct()->pluck('program')->filter()->sort()->values()->all();

    $user = (object)[
        'name' => 'Guest Viewer',
        'role' => 'Guest',
    ];

    $recent = IpRecord::orderByDesc('date_registered_deposited')
        ->limit(10)
        ->get()
        ->map(function ($r) {
            return [
                'id'      => $r->record_id,
                'title'   => $r->ip_title,
                'type'    => $r->category,
                'status'  => $r->status,
                'updated' => $r->date_registered_deposited,
            ];
        })
        ->toArray();

    return view('guestrecords', compact('user', 'allRecords', 'campuses', 'types', 'statuses', 'colleges', 'programs', 'recent'));
})->name('guest.records');

Route::get('/insights', function () {
    $allRecords = IpRecord::orderByDesc('date_registered_deposited')
        ->get()
        ->map(function ($r) {
            return [
                'id'         => $r->record_id,
                'title'      => $r->ip_title,
                'type'       => $r->category,
                'category'   => $r->category,
                'owner'      => $r->owner_inventor,
                'campus'     => $r->campus,
                'status'     => $r->status,
                'registered' => $r->date_registered_deposited,
                'registration_number'  => $r->registration_number,
                'gdrive_link'=> $r->gdrive_link,
                'remarks'    => $r->remarks,
            ];
        })
        ->toArray();

    $user = (object)[
        'name'         => session('user_name', 'KTTM User'),
        'role'         => session('user_role', 'staff'),
        'avatar_color' => session('user_avatar_color', '#A52C30'),
    ];

    return view('insights', compact('user', 'allRecords'));
})->name('insights');

Route::get('/records/{id}/print', function (\Illuminate\Http\Request $request, $id) {
    if (!$request->session()->has('user_id')) {
        return redirect('/')->with('login_error', 'Please sign in first.');
    }

    $id = trim(urldecode($id));

    $record = \App\Models\IpRecord::whereRaw('TRIM(record_id) = ?', [$id])->first();

    if (!$record) {
        abort(404, 'Record not found.');
    }

    return view('prints.Printrecord', compact('record'));
})->name('records.print');


/**
 * ✅ API: RECENT UPDATES
 */
Route::get('/api/recent-updates', function (Request $request) {
    try {
        $activityUpdates = collect();

        try {
            $activityUpdates = \App\Models\ActivityLog::orderBy('created_at', 'desc')
                ->limit(50)
                ->get(['id','record_id', 'record_title', 'action', 'changes', 'created_at'])
                ->map(function ($log) {
                    return [
                        'log_id'         => $log->id,
                        'record_id'      => $log->record_id,
                        'record_title'   => $log->record_title,
                        'action'         => $log->action,
                        'changes'        => $log->changes ?? [],
                        'timestamp'      => $log->created_at->toIso8601String(),
                    ];
                });
        } catch (\Exception $e) {
            \Log::info('ActivityLog table not ready: ' . $e->getMessage());
        }

        $createdRecords = IpRecord::whereNotNull('date_registered_deposited')
            ->orderBy('date_registered_deposited', 'desc')
            ->limit(50)
            ->get(['record_id', 'ip_title', 'category', 'date_registered_deposited'])
            ->map(function ($r) {
                $date = \Carbon\Carbon::parse($r->date_registered_deposited);
                return [
                    'log_id'         => null,
                    'record_id'      => $r->record_id,
                    'record_title'   => $r->ip_title,
                    'record_type'    => $r->category,
                    'action'         => 'Created',
                    'changes'        => [],
                    'timestamp'      => $date->toIso8601String(),
                ];
            });

        $allUpdates = $activityUpdates->merge($createdRecords)
            ->sortByDesc('timestamp')
            ->take(20)
            ->values();

        return response()->json(['updates' => $allUpdates]);
    } catch (\Exception $e) {
        \Log::error('Recent updates API error: ' . $e->getMessage());
        return response()->json(['updates' => [], 'error' => $e->getMessage()], 500);
    }
});