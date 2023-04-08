<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<style>
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        max-width: 100%;
    }

    .my-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    .my-table th {
        white-space: normal;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 16px;
    }

    .my-table td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 2px;
    }

    /* The rest of your CSS */

    .sticky-footer {
        margin-top: auto;
        /* Push the footer to the bottom */
    }



    .header {
        display: flex;
        align-items: center;
    }

    .text-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin-left: 5px;
    }

    .logo-container {
        display: flex;
        align-items: center;
    }


    .logo {
        height: 60px;
        width: auto;
        align-items: flex-start;
    }

    .pt-name,
    .qc-name {
        margin: 0;
        padding-left: 1px;
    }

    .text-container {
        margin-left: 15px;
    }

    .right-container {
        text-align: right;

    }

    .form-inline {
        display: flex;
        align-items: center;
    }

    .custom-tables-container {
        display: flex;
        justify-content: space-between;
    }

    .custom-table {
        border-collapse: collapse;
        width: 45%;
    }

    .table-center {
        margin-left: auto;
        margin-right: auto;
    }


    .custom-table,
    .custom-table th,
    .custom-table td {
        border: 1px solid black;
        text-align: left;
        padding: 8px;
    }

    .my-custom-table {
        margin-right: 100px;
        margin-left: 10px;
    }


    .table-1-no-border td {
        border: none;
    }

    .hide-row {
        visibility: collapse;
    }

    .signature-cell {
        vertical-align: bottom;
        text-align: center;
        border: 1px solid black;
    }
</style>

