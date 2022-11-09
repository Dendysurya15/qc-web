<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
            integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <title>Document</title>

    </head>
    <style>
        table.table-bordered>thead>tr>th {
            border: 1px solid rgb(0, 0, 0);
        }

        table.table-bordered>tbody>tr>td {
            border: 1px solid rgb(0, 0, 0);
        }

        table.table-active>thead>tr>th {
            border: 1px solid rgb(0, 0, 0);
        }

        table.table-active>tbody>tr>td {
            border: 1px solid rgb(0, 0, 0);
        }

        table.table-primary>thead>tr>th {
            border: 1px solid rgb(0, 0, 0);
        }

        table.table-primary>tbody>tr>td {
            border: 1px solid rgb(0, 0, 0);
        }

        table.table-bordered>tbody>tr>th {
            border: 1px solid rgb(0, 0, 0);
        }

        table.table-warning>thead>tr>th {
            border: 1px solid rgb(0, 0, 0);
        }

        table.table-warning>tbody>tr>td {
            border: 1px solid rgb(0, 0, 0);
        }

    </style>

    <body>
        <table class="table table-warning col-xs-1 text-center">
            <thead>
                <tr>
                    <th>III.PEMERIKSAAN GUDANG</th>
                </tr>
            </thead>
        </table>

        <div class="row col-12">
            {{-- <div class="col"> --}}
            <table class="table table-bordered" style="border: 1px solid black">
                <tbody>
                    <tr>
                        <th>ESTATE</th>
                        <td>Sulung</td>
                        <td style="color: white;border:1px solid white"></td>
                        <td style="color: white;border:1px solid white"></td>
                        <td style="color: white;border:1px solid white"></td>
                        <td style="color: white;border:1px solid white"></td>
                        <td style="color: white;border:1px solid white"></td>
                        <td style="color: white;border:1px solid white"></td>
                        <td style="color: white;border-top:1px solid white;border-bottom:1px solid white"></td>
                        <td colspan="3" class="table-warning text-center">SKOR</td>
                        {{-- <th class="table-bordered-warning" colspan="2">SKOR</th> --}}
                        {{-- <td>Sulung</td> --}}
                    </tr>
                    <tr>
                        <th>TANGGAL</th>
                        <td>27/10/2022</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border-top:1px solid white;border-bottom:1px solid white">test</td>
                        <td colspan="3" class="text-center">100</td>
                        {{-- <th class="table-bordered-warning" colspan="2">100</th> --}}
                        {{-- <td>Sulung</td> --}}
                    </tr>
                    <tr>
                        <th>KTU</th>
                        <td>Fery Sigit Prayogi</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border-top:1px solid white;border-bottom:1px solid white">test</td>
                        <td colspan="3" class="table-primary text-center">EXCELLENT</td>
                        {{-- <th class="table-bordered-warning" colspan="2">EXCELLENT</th> --}}
                        {{-- <td>Sulung</td> --}}
                    </tr>
                    <tr>
                        <th>KEPALA GUDANG</th>
                        <td>Ifani Rach madhani</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td
                            style="color: white;border-top:1px solid white;border-bottom:1px solid white;border-right:1px solid white">
                            test</td>
                        <td colspan="3" style="border-left:1px solid white;border-right:1px solid white"></td>
                    </tr>
                    <tr>
                        <th>DIPERIKSA OLEH</th>
                        <td>Slamet Indarto</td>
                        <td style="border-bottom:1px solid white"></td>
                    </tr>
                </tbody>
            </table>

            {{-- </div> --}}

        </div>

        <br>
        <table class="table table-bordered ">
            <tbody>
                <tr>
                    <th class="table-primary"></th>
                    <th class="table-primary">1.KESESUAIAN FISIK VS BINCARD</th>
                    <th class="table-primary"></th>
                    <th class="table-primary">2.KESESUAIAN FISIK VS PPRO</th>
                    <th class="table-primary"></th>
                    <th class="table-primary">3.BARANG CHEMICAL EXPIRED</th>
                </tr>
                <tr>
                    <td class="table-active">HASIL</td>
                    <td class="table-active">FOTO</td>
                    <td class="table-active">HASIL</td>
                    <td class="table-active">FOTO</td>
                    <td class="table-active">HASIL</td>
                    <td class="table-active">FOTO</td>
                </tr>
                <tr>
                    <td rowspan="2">sesuai</td>
                    <td><img src="{{ public_path('img/foto.jpeg') }}" style="weight:75pt;height:150pt"></td>
                    <td rowspan="2">sesuai</td>
                    <td><img src="{{ public_path('img/foto.jpeg') }}" style="weight:75pt;height:150pt"></td>
                    <td rowspan="2">sesuai</td>
                    <td> <img src="{{ public_path('img/foto.jpeg') }}" style="weight:75pt;height:150pt"></td>
                </tr>
                <tr>
                    <td><img src="{{ public_path('img/foto.jpeg') }}" style="weight:75pt;height:150pt"></td>
                    <td><img src="{{ public_path('img/foto.jpeg') }}" style="weight:75pt;height:150pt"></td>
                    <td> <img src="{{ public_path('img/foto.jpeg') }}" style="weight:75pt;height:150pt"></td>
                </tr>
                <tr>
                    <td colspan="2">Tidak terdapat selisih fisik vs bincard</td>
                    <td colspan="2">Tidak terdapat selisih fisik vs PPRO</td>
                    <td colspan="2">Tidak ditemukan chemical expired</td>
                </tr>
                <tr>
                    <th class="table-primary"></th>
                    <th class="table-primary">4.BARANG NON-STOCK</th>
                    <th class="table-primary"></th>
                    <th class="table-primary">5.SELURUH MR DITANDATANGANI EM</th>
                    <th class="table-primary"></th>
                    <th class="table-primary">6.KEBERSIHAN DAN KERAPIHAN GUDANG </th>
                </tr>
                <tr>
                    <td class="table-active">HASIL</td>
                    <td class="table-active">FOTO</td>
                    <td class="table-active">HASIL</td>
                    <td class="table-active">FOTO</td>
                    <td class="table-active">HASIL</td>
                    <td class="table-active">FOTO</td>
                </tr>
                <tr>
                    <td rowspan="2">sesuai</td>
                    <td> <img src="{{ public_path('CBI-logo.png') }}" style="weight:75pt;height:150pt"></td>
                    <td rowspan="2">sesuai</td>
                    <td> <img src="{{ public_path('img/KAN.png') }}" style="weight:75pt;height:150pt"></td>
                    <td rowspan="2">sesuai</td>
                    <td> <img src="{{ public_path('img/foto.jpeg') }}" style="weight:75pt;height:150pt"></td>
                </tr>
                <tr>
                    <td><img src="{{ public_path('img/foto.jpeg') }}" style="weight:75pt;height:150pt"></td>
                    <td> <img src="{{ public_path('img/foto.jpeg') }}" style="weight:75pt;height:150pt"></td>
                    <td><img src="{{ public_path('img/foto.jpeg') }}" style="weight:75pt;height:150pt"></td>
                </tr>
                <tr>
                    <td colspan="2">Tidak terdapat barang non stock</td>
                    <td colspan="2">Seluruh MR sudah ditandatangan lengakap oleh EM</td>
                    <td colspan="2">Secara umum gudang dan kantor gudang sudah rapi</td>
                </tr>
                <tr>
                    <th class="table-primary"></th>
                    <th class="table-primary">7.BARANG NON-STOCK</th>
                    <th style="border: 1px solid white"></th>
                    <th style="border: 1px solid white"></th>
                    <th style="border: 1px solid white"></th>
                    <th style="border: 1px solid white"></th>
                <tr>
                    <td class="table-active">HASIL</td>
                    <td class="table-active">FOTO</td>
                    <td style="border: 1px solid white"></td>
                </tr>
                <tr>
                    <td rowspan="2">SELESAI</td>
                    <td> <img src="{{ public_path('CBI-logo.png') }}" style="weight:75pt;height:150pt"></td>
                    <td style="border: 1px solid rgb(255, 255, 255)"></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td class="border-bottom-0"></td>

                </tr>
                <tr>

                    <td colspan="2" style="border: 1px solid black">Logbook Tersedia Dan Todate</td>
                    <td colspan="2" style="border: 1px solid rgb(255, 255, 255)"> </td>


                </tr>
                </tr>
            </tbody>
        </table>
    </body>

</html>