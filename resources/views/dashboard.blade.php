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
            <table class=" text-center" id="table_qc">
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
                        <th colspan="{{ $total_column_bulan }}">Bulan</th>
                    </tr>
                    <tr>
                        {{-- <th>JAN</th>
                        <th>FEB</th>
                        <th>MAR</th>
                        <th>APR</th>
                        <th>MEI</th>
                        <th>JUN</th>
                        <th>JUL</th>
                        <th>AGU</th>
                        <th>SEP</th>
                        <th>OKT</th>
                        <th>NOV</th>
                        <th>DES</th> --}}
                        @php
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
                            @endforeach

                            {{-- @foreach ($resultCount as $item)
                        @if ($item >1)
                        @for ($i = 1; $i <= $item; $i++) <th>{{ $i }}</th>
                            @endfor
                            <th>SKOR</th>
                            @else
                            <th>I</th>
                            <th>SKOR</th>
                            @endif
                            @endforeach --}}
                    </tr>
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

                    {{-- @foreach($qc_gudang as $q)
    <tr>
        <td>{{ $loop->iteration }}</td>
                    <td>{{ $q->unit }}</td>
                    <td>{{ $q->tanggal }}</td>
                    <td>{{ $q->qc }}</td>
                    <td>{{ $q->skor_total}}</td>
                    <>{{ $q->kesesuaian_ppro }}</ <td>{{ $q->chemtd>ical_expired }}</td>
                    <td>{{ $q->barang_nonstok }}</td>
                    <td>{{ $q->kebersihan_gudang }}</td>
                    <td>{{ $q->mr_ditandatangani }}</td>
                    <td>{{ $q->inspeksi_ktu}}</td>
                    </tr>
                    @endforeach --}}
                </thead>
                {{-- <tbody></tbody> --}}
            </table>
        </div>
    </section>
</div>
@include('layout/footer')

<script type="text/javascript">
    var resultCount = '<?php echo $resultCountJson; ?>';
    var bulanJson = '<?php echo $bulanJson; ?>';
    var bulanJson = JSON.parse(bulanJson)

    var resultCount = JSON.parse(resultCount)
    var countArr = Object.entries(resultCount)
    // console.log(resultCount)
    strColumn = '[{"data"'
    strColumn += ":"
    strColumn += '"wil", "name"'
    strColumn += ":"
    strColumn += '"wil"},'
    strColumn += '{"data"'
    strColumn += ":"
    strColumn += '"estate", "name"'
    strColumn += ":"
    strColumn += '"estate"},'
    strColumn += '{"data"'
    strColumn += ":"
    strColumn += '"est", "name"'
    strColumn += ":"
    strColumn += '"est"},'
    strColumn += '{"data"'
    strColumn += ":"
    strColumn += '"est", "name"'
    strColumn += ":"
    strColumn += '"est"},'
    strColumn += '{"data"'
    strColumn += ":"
    strColumn += '"est", "name"'
    strColumn += ":"
    strColumn += '"est"},'
    // console.log(resultCount[bulanJson[9]])
    var inc = 1;
    for (let i = 0; i < countArr.length; i++) {
        if(resultCount[bulanJson[i]] > 1){
            for (let j = 1; j <= resultCount[bulanJson[i]]; j++) {
                strColumn += '{"data"'
                strColumn += ":"
                strColumn += '"'+bulanJson[i]+'_'+j+'", "name"'
                strColumn += ":"
                strColumn += '"'+bulanJson[i]+'_'+j+'"},'
            }
        }else{
                strColumn += '{"data"'
                strColumn += ":"
                strColumn += '"'+bulanJson[i]+'", "name"'
                strColumn += ":"
                strColumn += '"'+bulanJson[i]+'"},'
        }
                strColumn += '{"data"'
                strColumn += ":"
                strColumn += '"skor_bulan_'+inc+'", "name"'
                strColumn += ":"
                strColumn += '"skor_bulan_'+inc+'"},'
                inc++;
    }
    strColumn += '{"data"'
    strColumn += ":"
    strColumn += '"skor_tahunan", "name"'
    strColumn += ":"
    strColumn += '"skor_tahunan"},'
    strColumn += '{"data"'
    strColumn += ":"
    strColumn += '"status", "name"'
    strColumn += ":"
    strColumn += '"status"},'
    strColumn += '{"data"'
    strColumn += ":"
    strColumn += '"rank", "name"'
    strColumn += ":"
    strColumn += '"rank"}'
    strColumn += ']'

    dataJsonTable = JSON.parse(strColumn)

    $(document).ready(function () {
   $('#table_qc').DataTable({
        scrollX:true,
        processing: true,
        serverSide: true,
        pageLength: 25,
        ajax: '{{ route('qc') }}',
        columns: dataJsonTable
            // { "data": 'wil', "name": 'wil' },
            // { "data": 'estate', "name": 'estate' },
            // { "data": 'est', "name": 'est' },
            // { "data": 'est', "name": 'est' },
            // { "data": 'est', "name": 'est' },
            // // { "data": 'January', "name": 'January' },
            // { "data": 'February', "name": 'February' },
            // { "data": 'March', "name": 'March' },
            // { "data": 'April', "name": 'April' },
            // { "data": 'May', "name": 'May' },
            // { "data": 'June', "name": 'June' },
            // { "data": 'July', "name": 'July' },
            // // { "data": 'August', name: 'August' },
            // // { "data": 'September', name: 'September' },
            // // { "data": 'October', name: 'October' },
            // // { "data": 'November', name: 'November' },
            // // { "data": 'December', name: 'December' },
            // { "data": 'skor_tahunan', name: 'skor_tahunan' },
            // { "data": 'status', name: 'status' },
            // { "data": 'rank', name: 'rank' },
        ,
    });
 });
</script>