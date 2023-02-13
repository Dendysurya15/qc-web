@include('layout/header')
<style>

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
                            <button class="btn btn-primary mb-3" style="float: right">Show</button>
                            <div class="col-2 mr-2" style="float: right">
                                {{csrf_field()}}
                                <select class="form-control" id="regionalData">
                                    <option value="1" selected>Regional 1</option>
                                    <option value="2">Regional 2</option>
                                    <option value="3">Regional 3</option>
                                </select>
                            </div>
                            <div class="col-2" style="float: right">
                                <input class="form-control" value="{{ date('Y-m') }}" type="month" name="tgl"
                                    id="input">
                            </div>
                        </div>

                        <div class="ml-3 mr-3 mb-3">
                            <div class="row text-center">
                                <div class="col-12" style="overflow-y:scroll;"">
                                    <table id=" tbData" class="table table-bordered"
                                    style="width: 100%; font-size: 13px;">
                                    <thead style="color: white;">
                                        <tr>
                                            {{-- <th rowspan="3" bgcolor="darkblue">Est.</th>
                                            <th rowspan="3" bgcolor="darkblue">Afd.</th> --}}
                                            <th class="freeze-col" rowspan="3" bgcolor="darkblue">Est.</th>
                                            <th class="freeze-col" rowspan="3" bgcolor="darkblue">Afd</th>
                                            <th colspan="8" bgcolor="blue">Mutu Transport (MT)</th>
                                            <th colspan="22" bgcolor="yellow" style="color: #000000;">Mutu Buah (MB)
                                            <th colspan="4" rowspan="2" bgcolor="blue">DATA BLOK SAMPEL</th>
                                            <th colspan="17" bgcolor="blue">Mutu Ancak (MA)</th>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2" bgcolor="blue">TPH Sampel</th>
                                            <th colspan="3" bgcolor="blue">Brd Tinggal</th>
                                            <th colspan="3" bgcolor="blue">Buah Tinggal</th>
                                            <th rowspan="2" bgcolor="blue">Total Skor</th>
                                            {{-- TAble Mutu Buah --}}
                                            <th rowspan="2" bgcolor="yellow" style="color: #000000;">Total Janjang
                                                Sampel</th>

                                            <th colspan="3" bgcolor="yellow" style="color: #000000;">Mentah (A)</th>
                                            <th colspan="3" bgcolor="yellow" style="color: #000000;">Matang (N)</th>
                                            <th colspan="3" bgcolor="yellow" style="color: #000000;">Lewat Matang
                                                (O)</th>
                                            <th colspan="3" bgcolor="yellow" style="color: #000000;">Janjang Kosong
                                                (E)</th>
                                            <th colspan="3" bgcolor="yellow" style="color: #000000;">Tidak Standar
                                                V-Cut</th>
                                            <th colspan="2" bgcolor="yellow" style="color: #000000;">Abnormal</th>
                                            <th colspan="3" bgcolor="yellow" style="color: #000000;">Penggunaan
                                                Karung Brondolan</th>
                                            <th rowspan="2" bgcolor="yellow" style="color: #000000;">Total Skor</th>
                                            {{-- Table Mutu Ancak --}}

                                            <th colspan="6" bgcolor="blue">Bronolan Tinggal</th>
                                            <th colspan="7" bgcolor="blue">Buah Tinggal</th>
                                            <th colspan="3" bgcolor="blue">Pelepah Sengkleh</th>
                                            <th rowspan="2" bgcolor="blue">Total Skor</th>

                                        </tr>
                                        <tr>
                                            <th bgcolor="blue">Butir</th>
                                            <th bgcolor="blue">Butir/TPH</th>
                                            <th bgcolor="blue">Skor</th>
                                            <th bgcolor="blue">Jjg</th>
                                            <th bgcolor="blue">Jjg/TPH</th>
                                            <th bgcolor="blue">Skor</th>
                                            {{-- table mutu Buah --}}
                                            <th bgcolor="yellow" style="color: #000000;">Jjg</th>
                                            <th bgcolor="yellow" style="color: #000000;">%</th>
                                            <th bgcolor="yellow" style="color: #000000;">Skor</th>

                                            <th bgcolor="yellow" style="color: #000000;">Jjg</th>
                                            <th bgcolor="yellow" style="color: #000000;">%</th>
                                            <th bgcolor="yellow" style="color: #000000;">Skor</th>

                                            <th bgcolor="yellow" style="color: #000000;">Jjg</th>
                                            <th bgcolor="yellow" style="color: #000000;">%</th>
                                            <th bgcolor="yellow" style="color: #000000;">Skor</th>

                                            <th bgcolor="yellow" style="color: #000000;">Jjg</th>
                                            <th bgcolor="yellow" style="color: #000000;">%</th>
                                            <th bgcolor="yellow" style="color: #000000;">Skor</th>

                                            <th bgcolor="yellow" style="color: #000000;">Jjg</th>
                                            <th bgcolor="yellow" style="color: #000000;">%</th>
                                            <th bgcolor="yellow" style="color: #000000;">Skor</th>

                                            <th bgcolor="yellow" style="color: #000000;">Jjg</th>
                                            <th bgcolor="yellow" style="color: #000000;">%</th>

                                            <th bgcolor="yellow" style="color: #000000;">TPH</th>
                                            <th bgcolor="yellow" style="color: #000000;">%</th>
                                            <th bgcolor="yellow" style="color: #000000;">Skor</th>

                                            {{-- Table Mutu Ancak --}}
                                            <th bgcolor="blue">Jumlah Pokok Sampel</th>
                                            <th bgcolor="blue">Luas Ha Sampel</th>
                                            <th bgcolor="blue">Jumlah Jjg Panen</th>
                                            <th bgcolor="blue">AKP Realisasi</th>

                                            <th bgcolor="blue">P</th>
                                            <th bgcolor="blue">K</th>
                                            <th bgcolor="blue">GL</th>
                                            <th bgcolor="blue">Total Brd</th>
                                            <th bgcolor="blue">Brd/JJG</th>
                                            <th bgcolor="blue">Skor</th>

                                            <th bgcolor="blue">S</th>
                                            <th bgcolor="blue">M1</th>
                                            <th bgcolor="blue">M2</th>
                                            <th bgcolor="blue">M3</th>
                                            <th bgcolor="blue">Total JJG</th>
                                            <th bgcolor="blue">JJG tinggal/ji</th>
                                            <th bgcolor="blue">Skor</th>


                                            <th bgcolor="blue">Jjg</th>
                                            <th bgcolor="blue">%</th>
                                            <th bgcolor="blue">Skor</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @php
                                        $totalSkor = 0;
                                        $totalSkorAkhir= 0;
                                        @endphp

                                        @foreach ($dataSkor as $key => $value)
                                        @foreach ($value as $key1 => $value1)
                                        @foreach($value1 as $key2 => $value3)
                                        @php
                                        $commonKey2 = $key1;
                                        $commonKey = $key;
                                        @endphp
                                        <tr>
                                            <td>{{ $commonKey }}</td>
                                            <td>{{ $commonKey2 }}</td>
                                            <td>{{ $value3['tph_sample'] }}</td>
                                            <td>{{ $value3['bt_total'] }}</td>
                                            <td>{{ $value3['skor'] }}</td>
                                            <td>{{ $value3['skor_akhir'] }}</td>
                                            {{-- bagian buah restan --}}
                                            <td>{{ $value3['restan_total'] }}</td>
                                            <td>{{ $value3['skor_restan'] }}</td>
                                            <td>{{ $value3['skor_akhir_restan'] }}</td>
                                            @php
                                            $totalSkor = $value3['skor_akhir'] + $value3['skor_akhir_restan'];
                                            @endphp
                                            <td>{{ $totalSkor }}</td>
                                            {{-- Data Mutu Buah (MA) --}}
                                            @php
                                            $totalSkorMtBuah = 0;
                                            @endphp
                                            @foreach ($Mutubuah as $key => $value)
                                            @foreach ($value as $key2 => $value2)
                                            @foreach ($value2 as $key3 => $value3)
                                            @if ($key == $commonKey && $key2 == $commonKey2)
                                            {{-- bagian Buah mentah --}}
                                            <td>{{ $value3['jml_janjang'] }}</td>
                                            <td>{{ $value3['jml_mentah'] }}</td>
                                            <td>{{ $value3['PersenBuahMentah'] }}</td>
                                            <td>{{ $value3['Skor_mentah'] }}</td>
                                            {{-- bagian buah masak --}}
                                            <td>{{ $value3['jml_masak'] }}</td>
                                            <td>{{ $value3['PersenBuahMasak'] }}</td>
                                            <td>{{ $value3['Skor_masak'] }}</td>
                                            {{-- bagian buah lewat matang --}}
                                            <td>{{ $value3['jml_over'] }}</td>
                                            <td>{{ $value3['PersenBuahOver'] }}</td>
                                            <td>{{ $value3['Skor_over'] }}</td>
                                            {{-- bagian Janjang kosong --}}
                                            <td>{{ $value3['jml_empty'] }}</td>
                                            <td>{{ $value3['PersenPerJanjang'] }}</td>
                                            <td>{{ $value3['Skor_PerJanjang'] }}</td>
                                            {{-- bagian Tidak standar v-cut--}}
                                            <td>{{ $value3['jml_vcut'] }}</td>
                                            <td>{{ $value3['PersenVcut'] }}</td>
                                            <td>{{ $value3['Skore_Vcut'] }}</td>
                                            {{-- bagian Abnormal --}}
                                            <td>{{ $value3['jml_abnormal'] }}</td>
                                            <td>{{ $value3['PersenAbr'] }}</td>

                                            {{-- bagian penggunaan karung brondolan --}}
                                            <td>{{ $value3['jml_alas_br'] }}</td>
                                            <td>{{ $value3['jml_alas_br'] }}</td>
                                            <td>{{ $value3['Skor_over'] }}</td>
                                            {{-- skor akhir --}}
                                            @php
                                            $totalSkorMtBuah = $value3['Skor_mentah']
                                            + $value3['Skor_masak']
                                            + $value3['Skor_over']
                                            + $value3['Skor_PerJanjang']
                                            + $value3['Skore_Vcut']
                                            + $value3['Skor_over']

                                            ;
                                            @endphp
                                            <td>{{ $totalSkorMtBuah }}</td>
                                            @endif
                                            @endforeach
                                            @endforeach
                                            @endforeach
                                            @foreach ($MutuAncak as $key => $value)
                                            @foreach ($value as $key2 => $value2)
                                            @foreach ($value2 as $key3 => $value3)
                                            @if ($key == $commonKey && $key2 == $commonKey2)
                                            <td>{{ $value3['pokok_sample'] }}</td>
                                            <td>{{ $value3['jum_ha'] }}</td>
                                            {{-- <td>-</td> --}}
                                            <td>{{ $value3['jumlah_panen'] }}</td>
                                            <td>{{ $value3['akp_rl'] }}</td>
                                            {{-- bagian brondolan --}}
                                            <td>{{ $value3['p'] }}</td>
                                            <td>{{ $value3['k'] }}</td>
                                            <td>{{ $value3['tgl'] }}</td>
                                            <td>{{ $value3['total_brd'] }}</td>
                                            <td>{{ $value3['brd/jjg'] }}</td>
                                            <td>{{ $value3['skor_brd'] }}</td>
                                            {{-- bagian buah tinggal --}}
                                            <td>{{ $value3['s'] }}</td>
                                            <td>{{ $value3['m1'] }}</td>
                                            <td>{{ $value3['m2'] }}</td>
                                            <td>{{ $value3['m3'] }}</td>
                                            <td>{{ $value3['total_jjg'] }}</td>
                                            <td>{{ $value3['jjg/ji'] }}</td>
                                            <td>{{ $value3['skor_bhTgl'] }}</td>
                                            {{-- pelapah data --}}
                                            <td>{{ $value3['jjgPS'] }}</td>
                                            <td>{{ $value3['perPl'] }}</td>
                                            <td>{{ $value3['skor_perPl'] }}</td>
                                            @php
                                            $totalSkorAkhir =
                                            $value3['skor_brd']
                                            + $value3['skor_bhTgl']
                                            + $value3['skor_perPl']

                                            ;
                                            @endphp

                                            <td>{{ $totalSkorAkhir}}</td>
                                            @endif
                                            @endforeach
                                            @endforeach
                                            @endforeach
                                        </tr>
                                        @endforeach
                                        @endforeach
                                        @endforeach

                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-issue" role="tabpanel" aria-labelledby="nav-issue-tab">
                        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                            <h5><b>QC PANEN</b></h5>
                        </div>

                        <div class="d-flex flex-row-reverse mr-3">
                            <button class="btn btn-primary mb-3" style="float: right">Show</button>
                            <div class="col-2 mr-2" style="float: right">
                                {{csrf_field()}}
                                <select class="form-control" id="regionalData">
                                    <option value="1" selected>Regional 1</option>
                                    <option value="2">Regional 2</option>
                                    <option value="3">Regional 3</option>
                                </select>
                            </div>
                            <div class="col-2" style="float: right">
                                <input class="form-control" value="{{ date('Y-m') }}" type="month" name="tgl"
                                    id="inputDate">
                            </div>
                        </div>

                        <div class="ml-4 mr-4">
                            <div class="row text-center">
                                <table class="table table-bordered" style="font-size: 13px">
                                    <thead bgcolor="gainsboro">
                                        <tr>
                                            <th rowspan="3" class="align-middle">ESTATE</th>
                                            <th colspan="5">Temuan Pemeriksaan Panen</th>
                                            <th rowspan="3" class="align-middle">Aksi</th>
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
                                    <tbody>
                                        <tr>
                                            <td class="align-middle">KNE</td>
                                            <td class="align-middle">19</td>
                                            <td class="align-middle">11</td>
                                            <td class="align-middle">57.89</td>
                                            <td class="align-middle">8</td>
                                            <td class="align-middle">42.11</td>
                                            <td class="align-middle">
                                                <button type="button" class="btn btn-success"><i
                                                        class="nav-icon fa fa-edit"></i></button>
                                                <button type="button" class="btn btn-primary"><i
                                                        class="nav-icon fa fa-download"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-score" role="tabpanel" aria-labelledby="nav-score-tab">
                        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                            <h5><b>SCORE KUALITAS PANEN BERDASARKAN BLOK</b></h5>
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
        $('#tbData').DataTable({
            "bPaginate": true
        });
    });

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