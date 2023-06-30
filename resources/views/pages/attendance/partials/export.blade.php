@php
    use Carbon\Carbon;
@endphp

<table>
    <tr>
        <td colspan="33" style="font-size: 28px; font-weight: bold; text-align: center; vertical-align: middle;">
            {{-- <img src="{{ public_path('/assets/img/logo.png') }}" alt="" class="" width="80%"> --}}
            <h3 style="margin-top: 10px; padding-left: 30px;">PT KARYA PACIFIC TEKNIK SHIPYARD</h3>
        </td>
    </tr>
    <thead>
        <tr>
            <th nowrap rowspan="2">Nama Karyawan</th>
            <th nowrap rowspan="2">Jabatan</th>
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
        @foreach ($data as $index => $item)
            <tr>
                <td>{{ $item['employee_name'] }}</td>
                <td>{{ $item['position_name'] }}</td>
                @foreach ($dates as $date)
                    @if (isset($item[$date]))
                        @if ($item[$date]->is_exists)
                            <td>
                                <div>{{ $item[$date]->hour_start }}</div> <br>
                                <div>{{ $item[$date]->hour_rest_start }}</div> <br>
                                <div>{{ $item[$date]->hour_rest_end }}</div> <br>
                                <div>{{ $item[$date]->hour_end }}</div>
                            </td>
                        @else
                            <td>
                                {{--  --}}
                            </td>
                        @endif
                    @else
                        <td>
                            {{--  --}}
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