<body>

    <!-- ganti sessuai kebutuhan landsacpe/potrait -->

    <!-- potrait -->
    <!-- <div class="content-wrapper" style="border: 1px solid #000; padding: 50px; min-height: calc(594mm - 100px); width: 420mm; margin: 0 auto;"> -->
    <!-- --- -->
    <!-- landscape -->
    <div class="content-wrapper" style="border: 1px solid #000; padding: 30px;">
        <!-- -- -->

        <style>
            .custom-border {
                border: 1px solid #000;
                padding: 20px;
                margin-top: 50px;
                margin-bottom: 50px;
            }
        </style>

        <div class="d-flex justify-content-center custom-border">
            <h2 class="text-center">REKAPITULASI SIDAK PEMERIKSAAN TPH, JALAN & BIN</h2>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: middle; padding-left: 0; width: 10%;border:0;">
                    <div>
                        <img src="{{ asset('img/logo-SSS.png') }}" style="height:60px">
                    </div>
                </td>
                <td style="width:30%;border:0;">

                    <p style="text-align: left;">PT. SAWIT SUMBERMAS SARANA,TBK</p>
                    <p style="text-align: left;">QUALITY CONTROL</p>

                </td>
                <td style=" width: 20%;border:0;">
                </td>
                <td style="vertical-align: middle; text-align: right;width:40%;border:0;">
                    <div class="right-container">
                        <div class="text-container">

                            <div class="afd">ESTATE : {{$data['est']}} </div>
                            <div class="afd">TANGGAL/BULAN: {{$data['awal']}}/{{$data['akhir']}}</div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">

        </div>

        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
            <div class="Wraping">
                <table class="my-table">
                    <thead>
                        <tr>

                            <th colspan="17">Mutu Transport</th>
                            <th colspan="12">Keterangan</th>
                        </tr>
                        <tr>
                            <th rowspan="3">Afdeling</th>
                            <th rowspan="3">Luas HA Sample</th>
                            <th rowspan="3">Jumlah Blok Sample</th>
                            <th colspan="8">Brondolan Tinggal</th>
                            <th colspan="2" rowspan="2">Karung Isi Brondolan</th>
                            <th colspan="2" rowspan="2">Buah Tinggal Di Tph</th>
                            <th colspan="2" rowspan="2">Restan tidak dilaporkan</th>

                            <th colspan="12" rowspan="12"></th>


                        </tr>
                        <tr>

                            <th colspan="2">Di TPH</th>
                            <th colspan="2">Di Jalan</th>
                            <th colspan="2">Di Bin</th>
                            <th colspan="2">Total</th>


                        </tr>
                        <tr>

                            <th>Jumlah</th>
                            <th>Brd/Blok</th>
                            <th>Jumlah</th>
                            <th>Brd/Blok</th>
                            <th>Jumlah</th>
                            <th>Brd/Blok</th>
                            <th>Jumlah</th>
                            <th>Brd/Blok</th>

                            <th>Jumlah</th>
                            <th>Krg/Blok</th>
                            <th>Jumlah</th>
                            <th>Jjg/Blok</th>
                            <th>Jumlah</th>
                            <th>Jjg/Blok</th>
                        <tr>

                    </thead>
                    <tbody id="tab2">
                        @php
                        function countRelevantKeys($array) {
                        $count = 0;
                        foreach ($array as $key => $value) {
                        if (!in_array($key, ['totalblok', 'bt_tph', 'tph_blok', 'bt_jalan', 'jalan_blok', 'bt_bin', 'bin_blok', 'TotalBRD', 'Total_blok', 'jum_karung', 'blok_karung', 'buah_tinggal', 'blok_buah', 'restan_unreported', 'blok_restan'])) {
                        $count++;
                        }
                        }
                        return $count;
                        }

                        $rowCount = countRelevantKeys($data['hitung']);
                        $emptyRows = 7 - $rowCount;
                        @endphp

                        @foreach ($data['hitung'] as $key => $item)
                        @if (!in_array($key, ['totalblok', 'bt_tph', 'tph_blok', 'bt_jalan', 'jalan_blok', 'bt_bin', 'bin_blok', 'TotalBRD', 'Total_blok', 'jum_karung', 'blok_karung', 'buah_tinggal', 'blok_buah', 'restan_unreported', 'blok_restan']))
                        <tr>
                            <td>{{ $key }}</td>
                            <td>{{$item['luas_blok']}}</td>
                            <td>{{ $item['jum_blok'] }}</td>
                            <td>{{ $item['bt_tph'] }}</td>
                            <td>{{ $item['tph_blok'] }}</td>
                            <td>{{ $item['bt_jalan'] }}</td>
                            <td>{{ $item['jalan_blok'] }}</td>
                            <td>{{ $item['bt_bin'] }}</td>
                            <td>{{ $item['bin_blok'] }}</td>
                            <td>{{ $item['TotalBRD'] }}</td>
                            <td>{{ $item['Total_blok'] }}</td>
                            <td>{{ $item['jum_karung'] }}</td>
                            <td>{{ $item['blok_karung'] }}</td>
                            <td>{{ $item['buah_tinggal'] }}</td>
                            <td>{{ $item['blok_buah'] }}</td>
                            <td>{{ $item['restan_unreported'] }}</td>
                            <td>{{ $item['blok_restanx'] }}</td>
                        </tr>
                        @endif
                        @endforeach
                        @for ($i = 0; $i < $emptyRows; $i++) <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>

                            </tr>
                            @endfor
                            <tr>
                                <td>Total</td>
                                <td>-</td>
                                <td>{{ $data['hitung']['totalblok'] }}</td>
                                <td>{{ $data['hitung']['bt_tph'] }}</td>
                                <td>{{ $data['hitung']['tph_blok'] }}</td>
                                <td>{{ $data['hitung']['bt_jalan'] }}</td>
                                <td>{{ $data['hitung']['jalan_blok'] }}</td>
                                <td>{{ $data['hitung']['bt_bin'] }}</td>
                                <td>{{ $data['hitung']['bin_blok'] }}</td>
                                <td>{{ $data['hitung']['TotalBRD'] }}</td>
                                <td>{{ $data['hitung']['Total_blok'] }}</td>
                                <td>{{ $data['hitung']['jum_karung'] }}</td>
                                <td>{{ $data['hitung']['blok_karung'] }}</td>
                                <td>{{ $data['hitung']['buah_tinggal'] }}</td>
                                <td>{{ $data['hitung']['blok_buah'] }}</td>
                                <td>{{ $data['hitung']['restan_unreported'] }}</td>
                                <td>{{ $data['hitung']['blok_restan'] }}</td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 " style="padding: 5px;">
            <!-- Table 1 -->
            <table class="custom-table table-1-no-border" style="float: left; width: 40%;">
                <thead>
                    <tr class="table-1-no-border hide-row">
                        <th colspan="2" class="text-center"></th>
                    </tr>
                </thead>
            </table>
            <!-- Table 2 -->
            <table class="custom-table" style="float: left; width: 40%; border-collapse: collapse;" border="1">
                <thead>
                    <tr>
                        <th colspan="12" class="table-1-no-border hide-row">Demikian hasil pemeriksaan ini dengan sebenar-benarnya tanpa rekayasa dan paksaan dari Siapapun,</th>
                    </tr>
                    <tr>
                        <th colspan="9" class="text-center">Dibuat</th>
                        <th colspan="3" class="text-center">Diterima</th>
                    </tr>
                </thead>
                <tbody>
                    <tr></tr>
                    <tr></tr>
                    <tr></tr>
                    <tr>
                        <td colspan="3" style="vertical-align: bottom; padding-top: 244px; text-align:center; border: 1px solid black;"></td>
                        <td colspan="3" style="vertical-align: bottom; text-align:center; border: 1px solid black;"></td>
                        <td colspan="3" style="vertical-align: bottom; text-align:center; border: 1px solid black;"></td>
                        <td colspan="3" style="vertical-align: bottom; text-align:center; border: 1px solid black;"></td>
                    </tr>
                    <tr>
                        <td colspan="9" style="vertical-align: bottom; text-align:center; border: 1px solid black;">Quality Control</td>
                        <td colspan="3" style="vertical-align: bottom; text-align:center; border: 1px solid black;">Estate Manager</td>
                    </tr>
                </tbody>
            </table>
            <!-- Table 3 -->
            <table class="custom-table table-1-no-border" style="float: right; width: 20%;">
                <thead>
                    <tr class="hide-row">
                        <th colspan="2" class="text-center"></th>
                    </tr>
                </thead>
            </table>
            <div style="clear:both;"></div>
        </div>

        <div style="clear:both;"></div>
    </div>


</body>

</html>