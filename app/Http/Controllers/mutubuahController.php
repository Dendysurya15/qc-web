<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;
use Barryvdh\DomPDF\Facade\Pdf;

require '../app/helpers.php';
class mutubuahController extends Controller
{
    //
    public function dashboard_mutubuah(Request $request)
    {


        function divide_janjang($jumlah_janjang)
        {
            $result = array(
                'bmk' => 0,
                'bmt' => 0,
                'overripe' => 0,
                'empty' => 0,
                'abnormal' => 0,
                'rd' => 0
            );

            while (array_sum($result) !== $jumlah_janjang || $result['abnormal'] > 20 || $result['abnormal'] == 0) {
                $result['bmk'] = rand(0, $jumlah_janjang);
                $result['bmt'] = rand(0, $jumlah_janjang - $result['bmk']);
                $result['overripe'] = rand(0, $jumlah_janjang - $result['bmk'] - $result['bmt']);
                $result['empty'] = rand(0, $jumlah_janjang - $result['bmk'] - $result['bmt'] - $result['overripe']);
                $result['abnormal'] = rand(0, $jumlah_janjang - $result['bmk'] - $result['bmt'] - $result['overripe'] - $result['empty']);
                $result['rd'] = $jumlah_janjang - $result['bmk'] - $result['bmt'] - $result['overripe'] - $result['empty'] - $result['abnormal'];
            }

            return $result;
        }


        $jumlah_janjang = 422;
        $result = divide_janjang($jumlah_janjang);

        dd($result);
        $queryEst = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->whereNotIn('estate.est', ['CWS1', 'CWS2', 'CWS3'])
            ->get();

        $queryEst = json_decode($queryEst, true);

        $getDate = date('Y-m');


        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $shortMonth = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $arrHeader = ['No', 'Estate', 'Kode', 'Estate Manager'];
        $arrHeader =  array_merge($arrHeader, $shortMonth);
        // array_push($arrHeader, date('Y'));

        $arrHeaderSc = ['WILAYAH', 'Group Manager'];
        $arrHeaderSc = array_merge($arrHeaderSc, $shortMonth);
        // array_push($arrHeaderSc, date('Y'));

        $arrHeaderReg = ['Region', 'Region Head'];
        $arrHeaderReg = array_merge($arrHeaderReg, $shortMonth);

        $arrHeaderTrd = ['No', 'Estate', 'Afdeling', 'Nama Asisten'];
        $arrHeaderTrd =  array_merge($arrHeaderTrd, $shortMonth);
        // array_push($arrHeaderTrd, date('Y'));


        ///untuk perhitungan latihan nnti di hapus kalau sudah
        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', '1')
            ->get();
        $queryEste = json_decode($queryEste, true);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);

        $queryMTbuah = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            // ->whereBetween('mutu_buah.datetime', [$startDate, $endDate])

            ->whereBetween('sidak_mutu_buah.datetime', ['2023-04-03', '2023-04-09'])
            ->get();
        $queryMTbuah = $queryMTbuah->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($queryMTbuah, true);

        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();

        $queryAsisten = json_decode($queryAsisten, true);
        // dd($queryAsisten);


