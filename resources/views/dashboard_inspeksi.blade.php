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
                                            <th colspan="4" rowspan="2" bgcolor="#588434">DATA BLOK SAMPEL</th>
                                            <th colspan="17" bgcolor="#588434">Mutu Ancak (MA)</th>
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

                                            <th colspan="6" bgcolor="#588434">Brondolan Tinggal</th>
                                            <th colspan="7" bgcolor="#588434">Buah Tinggal</th>
                                            <th colspan="3" bgcolor="#588434">Pelepah Sengkleh</th>
                                            <th rowspan="2" bgcolor="#588434">Total Skor</th>

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
                                            <th bgcolor="#588434">Jumlah Pokok Sampel</th>
                                            <th bgcolor="#588434">Luas Ha Sampel</th>
                                            <th bgcolor="#588434">Jumlah Jjg Panen</th>
                                            <th bgcolor="#588434">AKP Realisasi</th>
                                            <th bgcolor="#588434">P</th>
                                            <th bgcolor="#588434">K</th>
                                            <th bgcolor="#588434">GL</th>
                                            <th bgcolor="#588434">Total Brd</th>
                                            <th bgcolor="#588434">Brd/JJG</th>
                                            <th bgcolor="#588434">Skor</th>
                                            <th bgcolor="#588434">S</th>
                                            <th bgcolor="#588434">M1</th>
                                            <th bgcolor="#588434">M2</th>
                                            <th bgcolor="#588434">M3</th>
                                            <th bgcolor="#588434">Total JJG</th>
                                            <th bgcolor="#588434">JJG tinggal/ji</th>
                                            <th bgcolor="#588434">Skor</th>
                                            <th bgcolor="#588434">Jjg</th>
                                            <th bgcolor="#588434">%</th>
                                            <th bgcolor="#588434">Skor</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @foreach ($dataSkor as $key3 => $item3)
                                        @php
                                        // Mutu Transport Wilayah
                                        $bt_total_wil = 0;
                                        $tph_total_wil = 0;
                                        $bt_tph_total_wil = 0;
                                        $jjg_total_wil = 0;
                                        $jjg_tph_total_wil = 0;

                                        // Mutu Buah Wilayah
                                        $blok_mb = 0;
                                        $alas_mb = 0;
                                        $tot_jjg_wil = 0;
                                        $tot_mentah_wil = 0;
                                        $tot_matang_wil = 0;
                                        $tot_over_wil = 0;
                                        $tot_empty_wil = 0;
                                        $tot_vcut_wil = 0;
                                        $tot_abr_wil = 0;
                                        $tot_krg_wil = 0;
                                        $tot_Permentah_wil = 0;
                                        $tot_Permatang_wil = 0;
                                        $tot_Perover_wil = 0;
                                        $tot_Perjangkos_wil = 0;
                                        $tot_Pervcut_wil = 0;
                                        $tot_Perabr_wil = 0;
                                        $tot_Perkrg_wil = 0;
                                        @endphp
                                        @foreach ($dataSkor[$key3] as $key => $item)
                                        @if (is_array($item))
                                        @foreach ($item as $key2 => $value)
                                        @if (is_array($value))
                                        @php
                                        $bt_total_wil += check_array('bt_total', $value);
                                        $tph_total_wil += check_array('tph_sample', $value);
                                        $jjg_total_wil += check_array('restan_total', $value);

                                        $blok_mb += check_array('blok_mb', $value);
                                        $alas_mb += check_array('alas_mb', $value);
                                        $tot_jjg_wil += check_array('jml_janjang', $value);
                                        $tot_mentah_wil += check_array('jml_mentah', $value);
                                        $tot_matang_wil += check_array('jml_masak', $value);
                                        $tot_over_wil += check_array('jml_over', $value);
                                        $tot_empty_wil += check_array('jml_empty', $value);
                                        $tot_abr_wil += check_array('jml_abnormal', $value);
                                        $tot_vcut_wil += check_array('jml_vcut', $value);
                                        @endphp
                                        <tr>
                                            {{-- Bagian Mutu Transport --}}
                                            <td>{{$key}}</td>
                                            <td>{{$key2}}</td>
                                            <td>{{check_array('tph_sample', $value)}}</td>
                                            <td>{{check_array('bt_total', $value)}}</td>
                                            <td>{{check_array('skor', $value)}}</td>
                                            <td>{{skor_brd_tinggal(check_array('skor', $value))}}</td>
                                            <td>{{check_array('restan_total', $value)}}</td>
                                            <td>{{check_array('skor_restan', $value)}}</td>
                                            <td>{{skor_buah_tinggal(check_array('skor_restan', $value))}}</td>
                                            <td>{{ skor_brd_tinggal(check_array('skor', $value)) +
                                                skor_buah_tinggal(check_array('skor_restan', $value)) }}</td>
                                            {{-- Bagian Mutu Buah - Buah Mentah --}}
                                            <td>{{check_array('jml_janjang', $value)}}</td>
                                            <td>{{check_array('jml_mentah', $value)}}</td>
                                            <td>{{check_array('PersenBuahMentah', $value)}}</td>
                                            <td>{{skor_buah_mentah_mb(check_array('PersenBuahMentah', $value))}}</td>
                                            {{-- Bagian Mutu Buah - Buah Matang --}}
                                            <td>{{check_array('jml_masak', $value)}}</td>
                                            <td>{{check_array('PersenBuahMasak', $value)}}</td>
                                            <td>{{skor_buah_masak_mb(check_array('PersenBuahMasak', $value))}}</td>
                                            {{-- Bagian Mutu Buah - Lewat Matang --}}
                                            <td>{{check_array('jml_over', $value)}}</td>
                                            <td>{{check_array('PersenBuahOver', $value)}}</td>
                                            <td>{{skor_buah_over_mb(check_array('PersenBuahOver', $value))}}</td>
                                            {{-- Bagian Mutu Buah - Jangkos --}}
                                            <td>{{check_array('jml_empty', $value)}}</td>
                                            <td>{{check_array('PersenPerJanjang', $value)}}</td>
                                            <td>{{skor_jangkos_mb(check_array('PersenPerJanjang', $value))}}</td>
                                            {{-- Bagian Mutu Buah - Tidak Standar V-Cut --}}
                                            <td>{{check_array('jml_vcut', $value)}}</td>
                                            <td>{{check_array('PersenVcut', $value)}}</td>
                                            <td>{{skor_buah_over_mb(check_array('PersenVcut', $value))}}</td>
                                            {{-- Bagian Mutu Buah - Abnormal --}}
                                            <td>{{check_array('jml_abnormal', $value)}}</td>
                                            <td>{{check_array('PersenAbr', $value)}}</td>
                                            {{-- Bagian Mutu Buah - Karung Brondolan --}}
                                            <td>{{check_array('jml_krg_brd', $value)}}</td>
                                            <td>{{check_array('PersenKrgBrd', $value)}}</td>
                                            <td>{{skor_abr_mb(check_array('PersenKrgBrd', $value))}}</td>
                                            <td>{{skor_buah_mentah_mb(check_array('PersenBuahMentah', $value)) +
                                                skor_buah_masak_mb(check_array('PersenBuahMasak', $value))
                                                + skor_buah_over_mb(check_array('PersenBuahOver', $value)) +
                                                skor_jangkos_mb(check_array('PersenPerJanjang', $value)) +
                                                skor_buah_over_mb(check_array('PersenVcut', $value)) +
                                                skor_abr_mb(check_array('PersenKrgBrd', $value))}}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        <tr>
                                            <td style="background-color : #b0d48c; color: #000000;" colspan="2">{{$key}}
                                            </td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tph_sample_total', $item)}}</td>
                                            </td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('bt_total', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('bt_tph_total', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{skor_brd_tinggal(check_array('bt_tph_total', $item)) }}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('jjg_total', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('jjg_tph_total', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{skor_buah_tinggal(check_array('jjg_tph_total', $item)) }}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{skor_brd_tinggal(check_array('bt_tph_total',
                                                $item))+skor_buah_tinggal(check_array('jjg_tph_total', $item))
                                                }}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_jjg', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_mentah', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_PersenBuahMentah', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{skor_buah_mentah_mb(check_array('tot_PersenBuahMentah', $item))}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_matang', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_PersenBuahMasak', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{skor_buah_masak_mb(check_array('tot_PersenBuahMasak', $item))}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_over', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_PersenBuahOver', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{skor_buah_over_mb(check_array('tot_PersenBuahOver', $item))}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_empty', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_PersenPerJanjang', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{skor_jangkos_mb(check_array('tot_PersenPerJanjang', $item))}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_vcut', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_PersenVcut', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{skor_buah_over_mb(check_array('tot_PersenVcut', $item))}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_abr', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_PersenAbr', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_krg_brd', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{check_array('tot_PersenKrgBrd', $item)}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{skor_abr_mb(check_array('tot_PersenKrgBrd', $item))}}</td>
                                            <td style="background-color : #b0d48c; color: #000000;">
                                                {{skor_buah_mentah_mb(check_array('tot_PersenBuahMentah', $item)) +
                                                skor_buah_masak_mb(check_array('tot_PersenBuahMasak', $item))
                                                + skor_buah_over_mb(check_array('tot_PersenBuahOver', $item)) +
                                                skor_jangkos_mb(check_array('tot_PersenPerJanjang', $item)) +
                                                skor_buah_over_mb(check_array('tot_PersenVcut', $item)) +
                                                skor_abr_mb(check_array('tot_PersenKrgBrd', $item))}}</td>
                                        </tr>
                                        @php
                                        $bt_tph_total_wil = $bt_total_wil == 0 && $tph_total_wil == 0 ? 0 :
                                        round($bt_total_wil / $tph_total_wil, 2);
                                        $jjg_tph_total_wil = $jjg_total_wil == 0 && $tph_total_wil == 0 ? 0 :
                                        round($jjg_total_wil / $tph_total_wil, 2);

                                        $tot_krg_wil = $blok_mb == 0 && $alas_mb == 0 ? 0 : round($blok_mb / $alas_mb,
                                        2);
                                        $tot_Permentah_wil = $tot_jjg_wil == 0 && $tot_abr_wil == 0 ? 0 :
                                        round(($tot_mentah_wil / ($tot_jjg_wil - $tot_abr_wil)) *
                                        100, 2);
                                        $tot_Permatang_wil = $tot_jjg_wil == 0 && $tot_abr_wil == 0 ? 0
                                        : round(($tot_matang_wil / ($tot_jjg_wil - $tot_abr_wil)) *
                                        100, 2);
                                        $tot_Perover_wil = $tot_jjg_wil == 0 && $tot_abr_wil == 0 ? 0
                                        : round(($tot_over_wil / ($tot_jjg_wil - $tot_abr_wil)) *
                                        100, 2);
                                        $tot_Perjangkos_wil = $tot_jjg_wil == 0 && $tot_abr_wil == 0 ? 0
                                        : round(($tot_empty_wil / ($tot_jjg_wil - $tot_abr_wil)) *
                                        100, 2);
                                        $tot_Pervcut_wil = $tot_vcut_wil == 0 && $tot_jjg_wil == 0 ? 0
                                        : round(($tot_vcut_wil / $tot_jjg_wil) *
                                        100, 2);
                                        $tot_Perabr_wil = $tot_abr_wil == 0 && $tot_jjg_wil == 0 ? 0
                                        : round(($tot_abr_wil / $tot_jjg_wil) *
                                        100, 2);
                                        $tot_Perkrg_wil = $blok_mb == 0 && $alas_mb == 0 ? 0 : round(($blok_mb /
                                        $alas_mb) *
                                        100, 2);
                                        @endphp
                                        @endif
                                        @endforeach
                                        <tr>
                                            <td style="background-color : yellow; color: #000000;" colspan="2">
                                                WIL-{{$key3}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{ $tph_total_wil }}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{ $bt_total_wil }}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $bt_tph_total_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                skor_brd_tinggal($bt_tph_total_wil) }}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{ $jjg_total_wil }}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $jjg_tph_total_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                skor_buah_tinggal($jjg_tph_total_wil) }}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                skor_brd_tinggal($bt_tph_total_wil)+skor_buah_tinggal($jjg_tph_total_wil)}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_jjg_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_mentah_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_Permentah_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">
                                                {{skor_buah_mentah_mb($tot_Permentah_wil)}}</td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_matang_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_Permatang_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">
                                                {{skor_buah_masak_mb($tot_Permatang_wil)}}</td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_over_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_Perover_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">
                                                {{skor_buah_over_mb($tot_Perover_wil)}}</td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_empty_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_Perjangkos_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">
                                                {{skor_jangkos_mb($tot_Perjangkos_wil)}}</td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_vcut_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_Pervcut_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">
                                                {{skor_buah_over_mb($tot_Pervcut_wil)}}</td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_abr_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_Perabr_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_krg_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">{{
                                                $tot_Perkrg_wil}}
                                            </td>
                                            <td style="background-color : yellow; color: #000000;">
                                                {{skor_abr_mb($tot_Perkrg_wil)}}</td>
                                            <td style="background-color : yellow; color: #000000;">
                                                {{skor_buah_mentah_mb($tot_Permentah_wil) +
                                                skor_buah_masak_mb($tot_Permatang_wil) +
                                                skor_buah_over_mb($tot_Perover_wil) +
                                                skor_jangkos_mb($tot_Perjangkos_wil) +
                                                skor_buah_over_mb($tot_Pervcut_wil) + skor_abr_mb($tot_Perkrg_wil)}}
                                            </td>
                                        </tr>
                                        @if ($key3 === array_key_last($dataSkor))
                                        @else
                                        <tr style="border: none;">
                                            <td colspan="32"></td>
                                        </tr>
                                        @endif
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
                                        @foreach ($dataResFind as $key => $value)
                                        @foreach ($dataResFind[$key] as $key1 => $value1)
                                        <tr>
                                            <td class="align-middle">{{$key1}}</td>
                                            <td class="align-middle">{{ $value1['total_temuan'] }}</td>
                                            <td class="align-middle">{{ $value1['tuntas'] }}</td>
                                            <td class="align-middle">{{ count_percent($value1['tuntas'],
                                                $value1['total_temuan']) }}</td>
                                            <td class="align-middle">{{ $value1['no_tuntas'] }}</td>
                                            <td class="align-middle">{{ count_percent($value1['no_tuntas'],
                                                $value1['total_temuan']) }}</td>
                                            <td class="align-middle">
                                                <a href="/cetakPDFFI/{{$key1}}" class="btn btn-primary"
                                                    target="_blank"><i class="nav-icon fa fa-download"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endforeach
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
    // let skor1 = 1;
    // let skor2 = 15;
    // console.log(parseFloat(((skor1/skor2)*100).toFixed(2)))

    $(document).ready(function () {
        // $('#tbData').DataTable({
        //     "bPaginate": true
        // });
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