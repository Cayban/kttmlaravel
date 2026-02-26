@php
    $calMonth = $calMonth ?? \Carbon\Carbon::now()->month;
    $calYear  = $calYear ?? \Carbon\Carbon::now()->year;
    $today    = \Carbon\Carbon::create($calYear, $calMonth, 1);
    // ensure week runs Sunday–Saturday to match header labels
    $start = $today->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::SUNDAY);
    $end   = $today->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);
    $date  = $start->copy();
    $weekdays = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
@endphp

<div id="calendarLegend" class="mb-1 text-xs">
    <!-- color entries will be injected here -->
</div>
<div id="calendarNotePreview" class="text-sm text-[color:var(--muted)]"></div>

<table class="w-full text-xs table-fixed border-collapse">
    <thead>
        <tr>
            @foreach($weekdays as $d)
                <th class="py-1 text-center">{{ $d }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @while($date <= $end)
            <tr>
                @for($i = 0; $i < 7; $i++)
                    <td data-date="{{ $date->toDateString() }}" class="h-10 border p-1 align-top text-center {{ $date->month !== $today->month ? 'text-[color:var(--muted)]' : '' }}">
                        {{ $date->day }}
                    </td>
                    @php $date->addDay(); @endphp
                @endfor
            </tr>
        @endwhile
    </tbody>
</table>