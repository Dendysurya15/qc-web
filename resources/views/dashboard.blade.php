@include('layout/header')
<style>
    table.dataTable thead tr th {
        border: 1px solid black
    }

    .dataTables_scrollBody thead tr[role="row"] {
        visibility: collapse !important;
    }

    .dataTables_scrollHeaderInner table {
        margin-bottom: 0px !important;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <table id="tableData" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th colspan="{{$total_column_bulan + 5}}">SUMMARY SCORE GUDANG REGIONAL - I</th>
                        <th rowspan="3" colspan="2">STATUS 2022</th>
                        <th rowspan="4">RANK</th>
                    </tr>

                    <tr>
                        <th rowspan="3">WILAYAH</th>
                        <th rowspan="3">ESTATE</th>
                        <th rowspan="3">KODE</th>
                        <th rowspan="3">KTU</th>
                        <th rowspan="3">EM</th>

                        {{-- dd($total_column_bulan)}} --}}
                        <th colspan="{{ $total_column_bulan }}">Bulan</th>
                    </tr>
                    <tr> @php
                        $inc = 0;
                        @endphp
                        @foreach ($resultCount as $item)
                        @if ($item >1)
                        @php
                        $inc_bulan =1;
                        @endphp
                        @for ($i = 1; $i <= $item; $i++) @php $inc_bulan++; @endphp @endfor <th
                            colspan="{{ $inc_bulan }}">
                            {{ $bulan[$inc] }}</th>
                            @else
                            <th colspan="2">{{ $bulan[$inc] }}</th>
                            @endif
                            @php
                            $inc++;
                            @endphp
                            @endforeach</tr>
                    <tr>

                        @foreach ($resultCount as $item)
                        @if ($item >1)
                        @for ($i = 1; $i <= $item; $i++) <th>{{ $i }}</th>
                            @endfor
                            <th>SKOR</th>
                            @else
                            <th>I</th>
                            <th>SKOR</th>
                            @endif
                            @endforeach

                            <th>SKOR</th>
                            <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataResult as $key => $item)
                    <tr>
                        <td>{{$item['wil']}}</td>
                        <td>{{$item['estate']}}</td>
                        <td>{{$item['est']}}</td>
                        <td>{{$item['wil']}}</td>
                        <td>{{$item['wil']}}</td>

                        @foreach ($bulan as $key2 => $data)
                        {{-- {{dd($data)}} --}}
                        @isset($item[$data])
                        @if (is_array($item[$data]))
                        @foreach ($item[$data] as $key3=> $val)
                        <td>
                            <a href="{{route('detailInspeksi', ['id'=>$key3])}}">{{$val}}</a>
                        </td>
                        @endforeach
                        @else
                        <td>0</td>
                        @endif
                        @endisset
                        <td>{{$item['skor_bulan_' . $data]}}</td>
                        @endforeach

                        <td>{{$item['skor_tahunan']}}</td>
                        <td>{{$item['status']}}</td>

                        <td>{{$item['rank']}}</td>
                    </tr>
                    @endforeach

                </tbody>
                {{-- <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Office</th>
                        <th>Age</th>
                        <th>Start date</th>
                        <th>Salary</th>
                    </tr>
                </tfoot> --}}
            </table>
        </div>
    </section>
</div>
@include('layout/footer')

<script type="text/javascript">
    $(document).ready(function () {
    $('#tableData').DataTable({
        "scrollX": true
    });
});
</script>