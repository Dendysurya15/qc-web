<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pemeriksaan TPH & BIN Reg-I Periode {{$start}} - {{$last}} .pdf</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
        @page {
            size: A3;
            margin: 0;
        }

        .page-break:before {
            container: "";
            page-break-before: always;
        }

        .responsive {
            width: 300px;
            max-width: 350px;
            height: 300px;
            display: block;
        }

        /* .responsive {
            width: 100px;
            max-width: 100px;
            height: 100px;
            display: block;
        } */
    </style>
</head>

<body>
    <div class="container ">
        <div class="pb-12 mt-4">
            <h4 class="text-center border border-secondary border-2"> REKAPITULASI PEMERIKSAAN BRONDOLAN TINGGAL DI
                TPH,
                JALAN &
                BIN </h4>
        </div>

        <table class="table ">
            <thead>
                <tr>
                    <td>logo</td>
                    <td>
                        <h6>PT. SAWIT SUMBERMAS SARANA, TBK</h6>
                        <p>QUALITY CONTROL</p>
                    </td>

                    <td></td>
                    <td></td>

                    <td></td>
                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <p class="float-end">Wilayah - I</p>
                    </td>

                    </td>
                </tr>
            </thead>
        </table>

        {{-- table rekap --}}

        <div class=" row col-12 text-center align-items-center">
            <table id="myTable" class="table table-sm table-bordered text-center" style="border: 1px solid black">
                <thead>
                    <tr>
                        <th style="background-color : #e8ecdc; color: #000000;" rowspan="2" class="text-center">
                            ESTATE
                        </th>
                        <th style="background-color : #e8ecdc; color: #000000;" rowspan="2" class="text-center">
                            AFDELING
                        </th>
                        <th style="background-color : #e8ecdc; color: #000000;" rowspan="2" class="text-center">NAMA
                            ASISTEN</th>
                        <th style="background-color : #e8ecdc; color: #000000;" rowspan="2" class="text-center">HA
                            SAMPLE</th>
                        <th style="background-color : #e8ecdc; color: #000000;" colspan="2" class="text-center">
                            BRONDOLAN TINGGAL (JUMLAH)</th>
                        <th style="background-color : #e8ecdc; color: #000000;" rowspan="2" class="text-center">
                            KARUNG
                            BERISI BRONDOLAN (JUMLAH)</th>
                        <th style="background-color : #e8ecdc; color: #000000;" rowspan="2" class="text-center">BUAH
                            TINGGAL TPH (JANJANG)</th>
                        <th style="background-color : #e8ecdc; color: #000000;" rowspan="2" class="text-center">
                            RESTAN
                            TIDA DILAPORKAN (JANJANG)</th>
                    </tr>
                    <tr>
                        <th style="background-color : #e8ecdc; color: #000000;" class="text-center">TPH</th>
                        <th style="background-color : #e8ecdc; color: #000000;" class="text-center">JALAN & BIN</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $ha_total_wil = 0;
                    $tph_total_wil = 0;
                    $jlnBin_total_wil = 0;
                    $buahTinggla_total_wil = 0;
                    $karung_total_wil = 0;
                    $Restan_total_wil = 0;
                    @endphp
                    {{-- testing --}}
                    @foreach ($wil_1_sidak_tph as $key => $item)
                    @if (is_array($item))
                    @foreach ($item as $key2 => $value)
                    @if (is_array($value))
                    <tr>
                        <td>{{$key}}</td>
                        <td>{{$key2}}</td>
                        <td>-</td>
                        <td>{{$value['ha_sample']}}</td>
                        <td>{{$value['tph']}}</td>
                        <td>{{$value['jalan_bin']}}</td>
                        <td>{{$value['karung']}}</td>
                        <td>{{$value['buah_tinggal']}}</td>
                        <td>{{$value['restan_unreported']}}</td>
                        @php
                        $ha_total_wil += $value['ha_sample'];
                        $tph_total_wil += $value['tph'];
                        $jlnBin_total_wil += $value['jalan_bin'];
                        $karung_total_wil += $value['karung'];
                        $buahTinggla_total_wil += $value['buah_tinggal'];
                        $Restan_total_wil += $value['restan_unreported'];
                        @endphp
                    </tr>
                    @endif
                    @endforeach
                    {{-- Total Estate --}}
                    <tr>
                        <td style="background-color : #e8ecdc; color: #000000;" colspan="3">TOTAL</td>
                        <td style="background-color : #e8ecdc; color: #000000;">{{$item['ha_total']}}</td>
                        <td style="background-color : #e8ecdc; color: #000000;">{{$item['brondolan_total']}}</td>
                        <td style="background-color : #e8ecdc; color: #000000;">{{$item['JalanBin_total']}}</td>
                        <td style="background-color : #e8ecdc; color: #000000;"> {{$item['Karung_total']}}</td>
                        <td style="background-color : #e8ecdc; color: #000000;">{{$item['BuahTinggal_total']}}</td>
                        <td style="background-color : #e8ecdc; color: #000000;">{{$item['RestanUnreported_total']}}
                        </td>
                    </tr>
                    {{-- Total WILAYAH --}}
                    @endif
                    @endforeach
                    <tr>
                        <td style="background-color : #ffe494; color: #000000;" colspan="3">TOTAL WILAYAH</td>
                        <td style="background-color : #ffe494; color: #000000;">{{ $ha_total_wil }}</td>
                        <td style="background-color : #ffe494; color: #000000;">{{ $tph_total_wil }}</td>
                        <td style="background-color : #ffe494; color: #000000;">{{ $jlnBin_total_wil }}</td>
                        <td style="background-color : #ffe494; color: #000000;"> {{ $karung_total_wil }}</td>
                        <td style="background-color : #ffe494; color: #000000;">{{ $buahTinggla_total_wil }}</td>
                        <td style="background-color : #ffe494; color: #000000;">{{ $Restan_total_wil }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- end tabel rekap --}}


        {{-- table highest funding record --}}
        <div class="table-responsive">
            <table id="Table1" class="table table-bordered table-sm ">
                <thead>
                    <th colspan="3" class="text-center" style="background-color : rgb(209, 160, 115);">
                        HIGHEST FINDING RECORD
                    </th>
                    <tr>
                        <th scope="col" style="background-color : #b0d48c; color: #0f0f0f;" class="text-center ">
                            KATEGORI</th>
                        <th scope="col" style="background-color : #b0d48c; color: #0f0f0f;" class="text-center ">
                            AFDELING</th>
                        <th scope="col" style="background-color : #b0d48c; color: #0f0f0f;" class="text-center ">
                            JUMLAH
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">BRONDOLAN TINGGAL</th>
                        @if(!empty($wil_1_sidak_tph_max)){
                        @foreach ($wil_1_sidak_tph_max as $key => $item)
                        @if (is_array($item))
                        @foreach ($item as $key2 => $items)
                        @if(is_array($items) && !empty($items))
                        <td>{{$key}} {{$key2}}</td>
                        <td>{{$items['brondolan_maxx']}}</td>
                        @endif
                        @endforeach
                        @endif
                        @endforeach
                        }
                        @endif
                    </tr>
                    <tr>
                        <th scope="row">KARUNG BERISI BRONDOLAN</th>
                        @if(!empty($wil_1_sidak_krng_max)){
                        @foreach ($wil_1_sidak_krng_max as $key => $item)
                        @if (is_array($item))
                        @foreach ($item as $key2 => $items)
                        @if(is_array($items) && !empty($items))
                        <td>{{$key}} {{$key2}}</td>
                        <td>{{$items['karung_max']}}</td>
                        @endif
                        @endforeach
                        @endif
                        @endforeach
                        }
                        @endif
                    </tr>
                    <tr>
                        <th scope="row">BUAH TINGGAL</th>
                        @if(!empty($wil_1_sidak_buah_max)){
                        @foreach ($wil_1_sidak_buah_max as $key => $item)
                        @if (is_array($item))
                        @foreach ($item as $key2 => $items)
                        @if(is_array($items) && !empty($items))
                        <td>{{$key}} {{$key2}}</td>
                        <td>{{$items['buah_tgl_max_fix']}}</td>
                        @endif
                        @endforeach
                        @endif
                        @endforeach
                        }
                        @endif
                    </tr>
                    <tr>
                        <th scope="row">RESTAN TIDAK DILAPORKAN</th>
                        @if(!empty($wil_1_sidak_restant_max)){
                        @foreach ($wil_1_sidak_restant_max as $key => $item)
                        @if (is_array($item))
                        @foreach ($item as $key2 => $items)
                        @if(is_array($items) && !empty($items))
                        <td>{{$key}} {{$key2}}</td>
                        <td>{{$items['restant_max_fix']}}</td>
                        @endif
                        @endforeach
                        @endif
                        @endforeach
                        }
                        @endif
                    </tr>
                </tbody>



            </table>
        </div>


        {{-- <div style="page-break-after: always;"></div> --}}


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>

</body>

</html>