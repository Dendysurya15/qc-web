@include('layout/header')
<style>
    .tbl-fixed {
        overflow: scroll;
        height: fit-content;
        max-height: 70vh;
        border: 1px solid #777777;
    }

    .tbl-fixed table {
        border-spacing: 0;
        font-size: 15px;
    }

    .tbl-fixed th {
        border: 1px solid #bbbbbb;
    }

    .tbl-fixed thead {
        position: sticky;
        top: 0px;
        z-index: 2;
    }

    .tbl-fixed td {
        border: 1px solid #bbbbbb;
        padding: 5px;
        width: 80px;
        min-width: 80px;
    }

    .tbl-fixed td:nth-child(1) {
        position: sticky;
        left: 0;
    }

    .tbl-fixed td:nth-child(2) {
        position: sticky;
        left: 4.5%;
        width: 50px;
        min-width: 50px;
    }

    .tbl-fixed td:nth-child(1),
    .tbl-fixed td:nth-child(2) {
        background: #cfcfcf;
    }

    .label-estate {
        font-size: 15pt;
        color: white;
    }

    .label-blok {
        font-size: 12pt;
        color: black;
        font-weight: bold;
        opacity: 1;
    }

    .legend {
        padding: 5px 5px;
        background: white;
    }
</style>
<div class="content-wrapper">
    <section class="content"><br>
        <div class="container-fluid">
            <div class="card table_wrapper">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-utama-tab" data-toggle="tab" href="#nav-utama" role="tab" aria-controls="nav-utama" aria-selected="true">Halaman Utama</a>
                        <a class="nav-item nav-link" id="nav-data-tab" data-toggle="tab" href="#nav-data" role="tab" aria-controls="nav-data" aria-selected="false">Data</a>
                        <a class="nav-item nav-link" id="nav-issue-tab" data-toggle="tab" href="#nav-issue" role="tab" aria-controls="nav-issue" aria-selected="false">Finding Issue</a>
                        <a class="nav-item nav-link" id="nav-score-tab" data-toggle="tab" href="#nav-score" role="tab" aria-controls="nav-score" aria-selected="false">Score By Map</a>
                        <a class="nav-item nav-link" id="nav-grafik-tab" data-toggle="tab" href="#nav-grafik" role="tab" aria-controls="nav-grafik" aria-selected="false">Grafik</a>
                    </div>
                </nav>

                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-utama" role="tabpanel" aria-labelledby="nav-utama-tab">

                        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                            <h5><b>REKAPITULASI RANKING NILAI KUALITAS PANEN</b></h5>
                        </div>
                        {{-- <form action="{{route('filter')}}" method="GET"> --}}
                        <div class="d-flex flex-row-reverse mr-3">
                            <button class="btn btn-primary mb-3" style="float: right" id="btnShow">Show</button>
                            <div class="col-2 mr-2" style="float: right">
                                {{csrf_field()}}
                                <select class="form-control" id="regionalPanen">
                                    <option value="1" selected>Regional 1</option>
                                    <option value="2">Regional 2</option>
                                    <option value="3">Regional 3</option>

                                </select>
                            </div>
                            <div class="col-2" style="float: right">
                                <input class="form-control" value="{{ date('Y-m') }}" type="month" name="date" id="inputDate">
                                {{-- <input class="form-control" value="{{ old('tgl', date('Y-m')) }}" type="month"
                                name="tgl" id="inputDate"> --}}

                            </div>
                        </div>
                        {{--
</form> --}}
                        <div class="ml-3 mr-3">
                            <div class=" row text-center">
                                <div class="col-sm-3 " id="Tab1">
                                    <table class=" table table-bordered" style="font-size: 13px" id="table1">
                                        <thead>
                                            <tr bgcolor="darkorange">
                                                <th colspan="5" id="thead1">WILAYAH I</th>
                                            </tr>
                                            <tr bgcolor="darkblue" style="color: white">
                                                <th rowspan="2">KEBUN</th>
                                                <th rowspan="2">AFD</th>
                                                <th rowspan="2">Nama</th>
                                                <th colspan="2">Todate</th>
                                            </tr>
                                            <tr bgcolor="darkblue" style="color: white">
                                                <th>Score</th>
                                                <th>Rank</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody1">
                                            @foreach ($dataPerWil[1] as $key => $value)
                                            @foreach($value as $key1 => $value1)
                                            @foreach ($value1 as $key2 => $value3)
                                            @if (is_array($value3))
                                            <tr>
                                                <td>{{ $key }}</td>
                                                <td>{{ $key1 }}</td>
                                                <td>no data </td>
                                                <td bgcolor="red" style="color: white">{{ $value3['skor_akhir'] }}</td>
                                                <td>{{ $value1['rank'] }}</td>

                                            </tr>
                                            @endif
                                            @endforeach
                                            @endforeach
                                            @endforeach

                                            @foreach ($TotalperEstate[1] as $key4 => $value4)

                                            <tr>
                                                <td bgcolor="#e0dcec" style="color: #000000">{{ $key4 }}</td>
                                                <td bgcolor="#e0dcec" style="color: #000000">EM</td>
                                                <td bgcolor="#e0dcec" style="color: #000000">no data</td>
                                                <td bgcolor="#e0dcec" style="color: #000000">{{ $value4['skor_akhir'] }}
                                                </td>
                                                <td bgcolor="#e0dcec" style="color: #000000">{{ $value4['rank'] }}</td>
                                            </tr>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-3" id="Tab2">
                                    <table class=" table table-bordered" style="font-size: 13px" id="table1">
                                        <thead>
                                            <tr bgcolor="darkorange">
                                                <th colspan="5" id="thead2">WILAYAH II</th>
                                            </tr>
                                            <tr bgcolor="darkblue" style="color: white">
                                                <th rowspan="2">KEBUN</th>
                                                <th rowspan="2">AFD</th>
                                                <th rowspan="2">Nama</th>
                                                <th colspan="2">Todate</th>
                                            </tr>
                                            <tr bgcolor="darkblue" style="color: white">
                                                <th>Score</th>
                                                <th>Rank</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody2">
                                            @foreach ($dataPerWil[2] as $key => $value)
                                            @foreach($value as $key2 => $value2)
                                            @foreach ($value2 as $key3 => $value3)
                                            @if (is_array($value3))
                                            <tr>
                                                <td>{{ $key }}</td>
                                                <td>{{ $key2 }}</td>
                                                <td>no data </td>
                                                <td id="skor" bgcolor="red" style="color: white">{{
                            $value3['skor_akhir'] }}</td>
                                                <td>{{ $value2['rank'] }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                            @endforeach
                                            @endforeach

                                            @foreach ($TotalperEstate[2] as $key4 => $value4)

                                            <tr>
                                                <td bgcolor="#e0dcec" style="color: #000000">{{ $key4 }}</td>
                                                <td bgcolor="#e0dcec" style="color: #000000">EM</td>
                                                <td bgcolor="#e0dcec" style="color: #000000">no data</td>
                                                <td bgcolor="#e0dcec" style="color: #000000">{{ $value4['skor_akhir'] }}
                                                </td>
                                                <td bgcolor="#e0dcec" style="color: #000000">{{ $value4['rank'] }}</td>
                                            </tr>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-3" id="Tab3">
                                    <table class="table table-bordered" style="font-size: 13px" id="Reg3">
                                        <thead>
                                            <tr bgcolor="darkorange">
                                                <th colspan="5" id="thead3">WILAYAH III</th>
                                            </tr>
                                            <tr bgcolor="darkblue" style="color: white">
                                                <th rowspan="2">KEBUN</th>
                                                <th rowspan="2">AFD</th>
                                                <th rowspan="2">Nama</th>
                                                <th colspan="2">Todate</th>
                                            </tr>
                                            <tr bgcolor="darkblue" style="color: white">
                                                <th>Score</th>
                                                <th>Rank</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody3">
                                            @foreach ($dataPerWil[3] as $key => $value)
                                            @foreach($value as $key2 => $value2)
                                            @foreach ($value2 as $key3 => $value3)
                                            @if (is_array($value3))
                                            <tr>
                                                <td>{{ $key }}</td>
                                                <td>{{ $key2 }}</td>
                                                <td>no data </td>
                                                <td bgcolor="red" style="color: white">{{ $value3['skor_akhir'] }}</td>
                                                <td>{{ $value2['rank'] }}</td>

                                            </tr>
                                            @endif
                                            @endforeach
                                            @endforeach
                                            @endforeach

                                            @foreach ($TotalperEstate[3] as $key4 => $value4)

                                            <tr>
                                                <td bgcolor="#e0dcec" style="color: #000000">{{ $key4 }}</td>
                                                <td bgcolor="#e0dcec" style="color: #000000">EM</td>
                                                <td bgcolor="#e0dcec" style="color: #000000">no data</td>
                                                <td bgcolor="#e0dcec" style="color: #000000">{{ $value4['skor_akhir'] }}
                                                </td>
                                                <td bgcolor="#e0dcec" style="color: #000000">{{ $value4['rank'] }}</td>
                                            </tr>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- <div class="col-sm-12"> -->
                                <div class="col-sm-3 " id="Tab4">
                                    <table class="table table-bordered" style="font-size: 13px" id="plasmaID">
                                        <thead>
                                            <tr bgcolor="darkorange">
                                                <th colspan="5" id="plhead">Plasma</th>
                                            </tr>
                                            <tr bgcolor="darkblue" style="color: white">
                                                <th rowspan="2">KEBUN</th>
                                                <th rowspan="2">AFD</th>
                                                <th rowspan="2">Nama</th>
                                                <th colspan="2">Todate</th>
                                            </tr>
                                            <tr bgcolor="darkblue" style="color: white">
                                                <th>Score</th>
                                                <th>Rank</th>
                                            </tr>
                                        </thead>
                                        <tbody id="plbody">

                                            <tr>
                                                <td>PLASMA</td>
                                                <td>WIL-1</td>
                                                <td>MifTahul Huda</td>
                                                <td id="skor" bgcolor="red" style="color: white">
                                                    -</td>
                                                <td>-</td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#e0dcec" style="color: #000000">PLASMA</td>
                                                <td bgcolor="#e0dcec" style="color: #000000">EM</td>
                                                <td bgcolor="#e0dcec" style="color: #000000">SEPTIAN ADHI.P</td>
                                                <td bgcolor="#e0dcec" style="color: #000000">-
                                                </td>
                                                <td bgcolor="#e0dcec" style="color: #000000">-</td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#e0dcec" style="color: #232323">PLASMA</td>
                                                <td bgcolor="#e0dcec" style="color: #232323">GM</td>
                                                <td bgcolor="#e0dcec" style="color: #232323">M.INDRA WIJAYA</td>
                                                <td bgcolor="#e0dcec" style="color: #232323">-
                                                </td>
                                                <td bgcolor="#e0dcec" style="color: #232323">-</td>
                                            </tr>


                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-sm-12">
                                    <!-- <table class="table table-bordered" style="font-size: 13px">
            </table> -->
                                    <table class="table table-bordered">
                                        <thead id="theadreg">
                                            <tr>
                                                <th colspan="1">REG-I</th>
                                                <th colspan="1">RH-1</th>
                                                <th colspan="1">Akhmad Faisyal</th>
                                                <th colspan="8"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Isi tabel di sini -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p style="font-size: 15px; text-align: center;"><b><u>BRONDOLAN TINGGAL</u></b></p>
                                        <div id="brondolanGraph"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p style="font-size: 15px; text-align: center;"><b><u>BUAH TINGGAL</u></b></p>
                                        <div id="buahGraph"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p style="font-size: 15px; text-align: center;"><b><u>BRONDOLAN TINGGAL</u></b></p>
                                        <div id="brondolanGraphWil"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p style="font-size: 15px; text-align: center;"><b><u>BUAH TINGGAL</u></b></p>
                                        <div id="buahGraphWil"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="ml-3 mb-3 mr-3">
                            <button style="width: 100%" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#showByYear" aria-expanded="false" aria-controls="showByYear">
                                TAMPILKAN PER TAHUN
                            </button>
                        </p>

                        <div class="collapse" id="showByYear">
                            <div class="d-flex justify-content-center mb-2 ml-3 mr-3 border border-dark">
                                <h5><b>REKAPITULASI RANKING NILAI KUALITAS PANEN</b></h5>
                            </div>

                            <div class="d-flex flex-row-reverse mr-3">
                                <button class="btn btn-primary mb-3" style="float: right" id="showTahung">Show</button>
                                <div class="col-2 mr-2" style="float: right">
                                    {{csrf_field()}}
                                    <select class="form-control" id="regionalData">
                                        <option value="1" selected>Regional 1</option>
                                        <option value="2">Regional 2</option>
                                        <option value="3">Regional 3</option>
                                    </select>
                                </div>
                                <div class="col-2" style="float: right">
                                    <select class="form-control" id="yearDate" name="year">
                                        <option value="2023" selected>2023</option>
                                        <option value="2022">2022</option>
                                        <option value="2024">2024</option>
                                    </select>
                                </div>
                            </div>

                            <div class="ml-4 mr-4">
                                <div class=" row text-center">
                                    <table class="table table-bordered" style="font-size: 13px">
                                        <thead>
                                            <tr>
                                                @foreach ($arrHeader as $item)
                                                <th>{{ $item }}</th>

                                                @endforeach
                                                <th id="th_year">2023</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tb_tahun">


                                        </tbody>

                                        <thead>
                                            <tr>

                                                <th colspan='17' style="background-color: white;"></th>
                                            </tr>
                                            <tr>
                                                @foreach ($arrHeaderSc as $key => $item)
                                                @if ($key == 0)
                                                <th colspan="3">{{ $item }}</th>
                                                @else
                                                <th>{{ $item }}</th>
                                                @endif
                                                @endforeach
                                                <th id="th_years">2023</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablewil">

                                        </tbody>

                                        <thead>
                                            <tr>

                                                <th colspan='17' style="background-color: white;"></th>
                                            </tr>

                                            <tr>
                                                @foreach ($arrHeaderReg as $key => $item)
                                                @if ($key == 0)
                                                <th colspan="3">{{ $item }}</th>
                                                @else
                                                <th>{{ $item }}</th>
                                                @endif
                                                @endforeach
                                                <th id="th_years">2023</th>
                                            </tr>
                                        </thead>
                                        <tbody id="reg">

                                        </tbody>

                                        <thead>
                                            <tr>

                                                <th colspan='17' style="background-color: white;"></th>
                                            </tr>
                                            <tr>
                                                @foreach ($arrHeaderTrd as $item)
                                                <th>{{ $item }}</th>
                                                @endforeach
                                                <th id="th_years">2023</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rekapAFD">

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <p style="font-size: 15px; text-align: center;"><b><u>BRONDOLAN TINGGAL</u></b></p>
                                            <div id="brondolanGraphYear"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <p style="font-size: 15px; text-align: center;"><b><u>BUAH TINGGAL</u></b></p>
                                            <div id="buahGraphYear"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <p style="font-size: 15px; text-align: center;"><b><u>BRONDOLAN TINGGAL</u></b></p>
                                            <div id="brondolanGraphWilYear"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <p style="font-size: 15px; text-align: center;"><b><u>BUAH TINGGAL</u></b></p>
                                            <div id="buahGraphWilYear"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>

                    <div class="tab-pane fade" id="nav-data" role="tabpanel" aria-labelledby="nav-data-tab">
                        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                            <h5><b>DATA</b></h5>
                        </div>

                        <div class="d-flex flex-row-reverse mr-3">
                            <button class="btn btn-primary mb-3" style="float: right" id="showDataIns">Show</button>
                            <div class="col-2 mr-2" style="float: right">
                                {{csrf_field()}}
                                <select class="form-control" id="regDataIns">
                                    <option value="1" selected>Regional 1</option>
                                    <option value="2">Regional 2</option>
                                    <option value="3">Regional 3</option>
                                </select>
                            </div>
                            <div class="col-2" style="float: right">
                                <input class="form-control" value="{{ date('Y-m') }}" type="month" name="tgl" id="dateDataIns">
                            </div>
                        </div>

                        <div class="ml-3 mr-3 mb-3">
                            <div class="row text-center tbl-fixed">
                                <table>
                                    <thead style="color: white;">
                                        <tr>
                                            {{-- <th rowspan="3" bgcolor="darkblue">Est.</th>
                                            <th rowspan="3" bgcolor="darkblue">Afd.</th> --}}
                                            <th class="freeze-col align-middle" rowspan="3" bgcolor="#1c5870">Est.</th>
                                            <th class="freeze-col align-middle" rowspan="3" bgcolor="#1c5870">Afd.</th>
                                            <th class="align-middle" colspan="4" rowspan="2" bgcolor="#588434">DATA BLOK SAMPEL</th>
                                            <th class="align-middle" colspan="17" bgcolor="#588434">Mutu Ancak (MA)</th>
                                            <th class="align-middle" colspan="8" bgcolor="blue">Mutu Transport (MT)</th>
                                            <th class="align-middle" colspan="22" bgcolor="#ffc404" style="color: #000000;">Mutu Buah (MB)
                                            <th class="align-middle" rowspan="3" bgcolor="gray" style="color: #fff;">All Skor</th>
                                            <th class="align-middle" rowspan="3" bgcolor="gray" style="color: #fff;">Kategori</th>
                                            </th>
                                        </tr>
                                        <tr>
                                            {{-- Table Mutu Ancak --}}
                                            <th class="align-middle" colspan="6" bgcolor="#588434">Brondolan Tinggal</th>
                                            <th class="align-middle" colspan="7" bgcolor="#588434">Buah Tinggal</th>
                                            <th class="align-middle" colspan="3" bgcolor="#588434">Pelepah Sengkleh</th>
                                            <th class="align-middle" rowspan="2" bgcolor="#588434">Total Skor</th>

                                            <th class="align-middle" rowspan="2" bgcolor="blue">TPH Sampel</th>
                                            <th class="align-middle" colspan="3" bgcolor="blue">Brd Tinggal</th>
                                            <th class="align-middle" colspan="3" bgcolor="blue">Buah Tinggal</th>
                                            <th class="align-middle" rowspan="2" bgcolor="blue">Total Skor</th>

                                            {{-- Table Mutu Buah --}}
                                            <th class="align-middle" rowspan="2" bgcolor="#ffc404" style="color: #000000;">Total Janjang
                                                Sampel</th>
                                            <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Mentah (A)</th>
                                            <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Matang (N)</th>
                                            <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Lewat Matang
                                                (O)</th>
                                            <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Janjang Kosong
                                                (E)</th>
                                            <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Tidak Standar
                                                V-Cut</th>
                                            <th class="align-middle" colspan="2" bgcolor="#ffc404" style="color: #000000;">Abnormal</th>
                                            <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Penggunaan
                                                Karung Brondolan</th>
                                            <th class="align-middle" rowspan="2" bgcolor="#ffc404" style="color: #000000;">Total Skor</th>
                                        </tr>
                                        <tr>
                                            {{-- Table Mutu Ancak --}}
                                            <th class="align-middle" bgcolor="#588434">Jumlah Pokok Sampel</th>
                                            <th class="align-middle" bgcolor="#588434">Luas Ha Sampel</th>
                                            <th class="align-middle" bgcolor="#588434">Jumlah Jjg Panen</th>
                                            <th class="align-middle" bgcolor="#588434">AKP Realisasi</th>
                                            <th class="align-middle" bgcolor="#588434">P</th>
                                            <th class="align-middle" bgcolor="#588434">K</th>
                                            <th class="align-middle" bgcolor="#588434">GL</th>
                                            <th class="align-middle" bgcolor="#588434">Total Brd</th>
                                            <th class="align-middle" bgcolor="#588434">Brd/JJG</th>
                                            <th class="align-middle" bgcolor="#588434">Skor</th>
                                            <th class="align-middle" bgcolor="#588434">S</th>
                                            <th class="align-middle" bgcolor="#588434">M1</th>
                                            <th class="align-middle" bgcolor="#588434">M2</th>
                                            <th class="align-middle" bgcolor="#588434">M3</th>
                                            <th class="align-middle" bgcolor="#588434">Total JJG</th>
                                            <th class="align-middle" bgcolor="#588434">%</th>
                                            <th class="align-middle" bgcolor="#588434">Skor</th>
                                            <th class="align-middle" bgcolor="#588434">Pokok </th>
                                            <th class="align-middle" bgcolor="#588434">%</th>
                                            <th class="align-middle" bgcolor="#588434">Skor</th>

                                            <th class="align-middle" bgcolor="blue">Butir</th>
                                            <th class="align-middle" bgcolor="blue">Butir/TPH</th>
                                            <th class="align-middle" bgcolor="blue">Skor</th>
                                            <th class="align-middle" bgcolor="blue">Jjg</th>
                                            <th class="align-middle" bgcolor="blue">Jjg/TPH</th>
                                            <th class="align-middle" bgcolor="blue">Skor</th>
                                            {{-- table mutu Buah --}}
                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Jjg</th>
                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">%</th>
                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Skor</th>

                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Jjg</th>
                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">%</th>
                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Skor</th>

                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Jjg</th>
                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">%</th>
                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Skor</th>

                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Jjg</th>
                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">%</th>
                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Skor</th>

                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Jjg</th>
                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">%</th>
                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Skor</th>

                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Jjg</th>
                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">%</th>

                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">TPH</th>
                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">%</th>
                                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Skor</th>
                                        </tr>
                                    </thead>

                                    <tbody id="dataInspeksi">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-issue" role="tabpanel" aria-labelledby="nav-issue-tab">
                        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                            <h5><b>QC PANEN</b></h5>
                        </div>

                        <div class="d-flex flex-row-reverse mr-3">
                            <button class="btn btn-primary mb-3" style="float: right" id="showFinding">Show</button>
                            <div class="col-2 mr-2" style="float: right">
                                {{csrf_field()}}
                                <select class="form-control" id="regFind">
                                    <option value="1" selected>Regional 1</option>
                                    <option value="2">Regional 2</option>
                                    <option value="3">Regional 3</option>
                                </select>
                            </div>
                            <div class="col-2" style="float: right">
                                <input class="form-control" value="{{ date('Y-m') }}" type="month" name="tgl" id="dateFind">
                            </div>
                        </div>

                        <div class="ml-4 mr-4">
                            <div class="row text-center">
                                <table class="table table-bordered" style="font-size: 13px">
                                    <thead bgcolor="gainsboro">
                                        <tr>
                                            <th rowspan="3" class="align-middle">ESTATE</th>
                                            <th colspan="5">Temuan Pemeriksaan Panen</th>
                                            <th rowspan="3" class="align-middle">Visit 1</th>
                                            <th rowspan="3" class="align-middle">Visit 2</th>
                                            <th rowspan="3" class="align-middle">Visit 3</th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2" class="align-middle">Jumlah</th>
                                            <th colspan="2">Tuntas</th>
                                            <th colspan="2">Belum Tuntas</thr>
                                        </tr>
                                        <tr>
                                            <th>Jumlah</th>
                                            <th>%</th>
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyFind">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-score" role="tabpanel" aria-labelledby="nav-score-tab">
                        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                            <h5><b>SCORE KUALITAS PANEN BERDASARKAN BLOK</b></h5>
                        </div>

                        <div class="d-flex flex-row-reverse mr-3">
                            <button class="btn btn-primary mb-3" style="float: right" id="showEstMap">Show</button>
                            <div class="col-2 mr-2" style="float: right">
                                {{csrf_field()}}
                                <select class="form-control" id="estDataMap">
                                    <option value="" disabled>Pilih Estate</option>
                                    @foreach ($estate as $key => $item)
                                    <option value="{{ $item['est'] }}">{{ $item['est'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="ml-4 mr-4 mb-3">
                            <div class="row text-center">
                                <div id="map" style="width: 100%; height: 700px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-grafik" role="tabpanel" aria-labelledby="nav-grafik-tab">
                        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                            <h5><b>GRAFIK SCORE</b></h5>
                        </div>

                        <div class="d-flex flex-row-reverse mr-3">
                            <button class="btn btn-primary mb-3" style="float: right" id="GraphFilter">Show</button>

                            <div class="col-2 mr-2" style="float: right">
                                {{csrf_field()}}
                                <select class="form-control" id="estData" name="estData">
                                    @foreach($listEstate as $item)
                                    <option value={{$item}}>{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2" style="float: right">
                                {{csrf_field()}}
                                <select class="form-control" id="yearGraph" name="yearGraph">
                                    <option value="2023" selected>2023</option>
                                    <option value="2022">2022</option>

                                </select>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col">
                                <div class="card-body">
                                    <p style="font-size: 15px"><b><u>HISTORIS SKOR</u></b></p>
                                    <div id="skorGraph"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col">
                                <div class="card-body">
                                    <p style="font-size: 15px"><b><u>HISTORIS BRONDOLAN TINGGAL DI ANCAK</u></b></p>
                                    <div id="skorBronGraph"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col">
                                <div class="card-body">
                                    <p style="font-size: 15px"><b><u>HISTORIS JANJANG TINGGAL DI ANCAK</u></b></p>
                                    <div id="skorJanGraph"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
@include('layout/footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    $(document).ready(function() {
        changeData()
        getFindData()
        dataDashboard()
        dashboard_tahun()
        graphFilter()


        setTimeout(function() {
            map.invalidateSize()
            removeMarkers()
            getPlotBlok()
            // getPlotEstate()
        }, 2000);
    });

    $("#showDataIns").click(function() {
        changeData()
    });

    $("#showFinding").click(function() {
        getFindData()
    });

    var map = L.map('map').setView([-2.2745234, 111.61404248], 13);

    googleSat = L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);

    var legendVar = ''

    $('#showEstMap').click(function() {
        map.removeControl(legendVar)
        removeMarkers()
        getPlotBlok()
        // getPlotEstate()
    });

    var titleEstate = new Array();

    function drawEstatePlot(est, plot) {
        var geoJsonEst = '{"type"'
        geoJsonEst += ":"
        geoJsonEst += '"FeatureCollection",'
        geoJsonEst += '"features"'
        geoJsonEst += ":"
        geoJsonEst += '['

        geoJsonEst += '{"type"'
        geoJsonEst += ":"
        geoJsonEst += '"Feature",'
        geoJsonEst += '"properties"'
        geoJsonEst += ":"
        geoJsonEst += '{"estate"'
        geoJsonEst += ":"
        geoJsonEst += '"' + est + '"},'
        geoJsonEst += '"geometry"'
        geoJsonEst += ":"
        geoJsonEst += '{"coordinates"'
        geoJsonEst += ":"
        geoJsonEst += '[['
        geoJsonEst += plot
        geoJsonEst += ']],"type"'
        geoJsonEst += ":"
        geoJsonEst += '"Polygon"'
        geoJsonEst += '}},'

        geoJsonEst = geoJsonEst.substring(0, geoJsonEst.length - 1);
        geoJsonEst += ']}'

        var estate = JSON.parse(geoJsonEst)

        var estateObj = L.geoJSON(estate, {
                onEachFeature: function(feature, layer) {
                    layer.myTag = 'EstateMarker'
                    var label = L.marker(layer.getBounds().getCenter(), {
                        icon: L.divIcon({
                            className: 'label-estate',
                            html: feature.properties.estate,
                            iconSize: [100, 20]
                        })
                    }).addTo(map);
                    titleEstate.push(label)
                    layer.addTo(map);
                }
            })
            .addTo(map);

        map.fitBounds(estateObj.getBounds());
    }

    var titleBlok = new Array();

    function drawBlokPlot(blok) {
        var getPlotStr = '{"type"'
        getPlotStr += ":"
        getPlotStr += '"FeatureCollection",'
        getPlotStr += '"features"'
        getPlotStr += ":"
        getPlotStr += '['

        for (let i = 0; i < blok.length; i++) {
            getPlotStr += '{"type"'
            getPlotStr += ":"
            getPlotStr += '"Feature",'
            getPlotStr += '"properties"'
            getPlotStr += ":"
            getPlotStr += '{"blok"'
            getPlotStr += ":"
            getPlotStr += '"' + blok[i][1]['blok'] + '",'
            getPlotStr += '"estate"'
            getPlotStr += ":"
            getPlotStr += '"' + blok[i][1]['estate'] + '",'
            getPlotStr += '"nilai"'
            getPlotStr += ":"
            getPlotStr += '"' + blok[i][1]['nilai'] + '"'
            getPlotStr += '},'
            getPlotStr += '"geometry"'
            getPlotStr += ":"
            getPlotStr += '{"coordinates"'
            getPlotStr += ":"
            getPlotStr += '[['
            getPlotStr += blok[i][1]['latln']
            getPlotStr += ']],"type"'
            getPlotStr += ":"
            getPlotStr += '"Polygon"'
            getPlotStr += '}},'
        }
        getPlotStr = getPlotStr.substring(0, getPlotStr.length - 1);
        getPlotStr += ']}'

        var blok = JSON.parse(getPlotStr)

        var test = L.geoJSON(blok, {
                onEachFeature: function(feature, layer) {
                    layer.myTag = 'BlokMarker'

                    var popupContent = "<p><b>Blok</b>: " + feature.properties.blok + "</p>";

                    var label = L.marker(layer.getBounds().getCenter(), {
                        icon: L.divIcon({
                            className: 'label-blok',
                            html: feature.properties.nilai,
                            iconSize: [50, 10]
                        })
                    }).addTo(map);
                    label.bindPopup(popupContent);

                    titleBlok.push(label)
                    layer.addTo(map);
                    layer.bindPopup(popupContent);
                },
                style: function(feature) {
                    var nilai = feature.properties.nilai;
                    if (nilai >= 95.0 && nilai <= 100.0) {
                        return {
                            fillColor: "#4874c4",
                            color: 'black',
                            fillOpacity: 0.7,
                            opacity: 0.3,
                        };
                    } else if (nilai >= 85.0 && nilai < 95.0) {
                        return {
                            fillColor: "#00ff2e",
                            color: 'black',
                            fillOpacity: 0.7,
                            opacity: 0.3,
                        };
                    } else if (nilai >= 75.0 && nilai < 85.0) {
                        return {
                            fillColor: "yellow",
                            color: 'black',
                            fillOpacity: 0.7,
                            opacity: 0.3,
                        };
                    } else if (nilai >= 65.0 && nilai < 75.0) {
                        return {
                            fillColor: "orange",
                            color: 'black',
                            fillOpacity: 0.7,
                            opacity: 0.3,
                        };
                    } else if (nilai == 0) {
                        return {
                            fillColor: "white",
                            color: 'black',
                            fillOpacity: 0.7,
                            opacity: 0.3,
                        };
                    } else if (nilai < 65.0) {
                        return {
                            fillColor: "red",
                            color: 'black',
                            fillOpacity: 0.7,
                            opacity: 0.3,
                        };
                    }
                }
            })
            .addTo(map);

        map.fitBounds(test.getBounds());
    }

    var removeMarkers = function() {
        map.eachLayer(function(layer) {
            if (layer.myTag && layer.myTag === "EstateMarker") {
                map.removeLayer(layer)
            }
            if (layer.myTag && layer.myTag === "BlokMarker") {
                map.removeLayer(layer)
            }
        });

        for (i = 0; i < titleBlok.length; i++) {
            map.removeLayer(titleBlok[i]);
        }
        for (i = 0; i < titleEstate.length; i++) {
            map.removeLayer(titleEstate[i]);
        }
    }

    function getPlotEstate() {
        var _token = $('input[name="_token"]').val();
        var estData = $("#estDataMap").val();
        const params = new URLSearchParams(window.location.search)
        var paramArr = [];
        for (const param of params) {
            paramArr = param
        }
        $.ajax({
            url: "{{ route('plotEstate') }}",
            method: "POST",
            data: {
                est: estData,
                _token: _token
            },
            success: function(result) {
                var estate = JSON.parse(result);
                drawEstatePlot(estate['est'], estate['plot'])
            }
        })
    }

    function getPlotBlok() {
        var _token = $('input[name="_token"]').val();
        var estData = $("#estDataMap").val();
        const params = new URLSearchParams(window.location.search)
        var paramArr = [];
        for (const param of params) {
            paramArr = param
        }

        $.ajax({
            url: "{{ route('plotBlok') }}",
            method: "POST",
            data: {
                est: estData,
                _token: _token
            },
            success: function(result) {
                var plot = JSON.parse(result);
                const blokResult = Object.entries(plot['blok']);
                const lgd = Object.entries(plot['legend']);
                drawBlokPlot(blokResult)

                var legend = L.control({
                    position: "bottomright"
                });
                legend.onAdd = function(map) {
                    var div = L.DomUtil.create("div", "legend");
                    div.innerHTML += '<table class="table table-bordered text center" style="height:fit-content; font-size: 12px;"> <thead> <tr bgcolor="lightgrey"> <th rowspan="2" class="align-middle">Score</th><th colspan="2">Blok</th> </tr> <tr bgcolor="lightgrey"> <th>Jumlah</th> <th>%</th> </tr> </thead> <tbody><tr><td bgcolor="#4874c4">Excellent</td><td>' + lgd[0][1] + '</td><td>' + lgd[6][1] + '</td></tr><tr><td bgcolor="#00ff2e">Good</td><td>' + lgd[1][1] + '</td><td>' + lgd[7][1] + '</td></tr><tr><td bgcolor="yellow">Satisfactory</td><td>' + lgd[2][1] + '</td><td>' + lgd[8][1] + '</td></tr><tr><td bgcolor="orange">Fair</td><td>' + lgd[3][1] + '</td><td>' + lgd[9][1] + '</td></tr><tr><td bgcolor="red">Poor</td><td>' + lgd[4][1] + '</td><td>' + lgd[10][1] + '</td></tr><tr bgcolor="lightgrey"><td>TOTAL</td><td colspan="2">' + lgd[5][1] + '</td></tr></tbody></table>';
                    return div;
                };
                legend.addTo(map);

                legendVar = legend
            }
        })
    }

    function changeData() {
        var regIns = $("#regDataIns").val();
        var dateIns = $("#dateDataIns").val();
        var _token = $('input[name="_token"]').val();

        $.ajax({
            url: "{{ route('changeDataInspeksi') }}",
            method: "POST",
            cache: false,
            data: {
                _token: _token,
                regional: regIns,
                date: dateIns
            },
            success: function(result) {
                $("#dataInspeksi").html(result);
            }
        });
    }

    function getFindData() {
        $('#bodyFind').empty()

        var regional = $("#regFind").val();
        var date = $("#dateFind").val();
        var _token = $('input[name="_token"]').val();

        $.ajax({
            url: "{{ route('getFindData') }}",
            method: "POST",
            data: {
                regional: regional,
                date: date,
                _token: _token
            },
            success: function(result) {
                var parseResult = JSON.parse(result)
                var dataResFind = Object.entries(parseResult['dataResFind']) //parsing data brondolan ke dalam var list

                //   console.log(dataResFind[0])
                dataResFind.forEach(function(value, key) {
                    dataResFind[key].forEach(function(value1, key1) {
                        Object.entries(value1).forEach(function(value2, key2) {
                            if (value2[0] != 0) {
                                // console.log(value2)
                                var tbody1 = document.getElementById('bodyFind');

                                tr = document.createElement('tr')

                                let item1 = value2[0]
                                let item2 = value2[1]['total_temuan']
                                let item3 = value2[1]['tuntas']
                                let item4 = value2[1]['perTuntas']
                                let item5 = value2[1]['no_tuntas']
                                let item6 = value2[1]['perNoTuntas']

                                let itemElement1 = document.createElement('td')
                                let itemElement2 = document.createElement('td')
                                let itemElement3 = document.createElement('td')
                                let itemElement4 = document.createElement('td')
                                let itemElement5 = document.createElement('td')
                                let itemElement6 = document.createElement('td')
                                let itemElement7 = document.createElement('td')
                                let itemElement8 = document.createElement('td')
                                let itemElement9 = document.createElement('td')

                                itemElement1.innerText = item1
                                itemElement2.innerText = item2
                                itemElement3.innerText = item3
                                itemElement4.innerText = item4
                                itemElement5.innerText = item5
                                itemElement6.innerText = item6
                                itemElement7.innerHTML = '<a href="/cetakPDFFI/1/' + value2[0] + '/' + date + '" class="btn btn-primary" target="_blank"><i class="nav-icon fa fa-download"></i></a>'
                                itemElement8.innerHTML = '<a href="/cetakPDFFI/2/' + value2[0] + '/' + date + '" class="btn btn-primary" target="_blank"><i class="nav-icon fa fa-download"></i></a>'
                                itemElement9.innerHTML = '<a href="/cetakPDFFI/3/' + value2[0] + '/' + date + '" class="btn btn-primary" target="_blank"><i class="nav-icon fa fa-download"></i></a>'

                                tr.appendChild(itemElement1)
                                tr.appendChild(itemElement2)
                                tr.appendChild(itemElement3)
                                tr.appendChild(itemElement4)
                                tr.appendChild(itemElement5)
                                tr.appendChild(itemElement6)
                                tr.appendChild(itemElement7)
                                tr.appendChild(itemElement8)
                                tr.appendChild(itemElement9)
                                tbody1.appendChild(tr)
                            }
                        });
                    });
                });
            }
        });
    }

    var skor = document.getElementById("skor").innerHTML;
    if (skor < 65) {
        document.getElementById("skor").style.backgroundColor = "#ff0404";
        document.getElementById("skor").style.color = "black";
    } else if (skor >= 65 && skor < 75) {
        document.getElementById("skor").style.backgroundColor = "#ffc404";
    } else {
        document.getElementById("skor").style.backgroundColor = "#fffc04";
    }

    //testing  table ke tengah

    const c = document.getElementById('btnShow');
    const o = document.getElementById('regionalPanen');
    const s = document.getElementById("Tab1");
    const m = document.getElementById("Tab2");
    const l = document.getElementById("Tab3");
    c.addEventListener('click', function() {
        const c = o.value;
        // class="col-sm-3"
        if (c === '2') {
            s.classList.remove("col-sm-3");
            s.classList.add("col-sm-4");
            m.classList.remove("col-sm-3");
            m.classList.add("col-sm-4");
            l.classList.remove("col-sm-3");
            l.classList.add("col-sm-4")
        } else {
            s.classList.remove("col-sm-4");
            s.classList.add("col-sm-3");
            m.classList.remove("col-sm-4");
            m.classList.add("col-sm-3");
            l.classList.remove("col-sm-4");
            l.classList.add("col-sm-3")
        }
        if (c === '3') {
            s.classList.remove("col-sm-3");
            s.classList.add("col-sm-6");
            m.classList.remove("col-sm-3");
            m.classList.add("col-sm-6")
        } else {
            s.classList.remove("col-sm-6");
            s.classList.add("col-sm-3");
            m.classList.remove("col-sm-6");
            m.classList.add("col-sm-3")
        }
    });
    //untuk chart
    var list_wilayah = <?php echo json_encode($queryEsta); ?>;
    var list_btt = <?php echo json_encode($chartBTT); ?>;
    var list_buah = <?php echo json_encode($chartBuahTT); ?>;
    var list_brdWil = <?php echo json_encode($chartPerwil); ?>;
    var list_buahWil = <?php echo json_encode($buahPerwil); ?>;

    var wilayah = '['
    list_wilayah.forEach(element => {
        wilayah += '"' + element + '",'
    });
    wilayah = wilayah.substring(0, wilayah.length - 1);
    wilayah += ']'



    var buahWil = '['
    list_brdWil.forEach(element => {
        buahWil += '"' + element + '",'
    });
    buahWil = buahWil.substring(0, buahWil.length - 1);
    buahWil += ']'

    var btWill = '['
    list_buahWil.forEach(element => {
        btWill += '"' + element + '",'
    });
    btWill = btWill.substring(0, btWill.length - 1);
    btWill += ']'

    var estate = JSON.parse(wilayah)
    var brd_wil = JSON.parse(buahWil)
    var buah_wil = JSON.parse(btWill)
    // console.log(estate);


    ///Data test


    var options = {

        series: [{
            name: '',
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        }],
        chart: {
            background: '#ffffff',
            height: 350,
            type: 'bar'
        },
        plotOptions: {
            bar: {
                distributed: true
            }
        },

        colors: [
            '#00FF00',
            '#00FF00',
            '#00FF00',
            '#00FF00',
            '#3063EC',
            '#3063EC',
            '#3063EC',
            '#3063EC',
            '#FF8D1A',
            '#FF8D1A',
            '#FF8D1A',
            '#FF8D1A',
            '#00ffff'
        ],

        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            type: '',
            categories: estate
        }
    };

    //chart wil
    var will = {
        series: [{
            name: 'Butir/Ha Sample',
            data: [0, 0, 0]
        }],
        chart: {
            height: 350,
            type: 'bar'
        },
        plotOptions: {
            bar: {
                distributed: true
            }
        },
        colors: ['#00FF00', '#3063EC', '#FF8D1A'],
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            type: '',
            categories: ['WIL-I', 'WIL-II', 'WIL-III']
        }
    };




    //chart untuk perbulan
    var chartGrain = new ApexCharts(document.querySelector("#brondolanGraph"), options);
    chartGrain.render();
    var chartFruit = new ApexCharts(document.querySelector("#buahGraph"), options);
    chartFruit.render();

    var chartGrainWil = new ApexCharts(document.querySelector("#brondolanGraphWil"), will);
    chartGrainWil.render();
    var chartFruitWil = new ApexCharts(document.querySelector("#buahGraphWil"), will);
    chartFruitWil.render();
    ///chart untuk pertahun
    var chartGrainYear = new ApexCharts(document.querySelector("#brondolanGraphYear"), options);
    chartGrainYear.render();
    var chartFruitYear = new ApexCharts(document.querySelector("#buahGraphYear"), options);
    chartFruitYear.render();

    var chartGrainWilYear = new ApexCharts(document.querySelector("#brondolanGraphWilYear"), will);
    chartGrainWilYear.render();

    var chartFruitWilYear = new ApexCharts(document.querySelector("#buahGraphWilYear"), will);
    chartFruitWilYear.render();

    document.getElementById('btnShow').onclick = function() {
        dataDashboard()
    }

    function dataDashboard() {
        $('#tbody1').empty()
        $('#tbody2').empty()
        $('#tbody3').empty()
        $('#thead1').empty()
        $('#thead2').empty()
        $('#thead3').empty()
        $('#theadreg').empty()
        $('#plbody').empty()

        var date = ''
        var reg = ''
        var _token = $('input[name="_token"]').val();
        var date = document.getElementById('inputDate').value
        var reg = document.getElementById('regionalPanen').value

        // console.log(date);
        $.ajax({
            url: "{{ route('filter') }}",
            method: "GET",
            data: {
                date,
                reg,
                _token: _token
            },
            success: function(result) {

                // console.log(reg);
                var parseResult = JSON.parse(result)
                //list estate
                var list_will = Object.entries(parseResult['list_estate']);

                // //untuk chart
                var chart_btt = Object.entries(parseResult['chart_brd'])
                var chart_buah = Object.entries(parseResult['chart_buah'])

                var chartWillbt = Object.entries(parseResult['chart_brdwil'])
                var chartWillbh = Object.entries(parseResult['chart_buahwil'])
                //perbaikan untuk table utama unutuk rekap 
                // var Data_TableUtama = Object.entries(parseResult['data_tabelutama'])
                // console.log(Data_TableUtama);
                // unutk table utama
                const Data_TableUtama = Object.entries(parseResult['data_tabelutama']);
                const regional = Object.entries(parseResult['RekapRegTable']);
                const plasma = Object.entries(parseResult['plasma']);
                const plasmaEM = Object.entries(parseResult['plasmaEM']);
                const plasmaGM = Object.entries(parseResult['plasmaGM']);
                // console.log(plasma);
                const newPlasma = plasma.map(([_, data]) => ({

                    est: data.est,
                    afd: data.afd,
                    nama: data.nama,
                    skor: data.skor,
                    rank: data.rank,
                    // namaEM: namaEM,
                    // namaGM: namaGM,
                }));
                const newPlasmaEM = plasmaEM.map(([_, data]) => ({

                    est: data.est,
                    afd: data.afd,
                    nama: data.namaEM,
                    skor: data.Skor,

                    // namaEM: namaEM,
                    // namaGM: namaGM,
                }));
                const newPlasmaGM = plasmaGM.map(([_, data]) => ({

                    est: data.est,
                    afd: data.afd,
                    nama: data.namaEM,
                    skor: data.Skor,

                    // namaEM: namaEM,
                    // namaGM: namaGM,
                }));
                // console.log(newPlasmaEM);

                const newData_TableUtama = Data_TableUtama.map(([_, data]) => ({
                    afd: data.afd,
                    est: data.est,
                    est_afd: `${data.est}_${data.afd}`,
                    nama: data.nama,
                    rank: data.rank,
                    skor: data.skor,
                }));
                const Data_TableKedua = Object.entries(parseResult['data_tabelkedua']);

                // console.log(Data_TableKedua);
                const newData_TableKedua = Data_TableKedua.map(([_, data]) => ({
                    afd: data.afd,
                    est: data.est,
                    est_afd: `${data.est}_${data.afd}`,
                    nama: data.nama,
                    rank: data.rank,
                    skor: data.skor,
                }));
                const Data_TableKetiga = Object.entries(parseResult['data_tabeketiga']);
                const newData_TableKetiga = Data_TableKetiga.map(([_, data]) => ({
                    afd: data.afd,
                    est: data.est,
                    est_afd: `${data.est}_${data.afd}`,
                    nama: data.nama,
                    rank: data.rank,
                    skor: data.skor,
                }));


                //untuk table perestate
                const data_Est1 = Object.entries(parseResult['data_Est1']);
                const newData_data_Est1 = data_Est1.map(([_, data]) => ({

                    est: data.est,
                    em: data.EM,
                    nama: data.nama,
                    rank: data.rank,
                    skor: data.skor,
                }));
                const data_Est2 = Object.entries(parseResult['data_Est2']);
                const newData_data_Est2 = data_Est2.map(([_, data]) => ({

                    est: data.est,
                    em: data.EM,
                    nama: data.nama,
                    rank: data.rank,
                    skor: data.skor,
                }));

                const data_Est3 = Object.entries(parseResult['data_Est3']);



                const newData_data_Est3 = data_Est3.map(([_, data]) => ({

                    est: data.est,
                    em: data.EM,
                    nama: data.nama,
                    rank: data.rank,
                    skor: data.skor,
                }));

                const data_GM = Object.entries(parseResult['data_GM']);
                const GM_list = data_GM.map(([_, data]) => ({

                    est: data.est,
                    em: data.EM,
                    nama: data.nama,
                    skor: data.skor,
                }));
                //testing table otomatis ketengah
                let regInpt = reg;

                var GM_1 = Object.entries(parseResult['GM_1'])
                var GM_2 = Object.entries(parseResult['GM_2'])
                var GM_3 = Object.entries(parseResult['GM_3'])

                //buat menambahkan berdsarkan inputan reg 

                let regIonal = '';
                let regIonalRH = '';
                let regIonalNama = '';
                let titleHead1 = '';
                let titleHead2 = '';
                let titleHead3 = '';

                switch (regInpt) {
                    case '1':
                        regIonal = 'REG-I';
                        regIonalRH = 'RH-I';
                        regIonalNama = 'Akhmad Faisyal';
                        titleHead1 = 'WIL I';
                        titleHead2 = 'WIL II';
                        titleHead3 = 'WIL III';
                        break;
                    case '2':
                        regIonal = 'REG-II';
                        regIonalRH = 'RH-II';
                        regIonalNama = '';
                        titleHead1 = 'WIL IV';
                        titleHead2 = 'WIL V';
                        titleHead3 = 'WIL VI';
                        break;
                    case '3':
                        regIonal = 'REG-III';
                        regIonalRH = 'RH-III';
                        regIonalNama = '';
                        titleHead1 = 'WIL VII';
                        titleHead2 = 'WIL VIII';
                        titleHead3 = 'WIL NULL';

                        break;
                }




                var th1 = document.getElementById('thead1');
                var th2 = document.getElementById('thead2');
                var th3 = document.getElementById('thead3');
                if (th3) {
                    th3.innerText = newHeaderText3;
                }
                var newHeaderText1 = titleHead1;
                var newHeaderText2 = titleHead2;
                var newHeaderText3 = titleHead3;
                th1.innerText = newHeaderText1;
                th2.innerText = newHeaderText2;
                if (th3) {
                    th3.innerText = newHeaderText3;
                }
                // console.log(newData_TableUtama);
                var table3 = document.getElementById('Reg3');
                if (regInpt === '3') {
                    table3.style.display = 'none'; // hide the table
                } else {
                    table3.style.display = ''; // show the table
                }

                function filterArrayByEst(array) {
                    return array.filter(obj => obj.est !== 'Plasma1');
                }
                const originalArray = newData_TableUtama
                const filteredArray = filterArrayByEst(originalArray);



                // console.log(filteredArray);
                var arrTbody1 = filteredArray

                var tbody1 = document.getElementById('tbody1');
                //         $('#thead1').empty()
                // $('#thead2').empty()
                // $('#thead3').empty()

                arrTbody1.forEach(element => {

                    tr = document.createElement('tr')
                    let item1 = element['est']
                    let item2 = element['afd']
                    let item3 = element['nama']
                    let item4 = element['skor']
                    let item5 = element['rank']

                    let itemElement1 = document.createElement('td')
                    let itemElement2 = document.createElement('td')
                    let itemElement3 = document.createElement('td')
                    let itemElement4 = document.createElement('td')
                    let itemElement5 = document.createElement('td')



                    itemElement1.classList.add("text-center")
                    itemElement2.classList.add("text-center")
                    itemElement3.classList.add("text-center")
                    itemElement4.classList.add("text-center")
                    itemElement5.classList.add("text-center")



                    if (item4 >= 95) {
                        itemElement4.style.backgroundColor = "#609cd4";
                    } else if (item4 >= 85 && item4 < 95) {
                        itemElement4.style.backgroundColor = "#08b454";
                    } else if (item4 >= 75 && item4 < 85) {
                        itemElement4.style.backgroundColor = "#fffc04";
                    } else if (item4 >= 65 && item4 < 75) {
                        itemElement4.style.backgroundColor = "#ffc404";
                    } else {
                        itemElement4.style.backgroundColor = "red";
                    }

                    if (itemElement4.style.backgroundColor === "#609cd4") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#08b454") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#fffc04") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "#ffc404") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "red") {
                        itemElement4.style.color = "white";
                    }


                    // if (item4 != 0 && item4 != 90) {
                    //     itemElement4.innerHTML = '<a href="detailInpeksi/' + element['est'] + '/' + element['afd'] + '/' + date + '">' + element['skor'] + ' </a>'
                    // } else {
                    //     itemElement4.innerText = item4
                    // }

                    itemElement4.innerText = item4
                    itemElement1.innerText = item1
                    itemElement2.innerHTML = '<a href="detailInpeksi/' + element['est'] + '/' + element['afd'] + '/' + date + '" target="_blank">' + element['afd'] + ' </a>';

                    // itemElement2.innerText = item2
                    itemElement3.innerText = item3
                    //   itemElement4.innerText  = item4
                    itemElement5.innerText = item5

                    tr.appendChild(itemElement1)
                    tr.appendChild(itemElement2)
                    tr.appendChild(itemElement3)
                    tr.appendChild(itemElement4)
                    tr.appendChild(itemElement5)

                    tbody1.appendChild(tr)
                    // }
                });

                const arrTab1 = newData_data_Est1
                const EstTab1 = filterArrayByEst(arrTab1);
                var arrTbody1 = EstTab1
                // console.log(arrTbody1);
                // var table1 = document.getElementById('table1');
                var tbody1 = document.getElementById('tbody1');


                arrTbody1.forEach(element => {
                    // for (let i = 0; i < 5; i++) {

                    tr = document.createElement('tr')
                    let item1 = element['est']
                    let item2 = element['em']
                    let item3 = element['nama']
                    let item4 = element['skor']
                    let item5 = element['rank']


                    let itemElement1 = document.createElement('td')
                    let itemElement2 = document.createElement('td')
                    let itemElement3 = document.createElement('td')
                    let itemElement4 = document.createElement('td')
                    let itemElement5 = document.createElement('td')



                    itemElement1.classList.add("text-center")
                    itemElement2.classList.add("text-center")
                    itemElement3.classList.add("text-center")
                    itemElement4.classList.add("text-center")
                    itemElement5.classList.add("text-center")


                    itemElement1.style.backgroundColor = "#e8ecdc";
                    itemElement2.style.backgroundColor = "#e8ecdc";
                    itemElement3.style.backgroundColor = "#e8ecdc";
                    if (item4 >= 95) {
                        itemElement4.style.backgroundColor = "#609cd4";
                    } else if (item4 >= 85 && item4 < 95) {
                        itemElement4.style.backgroundColor = "#08b454";
                    } else if (item4 >= 75 && item4 < 85) {
                        itemElement4.style.backgroundColor = "#fffc04";
                    } else if (item4 >= 65 && item4 < 75) {
                        itemElement4.style.backgroundColor = "#ffc404";
                    } else {
                        itemElement4.style.backgroundColor = "red";
                    }

                    if (itemElement4.style.backgroundColor === "#609cd4") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#08b454") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#fffc04") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "#ffc404") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "red") {
                        itemElement4.style.color = "white";
                    }


                    itemElement4.innerText = item4;
                    itemElement1.innerText = item1
                    itemElement2.innerText = item2
                    itemElement3.innerText = item3
                    itemElement4.innerText = item4
                    itemElement5.innerText = item5

                    tr.appendChild(itemElement1)
                    tr.appendChild(itemElement2)
                    tr.appendChild(itemElement3)
                    tr.appendChild(itemElement4)
                    tr.appendChild(itemElement5)

                    tbody1.appendChild(tr)
                    // }
                });

                //untuk GM
                tr = document.createElement('tr')
                let item1 = GM_list[0].est;
                let item2 = GM_list[0].em;
                let item3 = GM_list[0].nama;
                let item4 = GM_list[0].skor;
                let item5 = ''
                let itemElement1 = document.createElement('td')
                let itemElement2 = document.createElement('td')
                let itemElement3 = document.createElement('td')
                let itemElement4 = document.createElement('td')
                let itemElement5 = document.createElement('td')
                itemElement1.classList.add("text-center")
                itemElement2.classList.add("text-center")
                itemElement3.classList.add("text-center")
                itemElement4.classList.add("text-center")
                itemElement5.classList.add("text-center")
                itemElement1.style.backgroundColor = "#fff4cc";
                itemElement2.style.backgroundColor = "#fff4cc";
                itemElement3.style.backgroundColor = "#fff4cc";
                if (item4 >= 95) {
                    itemElement4.style.backgroundColor = "#0804fc";
                } else if (item4 >= 85 && item4 < 95) {
                    itemElement4.style.backgroundColor = "#08b454";
                } else if (item4 >= 75 && item4 < 85) {
                    itemElement4.style.backgroundColor = "#fffc04";
                } else if (item4 >= 65 && item4 < 75) {
                    itemElement4.style.backgroundColor = "#ffc404";
                } else {
                    itemElement4.style.backgroundColor = "red";
                }
                itemElement1.innerText = item1;
                itemElement2.innerText = item2;
                itemElement3.innerText = item3;
                itemElement4.innerText = item4;
                itemElement5.innerText = item5;
                tr.appendChild(itemElement1)
                tr.appendChild(itemElement2)
                tr.appendChild(itemElement3)
                tr.appendChild(itemElement4)
                tr.appendChild(itemElement5)
                tbody1.appendChild(tr)

                //table wil 2
                var arrTbody2 = newData_TableKedua


                var tbody2 = document.getElementById('tbody2');


                arrTbody2.forEach(element => {

                    tr = document.createElement('tr')
                    let item1 = element['est']
                    let item2 = element['afd']
                    let item3 = element['nama']
                    let item4 = element['skor']
                    let item5 = element['rank']

                    let itemElement1 = document.createElement('td')
                    let itemElement2 = document.createElement('td')
                    let itemElement3 = document.createElement('td')
                    let itemElement4 = document.createElement('td')
                    let itemElement5 = document.createElement('td')



                    itemElement1.classList.add("text-center")
                    itemElement2.classList.add("text-center")
                    itemElement3.classList.add("text-center")
                    itemElement4.classList.add("text-center")
                    itemElement5.classList.add("text-center")



                    if (item4 >= 95) {
                        itemElement4.style.backgroundColor = "#609cd4";
                    } else if (item4 >= 85 && item4 < 95) {
                        itemElement4.style.backgroundColor = "#08b454";
                    } else if (item4 >= 75 && item4 < 85) {
                        itemElement4.style.backgroundColor = "#fffc04";
                    } else if (item4 >= 65 && item4 < 75) {
                        itemElement4.style.backgroundColor = "#ffc404";
                    } else {
                        itemElement4.style.backgroundColor = "red";
                    }

                    if (itemElement4.style.backgroundColor === "#609cd4") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#08b454") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#fffc04") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "#ffc404") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "red") {
                        itemElement4.style.color = "white";
                    }



                    // if (item4 != 0 && item4 != 90) {
                    //     itemElement4.innerHTML = '<a href="detailInpeksi/' + element['est'] + '/' + element['afd'] + '/' + date + '">' + element['skor'] + ' </a>'
                    // } else {
                    //     itemElement4.innerText = item4
                    // }
                    itemElement1.innerText = item1
                    // itemElement2.innerText = item2
                    itemElement2.innerHTML = '<a href="detailInpeksi/' + element['est'] + '/' + element['afd'] + '/' + date + '" target="_blank">' + element['afd'] + ' </a>';

                    itemElement3.innerText = item3
                    itemElement4.innerText = item4
                    itemElement5.innerText = item5

                    tr.appendChild(itemElement1)
                    tr.appendChild(itemElement2)
                    tr.appendChild(itemElement3)
                    tr.appendChild(itemElement4)
                    tr.appendChild(itemElement5)

                    tbody2.appendChild(tr)
                    // }
                });
                var arrTbody2 = newData_data_Est2
                // var table1 = document.getElementById('table1');
                var tbody2 = document.getElementById('tbody2');


                arrTbody2.forEach(element => {
                    // for (let i = 0; i < 5; i++) {

                    tr = document.createElement('tr')
                    let item1 = element['est']
                    let item2 = element['em']
                    let item3 = element['nama']
                    let item4 = element['skor']
                    let item5 = element['rank']


                    let itemElement1 = document.createElement('td')
                    let itemElement2 = document.createElement('td')
                    let itemElement3 = document.createElement('td')
                    let itemElement4 = document.createElement('td')
                    let itemElement5 = document.createElement('td')



                    itemElement1.classList.add("text-center")
                    itemElement2.classList.add("text-center")
                    itemElement3.classList.add("text-center")
                    itemElement4.classList.add("text-center")
                    itemElement5.classList.add("text-center")


                    itemElement1.style.backgroundColor = "#e8ecdc";
                    itemElement2.style.backgroundColor = "#e8ecdc";
                    itemElement3.style.backgroundColor = "#e8ecdc";
                    if (item4 >= 95) {
                        itemElement4.style.backgroundColor = "#609cd4";
                    } else if (item4 >= 85 && item4 < 95) {
                        itemElement4.style.backgroundColor = "#08b454";
                    } else if (item4 >= 75 && item4 < 85) {
                        itemElement4.style.backgroundColor = "#fffc04";
                    } else if (item4 >= 65 && item4 < 75) {
                        itemElement4.style.backgroundColor = "#ffc404";
                    } else {
                        itemElement4.style.backgroundColor = "red";
                    }

                    if (itemElement4.style.backgroundColor === "#609cd4") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#08b454") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#fffc04") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "#ffc404") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "red") {
                        itemElement4.style.color = "white";
                    }


                    itemElement4.innerText = item4;
                    itemElement1.innerText = item1
                    itemElement2.innerText = item2
                    itemElement3.innerText = item3
                    itemElement4.innerText = item4
                    itemElement5.innerText = item5

                    tr.appendChild(itemElement1)
                    tr.appendChild(itemElement2)
                    tr.appendChild(itemElement3)
                    tr.appendChild(itemElement4)
                    tr.appendChild(itemElement5)

                    tbody2.appendChild(tr)
                    // }
                });
                //untuk GM
                tr = document.createElement('tr')
                let items1 = GM_list[1].est;
                let items2 = GM_list[1].em;
                let items3 = GM_list[1].nama;
                let items4 = GM_list[1].skor;
                let items5 = ''
                let itemsElement1 = document.createElement('td')
                let itemsElement2 = document.createElement('td')
                let itemsElement3 = document.createElement('td')
                let itemsElement4 = document.createElement('td')
                let itemsElement5 = document.createElement('td')
                itemsElement1.classList.add("text-center")
                itemsElement2.classList.add("text-center")
                itemsElement3.classList.add("text-center")
                itemsElement4.classList.add("text-center")
                itemsElement5.classList.add("text-center")
                itemsElement1.style.backgroundColor = "#fff4cc";
                itemsElement2.style.backgroundColor = "#fff4cc";
                itemsElement3.style.backgroundColor = "#fff4cc";
                if (items4 >= 95) {
                    itemsElement4.style.backgroundColor = "#0804fc";
                } else if (items4 >= 85 && items4 < 95) {
                    itemsElement4.style.backgroundColor = "#08b454";
                } else if (items4 >= 75 && items4 < 85) {
                    itemsElement4.style.backgroundColor = "#fffc04";
                } else if (items4 >= 65 && items4 < 75) {
                    itemsElement4.style.backgroundColor = "#ffc404";
                } else {
                    itemsElement4.style.backgroundColor = "red";
                }
                itemsElement1.innerText = items1;
                itemsElement2.innerText = items2;
                itemsElement3.innerText = items3;
                itemsElement4.innerText = items4;
                itemsElement5.innerText = items5;
                tr.appendChild(itemsElement1)
                tr.appendChild(itemsElement2)
                tr.appendChild(itemsElement3)
                tr.appendChild(itemsElement4)
                tr.appendChild(itemsElement5)
                tbody2.appendChild(tr)

                //table wil 2
                var arrTbody3 = newData_TableKetiga


                var tbody3 = document.getElementById('tbody3');


                arrTbody3.forEach(element => {

                    tr = document.createElement('tr')
                    let item1 = element['est']
                    let item2 = element['afd']
                    let item3 = element['nama']
                    let item4 = element['skor']
                    let item5 = element['rank']

                    let itemElement1 = document.createElement('td')
                    let itemElement2 = document.createElement('td')
                    let itemElement3 = document.createElement('td')
                    let itemElement4 = document.createElement('td')
                    let itemElement5 = document.createElement('td')



                    itemElement1.classList.add("text-center")
                    itemElement2.classList.add("text-center")
                    itemElement3.classList.add("text-center")
                    itemElement4.classList.add("text-center")
                    itemElement5.classList.add("text-center")



                    if (item4 >= 95) {
                        itemElement4.style.backgroundColor = "#609cd4";
                    } else if (item4 >= 85 && item4 < 95) {
                        itemElement4.style.backgroundColor = "#08b454";
                    } else if (item4 >= 75 && item4 < 85) {
                        itemElement4.style.backgroundColor = "#fffc04";
                    } else if (item4 >= 65 && item4 < 75) {
                        itemElement4.style.backgroundColor = "#ffc404";
                    } else {
                        itemElement4.style.backgroundColor = "red";
                    }

                    if (itemElement4.style.backgroundColor === "#609cd4") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#08b454") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#fffc04") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "#ffc404") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "red") {
                        itemElement4.style.color = "white";
                    }



                    // if (item4 != 0 && item4 != 90) {
                    //     itemElement4.innerHTML = '<a href="detailInpeksi/' + element['est'] + '/' + element['afd'] + '/' + date + '">' + element['skor'] + ' </a>'
                    // } else {
                    //     itemElement4.innerText = item4
                    // }
                    itemElement1.innerText = item1
                    // itemElement2.innerText = item2
                    itemElement2.innerHTML = '<a href="detailInpeksi/' + element['est'] + '/' + element['afd'] + '/' + date + '" target="_blank">' + element['afd'] + ' </a>';

                    itemElement3.innerText = item3
                    itemElement4.innerText = item4
                    itemElement5.innerText = item5

                    tr.appendChild(itemElement1)
                    tr.appendChild(itemElement2)
                    tr.appendChild(itemElement3)
                    tr.appendChild(itemElement4)
                    tr.appendChild(itemElement5)

                    tbody3.appendChild(tr)
                    // }
                });

                var arrTbody3 = newData_data_Est3
                // var table1 = document.getElementById('table1');
                var tbody3 = document.getElementById('tbody3');


                arrTbody3.forEach(element => {
                    // for (let i = 0; i < 5; i++) {

                    tr = document.createElement('tr')
                    let item1 = element['est']
                    let item2 = element['em']
                    let item3 = element['nama']
                    let item4 = element['skor']
                    let item5 = element['rank']


                    let itemElement1 = document.createElement('td')
                    let itemElement2 = document.createElement('td')
                    let itemElement3 = document.createElement('td')
                    let itemElement4 = document.createElement('td')
                    let itemElement5 = document.createElement('td')



                    itemElement1.classList.add("text-center")
                    itemElement2.classList.add("text-center")
                    itemElement3.classList.add("text-center")
                    itemElement4.classList.add("text-center")
                    itemElement5.classList.add("text-center")


                    itemElement1.style.backgroundColor = "#e8ecdc";
                    itemElement2.style.backgroundColor = "#e8ecdc";
                    itemElement3.style.backgroundColor = "#e8ecdc";
                    if (item4 >= 95) {
                        itemElement4.style.backgroundColor = "#609cd4";
                    } else if (item4 >= 85 && item4 < 95) {
                        itemElement4.style.backgroundColor = "#08b454";
                    } else if (item4 >= 75 && item4 < 85) {
                        itemElement4.style.backgroundColor = "#fffc04";
                    } else if (item4 >= 65 && item4 < 75) {
                        itemElement4.style.backgroundColor = "#ffc404";
                    } else {
                        itemElement4.style.backgroundColor = "red";
                    }

                    if (itemElement4.style.backgroundColor === "#609cd4") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#08b454") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#fffc04") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "#ffc404") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "red") {
                        itemElement4.style.color = "white";
                    }


                    itemElement4.innerText = item4;
                    itemElement1.innerText = item1
                    itemElement2.innerText = item2
                    itemElement3.innerText = item3
                    itemElement4.innerText = item4
                    itemElement5.innerText = item5

                    tr.appendChild(itemElement1)
                    tr.appendChild(itemElement2)
                    tr.appendChild(itemElement3)
                    tr.appendChild(itemElement4)
                    tr.appendChild(itemElement5)

                    tbody3.appendChild(tr)
                    // }
                });

                //untuk GM
                tr = document.createElement('tr')
                let itemx1 = GM_list[2].est;
                let itemx2 = GM_list[2].em;
                let itemx3 = GM_list[2].nama;
                let itemx4 = GM_list[2].skor;
                let itemx5 = ''
                let itemxElement1 = document.createElement('td')
                let itemxElement2 = document.createElement('td')
                let itemxElement3 = document.createElement('td')
                let itemxElement4 = document.createElement('td')
                let itemxElement5 = document.createElement('td')
                itemxElement1.classList.add("text-center")
                itemxElement2.classList.add("text-center")
                itemxElement3.classList.add("text-center")
                itemxElement4.classList.add("text-center")
                itemxElement5.classList.add("text-center")
                itemxElement1.style.backgroundColor = "#fff4cc";
                itemxElement2.style.backgroundColor = "#fff4cc";
                itemxElement3.style.backgroundColor = "#fff4cc";
                if (itemx4 >= 95) {
                    itemxElement4.style.backgroundColor = "#0804fc";
                } else if (itemx4 >= 85 && itemx4 < 95) {
                    itemxElement4.style.backgroundColor = "#08b454";
                } else if (itemx4 >= 75 && itemx4 < 85) {
                    itemxElement4.style.backgroundColor = "#fffc04";
                } else if (itemx4 >= 65 && itemx4 < 75) {
                    itemxElement4.style.backgroundColor = "#ffc404";
                } else {
                    itemxElement4.style.backgroundColor = "red";
                }
                itemxElement1.innerText = itemx1;
                itemxElement2.innerText = itemx2;
                itemxElement3.innerText = itemx3;
                itemxElement4.innerText = itemx4;
                itemxElement5.innerText = itemx5;
                tr.appendChild(itemxElement1)
                tr.appendChild(itemxElement2)
                tr.appendChild(itemxElement3)
                tr.appendChild(itemxElement4)
                tr.appendChild(itemxElement5)
                tbody3.appendChild(tr)


                // // <thead id="theadreg">




                tr = document.createElement('tr')
                let reg1 = regIonal
                let reg2 = regIonalRH
                let reg3 = regIonalNama
                let reg4 = regional[0][1]
                let regElement1 = document.createElement('td')
                let regElement2 = document.createElement('td')
                let regElement3 = document.createElement('td')
                let regElement4 = document.createElement('td')

                regElement1.classList.add("text-center")
                regElement2.classList.add("text-center")
                regElement3.classList.add("text-center")
                regElement4.classList.add("text-center")

                regElement1.style.backgroundColor = "#c8e4b4";
                regElement2.style.backgroundColor = "#c8e4b4";
                regElement3.style.backgroundColor = "#c8e4b4";
                if (reg4 >= 95) {
                    regElement4.style.backgroundColor = "#0804fc";
                } else if (reg4 >= 85 && reg4 < 95) {
                    regElement4.style.backgroundColor = "#08b454";
                } else if (reg4 >= 75 && reg4 < 85) {
                    regElement4.style.backgroundColor = "#fffc04";
                } else if (reg4 >= 65 && reg4 < 75) {
                    regElement4.style.backgroundColor = "#ffc404";
                } else {
                    regElement4.style.backgroundColor = "red";
                }
                regElement1.innerText = reg1;
                regElement2.innerText = reg2;
                regElement3.innerText = reg3;
                regElement4.innerText = reg4;

                tr.appendChild(regElement1)
                tr.appendChild(regElement2)
                tr.appendChild(regElement3)
                tr.appendChild(regElement4)

                theadreg.appendChild(tr)

                var plasmahide = document.getElementById('plasmaID');
                if (regInpt === '2' || regInpt === '3') {
                    plasmahide.style.display = 'none'; // hide the table
                } else {
                    plasmahide.style.display = ''; // show the table
                }

                //plasma
                var arrTplasma = newPlasma

                var tplasma = document.getElementById('plbody');

                // $('#thead3').empty()

                arrTplasma.forEach(element => {

                    tr = document.createElement('tr')
                    let item1 = element['est']
                    let item2 = element['afd']
                    let item3 = element['nama']
                    let item4 = element['skor']
                    let item5 = element['rank']
                    // let item6 = newPlasmaEM['EM']

                    let itemElement1 = document.createElement('td')
                    let itemElement2 = document.createElement('td')
                    let itemElement3 = document.createElement('td')
                    let itemElement4 = document.createElement('td')
                    let itemElement5 = document.createElement('td')



                    itemElement1.classList.add("text-center")
                    itemElement2.classList.add("text-center")
                    itemElement3.classList.add("text-center")
                    itemElement4.classList.add("text-center")
                    itemElement5.classList.add("text-center")



                    if (item4 >= 95) {
                        itemElement4.style.backgroundColor = "#609cd4";
                    } else if (item4 >= 85 && item4 < 95) {
                        itemElement4.style.backgroundColor = "#08b454";
                    } else if (item4 >= 75 && item4 < 85) {
                        itemElement4.style.backgroundColor = "#fffc04";
                    } else if (item4 >= 65 && item4 < 75) {
                        itemElement4.style.backgroundColor = "#ffc404";
                    } else {
                        itemElement4.style.backgroundColor = "red";
                    }

                    if (itemElement4.style.backgroundColor === "#609cd4") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#08b454") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#fffc04") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "#ffc404") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "red") {
                        itemElement4.style.color = "white";
                    }


                    // if (item4 != 0 || item4 != 90) {
                    //     itemElement4.innerHTML = '<a href="detailInpeksi/' + element['est'] + '/' + element['afd'] + '/' + date + '">' + element['skor'] + ' </a>'
                    // } else {
                    //     itemElement4.innerText = item4
                    // }
                    itemElement1.innerText = item1
                    // itemElement2.innerText = item2
                    itemElement2.innerHTML = '<a href="detailInpeksi/' + element['est'] + '/' + element['afd'] + '/' + date + '" target="_blank">' + element['afd'] + ' </a>';

                    itemElement3.innerText = item3
                    itemElement4.innerText = item4
                    itemElement5.innerText = item5

                    tr.appendChild(itemElement1)
                    tr.appendChild(itemElement2)
                    tr.appendChild(itemElement3)
                    tr.appendChild(itemElement4)
                    tr.appendChild(itemElement5)

                    tplasma.appendChild(tr)
                    // }
                });


                var plasmaBEM = document.getElementById('plbody');

                // $('#thead3').empty()
                var arrplasmaEM = newPlasmaEM
                arrplasmaEM.forEach(element => {

                    tr = document.createElement('tr')
                    let item1 = element['est']
                    let item2 = element['afd']
                    let item3 = element['nama']
                    let item4 = element['skor']
                    let item5 = ''
                    // let item6 = newPlasmaEM['EM']

                    let itemElement1 = document.createElement('td')
                    let itemElement2 = document.createElement('td')
                    let itemElement3 = document.createElement('td')
                    let itemElement4 = document.createElement('td')
                    let itemElement5 = document.createElement('td')



                    itemElement1.classList.add("text-center")
                    itemElement2.classList.add("text-center")
                    itemElement3.classList.add("text-center")
                    itemElement4.classList.add("text-center")
                    itemElement5.classList.add("text-center")



                    if (item4 >= 95) {
                        itemElement4.style.backgroundColor = "#609cd4";
                    } else if (item4 >= 85 && item4 < 95) {
                        itemElement4.style.backgroundColor = "#08b454";
                    } else if (item4 >= 75 && item4 < 85) {
                        itemElement4.style.backgroundColor = "#fffc04";
                    } else if (item4 >= 65 && item4 < 75) {
                        itemElement4.style.backgroundColor = "#ffc404";
                    } else {
                        itemElement4.style.backgroundColor = "red";
                    }

                    if (itemElement4.style.backgroundColor === "#609cd4") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#08b454") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#fffc04") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "#ffc404") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "red") {
                        itemElement4.style.color = "white";
                    }


                    itemElement4.innerText = item4;
                    itemElement1.innerText = item1
                    itemElement2.innerText = item2
                    itemElement3.innerText = item3
                    //   itemElement4.innerText  = item4
                    itemElement5.innerText = item5

                    tr.appendChild(itemElement1)
                    tr.appendChild(itemElement2)
                    tr.appendChild(itemElement3)
                    tr.appendChild(itemElement4)
                    tr.appendChild(itemElement5)

                    plasmaBEM.appendChild(tr)
                    // }
                });

                var plasmaGMe = document.getElementById('plbody');

                // $('#thead3').empty()
                var arrPlasmaGM = newPlasmaGM
                arrPlasmaGM.forEach(element => {

                    tr = document.createElement('tr')
                    let item1 = element['est']
                    let item2 = element['afd']
                    let item3 = element['nama']
                    let item4 = element['skor']
                    let item5 = ''
                    // let item6 = newPlasmaEM['EM']

                    let itemElement1 = document.createElement('td')
                    let itemElement2 = document.createElement('td')
                    let itemElement3 = document.createElement('td')
                    let itemElement4 = document.createElement('td')
                    let itemElement5 = document.createElement('td')



                    itemElement1.classList.add("text-center")
                    itemElement2.classList.add("text-center")
                    itemElement3.classList.add("text-center")
                    itemElement4.classList.add("text-center")
                    itemElement5.classList.add("text-center")



                    if (item4 >= 95) {
                        itemElement4.style.backgroundColor = "#609cd4";
                    } else if (item4 >= 85 && item4 < 95) {
                        itemElement4.style.backgroundColor = "#08b454";
                    } else if (item4 >= 75 && item4 < 85) {
                        itemElement4.style.backgroundColor = "#fffc04";
                    } else if (item4 >= 65 && item4 < 75) {
                        itemElement4.style.backgroundColor = "#ffc404";
                    } else {
                        itemElement4.style.backgroundColor = "red";
                    }

                    if (itemElement4.style.backgroundColor === "#609cd4") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#08b454") {
                        itemElement4.style.color = "white";
                    } else if (itemElement4.style.backgroundColor === "#fffc04") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "#ffc404") {
                        itemElement4.style.color = "black";
                    } else if (itemElement4.style.backgroundColor === "red") {
                        itemElement4.style.color = "white";
                    }


                    itemElement4.innerText = item4;
                    itemElement1.innerText = item1
                    itemElement2.innerText = item2
                    itemElement3.innerText = item3
                    //   itemElement4.innerText  = item4
                    itemElement5.innerText = item5

                    tr.appendChild(itemElement1)
                    tr.appendChild(itemElement2)
                    tr.appendChild(itemElement3)
                    tr.appendChild(itemElement4)
                    tr.appendChild(itemElement5)

                    plasmaGMe.appendChild(tr)
                    // }
                });


                //chart
                var wilayah = '['
                list_will.forEach(element => {
                    wilayah += '"' + element + '",'
                });
                wilayah = wilayah.substring(0, wilayah.length - 1);
                wilayah += ']'

                var brd = '['
                if (chart_btt.length > 0) {
                    chart_btt.forEach(element => {
                        brd += '"' + element[1] + '",'
                    });
                    brd = brd.substring(0, brd.length - 1);
                } else {
                    brd = '[0, 0 , 0, 0, 0, 0, 0 , 0 ,0 , 0 , 0 , 0]'
                }
                brd += ']'

                var buah = '['
                chart_buah.forEach(element => {
                    buah += '"' + element[1] + '",'
                });
                buah = buah.substring(0, buah.length - 1);
                buah += ']'

                var bttWil = '['
                chartWillbt.forEach(element => {
                    bttWil += '"' + element[1] + '",'
                });
                bttWil = bttWil.substring(0, bttWil.length - 1);
                bttWil += ']'

                var bhWil = '['
                chartWillbh.forEach(element => {
                    bhWil += '"' + element[1] + '",'
                });
                bhWil = bhWil.substring(0, bhWil.length - 1);
                bhWil += ']'

                var estate = JSON.parse(wilayah)
                var brd_jjgJson = JSON.parse(brd)
                var buah_jjgJson = JSON.parse(buah)

                var brd_wilJson = JSON.parse(bttWil)
                var buah_wilJson = JSON.parse(bhWil)

                const arr = estate

                const formatEst = arr.map((item) => item.split(',')[1]);

                // let regInpt = reg;
                let wilayahReg = '';


                if (regInpt === '1') {
                    wilayahReg = ['WIL I', 'WIL II', 'WIL III']

                } else if (regInpt === '2') {
                    wilayahReg = ['WIL IV', 'WIL V', 'WIL VI']

                } else if (regInpt === '3') {
                    wilayahReg = ['WIL VII', 'WIL VIII']

                }

                let colors = '';


                if (regInpt === '1') {
                    colors = ['#00FF00',
                        '#00FF00',
                        '#00FF00',
                        '#00FF00',
                        '#3063EC',
                        '#3063EC',
                        '#3063EC',
                        '#3063EC',
                        '#FF8D1A',
                        '#FF8D1A',
                        '#FF8D1A',
                        '#FF8D1A',
                        '#00ffff'
                    ]

                } else if (regInpt === '2') {
                    colors = ['#00FF00',
                        '#00FF00',
                        '#00FF00',
                        '#00FF00',
                        '#3063EC',
                        '#3063EC',
                        '#3063EC',
                        '#00ffff',
                        '#00ffff'
                    ]


                } else if (regInpt === '3') {
                    colors = ['#00FF00',
                        '#00FF00',
                        '#00FF00',
                        '#00FF00',
                        '#3063EC',
                        '#3063EC',
                        '#3063EC',
                        '#3063EC',
                    ]
                }

                chartGrain.updateSeries([{
                    name: 'butir/jjg panen',
                    data: brd_jjgJson,

                }])
                chartGrain.updateOptions({
                    xaxis: {
                        categories: formatEst
                    },
                    colors: colors // Set the colors directly, no need for an object
                })




                chartFruit.updateSeries([{
                    name: '% buah tinggal',
                    data: buah_jjgJson,

                }])
                chartFruit.updateOptions({
                    xaxis: {
                        categories: formatEst
                    },
                    colors: colors // Set the colors directly, no need for an object
                })

                chartGrainWil.updateSeries([{
                    name: 'butir/jjg panen',
                    data: brd_wilJson
                }])

                chartGrainWil.updateOptions({
                    xaxis: {
                        categories: wilayahReg
                    }
                })


                chartFruitWil.updateSeries([{
                    name: '% buah tinggal',
                    data: buah_wilJson
                }])

                chartFruitWil.updateOptions({
                    xaxis: {
                        categories: wilayahReg
                    }
                })


                //untuk table utama

                //end table


                //end ajax
            }
        });
    }


    //tampilkan pertahun filter table utama
    document.getElementById('showTahung').onclick = function() {
        dashboard_tahun()
    }


    function dashboard_tahun() {
        $('#tb_tahun').empty()
        $('#tablewil').empty()
        $('#reg').empty()
        $('#rekapAFD').empty()

        var year = ''
        $regData = ''
        var _token = $('input[name="_token"]').val();
        var year = document.getElementById('yearDate').value
        var regData = document.getElementById('regionalData').value


        $.ajax({
            url: "{{ route('filterTahun') }}",
            method: "GET",
            data: {
                year,
                regData,
                _token: _token
            },
            success: function(result) {

                var parseResult = JSON.parse(result)
                //list estate
                //untuk tabel pertahun
                //   var FinalTahun = Object.entries(parseResult['FinalTahun'])

                var list_tabel = Object.entries(parseResult['FinalTahun'])
                var total_tahun = Object.entries(parseResult['Final_end'])
                var rekap_bulan = Object.entries(parseResult['RekapBulan'])
                var rekap_tahun = Object.entries(parseResult['RekapTahun'])
                var rekap_bulanwil = Object.entries(parseResult['RekapBulanwil'])
                // console.log(rekap_bulanwil);
                var rekap_tahunwil = Object.entries(parseResult['RekapTahunwil'])
                var RekapBulanReg = Object.entries(parseResult['RekapBulanReg'])
                var RekapTahunReg = Object.entries(parseResult['RekapTahunReg'])
                var RekapBulanAFD = Object.entries(parseResult['RekapBulanAFD'])
                var RekapTahunAFD = Object.entries(parseResult['RekapTahunAFD'])
                var chart_brdTH = Object.entries(parseResult['chart_brdTAHUN'])
                var chart_bhTH = Object.entries(parseResult['chart_buahTAHUN'])
                var chart_brdWIl = Object.entries(parseResult['chartbrdWilTH'])
                var chart_bhWil = Object.entries(parseResult['chartBhwilTH'])
                var list_will = Object.entries(parseResult['list_estate'])
                var estateEST = Object.entries(parseResult['estateEST'])
                var RekapBulanPlasma = Object.entries(parseResult['RekapBulanPlasma'])
                regInpt = regData
                // console.log(RekapBulanPlasma);

                var nama_asisten = Object.entries(parseResult['asisten'])
                const assistants = nama_asisten;

                const filteredAssistants = assistants.map((assistant) => ({
                    est: assistant[1].est,
                    afd: assistant[1].afd,
                    nama: assistant[1].nama,
                }));

                // console.log(filteredAssistants);

                var bttEST = '[';
                chart_brdTH.forEach(element => {
                    bttEST += '"' + element.toString().split(',')[1] + '",';
                });
                bttEST = bttEST.substring(0, bttEST.length - 1);
                bttEST += ']';

                var bhEST = '[';
                chart_bhTH.forEach(element => {
                    bhEST += '"' + element.toString().split(',')[1] + '",';
                });
                bhEST = bhEST.substring(0, bhEST.length - 1);
                bhEST += ']';

                var brdWil = '[';
                chart_brdWIl.forEach(element => {
                    brdWil += '"' + element.toString().split(',')[1] + '",';
                });
                brdWil = brdWil.substring(0, brdWil.length - 1);
                brdWil += ']';

                var bhWil = '[';
                chart_bhWil.forEach(element => {
                    bhWil += '"' + element.toString().split(',')[1] + '",';
                });
                bhWil = bhWil.substring(0, bhWil.length - 1);
                bhWil += ']';


                var wilayah = '['
                list_will.forEach(element => {
                    wilayah += '"' + element + '",'
                });
                wilayah = wilayah.substring(0, wilayah.length - 1);
                wilayah += ']'



                var estate = JSON.parse(wilayah)
                var bttEst = JSON.parse(bttEST)
                var bhEST = JSON.parse(bhEST)

                var brdWil = JSON.parse(brdWil)
                var bhWil = JSON.parse(bhWil)

                //untuk table
                var arrbodywil = rekap_bulan;

                var yearStr = year.toString();
                var totalBody = rekap_tahun;

                const arr = estate

                const EstTahun = arr.map((item) => item.split(',')[1]);



                const arrEst = estateEST;
                const est = arrEst.map((item) => item.slice(1)); // remove the first element of each array

                // console.log(arrbodywil);

                const array = est

                const estAndNamaValues = array.map(([{
                    est,
                    nama
                }]) => ({
                    est,
                    nama
                }));

                var tbody1 = document.getElementById('tb_tahun');

                arrbodywil.forEach((element, index) => {
                    var tr = document.createElement('tr');
                    let namaEst = {
                        "KNE": "Samuel M. Sidabutar",
                        "PLE": "Hamdani",
                        "RDE": "Muhammad Rizaldi",
                        "SLE": "Wahyu Binarko",
                        "BKE": "Andri J. A. Engkang",
                        "KDE": "Ahmad Seno Aji",
                        "RGE": "Angga Putera Perdana",
                        "SGE": "Jurianto",
                        "BGE": "Prawito",
                        "NBE": "Larmaya Aji Pamungkas",
                        "SYE": "Dedi Yusdarty",
                        "UPE": "M. Rasyid Fauzirin"
                    }



                    let item1 = index + 1;
                    let item3 = element[0];
                    let item2 = '-';

                    // // Iterate over estAndNamaValues to get the corresponding nama value
                    for (let i = 0; i < estAndNamaValues.length; i++) {
                        if (estAndNamaValues[i].est === item3) {
                            item2 = estAndNamaValues[i].nama;
                            break;
                        }
                    }
                    let item4;

                    // console.log(item2);
                    filteredAssistants.forEach((element, index) => {
                        const assistantEstate = element['est'];
                        const assistantAfd = element['afd'];
                        // console.log(assistantEstate)

                        if (assistantEstate === item3) {
                            item4 = element['nama'];
                        }
                    });

                    if (item4 === undefined) {
                        item4 = '-';
                    } else if (item4 === 'Budi Saputra') {
                        item4 = 'SEPTIAN ADHI P';
                    }
                    // let item2 = slangEst[item3];
                    // // <!-- let item4 = namaEst[item3];
                    // if (item4 === undefined) {
                    //     item4 = '-';
                    // } -->

                    let item5 = element[1].January.bulan_skor;
                    let item6 = element[1].February.bulan_skor;
                    let item7 = element[1].March.bulan_skor;
                    let item8 = element[1].April.bulan_skor;
                    let item9 = element[1].May.bulan_skor;
                    let item10 = element[1].June.bulan_skor;
                    let item11 = element[1].July.bulan_skor;
                    let item12 = element[1].August.bulan_skor;
                    let item13 = element[1].September.bulan_skor;
                    let item14 = element[1].October.bulan_skor;
                    let item15 = element[1].November.bulan_skor;
                    let item16 = element[1].December.bulan_skor;

                    // Find the skor_tahun for the current element
                    // let item17;
                    for (var i = 0; i < totalBody.length; i++) {
                        if (totalBody[i][0] == item3) {
                            item17 = totalBody[i][1]['tahun_skor'];
                            break;
                        }
                    }

                    let items = [item1, item2, item3, item4, item5, item6, item7, item8, item9, item10, item11, item12, item13, item14, item15, item16, item17];

                    let column = 0;
                    items.forEach(item => {
                        let td = document.createElement('td');
                        if (column >= 4) {
                            if (item >= 95) {
                                td.style.backgroundColor = "#0804fc";
                            } else if (item >= 85 && item < 95) {
                                td.style.backgroundColor = "#08b454";
                            } else if (item >= 75 && item < 85) {
                                td.style.backgroundColor = "#fffc04";
                            } else if (item >= 65 && item < 75) {
                                td.style.backgroundColor = "#ffc404";
                            } else {
                                td.style.backgroundColor = "red";
                            }
                        }
                        column++;

                        td.innerText = item;
                        tr.appendChild(td);
                    });

                    tbody1.appendChild(tr);
                    var header = document.getElementById('th_year');
                    header.innerText = yearStr;
                });


                //bagian untuk table perwil


                // var yearStr = year.toString();
                var arrwil = rekap_bulanwil;
                // console.log(arrwil);
                var totalBodywil = rekap_tahunwil;

                var tbody2 = document.getElementById('tablewil');

                arrwil.forEach((element, index) => {
                    tr = document.createElement('tr')
                    let angka = element[0];
                    if (angka === '1') {
                        angka = 'I';
                    } else if (angka === '2') {
                        angka = 'II';
                    } else if (angka === '3') {
                        angka = 'III';
                    }

                    let namaGM = {
                        "1": "Kinan Efran Harahap",
                        "2": "Sucipto",
                        "3": "Achmad Kursani"
                    }

                    let item1 = element[0];

                    let item2 = namaGM[item1];
                    if (item2 === undefined) {
                        item2 = '-';
                    }
                    let item3 = element[1].January.skor_bulanTotal;
                    let item4 = element[1].February.skor_bulanTotal;
                    let item5 = element[1].March.skor_bulanTotal;
                    let item6 = element[1].April.skor_bulanTotal;
                    let item7 = element[1].May.skor_bulanTotal;
                    let item8 = element[1].June.skor_bulanTotal;
                    let item9 = element[1].July.skor_bulanTotal;
                    let item10 = element[1].August.skor_bulanTotal;
                    let item11 = element[1].September.skor_bulanTotal;
                    let item12 = element[1].October.skor_bulanTotal;
                    let item13 = element[1].November.skor_bulanTotal;
                    let item14 = element[1].December.skor_bulanTotal;

                    let tahunskor = [];
                    for (var i = 0; i < totalBodywil.length; i++) {
                        if (totalBodywil[i][0] == item1) {
                            tahunskor.push(totalBodywil[i][1]['tahun_skorwil']);
                            break;
                        }

                    }

                    let item15 = tahunskor;

                    let items = [item1, item2, item3, item4, item5, item6, item7, item8, item9, item10, item11, item12, item13, item14, item15];

                    // Create a td element with colspan="3" for the first three items
                    let td1 = document.createElement('td');
                    td1.colSpan = "3";
                    td1.innerText = item1;
                    tr.appendChild(td1);

                    let column = 1; // Start column after the first three items
                    for (let i = 1; i < items.length; i++) {
                        let item = items[i];
                        let td = document.createElement('td');
                        if (column >= 2) {
                            if (item >= 95) {
                                td.style.backgroundColor = "#0804fc";
                            } else if (item >= 85 && item < 95) {
                                td.style.backgroundColor = "#08b454";
                            } else if (item >= 75 && item < 85) {
                                td.style.backgroundColor = "#fffc04";
                            } else if (item >= 65 && item < 75) {
                                td.style.backgroundColor = "#ffc404";
                            } else {
                                td.style.backgroundColor = "red";
                            }
                        }
                        column++;
                        td.innerText = item;
                        tr.appendChild(td);
                    }

                    tbody2.appendChild(tr)
                    var header = document.getElementById('th_years');
                    header.innerText = yearStr;
                });

                var plasmaWil = RekapBulanPlasma;
                // console.log(plasmaWil);


                if (regInpt === '1') {
                    var plasma = document.getElementById('tablewil');

                    plasmaWil.forEach((element, index) => {
                        tr = document.createElement('tr')

                        let item1 = element[0];
                        let item2 = element[1].namaGM;
                        let item3 = element[1].January.Bulan;
                        let item4 = element[1].February.Bulan;
                        let item5 = element[1].March.Bulan;
                        let item6 = element[1].April.Bulan;
                        let item7 = element[1].May.Bulan;
                        let item8 = element[1].June.Bulan;
                        let item9 = element[1].July.Bulan;
                        let item10 = element[1].August.Bulan;
                        let item11 = element[1].September.Bulan;
                        let item12 = element[1].October.Bulan;
                        let item13 = element[1].November.Bulan;
                        let item14 = element[1].December.Bulan;

                        let item15 = element[1].Tahun;

                        let items = [item1, item2, item3, item4, item5, item6, item7, item8, item9, item10, item11, item12, item13, item14, item15];

                        // Create a td element with colspan="3" for the first three items
                        let td1 = document.createElement('td');
                        td1.colSpan = "3";
                        td1.innerText = item1;
                        tr.appendChild(td1);

                        let column = 1; // Start column after the first three items
                        for (let i = 1; i < items.length; i++) {
                            let item = items[i];
                            let td = document.createElement('td');
                            if (column >= 2) {
                                if (item >= 95) {
                                    td.style.backgroundColor = "#0804fc";
                                } else if (item >= 85 && item < 95) {
                                    td.style.backgroundColor = "#08b454";
                                } else if (item >= 75 && item < 85) {
                                    td.style.backgroundColor = "#fffc04";
                                } else if (item >= 65 && item < 75) {
                                    td.style.backgroundColor = "#ffc404";
                                } else {
                                    td.style.backgroundColor = "red";
                                }
                            }
                            column++;
                            td.innerText = item;
                            tr.appendChild(td);
                        }

                        plasma.appendChild(tr)

                    });

                }

                // console.log(RekapBulanReg);

                //table untuk regional 1
                var regbln = RekapBulanReg;
                var regthn = RekapTahunReg;

                var tbody3 = document.getElementById('reg');


                let regWil = '';


                if (regInpt === '1') {
                    regWil = 'Akhmad Faisyal';
                    regW = 'I'

                } else if (regInpt === '2') {
                    regWil = '-'
                    regW = 'II'
                } else if (regInpt === '3') {
                    regWil = '-'
                    regW = 'III'
                }
                tr = document.createElement('tr');

                let item1 = regW
                let item2 = regWil
                let item3 = regbln[0][1].skor_bulanTotal;
                let item4 = regbln[1][1].skor_bulanTotal;
                let item5 = regbln[2][1].skor_bulanTotal;
                let item6 = regbln[3][1].skor_bulanTotal;
                let item7 = regbln[4][1].skor_bulanTotal;
                let item8 = regbln[5][1].skor_bulanTotal;
                let item9 = regbln[6][1].skor_bulanTotal;
                let item10 = regbln[7][1].skor_bulanTotal;
                let item11 = regbln[8][1].skor_bulanTotal;
                let item12 = regbln[9][1].skor_bulanTotal;
                let item13 = regbln[10][1].skor_bulanTotal;
                let item14 = regbln[11][1].skor_bulanTotal;
                let item15 = regthn[0][1].tahun_skorwil;

                let items = [item1, item2, item3, item4, item5, item6, item7, item8, item9, item10, item11, item12, item13, item14, item15];

                let td1 = document.createElement('td');
                td1.colSpan = "3";
                td1.innerText = item1;
                tr.appendChild(td1);

                let column = 1; // Start column after the first three items
                for (let j = 1; j < items.length; j++) {
                    let item = items[j];
                    let td = document.createElement('td');
                    if (column >= 2) {
                        if (item >= 95) {
                            td.style.backgroundColor = "#0804fc";
                        } else if (item >= 85 && item < 95) {
                            td.style.backgroundColor = "#08b454";
                        } else if (item >= 75 && item < 85) {
                            td.style.backgroundColor = "#fffc04";
                        } else if (item >= 65 && item < 75) {
                            td.style.backgroundColor = "#ffc404";
                        } else {
                            td.style.backgroundColor = "red";
                        }
                    }
                    column++;
                    td.innerText = item;
                    tr.appendChild(td);
                }

                tbody3.appendChild(tr);

                ///table untuk rekap perafd
                var arrAFD = RekapBulanAFD;
                console.log(arrAFD);

                var arrAFDTH = RekapTahunAFD;
                //   console.log(arrAFDTH)

                var tbody4 = document.getElementById('rekapAFD');
                let currentIndex = 1;



                // console.log(assist[0][1])
                arrAFD.forEach((element, index) => {
                    let estate = element[0];
                    let namaAFD = Object.keys(element[1].January);
                    namaAFD.forEach((asisten) => {
                        tr = document.createElement('tr');
                        let item0 = '-';
                        let item1 = estate;
                        let item2 = asisten;
                        let item3;

                        filteredAssistants.forEach((element, index) => {
                            const assistantEstate = element['est'];
                            const assistantAfd = element['afd'];
                            // console.log(assistantEstate)

                            if (assistantEstate === item1 && assistantAfd === item2) {
                                item3 = element['nama'];
                            }
                        });

                        if (item3 === undefined) {
                            item3 = '-';
                        }

                        let item4 = element[1].January[asisten].bulan_afd;
                        let item5 = element[1].February[asisten].bulan_afd;
                        let item6 = element[1].March[asisten].bulan_afd;
                        let item7 = element[1].April[asisten].bulan_afd;
                        let item8 = element[1].May[asisten].bulan_afd;
                        let item9 = element[1].June[asisten].bulan_afd;
                        let item10 = element[1].July[asisten].bulan_afd;
                        let item11 = element[1].August[asisten].bulan_afd;
                        let item12 = element[1].September[asisten].bulan_afd;
                        let item13 = element[1].October[asisten].bulan_afd;
                        let item14 = element[1].November[asisten].bulan_afd;
                        let item15 = element[1].December[asisten].bulan_afd;
                        // let item17 = arrAFDTH[0][1].OA.tahun_skorwil;

                        for (var i = 0; i < arrAFDTH.length; i++) {
                            if (arrAFDTH[i][0] == item1) {
                                item16 = arrAFDTH[i][1][item2]['tahun_skorwil'];
                                break;
                            }
                        }

                        let items = [item0, item1, item2, item3, item4, item5, item6, item7, item8, item9, item10, item11, item12, item13, item14, item15, item16];

                        let column = 1; // Start column after the first three items
                        for (let j = 0; j < items.length; j++) {
                            let item = items[j];
                            let td = document.createElement('td');
                            if (column >= 5) {
                                if (item >= 95) {
                                    td.style.backgroundColor = "#0804fc";
                                } else if (item >= 85 && item < 95) {
                                    td.style.backgroundColor = "#08b454";
                                } else if (item >= 75 && item < 85) {
                                    td.style.backgroundColor = "#fffc04";
                                } else if (item >= 65 && item < 75) {
                                    td.style.backgroundColor = "#ffc404";
                                } else if (item === 0) {
                                    td.style.backgroundColor = "white";
                                } else {
                                    td.style.backgroundColor = "red";
                                }
                            }
                            column++;
                            td.innerText = item;
                            tr.appendChild(td);
                        }

                        tbody4.appendChild(tr);
                    });
                });

                const sorting = document.getElementById('rekapAFD');

                if (sorting) {
                    // Convert the table rows to an array for sorting
                    const rows = Array.from(sorting.rows);

                    // Sort the rows based on the values in the 16th column
                    rows.sort((row1, row2) => {
                        const value1 = parseInt(row1.cells[16].textContent);
                        const value2 = parseInt(row2.cells[16].textContent);
                        return value2 - value1;
                    });

                    // Remove the existing rows from the table body
                    while (sorting.firstChild) {
                        sorting.removeChild(sorting.firstChild);
                    }

                    // Add the sorted rows back to the table body
                    rows.forEach((row) => {
                        sorting.appendChild(row);
                    });
                } else {
                    console.error("Element with id 'rekapAFD' not found");
                }

                const index = document.getElementById('rekapAFD');
                if (index) {
                    const rows = Array.from(index.rows);
                    let i = 1;
                    rows.forEach(row => {
                        row.cells[0].textContent = i;
                        i++;
                    });
                }


                //   end rekap table afd
                //endtable

                let colors = '';


                if (regInpt === '1') {
                    colors = ['#00FF00',
                        '#00FF00',
                        '#00FF00',
                        '#00FF00',
                        '#3063EC',
                        '#3063EC',
                        '#3063EC',
                        '#3063EC',
                        '#FF8D1A',
                        '#FF8D1A',
                        '#FF8D1A',
                        '#FF8D1A',
                        '#00ffff'
                    ]

                } else if (regInpt === '2') {
                    colors = ['#00FF00',
                        '#00FF00',
                        '#00FF00',
                        '#00FF00',
                        '#3063EC',
                        '#3063EC',
                        '#3063EC',
                        '#00ffff',
                        '#00ffff'
                    ]


                } else if (regInpt === '3') {
                    colors = ['#00FF00',
                        '#00FF00',
                        '#00FF00',
                        '#00FF00',
                        '#3063EC',
                        '#3063EC',
                        '#3063EC',
                        '#3063EC',
                    ]
                }



                //chart table untuk pertahun
                chartGrainYear.updateSeries([{
                    name: 'butir/jjg panen',
                    data: bttEst
                }])
                chartGrainYear.updateOptions({
                    xaxis: {
                        categories: EstTahun
                    },
                    colors: colors // Set the colors directly, no need for an object
                })

                chartFruitYear.updateSeries([{
                    name: '% buah tinggal',
                    data: bhEST
                }])
                chartFruitYear.updateOptions({
                    xaxis: {
                        categories: EstTahun
                    },
                    colors: colors // Set the colors directly, no need for an object
                })


                let wilayahReg = '';


                if (regInpt === '1') {
                    wilayahReg = ['WIL I', 'WIL II', 'WIL III']

                } else if (regInpt === '2') {
                    wilayahReg = ['WIL IV', 'WIL V', 'WIL VI']

                } else if (regInpt === '3') {
                    wilayahReg = ['WIL VII', 'WIL VIII']

                }
                chartGrainWilYear.updateSeries([{
                    name: 'butir/jjg panen',
                    data: brdWil
                }])

                chartGrainWilYear.updateOptions({
                    xaxis: {
                        categories: wilayahReg
                    }
                })

                chartFruitWilYear.updateSeries([{
                    name: '% buah tinggal',
                    data: bhWil
                }])

                chartFruitWilYear.updateOptions({
                    xaxis: {
                        categories: wilayahReg
                    }
                })


                //endchart
            }
        });
    }



    var options = {
        chart: {
            height: 280,
            type: "area"
        },
        dataLabels: {
            enabled: false
        },
        series: [{
                name: "ESTATE",
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            },

            {
                name: "Batas Toleransi",
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                color: "#ff0000" // set the color to red
            }
        ],
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.9,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "July", "Agustus", "Sept", "Okt", "Nov", "Dec"]
        }
    };


    var chartScore = new ApexCharts(document.querySelector("#skorGraph"), options);
    chartScore.render();
    var chartScoreBron = new ApexCharts(document.querySelector("#skorBronGraph"), options);
    chartScoreBron.render();
    var chatScoreJan = new ApexCharts(document.querySelector("#skorJanGraph"), options);
    chatScoreJan.render();


    document.getElementById('GraphFilter').onclick = function() {
        graphFilter()
    }

    function graphFilter() {
        var est = ''
        var yearGraph = ''
        var est = document.getElementById('estData').value

        var yearGraph = document.getElementById('yearGraph').value
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('graphfilter') }}",
            method: "GET",
            data: {
                est,
                yearGraph,
                _token: _token
            },
            success: function(result) {
                // console.log(est)
                // console.log(yearGraph)
                var parseResult = JSON.parse(result)
                //list estate


                var chart_btt = Object.entries(parseResult['GraphBtt'])
                var chart_buah = Object.entries(parseResult['GraphBuah'])
                var chart_skor = Object.entries(parseResult['GraphSkorTotal'])

                var graphBtt = '['
                chart_btt.forEach(element => {
                    graphBtt += '"' + element[1] + '",'
                });
                graphBtt = graphBtt.substring(0, graphBtt.length - 1);
                graphBtt += ']'

                var graphBuah = '['
                chart_buah.forEach(element => {
                    graphBuah += '"' + element[1] + '",'
                });
                graphBuah = graphBuah.substring(0, graphBuah.length - 1);
                graphBuah += ']'

                var graphSkor = '['
                chart_skor.forEach(element => {
                    graphSkor += '"' + element[1] + '",'
                });
                graphSkor = graphSkor.substring(0, graphSkor.length - 1);
                graphSkor += ']'


                var bttJson = JSON.parse(graphBtt)
                var bhJson = JSON.parse(graphBuah)
                var skorJson = JSON.parse(graphSkor)

                chartScore.updateSeries([{
                        name: est,
                        data: skorJson
                    },

                    {
                        name: "Batas Toleransi",
                        data: [85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85]
                    }
                ])

                chartScoreBron.updateSeries([{
                        name: est,
                        data: bttJson
                    },

                    {
                        name: "Batas Toleransi",
                        data: [1.0, 1.0, 1.0, 1.0, 1.0, 1.0, 1.0, 1.0, 1.0, 1.0, 1.0, 1.0]
                    }
                ])
                chatScoreJan.updateSeries([{
                        name: est,
                        data: bhJson
                    },

                    {
                        name: "Batas Toleransi",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    }
                ])
            }
        });
    }
</script>