        $databulananBuah = array();
        foreach ($queryMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {

                    $databulananBuah[$key][$key2][$key3] = $value3;
                }
            }
        }

        $defPerbulanWil = array();

        foreach ($queryEste as $key2 => $value2) {
            foreach ($queryAfd as $key3 => $value3) {
                if ($value2['est'] == $value3['est']) {
                    $defPerbulanWil[$value2['est']][$value3['nama']] = 0;
                }
            }
        }



        foreach ($defPerbulanWil as $estateKey => $afdelingArray) {
            foreach ($afdelingArray as $afdelingKey => $afdelingValue) {
                if (isset($databulananBuah[$estateKey]) && isset($databulananBuah[$estateKey][$afdelingKey])) {
                    $defPerbulanWil[$estateKey][$afdelingKey] = $databulananBuah[$estateKey][$afdelingKey];
                }
            }
        }

        // dd($defPerbulanWil);
        $sidak_buah = array();
        foreach ($defPerbulanWil as $key => $value) {
            foreach ($value as $key1 => $value1) {
                if (is_array($value1)) {
                    $jjg_sample = 0;
                    $tnpBRD = 0;
                    $krgBRD = 0;
                    $abr = 0;
                    $skor_total = 0;
                    $overripe = 0;
                    $empty = 0;
                    $vcut = 0;
                    $rd = 0;
                    $sum_kr = 0;
                    $allSkor = 0;
                    $combination_counts = array();

                    foreach ($value1 as $key2 => $value2) {
                        $combination = $value2['blok'] . ' ' . $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $jjg_sample += $value2['jumlah_jjg'];
                        $tnpBRD += $value2['bmt'];
                        $krgBRD += $value2['bmk'];
                        $abr += $value2['abnormal'];
                        $overripe += $value2['overripe'];
                        $empty += $value2['empty'];
                        $vcut += $value2['vcut'];
                        $rd += $value2['rd'];
                        $sum_kr += $value2['alas_br'];
                    }
                    $dataBLok = count($combination_counts);
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty)) / ($jjg_sample - $abr) * 100, 2);
                    $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
                    $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                    $sidak_buah[$key][$key1]['blok'] = $dataBLok;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;
                    $sidak_buah[$key][$key1]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1]['tnp_brd'] = $tnpBRD;
                    $sidak_buah[$key][$key1]['krg_brd'] = $krgBRD;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = $skor_total;
                    $sidak_buah[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                    $sidak_buah[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty);
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                    $sidak_buah[$key][$key1]['lewat_matang'] = $overripe;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                    $sidak_buah[$key][$key1]['janjang_kosong'] = $empty;
                    $sidak_buah[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                    $sidak_buah[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                    $sidak_buah[$key][$key1]['vcut'] = $vcut;
                    $sidak_buah[$key][$key1]['vcut_persen'] = $skor_vcut;
                    $sidak_buah[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $sidak_buah[$key][$key1]['abnormal'] = $abr;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['rat_dmg'] = $rd;
                    $sidak_buah[$key][$key1]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['karung'] = $sum_kr;
                    $sidak_buah[$key][$key1]['TPH'] = $total_kr;
                    $sidak_buah[$key][$key1]['persen_krg'] = $per_kr;
                    $sidak_buah[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                    $sidak_buah[$key][$key1]['All_skor'] = $allSkor;
                    $sidak_buah[$key][$key1]['kategori'] = sidak_akhir($allSkor);

                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                            $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                        }
                    }
                } else {

                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = 0;
                    $sidak_buah[$key][$key1]['blok'] = 0;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;
                    $sidak_buah[$key][$key1]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1]['tnp_brd'] = 0;
                    $sidak_buah[$key][$key1]['krg_brd'] = 0;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = 0;
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = 0;
                    $sidak_buah[$key][$key1]['total_jjg'] = 0;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = 0;
                    $sidak_buah[$key][$key1]['skor_total'] = 0;
                    $sidak_buah[$key][$key1]['jjg_matang'] = 0;
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = 0;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = 0;
                    $sidak_buah[$key][$key1]['lewat_matang'] = 0;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  0;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = 0;
                    $sidak_buah[$key][$key1]['janjang_kosong'] = 0;
                    $sidak_buah[$key][$key1]['persen_kosong'] = 0;
                    $sidak_buah[$key][$key1]['skor_kosong'] = 0;
                    $sidak_buah[$key][$key1]['vcut'] = 0;
                    $sidak_buah[$key][$key1]['vcut_persen'] = 0;
                    $sidak_buah[$key][$key1]['vcut_skor'] = 0;
                    $sidak_buah[$key][$key1]['abnormal'] = 0;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = 0;
                    $sidak_buah[$key][$key1]['rat_dmg'] = 0;
                    $sidak_buah[$key][$key1]['rd_persen'] = 0;
                    $sidak_buah[$key][$key1]['karung'] = 0;
                    $sidak_buah[$key][$key1]['TPH'] = 0;
                    $sidak_buah[$key][$key1]['persen_krg'] = 0;
                    $sidak_buah[$key][$key1]['skor_kr'] = 0;
                    $sidak_buah[$key][$key1]['All_skor'] = 0;
                    $sidak_buah[$key][$key1]['kategori'] = 0;

                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                            $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                        }
                    }
                }
            }
        }
        // dd($sidak_buah);
        $mutu_buah = array();
        foreach ($queryEste as $key => $value) {
            foreach ($sidak_buah as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mutu_buah[$value['wil']][$key2] = array_merge($mutu_buah[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }

        // Remove the "Plasma1" element from the original array
        if (isset($mutu_buah[1]['Plasma1'])) {
            $plasma1 = $mutu_buah[1]['Plasma1'];
            unset($mutu_buah[1]['Plasma1']);
        } else {
            $plasma1 = null;
        }

        // Add the "Plasma1" element to its own index
        if ($plasma1 !== null) {
            $mutu_buah[4] = ['Plasma1' => $plasma1];
        }

        // Optional: Re-index the array to ensure the keys are in sequential order
        $mutu_buah = array_values($mutu_buah);

        // dd($mutu_buah);

        $mutubuah_est = array();
        foreach ($mutu_buah as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $jjg_sample = 0;
                $tnpBRD = 0;
                $krgBRD = 0;
                $abr = 0;
                $skor_total = 0;
                $overripe = 0;
                $empty = 0;
                $vcut = 0;
                $rd = 0;
                $sum_kr = 0;
                $allSkor = 0;
                $dataBLok = 0;
                foreach ($value1 as $key2 => $value2) {
                    $jjg_sample += $value2['Jumlah_janjang'];
                    $tnpBRD += $value2['tnp_brd'];
                    $krgBRD += $value2['krg_brd'];
                    $abr += $value2['abnormal'];
                    $overripe += $value2['lewat_matang'];
                    $empty += $value2['janjang_kosong'];
                    $vcut += $value2['vcut'];

                    $rd += $value2['rat_dmg'];

                    $dataBLok += $value2['blok'];
                    $sum_kr += $value2['karung'];
                }

                if ($sum_kr != 0) {
                    $total_kr = round($sum_kr / $dataBLok, 2);
                } else {
                    $total_kr = 0;
                }
                $per_kr = round($total_kr * 100, 2);
                $skor_total = round(($jjg_sample - $abr != 0 ? (($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_jjgMSk = round(($jjg_sample - $abr != 0 ? (($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty)) / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_lewatMTng = round(($jjg_sample - $abr != 0 ? ($overripe / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_jjgKosong = round(($jjg_sample - $abr != 0 ? ($empty / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_vcut = round(($jjg_sample != 0 ? ($vcut / $jjg_sample) * 100 : 0), 2);

                $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);


                $mutubuah_est[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                $mutubuah_est[$key][$key1]['blok'] = $dataBLok;
                $mutubuah_est[$key][$key1]['est'] = $key;
                $mutubuah_est[$key][$key1]['afd'] = $key1;
                $mutubuah_est[$key][$key1]['nama_staff'] = '-';
                $mutubuah_est[$key][$key1]['tnp_brd'] = $tnpBRD;
                $mutubuah_est[$key][$key1]['krg_brd'] = $krgBRD;
                $mutubuah_est[$key][$key1]['persenTNP_brd'] = round(($jjg_sample - $abr != 0 ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['persenKRG_brd'] = round(($jjg_sample - $abr != 0 ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['abnormal_persen'] = round(($jjg_sample != 0 ? ($abr / $jjg_sample) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['rd_persen'] = round(($jjg_sample != 0 ? ($rd / $jjg_sample) * 100 : 0), 2);


                $mutubuah_est[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                $mutubuah_est[$key][$key1]['persen_totalJjg'] = $skor_total;
                $mutubuah_est[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                $mutubuah_est[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty);
                $mutubuah_est[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                $mutubuah_est[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                $mutubuah_est[$key][$key1]['lewat_matang'] = $overripe;
                $mutubuah_est[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                $mutubuah_est[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                $mutubuah_est[$key][$key1]['janjang_kosong'] = $empty;
                $mutubuah_est[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                $mutubuah_est[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                $mutubuah_est[$key][$key1]['vcut'] = $vcut;
                $mutubuah_est[$key][$key1]['vcut_persen'] = $skor_vcut;
                $mutubuah_est[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                $mutubuah_est[$key][$key1]['abnormal'] = $abr;

                $mutubuah_est[$key][$key1]['rat_dmg'] = $rd;

                $mutubuah_est[$key][$key1]['karung'] = $sum_kr;
                $mutubuah_est[$key][$key1]['TPH'] = $total_kr;
                $mutubuah_est[$key][$key1]['persen_krg'] = $per_kr;
                $mutubuah_est[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                $mutubuah_est[$key][$key1]['All_skor'] = $allSkor;
                $mutubuah_est[$key][$key1]['kategori'] = sidak_akhir($allSkor);
            }
        }

        // dd($mutubuah_est);

        $mutuBuah_wil = array();
        foreach ($mutubuah_est as $key => $value) {
            $jjg_sample = 0;
            $tnpBRD = 0;
            $krgBRD = 0;
            $abr = 0;
            $skor_total = 0;
            $overripe = 0;
            $empty = 0;
            $vcut = 0;
            $rd = 0;
            $sum_kr = 0;
            $allSkor = 0;
            $dataBLok = 0;
            foreach ($value as $key1 => $value1) {
                // dd($value2);
                $jjg_sample += $value1['Jumlah_janjang'];
                $tnpBRD += $value1['tnp_brd'];
                $krgBRD += $value1['krg_brd'];
                $abr += $value1['abnormal'];
                $overripe += $value1['lewat_matang'];
                $empty += $value1['janjang_kosong'];
                $vcut += $value1['vcut'];

                $rd += $value1['rat_dmg'];

                $dataBLok += $value1['blok'];
                $sum_kr += $value1['karung'];
            }

            if ($sum_kr != 0) {
                $total_kr = round($sum_kr / $dataBLok, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round(($jjg_sample - $abr != 0 ? (($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_jjgMSk = round(($jjg_sample - $abr != 0 ? (($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty)) / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_lewatMTng = round(($jjg_sample - $abr != 0 ? ($overripe / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_jjgKosong = round(($jjg_sample - $abr != 0 ? ($empty / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_vcut = round(($jjg_sample != 0 ? ($vcut / $jjg_sample) * 100 : 0), 2);

            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);


            $mutuBuah_wil[$key]['Jumlah_janjang'] = $jjg_sample;
            $mutuBuah_wil[$key]['blok'] = $dataBLok;
            $mutuBuah_wil[$key]['est'] = $key;
            $mutuBuah_wil[$key]['afd'] = $key1;
            $mutuBuah_wil[$key]['nama_staff'] = '-';
            $mutuBuah_wil[$key]['tnp_brd'] = $tnpBRD;
            $mutuBuah_wil[$key]['krg_brd'] = $krgBRD;
            $mutuBuah_wil[$key]['persenTNP_brd'] = round(($jjg_sample - $abr != 0 ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
            $mutuBuah_wil[$key]['persenKRG_brd'] = round(($jjg_sample - $abr != 0 ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
            $mutuBuah_wil[$key]['abnormal_persen'] = round(($jjg_sample != 0 ? ($abr / $jjg_sample) * 100 : 0), 2);
            $mutuBuah_wil[$key]['rd_persen'] = round(($jjg_sample != 0 ? ($rd / $jjg_sample) * 100 : 0), 2);


            $mutuBuah_wil[$key]['total_jjg'] = $tnpBRD + $krgBRD;
            $mutuBuah_wil[$key]['persen_totalJjg'] = $skor_total;
            $mutuBuah_wil[$key]['skor_total'] = sidak_brdTotal($skor_total);
            $mutuBuah_wil[$key]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty);
            $mutuBuah_wil[$key]['persen_jjgMtang'] = $skor_jjgMSk;
            $mutuBuah_wil[$key]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
            $mutuBuah_wil[$key]['lewat_matang'] = $overripe;
            $mutuBuah_wil[$key]['persen_lwtMtng'] =  $skor_lewatMTng;
            $mutuBuah_wil[$key]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
            $mutuBuah_wil[$key]['janjang_kosong'] = $empty;
            $mutuBuah_wil[$key]['persen_kosong'] = $skor_jjgKosong;
            $mutuBuah_wil[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
            $mutuBuah_wil[$key]['vcut'] = $vcut;
            $mutuBuah_wil[$key]['vcut_persen'] = $skor_vcut;
            $mutuBuah_wil[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
            $mutuBuah_wil[$key]['abnormal'] = $abr;

            $mutuBuah_wil[$key]['rat_dmg'] = $rd;

            $mutuBuah_wil[$key]['karung'] = $sum_kr;
            $mutuBuah_wil[$key]['TPH'] = $total_kr;
            $mutuBuah_wil[$key]['persen_krg'] = $per_kr;
            $mutuBuah_wil[$key]['skor_kr'] = sidak_PengBRD($per_kr);
            $mutuBuah_wil[$key]['All_skor'] = $allSkor;
            $mutuBuah_wil[$key]['kategori'] = sidak_akhir($allSkor);
        }
        // dd($mutuBuah_wil);
        foreach ($mutu_buah as $key1 => $estates)  if (is_array($estates)) {
            $sortedData = array();
            $sortedDataEst = array();

            foreach ($estates as $estateName => $data) {
                if (is_array($data)) {
                    // dd($data);
                    $sortedDataEst[] = array(
                        'key1' => $key1,
                        'estateName' => $estateName,
                        'data' => $data
                    );
                    foreach ($data as $key2 => $scores) {
                        if (is_array($scores)) {
                            // dd($scores);
                            $sortedData[] = array(
                                'estateName' => $estateName,
                                'key2' => $key2,
                                'scores' => $scores
                            );
                        }
                    }
                }
            }

            //mengurutkan untuk nilai afd
            usort($sortedData, function ($a, $b) {
                return $b['scores']['All_skor'] - $a['scores']['All_skor'];
            });
            // //mengurutkan untuk nilai estate
            // usort($sortedDataEst, function ($a, $b) {
            //     return $b['data']['TotalSkorEST'] - $a['data']['TotalSkorEST'];
            // });

            //menambahkan nilai rank ke dalam afd
            $rank = 1;
            foreach ($sortedData as $sortedEstate) {
                $mutu_buah[$key1][$sortedEstate['estateName']][$sortedEstate['key2']]['rankAFD'] = $rank;
                $rank++;
            }

            // //menambahkan nilai rank ke dalam estate
            // $rank = 1;
            // foreach ($sortedDataEst as $sortedest) {
            //     $RekapWIlTabel[$key1][$sortedest['estateName']]['rankEST'] = $rank;
            //     $rank++;
            // }


            // unset($sortedData, $sortedDataEst);
            unset($sortedData);
        }
        foreach ($mutubuah_est as $key1 => $estates)  if (is_array($estates)) {

            $sortedDataEst = array();

            foreach ($estates as $estateName => $data) {
                if (is_array($data)) {
                    // dd($data);
                    $sortedDataEst[] = array(
                        'key1' => $key1,
                        'estateName' => $estateName,
                        'data' => $data
                    );
                }
            }

            // //mengurutkan untuk nilai estate
            usort($sortedDataEst, function ($a, $b) {
                return $b['data']['All_skor'] - $a['data']['All_skor'];
            });

            // //menambahkan nilai rank ke dalam estate
            $rank = 1;
            foreach ($sortedDataEst as $sortedest) {
                $mutubuah_est[$key1][$sortedest['estateName']]['rankEST'] = $rank;
                $rank++;
            }
            // unset($sortedData, $sortedDataEst);
            unset($sortedData);
        }

        // dd($mutuBuah_wil);/
        $sortedDataEst = array();
        foreach ($mutuBuah_wil as $key1 => $estates) {
            if (is_array($estates)) {
                $sortedDataEst[] = array(
                    'key1' => $key1,
                    'data' => $estates
                );
            }
        }

        usort($sortedDataEst, function ($a, $b) {
            return $b['data']['All_skor'] - $a['data']['All_skor'];
        });

        $rank = 1;
        foreach ($sortedDataEst as $sortedest) {
            $estateKey = $sortedest['key1'];
            $mutuBuah_wil[$estateKey]['rankWil'] = $rank;
            $rank++;
        }

        unset($sortedDataEst);
        // dd($mutuBuah_wil);


        return view('dashboard_mutubuah', [
            'arrHeader' => $arrHeader,
            'arrHeaderSc' => $arrHeaderSc,
            'arrHeaderTrd' => $arrHeaderTrd,
            'arrHeaderReg' => $arrHeaderReg,
        ]);
    }

    public function getWeek(Request $request)
    {
        $regional = $request->input('reg');
        $startWeek = $request->input('startWeek');
        $lastWeek = $request->input('lastWeek');
        $dateWeek = $request->input('dateWeek');

        // Convert the week format to start and end dates
        $weekDateTime = new DateTime($dateWeek);
        $weekDateTime->setISODate((int)$weekDateTime->format('o'), (int)$weekDateTime->format('W'));

        $startDate = $weekDateTime->format('Y-m-d');
        $weekDateTime->modify('+6 days');
        $endDate = $weekDateTime->format('Y-m-d');

        $queryAsisten = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            ->get();

        $queryAsisten = json_decode($queryAsisten, true);

        // dd($startDate, $endDate);
        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->get();
        $queryEste = json_decode($queryEste, true);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);

        $queryMTbuah = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            ->whereBetween('sidak_mutu_buah.datetime', [$startDate, $endDate])

            // ->whereBetween('sidak_mutu_buah.datetime', ['2023-04-03', '2023-04-09'])
            ->get();
        $queryMTbuah = $queryMTbuah->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($queryMTbuah, true);
        $databulananBuah = array();
        foreach ($queryMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {

                    $databulananBuah[$key][$key2][$key3] = $value3;
                }
            }
        }

        $defPerbulanWil = array();

        foreach ($queryEste as $key2 => $value2) {
            foreach ($queryAfd as $key3 => $value3) {
                if ($value2['est'] == $value3['est']) {
                    $defPerbulanWil[$value2['est']][$value3['nama']] = 0;
                }
            }
        }



        foreach ($defPerbulanWil as $estateKey => $afdelingArray) {
            foreach ($afdelingArray as $afdelingKey => $afdelingValue) {
                if (isset($databulananBuah[$estateKey]) && isset($databulananBuah[$estateKey][$afdelingKey])) {
                    $defPerbulanWil[$estateKey][$afdelingKey] = $databulananBuah[$estateKey][$afdelingKey];
                }
            }
        }

        // dd($defPerbulanWil);
        $sidak_buah = array();
        foreach ($defPerbulanWil as $key => $value) {
            $totalJJG = 0;
            $totaltnpBRD = 0;
            $totalkrgBRD = 0;
            $totalabr = 0;
            $TotPersenTNP = 0;
            $TotPersenKRG = 0;
            $totJJG = 0;
            $totPersenTOtaljjg = 0;
            $totSkor_total = 0;
            $totoverripe = 0;
            $totempty = 0;
            $totJJG_matang = 0;
            $totPer_jjgMtng = 0;
            $totPer_over = 0;
            $totSkor_Over = 0;
            $totPer_Empty = 0;
            $totSkor_Empty = 0;
            $totVcut = 0;
            $totPer_vcut =  0;
            $totSkor_Vcut =  0;
            $totPer_abr =  0;
            $totRD = 0;
            $totPer_rd = 0;
            $totBlok = 0;
            $totKR = 0;
            $tot_krS = 0;
            $totPer_kr = 0;
            $totSkor_kr = 0;
            $totALlskor = 0;
            $totKategor = 0;
            foreach ($value as $key1 => $value1) {
                if (is_array($value1)) {
                    $jjg_sample = 0;
                    $tnpBRD = 0;
                    $krgBRD = 0;
                    $abr = 0;
                    $skor_total = 0;
                    $overripe = 0;
                    $empty = 0;
                    $vcut = 0;
                    $rd = 0;
                    $sum_kr = 0;
                    $allSkor = 0;
                    $combination_counts = array();

                    foreach ($value1 as $key2 => $value2) {
                        $combination = $value2['blok'] . ' ' . $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $jjg_sample += $value2['jumlah_jjg'];
                        $tnpBRD += $value2['bmt'];
                        $krgBRD += $value2['bmk'];
                        $abr += $value2['abnormal'];
                        $overripe += $value2['overripe'];
                        $empty += $value2['empty'];
                        $vcut += $value2['vcut'];
                        $rd += $value2['rd'];
                        $sum_kr += $value2['alas_br'];
                    }
                    $dataBLok = count($combination_counts);
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty)) / ($jjg_sample - $abr) * 100, 2);
                    $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
                    $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                    $sidak_buah[$key][$key1]['blok'] = $dataBLok;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;
                    $sidak_buah[$key][$key1]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1]['tnp_brd'] = $tnpBRD;
                    $sidak_buah[$key][$key1]['krg_brd'] = $krgBRD;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = $skor_total;
                    $sidak_buah[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                    $sidak_buah[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty);
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                    $sidak_buah[$key][$key1]['lewat_matang'] = $overripe;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                    $sidak_buah[$key][$key1]['janjang_kosong'] = $empty;
                    $sidak_buah[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                    $sidak_buah[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                    $sidak_buah[$key][$key1]['vcut'] = $vcut;
                    $sidak_buah[$key][$key1]['karung'] = $sum_kr;
                    $sidak_buah[$key][$key1]['vcut_persen'] = $skor_vcut;
                    $sidak_buah[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $sidak_buah[$key][$key1]['abnormal'] = $abr;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['rat_dmg'] = $rd;
                    $sidak_buah[$key][$key1]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['TPH'] = $total_kr;
                    $sidak_buah[$key][$key1]['persen_krg'] = $per_kr;
                    $sidak_buah[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                    $sidak_buah[$key][$key1]['All_skor'] = $allSkor;
                    $sidak_buah[$key][$key1]['kategori'] = sidak_akhir($allSkor);

                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                            $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                        }
                    }


                    $totalJJG += $jjg_sample;
                    $totaltnpBRD += $tnpBRD;
                    $totalkrgBRD += $krgBRD;
                    $totalabr += $abr;
                    $TotPersenTNP = round(($totaltnpBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $TotPersenKRG = round(($totalkrgBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $totJJG = $totaltnpBRD + $totalkrgBRD;
                    $totPersenTOtaljjg = round((($totaltnpBRD + $totalkrgBRD) / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_total = sidak_brdTotal($totPersenTOtaljjg);
                    $totoverripe += $overripe;
                    $totempty += $empty;
                    $totJJG_matang = $totalJJG - ($totaltnpBRD + $totalkrgBRD + $totoverripe + $totempty);
                    $totPer_jjgMtng = round($totJJG_matang / ($totalJJG - $totalabr) * 100, 2);

                    $totSkor_jjgMtng = sidak_matangSKOR($totPer_jjgMtng);
                    $totPer_over = round(($totoverripe / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Over = sidak_lwtMatang($totPer_over);
                    $totPer_Empty = round(($totempty / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Empty = sidak_jjgKosong($totPer_Empty);
                    $totVcut += $vcut;
                    $totPer_vcut =   round(($totVcut / $totalJJG) * 100, 2);
                    $totSkor_Vcut =  sidak_tangkaiP($totPer_vcut);
                    $totPer_abr =  round(($totalabr / $totalJJG) * 100, 2);
                    $totRD += $rd;
                    $totPer_rd = round(($totRD / $totalJJG) * 100, 2);
                    $totBlok += $dataBLok;
                    $totKR += $sum_kr;
                    if ($totKR != 0) {
                        $tot_krS = round($totKR / $totBlok, 2);
                    } else {
                        $tot_krS = 0;
                    }
                    $totPer_kr = round($tot_krS * 100, 2);
                    $totSkor_kr = sidak_PengBRD($totPer_kr);
                    $totALlskor = sidak_brdTotal($totPersenTOtaljjg) + sidak_matangSKOR($totPer_jjgMtng) + sidak_lwtMatang($totPer_over) + sidak_jjgKosong($totPer_Empty) + sidak_tangkaiP($totPer_vcut) + sidak_PengBRD($totPer_kr);

                    $totKategor = sidak_akhir($totALlskor);
                } else {

                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = 0;
                    $sidak_buah[$key][$key1]['blok'] = 0;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;
                    $sidak_buah[$key][$key1]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1]['tnp_brd'] = 0;
                    $sidak_buah[$key][$key1]['krg_brd'] = 0;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = 0;
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = 0;
                    $sidak_buah[$key][$key1]['total_jjg'] = 0;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = 0;
                    $sidak_buah[$key][$key1]['skor_total'] = 0;
                    $sidak_buah[$key][$key1]['jjg_matang'] = 0;
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = 0;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = 0;
                    $sidak_buah[$key][$key1]['lewat_matang'] = 0;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  0;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = 0;
                    $sidak_buah[$key][$key1]['janjang_kosong'] = 0;
                    $sidak_buah[$key][$key1]['persen_kosong'] = 0;
                    $sidak_buah[$key][$key1]['skor_kosong'] = 0;
                    $sidak_buah[$key][$key1]['vcut'] = 0;
                    $sidak_buah[$key][$key1]['karung'] = 0;
                    $sidak_buah[$key][$key1]['vcut_persen'] = 0;
                    $sidak_buah[$key][$key1]['vcut_skor'] = 0;
                    $sidak_buah[$key][$key1]['abnormal'] = 0;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = 0;
                    $sidak_buah[$key][$key1]['rat_dmg'] = 0;
                    $sidak_buah[$key][$key1]['rd_persen'] = 0;
                    $sidak_buah[$key][$key1]['TPH'] = 0;
                    $sidak_buah[$key][$key1]['persen_krg'] = 0;
                    $sidak_buah[$key][$key1]['skor_kr'] = 0;
                    $sidak_buah[$key][$key1]['All_skor'] = 0;
                    $sidak_buah[$key][$key1]['kategori'] = 0;
                    foreach ($queryAsisten as $ast => $asisten) {
                        if ($key === $asisten['est'] && $key1 === $asisten['afd']) {
                            $sidak_buah[$key][$key1]['nama_asisten'] = $asisten['nama'];
                        }
                    }
                }
            }
        }



        $mutu_buah = array();
        foreach ($queryEste as $key => $value) {
            foreach ($sidak_buah as $key2 => $value2) {
                if ($value['est'] == $key2) {
                    $mutu_buah[$value['wil']][$key2] = array_merge($mutu_buah[$value['wil']][$key2] ?? [], $value2);
                }
            }
        }

        // Remove the "Plasma1" element from the original array
        if (isset($mutu_buah[1]['Plasma1'])) {
            $plasma1 = $mutu_buah[1]['Plasma1'];
            unset($mutu_buah[1]['Plasma1']);
        } else {
            $plasma1 = null;
        }

        // Add the "Plasma1" element to its own index
        if ($plasma1 !== null) {
            $mutu_buah[4] = ['Plasma1' => $plasma1];
        }

        // Optional: Re-index the array to ensure the keys are in sequential order
        $mutu_buah = array_values($mutu_buah);

        $mutubuah_est = array();
        foreach ($mutu_buah as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $jjg_sample = 0;
                $tnpBRD = 0;
                $krgBRD = 0;
                $abr = 0;
                $skor_total = 0;
                $overripe = 0;
                $empty = 0;
                $vcut = 0;
                $rd = 0;
                $sum_kr = 0;
                $allSkor = 0;
                $dataBLok = 0;
                foreach ($value1 as $key2 => $value2) {
                    $jjg_sample += $value2['Jumlah_janjang'];
                    $tnpBRD += $value2['tnp_brd'];
                    $krgBRD += $value2['krg_brd'];
                    $abr += $value2['abnormal'];
                    $overripe += $value2['lewat_matang'];
                    $empty += $value2['janjang_kosong'];
                    $vcut += $value2['vcut'];

                    $rd += $value2['rat_dmg'];

                    $dataBLok += $value2['blok'];
                    $sum_kr += $value2['karung'];
                }

                if ($sum_kr != 0) {
                    $total_kr = round($sum_kr / $dataBLok, 2);
                } else {
                    $total_kr = 0;
                }
                $per_kr = round($total_kr * 100, 2);
                $skor_total = round(($jjg_sample - $abr != 0 ? (($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_jjgMSk = round(($jjg_sample - $abr != 0 ? (($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty)) / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_lewatMTng = round(($jjg_sample - $abr != 0 ? ($overripe / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_jjgKosong = round(($jjg_sample - $abr != 0 ? ($empty / ($jjg_sample - $abr)) * 100 : 0), 2);

                $skor_vcut = round(($jjg_sample != 0 ? ($vcut / $jjg_sample) * 100 : 0), 2);

                $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);


                $mutubuah_est[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                $mutubuah_est[$key][$key1]['blok'] = $dataBLok;
                $mutubuah_est[$key][$key1]['EM'] = 'EM';
                $mutubuah_est[$key][$key1]['Nama_assist'] = '-';
                $mutubuah_est[$key][$key1]['nama_staff'] = '-';
                $mutubuah_est[$key][$key1]['tnp_brd'] = $tnpBRD;
                $mutubuah_est[$key][$key1]['krg_brd'] = $krgBRD;
                $mutubuah_est[$key][$key1]['persenTNP_brd'] = round(($jjg_sample - $abr != 0 ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['persenKRG_brd'] = round(($jjg_sample - $abr != 0 ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['abnormal_persen'] = round(($jjg_sample != 0 ? ($abr / $jjg_sample) * 100 : 0), 2);
                $mutubuah_est[$key][$key1]['rd_persen'] = round(($jjg_sample != 0 ? ($rd / $jjg_sample) * 100 : 0), 2);


                $mutubuah_est[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                $mutubuah_est[$key][$key1]['persen_totalJjg'] = $skor_total;
                $mutubuah_est[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                $mutubuah_est[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty);
                $mutubuah_est[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                $mutubuah_est[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                $mutubuah_est[$key][$key1]['lewat_matang'] = $overripe;
                $mutubuah_est[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                $mutubuah_est[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                $mutubuah_est[$key][$key1]['janjang_kosong'] = $empty;
                $mutubuah_est[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                $mutubuah_est[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                $mutubuah_est[$key][$key1]['vcut'] = $vcut;
                $mutubuah_est[$key][$key1]['vcut_persen'] = $skor_vcut;
                $mutubuah_est[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                $mutubuah_est[$key][$key1]['abnormal'] = $abr;

                $mutubuah_est[$key][$key1]['rat_dmg'] = $rd;

                $mutubuah_est[$key][$key1]['karung'] = $sum_kr;
                $mutubuah_est[$key][$key1]['TPH'] = $total_kr;
                $mutubuah_est[$key][$key1]['persen_krg'] = $per_kr;
                $mutubuah_est[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                $mutubuah_est[$key][$key1]['All_skor'] = $allSkor;
                $mutubuah_est[$key][$key1]['kategori'] = sidak_akhir($allSkor);
            }
        }


        // dd($mutu_buah);

        foreach ($mutu_buah as $key1 => $estates)  if (is_array($estates)) {
            $sortedData = array();
            $sortedDataEst = array();

            foreach ($estates as $estateName => $data) {
                if (is_array($data)) {
                    // dd($data);
                    $sortedDataEst[] = array(
                        'key1' => $key1,
                        'estateName' => $estateName,
                        'data' => $data
                    );
                    foreach ($data as $key2 => $scores) {
                        if (is_array($scores)) {
                            // dd($scores);
                            $sortedData[] = array(
                                'estateName' => $estateName,
                                'key2' => $key2,
                                'scores' => $scores
                            );
                        }
                    }
                }
            }

            //mengurutkan untuk nilai afd
            usort($sortedData, function ($a, $b) {
                return $b['scores']['All_skor'] - $a['scores']['All_skor'];
            });
            // //mengurutkan untuk nilai estate
            // usort($sortedDataEst, function ($a, $b) {
            //     return $b['data']['TotalSkorEST'] - $a['data']['TotalSkorEST'];
            // });

            //menambahkan nilai rank ke dalam afd
            $rank = 1;
            foreach ($sortedData as $sortedEstate) {
                $mutu_buah[$key1][$sortedEstate['estateName']][$sortedEstate['key2']]['rankAFD'] = $rank;
                $rank++;
            }




            // unset($sortedData, $sortedDataEst);
            unset($sortedData);
        }

        // dd($mutu_buah);
        foreach ($mutubuah_est as $key1 => $estates)  if (is_array($estates)) {

            $sortedDataEst = array();

            foreach ($estates as $estateName => $data) {
                if (is_array($data)) {
                    // dd($data);
                    $sortedDataEst[] = array(
                        'key1' => $key1,
                        'estateName' => $estateName,
                        'data' => $data
                    );
                }
            }

            // //mengurutkan untuk nilai estate
            usort($sortedDataEst, function ($a, $b) {
                return $b['data']['All_skor'] - $a['data']['All_skor'];
            });

            // //menambahkan nilai rank ke dalam estate
            $rank = 1;
            foreach ($sortedDataEst as $sortedest) {
                $mutubuah_est[$key1][$sortedest['estateName']]['rankEST'] = $rank;
                $rank++;
            }
            // unset($sortedData, $sortedDataEst);
            unset($sortedData);
        }
        $mutuBuah_wil = array();
        foreach ($mutubuah_est as $key => $value) {
            $jjg_sample = 0;
            $tnpBRD = 0;
            $krgBRD = 0;
            $abr = 0;
            $skor_total = 0;
            $overripe = 0;
            $empty = 0;
            $vcut = 0;
            $rd = 0;
            $sum_kr = 0;
            $allSkor = 0;
            $dataBLok = 0;
            foreach ($value as $key1 => $value1) {
                // dd($value2);
                $jjg_sample += $value1['Jumlah_janjang'];
                $tnpBRD += $value1['tnp_brd'];
                $krgBRD += $value1['krg_brd'];
                $abr += $value1['abnormal'];
                $overripe += $value1['lewat_matang'];
                $empty += $value1['janjang_kosong'];
                $vcut += $value1['vcut'];

                $rd += $value1['rat_dmg'];

                $dataBLok += $value1['blok'];
                $sum_kr += $value1['karung'];
            }

            if ($sum_kr != 0) {
                $total_kr = round($sum_kr / $dataBLok, 2);
            } else {
                $total_kr = 0;
            }
            $per_kr = round($total_kr * 100, 2);
            $skor_total = round(($jjg_sample - $abr != 0 ? (($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_jjgMSk = round(($jjg_sample - $abr != 0 ? (($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty)) / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_lewatMTng = round(($jjg_sample - $abr != 0 ? ($overripe / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_jjgKosong = round(($jjg_sample - $abr != 0 ? ($empty / ($jjg_sample - $abr)) * 100 : 0), 2);

            $skor_vcut = round(($jjg_sample != 0 ? ($vcut / $jjg_sample) * 100 : 0), 2);

            $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);


            $mutuBuah_wil[$key]['Jumlah_janjang'] = $jjg_sample;
            $mutuBuah_wil[$key]['blok'] = $dataBLok;
            switch ($key) {
                case 0:
                    $mutuBuah_wil[$key]['est'] = 'WIl-I';
                    break;
                case 1:
                    $mutuBuah_wil[$key]['est'] = 'WIl-II';
                    break;
                case 2:
                    $mutuBuah_wil[$key]['est'] = 'WIl-III';
                    break;
                case 3:
                    $mutuBuah_wil[$key]['est'] = 'Plasma1';
                    break;
                default:
                    $mutuBuah_wil[$key]['est'] = 'WIl' . $key;
                    break;
            }

            $mutuBuah_wil[$key]['afd'] = $key1;
            $mutuBuah_wil[$key]['nama_staff'] = '-';
            $mutuBuah_wil[$key]['tnp_brd'] = $tnpBRD;
            $mutuBuah_wil[$key]['krg_brd'] = $krgBRD;
            $mutuBuah_wil[$key]['persenTNP_brd'] = round(($jjg_sample - $abr != 0 ? ($tnpBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
            $mutuBuah_wil[$key]['persenKRG_brd'] = round(($jjg_sample - $abr != 0 ? ($krgBRD / ($jjg_sample - $abr)) * 100 : 0), 2);
            $mutuBuah_wil[$key]['abnormal_persen'] = round(($jjg_sample != 0 ? ($abr / $jjg_sample) * 100 : 0), 2);
            $mutuBuah_wil[$key]['rd_persen'] = round(($jjg_sample != 0 ? ($rd / $jjg_sample) * 100 : 0), 2);


            $mutuBuah_wil[$key]['total_jjg'] = $tnpBRD + $krgBRD;
            $mutuBuah_wil[$key]['persen_totalJjg'] = $skor_total;
            $mutuBuah_wil[$key]['skor_total'] = sidak_brdTotal($skor_total);
            $mutuBuah_wil[$key]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty);
            $mutuBuah_wil[$key]['persen_jjgMtang'] = $skor_jjgMSk;
            $mutuBuah_wil[$key]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
            $mutuBuah_wil[$key]['lewat_matang'] = $overripe;
            $mutuBuah_wil[$key]['persen_lwtMtng'] =  $skor_lewatMTng;
            $mutuBuah_wil[$key]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
            $mutuBuah_wil[$key]['janjang_kosong'] = $empty;
            $mutuBuah_wil[$key]['persen_kosong'] = $skor_jjgKosong;
            $mutuBuah_wil[$key]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
            $mutuBuah_wil[$key]['vcut'] = $vcut;
            $mutuBuah_wil[$key]['vcut_persen'] = $skor_vcut;
            $mutuBuah_wil[$key]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
            $mutuBuah_wil[$key]['abnormal'] = $abr;

            $mutuBuah_wil[$key]['rat_dmg'] = $rd;

            $mutuBuah_wil[$key]['karung'] = $sum_kr;
            $mutuBuah_wil[$key]['TPH'] = $total_kr;
            $mutuBuah_wil[$key]['persen_krg'] = $per_kr;
            $mutuBuah_wil[$key]['skor_kr'] = sidak_PengBRD($per_kr);
            $mutuBuah_wil[$key]['All_skor'] = $allSkor;
            $mutuBuah_wil[$key]['kategori'] = sidak_akhir($allSkor);
        }
        $sortedDataEst = array();
        foreach ($mutuBuah_wil as $key1 => $estates) {
            if (is_array($estates)) {
                $sortedDataEst[] = array(
                    'key1' => $key1,
                    'data' => $estates
                );
            }
        }

        usort($sortedDataEst, function ($a, $b) {
            return $b['data']['All_skor'] - $a['data']['All_skor'];
        });

        $rank = 1;
        foreach ($sortedDataEst as $sortedest) {
            $estateKey = $sortedest['key1'];
            $mutuBuah_wil[$estateKey]['rankWil'] = $rank;
            $rank++;
        }

        unset($sortedDataEst);

        $defaultMTbh = array();



        $arrView = array();

        $arrView['listregion'] =  $queryEste;
        $arrView['mutu_buah'] =  $mutu_buah;
        $arrView['mutubuah_est'] =  $mutubuah_est;
        $arrView['mutuBuah_wil'] =  $mutuBuah_wil;


        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }


    public function getYear(Request $request)
    {
        $year = $request->input('year');
        $RegData = $request->input('regData');

        // Process the input data here
        // dd($year, $RegData);

        // Return a response or redirect as needed

    }


    public function getYearData(Request $request)
    {
        $reg = $request->input('reg');
        $year = $request->input('tahun');

        $queryEste = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $reg)
            ->get();
        $queryEste = json_decode($queryEste, true);

        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);

        $queryMTbuah = DB::connection('mysql2')->table('sidak_mutu_buah')
            ->select(
                "sidak_mutu_buah.*",
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(sidak_mutu_buah.datetime, "%Y") as tahun')
            )
            // ->whereBetween('mutu_buah.datetime', [$startDate, $endDate])
            ->whereYear('datetime', $year)
            // ->whereBetween('sidak_mutu_buah.datetime', ['2023-04-03', '2023-04-09'])
            ->get();
        $queryMTbuah = $queryMTbuah->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($queryMTbuah, true);
        $databulananBuah = array();
        foreach ($queryMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {

                    $databulananBuah[$key][$key2][$key3] = $value3;
                }
            }
        }

        $defPerbulanWil = array();

        foreach ($queryEste as $key2 => $value2) {
            foreach ($queryAfd as $key3 => $value3) {
                if ($value2['est'] == $value3['est']) {
                    $defPerbulanWil[$value2['est']][$value3['nama']] = 0;
                }
            }
        }



        foreach ($defPerbulanWil as $estateKey => $afdelingArray) {
            foreach ($afdelingArray as $afdelingKey => $afdelingValue) {
                if (isset($databulananBuah[$estateKey]) && isset($databulananBuah[$estateKey][$afdelingKey])) {
                    $defPerbulanWil[$estateKey][$afdelingKey] = $databulananBuah[$estateKey][$afdelingKey];
                }
            }
        }
        function tesko($values)
        {
            foreach ($values as $value) {
                if ($value > 0) {
                    return true;
                }
            }
            return false;
        }
        // dd($defPerbulanWil);
        $sidak_buah = array();

        foreach ($defPerbulanWil as $key => $value) {
            $totalJJG = 0;
            $totaltnpBRD = 0;
            $totalkrgBRD = 0;
            $totalabr = 0;
            $TotPersenTNP = 0;
            $TotPersenKRG = 0;
            $totJJG = 0;
            $totPersenTOtaljjg = 0;
            $totSkor_total = 0;
            $totoverripe = 0;
            $totempty = 0;
            $totJJG_matang = 0;
            $totPer_jjgMtng = 0;
            $totPer_over = 0;
            $totSkor_Over = 0;
            $totPer_Empty = 0;
            $totSkor_Empty = 0;
            $totVcut = 0;
            $totPer_vcut =  0;
            $totSkor_Vcut =  0;
            $totPer_abr =  0;
            $totRD = 0;
            $totPer_rd = 0;
            $totBlok = 0;
            $totKR = 0;
            $tot_krS = 0;
            $totPer_kr = 0;
            $totSkor_kr = 0;
            $totALlskor = 0;
            $totKategor = 0;
            foreach ($value as $key1 => $value1) {
                $totSkor_jjgMtng = 0;
                if (is_array($value1)) {
                    $jjg_sample = 0;
                    $tnpBRD = 0;
                    $krgBRD = 0;
                    $abr = 0;
                    $skor_total = 0;
                    $overripe = 0;
                    $empty = 0;
                    $vcut = 0;
                    $rd = 0;
                    $sum_kr = 0;
                    $allSkor = 0;
                    $combination_counts = array();

                    foreach ($value1 as $key2 => $value2) {
                        $combination = $value2['blok'] . ' ' . $value2['estate'] . ' ' . $value2['afdeling'] . ' ' . $value2['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $jjg_sample += $value2['jumlah_jjg'];
                        $tnpBRD += $value2['bmt'];
                        $krgBRD += $value2['bmk'];
                        $abr += $value2['abnormal'];
                        $overripe += $value2['overripe'];
                        $empty += $value2['empty'];
                        $vcut += $value2['vcut'];
                        $rd += $value2['rd'];
                        $sum_kr += $value2['alas_br'];
                    }
                    $dataBLok = count($combination_counts);
                    if ($sum_kr != 0) {
                        $total_kr = round($sum_kr / $dataBLok, 2);
                    } else {
                        $total_kr = 0;
                    }
                    $per_kr = round($total_kr * 100, 2);
                    $skor_total = round((($tnpBRD + $krgBRD) / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgMSk = round(($jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty)) / ($jjg_sample - $abr) * 100, 2);
                    $skor_lewatMTng =  round(($overripe / ($jjg_sample - $abr)) * 100, 2);
                    $skor_jjgKosong =  round(($empty / ($jjg_sample - $abr)) * 100, 2);
                    $skor_vcut =   round(($vcut / $jjg_sample) * 100, 2);
                    $allSkor = sidak_brdTotal($skor_total) +  sidak_matangSKOR($skor_jjgMSk) +  sidak_lwtMatang($skor_lewatMTng) + sidak_jjgKosong($skor_jjgKosong) + sidak_tangkaiP($skor_vcut) + sidak_PengBRD($per_kr);

                    $sidak_buah[$key][$key1]['reg'] = 'REG-I';
                    $sidak_buah[$key][$key1]['pt'] = 'SSMS';
                    $sidak_buah[$key][$key1]['Jumlah_janjang'] = $jjg_sample;
                    $sidak_buah[$key][$key1]['blok'] = $dataBLok;
                    $sidak_buah[$key][$key1]['est'] = $key;
                    $sidak_buah[$key][$key1]['afd'] = $key1;
                    $sidak_buah[$key][$key1]['nama_staff'] = '-';
                    $sidak_buah[$key][$key1]['tnp_brd'] = $tnpBRD;
                    $sidak_buah[$key][$key1]['krg_brd'] = $krgBRD;
                    $sidak_buah[$key][$key1]['persenTNP_brd'] = round(($tnpBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['persenKRG_brd'] = round(($krgBRD / ($jjg_sample - $abr)) * 100, 2);
                    $sidak_buah[$key][$key1]['total_jjg'] = $tnpBRD + $krgBRD;
                    $sidak_buah[$key][$key1]['persen_totalJjg'] = $skor_total;
                    $sidak_buah[$key][$key1]['skor_total'] = sidak_brdTotal($skor_total);
                    $sidak_buah[$key][$key1]['jjg_matang'] = $jjg_sample - ($tnpBRD + $krgBRD + $overripe + $empty);
                    $sidak_buah[$key][$key1]['persen_jjgMtang'] = $skor_jjgMSk;
                    $sidak_buah[$key][$key1]['skor_jjgMatang'] = sidak_matangSKOR($skor_jjgMSk);
                    $sidak_buah[$key][$key1]['lewat_matang'] = $overripe;
                    $sidak_buah[$key][$key1]['persen_lwtMtng'] =  $skor_lewatMTng;
                    $sidak_buah[$key][$key1]['skor_lewatMTng'] = sidak_lwtMatang($skor_lewatMTng);
                    $sidak_buah[$key][$key1]['janjang_kosong'] = $empty;
                    $sidak_buah[$key][$key1]['persen_kosong'] = $skor_jjgKosong;
                    $sidak_buah[$key][$key1]['skor_kosong'] = sidak_jjgKosong($skor_jjgKosong);
                    $sidak_buah[$key][$key1]['vcut'] = $vcut;
                    $sidak_buah[$key][$key1]['vcut_persen'] = $skor_vcut;
                    $sidak_buah[$key][$key1]['vcut_skor'] = sidak_tangkaiP($skor_vcut);
                    $sidak_buah[$key][$key1]['abnormal'] = $abr;
                    $sidak_buah[$key][$key1]['abnormal_persen'] = round(($abr / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['rat_dmg'] = $rd;
                    $sidak_buah[$key][$key1]['rd_persen'] = round(($rd / $jjg_sample) * 100, 2);
                    $sidak_buah[$key][$key1]['TPH'] = $total_kr;
                    $sidak_buah[$key][$key1]['persen_krg'] = $per_kr;
                    $sidak_buah[$key][$key1]['skor_kr'] = sidak_PengBRD($per_kr);
                    $sidak_buah[$key][$key1]['All_skor'] = $allSkor;
                    $sidak_buah[$key][$key1]['kategori'] = sidak_akhir($allSkor);

                    $totalJJG += $jjg_sample;
                    $totaltnpBRD += $tnpBRD;
                    $totalkrgBRD += $krgBRD;
                    $totalabr += $abr;
                    $TotPersenTNP = round(($totaltnpBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $TotPersenKRG = round(($totalkrgBRD / ($totalJJG - $totalabr)) * 100, 2);
                    $totJJG = $totaltnpBRD + $totalkrgBRD;
                    $totPersenTOtaljjg = round((($totaltnpBRD + $totalkrgBRD) / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_total = sidak_brdTotal($totPersenTOtaljjg);
                    $totoverripe += $overripe;
                    $totempty += $empty;
                    $totJJG_matang = $totalJJG - ($totaltnpBRD + $totalkrgBRD + $totoverripe + $totempty);
                    $totPer_jjgMtng = round($totJJG_matang / ($totalJJG - $totalabr) * 100, 2);

                    $totSkor_jjgMtng = sidak_matangSKOR($totPer_jjgMtng);
                    $totPer_over = round(($totoverripe / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Over = sidak_lwtMatang($totPer_over);
                    $totPer_Empty = round(($totempty / ($totalJJG - $totalabr)) * 100, 2);
                    $totSkor_Empty = sidak_jjgKosong($totPer_Empty);
                    $totVcut += $vcut;
                    $totPer_vcut =   round(($totVcut / $totalJJG) * 100, 2);
                    $totSkor_Vcut =  sidak_tangkaiP($totPer_vcut);
                    $totPer_abr =  round(($totalabr / $totalJJG) * 100, 2);
                    $totRD += $rd;
                    $totPer_rd = round(($totRD / $totalJJG) * 100, 2);
                    $totBlok += $dataBLok;
                    $totKR += $sum_kr;
                    if ($totKR != 0) {
                        $tot_krS = round($totKR / $totBlok, 2);
                    } else {
                        $tot_krS = 0;
                    }
                    $totPer_kr = round($tot_krS * 100, 2);
                    $totSkor_kr = sidak_PengBRD($totPer_kr);
                    $totALlskor = sidak_brdTotal($totPersenTOtaljjg) + sidak_matangSKOR($totPer_jjgMtng) + sidak_lwtMatang($totPer_over) + sidak_jjgKosong($totPer_Empty) + sidak_tangkaiP($totPer_vcut) + sidak_PengBRD($totPer_kr);

                    $totKategor = sidak_akhir($totALlskor);
                }

                $totalValues = [

                    'reg' => '',
                    'pt' => '',
                    'nama_staff' => '',
                    'Jumlah_janjang' => $totalJJG,
                    'est' => '',
                    'afd' => '',
                    'tnp_brd' => $totaltnpBRD,
                    'krg_brd' => $totalkrgBRD,
                    'persenTNP_brd' => $TotPersenTNP,
                    'persenKRG_brd' => $TotPersenKRG,
                    'total_jjg' => $totJJG,
                    'persen_totalJjg' => $totPersenTOtaljjg,
                    'skor_total' => $totSkor_total,
                    'jjg_matang' => $totJJG_matang,
                    'persen_jjgMtang' => $totPer_jjgMtng,
                    'skor_jjgMatang' => $totSkor_jjgMtng,
                    'lewat_matang' => $totoverripe,
                    'persen_lwtMtng' => $totPer_over,
                    'skor_lewatMTng' => $totSkor_Over,
                    'janjang_kosong' => $totempty,
                    'persen_kosong' => $totPer_Empty,
                    'skor_kosong' => $totSkor_Empty,
                    'vcut' => $totVcut,
                    'vcut_persen' => $totPer_vcut,
                    'vcut_skor' => $totSkor_Vcut,
                    'abnormal' => $totalabr,
                    'abnormal_persen' => $totPer_abr,
                    'rat_dmg' => $totRD,
                    'rd_persen' => $totPer_rd,
                    'TPH' => $tot_krS,
                    'persen_krg' => $totPer_kr,
                    'skor_kr' => $totSkor_kr,
                    'All_skor' => $totALlskor,
                    'kategori' => $totKategor,
                    // Add more variables here
                ];

                if (tesko($totalValues)) {
                    $sidak_buah[$key][$key] = $totalValues;
                }
            }
        }

        $new_sidak_buah = array();

        foreach ($sidak_buah as $key => $value) {
            $new_subarray = array();

            foreach ($value as $sub_key => $sub_value) {
                if ($sub_key != $key) {
                    $new_subarray[$sub_key] = $sub_value;
                }
            }

            if (isset($value[$key])) {
                $new_subarray[$key] = $value[$key];
            }

            $new_sidak_buah[$key] = $new_subarray;
        }

        $sidak_buah = $new_sidak_buah;



        $arrView = array();

        $arrView['data_sidak'] =  $sidak_buah;

        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }

    public function findingIsueTahun(Request $request)
    {
        $reg = $request->input('reg');
        $year = $request->input('tahun');

        // Process the input data here
        // dd($reg, $year);

        // Return a response or redirect as needed

    }
}
