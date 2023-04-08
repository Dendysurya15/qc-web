@include('layout/header')
<style>

</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Add these dependencies in your HTML head -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>




<div class="content-wrapper">
    <section class="content"><br>
        <div class="container-fluid">
            <div class="card table_wrapper">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-utama-tab" data-toggle="tab" href="#nav-utama" role="tab" aria-controls="nav-utama" aria-selected="true">Halaman Utama</a>
                        <a class="nav-item nav-link" id="nav-data-tab" data-toggle="tab" href="#nav-data" role="tab" aria-controls="nav-data" aria-selected="false">Data</a>

                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-utama" role="tabpanel" aria-labelledby="nav-utama-tab">
                        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                            <h5><b>REKAPITULASI RANKING NILAI SIDAK PEMERIKSAAN MUTU BUAH
                                </b></h5>
                        </div>


                        <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">
                            <div class="col-2">
                                {{csrf_field()}}
                                <select class="form-control" id="regionalPanen">
                                    <option value="1" selected>Regional 1</option>
                                    <option value="2">Regional 2</option>
                                    <option value="3">Regional 3</option>
                                </select>
                            </div>
                            <div class="col-2">

                                {{ csrf_field() }}
                                <input type="hidden" id="startWeek" name="start" value="">
                                <input type="hidden" id="lastWeek" name="last" value="">
                                <input type="week" name="dateWeek" id="dateWeek" value="{{ date('Y').'-W'.date('W') }}" aria-describedby="dateWeekHelp">
                                <small id="dateWeekHelp" class="form-text text-muted">Pilih</small>
                            </div>
                            <button class="btn btn-primary mb-3" style="float: right" id="btnShow">Show</button>

                        </div>

                        <style>
                            .tabContainer {
                                position: relative;
                                overflow-x: scroll;
                                overflow-y: hidden;
                                white-space: nowrap;
                            }

                            .blur {
                                filter: blur(4px);
                                opacity: 0.5;
                            }

                            .big-table {
                                width: 100% !important;
                                position: absolute !important;
                                left: 0 !important;
                                z-index: 10;
                                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
                            }

                            .tabContainer .col-sm-3:not(.blur) {
                                cursor: pointer;
                            }

                            .mode-active {
                                background-color: #007bff !important;
                                border-color: #007bff !important;
                            }

                            .mode-options {
                                position: absolute;
                                background-color: white;
                                border: 1px solid #ccc;
                                border-radius: 3px;
                                padding: 5px;
                                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                z-index: 1000;
                            }
                        </style>
                        <!-- untuk icon full and single mode  -->
                        <!-- <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3">
                            <button class="btn btn-secondary" id="eyeButton">
                                <i class="fa fa-eye"></i>
                            </button>
                            <div class="mode-options" id="modeOptions" style="display: none;">
                                <button class="btn btn-sm btn-secondary ms-1" id="singleModeBtn"><i class="fa fa-arrows-h"></i> Single Mode</button>
                                <button class="btn btn-sm btn-secondary mode-active" id="allModeBtn"><i class="fa fa-th-large"></i> All Mode</button>
                            </div>

                        </div>
                        <div id="instructions" style="display: none; text-align: center;">Click table untuk single mode dan click table untuk memilih tabel lain.</div> -->

                        <!-- /// -->

                        <div id="tablesContainer">
                            <div class="tabContainer">
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
                                                <tbody id="week1">
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
                                                <tbody id="week2">

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
                                                <tbody id="week3">

                                                </tbody>
                                            </table>
                                        </div>
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
                                                <tbody id="plasma1">

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="col-sm-12">
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

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p style="font-size: 15px; text-align: center;"><b><u>MATANG (%)</u></b></p>
                                        <div id="matang"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p style="font-size: 15px; text-align: center;"><b><u>MENTAH (%)</u></b></p>
                                        <div id="mentah"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p style="font-size: 15px; text-align: center;"><b><u>LEWAT MATANG (%)</u></b></p>
                                        <div id="lewatmatang"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p style="font-size: 15px; text-align: center;"><b><u>JANGKOS (%)</u></b></p>
                                        <div id="jangkos"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p style="font-size: 15px; text-align: center;"><b><u>TIDAK STANDAR V-CUT (%)</u></b></p>
                                        <div id="tidakvcut"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p style="font-size: 15px; text-align: center;"><b><u>PENGGUNAAN KARUNG BRONDOLAN (%)</u></b></p>
                                        <div id="karungbrondolan"></div>
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
                            <div class="d-flex justify-content-center mb-2 ml-3 mr-3 border border-dark">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tabTable" role="tab">Halaman Utama</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabGraphs" role="tab">Data</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabFinding" role="tab">Finding Issue</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content ml-3 mr-3">
                                <!-- tab tabel -->
                                <div class="tab-pane active" id="tabTable" role="tabpanel">
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
                                            {{csrf_field()}}
                                            <select class="form-control" id="yearDate" name="year">
                                                <option value="2023" selected>2023</option>
                                                <option value="2022">2022</option>
                                                <option value="2024">2024</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="ml-4 mr-4">
                                        <div class=" row text-center">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sticky-header" style="font-size: 13px">
                                                    <thead class="sticky-header">
                                                        <tr>
                                                            <!-- thead for estate -->
                                                            @foreach ($arrHeader as $key => $item)
                                                            @if ($key == 0)
                                                            <th class="first-col">{{ $item }}</th>
                                                            @elseif ($key == 1)
                                                            <th class="second-col">{{ $item }}</th>
                                                            @else
                                                            <th>{{ $item }}</th>
                                                            @endif
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
                                                            <!-- thead for wilayah -->
                                                            @foreach ($arrHeaderSc as $key => $item)
                                                            @if ($key == 0)
                                                            <th colspan="3">{{ $item }}</th>
                                                            @elseif ($key == 1)
                                                            <th>{{ $item }}</th>
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
                                                            <!-- thead for regional -->
                                                            @foreach ($arrHeaderReg as $key => $item)
                                                            @if ($key == 0)
                                                            <th colspan="3">{{ $item }}</th>
                                                            @elseif ($key == 1)
                                                            <th>{{ $item }}</th>
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
                                                        <!-- thead for afdeling -->
                                                        @foreach ($arrHeaderTrd as $key => $item)
                                                        @if ($key == 0)
                                                        <th>{{ $item }}</th>
                                                        @elseif ($key == 1)
                                                        <th class="first-col">{{ $item }}</th>
                                                        @elseif ($key == 2)
                                                        <th class="second-col">{{ $item }}</th>
                                                        @else
                                                        <th>{{ $item }}</th>
                                                        @endif
                                                        @endforeach
                                                        <th id="th_years">2023</th>

                                                    </thead>
                                                    <tbody id="rekapAFD">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>MATANG (%)</u></b></p>
                                                    <div id="matangthun"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>MENTAH (%)</u></b></p>
                                                    <div id="mentahtahun"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>LEWAT MATANG (%)</u></b></p>
                                                    <div id="lewatmatangtahun"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>JANGKOS (%)</u></b></p>
                                                    <div id="jangkostahun"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>TIDAK STANDAR V-CUT (%)</u></b></p>
                                                    <div id="tidakvcuttahun"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>PENGGUNAAN KARUNG BRONDOLAN (%)</u></b></p>
                                                    <div id="karungbrondolantahun"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- tab Data pertahun -->

                                <div class="tab-pane" id="tabGraphs" role="tabpanel">
                                    <div class="d-flex flex-row-reverse mr-3">
                                        <button class="btn btn-primary mb-3" style="float: right" id="showDataIns">Show</button>
                                        <div class="col-2 mr-2" style="float: right">
                                            {{csrf_field()}}
                                            <select class="form-control" id="regDataTahun">
                                                <option value="1" selected>Regional 1</option>
                                                <option value="2">Regional 2</option>
                                                <option value="3">Regional 3</option>
                                            </select>
                                        </div>
                                        <div class="col-2" style="float: right">
                                            {{csrf_field()}}
                                            <select class="form-control" id="yearData" name="yearData">
                                                <option value="2023" selected>2023</option>
                                                <option value="2022">2022</option>
                                                <option value="2024">2024</option>
                                            </select>
                                        </div>
                                    </div>
                                    <style>
                                        .my-table {
                                            width: 100%;
                                            border-collapse: collapse;
                                        }

                                        .my-table th,
                                        .my-table td {
                                            text-align: center;
                                            padding: 8px;
                                            border: 1px solid black;
                                            white-space: nowrap;
                                        }

                                        .my-table thead th {
                                            font-weight: bold;
                                            position: sticky;
                                            top: 0;
                                            z-index: 100;
                                        }

                                        .title-row {
                                            font-size: 1.5em;
                                            text-align: center;
                                            margin-bottom: 10px;
                                        }

                                        .table-wrapper {
                                            width: 100%;
                                            overflow-x: scroll;
                                        }
                                    </style>
                                    <div class="title-row">
                                        <!-- <h2>Fruit Quality Evaluation</h2> -->
                                    </div>
                                    <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                                        <div class="table-wrapper">
                                            <table class="my-table">

                                                <thead>
                                                    <tr>
                                                        <th rowspan="4" style="background-color: #883c0c;">No</th>
                                                        <th rowspan="4" style="background-color: #883c0c;">Reg.</th>
                                                        <th rowspan="4" style="background-color: #883c0c;">PT.</th>
                                                        <th rowspan="4" rowspan="2" style="background-color: #883c0c;">Est.</th>
                                                        <th rowspan="4" rowspan="2" style="background-color: #883c0c;">Afd.</th>
                                                        <th rowspan="4" rowspan="2" style="background-color: #883c0c;">Nama Staff</th>
                                                        <th colspan="27" style="background-color: #ffc404;">Mutu Buah</th>
                                                        <th rowspan="4" style="background-color: #a8a4a4;" rowspan="2">AlL Skor.</th>
                                                        <th rowspan="4" style="background-color: #a8a4a4;" rowspan="2">Katagori</th>
                                                    </tr>
                                                    <tr>
                                                        <th rowspan="3" style="background-color: #ffc404; white-space: nowrap;">Total Janjang Sample</th>

                                                        <th colspan="7" style="background-color: #ffc404;">Mentah</th>
                                                        <th colspan="3" rowspan="2" style="background-color: #ffc404;">Matang</th>
                                                        <th colspan="3" rowspan="2" style="background-color: #ffc404;">Lewat Matang (O)</th>
                                                        <th colspan="3" rowspan="2" style="background-color: #ffc404;">Janjang Kosong (E)</th>
                                                        <th colspan="3" rowspan="2" style="background-color: #ffc404;">Tangkai Panjang (TP)</th>
                                                        <th colspan="2" rowspan="2" style="background-color: #ffc404;">Abnormal</th>
                                                        <th colspan="2" rowspan="2" style="background-color: #ffc404;">Rat Damage</th>
                                                        <th colspan="3" rowspan="2" style="background-color: #ffc404;">Penggunaan Karung Brondolan</th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="2" style="background-color: #ffc404;">Tanpa Brondol</th>
                                                        <th colspan="2" style="background-color: #ffc404;">Kurang Brondol</th>
                                                        <th colspan="3" style="background-color: #ffc404;">Total</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="background-color: #ffc404;">Jjg</th>
                                                        <th style="background-color: #ffc404;">%</th>
                                                        <th style="background-color: #ffc404;">Jjg</th>
                                                        <th style="background-color: #ffc404;">%</th>
                                                        <th style="background-color: #ffc404;">Jjg</th>
                                                        <th style="background-color: #ffc404;">%</th>
                                                        <th style="background-color: #ffc404;">Total</th>
                                                        <th style="background-color: #ffc404;">Jjg</th>
                                                        <th style="background-color: #ffc404;">%</th>
                                                        <th style="background-color: #ffc404;">Total</th>
                                                        <th style="background-color: #ffc404;">Jjg</th>
                                                        <th style="background-color: #ffc404;">%</th>
                                                        <th style="background-color: #ffc404;">Total</th>
                                                        <th style="background-color: #ffc404;">Jjg</th>
                                                        <th style="background-color: #ffc404;">%</th>
                                                        <th style="background-color: #ffc404;">Total</th>
                                                        <th style="background-color: #ffc404;">Jjg</th>
                                                        <th style="background-color: #ffc404;">%</th>
                                                        <th style="background-color: #ffc404;">Total</th>
                                                        <th style="background-color: #ffc404;">Jjg</th>
                                                        <th style="background-color: #ffc404;">%</th>
                                                        <th style="background-color: #ffc404;">Jjg</th>
                                                        <th style="background-color: #ffc404;">%</th>
                                                        <th style="background-color: #ffc404;">TPH</th>
                                                        <th style="background-color: #ffc404;">%</th>
                                                        <th style="background-color: #ffc404;">Skor</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data_tahunTab">
                                                    <!-- <tr>
                                                        <td>1</td>
                                                        <td> REG 1
                                                        </td>
                                                        <td> SSS
                                                        </td>
                                                        <td> SLE
                                                        </td>
                                                        <td> OB
                                                        </td>
                                                        <td> Ahmad Ari Nugroho
                                                        </td>
                                                    </tr> -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                </div>

                                <div class="tab-pane" id="tabFinding" role="tabpanel">
                                    <div class="d-flex flex-row-reverse mr-3">
                                        <button class="btn btn-primary mb-3" style="float: right" id="showFindingYear">Show</button>
                                        <div class="col-2 mr-2" style="float: right">
                                            {{csrf_field()}}
                                            <select class="form-control" id="regFindingYear">
                                                <option value="1" selected>Regional 1</option>
                                                <option value="2">Regional 2</option>
                                                <option value="3">Regional 3</option>
                                            </select>
                                        </div>
                                        <div class="col-2" style="float: right">
                                            {{csrf_field()}}
                                            <select class="form-control" id="yearFinding" name="yearFinding">
                                                <option value="2023" selected>2023</option>
                                                <option value="2022">2022</option>
                                                <option value="2024">2024</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                                        <p style="text-align: center;">MAIN ISSUE FOTO TEMUAN SIDAK PEMERIKSAAN MUTU BUAH DI TPH
                                        </p>
                                    </div>
                                    <style>
                                        .image-table {
                                            width: 100%;
                                            table-layout: fixed;
                                            border-collapse: collapse;
                                        }

                                        .image-table td {
                                            padding: 0;
                                            position: relative;
                                        }

                                        .image-table img {
                                            width: 100%;
                                            height: auto;
                                            display: block;
                                        }

                                        .image-title {
                                            text-align: center;
                                            font-weight: bold;
                                            margin: 0;
                                        }

                                        .image-description {
                                            text-align: center;
                                            margin: 0;
                                        }
                                    </style>
                                    <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                                        <table class="border border-dark image-table">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="background-color: grey; text-align: center;">FOTO</th>
                                                </tr>
                                            </thead>
                                            <!-- <tbody id="findingYears"> -->
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <img src="https://res.cloudinary.com/teepublic/image/private/s--l2N30sYN--/t_Preview/t_watermark_lock/b_rgb:191919,c_lpad,f_jpg,h_630,q_90,w_1200/v1611259756/production/designs/18733540_0.jpg" alt="Image 1">
                                                        <p class="image-title">Image 1 Title</p>
                                                        <p class="image-description">Image 1 Description</p>
                                                    </td>
                                                    <td>
                                                        <img src="https://res.cloudinary.com/teepublic/image/private/s--l2N30sYN--/t_Preview/t_watermark_lock/b_rgb:191919,c_lpad,f_jpg,h_630,q_90,w_1200/v1611259756/production/designs/18733540_0.jpg" alt="Image 2">
                                                        <p class="image-title">Image 2 Title</p>
                                                        <p class="image-description">Image 2 Description</p>
                                                    </td>
                                                    <td>
                                                        <img src="https://res.cloudinary.com/teepublic/image/private/s--l2N30sYN--/t_Preview/t_watermark_lock/b_rgb:191919,c_lpad,f_jpg,h_630,q_90,w_1200/v1611259756/production/designs/18733540_0.jpg" alt="Image 3">
                                                        <p class="image-title">Image 3 Title</p>
                                                        <p class="image-description">Image 3 Description</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <img src="https://assets.justinmind.com/wp-content/uploads/2018/11/Lorem-Ipsum-alternatives.png" alt="Image 1">
                                                        <p class="image-title">Image 1 Title</p>
                                                        <p class="image-description">Image 1 Description</p>
                                                    </td>
                                                    <td>
                                                        <img src="https://assets.justinmind.com/wp-content/uploads/2018/11/Lorem-Ipsum-alternatives.png" alt="Image 2">
                                                        <p class="image-title">Image 2 Title</p>
                                                        <p class="image-description">Image 2 Description</p>
                                                    </td>
                                                    <td>
                                                        <img src="https://assets.justinmind.com/wp-content/uploads/2018/11/Lorem-Ipsum-alternatives.png" alt="Image 3">
                                                        <p class="image-title">Image 3 Title</p>
                                                        <p class="image-description">Image 3 Description</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" tab-pane fade" id="nav-data" role="tabpanel" aria-labelledby="nav-data-tab">
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

                                    <tbody id="datatahun">
                                        <!-- <td>PLE</td>
                                        <td>OG</td> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
