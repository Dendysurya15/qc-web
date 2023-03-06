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
                        <a class="nav-item nav-link active" id="nav-utama-tab" data-toggle="tab" href="#nav-utama"
                            role="tab" aria-controls="nav-utama" aria-selected="true">Halaman Utama</a>
                        <a class="nav-item nav-link" id="nav-data-tab" data-toggle="tab" href="#nav-data" role="tab"
                            aria-controls="nav-data" aria-selected="false">Data</a>
                        <a class="nav-item nav-link" id="nav-issue-tab" data-toggle="tab" href="#nav-issue" role="tab"
                            aria-controls="nav-issue" aria-selected="false">Finding Issue</a>
                        <a class="nav-item nav-link" id="nav-score-tab" data-toggle="tab" href="#nav-score" role="tab"
                            aria-controls="nav-score" aria-selected="false">Score By Map</a>
                        <a class="nav-item nav-link" id="nav-grafik-tab" data-toggle="tab" href="#nav-grafik" role="tab"
                            aria-controls="nav-grafik" aria-selected="false">Grafik</a>
                    </div>
                </nav>

                <div class="tab-content" id="nav-tabContent">



                    <div class="tab-pane fade show active" id="nav-utama" role="tabpanel"
                        aria-labelledby="nav-utama-tab">

                        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                            <h5><b>REKAPITULASI RANKING NILAI KUALITAS PANEN</b></h5>
                        </div>
                        {{-- <form action="{{route('filter')}}" method="GET"> --}}
                            <div class="d-flex flex-row-reverse mr-3">
                                <button class="btn btn-primary mb-3" style="float: right" id="btnShow">Show</button>
                                <div class="col-2 mr-2" style="float: right">
                                    {{csrf_field()}}
                                    <select class="form-control" id="regionalData">
                                        <option value="1" selected>Regional 1</option>
                                        <option value="2">Regional 2</option>
                                        <option value="3">Regional 3</option>
                                    </select>
                                </div>
                                <div class="col-2" style="float: right">
                                    <input class="form-control" value="{{ date('Y-m') }}" type="month" name="date"
                                        id="inputDate">
                                    {{-- <input class="form-control" value="{{ old('tgl', date('Y-m')) }}" type="month"
                                        name="tgl" id="inputDate"> --}}

                                </div>
                            </div>
                            {{--
                        </form> --}}
                        <div class="ml-3 mr-3">
                            <div class=" row text-center">
                                <div class="col-sm-4">
                                    <table class="table table-bordered" style="font-size: 13px" id="table1">
                                        <thead>
                                            <tr bgcolor="darkorange">
                                                <th colspan="5">WILAYAH I</th>
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
                                <div class="col-sm-4">
                                    <table class="table table-bordered" style="font-size: 13px">
                                        <thead>
                                            <tr bgcolor="darkorange">
                                                <th colspan="5">WILAYAH II</th>
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
                                <div class="col-sm-4">
                                    <table class="table table-bordered" style="font-size: 13px">
                                        <thead>
                                            <tr bgcolor="darkorange">
                                                <th colspan="5">WILAYAH III</th>
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
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col">
                                <div class="card-body">
                                    <p style="font-size: 15px"><b><u>BRONDOLAN TINGGAL</u></b></p>
                                    <div id="brondolanGraph"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card-body">
                                    <p style="font-size: 15px"><b><u>BUAH TINGGAL</u></b></p>
                                    <div id="buahGraph"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col">
                                <div class="card-body">
                                    <p style="font-size: 15px"><b><u>BRONDOLAN TINGGAL</u></b></p>
                                    <div id="brondolanGraphWil"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card-body">
                                    <p style="font-size: 15px"><b><u>BUAH TINGGAL</u></b></p>
                                    <div id="buahGraphWil"></div>
                                </div>
                            </div>
                        </div>

                        <p class="ml-3 mb-3 mr-3">
                            <button style="width: 100%" class="btn btn-primary" type="button" data-toggle="collapse"
                                data-target="#showByYear" aria-expanded="false" aria-controls="showByYear">
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
                                            @foreach ($FinalTahun as $key => $value)
                                            <tr>
                                                <td>No -</td>
                                                <td>-</td>
                                                <td>{{ $key }}</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                @foreach ($value as $key1 => $value2)


                                                {{-- <td>{{ $key1['January'] $value2['skor_final'] ?? '' }}</td> --}}

                                                {{-- <td>{{ $key1['February'] $value2['skor_final'] ?? '' }}</td> --}}

                                                @endforeach
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                    <table class="table table-bordered" style="font-size: 13px">
                                        <thead>
                                            <tr>
                                                <td colspan="{{ count($arrHeader) }}"></td>
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
                                            <tr>
                                                <td colspan="3">I</td>
                                                <td>Kinan Efran Harahap</td>
                                                <td bgcolor="blue" style="color: white">100</td>
                                                <td bgcolor="blue" style="color: white">99.0</td>
                                                <td bgcolor="blue" style="color: white">95.0</td>
                                                <td bgcolor="blue" style="color: white">100.0</td>
                                                <td bgcolor="green" style="color: white">89.0</td>
                                                <td bgcolor="yellow">78.0</td>
                                                <td bgcolor="green" style="color: white">85.0</td>
                                                <td bgcolor="blue" style="color: white">95.0</td>
                                                <td bgcolor="blue" style="color: white">96.0</td>
                                                <td bgcolor="green" style="color: white">94.0</td>
                                                <td bgcolor="blue" style="color: white">96.0</td>
                                                <td bgcolor="blue" style="color: white">97.0</td>
                                                <td bgcolor="green" style="color: white">93.7</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered" style="font-size: 13px">
                                        <thead>
                                            <tr>
                                                <td colspan="{{ count($arrHeader) }}"></td>
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
                                            <tr>
                                                <td colspan="3">I</td>
                                                <td>Kinan Efran Harahap</td>
                                                <td bgcolor="blue" style="color: white">100</td>
                                                <td bgcolor="blue" style="color: white">99.0</td>
                                                <td bgcolor="blue" style="color: white">95.0</td>
                                                <td bgcolor="blue" style="color: white">100.0</td>
                                                <td bgcolor="green" style="color: white">89.0</td>
                                                <td bgcolor="yellow">78.0</td>
                                                <td bgcolor="green" style="color: white">85.0</td>
                                                <td bgcolor="blue" style="color: white">95.0</td>
                                                <td bgcolor="blue" style="color: white">96.0</td>
                                                <td bgcolor="green" style="color: white">94.0</td>
                                                <td bgcolor="blue" style="color: white">96.0</td>
                                                <td bgcolor="blue" style="color: white">97.0</td>
                                                <td bgcolor="green" style="color: white">93.7</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered" style="font-size: 13px">
                                        <thead>
                                            <tr>
                                                @foreach ($arrHeaderTrd as $item)
                                                <th>{{ $item }}</th>
                                                @endforeach
                                                <th id="th_years">2023</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rekapAFD">
                                            <tr>
                                                <td>1</td>
                                                <td>BGE</td>
                                                <td>OA</td>
                                                <td>Eko Nuri W.</td>
                                                <td bgcolor="blue" style="color: white">100</td>
                                                <td bgcolor="blue" style="color: white">99.0</td>
                                                <td bgcolor="blue" style="color: white">95.0</td>
                                                <td bgcolor="blue" style="color: white">100.0</td>
                                                <td bgcolor="green" style="color: white">89.0</td>
                                                <td bgcolor="yellow">78.0</td>
                                                <td bgcolor="green" style="color: white">85.0</td>
                                                <td bgcolor="blue" style="color: white">95.0</td>
                                                <td bgcolor="blue" style="color: white">96.0</td>
                                                <td bgcolor="green" style="color: white">94.0</td>
                                                <td bgcolor="blue" style="color: white">96.0</td>
                                                <td bgcolor="blue" style="color: white">97.0</td>
                                                <td bgcolor="green" style="color: white">93.7</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row text-center">
                                <div class="col">
                                    <div class="card-body">
                                        <p style="font-size: 15px"><b><u>BRONDOLAN TINGGAL</u></b></p>
                                        <div id="brondolanGraphYear"></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card-body">
                                        <p style="font-size: 15px"><b><u>BUAH TINGGAL</u></b></p>
                                        <div id="buahGraphYear"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row text-center">
                                <div class="col">
                                    <div class="card-body">
                                        <p style="font-size: 15px"><b><u>BRONDOLAN TINGGAL</u></b></p>
                                        <div id="brondolanGraphWilYear"></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card-body">
                                        <p style="font-size: 15px"><b><u>BUAH TINGGAL</u></b></p>
                                        <div id="buahGraphWilYear"></div>
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
                                <input class="form-control" value="{{ date('Y-m') }}" type="month" name="tgl"
                                    id="dateDataIns">
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
                                            <th class="align-middle" bgcolor="#588434">Pokok    </th>
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
                                <input class="form-control" value="{{ date('Y-m') }}" type="month" name="tgl"
                                    id="dateFind">
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
                            <button class="btn btn-primary mb-3" style="float: right">Show</button>
                            <div class="col-2 mr-2" style="float: right">
                                {{csrf_field()}}
                                <select class="form-control" id="estData">
                                    <option value="SLE" selected>SLE</option>
                                    <option value="RGE">RGE</option>
                                    <option value="KNE">KNE</option>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    $(document).ready(function () {
        changeData()
        getFindData()

        setTimeout(function () {
            map.invalidateSize()
            removeMarkers()
            getPlotBlok()
            // getPlotEstate()
        }, 2000);
    });
    
    $("#showDataIns").click(function() {
        changeData()
    });
    
    $("#showFinding").click(function(){
        getFindData()
    });

    var map = L.map('map').setView([-2.2745234, 111.61404248], 13);
    
    googleSat = L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);

    var legendVar = ''

    $('#showEstMap').click(function(){
        map.removeControl(legendVar)
        removeMarkers()
        getPlotBlok()
        // getPlotEstate()
    });  
    
    var titleEstate = new Array();
    function drawEstatePlot(est,plot){
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
                    geoJsonEst += '"' + est +'"},'
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
            onEachFeature: function(feature, layer){
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
    function drawBlokPlot(blok){
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
                    getPlotStr += '"'+ blok[i][1]['blok'] +'",'
                    getPlotStr += '"estate"'
                    getPlotStr += ":"
                    getPlotStr += '"'+ blok[i][1]['estate'] +'",'
                    getPlotStr += '"nilai"'
                    getPlotStr += ":"
                    getPlotStr += '"'+ blok[i][1]['nilai'] +'"'
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
            onEachFeature: function(feature, layer){
                layer.myTag = 'BlokMarker'

                var popupContent = "<p><b>Blok</b>: " + feature.properties.blok + "</p>" ;

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
        map.eachLayer( function(layer) {
            if (layer.myTag && layer.myTag === "EstateMarker") {
                map.removeLayer(layer)
            }
            if(layer.myTag && layer.myTag === "BlokMarker"){
                map.removeLayer(layer)
            }
        });

        for(i=0;i<titleBlok.length;i++) {
            map.removeLayer(titleBlok[i]);
        }  
        for(i=0;i<titleEstate.length;i++) {
            map.removeLayer(titleEstate[i]);
        } 
    }

    function getPlotEstate() {
        var _token = $('input[name="_token"]').val();
        var estData = $("#estDataMap").val();
        const params = new URLSearchParams(window.location.search)
        var paramArr = [];
        for (const param of  params) {
            paramArr = param
        }
        $.ajax({
        url:"{{ route('plotEstate') }}",
        method:"POST",
        data:{est:estData,  _token:_token},
        success:function(result)
        {
            var estate = JSON.parse(result);
            drawEstatePlot(estate['est'], estate['plot'])
        }
        })
    }

    function getPlotBlok(){
        var _token = $('input[name="_token"]').val();
        var estData = $("#estDataMap").val();
        const params = new URLSearchParams(window.location.search)
        var paramArr = [];
        for (const param of  params) {
            paramArr = param
        }

        $.ajax({
        url:"{{ route('plotBlok') }}",
        method:"POST",
        data:{ est:estData,  _token:_token},
        success:function(result)
        {
            var plot = JSON.parse(result);
            const blokResult = Object.entries(plot['blok']);
            const lgd = Object.entries(plot['legend']);
            drawBlokPlot(blokResult)

            var legend = L.control({ position: "bottomright" });
            legend.onAdd = function(map) {
                var div = L.DomUtil.create("div", "legend");
                div.innerHTML += '<table class="table table-bordered text center" style="height:fit-content; font-size: 12px;"> <thead> <tr bgcolor="lightgrey"> <th rowspan="2" class="align-middle">Score</th><th colspan="2">Blok</th> </tr> <tr bgcolor="lightgrey"> <th>Jumlah</th> <th>%</th> </tr> </thead> <tbody><tr><td bgcolor="#4874c4">Excellent</td><td>'+lgd[0][1]+'</td><td>'+lgd[6][1]+'</td></tr><tr><td bgcolor="#00ff2e">Good</td><td>'+lgd[1][1]+'</td><td>'+lgd[7][1]+'</td></tr><tr><td bgcolor="yellow">Satisfactory</td><td>'+lgd[2][1]+'</td><td>'+lgd[8][1]+'</td></tr><tr><td bgcolor="orange">Fair</td><td>'+lgd[3][1]+'</td><td>'+lgd[9][1]+'</td></tr><tr><td bgcolor="red">Poor</td><td>'+lgd[4][1]+'</td><td>'+lgd[10][1]+'</td></tr><tr bgcolor="lightgrey"><td>TOTAL</td><td colspan="2">'+lgd[5][1]+'</td></tr></tbody></table>';
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
            url:"{{ route('changeDataInspeksi') }}",
            method:"POST",
            cache: false,
            data:{  _token: _token, regional:regIns,date:dateIns },
            success:function(result)
            {
                $("#dataInspeksi").html(result);
            }
        });
    }

    function getFindData() {
        $('#bodyFind').empty()

        var regional = $( "#regFind" ).val();
        var date = $( "#dateFind" ).val();
        var _token = $('input[name="_token"]').val();

        $.ajax({
            url:"{{ route('getFindData') }}",
            method:"POST",
            data:{ regional:regional,date:date, _token:_token},
            success:function(result)
            {
              var parseResult = JSON.parse(result)
              var dataResFind = Object.entries(parseResult['dataResFind']) //parsing data brondolan ke dalam var list

            //   console.log(dataResFind[0])
              dataResFind.forEach(function (value, key) {
                dataResFind[key].forEach(function (value1, key1) {
                    Object.entries(value1).forEach(function (value2, key2) {
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

                            itemElement1.innerText  = item1
                            itemElement2.innerText  = item2
                            itemElement3.innerText  = item3
                            itemElement4.innerText  = item4
                            itemElement5.innerText  = item5
                            itemElement6.innerText  = item6
                            itemElement7.innerHTML  =  '<a href="/cetakPDFFI/1/' + value2[0] + '/'+ date + '" class="btn btn-primary" target="_blank"><i class="nav-icon fa fa-download"></i></a>'
                            itemElement8.innerHTML  =  '<a href="/cetakPDFFI/2/' + value2[0] + '/'+ date + '" class="btn btn-primary" target="_blank"><i class="nav-icon fa fa-download"></i></a>'
                            itemElement9.innerHTML  =  '<a href="/cetakPDFFI/3/' + value2[0] + '/'+ date + '" class="btn btn-primary" target="_blank"><i class="nav-icon fa fa-download"></i></a>'

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

    //untuk chart
    var list_wilayah = <?php echo json_encode($queryEsta); ?>;
    var list_btt = <?php echo json_encode($chartBTT);?>;
    var list_buah = <?php echo json_encode($chartBuahTT);?>;
    var list_brdWil = <?php echo json_encode($chartPerwil);?>;
    var list_buahWil = <?php echo json_encode($buahPerwil);?>;

    var wilayah = '['
    list_wilayah.forEach(element => {
            wilayah += '"' +element + '",'
          });
    wilayah = wilayah.substring(0, wilayah.length - 1);
    wilayah += ']'



    var buahWil= '['
    list_brdWil.forEach(element => {
            buahWil += '"' +element + '",'
          });
    buahWil = buahWil.substring(0, buahWil.length - 1);
    buahWil += ']'

    var btWill= '['
    list_buahWil.forEach(element => {
            btWill += '"' +element + '",'
          });
    btWill = btWill.substring(0, btWill.length - 1);
    btWill += ']'
  
    var estate = JSON.parse(wilayah)
    // var brd_jjg = JSON.parse(brd)
    // console.log(brd_jjg)
    // var buah_jjg = JSON.parse(buah)
    var brd_wil = JSON.parse(buahWil)
    var buah_wil = JSON.parse(btWill)




    var options = {
    chart: {
        height: 350,
        type: "line",
        stacked: false
    },
    dataLabels: {
        enabled: false
    },
    colors: ["#FF1654", "#247BA0"],
    series: [
        {
        name: "Series A",
        data: [1.4, 2, 2.5, 1.5, 2.5, 2.8, 3.8, 4.6]
        },
        {
        name: "Series B",
        data: [20, 29, 37, 36, 44, 45, 50, 58]
        }
    ],
    stroke: {
        width: [4, 4]
    },
    plotOptions: {
        bar: {
        columnWidth: "20%"
        }
    },
    xaxis: {
        categories: [2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016]
    },
    yaxis: [
        {
        axisTicks: {
            show: true
        },
        axisBorder: {
            show: true,
            color: "#FF1654"
        },
        labels: {
            style: {
            colors: "#FF1654"
            }
        },
        title: {
            text: "Series A",
            style: {
            color: "#FF1654"
            }
        }
        },
        {
        opposite: true,
        axisTicks: {
            show: true
        },
        axisBorder: {
            show: true,
            color: "#247BA0"
        },
        labels: {
            style: {
            colors: "#247BA0"
            }
        },
        title: {
            text: "Series B",
            style: {
            color: "#247BA0"
            }
        }
        }
    ],
    tooltip: {
        shared: false,
        intersect: true,
        x: {
        show: false
        }
    },
    legend: {
        horizontalAlign: "left",
        offsetX: 40
    }
    };
    var chartScore = new ApexCharts(document.querySelector("#skorGraph"), options);
    chartScore.render();

    var options = {
    chart: {
        height: 350,
        type: "line",
        stacked: false
    },
    dataLabels: {
        enabled: false
    },
    colors: ["#FF1654", "#247BA0"],
    series: [
        {
        name: "Series A",
        data: [1.4, 2, 2.5, 1.5, 2.5, 2.8, 3.8, 4.6]
        },
        {
        name: "Series B",
        data: [20, 29, 37, 36, 44, 45, 50, 58]
        }
    ],
    stroke: {
        width: [4, 4]
    },
    plotOptions: {
        bar: {
        columnWidth: "20%"
        }
    },
    xaxis: {
        categories: [2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016]
    },
    yaxis: [
        {
        axisTicks: {
            show: true
        },
        axisBorder: {
            show: true,
            color: "#FF1654"
        },
        labels: {
            style: {
            colors: "#FF1654"
            }
        },
        title: {
            text: "Series A",
            style: {
            color: "#FF1654"
            }
        }
        },
        {
        opposite: true,
        axisTicks: {
            show: true
        },
        axisBorder: {
            show: true,
            color: "#247BA0"
        },
        labels: {
            style: {
            colors: "#247BA0"
            }
        },
        title: {
            text: "Series B",
            style: {
            color: "#247BA0"
            }
        }
        }
    ],
    tooltip: {
        shared: false,
        intersect: true,
        x: {
        show: false
        }
    },
    legend: {
        horizontalAlign: "left",
        offsetX: 40
    }
    };
    var chartScoreBron = new ApexCharts(document.querySelector("#skorBronGraph"), options);
    chartScoreBron.render();

    var options = {
    chart: {
        height: 350,
        type: "line",
        stacked: false
    },
    dataLabels: {
        enabled: false
    },
    colors: ["#FF1654", "#247BA0"],
    series: [
        {
        name: "Series A",
        data: [1.4, 2, 2.5, 1.5, 2.5, 2.8, 3.8, 4.6]
        },
        {
        name: "Series B",
        data: [20, 29, 37, 36, 44, 45, 50, 58]
        }
    ],
    stroke: {
        width: [4, 4]
    },
    plotOptions: {
        bar: {
        columnWidth: "20%"
        }
    },
    xaxis: {
        categories: [2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016]
    },
    yaxis: [
        {
        axisTicks: {
            show: true
        },
        axisBorder: {
            show: true,
            color: "#FF1654"
        },
        labels: {
            style: {
            colors: "#FF1654"
            }
        },
        title: {
            text: "Series A",
            style: {
            color: "#FF1654"
            }
        }
        },
        {
        opposite: true,
        axisTicks: {
            show: true
        },
        axisBorder: {
            show: true,
            color: "#247BA0"
        },
        labels: {
            style: {
            colors: "#247BA0"
            }
        },
        title: {
            text: "Series B",
            style: {
            color: "#247BA0"
            }
        }
        }
    ],
    tooltip: {
        shared: false,
        intersect: true,
        x: {
        show: false
        }
    },
    legend: {
        horizontalAlign: "left",
        offsetX: 40
    }
    };
    var chatScoreJan = new ApexCharts(document.querySelector("#skorJanGraph"), options);
    chatScoreJan.render();


///Data test


    var options = {
          
          series: [
       { name: '',
      data: [0, 0 , 0, 0, 0, 0, 0 , 0 ,0 , 0 , 0 , 0] }
        ],
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
  '#FF8D1A'
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
   var will= { 
        series: [{
            name: 'Butir/Ha Sample',
            data: [0,0,0]
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
        colors: ['#00FF00','#3063EC','#FF8D1A'],
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

 document.getElementById('btnShow').onclick = function(){
  $('#tbody1').empty()
  $('#tbody2').empty()
  $('#tbody3').empty()
var date = ''
var _token = $('input[name="_token"]').val();
var date = document.getElementById('inputDate').value

// console.log(date);
    $.ajax({
        url:"{{ route('filter') }}",
        method:"GET",
        data:{ date, _token:_token},
        success:function(result)
        {

    var parseResult = JSON.parse(result)
    //list estate
    var list_will = Object.entries(parseResult['list_estate'] )
    
    // untuk table
    var val_wil = Object.entries(parseResult['value_tblWIl'])
    var val_est = Object.entries(parseResult['value_tblEST'])
    //untuk chart
    var chart_btt = Object.entries(parseResult['chart_brd'])
    var chart_buah = Object.entries(parseResult['chart_buah'])

    var chartWillbt = Object.entries(parseResult['chart_brdwil'])
    var chartWillbh = Object.entries(parseResult['chart_buahwil'])
    // unutk table utama

    //untuk tabel pertahun
    var FinalTahun = Object.entries(parseResult['FinalTahun'])
    // console.log(chart_btt);

  
    // console.log(FinalTahun);

    var wilayah = '['
    list_will.forEach(element => {
            wilayah += '"' +element + '",'
          });
    wilayah = wilayah.substring(0, wilayah.length - 1);
    wilayah += ']'

    var brd= '['
    if (chart_btt.length > 0) {
        chart_btt.forEach(element => {
            brd += '"' +element[1] + '",'
        });
        brd = brd.substring(0, brd.length - 1);
    } else {
        brd = '[0, 0 , 0, 0, 0, 0, 0 , 0 ,0 , 0 , 0 , 0]'
    }
    brd += ']'

    var buah= '['
    chart_buah.forEach(element => {
            buah += '"' +element[1] + '",'
          });
    buah = buah.substring(0, buah.length - 1);
    buah += ']'

    var bttWil= '['
    chartWillbt.forEach(element => {
            bttWil += '"' +element [1]+ '",'
          });
    bttWil = bttWil.substring(0, bttWil.length - 1);
    bttWil += ']'

    var bhWil= '['
    chartWillbh.forEach(element => {
            bhWil += '"' +element[1]  + '",'
          });
    bhWil = bhWil.substring(0, bhWil.length - 1);
    bhWil += ']'

    var estate = JSON.parse(wilayah)
    var brd_jjgJson = JSON.parse(brd)
    var buah_jjgJson = JSON.parse(buah)
        
    var brd_wilJson = JSON.parse(bttWil)
    var buah_wilJson = JSON.parse(bhWil)



    chartGrain.updateSeries([{
                name: 'butir/jjg panen',
                data: brd_jjgJson
            }])

    chartFruit.updateSeries([{
          name: 'buah %',
           data: buah_jjgJson
         }])

         chartGrainWil.updateSeries([{
          name: 'butir/Ha sample',
           data: brd_wilJson
         }])

         chartFruitWil.updateSeries([{
          name: 'buah/Ha sample',
           data: buah_wilJson
         }])


         //untuk table utama
     
          //end table

    
         //end ajax
        }
        });

    }

    //tampilkan pertahun filter table utama
 document.getElementById('showTahung').onclick = function(){
    $('#tb_tahun').empty()
    $('#tablewil').empty()
    $('#reg').empty()
    $('#rekapAFD').empty()

    

            var year = ''
            var _token = $('input[name="_token"]').val();
            var year = document.getElementById('yearDate').value

                    $.ajax({
                            url:"{{ route('filter') }}",
                            method:"GET",
                            data:{ year, _token:_token},
                            success:function(result)
                            {
                                // console.log(result)
                         var parseResult = JSON.parse(result)
                        //list estate
                        var list_tabel = Object.entries(parseResult['FinalTahun'] )
                        var total_tahun = Object.entries(parseResult['Final_end'] )
                        var rekap_bulan= Object.entries(parseResult['RekapBulan'] )
                        var rekap_tahun = Object.entries(parseResult['RekapTahun'] )
                        var rekap_bulanwil= Object.entries(parseResult['RekapBulanwil'] )
                        var rekap_tahunwil = Object.entries(parseResult['RekapTahunwil'] )
                        var RekapBulanReg = Object.entries(parseResult['RekapBulanReg'])
                         var RekapTahunReg = Object.entries(parseResult['RekapTahunReg'])
                         var RekapBulanAFD = Object.entries(parseResult['RekapBulanAFD'])
                         var RekapTahunAFD = Object.entries(parseResult['RekapTahunAFD'])
                         var chart_brdTH = Object.entries(parseResult['chart_brdTAHUN'])
                         var chart_bhTH = Object.entries(parseResult['chart_buahTAHUN'])
                         var chart_brdWIl = Object.entries(parseResult['chartbrdWilTH'])
                         var chart_bhWil = Object.entries(parseResult['chartBhwilTH'])   
                         var list_will = Object.entries(parseResult['list_estate'] )


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
                                        wilayah += '"' +element + '",'
                                    });
                                wilayah = wilayah.substring(0, wilayah.length - 1);
                                wilayah += ']'

                           

    var estate = JSON.parse(wilayah)
    var bttEst = JSON.parse(bttEST)
    var bhEST = JSON.parse(bhEST)

    var brdWil = JSON.parse(brdWil)
    var bhWil = JSON.parse(bhWil)
   
    // console.log(bttEST)
   
    

   
  
                        // console.log(year)
                        // console.log(rekap_bulan)
                        
                        //untuk table
                        var arrbodywil = rekap_bulan;
                        // console.log(arrbodywil)
                        var yearStr = year.toString();
                        var totalBody = rekap_tahun;
                        
                    //   console.log(totalBody)
                            
// var table1 = document.getElementById('table_th');
var tbody1 = document.getElementById('tb_tahun');

arrbodywil.forEach((element, index) => {
  var tr = document.createElement('tr');

  let slangEst = {
    "KNE": "Kenambui",
    "PLE": "Pulau",
    "RDE": "Rangda",
    "SLE": "Sulung",
    "BKE": "Batu Kotam",
    "KDE": "Kondang",
    "RGE": "Rungun",
    "SGE": "Selangkun",
    "BGE": "Bengaris",
    "NBE": "Natai Baru",
    "SYE": "Suayap",
    "UPE": "Umpang"
  }

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
  let item2 = slangEst[item3];
  let item4 = namaEst[item3];
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

  let items = [item1, item2, item3, item4, item5, item6, item7, item8, item9, item10, item11, item12, item13, item14, item15, item16 ,item17];

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

var totalBodywil = rekap_tahunwil;
// console.log(totalBodywil)
var tbody2 = document.getElementById('tablewil');

arrwil.forEach((element, index) => {
    tr = document.createElement('tr')
    let angka = element[0];
    if (angka === '1'){
        angka = 'I';
    }else if (angka === '2'){
        angka = 'II';
    }else if (angka === '3'){
        angka = 'III';
    }

    let namaGM = {
    "1": "Kinan Efran Harahap",
    "2": "Sucipto",
    "3": "Achmad Kursani"
  }

    let item1 = element[0];
    let item2 = namaGM[item1];
    let item3 = element[1].January.skor_bulanTotal;
  let item4 = element[1].February.skor_bulanTotal;
  let item5 = element[1].March.skor_bulanTotal;
  let item6= element[1].April.skor_bulanTotal;
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
    if (totalBodywil[i][0] == item1){
        tahunskor.push(totalBodywil[i][1]['tahun_skorwil']);
  break;
    }
 
}

let item15 = tahunskor;

    let items = [item1, item2, item3, item4, item5, item6, item7, item8, item9, item10, item11, item12, item13, item14, item15];

    // Create a td element with colspan="3" for the first three items
    let td1 = document.createElement('td');
    td1.colSpan = "3";
    td1.innerText = item1 ;
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

//table untuk regional 1
var regbln = RekapBulanReg;
var regthn = RekapTahunReg;
// console.log(regthn);
var tbody3 = document.getElementById('reg');


    tr = document.createElement('tr');

    let item1 = 'I';
    let item2 = 'Akhmad Faisyal';
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
    td1.innerText = item1 ;
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
  var arrAFDTH = RekapTahunAFD;
//   console.log(arrAFDTH)

  var tbody4 = document.getElementById('rekapAFD');
  let currentIndex = 1;

  arrAFD.sort(function(a, b) {
    let estateA = a[0];
    let asistenA = Object.keys(a[1].January)[0];
    let item16A = 0;
    for (var i = 0; i < arrAFDTH.length; i++) {
        if (arrAFDTH[i][0] == estateA) {
            item16A = arrAFDTH[i][1][asistenA]['tahun_skorwil'];
            // console.log(item16A)
            break;
        }
    }

    let estateB = b[0];
    let asistenB = Object.keys(b[1].January)[0];
    console.log(asistenB)
    let item16B = 0;
    for (var i = 0; i < arrAFDTH.length; i++) {
        if (arrAFDTH[i][0] == estateB) {
            item16B = arrAFDTH[i][1][asistenB]['tahun_skorwil'];
            break;
        }
    }

    return item16B - item16A;
    // return item16A - item16B ;
});


  arrAFD.forEach((element, index) =>{
  let estate = element[0];
  let namaAFD = Object.keys(element[1].January);
  namaAFD.forEach((asisten) => {
    tr = document.createElement('tr');
    let item0 = currentIndex++;
    let item1 = estate;
    let item2 = asisten;
    let item3 = '-'
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

    let items = [item0, item1, item2,item3,item4,item5,item6, item7, item8, item9, item10, item11, item12, item13, item14, item15, item16];

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

//   end rekap table afd
                        //endtable
                        //chart table untuk pertahun
                        chartGrainYear.updateSeries([{
                name: 'butir/jjg panen',
                data: bttEst
            }])

            chartFruitYear.updateSeries([{
          name: 'buah %',
           data: bhEST
         }])

         
         chartGrainWilYear.updateSeries([{
          name:'butir/ha sample',
           data: brdWil
         }])
            
         chartFruitWilYear.updateSeries([{
          name:'janjang/ha sample',
           data: bhWil
         }])

   
    

                        //endchart
        }
    });
    }

    
</script>