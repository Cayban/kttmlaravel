<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Record - {{ $record->record_id ?? 'KTTM Record' }}</title>

    @php
        use Carbon\Carbon;

        $title = trim((string) ($record->ip_title ?? ''));
        $recordId = trim((string) ($record->record_id ?? ''));
        $category = trim((string) ($record->category ?? ''));
        $status = trim((string) ($record->status ?? ''));
        $owner = trim((string) ($record->owner_inventor ?? ''));
        $campus = trim((string) ($record->campus ?? ''));
        $college = trim((string) ($record->college ?? ''));
        $program = trim((string) ($record->program ?? ''));
        $classOfWork = trim((string) ($record->class_of_work ?? ''));
        $registrationNumber = trim((string) ($record->registration_number ?? ''));
        $gdriveLink = trim((string) ($record->gdrive_link ?? ''));
        $remarks = trim((string) ($record->remarks ?? ''));
        $dateCreationRaw = $record->date_creation ?? null;
        $dateRegisteredRaw = $record->date_registered_deposited ?? null;

        $dateCreation = '—';
        $dateRegistered = '—';

        try {
            if (!empty($dateCreationRaw)) {
                $dateCreation = Carbon::parse($dateCreationRaw)->format('F j, Y');
            }
        } catch (\Throwable $e) {
            $dateCreation = !empty($dateCreationRaw) ? (string) $dateCreationRaw : '—';
        }

        try {
            if (!empty($dateRegisteredRaw)) {
                $dateRegistered = Carbon::parse($dateRegisteredRaw)->format('F j, Y');
            }
        } catch (\Throwable $e) {
            $dateRegistered = !empty($dateRegisteredRaw) ? (string) $dateRegisteredRaw : '—';
        }

        $printedAt = now()->format('F j, Y, g:i A');
    @endphp

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background-color: #e5e5e5;
            display: flex;
            justify-content: center;
            padding: 40px;
            margin: 0;
        }

        .page {
            background-color: #ffffff;
            width: 800px;
            padding: 60px 50px 50px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            position: relative;
        }

        /* --- Header --- */
        .header {
            text-align: center;
            margin-bottom: 25px;
            font-family: "Times New Roman", Times, serif;
            color: #000;
        }

        .header h1 { font-size: 26px; margin: 0 0 5px 0; font-weight: bold; }
        .header h2 { font-size: 18px; margin: 0 0 5px 0; font-weight: bold; }
        .header h3 { font-size: 18px; margin: 0 0 5px 0; font-weight: bold; }
        .header h4 { font-size: 18px; margin: 0;          font-weight: bold; }

        /* --- Badges --- */
        .badges {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 15px;
        }

        .badge-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #aaa;
            background: #fff;
        }

        /* --- Table --- */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 10px;
        }

        th, td {
            border: 1px solid #a0a0a0;
            padding: 8px 10px;
            vertical-align: middle;
        }

        .label {
            font-weight: bold;
            width: 25%;
            color: #333;
        }

        .center-label {
            text-align: center;
            font-weight: bold;
            background-color: #f8f8f8;
            width: 20%;
        }

        .center-text { text-align: center; }

        /* --- Section header --- */
        .section-header {
            background-color: #6d747a;
            color: white;
            font-weight: bold;
            font-size: 14px;
            padding: 6px 10px;
        }

        /* --- Link --- */
        .link {
            color: #1155cc;
            text-decoration: none;
            word-break: break-all;
        }

        .link:hover { text-decoration: underline; }

        /* --- Remarks --- */
        .remarks-text {
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* --- Footer --- */
        .footer {
            text-align: right;
            font-size: 11px;
            margin-top: 15px;
            color: #000;
        }

        /* --- Print button --- */
        .no-print {
            position: fixed;
            top: 16px; right: 16px;
            z-index: 9999;
            display: inline-flex; align-items: center; gap: 8px;
            background: #111; color: #fff;
            border: none; border-radius: 8px;
            padding: 10px 14px; font-size: 14px; cursor: pointer;
        }

        .no-print:hover { opacity: 0.9; }

        @media print {
            body { background: #fff; padding: 0; }
            .page { width: 100%; box-shadow: none; padding: 30px 28px 40px; }
            .no-print { display: none !important; }
            @page { size: auto; margin: 10mm; }
        }
    </style>
</head>
<body>
    <button type="button" class="no-print" onclick="window.print()">Print</button>

    <div class="page">
        <div class="header">
            <h1>Batangas State University</h1>
            <h2>The National Engineering University</h2>
            <h3>Knowledge and Technology Transfer Management</h3>
            <h4>Research Management Services</h4>

            <div class="badges" aria-label="University Logos">
                <img src="{{ asset('images/badge-bsu.png') }}"   alt="BSU"  class="badge-img">
                <img src="{{ asset('images/badge-rnis.png') }}"  alt="RNIS" class="badge-img">
                <img src="{{ asset('images/badge-seal.png') }}"  alt="SEAL" class="badge-img">
            </div>
        </div>

        <table aria-label="Study Information Form">
            <tr>
                <td class="label">Title of the Study</td>
                <td colspan="3">{{ $title !== '' ? $title : '—' }}</td>
            </tr>
            <tr>
                <td class="label">ID No.</td>
                <td>{{ $recordId !== '' ? $recordId : '—' }}</td>
                <td class="center-label">Category</td>
                <td class="center-text">{{ $category !== '' ? $category : '—' }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td colspan="3">{{ $status !== '' ? $status : '—' }}</td>
            </tr>
            <tr>
                <td class="label">Author/Owner</td>
                <td colspan="3">{{ $owner !== '' ? $owner : '—' }}</td>
            </tr>
            <tr>
                <td class="label">Campus</td>
                <td>{{ $campus !== '' ? $campus : '—' }}</td>
                <td class="center-label">Date Registered</td>
                <td class="center-text">{{ $dateRegistered }}</td>
            </tr>
            <tr>
                <td class="label">Registered Number</td>
                <td colspan="3">{{ $registrationNumber !== '' ? $registrationNumber : '—' }}</td>
            </tr>

            <tr>
                <td colspan="4" class="section-header">Additional Record Information</td>
            </tr>
            <tr>
                <td class="label">College</td>
                <td colspan="3">{{ $college !== '' ? $college : '—' }}</td>
            </tr>
            <tr>
                <td class="label">Program</td>
                <td colspan="3">{{ $program !== '' ? $program : '—' }}</td>
            </tr>
            <tr>
                <td class="label">Class of Work</td>
                <td colspan="3">{{ $classOfWork !== '' ? $classOfWork : '—' }}</td>
            </tr>
            <tr>
                <td class="label">Date Created</td>
                <td colspan="3">{{ $dateCreation }}</td>
            </tr>
            <tr>
                <td class="label">Certificate / Remarks</td>
                <td colspan="3">
                    @if($remarks !== '')
                        <div class="remarks-text">{{ $remarks }}</div>
                    @else
                        —
                    @endif
                </td>
            </tr>
        </table>

        <div class="footer">Printed: {{ $printedAt }}</div>
    </div>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>