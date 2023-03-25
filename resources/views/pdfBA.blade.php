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
        font-size: 0.7rem;
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
        align-items: center;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .logo-container {
        display: flex;
        align-items: center;
    }

    .text-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin-left: 10px;
    }

    .logo {
        height: 60px;
        width: auto;
    }

    .pt-name,
    .qc-name {
        margin: 0;
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

    .custom-table,
    .custom-table th,
    .custom-table td {
        border: 1px solid black;
        text-align: left;
        padding: 8px;
    }



    .table-1-no-border td {
        border: none;
    }
</style>

<body>
    <div class="content-wrapper">
        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
            <h2 class="text-center">BERITA ACARA REKAPITULASI PEMERIKSAAN KUALITAS PANEN QUALITY CONTROL</h2>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: middle; border: 0;">
                    <div class="logo-container">
                        <img src="{{ asset('img/logo-SSS.png') }}" alt="Logo" class="logo">
                    </div>
                </td>
                <td style="vertical-align: middle; border: 0;">
                    <div class="text-container">
                        <div class="pt-name">PT. SAWIT SUMBERMAS SARANA, TBK</div>
                        <div class="qc-name">QUALITY CONTROL</div>
                    </div>
                </td>
                <td style="width: 40%; border: 0;"></td>
                <td style="vertical-align: middle; text-align: right; border: 0;">
                    <div class="right-container">
                        <div class="text-container">
                            <div class="afd">periode pemeriksaan ke: _________________</div>
                            <div class="afd">ESTATE/ AFD: {{$data['est']}} {{$data['afd']}}</div>
                            <div class="afd">TANGGAL: {{$data['tanggal']}}</div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
            <div class="table-responsive">
                <table class="my-table">
                    <table class="my-table">
                        <thead>
                            <tr>
                                <th rowspan="4">Status Panen (H+…)</th>
                                <th colspan="8">Data Blok Sample</th>
                                <th colspan="13" rowspan="2">MUTU ANCAK (MA)</th>
                                <th colspan="5" rowspan="2">MUTU TRANSPOT (MT)</th>
                            </tr>
                            <tr>
                                <th rowspan="3">Nomor Blok</th>
                                <th rowspan="3">Luas Blok</th>
                                <th rowspan="3">SPH</th>
                                <th rowspan="3">Jum Pokok Sampel</th>
                                <th rowspan="3">Luas Sampel (Ha)</th>
                                <th rowspan="3">Persen Sampel (%)</th>
                                <th rowspan="3">Jum Janjang Panen</th>
                                <th rowspan="3">AKP Realisasi</th>

                            </tr>
                            <tr>
                                <th colspan="5">Brondolan Tinggal</th>

                                <th colspan="6">Buah Tinggal</th>
                                <th colspan="2">Palepah Sengklek</th>
                                <th rowspan="2">TPH Sample</th>
                                <th colspan="2">Brondolan Tingal</th>
                                <th colspan="2">Buah TInggal</th>

                            </tr>
                            <tr>
                                <!-- brondolan tinggal -->
                                <th>P</th>
                                <th>K</th>
                                <th>GL</th>
                                <th>Total</th>
                                <th>Butir / Jjg</th>
                                <!-- buah tinggal -->
                                <th>S</th>
                                <th>M1</th>
                                <th>M2</th>
                                <th>M3</th>
                                <th>Total</th>
                                <th>Persen (%)</th>
                                <!-- palepah -->
                                <th>Pokok</th>
                                <th> Persen (%)</th>
                                <!-- Transport -->
                                <th> Butir </th>
                                <th> Butir / TPH </th>
                                <th> Jjg </th>
                                <th> Jjg / TPH</th>
                            </tr>

                        </thead>
                        <tbody id="tab1">

                            @php
                            $mergedKeys = array_unique(array_merge(array_keys($data['mutuAncak']), array_keys($data['mutuTransport'])));
                            $rowCount = count($mergedKeys);
                            $emptyRows = 8 - $rowCount;
                            @endphp

                            @foreach ($mergedKeys as $key)
                            <tr>
                                @php
                                $totalPokokSample = 0;
                                $totalLuasHa = 0;
                                $totaljumPanen = 0;
                                $akp_real =0;
                                $total_p = 0;
                                $total_k = 0;
                                $total_gl = 0;
                                $total_brdMA =0;
                                $total_s = 0;
                                $total_m1 = 0;
                                $total_m2 = 0;
                                $total_m3 = 0;
                                $total_bh = 0;
                                $brd_jjg =0;
                                $buah_brd = 0;
                                $total_ps = 0;
                                $ps_persen=0;
                                foreach ($data['mutuAncak'] as $mutuAncak) {
                                $totalPokokSample += $mutuAncak['pokok_sample'] ?? 0;
                                $totalLuasHa += $mutuAncak['luas_ha'] ?? 0;
                                $totaljumPanen += $mutuAncak['jml_jjg_panen'] ?? 0;
                                $akp_real = count_percent($totaljumPanen, $totalPokokSample);
                                $total_p += $mutuAncak['p_ma'] ?? 0;
                                $total_k += $mutuAncak['k_ma'] ?? 0;
                                $total_gl += $mutuAncak['gl_ma'] ?? 0;
                                $total_brdMA += $mutuAncak ['total_brd_ma'] ?? 0;
                                $brd_jjg = round(($total_brdMA / $totaljumPanen), 2);

                                $total_s += $mutuAncak['bhts_ma'] ?? 0;
                                $total_m1 += $mutuAncak['bhtm1_ma'] ?? 0;
                                $total_m2 += $mutuAncak['bhtm2_ma'] ?? 0;
                                $total_m3 += $mutuAncak['bhtm3_ma'] ?? 0;
                                $total_bh += $mutuAncak['tot_jjg_ma'] ?? 0;
                                $buah_brd = round(($total_bh / ($totaljumPanen + $total_bh)) * 100, 2);

                                $total_ps += $mutuAncak['ps_ma'] ?? 0;
                                $ps_persen =count_percent($total_ps, $totalPokokSample);
                                }

                                @endphp

                                @php
                                $total_tph =0;
                                $total_brd =0;
                                $brd_tph = 0;
                                $buah_tph = 0;
                                $total_buah =0;
                                foreach($data['mutuTransport'] as $transport){
                                $total_tph += $transport['tph_sample'] ?? 0;
                                $total_brd += $transport['bt_total'] ?? 0;
                                $brd_tph = round($total_brd / $total_tph, 2);
                                $total_buah += $transport['restan_total'] ?? 0;
                                $buah_tph = round($total_buah / $total_tph, 2);
                                }
                                @endphp
                                <td>{{ $data['mutuAncak'][$key]['status_panen'] ?? '-' }}</td>
                                <td>{{ $key }}</td>
                                <td>{{ $data['mutuAncak'][$key]['luas_blok'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['sph'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['pokok_sample'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['luas_ha'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['persen_sample'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['jml_jjg_panen'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['akp_real'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['p_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['k_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['gl_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['total_brd_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['btr_jjg_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['bhts_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['bhtm1_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['bhtm2_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['bhtm3_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['tot_jjg_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['jjg_tgl_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['ps_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['PerPSMA'] ?? '-' }}</td>
                                <td>{{ $data['mutuTransport'][$key]['tph_sample'] ?? '-' }}</td>
                                <td>{{ $data['mutuTransport'][$key]['bt_total'] ?? '-' }}</td>
                                <td>{{ $data['mutuTransport'][$key]['skor'] ?? '-' }}</td>
                                <td>{{ $data['mutuTransport'][$key]['restan_total'] ?? '-' }}</td>
                                <td>{{ $data['mutuTransport'][$key]['skor_restan'] ?? '-' }}</td>
                            </tr>
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
                                    <td colspan="4">Total</td>
                                    <td>{{ $totalPokokSample }}</td>
                                    <td>{{ $totalLuasHa }}</td>
                                    <td>-</td>
                                    <td>{{$totaljumPanen}}</td>
                                    <td>{{$akp_real}}</td>
                                    <td>{{$total_p}}</td>
                                    <td>{{$total_k}}</td>
                                    <td>{{$total_gl}}</td>
                                    <td>{{$total_brdMA}}</td>
                                    <td>{{$brd_jjg}}</td>
                                    <td>{{ $total_s}}</td>
                                    <td>{{ $total_m1}}</td>
                                    <td>{{ $total_m2}}</td>
                                    <td>{{ $total_m3}}</td>
                                    <td>{{ $total_bh}}</td>
                                    <td>{{$buah_brd}}</td>
                                    <td>{{$total_ps}}</td>
                                    <td>{{$ps_persen}}</td>
                                    <td>{{$total_tph}}</td>
                                    <td>{{$total_brd}}</td>
                                    <td>{{$brd_tph}}</td>
                                    <td>{{$total_buah}}</td>
                                    <td>{{$buah_tph}}</td>
                                </tr>
                        </tbody>
                    </table>
                </table>
            </div>
        </div>



        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
            <div class="Wraping">
                <table class="my-table">
                    <thead>
                        <tr>

                            <th colspan="16">Mutu Buah</th>
                            <th colspan="12">Keterangan</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Nomor Blok</th>
                            <th rowspan="2">Total Janjang Sample</th>
                            <th colspan="2">Mentah</th>
                            <th colspan="2">Matang</th>
                            <th colspan="2">Lewat matang</th>
                            <th colspan="2">Janjang Kosong</th>
                            <th colspan="2">Abnormal</th>
                            <th colspan="2">Tidak Standar Vcut</th>
                            <th colspan="2">Alas Brondol</th>
                            <th colspan="12" rowspan="11"></th>

                        </tr>
                        <tr>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>Ya</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody id="tab2">
                        @php
                        $mergedKeys = array_keys($data['mutuBuah']);
                        $rowCount = count($mergedKeys);
                        $emptyRows = 8 - $rowCount;
                        @endphp


                        @php
                        $total_jjg =0;
                        $bh_mntah = 0;
                        $bh_abnromal =0;
                        $perMnth = 0;
                        $bh_matang =0;
                        $perMasak =0;
                        $bh_over =0;
                        $bh_emoty =0;
                        $bh_vcut =0;
                        $alasTot = 0;
                        $blokJm =0;
                        $perMnth = 0;
                        $perMasak = 0;
                        $perOver =0;
                        $perAbr= 0;
                        $preVcut = 0;
                        $mempt =0;
                        $perKrg = 0;
                        foreach($data['mutuBuah'] as $buah){
                        $total_jjg += $buah['jml_janjang'] ?? 0;
                        $bh_mntah += $buah['jml_mentah'] ?? 0;
                        $bh_abnromal += $buah['jml_abnormal'] ?? 0;
                        $bh_matang += $buah['jml_masak'] ?? 0;
                        $bh_over += $buah['jml_over'] ?? 0;
                        $bh_vcut += $buah['jml_vcut'] ?? 0;
                        $bh_emoty += $buah ['jml_empty'] ?? 0;
                        $alasTot += $buah['count_alas_br_1'] ?? 0;
                        $blokJm += $buah['blok_mb'] ?? 0;

                        $denom = ($total_jjg - $bh_abnromal) != 0 ? ($total_jjg - $bh_abnromal) : 1;
                        $perMnth = $denom != 0 ? round(($bh_mntah / $denom) * 100, 2) : 0;
                        $perMasak = $denom != 0 ? round(($bh_matang / $denom) * 100, 2) : 0;
                        $perOver = $denom != 0 ? round(($bh_over / $denom) * 100, 2) : 0;
                        $perAbr= $denom != 0 ? round(($bh_abnromal / $denom) * 100, 2) : 0;
                        $preVcut = count_percent($bh_vcut, $total_jjg);
                        $mempt = $denom != 0 ? round(($bh_emoty / $denom) * 100, 2) : 0;
                        $perKrg = count_percent($alasTot, $blokJm);

                        }
                        @endphp

                        @foreach ($data['mutuBuah'] as $key =>$item)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{$item['jml_janjang']}}</td>
                            <td>{{$item['jml_mentah']}}</td>
                            <td>{{$item['PersenBuahMentah']}}</td>
                            <td>{{$item['jml_masak']}}</td>
                            <td>{{$item['PersenBuahMasak']}}</td>
                            <td>{{$item['jml_over']}}</td>
                            <td>{{$item['PersenBuahOver']}}</td>
                            <td>{{$item['jml_empty']}}</td>
                            <td>{{$item['PersenPerJanjang']}}</td>
                            <td>{{$item['jml_abnormal']}}</td>
                            <td>{{$item['PersenAbr']}}</td>
                            <td>{{$item['jml_vcut']}}</td>
                            <td>{{$item['PersenVcut']}}</td>
                            <td>{{$item['count_alas_br_1']}} of {{$item['blok_mb']}}</td>
                            <td>{{$item['PersenKrgBrd']}}</td>
                        </tr>
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

                            </tr>
                            @endfor

                            <tr>
                                <td>Total</td>
                                <td>{{$total_jjg}}</td>
                                <td>{{$bh_mntah}}</td>
                                <td>{{$perMnth}}</td>
                                <td>{{$bh_matang}}</td>
                                <td>{{$perMasak}}</td>
                                <td>{{$bh_over}}</td>
                                <td>{{$perOver}}</td>
                                <td>{{$bh_emoty}}</td>
                                <td>{{$mempt}}</td>
                                <td>{{$bh_abnromal}}</td>
                                <td>{{$perAbr}}</td>
                                <td>{{$bh_vcut}}</td>
                                <td>{{$preVcut}}</td>
                                <td>{{$alasTot}} of {{$blokJm}}</td>
                                <td>{{$perKrg}}</td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
            <div class="custom-tables-container">
                <!-- Table 1 -->
                <table class="custom-table table-1-no-border" style="float: left; width: 20%;">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">Catatan Lainnya:</th>
                        </tr>
                    </thead>
                    <tr>
                        <td>Frond Stacking</td>
                        <td>: 99,2%</td>
                    </tr>
                    <tr>
                        <td>Pokok Kuning</td>
                        <td>: 0,5%</td>
                    </tr>
                    <tr>
                        <td>Piringan Semak</td>
                        <td>: 1,3%</td>
                    </tr>
                    <tr>
                        <td>Under Pruning</td>
                        <td>: 2,7%</td>
                    </tr>
                    <tr>
                        <td>Over Pruning</td>
                        <td>: 0,4%</td>
                    </tr>
                    <tr>
                        <td>Mentah Tanpa Brondol</td>
                        <td>: 0,0%</td>
                    </tr>
                    <tr>
                        <td>Mentah Kurang Brondol</td>
                        <td>: 0,0%</td>
                    </tr>
                    <tr>
                        <td>V-Cut</td>
                        <td>: 75,6%</td>
                    </tr>
                </table>

                <!-- Table 2 -->
                <table class="custom-table" style="float: right; width: 80%; border-collapse: collapse;" border="1">
                    <thead>
                        <tr>
                            <th colspan="12">Demikian hasil pemeriksaan ini dibuat dengan sebenar-benarnya tanpa rekayasa dan paksaan dari Siapapun,</th>
                        </tr>
                        <tr>
                            <th colspan="6">Dibuat</th>
                            <th colspan="3">Diterima</th>
                            <th colspan="3">Diketahui</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="4"></td>
                            <td>Harno</td>
                            <td rowspan="4"></td>
                            <td>Nurul Akbar Majid</td>
                            <td rowspan="4"></td>
                            <td>Andika Ika S.</td>
                            <td colspan="3" rowspan="7"></td>
                            <td colspan="3" rowspan="7"></td>
                        </tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                    </tbody>
                </table>

            </div>
        </div>

    </div>


</body>

</html>