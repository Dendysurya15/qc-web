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
                        <td>{{ $data->nama }}</td>
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
                        <td>
                            {{ $data->tanggal_formatted }}
                        </td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border-top:1px solid white;border-bottom:1px solid white">test</td>
                        <td colspan="3" class="text-center">{{ $data->skor_total }}</td>
                        {{-- <th class="table-bordered-warning" colspan="2">100</th> --}}
                        {{-- <td>Sulung</td> --}}
                    </tr>
                    <tr>
                        <th>KTU</th>
                        <td>{{ $data->ktu }}</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border:1px solid white">test</td>
                        <td style="color: white;border-top:1px solid white;border-bottom:1px solid white">test</td>
                        @if ($data->skor_total >= 95)
                        <td colspan="3" class="table-primary text-center">EXCELLENT</td>
                        @elseif($data->skor_total >= 85 && $data->skor_total <95) <td colspan="3"
                            class="table-success text-center">
                            Good</td>
                            @elseif($data->skor_total >= 75 && $data->skor_total <85) <td colspan="3"
                                class="table text-center" style="background-color: yellow">Satisfactory</td>
                                @elseif($data->skor_total >= 65 && $data->skor_total <75) <td colspan="3"
                                    class="table-warning text-center">Fair</td>
                                    @elseif($data->skor_total <75) <td colspan="3" class="table text-center"
                                        style="background-color: red">Poor
                                        </td>
                                        @endif
                                        {{-- <th class="table-bordered-warning" colspan="2">EXCELLENT</th> --}}
                                        {{-- <td>Sulung</td> --}}
                    </tr>
                    <tr>
                        <th>KEPALA GUDANG</th>
                        <td>{{ $data->kpl_gudang }}</td>
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
                        <td>{{ $data->qc }}</td>
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
                    <td class="text-center" colspan="2">{{ $data->komentar_kesesuaian_bincard }}</td>
                    <td class="text-center" colspan="2">{{ $data->komentar_kesesuaian_ppro }}</td>
                    <td class="text-center" colspan="2">{{ $data->komentar_chemical_expired }}</td>
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
                    <td class="text-center" colspan="2">{{ $data->komentar_barang_nonstok }}</td>
                    <td class="text-center" colspan="2">{{ $data->komentar_mr_ditandatangani }}</td>
                    <td class="text-center" colspan="2">{{ $data->komentar_kebersihan_gudang }}</td>
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

                    <td class="text-center" colspan="2" style="border: 1px solid black">
                        {{ $data->komentar_inspeksi_ktu }}</td>
                    <td colspan="2" style="border: 1px solid rgb(255, 255, 255)"> </td>


                </tr>
                </tr>
            </tbody>
        </table>
    </body>

</html>