</div>
@include('layout/footer')
<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


<script>
    // untuk buat table data bisa d scroll dengan mouse
    document.addEventListener("DOMContentLoaded", function() {
        const tableWrapper = document.querySelector(".table-wrapper");
        let isMouseDown = false;
        let startX, scrollLeft;

        tableWrapper.addEventListener("mousedown", (e) => {
            isMouseDown = true;
            startX = e.pageX - tableWrapper.offsetLeft;
            scrollLeft = tableWrapper.scrollLeft;
            tableWrapper.style.cursor = "grabbing";
        });

        tableWrapper.addEventListener("mouseleave", () => {
            isMouseDown = false;
            tableWrapper.style.cursor = "default";
        });

        tableWrapper.addEventListener("mouseup", () => {
            isMouseDown = false;
            tableWrapper.style.cursor = "default";
        });

        tableWrapper.addEventListener("mousemove", (e) => {
            if (!isMouseDown) return;
            e.preventDefault();
            const x = e.pageX - tableWrapper.offsetLeft;
            const walk = (x - startX) * 2;
            tableWrapper.scrollLeft = scrollLeft - walk;
        });
    });

    ////untuk mode single and full mode


    var currentMode = 'all';

    $(document).ready(function() {


        //untuk effect icon eyes terbuka di area

        //untuk table etc
        getweek()
        dashboard_tahun()
        dashboardData_tahun()
        dashboardFindingYear()



    });







    // $('#regionalPanen').on('change', function() {
    // getweek();
    // });


    //fungis table ke tengah berdaraskan filter
    const c = document.getElementById('btnShow');
    const o = document.getElementById('regionalPanen');
    const s = document.getElementById("Tab1");
    const m = document.getElementById("Tab2");
    const l = document.getElementById("Tab3");
    const p = document.getElementById("Tab4");

    c.addEventListener('click', function() {
        const c = o.value;
        // Reset classes and visibility
        s.classList.remove("col-sm-3", "col-sm-4", "col-sm-6");
        m.classList.remove("col-sm-3", "col-sm-4", "col-sm-6");
        l.classList.remove("col-sm-3", "col-sm-4", "col-sm-6");
        p.classList.remove("col-sm-3", "col-sm-4", "col-sm-6");
        s.style.display = "block";
        m.style.display = "block";
        l.style.display = "block";
        p.style.display = "block";

        if (c === '1') {
            s.classList.add("col-sm-3");
            m.classList.add("col-sm-3");
            l.classList.add("col-sm-3");
            p.classList.add("col-sm-3");
            document.getElementById("thead1").innerText = "WILAYAH I";
            document.getElementById("thead2").innerText = "WILAYAH II";
            document.getElementById("thead3").innerText = "WILAYAH III";
            document.getElementById("plhead").innerText = "Plasma";
        } else if (c === '2') {
            s.classList.add("col-sm-4");
            m.classList.add("col-sm-4");
            l.classList.add("col-sm-4");
            p.style.display = "none";
            document.getElementById("thead1").innerText = "WIL IV";
            document.getElementById("thead2").innerText = "WIL V";
            document.getElementById("thead3").innerText = "WIL VI";
        } else if (c === '3') {
            s.classList.add("col-sm-6");
            m.classList.add("col-sm-6");
            l.style.display = "none";
            p.style.display = "none";
            document.getElementById("thead1").innerText = "WIL VII";
            document.getElementById("thead2").innerText = "WIL VIII";
        }
    });

    //tampilakn filter perweek
    document.getElementById('btnShow').onclick = function() {
        getweek();
    }

    var options = {
        chart: {
            type: 'line'
        },
        series: [{
            name: 'sales',
            data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
        }],
        xaxis: {
            categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999]
        }
    }

    var options1 = {
        series: [{
            name: 'Website Blog',
            type: 'column',
            data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
        }, {
            name: 'Social Media',
            type: 'line',
            data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
        }],
        chart: {
            height: 350,
            type: 'line',
        },
        stroke: {
            width: [0, 4]
        },
        title: {
            text: 'Traffic Sources'
        },
        dataLabels: {
            enabled: true,
            enabledOnSeries: [1]
        },
        labels: ['01 Jan 2001', '02 Jan 2001', '03 Jan 2001', '04 Jan 2001', '05 Jan 2001', '06 Jan 2001', '07 Jan 2001', '08 Jan 2001', '09 Jan 2001', '10 Jan 2001', '11 Jan 2001', '12 Jan 2001'],
        xaxis: {
            type: 'datetime'
        },
        yaxis: [{
            title: {
                text: 'Website Blog',
            },

        }, {
            opposite: true,
            title: {
                text: 'Social Media'
            }
        }]
    };




    var chart = new ApexCharts(document.querySelector("#matang"), options);
    var chartx = new ApexCharts(document.querySelector("#mentah"), options);
    var charts = new ApexCharts(document.querySelector("#lewatmatang"), options1);
    var chartc = new ApexCharts(document.querySelector("#jangkos"), options1);
    var chartv = new ApexCharts(document.querySelector("#tidakvcut"), options1);
    var chartb = new ApexCharts(document.querySelector("#karungbrondolan"), options1);

    chart.render();
    chartx.render();
    charts.render();
    chartc.render();
    chartv.render();
    chartb.render();

    function getweek() {
        $('#week1').empty()
        $('#week2').empty()
        $('#week3').empty()
        $('#plasma1').empty()


        var reg = '';

        var dateWeek = '';
        var reg = document.getElementById('regionalPanen').value;

        var dateWeek = document.getElementById('dateWeek').value;
        var _token = $('input[name="_token"]').val();

        $.ajax({
            url: "{{ route('getWeek') }}",
            method: "GET",
            data: {
                reg: reg,
                dateWeek: dateWeek,
                _token: _token
            },
            headers: {
                'X-CSRF-TOKEN': _token
            },
            success: function(result) {


                var parseResult = JSON.parse(result)
                var region = Object.entries(parseResult['listregion'])

                var mutu_buah = Object.entries(parseResult['mutu_buah'])
                var mutubuah_est = Object.entries(parseResult['mutubuah_est'])
                var mutuBuah_wil = Object.entries(parseResult['mutuBuah_wil'])

                console.log(mutuBuah_wil);

                regInpt = reg;

                function createTableCell(text, customClass = null) {
                    const cell = document.createElement('td');
                    cell.innerText = text;
                    if (customClass) {
                        cell.classList.add(customClass);
                    }
                    return cell;
                }

                function setBackgroundColor(element, score) {
                    let color;
                    if (score >= 95) {
                        color = "#609cd4";
                    } else if (score >= 85 && score < 95) {
                        color = "#08b454";
                    } else if (score >= 75 && score < 85) {
                        color = "#fffc04";
                    } else if (score >= 65 && score < 75) {
                        color = "#ffc404";
                    } else {
                        color = "red";
                    }

                    element.style.backgroundColor = color;
                    element.style.color = (color === "#609cd4" || color === "#08b454" || color === "red") ? "white" : "black";
                }

                function bgest(element, score) {
                    let color;
                    if (score >= 95) {
                        color = "#609cd4";
                    } else if (score >= 85 && score < 95) {
                        color = "#08b454";
                    } else if (score >= 75 && score < 85) {
                        color = "#fffc04";
                    } else if (score >= 65 && score < 75) {
                        color = "#ffc404";
                    } else {
                        color = "red";
                    }

                    element.style.backgroundColor = color;
                    element.style.color = (color === "#609cd4" || color === "#08b454" || color === "red") ? "white" : "black";
                }



                var arrTbody1 = mutu_buah[0][1];

                var tbody1 = document.getElementById('week1');

                Object.entries(arrTbody1).forEach(([estateName, estateData]) => {
                    Object.entries(estateData).forEach(([key2, data], index) => {
                        const tr = document.createElement('tr');

                        const dataItems = {
                            item1: estateName,
                            item2: key2,
                            item3: data['nama_asisten'],
                            item4: data['All_skor'],
                            item5: data['rankAFD'],
                        };

                        const rowData = Object.values(dataItems);

                        rowData.forEach((item, cellIndex) => {
                            const cell = createTableCell(item, "text-center");

                            if (cellIndex === 3) {
                                setBackgroundColor(cell, item);
                            }

                            tr.appendChild(cell);
                        });

                        tbody1.appendChild(tr);
                    });
                });
                var arrEst1 = mutubuah_est[0][1];
                // console.log(arrEst1);
                var tbody1 = document.getElementById('week1');

                Object.entries(arrEst1).forEach(([estateName, estateData]) => {
                    const tr = document.createElement('tr');
                    // console.log(estateData);
                    const dataItems = {
                        item1: estateName,
                        item2: estateData['EM'],
                        item3: estateData['Nama_assist'] || '-',
                        item4: estateData['All_skor'],
                        item5: estateData['rankEST'],
                    };

                    const rowData = Object.values(dataItems);

                    rowData.forEach((item, cellIndex) => {
                        const cell = createTableCell(item, "text-center");
                        if (cellIndex <= 2) {
                            cell.style.backgroundColor = "#e8ecdc";
                            cell.style.color = "black";
                        } else if (cellIndex === 3) {
                            bgest(cell, item);
                        }


                        tr.appendChild(cell);
                    });

                    tbody1.appendChild(tr);

                });
                if (regInpt === '1') {
                    wil1 = 'WIL-I';
                    wil2 = 'WIL-II';
                    wil3 = 'WIL-III';
                    wil4 = 'Plasma1'
                } else if (regInpt === '2') {
                    wil1 = 'WIL-IV';
                    wil2 = 'WIL-V';
                    wil3 = 'WIL-VI';
                    wil4 = '-'
                } else {
                    wil1 = 'WIL-VII';
                    wil2 = 'WIL-VIII';
                    wil3 = '-';
                    wil4 = '-'
                }
                var arrEst1 = mutuBuah_wil[0][1];
                // console.log(arrEst1);
                var tbody1 = document.getElementById('week1');
                const tr = document.createElement('tr');
                // console.log(estateData);
                const dataItems = {

                    item1: wil1,
                    item2: 'GM',
                    item3: '-',
                    item4: arrEst1['All_skor'],
                    item5: arrEst1['rankWil'],
                };

                const rowData = Object.values(dataItems);

                rowData.forEach((item, cellIndex) => {
                    const cell = createTableCell(item, "text-center");
                    if (cellIndex <= 2) {
                        cell.style.backgroundColor = "#e8ecdc";
                        cell.style.color = "black";
                    } else if (cellIndex === 3) {
                        bgest(cell, item);
                    }


                    tr.appendChild(cell);
                });

                tbody1.appendChild(tr);



                var tab2 = mutu_buah[1][1];
                var tbody2 = document.getElementById('week2');
                // console.log(tab2);
                Object.entries(tab2).forEach(([estateName, estateData]) => {
                    Object.entries(estateData).forEach(([key2, data], index) => {
                        const tr = document.createElement('tr');

                        const dataItems = {
                            item1: estateName,
                            item2: key2,
                            item3: data['nama_asisten'],
                            item4: data['All_skor'],
                            item5: data['rankAFD'],
                        };

                        const rowData = Object.values(dataItems);

                        rowData.forEach((item, cellIndex) => {
                            const cell = createTableCell(item, "text-center");

                            if (cellIndex === 3) {
                                setBackgroundColor(cell, item);
                            }

                            tr.appendChild(cell);
                        });

                        tbody2.appendChild(tr);
                    });
                });

                var arrEst2 = mutubuah_est[1][1];
                // console.log(arrEst2);
                var tbody2 = document.getElementById('week2');

                Object.entries(arrEst2).forEach(([estateName, estateData]) => {
                    const tr = document.createElement('tr');
                    // console.log(estateData);
                    const dataItems = {
                        item1: estateName,
                        item2: estateData['EM'],
                        item3: estateData['Nama_assist'],
                        item4: estateData['All_skor'],
                        item5: estateData['rankEST'],
                    };

                    const rowData = Object.values(dataItems);

                    rowData.forEach((item, cellIndex) => {
                        const cell = createTableCell(item, "text-center");
                        if (cellIndex <= 2) {
                            cell.style.backgroundColor = "#e8ecdc";
                            cell.style.color = "black";
                        } else if (cellIndex === 3) {
                            bgest(cell, item);
                        }


                        tr.appendChild(cell);
                    });

                    tbody2.appendChild(tr);

                });

                var arrWil2 = mutuBuah_wil[1][1];
                // console.log(arrWil2);
                var tbody2 = document.getElementById('week2');
                const tx = document.createElement('tr');
                // console.log(estateData);
                const dataItemx = {

                    item1: wil2,
                    item2: 'GM',
                    item3: '-',
                    item4: arrWil2['All_skor'],
                    item5: arrWil2['rankWil'],
                };

                const rowDatax = Object.values(dataItemx);

                rowDatax.forEach((item, cellIndex) => {
                    const cell = createTableCell(item, "text-center");
                    if (cellIndex <= 2) {
                        cell.style.backgroundColor = "#e8ecdc";
                        cell.style.color = "black";
                    } else if (cellIndex === 3) {
                        bgest(cell, item);
                    }


                    tx.appendChild(cell);
                });

                tbody2.appendChild(tx);

                var tab3 = mutu_buah[2][1];
                var tbody3 = document.getElementById('week3');
                // console.log(tab3);
                Object.entries(tab3).forEach(([estateName, estateData]) => {
                    Object.entries(estateData).forEach(([key2, data], index) => {
                        const tr = document.createElement('tr');

                        const dataItems = {
                            item1: estateName,
                            item2: key2,
                            item3: data['nama_asisten'],
                            item4: data['All_skor'],
                            item5: data['rankAFD'],
                        };

                        const rowData = Object.values(dataItems);

                        rowData.forEach((item, cellIndex) => {
                            const cell = createTableCell(item, "text-center");

                            if (cellIndex === 3) {
                                setBackgroundColor(cell, item);
                            }

                            tr.appendChild(cell);
                        });

                        tbody3.appendChild(tr);
                    });
                });
                var arrEst3 = mutubuah_est[2][1];
                // console.log(arrEst3);
                var tbody3 = document.getElementById('week3');

                Object.entries(arrEst3).forEach(([estateName, estateData]) => {
                    const tr = document.createElement('tr');
                    // console.log(estateData);
                    const dataItems = {
                        item1: estateName,
                        item2: estateData['EM'],
                        item3: estateData['Nama_assist'],
                        item4: estateData['All_skor'],
                        item5: estateData['rankEST'],
                    };

                    const rowData = Object.values(dataItems);

                    rowData.forEach((item, cellIndex) => {
                        const cell = createTableCell(item, "text-center");
                        if (cellIndex <= 2) {
                            cell.style.backgroundColor = "#e8ecdc";
                            cell.style.color = "black";
                        } else if (cellIndex === 3) {
                            bgest(cell, item);
                        }


                        tr.appendChild(cell);
                    });

                    tbody3.appendChild(tr);

                });
                var arrWIl3 = mutuBuah_wil[2][1];
                // console.log(arrWIl3);
                var tbody3 = document.getElementById('week3');
                const tm = document.createElement('tr');
                // console.log(estateData);
                const dataitemc = {

                    item1: wil3,
                    item2: 'GM',
                    item3: '-',
                    item4: arrWIl3['All_skor'],
                    item5: arrWIl3['rankWil'],
                };

                const rowDatac = Object.values(dataitemc);

                rowDatac.forEach((item, cellIndex) => {
                    const cell = createTableCell(item, "text-center");
                    if (cellIndex <= 2) {
                        cell.style.backgroundColor = "#e8ecdc";
                        cell.style.color = "black";
                    } else if (cellIndex === 3) {
                        bgest(cell, item);
                    }


                    tm.appendChild(cell);
                });

                tbody3.appendChild(tm);




                var arrTbody1 = mutu_buah[0]?.[1] || [];

                if (mutu_buah[3]) {
                    var tab4 = mutu_buah[3][1];
                }
                // var tab4 = mutu_buah[3][1];
                var tbody4 = document.getElementById('plasma1');
                // console.log(tab4);
                Object.entries(tab4).forEach(([estateName, estateData]) => {
                    Object.entries(estateData).forEach(([key2, data], index) => {
                        const tr = document.createElement('tr');

                        const dataItems = {
                            item1: estateName,
                            item2: key2,
                            item3: data['nama_asisten'],
                            item4: data['All_skor'],
                            item5: data['rankAFD'],
                        };

                        const rowData = Object.values(dataItems);

                        rowData.forEach((item, cellIndex) => {
                            const cell = createTableCell(item, "text-center");

                            if (cellIndex === 3) {
                                setBackgroundColor(cell, item);
                            }

                            tr.appendChild(cell);
                        });

                        tbody4.appendChild(tr);
                    });
                });
                var arrEst4 = mutubuah_est[3][1];
                // console.log(arrEst4);
                var tbody4 = document.getElementById('plasma1');

                Object.entries(arrEst4).forEach(([estateName, estateData]) => {
                    const tr = document.createElement('tr');
                    // console.log(estateData);
                    const dataItems = {
                        item1: estateName,
                        item2: estateData['EM'],
                        item3: estateData['Nama_assist'],
                        item4: estateData['All_skor'],
                        item5: estateData['rankEST'],
                    };

                    const rowData = Object.values(dataItems);

                    rowData.forEach((item, cellIndex) => {
                        const cell = createTableCell(item, "text-center");
                        if (cellIndex <= 2) {
                            cell.style.backgroundColor = "#e8ecdc";
                            cell.style.color = "black";
                        } else if (cellIndex === 3) {
                            bgest(cell, item);
                        }


                        tr.appendChild(cell);
                    });

                    tbody4.appendChild(tr);

                });

                var arrWIl3 = mutuBuah_wil[3][1];
                // console.log(arrWIl3);
                var tbody4 = document.getElementById('plasma1');
                const tl = document.createElement('tr');
                // console.log(estateData);
                const dataOm = {

                    item1: wil4,
                    item2: 'GM',
                    item3: '-',
                    item4: arrWIl3['All_skor'],
                    item5: arrWIl3['rankWil'],
                };

                const rowOm = Object.values(dataOm);

                rowOm.forEach((item, cellIndex) => {
                    const cell = createTableCell(item, "text-center");
                    if (cellIndex <= 2) {
                        cell.style.backgroundColor = "#e8ecdc";
                        cell.style.color = "black";
                    } else if (cellIndex === 3) {
                        bgest(cell, item);
                    }


                    tl.appendChild(cell);
                });

                tbody4.appendChild(tl);


            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
        });
    }

    //tampilkan pertahun filter table utama
    document.getElementById('showTahung').onclick = function() {
        dashboard_tahun()
    }


    var char1 = new ApexCharts(document.querySelector("#matangthun"), options);
    var chart2 = new ApexCharts(document.querySelector("#mentahtahun"), options);

    var chart3 = new ApexCharts(document.querySelector("#lewatmatangtahun"), options1);
    var chart4 = new ApexCharts(document.querySelector("#jangkostahun"), options1);
    var chart5 = new ApexCharts(document.querySelector("#tidakvcuttahun"), options1);
    var chart6 = new ApexCharts(document.querySelector("#karungbrondolantahun"), options1);


    char1.render();
    chart2.render();
    chart3.render();
    chart4.render();
    chart5.render();
    chart6.render();

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
            url: "{{ route('getYear') }}",
            method: "GET",
            data: {
                year,
                regData,
                _token: _token
            },
            success: function(result) {

            }
        });
    }


    document.getElementById('showDataIns').onclick = function() {
        dashboardData_tahun()
    }

    function dashboardData_tahun() {
        $('#data_tahunTab').empty()


        var reg = ''
        var tahun = ''

        var _token = $('input[name="_token"]').val();
        var reg = document.getElementById('regDataTahun').value
        var tahun = document.getElementById('yearData').value


        $.ajax({
            url: "{{ route('getYearData') }}",
            method: "GET",
            data: {
                reg,
                tahun,
                _token: _token
            },
            success: function(result) {
                var parseResult = JSON.parse(result)
                var data_Sidak = Object.entries(parseResult['data_sidak'])
                // console.log(data_Sidak);


                function createTableCell(text) {
                    const cell = document.createElement('td');
                    cell.innerText = text;
                    return cell;
                }

                var arrTbody1 = data_Sidak;
                var tbody1 = document.getElementById('data_tahunTab');
                counter = 1;

                arrTbody1.forEach(element => {
                    let item4 = element[0];
                    let afdelingData = element[1];

                    Object.keys(afdelingData).forEach((key, index) => {
                        tr = document.createElement('tr');
                        let dataItems = {
                            item1: counter++,
                            item2: afdelingData[key].reg,
                            item3: afdelingData[key].pt,
                            item4: item4,
                            item5: afdelingData[key].afd,
                            item6: afdelingData[key].nama_staff,
                            item7: afdelingData[key].Jumlah_janjang,
                            item8: afdelingData[key].tnp_brd,
                            item9: afdelingData[key].persenTNP_brd,
                            item10: afdelingData[key].krg_brd,
                            item11: afdelingData[key].persenKRG_brd,
                            item12: afdelingData[key].total_jjg,
                            item13: afdelingData[key].persen_totalJjg,
                            item14: afdelingData[key].skor_total,
                            item15: afdelingData[key].jjg_matang,
                            item16: afdelingData[key].persen_jjgMtang,
                            item17: afdelingData[key].skor_jjgMatang,
                            item18: afdelingData[key].lewat_matang,
                            item19: afdelingData[key].persen_lwtMtng,
                            item20: afdelingData[key].skor_lewatMTng,
                            item21: afdelingData[key].janjang_kosong,
                            item22: afdelingData[key].persen_kosong,
                            item23: afdelingData[key].skor_kosong,
                            item24: afdelingData[key].vcut,
                            item25: afdelingData[key].vcut_persen,
                            item26: afdelingData[key].vcut_skor,
                            item27: afdelingData[key].abnormal,
                            item28: afdelingData[key].abnormal_persen,
                            item29: afdelingData[key].rat_dmg,
                            item30: afdelingData[key].rd_persen,
                            item31: afdelingData[key].TPH,
                            item32: afdelingData[key].persen_krg,
                            item33: afdelingData[key].skor_kr,
                            item34: afdelingData[key].All_skor,
                            item35: afdelingData[key].kategori,
                        };

                        const rowData = Object.values(dataItems);

                        rowData.forEach((data, cellIndex) => {
                            let cell = createTableCell(data);


                            if (index === Object.keys(afdelingData).length - 1) {
                                cell.style.backgroundColor = 'lightblue';
                            }

                            tr.appendChild(cell);
                        });

                        tbody1.appendChild(tr);
                    });


                });


            }
        });
    }
    document.getElementById('showFindingYear').onclick = function() {
        dashboardFindingYear()
    }



    function dashboardFindingYear() {
        $('#findingYears').empty()


        var reg = ''
        var tahun = ''

        var _token = $('input[name="_token"]').val();
        var reg = document.getElementById('regFindingYear').value
        var tahun = document.getElementById('yearFinding').value


        $.ajax({
            url: "{{ route('findingIsueTahun') }}",
            method: "GET",
            data: {
                reg,
                tahun,
                _token: _token
            },
            success: function(result) {

            }
        });
    }
</script>