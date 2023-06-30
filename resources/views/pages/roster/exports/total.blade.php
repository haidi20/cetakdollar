@php
    use Carbon\Carbon;
@endphp

<table>
    <tr>
        <td colspan="32" style="font-size: 28px; font-weight: bold; text-align: center; vertical-align: middle;">
            {{-- <img src="{{ public_path('/assets/img/logo.png') }}" alt="" class="" width="80%"> --}}
            <h3 style="margin-top: 10px; padding-left: 30px;">PT KARYA PACIFIC TEKNIK SHIPYARD</h3>
        </td>
    </tr>
    <thead>
        <tr>
            <th rowspan="2" width="15" style="vertical-align: middle" class="fixed-column" nowrap></th>
            @foreach ($dates as $date)
                <th>{{ Carbon::parse($date)->format('d') }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach ($dates as $date)
                <th>{{ Carbon::parse($date)->locale('id')->isoFormat('dddd') }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($positions as $index => $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                @foreach ($dates as $date)
                    @if (isset($data[$item->id]))
                        @php
                            $getTotal = $data[$item->id][$date];
                        @endphp

                        <td style="background-color: {{ $getTotal < $item->minimum_employee ? 'orange' : null }}">
                            {{ $getTotal }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
