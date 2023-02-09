<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Random;

class inspectController extends Controller
{
    public function dashboard_inspeksi(Request $request)
    {


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

        $query = DB::connection('mysql2')->table('mutu_transport')->get();

        foreach ($query as $item) {
            $item->est_afd =  $item->estate . ' ' . $item->afdeling;
        }

        $query = $query->groupBy(function ($value2) {
            return $value2->est_afd;
        });

        $arrResult = array();
        $jm_tph = array();
        $inc = 0;
        foreach ($query as $key => $value) {
            $sum_bt = 0;
            $sum_jjg = 0;
            $arrResult[$inc]['est'] = $value[0]->estate;
            // $arrResult[$inc]['afd'] = $value[0]->afdeling;
            foreach ($value as $key2 => $value2) {
                $jm_tph[$inc][$value2->tph_baris][] = $value2;
                $sum_bt += $value2->bt;
                $sum_jjg += $value2->rst;
            }

            foreach ($jm_tph as $key3 => $value3) {
                $arrResult[$inc]['tph'] = count($value3);
            }
            $arrResult[$inc]['butir'] = $sum_bt;
            // $arrResult[$inc]['jjg'] = $sum_jjg;
            // $arrResult[$inc]['bt_tph'] = round($sum_bt / $arrResult[$inc]['tph'], 2);
            // $arrResult[$inc]['jjg_tph'] = round($sum_jjg / $arrResult[$inc]['tph'], 2);
            // if ($arrResult[$inc]['bt_tph'] <= 3) {
            //     $arrResult[$inc]['skor'] = 10;
            // } else if ($arrResult[$inc]['bt_tph'] <= 5) {
            //     $arrResult[$inc]['skor'] = 8;
            // } else if ($arrResult[$inc]['bt_tph'] <= 7) {
            //     $arrResult[$inc]['skor'] = 6;
            // } else if ($arrResult[$inc]['bt_tph'] <= 9) {
            //     $arrResult[$inc]['skor'] = 4;
            // } else if ($arrResult[$inc]['bt_tph'] <= 11) {
            //     $arrResult[$inc]['skor'] = 2;
            // } else {
            //     $arrResult[$inc]['skor'] = 0;
            // }
            $inc++;
        }
        // dd($arrResult);

        $queryEstate = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', 1)
            ->get();

        $queryEstate = json_decode($queryEstate, true);
        // dd($queryEstate);
        $dataRaw = array();
        $jm_tph = array();


        foreach ($queryEstate as $value) {
            $queryEstateTr = DB::connection('mysql2')->table('mutu_transport')
                ->select("mutu_transport.*")
                ->where('estate', $value['est'])
                ->where('datetime', 'like', '%' . $getDate . '%')
                ->orderBy('afdeling', 'asc')
                ->get();
            // dd($queryEstateTr);
            $sum_bt = 0;
            foreach ($queryEstateTr as $val3) {
                $jm_tph[$value['est']][$val3->afdeling][$val3->tph_baris][] = $val3;
                $sum_bt += $val3->bt;
                $dataRaw[$value['est']][$val3->afdeling]['bt_mt'] = $sum_bt;
            }

            foreach ($queryEstateTr as $val3) {
                foreach ($jm_tph as $value3) {
                    foreach ($value3 as $value4) {
                        // dd($value4);
                        $dataRaw[$value['est']][$val3->afdeling]['tph_mt'] = count($value4);
                    }
                }
            }
        }

        // $querySidak = DB::connection('mysql2')->table('mutu_transport')
        //     ->select("mutu_transport.*")
        //     ->where('datetime', 'like', '%' . $getDate . '%')
        //     ->get();
        $querySidak = DB::connection('mysql2')->table('mutu_transport')
            ->select("mutu_transport.*")
            // ->where('datetime', 'like', '%' . $getDate . '%')
            ->where('datetime', 'like', '%' . '2023-01' . '%')
            ->get();
        $DataEstate = $querySidak->groupBy(['estate', 'afdeling']);
        // dd($DataEstate);
        $DataEstate = json_decode($DataEstate, true);

        //menghitung buat table tampilkan pertahun
        $querytahun = DB::connection('mysql2')->table('mutu_ancak')
            ->select("mutu_ancak.*", DB::raw('DATE_FORMAT(mutu_ancak.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak.datetime, "%Y") as tahun'))
            ->whereYear('datetime', '2023')
            ->get();

        $querytahun = $querytahun->groupBy(['estate', 'afdeling']);

        $querytahun = json_decode($querytahun, true);

        // dd($querytahun);
        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db

            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();

        $queryAfd = json_decode($queryAfd, true);
        $queryEste = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        $queryEste = json_decode($queryEste, true);

        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $dataPerBulan = array();
        foreach ($querytahun as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $dataPerBulan)) {
                        $dataPerBulan[$month] = array();
                    }
                    if (!array_key_exists($key, $dataPerBulan[$month])) {
                        $dataPerBulan[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $dataPerBulan[$month][$key])) {
                        $dataPerBulan[$month][$key][$key2] = array();
                    }
                    $dataPerBulan[$month][$key][$key2][$key3] = $value3;
                }
            }
        }



        // dd($dataPerBulan);
        $defaultDataPerBulan = array();
        foreach ($bulan as $key => $value) {
            foreach ($queryEste as $key2 => $value2) {
                foreach ($queryAfd as $key3 => $value3) {
                    if ($value2['est'] == $value3['est']) {
                        $defaultDataPerBulan[$value][$value2['est']][$value3['nama']] = 0;
                        // $defaultDataPerBulan[$value][$value2['est']][$value] = 0;
                    }
                }
            }
        }


        foreach ($dataPerBulan as $key2 => $value2) {
            foreach ($value2 as $key3 => $value3) {
                foreach ($value3 as $key4 => $value4) {
                    // foreach ($defaultDataPerBulan[$key2][$key3][$key4] as $key => $value) {
                    $defaultDataPerBulan[$key2][$key3][$key4] = $value4;
                }
            }
        }




        $defaultNew = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($queryAfd as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defaultNew[$est['est']][$month][$afd['nama']] = 0;
                    }
                }
            }
        }

        foreach ($defaultNew as $estKey => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($dataPerBulan as $dataKey => $dataValue) {
                    if ($dataKey == $monthKey) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {
                            if ($dataEstKey == $estKey) {
                                $defaultNew[$estKey][$monthKey] = array_merge($monthValue, $dataEstValue);
                            }
                        }
                    }
                }
            }
        }

        ///perhitungan untuk table ngitung perhwilayah
        //membuat table wilayah untuk mutu ancak

        // dd($bulanMTancak);
        // dd($defaultDataPerBulan, $defaultNew);

        $dataTahunEst = array();
        foreach ($defaultNew as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) if (is_array($value3)) {
                    $akp = 0;
                    $skor_bTinggal = 0;
                    $brdPerjjg = 0;
                    $pokok_panen = 0;
                    $janjang_panen = 0;
                    $p_panen = 0;
                    $k_panen = 0;
                    $bhts_panen  = 0;
                    $bhtm1_panen  = 0;
                    $bhtm2_panen  = 0;
                    $bhtm3_oanen  = 0;
                    $ttlSkorMA = 0;
                    $listBlokPerAfd = array();
                    $jum_ha = 0;
                    $pelepah_s = 0;
                    foreach ($value3 as $key4 => $value4) if (is_array($value4)) {
                        if (!in_array($value4['estate'] . ' ' . $value4['afdeling'] . ' ' . $value4['blok'], $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $value4['estate'] . ' ' . $value4['afdeling'] . ' ' . $value4['blok'];
                        }
                        $jum_ha = count($listBlokPerAfd);

                        $pokok_panen = json_decode($value4["pokok_dipanen"], true);
                        $jajang_panen = json_decode($value4["jjg_dipanen"], true);
                        $brtp = json_decode($value4["brtp"], true);
                        $brtk = json_decode($value4["brtk"], true);
                        $brtgl = json_decode($value4["brtgl"], true);

                        $pokok_panen  = count($pokok_panen);
                        $janjang_panen = array_sum($jajang_panen);
                        $p_panen = array_sum($brtp);
                        $k_panen = array_sum($brtk);
                        $brtgl_panen = array_sum($brtgl);

                        // $akp = ($janjang_panen / $pokok_panen) %
                        $akp = ($janjang_panen / $pokok_panen) * 100;
                        $skor_bTinggal = $p_panen + $k_panen + $brtgl_panen;
                        $brdPerjjg = $skor_bTinggal / $pokok_panen;

                        //skore PEnggunnan Brondolan
                        $skor_brdPerjjg = 0;
                        if ($brdPerjjg <= 1.0) {
                            $skor_brdPerjjg = 20;
                        } else if ($brdPerjjg >= 1.5 && $brdPerjjg <= 2.0) {
                            $skor_brdPerjjg = 12;
                        } else if ($brdPerjjg >= 2.0 && $brdPerjjg <= 2.5) {
                            $skor_brdPerjjg = 8;
                        } else if ($brdPerjjg >= 2.5 && $brdPerjjg <= 3.0) {
                            $skor_brdPerjjg = 4;
                        } else if ($brdPerjjg >= 3.0 && $brdPerjjg <= 3.5) {
                            $skor_brdPerjjg = 0;
                        } else if ($brdPerjjg >= 4.0 && $brdPerjjg <= 4.5) {
                            $skor_brdPerjjg = 8;
                        } else if ($brdPerjjg >=  4.5 && $brdPerjjg <= 5.0) {
                            $skor_brdPerjjg = 12;
                        } else if ($brdPerjjg >=  5.0) {
                            $skor_brdPerjjg = 16;
                        }

                        // bagian buah tinggal
                        $bhts = json_decode($value4["bhts"], true);
                        $bhtm1 = json_decode($value4["bhtm1"], true);
                        $bhtm2 = json_decode($value4["bhtm2"], true);
                        $bhtm3 = json_decode($value4["bhtm3"], true);


                        $bhts_panen = array_sum($bhts);
                        $bhtm1_panen = array_sum($bhtm1);
                        $bhtm2_panen = array_sum($bhtm2);
                        $bhtm3_oanen = array_sum($bhtm3);

                        $sumBH = $bhts_panen +  $bhtm1_panen +  $bhtm2_panen +  $bhtm3_oanen;

                        $sumPerBH = $sumBH / ($janjang_panen + $sumBH) * 100;

                        $skor_bh = 0;
                        if ($sumPerBH <=  0.0) {
                            $skor_bh = 20;
                        } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                            $skor_bh = 18;
                        } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                            $skor_bh = 16;
                        } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                            $skor_bh = 12;
                        } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                            $skor_bh = 8;
                        } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                            $skor_bh = 4;
                        } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                            $skor_bh = 0;
                        } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                            $skor_bh = 0;
                        } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                            $skor_bh = 4;
                        } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                            $skor_bh = 8;
                        } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                            $skor_bh = 12;
                        } else if ($sumPerBH >= 5.0) {
                            $skor_bh = 10;
                        }
                        // data untuk pelepah sengklek

                        $ps = json_decode($value4["ps"], true);
                        $pelepah_s = array_sum($ps);

                        if ($pelepah_s != 0) {
                            $perPl = ($pokok_panen / $pelepah_s) * 100;
                        } else {
                            $perPl = 0;
                        }
                        $skor_perPl = 0;
                        if ($perPl <=  0.5) {
                            $skor_perPl = 5;
                        } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                            $skor_perPl = 4;
                        } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                            $skor_perPl = 3;
                        } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                            $skor_perPl = 2;
                        } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                            $skor_perPl = 1;
                        } else if ($perPl >= 2.5) {
                            $skor_perPl = 0;
                        }
                    }

                    $ttlSkorMA = $skor_brdPerjjg + $skor_bh + $skor_perPl;

                    $dataTahunEst[$key][$key2][$key3]['pokok_sample'] = $pokok_panen;
                    $dataTahunEst[$key][$key2][$key3]['ha_sample'] = $jum_ha;
                    $dataTahunEst[$key][$key2][$key3]['jumlah_panen'] = $janjang_panen;
                    $dataTahunEst[$key][$key2][$key3]['akp_rl'] =  number_format($akp, 2);

                    $dataTahunEst[$key][$key2][$key3]['p'] = $p_panen;
                    $dataTahunEst[$key][$key2][$key3]['k'] = $k_panen;
                    $dataTahunEst[$key][$key2][$key3]['tgl'] = $skor_bTinggal;

                    // $dataTahunEst[$key][$key2][$key3]['total_brd'] = $skor_bTinggal;
                    $dataTahunEst[$key][$key2][$key3]['brd/jjg'] = number_format($brdPerjjg, 2);

                    // data untuk buah tinggal
                    $dataTahunEst[$key][$key2][$key3]['bhts_s'] = $bhts_panen;
                    $dataTahunEst[$key][$key2][$key3]['bhtm1'] = $bhtm1_panen;
                    $dataTahunEst[$key][$key2][$key3]['bhtm2'] = $bhtm2_panen;
                    $dataTahunEst[$key][$key2][$key3]['bhtm3'] = $bhtm3_oanen;


                    // $dataTahunEst[$key][$key2][$key3]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek

                    $dataTahunEst[$key][$key2][$key3]['palepah_pokok'] = $pelepah_s;
                    // total skor akhir
                    $dataTahunEst[$key][$key2][$key3]['skor_bh'] = number_format($skor_bh, 2);
                    $dataTahunEst[$key][$key2][$key3]['skor_brd'] = number_format($skor_brdPerjjg, 2);
                    $dataTahunEst[$key][$key2][$key3]['skor_ps'] = number_format($skor_perPl, 2);
                    $dataTahunEst[$key][$key2][$key3]['skor_akhir'] = number_format($ttlSkorMA, 2);
                } else {
                    $dataTahunEst[$key][$key2][$key3]['pokok_sample'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['ha_sample'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['jumlah_panen'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['akp_rl'] = 0;

                    $dataTahunEst[$key][$key2][$key3]['p'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['k'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['tgl'] = 0;

                    // $dataTahunEst[$key][$key2][$key3]['total_brd'] = $skor_bTinggal;
                    $dataTahunEst[$key][$key2][$key3]['brd/jjg'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['skor_brd'] = 0;
                    // data untuk buah tinggal
                    $dataTahunEst[$key][$key2][$key3]['bhts_s'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['bhtm1'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['bhtm2'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['bhtm3'] = 0;

                    $dataTahunEst[$key][$key2][$key3]['skor_bh'] = 0;
                    // $dataTahunEst[$key][$key2][$key3]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek
                    $dataTahunEst[$key][$key2][$key3]['skor_ps'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['palepah_pokok'] = 0;
                    // total skor akhir
                    $dataTahunEst[$key][$key2][$key3]['skor_akhir'] = 0;
                }
            }
        }

        $FinalTahun = array();
        foreach ($dataTahunEst as $key => $value) {
            foreach ($value as $key1 => $value2) {
                $total_brd = 0;
                $total_buah = 0;
                $total_skor = 0;
                $sum_p = 0;
                $sum_k = 0;
                $sum_gl = 0;
                $sum_panen = 0;
                $total_BrdperJJG = 0;
                $sum_pokok = 0;
                $sum_Restan = 0;
                $sum_s = 0;
                $sum_m1 = 0;
                $sum_m2 = 0;
                $sum_m3 = 0;
                $sumPerBH = 0;

                $sum_pelepah = 0;
                $perPl = 0;
                foreach ($value2 as $key2 => $value3) {
                    $sum_panen += $value3['jumlah_panen'];
                    $sum_pokok += $value3['pokok_sample'];
                    //brondolamn
                    $sum_p += $value3['p'];
                    $sum_k += $value3['k'];
                    $sum_gl += $value3['tgl'];
                    //buah tianggal
                    $sum_s += $value3['bhts_s'];
                    $sum_m1 += $value3['bhtm1'];
                    $sum_m2 += $value3['bhtm2'];
                    $sum_m3 += $value3['bhtm3'];
                    //pelepah
                    $sum_pelepah += $value3['palepah_pokok'];
                }
                $total_brd = $sum_p + $sum_k + $sum_gl;
                $total_buah = $sum_s + $sum_m1 + $sum_m2 + $sum_m3;
                // $persenPalepah = $sum_palepah/$sum_pokok 




                if ($sum_panen != 0) {
                    $total_BrdperJJG = round($total_brd / $sum_panen, 2);
                } else {
                    $total_BrdperJJG = 0;
                }

                if ($sum_panen != 0) {
                    $sumPerBH = round($total_buah / ($sum_panen + $total_buah) * 100, 2);
                } else {
                    $sumPerBH = 0;
                }
                if ($sum_pelepah != 0) {
                    $perPl = round(($sum_pokok / $sum_pelepah) * 100, 2);
                } else {
                    $perPl = 0;
                }
                $skor_brdPerjjg = 0;
                $skor_perPl = 0;
                $skor_bh = 0;
                if ($total_BrdperJJG <= 1.0) {
                    $skor_brdPerjjg = 20;
                } else if ($total_BrdperJJG >= 1.5 && $total_BrdperJJG <= 2.0) {
                    $skor_brdPerjjg = 12;
                } else if ($total_BrdperJJG >= 2.0 && $total_BrdperJJG <= 2.5) {
                    $skor_brdPerjjg = 8;
                } else if ($total_BrdperJJG >= 2.5 && $total_BrdperJJG <= 3.0) {
                    $skor_brdPerjjg = 4;
                } else if ($total_BrdperJJG >= 3.0 && $total_BrdperJJG <= 3.5) {
                    $skor_brdPerjjg = 0;
                } else if ($total_BrdperJJG >= 4.0 && $total_BrdperJJG <= 4.5) {
                    $skor_brdPerjjg = 8;
                } else if ($total_BrdperJJG >=  4.5 && $total_BrdperJJG <= 5.0) {
                    $skor_brdPerjjg = 12;
                } else if ($total_BrdperJJG >=  5.0) {
                    $skor_brdPerjjg = 16;
                }


                if ($sumPerBH <=  0.0) {
                    $skor_bh = 20;
                } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                    $skor_bh = 18;
                } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                    $skor_bh = 16;
                } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                    $skor_bh = 12;
                } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                    $skor_bh = 8;
                } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                    $skor_bh = 4;
                } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                    $skor_bh = 0;
                } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                    $skor_bh = 0;
                } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                    $skor_bh = 4;
                } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                    $skor_bh = 8;
                } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                    $skor_bh = 12;
                } else if ($sumPerBH >= 5.0) {
                    $skor_bh = 10;
                }


                if ($perPl <=  0.5) {
                    $skor_perPl = 5;
                } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                    $skor_perPl = 4;
                } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                    $skor_perPl = 3;
                } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                    $skor_perPl = 2;
                } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                    $skor_perPl = 1;
                } else if ($perPl >= 2.5) {
                    $skor_perPl = 0;
                }

                $total_skor = $skor_brdPerjjg + $skor_bh + $skor_perPl;


                $FinalTahun[$key][$key1]['total_p.k.gl'] = $total_brd;
                $FinalTahun[$key][$key1]['total_jumPanen'] = $sum_panen;
                $FinalTahun[$key][$key1]['total_jumPokok'] = $sum_pokok;
                $FinalTahun[$key][$key1]['total_brd/jjg'] = $total_BrdperJJG;
                $FinalTahun[$key][$key1]['skor_brd'] = $skor_brdPerjjg;
                //buah tinggal
                $FinalTahun[$key][$key1]['s'] = $sum_s;
                $FinalTahun[$key][$key1]['m1'] = $sum_m1;
                $FinalTahun[$key][$key1]['m2'] = $sum_m2;
                $FinalTahun[$key][$key1]['m3'] = $sum_m3;
                $FinalTahun[$key][$key1]['total_bh'] = $total_buah;
                $FinalTahun[$key][$key1]['total_bh/jjg'] = $sumPerBH;
                $FinalTahun[$key][$key1]['skor_bh'] = $skor_bh;
                //palepah sengklek
                $FinalTahun[$key][$key1]['pokok_palepah'] = $sum_pelepah;
                $FinalTahun[$key][$key1]['perPalepah'] = $perPl;
                $FinalTahun[$key][$key1]['skor_perPl'] = $skor_perPl;
                //total skor akhir
                $FinalTahun[$key][$key1]['skor_final'] = $total_skor;
            }
        }
        // dd($FinalTahun);
        // end menghitung table untuk data pertahun
        $dataSkor = array();
        foreach ($DataEstate as $key => $value) {
            $skor_butir = 0;
            $skor_Restant = 0;
            foreach ($value as $key2 => $value2) {
                $sum_bt = 0;
                $sum_Restan = 0;
                $tph_sample = 0;
                $listBlokPerAfd = array();
                foreach ($value2 as $key3 => $value3) {
                    if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'], $listBlokPerAfd)) {
                        $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'];
                    }
                    $sum_Restan += $value3['rst'];
                    $tph_sample = count($listBlokPerAfd);
                    $sum_bt += $value3['bt'];
                }

                $skor_butir = round($sum_bt / $tph_sample, 2);
                $skor_restant = round($sum_Restan / $tph_sample);

                //menghitung skor butir
                $skor_butirTPH = 0;
                if ($skor_butir <= 3) {
                    $skor_butirTPH = 10;
                } else if ($skor_butir >= 3 && $skor_butir <= 5) {
                    $skor_butirTPH = 8;
                } else if ($skor_butir >= 5 && $skor_butir <= 7) {
                    $skor_butirTPH = 6;
                } else if ($skor_butir >= 7 && $skor_butir <= 9) {
                    $skor_butirTPH = 4;
                } else if ($skor_butir >= 9 && $skor_butir <= 11) {
                    $skor_butirTPH = 2;
                } else if ($skor_butir >= 11) {
                    $skor_butirTPH = 0;
                }
                //menghitung Skor Restant
                $skor_restantTPH = 0;
                if ($skor_restant <= 0.0) {
                    $skor_restantTPH = 10;
                } else if ($skor_restant >= 0.0 && $skor_restant <= 0.5) {
                    $skor_restantTPH = 8;
                } else if ($skor_restant >= 0.5 && $skor_restant <= 1) {
                    $skor_restantTPH = 6;
                } else if ($skor_restant >= 1.0 && $skor_restant <= 1.5) {
                    $skor_restantTPH = 4;
                } else if ($skor_restant >= 1.5 && $skor_restant <= 2.0) {
                    $skor_restantTPH = 2;
                } else if ($skor_restant >= 2.0 && $skor_restant <= 2.5) {
                    $skor_restantTPH = 0;
                } else if ($skor_restant >= 2.5 && $skor_restant <= 3.0) {
                    $skor_restantTPH = 2;
                } else if ($skor_restant >= 3.0 && $skor_restant <= 3.5) {
                    $skor_restantTPH = 4;
                } else if ($skor_restant >= 3.5 && $skor_restant <= 4.0) {
                    $skor_restantTPH = 6;
                } else if ($skor_restant >= 4.0) {
                    $skor_restantTPH = 8;
                }

                $dataSkor[$key][$key2][$key3]['bt_total'] = $sum_bt;
                $dataSkor[$key][$key2][$key3]['restan_total'] = $sum_Restan;
                $dataSkor[$key][$key2][$key3]['tph_sample'] = $tph_sample;
                $dataSkor[$key][$key2][$key3]['skor'] = $skor_butir;
                $dataSkor[$key][$key2][$key3]['skor_restan'] = $skor_restant;
                $dataSkor[$key][$key2][$key3]['skor_akhir'] = $skor_butirTPH;
                $dataSkor[$key][$key2][$key3]['skor_akhir_restan'] = $skor_restantTPH;
            }
        }
        // dd($dataSkor);
        // dd($arrResult, $dataRaw);

        $queryBuah = DB::connection('mysql2')->table('mutu_buah')
            ->select("mutu_buah.*")
            ->where('datetime', 'like', '%' . $getDate . '%')
            ->get();

        $DataMTbuah = $queryBuah->groupBy(['estate', 'afdeling']);

        $DataMTbuah = json_decode($DataMTbuah, true);
        // dd($DataMTbuah);

        $Mutubuah = array();

        foreach ($DataMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $listBlokPerAfd = array();
                $janjang = 0;

                $Jjg_Mth = 0;
                $Jjg_Mtng = 0;
                $Jjg_Over = 0;
                $Jjg_Empty = 0;
                $Jjg_Abr = 0;
                $Jjg_Vcut = 0;
                $Jjg_Als = 0;

                foreach ($value2 as $key3 => $value3) {
                    if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'], $listBlokPerAfd)) {
                        $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'];
                    }
                    $dataBLok = count($listBlokPerAfd);
                    $janjang += $value3['jumlah_jjg'];
                    $Jjg_Mth += $value3['bmt'];
                    $Jjg_Mtng += $value3['bmk'];
                    $Jjg_Over += $value3['overripe'];
                    $Jjg_Empty += $value3['empty'];
                    $Jjg_Abr += $value3['abnormal'];
                    $Jjg_Vcut += $value3['vcut'];
                    $Jjg_Als += $value3['alas_br'];
                }


                $PerMth = ($Jjg_Mth / ($janjang - $Jjg_Abr)) * 100;
                $PerMsk = ($Jjg_Mtng / ($janjang - $Jjg_Abr)) * 100;
                $PerOver = ($Jjg_Over / ($janjang - $Jjg_Abr)) * 100;
                $PerJanjang = ($Jjg_Empty / ($janjang - $Jjg_Abr)) * 100;
                $PerVcut = ($Jjg_Vcut / $janjang) * 100;
                $PerAbr = ($Jjg_Abr / $janjang) * 100;

                // (jjgvcut /total janjang sampel )%

                // skoring buah mentah
                $skor_PerMth = 0;
                if ($PerMth <= 1.0) {
                    $skor_PerMth = 10;
                } else if ($PerMth >= 1.0 && $PerMth <= 2.0) {
                    $skor_PerMth = 8;
                } else if ($PerMth >= 2.0 && $PerMth <= 3.0) {
                    $skor_PerMth = 6;
                } else if ($PerMth >= 3.0 && $PerMth <= 4.0) {
                    $skor_PerMth = 4;
                } else if ($PerMth >= 4.0 && $PerMth <= 5.0) {
                    $skor_PerMth = 2;
                } else if ($PerMth >= 5.0) {
                    $skor_PerMth = 0;
                }

                // skoring buah masak
                $skor_PerOver = 0;
                if ($PerOver <= 75.0) {
                    $skor_PerOver = 0;
                } else if ($PerOver >= 75.0 && $PerOver <= 80.0) {
                    $skor_PerOver = 1;
                } else if ($PerOver >= 80.0 && $PerOver <= 85.0) {
                    $skor_PerOver = 2;
                } else if ($PerOver >= 85.0 && $PerOver <= 90.0) {
                    $skor_PerOver = 3;
                } else if ($PerOver >= 90.0 && $PerOver <= 95.0) {
                    $skor_PerOver = 4;
                } else if ($PerOver >= 95.0) {
                    $skor_PerOver = 5;
                }


                // skoring buah over
                $skor_PerMsk = 0;
                if ($PerMsk <= 2.0) {
                    $skor_PerMsk = 5;
                } else if ($PerMsk >= 2.0 && $PerMsk <= 4.0) {
                    $skor_PerMsk = 4;
                } else if ($PerMsk >= 4.0 && $PerMsk <= 6.0) {
                    $skor_PerMsk = 3;
                } else if ($PerMsk >= 6.0 && $PerMsk <= 8.0) {
                    $skor_PerMsk = 2;
                } else if ($PerMsk >= 8.0 && $PerMsk <= 10.0) {
                    $skor_PerMsk = 1;
                } else if ($PerMsk >= 10.0) {
                    $skor_PerMsk = 0;
                }


                //skor janjang kosong
                $skor_PerJanjang = 0;
                if ($PerJanjang <= 1.0) {
                    $skor_PerJanjang = 5;
                } else if ($PerJanjang >= 1.0 && $PerJanjang <= 2.0) {
                    $skor_PerJanjang = 4;
                } else if ($PerJanjang >= 2.0 && $PerJanjang <= 3.0) {
                    $skor_PerJanjang = 3;
                } else if ($PerJanjang >= 3.0 && $PerJanjang <= 4.0) {
                    $skor_PerJanjang = 2;
                } else if ($PerJanjang >= 4.0 && $PerJanjang <= 5.0) {
                    $skor_PerJanjang = 1;
                } else if ($PerJanjang >= 5.0) {
                    $skor_PerJanjang = 0;
                }

                //skore Vcut
                $skor_PerVcut = 0;
                if ($PerVcut <= 2.0) {
                    $skor_PerVcut = 5;
                } else if ($PerVcut >= 2.0 && $PerVcut <= 4.0) {
                    $skor_PerVcut = 4;
                } else if ($PerVcut >= 4.0 && $PerVcut <= 6.0) {
                    $skor_PerVcut = 3;
                } else if ($PerVcut >= 6.0 && $PerVcut <= 8.0) {
                    $skor_PerVcut = 2;
                } else if ($PerVcut >= 8.0 && $PerVcut <= 10.0) {
                    $skor_PerVcut = 1;
                } else if ($PerVcut >= 10.0) {
                    $skor_PerVcut = 0;
                }

                // blum di cek skornya di bawah
                //skore PEnggunnan Brondolan
                $skor_PerAbr = 0;
                if ($PerAbr <= 75.0) {
                    $skor_PerAbr = 0;
                } else if ($PerAbr >= 75.0 && $PerAbr <= 80.0) {
                    $skor_PerAbr = 1;
                } else if ($PerAbr >= 80.0 && $PerAbr <= 85.0) {
                    $skor_PerAbr = 2;
                } else if ($PerAbr >= 85.0 && $PerAbr <= 90.0) {
                    $skor_PerAbr = 3;
                } else if ($PerAbr >= 90.0 && $PerAbr <= 95.0) {
                    $skor_PerAbr = 4;
                } else if ($PerAbr >= 95.0) {
                    $skor_PerAbr = 5;
                }

                $Mutubuah[$key][$key2][$key3]['jml_blok'] = $dataBLok;
                $Mutubuah[$key][$key2][$key3]['jml_janjang'] = $janjang;
                $Mutubuah[$key][$key2][$key3]['jml_mentah'] = $Jjg_Mth;
                $Mutubuah[$key][$key2][$key3]['jml_masak'] = $Jjg_Mtng;
                $Mutubuah[$key][$key2][$key3]['jml_over'] = $Jjg_Over;
                $Mutubuah[$key][$key2][$key3]['jml_empty'] = $Jjg_Empty;
                $Mutubuah[$key][$key2][$key3]['jml_abnormal'] = $Jjg_Abr;
                $Mutubuah[$key][$key2][$key3]['jml_vcut'] = $Jjg_Vcut;
                $Mutubuah[$key][$key2][$key3]['jml_alas_br'] = $Jjg_Als;

                $Mutubuah[$key][$key2][$key3]['PersenBuahMentah'] = number_format($PerMth, 2);
                $Mutubuah[$key][$key2][$key3]['PersenBuahMasak'] = number_format($PerMsk, 2);
                $Mutubuah[$key][$key2][$key3]['PersenBuahOver'] = number_format($PerOver, 2);
                $Mutubuah[$key][$key2][$key3]['PersenPerJanjang'] = number_format($PerJanjang, 2);
                $Mutubuah[$key][$key2][$key3]['PersenVcut'] = number_format($PerVcut, 2);
                $Mutubuah[$key][$key2][$key3]['PersenAbr'] = number_format($PerAbr, 2);
                $Mutubuah[$key][$key2][$key3]['Skor_mentah'] = $skor_PerMth;
                $Mutubuah[$key][$key2][$key3]['Skor_masak'] = $skor_PerMsk;
                $Mutubuah[$key][$key2][$key3]['Skor_over'] = $skor_PerOver;
                $Mutubuah[$key][$key2][$key3]['Skor_PerJanjang'] = $skor_PerJanjang;
                $Mutubuah[$key][$key2][$key3]['Skore_Vcut'] = $skor_PerVcut;
                // $Mutubuah[$key][$key2][$key3]['Skore_Abnormal'] = $skor_PerAbr;
            }
        }

        // dd($Mutubuah);

        $queryMtAncak = DB::connection('mysql2')->table('mutu_ancak')
            ->select("mutu_ancak.*")
            ->where('datetime', 'like', '%' . $getDate . '%')
            ->get();
        $DataMtAncak = $queryMtAncak->groupBy(['estate', 'afdeling']);
        $DataMtAncak = json_decode($DataMtAncak, true);
        // dd($DataMtAncak);

        $MutuAncak = array();
        foreach ($DataMtAncak as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $akp = 0;
                $skor_bTinggal = 0;
                $brdPerjjg = 0;
                $pokok_panen = 0;
                $janjang_panen = 0;
                $p_panen = 0;
                $k_panen = 0;
                $listBlokPerAfd = array();
                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3)) {
                        if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['estate'] . ' ' . $value3['blok'];
                        }
                        $jum_ha = count($listBlokPerAfd);
                        $pokok_panen = json_decode($value3["pokok_dipanen"], true);
                        $jajang_panen = json_decode($value3["jjg_dipanen"], true);
                        $brtp = json_decode($value3["brtp"], true);
                        $brtk = json_decode($value3["brtk"], true);
                        $brtgl = json_decode($value3["brtgl"], true);

                        $pokok_panen  = count($pokok_panen);
                        $janjang_panen = array_sum($jajang_panen);
                        $p_panen = array_sum($brtp);
                        $k_panen = array_sum($brtk);
                        $brtgl_panen = array_sum($brtgl);

                        // $akp = ($janjang_panen / $pokok_panen) %
                        $akp = ($janjang_panen / $pokok_panen) * 100;
                        $skor_bTinggal = $p_panen + $k_panen + $brtgl_panen;
                        $brdPerjjg = $skor_bTinggal / $pokok_panen;

                        //skore PEnggunnan Brondolan
                        $skor_brdPerjjg = 0;
                        if ($brdPerjjg <= 1.0) {
                            $skor_brdPerjjg = 20;
                        } else if ($brdPerjjg >= 1.5 && $brdPerjjg <= 2.0) {
                            $skor_brdPerjjg = 12;
                        } else if ($brdPerjjg >= 2.0 && $brdPerjjg <= 2.5) {
                            $skor_brdPerjjg = 8;
                        } else if ($brdPerjjg >= 2.5 && $brdPerjjg <= 3.0) {
                            $skor_brdPerjjg = 4;
                        } else if ($brdPerjjg >= 3.0 && $brdPerjjg <= 3.5) {
                            $skor_brdPerjjg = 0;
                        } else if ($brdPerjjg >= 4.0 && $brdPerjjg <= 4.5) {
                            $skor_brdPerjjg = 8;
                        } else if ($brdPerjjg >=  4.5 && $brdPerjjg <= 5.0) {
                            $skor_brdPerjjg = 12;
                        } else if ($brdPerjjg >=  5.0) {
                            $skor_brdPerjjg = 16;
                        }

                        $bhts = json_decode($value3["bhts"], true);
                        $bhtm1 = json_decode($value3["bhtm1"], true);
                        $bhtm2 = json_decode($value3["bhtm2"], true);
                        $bhtm3 = json_decode($value3["bhtm3"], true);


                        $bhts_panen = array_sum($bhts);
                        $bhtm1_panen = array_sum($bhtm1);
                        $bhtm2_panen = array_sum($bhtm2);
                        $bhtm3_oanen = array_sum($bhtm3);

                        $sumBH = $bhts_panen +  $bhtm1_panen +  $bhtm2_panen +  $bhtm3_oanen;

                        $sumPerBH = $sumBH / ($janjang_panen + $sumBH) * 100;

                        $skor_bh = 0;
                        if ($sumPerBH <=  0.0) {
                            $skor_bh = 20;
                        } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                            $skor_bh = 18;
                        } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                            $skor_bh = 16;
                        } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                            $skor_bh = 12;
                        } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                            $skor_bh = 8;
                        } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                            $skor_bh = 4;
                        } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                            $skor_bh = 0;
                        } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                            $skor_bh = 0;
                        } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                            $skor_bh = 4;
                        } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                            $skor_bh = 8;
                        } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                            $skor_bh = 12;
                        } else if ($sumPerBH >= 5.0) {
                            $skor_bh = 10;
                        }
                    }
                    // data untuk pelepah sengklek
                    $ps = json_decode($value3["ps"], true);
                    $pelepah_s = array_sum($ps);
                    if ($pelepah_s != 0) {
                        $perPl = ($pokok_panen / $pelepah_s) * 100;
                    } else {
                        $perPl = 0;
                    }
                    $skor_perPl = 0;
                    if ($perPl <=  0.5) {
                        $skor_perPl = 5;
                    } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                        $skor_perPl = 4;
                    } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                        $skor_perPl = 3;
                    } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                        $skor_perPl = 2;
                    } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                        $skor_perPl = 1;
                    } else if ($perPl >= 2.5) {
                        $skor_perPl = 0;
                    }
                }

                $MutuAncak[$key][$key2][$key3]['pokok_sample'] = $pokok_panen;
                $MutuAncak[$key][$key2][$key3]['jum_ha'] = $jum_ha;
                $MutuAncak[$key][$key2][$key3]['jumlah_panen'] = $janjang_panen;
                $MutuAncak[$key][$key2][$key3]['akp_rl'] =  number_format($akp, 2);
                $MutuAncak[$key][$key2][$key3]['p'] = $p_panen;
                $MutuAncak[$key][$key2][$key3]['k'] = $k_panen;
                $MutuAncak[$key][$key2][$key3]['tgl'] = $brtgl_panen;
                $MutuAncak[$key][$key2][$key3]['total_brd'] = $skor_bTinggal;
                $MutuAncak[$key][$key2][$key3]['brd/jjg'] = number_format($brdPerjjg, 2);
                $MutuAncak[$key][$key2][$key3]['skor_brd'] = number_format($skor_brdPerjjg, 2);

                $MutuAncak[$key][$key2][$key3]['s'] = $bhts_panen;
                $MutuAncak[$key][$key2][$key3]['m1'] = $bhtm1_panen;
                $MutuAncak[$key][$key2][$key3]['m2'] = $bhtm2_panen;
                $MutuAncak[$key][$key2][$key3]['m3'] = $bhtm3_oanen;
                $MutuAncak[$key][$key2][$key3]['total_jjg'] = $sumBH;
                $MutuAncak[$key][$key2][$key3]['jjg/ji'] = number_format($sumPerBH, 2);
                $MutuAncak[$key][$key2][$key3]['skor_bhTgl'] = $skor_bh;

                $MutuAncak[$key][$key2][$key3]['jjgPS'] = $pelepah_s;
                $MutuAncak[$key][$key2][$key3]['perPl'] =  number_format($perPl, 2);
                $MutuAncak[$key][$key2][$key3]['skor_perPl'] = $skor_perPl;
            }
        }

        // dd($MutuAncak);

        $firstWeek = $request->get('start');
        $lastWeek = $request->get('finish');
        // 

        // backend untuk halaman utama
        $queryWill = DB::connection('mysql2')->table('wil')->whereIn('regional', [1])->get();;
        $queryEste = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        $queryEste = $queryEste->groupBy(function ($item) {
            return $item->wil;
        });
        $queryAfd = DB::connection('mysql2')->Table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryMtAncak = DB::connection('mysql2')->table('mutu_ancak')
            ->select("mutu_ancak.*")
            ->where('datetime', 'like', '%' . $getDate . '%')
            // ->where('datetime', 'like', '%' . "2023-02" . '%')

            // ->whereBetween('sidak_tph.datetime', ['2023-01-23', '202-12-25'])
            ->get();


        $queryMtAncak = json_decode($queryMtAncak, true);
        $queryAfd = json_decode($queryAfd, true);
        $queryEste = json_decode($queryEste, true);
        $queryWill = json_decode($queryWill, true);

        $dataAfdEst = array();
        // menyimpan array nested dari  wil -> est -> afd
        foreach ($queryEste as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($queryAfd as $key3 => $value3) {
                    $est = $value2['est'];
                    $afd = $value3['nama'];
                    // dd($value3);
                    if ($value2['est'] == $value3['est']) {
                        foreach ($queryMtAncak as $key4 => $value4) {
                            // dd($value4);
                            if (($value2['est'] == $value4['estate']) && $value3['nama'] == $value4['afdeling']) {
                                $dataAfdEst[$est][$afd][] = $value4;
                            } else {
                                $dataAfdEst[$est][$afd]['null'] = 0;
                            }
                        }
                    }
                }
            }
        }

        $DataTable1 = array();
        foreach ($dataAfdEst as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $akp = 0;
                $skor_bTinggal = 0;
                $brdPerjjg = 0;
                $pokok_panen = 0;
                $janjang_panen = 0;
                $p_panen = 0;
                $k_panen = 0;
                $bhts_panen  = 0;
                $bhtm1_panen  = 0;
                $bhtm2_panen  = 0;
                $bhtm3_oanen  = 0;
                $ttlSkorMA = 0;
                $listBlokPerAfd = array();
                $jum_ha = 0;
                $pelepah_s = 0;
                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3)) {
                        if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'];
                        }
                        $jum_ha = count($listBlokPerAfd);

                        // bagian brondolan tingaal

                        $pokok_panen = json_decode($value3["pokok_dipanen"], true);
                        $jajang_panen = json_decode($value3["jjg_dipanen"], true);
                        $brtp = json_decode($value3["brtp"], true);
                        $brtk = json_decode($value3["brtk"], true);
                        $brtgl = json_decode($value3["brtgl"], true);
                        $pokok_panen  = count($pokok_panen);
                        $janjang_panen = array_sum($jajang_panen);
                        $p_panen = array_sum($brtp);
                        $k_panen = array_sum($brtk);
                        $brtgl_panen = array_sum($brtgl);

                        // $akp = ($janjang_panen / $pokok_panen) %
                        $akp = ($janjang_panen / $pokok_panen) * 100;
                        $skor_bTinggal = $p_panen + $k_panen + $brtgl_panen;
                        $brdPerjjg = $skor_bTinggal / $pokok_panen;

                        //skore PEnggunnan Brondolan
                        $skor_brdPerjjg = 0;
                        if ($brdPerjjg <= 1.0) {
                            $skor_brdPerjjg = 20;
                        } else if ($brdPerjjg >= 1.5 && $brdPerjjg <= 2.0) {
                            $skor_brdPerjjg = 12;
                        } else if ($brdPerjjg >= 2.0 && $brdPerjjg <= 2.5) {
                            $skor_brdPerjjg = 8;
                        } else if ($brdPerjjg >= 2.5 && $brdPerjjg <= 3.0) {
                            $skor_brdPerjjg = 4;
                        } else if ($brdPerjjg >= 3.0 && $brdPerjjg <= 3.5) {
                            $skor_brdPerjjg = 0;
                        } else if ($brdPerjjg >= 4.0 && $brdPerjjg <= 4.5) {
                            $skor_brdPerjjg = 8;
                        } else if ($brdPerjjg >=  4.5 && $brdPerjjg <= 5.0) {
                            $skor_brdPerjjg = 12;
                        } else if ($brdPerjjg >=  5.0) {
                            $skor_brdPerjjg = 16;
                        }

                        // bagian buah tinggal
                        $bhts = json_decode($value3["bhts"], true);
                        $bhtm1 = json_decode($value3["bhtm1"], true);
                        $bhtm2 = json_decode($value3["bhtm2"], true);
                        $bhtm3 = json_decode($value3["bhtm3"], true);


                        $bhts_panen = array_sum($bhts);
                        $bhtm1_panen = array_sum($bhtm1);
                        $bhtm2_panen = array_sum($bhtm2);
                        $bhtm3_oanen = array_sum($bhtm3);

                        $sumBH = $bhts_panen +  $bhtm1_panen +  $bhtm2_panen +  $bhtm3_oanen;

                        $sumPerBH = $sumBH / ($janjang_panen + $sumBH) * 100;

                        $skor_bh = 0;
                        if ($sumPerBH <=  0.0) {
                            $skor_bh = 20;
                        } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                            $skor_bh = 18;
                        } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                            $skor_bh = 16;
                        } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                            $skor_bh = 12;
                        } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                            $skor_bh = 8;
                        } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                            $skor_bh = 4;
                        } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                            $skor_bh = 0;
                        } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                            $skor_bh = 0;
                        } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                            $skor_bh = 4;
                        } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                            $skor_bh = 8;
                        } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                            $skor_bh = 12;
                        } else if ($sumPerBH >= 5.0) {
                            $skor_bh = 10;
                        }
                        // data untuk pelepah sengklek

                        $ps = json_decode($value3["ps"], true);

                        $pelepah_s = array_sum($ps);
                        if ($pelepah_s != 0) {
                            $perPl = ($pokok_panen / $pelepah_s) * 100;
                        } else {
                            $perPl = 0;
                        }
                        $skor_perPl = 0;
                        if ($perPl <=  0.5) {
                            $skor_perPl = 5;
                        } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                            $skor_perPl = 4;
                        } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                            $skor_perPl = 3;
                        } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                            $skor_perPl = 2;
                        } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                            $skor_perPl = 1;
                        } else if ($perPl >= 2.5) {
                            $skor_perPl = 0;
                        }
                    }
                }
                $ttlSkorMA = $skor_brdPerjjg + $skor_bh + $skor_perPl;
                $DataTable1[$key][$key2][$key3]['pokok_sample'] = $pokok_panen;
                $DataTable1[$key][$key2][$key3]['ha_sample'] = $jum_ha;
                $DataTable1[$key][$key2][$key3]['jumlah_panen'] = $janjang_panen;
                $DataTable1[$key][$key2][$key3]['akp_rl'] =  number_format($akp, 2);


                $DataTable1[$key][$key2][$key3]['p'] = $p_panen;
                $DataTable1[$key][$key2][$key3]['k'] = $k_panen;
                $DataTable1[$key][$key2][$key3]['tgl'] = $skor_bTinggal;

                // $DataTable1[$key][$key2][$key3]['total_brd'] = $skor_bTinggal;
                $DataTable1[$key][$key2][$key3]['brd/jjg'] = number_format($brdPerjjg, 2);
                $DataTable1[$key][$key2][$key3]['skor_brd'] = number_format($skor_brdPerjjg, 2);
                // data untuk buah tinggal
                $DataTable1[$key][$key2][$key3]['bhts_s'] = $bhts_panen;
                $DataTable1[$key][$key2][$key3]['bhtm1'] = $bhtm1_panen;
                $DataTable1[$key][$key2][$key3]['bhtm2'] = $bhtm2_panen;
                $DataTable1[$key][$key2][$key3]['bhtm3'] = $bhtm3_oanen;

                $DataTable1[$key][$key2][$key3]['skor_bh'] = number_format($skor_bh, 2);
                // $DataTable1[$key][$key2][$key3]['jjgperBuah'] = number_format($sumPerBH, 2);
                // data untuk pelepah sengklek
                $DataTable1[$key][$key2][$key3]['skor_ps'] = number_format($skor_perPl, 2);
                $DataTable1[$key][$key2][$key3]['total_pelepah'] = $pelepah_s;
                // total skor akhir
                $DataTable1[$key][$key2][$key3]['skor_akhir'] = number_format($ttlSkorMA, 2);
            }
        }



        // dd($DataTable1);
        foreach ($queryEste as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($DataTable1 as $key3 => $value3) {
                    if ($value2['est'] == $key3) {
                        $dataPerWil[$key][$key3] = $value3;
                    }
                }
            }
        }
        // dd($dataPerWil);
        foreach ($dataPerWil as $key1 => $estates) {
            $sortedData = array();
            foreach ($estates as $estateName => $data) {
                foreach ($data as $key2 => $scores) {
                    foreach ($scores as $key3 => $value) {
                        $sortedData[] = array(
                            'estateName' => $estateName,
                            'key2' => $key2,
                            'value' => $value
                        );
                    }
                }
            }
            // Sort the new array based on skor_akhir
            usort($sortedData, function ($a, $b) {
                return $b['value']['skor_akhir'] - $a['value']['skor_akhir'];
            });

            // Assign rank to the original data
            $rank = 1;
            foreach ($sortedData as $sortedEstate) {
                $dataPerWil[$key1][$sortedEstate['estateName']][$sortedEstate['key2']]['rank'] = $rank;
                $rank++;
            }
            unset($sortedData);
        }

        // dd($dataPerWil);
        //membuat tabel utama untuk tiap estate total
        $TotalperEstate = array();
        foreach ($dataPerWil as $key => $value) {
            foreach ($value as $key2 => $value2) {

                $sum_p = 0;
                $sum_k = 0;
                $sum_tgl = 0;
                $sum_ttlPanen = 0;
                $sum_bhts = 0;
                $sum_bhtm1 = 0;
                $sum_bhtm2 = 0;
                $sum_bhtm3 = 0;
                $total_brd = 0;
                $total_buah = 0;
                $sum_pelepah = 0;
                $sum_ttlPokok = 0;
                $total_skor = 0;
                $sum_ha_sample = 0;
                $brdperjjg = 0;
                $persenPalepah = 0;
                foreach ($value2 as $key3 => $value3) {
                    foreach ($value3 as $key4 => $value4) {
                        if (is_array($value4)) {
                            $sum_ttlPanen += $value4['jumlah_panen'];
                            $sum_ttlPokok += $value4['pokok_sample'];
                            $sum_ha_sample += $value4['ha_sample'];


                            $sum_p += $value4['p'];
                            $sum_k += $value4['k'];
                            $sum_tgl += $value4['tgl'];

                            $sum_bhts += $value4['bhts_s'];
                            $sum_bhtm1 += (int) $value4['bhtm1'];
                            $sum_bhtm2 += (int)$value4['bhtm2'];
                            $sum_bhtm3 += (int)$value4['bhtm3'];

                            $sum_pelepah += (int)$value4['total_pelepah'];

                            // $TotalperEstate[$key][$key2]['total_p'] += $value4['p'];
                            // $TotalperEstate[$key][$key2]['total_k'] += $value4['k'];
                            // $TotalperEstate[$key][$key2]['total_tinggal'] += $value4['tgl'];
                            // $TotalperEstate[$key][$key2]['total_panen'] += $value4['jumlah_panen'];

                            // $TotalperEstate[$key][$key2]['bhts'] += $value4['bhts_s'];
                            // $TotalperEstate[$key][$key2]['bhtm1'] +=  (int) $value4['bhtm1'];
                            // $TotalperEstate[$key][$key2]['bhtm2'] +=  (int) $value4['bhtm2'];
                            // $TotalperEstate[$key][$key2]['bhtm3'] +=  (int) $value4['bhtm3'];
                        }
                    }

                    $total_brd = $sum_p + $sum_k + $sum_tgl;
                    $total_buah = $sum_bhts + $sum_bhtm1 + $sum_bhtm2 + $sum_bhtm3;





                    if ($sum_ttlPanen != 0) {
                        $brdperjjg = $total_brd / $sum_ttlPanen;
                    } else {
                        $brdperjjg = 0;
                    }


                    if ($sum_ttlPokok != 0) {
                        $persenPalepah = ($sum_ttlPokok / $sum_pelepah) * 100;
                    } else {
                        $persenPalepah = 0;
                    }

                    $buahperjjg = 0;
                    if ($sum_ttlPanen != 0) {
                        $buahperjjg = $total_buah / ($sum_ttlPanen + $total_buah) * 100;
                    } else {
                        $buahperjjg = 0;
                    }

                    $skor_brd = 0;
                    if ($brdperjjg <= 1.0) {
                        $skor_brd = 20;
                    } else if ($brdperjjg >= 1.5 && $brdperjjg <= 2.0) {
                        $skor_brd = 12;
                    } else if ($brdperjjg >= 2.0 && $brdperjjg <= 2.5) {
                        $skor_brd = 8;
                    } else if ($brdperjjg >= 2.5 && $brdperjjg <= 3.0) {
                        $skor_brd = 4;
                    } else if ($brdperjjg >= 3.0 && $brdperjjg <= 3.5) {
                        $skor_brd = 0;
                    } else if ($brdperjjg >= 4.0 && $brdperjjg <= 4.5) {
                        $skor_brd = 8;
                    } else if ($brdperjjg >=  4.5 && $brdperjjg <= 5.0) {
                        $skor_brd = 12;
                    } else if ($brdperjjg >=  5.0) {
                        $skor_brd = 16;
                    }

                    //buah tinggal
                    $skor_bh = 0;
                    if ($buahperjjg <=  0.0) {
                        $skor_bh = 20;
                    } else if ($buahperjjg >=  0.0 && $buahperjjg <= 1.0) {
                        $skor_bh = 18;
                    } else if ($buahperjjg >= 1 && $buahperjjg <= 1.5) {
                        $skor_bh = 16;
                    } else if ($buahperjjg >= 1.5 && $buahperjjg <= 2.0) {
                        $skor_bh = 12;
                    } else if ($buahperjjg >= 2.0 && $buahperjjg <= 2.5) {
                        $skor_bh = 8;
                    } else if ($buahperjjg >= 2.5 && $buahperjjg <= 3.0) {
                        $skor_bh = 4;
                    } else if ($buahperjjg >= 3.0 && $buahperjjg <= 3.5) {
                        $skor_bh = 0;
                    } else if ($buahperjjg >=  3.5 && $buahperjjg <= 3.5) {
                        $skor_bh = 0;
                    } else if ($buahperjjg >= 3.5 && $buahperjjg <= 4.0) {
                        $skor_bh = 4;
                    } else if ($buahperjjg >= 4.0 && $buahperjjg <= 4.5) {
                        $skor_bh = 8;
                    } else if ($buahperjjg >= 4.5 && $buahperjjg <= 5.0) {
                        $skor_bh = 12;
                    } else if ($buahperjjg >= 5.0) {
                        $skor_bh = 10;
                    }

                    $skor_perPl = 0;
                    if ($persenPalepah <=  0.5) {
                        $skor_perPl = 5;
                    } else if ($persenPalepah >=  0.5 && $persenPalepah <= 1.0) {
                        $skor_perPl = 4;
                    } else if ($persenPalepah >= 1.0 && $persenPalepah <= 1.5) {
                        $skor_perPl = 3;
                    } else if ($persenPalepah >= 1.5 && $persenPalepah <= 2.0) {
                        $skor_perPl = 2;
                    } else if ($persenPalepah >= 2.0 && $persenPalepah <= 2.5) {
                        $skor_perPl = 1;
                    } else if ($persenPalepah >= 2.5) {
                        $skor_perPl = 0;
                    }


                    $total_skor = $skor_brd + $skor_bh + $skor_perPl;


                    $TotalperEstate[$key][$key2]['brd_janjang'] = number_format($brdperjjg, 2);
                    $TotalperEstate[$key][$key2]['total_ha'] = $sum_ha_sample;
                    $TotalperEstate[$key][$key2]['buah_janjang'] = number_format($buahperjjg, 2);
                    $TotalperEstate[$key][$key2]['skor_brd'] = $skor_brd;
                    $TotalperEstate[$key][$key2]['skor_buah'] = $skor_bh;
                    $TotalperEstate[$key][$key2]['skor_palepah'] = $skor_perPl;
                    $TotalperEstate[$key][$key2]['skor_akhir'] = $total_skor;
                }
            }
        }


        foreach ($TotalperEstate as $key1 => $estates) {
            $sortedData = array();
            foreach ($estates as $estateName => $data) {
                $sortedData[] = array(
                    'estateName' => $estateName,
                    'scores' => $data
                );
            }

            // Sort the new array based on skor_akhir
            usort($sortedData, function ($a, $b) {
                return $b['scores']['skor_akhir'] - $a['scores']['skor_akhir'];
            });

            // Assign rank to the original data
            $rank = 1;
            foreach ($sortedData as $sortedEstate) {
                $TotalperEstate[$key1][$sortedEstate['estateName']]['rank'] = $rank;
                $rank++;
            }
            unset($sortedData);
        }

        // dd($TotalperEstate);


        $wil_1 =  $TotalperEstate[1];
        // dd($DataTable1);
        $queryEsta = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->pluck('est');
        $queryEsta = json_decode($queryEsta, true);
        // dd($queryEsta);


        // dd($dataPerWil, $TotalperEstate);
        // dd($TotalperEstate);
        // $dummy_array = [];
        // for ($i = 0; $i < 10; $i++) {
        //     $dummy_array[] = rand(0, 1);
        // }
        // echo "[";
        // for ($i = 0; $i < count($dummy_array); $i++) {
        //     echo $dummy_array[$i];
        //     if ($i < count($dummy_array) - 1) {
        //         echo ",";
        //     }
        // }
        // echo "]";



        $chartBTT = array();
        foreach ($TotalperEstate as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $chartBTT[] = $value2['brd_janjang'];
            }
        }

        $chartBuahTT = array();
        foreach ($TotalperEstate as $key => $value) {
            foreach ($value as $key2 => $value2) {

                $chartBuahTT[] = $value2['buah_janjang'];
            }
        }

        // dd($TotalperEstate);

        $chartPerwil = array();
        foreach ($TotalperEstate as $key => $value) {
            $sum_brd = 0;
            $sum_ha = 0;
            $total_Brd = 0;
            foreach ($value as $key2 => $value2) {
                // dd($value2);
                $sum_brd += $value2['brd_janjang'];
                $sum_ha += $value2['total_ha'];
            }

            if ($sum_brd != 0) {
                $total_Brd = $sum_brd / $sum_ha;
            } else {
                $total_Brd = 0;
            }
            // $chartPerwil[$key]['total_brd'] = $sum_brd;
            // $chartPerwil[$key]['total_haS'] = $sum_ha;
            $chartPerwil[] = number_format($total_Brd, 2);
        }

        $buahPerwil = array();
        foreach ($TotalperEstate as $key => $value) {
            $sum_brd = 0;
            $sum_ha = 0;
            $total_Brd = 0;
            foreach ($value as $key2 => $value2) {
                // dd($value2);
                $sum_brd += $value2['buah_janjang'];
                $sum_ha += $value2['total_ha'];
            }

            if ($sum_brd != 0) {
                $total_Brd = $sum_brd / $sum_ha;
            } else {
                $total_Brd = 0;
            }
            // $chartPerwil[$key]['total_brd'] = $sum_brd;
            // $chartPerwil[$key]['total_haS'] = $sum_ha;
            $buahPerwil[] = number_format($total_Brd, 2);
        }
        // dd($buahPerwil);

        //testing uuntk perhitungan jangan lupa dihapus 
        $querySidak = DB::connection('mysql2')->table('mutu_transport')
            ->select("mutu_transport.*")
            // ->where('datetime', 'like', '%' . $getDate . '%')
            // ->where('datetime', 'like', '%' . '2023-01' . '%')
            ->get();
        $DataEstate = $querySidak->groupBy(['estate', 'afdeling']);
        // dd($DataEstate);
        $DataEstate = json_decode($DataEstate, true);

        //menghitung buat table tampilkan pertahun

        //bagian querry
        //mutu ancak
        $querytahun = DB::connection('mysql2')->table('mutu_ancak')
            ->select("mutu_ancak.*", DB::raw('DATE_FORMAT(mutu_ancak.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak.datetime, "%Y") as tahun'))
            // ->whereYear('datetime', '2023')
            ->whereYear('datetime', '2023')
            ->get();
        $querytahun = $querytahun->groupBy(['estate', 'afdeling']);
        $querytahun = json_decode($querytahun, true);
        // dd($querytahun);
        //mutu buah
        $queryMTbuah = DB::connection('mysql2')->table('mutu_buah')
            ->select(
                "mutu_buah.*",
                DB::raw('DATE_FORMAT(mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_buah.datetime, "%Y") as tahun')
            )
            ->whereYear('datetime', '2023')
            ->get();
        $queryMTbuah = $queryMTbuah->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($queryMTbuah, true);
        // dd($queryMTbuah);
        //MUTU ANCAK
        $queryMTtrans = DB::connection('mysql2')->table('mutu_transport')
            ->select(
                "mutu_transport.*",
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y") as tahun')
            )
            ->whereYear('datetime', '2023')
            ->get();
        $queryMTtrans = $queryMTtrans->groupBy(['estate', 'afdeling']);
        $queryMTtrans = json_decode($queryMTtrans, true);
        // dd($queryMTancak);

        //afdeling
        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);
        //estate
        $queryEste = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        $queryEste = json_decode($queryEste, true);

        // dd($queryMTbuah);
        //end query

        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        //membuat nilai untuk mutu buah berdasarkan afdeling perbulan

        //end testing
        // dd($RegMTbuahBln);
        return view('dashboard_inspeksi', [
            'dataRaw' => $dataRaw,
            'arrHeader' => $arrHeader,
            'arrHeaderSc' => $arrHeaderSc,
            'arrHeaderTrd' => $arrHeaderTrd,
            'arrHeaderReg' => $arrHeaderReg,

            'dataSkor' => $dataSkor,
            'Mutubuah' => $Mutubuah,
            'MutuAncak' => $MutuAncak,
            'dataPerWil' => $dataPerWil,
            'TotalperEstate' => $TotalperEstate,
            'wil_1' => $wil_1,
            'queryEsta' => $queryEsta,
            'chartBTT' => $chartBTT,
            'chartBuahTT' => $chartBuahTT,
            'chartPerwil' => $chartPerwil,
            'buahPerwil' => $buahPerwil,
            'dataTahunEst' => $dataTahunEst,
            'FinalTahun' => $FinalTahun
        ]);
    }



    public function filter(Request $request)
    {
        $regionalData = $request->input('regionalData');
        $date = $request->input('date');
        $year = $request->input('year');

        // dd($year);


        // backend untuk halaman utama
        $queryWill = DB::connection('mysql2')->table('wil')->whereIn('regional', [1])->get();;
        $queryEste = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        $queryEste = $queryEste->groupBy(function ($item) {
            return $item->wil;
        });
        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db

            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryMtAncak = DB::connection('mysql2')->table('mutu_ancak')
            ->select("mutu_ancak.*")
            ->where('datetime', 'like', '%' . $date . '%')
            // ->where('datetime', 'like', '%' . "2023-02" . '%')
            ->get();


        $queryMtAncak = json_decode($queryMtAncak, true);
        $queryAfd = json_decode($queryAfd, true);
        $queryEste = json_decode($queryEste, true);
        $queryWill = json_decode($queryWill, true);

        $dataAfdEst = array();
        // menyimpan array nested dari  wil -> est -> afd
        foreach ($queryEste as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($queryAfd as $key3 => $value3) {
                    $est = $value2['est'];
                    $afd = $value3['nama'];
                    // dd($value3);
                    if ($value2['est'] == $value3['est']) {
                        foreach ($queryMtAncak as $key4 => $value4) {
                            // dd($value4);
                            if (($value2['est'] == $value4['estate']) && $value3['nama'] == $value4['afdeling']) {
                                $dataAfdEst[$est][$afd][] = $value4;
                            } else {
                                $dataAfdEst[$est][$afd][] = 0;
                            }
                        }
                    }
                }
            }
        }

        // dd($dataAfdEst);
        $DataTable1 = array();
        foreach ($dataAfdEst as $key => $value) {
            foreach ($value as $key2 => $value2)
                if (!empty($value2)) {
                    $akp = 0;
                    $skor_bTinggal = 0;
                    $brdPerjjg = 0;
                    $pokok_panen = 0;
                    $janjang_panen = 0;
                    $p_panen = 0;
                    $k_panen = 0;
                    $bhts_panen  = 0;
                    $bhtm1_panen  = 0;
                    $bhtm2_panen  = 0;
                    $bhtm3_oanen  = 0;
                    $ttlSkorMA = 0;
                    $listBlokPerAfd = array();
                    $jum_ha = 0;
                    $pelepah_s = 0;
                    $brtgl_panen = 0;
                    foreach ($value2 as $key3 => $value3) {
                        if (is_array($value3)) {
                            if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
                                $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'];
                            }
                            $jum_ha = count($listBlokPerAfd);

                            // bagian brondolan tingaal

                            $pokok_panen = json_decode($value3["pokok_dipanen"], true);
                            $jajang_panen = json_decode($value3["jjg_dipanen"], true);
                            $brtp = json_decode($value3["brtp"], true);
                            $brtk = json_decode($value3["brtk"], true);
                            $brtgl = json_decode($value3["brtgl"], true);
                            $pokok_panen  = count($pokok_panen);
                            $janjang_panen = array_sum($jajang_panen);
                            $p_panen = array_sum($brtp);
                            $k_panen = array_sum($brtk);


                            // $akp = ($janjang_panen / $pokok_panen) %
                            $akp = ($janjang_panen / $pokok_panen) * 100;
                            $skor_bTinggal = $p_panen + $k_panen + $brtgl_panen;
                            $brdPerjjg = $skor_bTinggal / $pokok_panen;

                            //skore PEnggunnan Brondolan
                            $skor_brdPerjjg = 0;
                            if ($brdPerjjg <= 1.0) {
                                $skor_brdPerjjg = 20;
                            } else if ($brdPerjjg >= 1.5 && $brdPerjjg <= 2.0) {
                                $skor_brdPerjjg = 12;
                            } else if ($brdPerjjg >= 2.0 && $brdPerjjg <= 2.5) {
                                $skor_brdPerjjg = 8;
                            } else if ($brdPerjjg >= 2.5 && $brdPerjjg <= 3.0) {
                                $skor_brdPerjjg = 4;
                            } else if ($brdPerjjg >= 3.0 && $brdPerjjg <= 3.5) {
                                $skor_brdPerjjg = 0;
                            } else if ($brdPerjjg >= 4.0 && $brdPerjjg <= 4.5) {
                                $skor_brdPerjjg = 8;
                            } else if ($brdPerjjg >=  4.5 && $brdPerjjg <= 5.0) {
                                $skor_brdPerjjg = 12;
                            } else if ($brdPerjjg >=  5.0) {
                                $skor_brdPerjjg = 16;
                            }

                            // bagian buah tinggal
                            $bhts = json_decode($value3["bhts"], true);
                            $bhtm1 = json_decode($value3["bhtm1"], true);
                            $bhtm2 = json_decode($value3["bhtm2"], true);
                            $bhtm3 = json_decode($value3["bhtm3"], true);


                            $bhts_panen = array_sum($bhts);
                            $bhtm1_panen = array_sum($bhtm1);
                            $bhtm2_panen = array_sum($bhtm2);
                            $bhtm3_oanen = array_sum($bhtm3);

                            $sumBH = $bhts_panen +  $bhtm1_panen +  $bhtm2_panen +  $bhtm3_oanen;

                            $sumPerBH = $sumBH / ($janjang_panen + $sumBH) * 100;

                            $skor_bh = 0;
                            if ($sumPerBH <=  0.0) {
                                $skor_bh = 20;
                            } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                                $skor_bh = 18;
                            } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                                $skor_bh = 16;
                            } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                                $skor_bh = 12;
                            } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                                $skor_bh = 8;
                            } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                                $skor_bh = 4;
                            } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                                $skor_bh = 0;
                            } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                                $skor_bh = 0;
                            } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                                $skor_bh = 4;
                            } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                                $skor_bh = 8;
                            } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                                $skor_bh = 12;
                            } else if ($sumPerBH >= 5.0) {
                                $skor_bh = 10;
                            }
                            // data untuk pelepah sengklek

                            $ps = json_decode($value3["ps"], true);

                            $pelepah_s = array_sum($ps);
                            if ($pelepah_s != 0) {
                                $perPl = ($pokok_panen / $pelepah_s) * 100;
                            } else {
                                $perPl = 0;
                            }
                            $skor_perPl = 0;
                            if ($perPl <=  0.5) {
                                $skor_perPl = 5;
                            } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                                $skor_perPl = 4;
                            } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                                $skor_perPl = 3;
                            } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                                $skor_perPl = 2;
                            } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                                $skor_perPl = 1;
                            } else if ($perPl >= 2.5) {
                                $skor_perPl = 0;
                            }
                        }
                    }

                    $ttlSkorMA = $skor_brdPerjjg + $skor_bh + $skor_perPl;
                    $DataTable1[$key][$key2][$key3]['pokok_sample'] = $pokok_panen;
                    $DataTable1[$key][$key2][$key3]['ha_sample'] = $jum_ha;
                    $DataTable1[$key][$key2][$key3]['jumlah_panen'] = $janjang_panen;
                    $DataTable1[$key][$key2][$key3]['akp_rl'] =  number_format($akp, 2);


                    $DataTable1[$key][$key2][$key3]['p'] = $p_panen;
                    $DataTable1[$key][$key2][$key3]['k'] = $k_panen;
                    $DataTable1[$key][$key2][$key3]['tgl'] = $skor_bTinggal;

                    // $DataTable1[$key][$key2][$key3]['total_brd'] = $skor_bTinggal;
                    $DataTable1[$key][$key2][$key3]['brd/jjg'] = number_format($brdPerjjg, 2);
                    $DataTable1[$key][$key2][$key3]['skor_brd'] = number_format($skor_brdPerjjg, 2);
                    // data untuk buah tinggal
                    $DataTable1[$key][$key2][$key3]['bhts_s'] = $bhts_panen;
                    $DataTable1[$key][$key2][$key3]['bhtm1'] = $bhtm1_panen;
                    $DataTable1[$key][$key2][$key3]['bhtm2'] = $bhtm2_panen;
                    $DataTable1[$key][$key2][$key3]['bhtm3'] = $bhtm3_oanen;

                    $DataTable1[$key][$key2][$key3]['skor_bh'] = number_format($skor_bh, 2);
                    // $DataTable1[$key][$key2][$key3]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek
                    $DataTable1[$key][$key2][$key3]['skor_ps'] = number_format($skor_perPl, 2);
                    $DataTable1[$key][$key2][$key3]['total_pelepah'] = $pelepah_s;
                    // total skor akhir
                    $DataTable1[$key][$key2][$key3]['skor_akhir'] = number_format($ttlSkorMA, 2);
                } else {
                    $DataTable1[$key][$key2][$key3]['pokok_sample'] = 0;
                    $DataTable1[$key][$key2][$key3]['ha_sample'] = 0;
                    $DataTable1[$key][$key2][$key3]['jumlah_panen'] = 0;
                    $DataTable1[$key][$key2][$key3]['akp_rl'] =  0;


                    $DataTable1[$key][$key2][$key3]['p'] = 0;
                    $DataTable1[$key][$key2][$key3]['k'] = 0;
                    $DataTable1[$key][$key2][$key3]['tgl'] = 0;

                    // $DataTable1[$key][$key2][$key3]['total_brd'] = $skor_bTinggal;
                    $DataTable1[$key][$key2][$key3]['brd/jjg'] = 0;
                    $DataTable1[$key][$key2][$key3]['skor_brd'] = 0;
                    // data untuk buah tinggal
                    $DataTable1[$key][$key2][$key3]['bhts_s'] = 0;
                    $DataTable1[$key][$key2][$key3]['bhtm1'] = 0;
                    $DataTable1[$key][$key2][$key3]['bhtm2'] = 0;
                    $DataTable1[$key][$key2][$key3]['bhtm3'] = 0;

                    $DataTable1[$key][$key2][$key3]['skor_bh'] = 0;
                    // $DataTable1[$key][$key2][$key3]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek
                    $DataTable1[$key][$key2][$key3]['skor_ps'] = 0;
                    $DataTable1[$key][$key2][$key3]['total_pelepah'] = 0;
                    // total skor akhir
                    $DataTable1[$key][$key2][$key3]['skor_akhir'] = 0;
                }
        }

        // dd($DataTable1);
        foreach ($queryEste as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($DataTable1 as $key3 => $value3) {
                    if ($value2['est'] == $key3) {
                        $dataPerWil[$key][$key3] = $value3;
                    }
                }
            }
        }
        // dd($dataPerWil);
        foreach ($dataPerWil as $key1 => $estates) {
            $sortedData = array();
            foreach ($estates as $estateName => $data) {
                foreach ($data as $key2 => $scores) {
                    foreach ($scores as $key3 => $value) {
                        $sortedData[] = array(
                            'estateName' => $estateName,
                            'key2' => $key2,
                            'value' => $value
                        );
                    }
                }
            }
            // Sort the new array based on skor_akhir
            usort($sortedData, function ($a, $b) {
                return $b['value']['skor_akhir'] - $a['value']['skor_akhir'];
            });

            // Assign rank to the original data
            $rank = 1;
            foreach ($sortedData as $sortedEstate) {
                $dataPerWil[$key1][$sortedEstate['estateName']][$sortedEstate['key2']]['rank'] = $rank;
                $rank++;
            }
            unset($sortedData);
        }

        //membuat tabel utama untuk tiap estate total
        $TotalperEstate = array();
        foreach ($dataPerWil as $key => $value) {
            foreach ($value as $key2 => $value2) {

                $sum_p = 0;
                $sum_k = 0;
                $sum_tgl = 0;
                $sum_ttlPanen = 0;
                $sum_bhts = 0;
                $sum_bhtm1 = 0;
                $sum_bhtm2 = 0;
                $sum_bhtm3 = 0;
                $total_brd = 0;
                $total_buah = 0;
                $sum_pelepah = 0;
                $sum_ttlPokok = 0;
                $total_skor = 0;
                $sum_ha_sample = 0;
                $brdperjjg = 0;
                $persenPalepah = 0;
                foreach ($value2 as $key3 => $value3) {
                    foreach ($value3 as $key4 => $value4) {
                        if (is_array($value4)) {
                            $sum_ttlPanen += $value4['jumlah_panen'];
                            $sum_ttlPokok += $value4['pokok_sample'];
                            $sum_ha_sample += $value4['ha_sample'];


                            $sum_p += $value4['p'];
                            $sum_k += $value4['k'];
                            $sum_tgl += $value4['tgl'];

                            $sum_bhts += $value4['bhts_s'];
                            $sum_bhtm1 += (int) $value4['bhtm1'];
                            $sum_bhtm2 += (int)$value4['bhtm2'];
                            $sum_bhtm3 += (int)$value4['bhtm3'];

                            $sum_pelepah += (int)$value4['total_pelepah'];

                            // $TotalperEstate[$key][$key2]['total_p'] += $value4['p'];
                            // $TotalperEstate[$key][$key2]['total_k'] += $value4['k'];
                            // $TotalperEstate[$key][$key2]['total_tinggal'] += $value4['tgl'];
                            // $TotalperEstate[$key][$key2]['total_panen'] += $value4['jumlah_panen'];

                            // $TotalperEstate[$key][$key2]['bhts'] += $value4['bhts_s'];
                            // $TotalperEstate[$key][$key2]['bhtm1'] +=  (int) $value4['bhtm1'];
                            // $TotalperEstate[$key][$key2]['bhtm2'] +=  (int) $value4['bhtm2'];
                            // $TotalperEstate[$key][$key2]['bhtm3'] +=  (int) $value4['bhtm3'];
                        }
                    }

                    $total_brd = $sum_p + $sum_k + $sum_tgl;
                    $total_buah = $sum_bhts + $sum_bhtm1 + $sum_bhtm2 + $sum_bhtm3;





                    if ($sum_ttlPanen != 0) {
                        $brdperjjg = $total_brd / $sum_ttlPanen;
                    } else {
                        $brdperjjg = 0;
                    }


                    if ($sum_ttlPokok != 0) {
                        $persenPalepah = ($sum_ttlPokok / $sum_pelepah) * 100;
                    } else {
                        $persenPalepah = 0;
                    }

                    $buahperjjg = 0;
                    if ($sum_ttlPanen != 0) {
                        $buahperjjg = $total_buah / ($sum_ttlPanen + $total_buah) * 100;
                    } else {
                        $buahperjjg = 0;
                    }

                    $skor_brd = 0;
                    if ($brdperjjg <= 1.0) {
                        $skor_brd = 20;
                    } else if ($brdperjjg >= 1.5 && $brdperjjg <= 2.0) {
                        $skor_brd = 12;
                    } else if ($brdperjjg >= 2.0 && $brdperjjg <= 2.5) {
                        $skor_brd = 8;
                    } else if ($brdperjjg >= 2.5 && $brdperjjg <= 3.0) {
                        $skor_brd = 4;
                    } else if ($brdperjjg >= 3.0 && $brdperjjg <= 3.5) {
                        $skor_brd = 0;
                    } else if ($brdperjjg >= 4.0 && $brdperjjg <= 4.5) {
                        $skor_brd = 8;
                    } else if ($brdperjjg >=  4.5 && $brdperjjg <= 5.0) {
                        $skor_brd = 12;
                    } else if ($brdperjjg >=  5.0) {
                        $skor_brd = 16;
                    }

                    //buah tinggal
                    $skor_bh = 0;
                    if ($buahperjjg <=  0.0) {
                        $skor_bh = 20;
                    } else if ($buahperjjg >=  0.0 && $buahperjjg <= 1.0) {
                        $skor_bh = 18;
                    } else if ($buahperjjg >= 1 && $buahperjjg <= 1.5) {
                        $skor_bh = 16;
                    } else if ($buahperjjg >= 1.5 && $buahperjjg <= 2.0) {
                        $skor_bh = 12;
                    } else if ($buahperjjg >= 2.0 && $buahperjjg <= 2.5) {
                        $skor_bh = 8;
                    } else if ($buahperjjg >= 2.5 && $buahperjjg <= 3.0) {
                        $skor_bh = 4;
                    } else if ($buahperjjg >= 3.0 && $buahperjjg <= 3.5) {
                        $skor_bh = 0;
                    } else if ($buahperjjg >=  3.5 && $buahperjjg <= 3.5) {
                        $skor_bh = 0;
                    } else if ($buahperjjg >= 3.5 && $buahperjjg <= 4.0) {
                        $skor_bh = 4;
                    } else if ($buahperjjg >= 4.0 && $buahperjjg <= 4.5) {
                        $skor_bh = 8;
                    } else if ($buahperjjg >= 4.5 && $buahperjjg <= 5.0) {
                        $skor_bh = 12;
                    } else if ($buahperjjg >= 5.0) {
                        $skor_bh = 10;
                    }

                    $skor_perPl = 0;
                    if ($persenPalepah <=  0.5) {
                        $skor_perPl = 5;
                    } else if ($persenPalepah >=  0.5 && $persenPalepah <= 1.0) {
                        $skor_perPl = 4;
                    } else if ($persenPalepah >= 1.0 && $persenPalepah <= 1.5) {
                        $skor_perPl = 3;
                    } else if ($persenPalepah >= 1.5 && $persenPalepah <= 2.0) {
                        $skor_perPl = 2;
                    } else if ($persenPalepah >= 2.0 && $persenPalepah <= 2.5) {
                        $skor_perPl = 1;
                    } else if ($persenPalepah >= 2.5) {
                        $skor_perPl = 0;
                    }


                    $total_skor = $skor_brd + $skor_bh + $skor_perPl;


                    $TotalperEstate[$key][$key2]['brd_janjang'] = round($brdperjjg, 2);
                    $TotalperEstate[$key][$key2]['total_ha'] = $sum_ha_sample;
                    $TotalperEstate[$key][$key2]['buah_janjang'] = round($buahperjjg, 2);
                    $TotalperEstate[$key][$key2]['skor_brd'] = $skor_brd;
                    $TotalperEstate[$key][$key2]['skor_buah'] = $skor_bh;
                    $TotalperEstate[$key][$key2]['skor_palepah'] = $skor_perPl;
                    $TotalperEstate[$key][$key2]['skor_akhir'] = $total_skor;
                }
            }
        }


        foreach ($TotalperEstate as $key1 => $estates) {
            $sortedData = array();
            foreach ($estates as $estateName => $data) {
                $sortedData[] = array(
                    'estateName' => $estateName,
                    'scores' => $data
                );
            }

            // Sort the new array based on skor_akhir
            usort($sortedData, function ($a, $b) {
                return $b['scores']['skor_akhir'] - $a['scores']['skor_akhir'];
            });

            // Assign rank to the original data
            $rank = 1;
            foreach ($sortedData as $sortedEstate) {
                $TotalperEstate[$key1][$sortedEstate['estateName']]['rank'] = $rank;
                $rank++;
            }
            unset($sortedData);
        }

        // dd($TotalperEstate);



        // dd($DataTable1);
        $queryEsta = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->pluck('est');
        $queryEsta = json_decode($queryEsta, true);
        // dd($queryEsta);

        $chartBTT = array();
        foreach ($TotalperEstate as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $chartBTT[] = $value2['brd_janjang'];
            }
        }

        $chartBuahTT = array();
        foreach ($TotalperEstate as $key => $value) {
            foreach ($value as $key2 => $value2) {

                $chartBuahTT[] = $value2['buah_janjang'];
            }
        }

        // dd($TotalperEstate);

        $chartPerwil = array();
        foreach ($TotalperEstate as $key => $value) {
            $sum_brd = 0;
            $sum_ha = 0;
            $total_Brd = 0;
            foreach ($value as $key2 => $value2) {
                // dd($value2);
                $sum_brd += $value2['brd_janjang'];
                $sum_ha += $value2['total_ha'];
            }

            if ($sum_brd != 0) {
                $total_Brd = $sum_brd / $sum_ha;
            } else {
                $total_Brd = 0;
            }
            // $chartPerwil[$key]['total_brd'] = $sum_brd;
            // $chartPerwil[$key]['total_haS'] = $sum_ha;
            $chartPerwil[] = round($total_Brd, 2);
        }

        $buahPerwil = array();
        foreach ($TotalperEstate as $key => $value) {
            $sum_brd = 0;
            $sum_ha = 0;
            $total_Brd = 0;
            foreach ($value as $key2 => $value2) {
                // dd($value2);
                $sum_brd += $value2['buah_janjang'];
                $sum_ha += $value2['total_ha'];
            }

            if ($sum_brd != 0) {
                $total_Brd = $sum_brd / $sum_ha;
            } else {
                $total_Brd = 0;
            }
            // $chartPerwil[$key]['total_brd'] = $sum_brd;
            // $chartPerwil[$key]['total_haS'] = $sum_ha;
            $buahPerwil[] = round($total_Brd, 2);
        }

        //table perbulan 

        // Untuk table perhitungan berdasarkan tahun dashbouard utama
        $querySidak = DB::connection('mysql2')->table('mutu_transport')
            ->select("mutu_transport.*")
            // ->where('datetime', 'like', '%' . $getDate . '%')
            // ->where('datetime', 'like', '%' . '2023-01' . '%')
            ->get();
        $DataEstate = $querySidak->groupBy(['estate', 'afdeling']);
        // dd($DataEstate);
        $DataEstate = json_decode($DataEstate, true);

        //menghitung buat table tampilkan pertahun

        //bagian querry
        //mutu ancak
        $querytahun = DB::connection('mysql2')->table('mutu_ancak')
            ->select("mutu_ancak.*", DB::raw('DATE_FORMAT(mutu_ancak.datetime, "%M") as bulan'), DB::raw('DATE_FORMAT(mutu_ancak.datetime, "%Y") as tahun'))
            // ->whereYear('datetime', '2023')
            ->whereYear('datetime', $year)
            ->get();
        $querytahun = $querytahun->groupBy(['estate', 'afdeling']);
        $querytahun = json_decode($querytahun, true);
        // dd($querytahun);
        //mutu buah
        $queryMTbuah = DB::connection('mysql2')->table('mutu_buah')
            ->select(
                "mutu_buah.*",
                DB::raw('DATE_FORMAT(mutu_buah.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_buah.datetime, "%Y") as tahun')
            )
            ->whereYear('datetime', $year)
            ->get();
        $queryMTbuah = $queryMTbuah->groupBy(['estate', 'afdeling']);
        $queryMTbuah = json_decode($queryMTbuah, true);
        // dd($queryMTbuah);
        //MUTU ANCAK
        $queryMTtrans = DB::connection('mysql2')->table('mutu_transport')
            ->select(
                "mutu_transport.*",
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%M") as bulan'),
                DB::raw('DATE_FORMAT(mutu_transport.datetime, "%Y") as tahun')
            )
            ->whereYear('datetime', $year)
            ->get();
        $queryMTtrans = $queryMTtrans->groupBy(['estate', 'afdeling']);
        $queryMTtrans = json_decode($queryMTtrans, true);
        // dd($queryMTancak);

        //afdeling
        $queryAfd = DB::connection('mysql2')->table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            ) //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();
        $queryAfd = json_decode($queryAfd, true);
        //estate
        $queryEste = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        $queryEste = json_decode($queryEste, true);

        // dd($queryMTbuah);
        //end query

        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        //mutu ancak membuat nilai berdasrakan bulan
        $dataPerBulan = array();
        foreach ($querytahun as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $dataPerBulan)) {
                        $dataPerBulan[$month] = array();
                    }
                    if (!array_key_exists($key, $dataPerBulan[$month])) {
                        $dataPerBulan[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $dataPerBulan[$month][$key])) {
                        $dataPerBulan[$month][$key][$key2] = array();
                    }
                    $dataPerBulan[$month][$key][$key2][$key3] = $value3;
                }
            }
        }






        //mutu buah  membuat nilai berdasrakan bulan
        $dataPerBulanMTbh = array();
        foreach ($queryMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $dataPerBulanMTbh)) {
                        $dataPerBulanMTbh[$month] = array();
                    }
                    if (!array_key_exists($key, $dataPerBulanMTbh[$month])) {
                        $dataPerBulanMTbh[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $dataPerBulanMTbh[$month][$key])) {
                        $dataPerBulanMTbh[$month][$key][$key2] = array();
                    }
                    $dataPerBulanMTbh[$month][$key][$key2][$key3] = $value3;
                }
            }
        }

        // dd($dataPerBulanMTbh);
        //mutu transport memnuat nilai perbulan
        $dataBulananMTtrans = array();
        foreach ($queryMTtrans as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $dataBulananMTtrans)) {
                        $dataBulananMTtrans[$month] = array();
                    }
                    if (!array_key_exists($key, $dataBulananMTtrans[$month])) {
                        $dataBulananMTtrans[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $dataBulananMTtrans[$month][$key])) {
                        $dataBulananMTtrans[$month][$key][$key2] = array();
                    }
                    $dataBulananMTtrans[$month][$key][$key2][$key3] = $value3;
                }
            }
        }
        // dd($dataBulananMTtrans);

        //membuat nilai default 0 ke masing masing est-afdeling untuk di timpa nanti
        //membuat array estate -> bulan -> afdeling
        // mutu ancak
        $defaultNew = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($queryAfd as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defaultNew[$est['est']][$month][$afd['nama']] = 0;
                    }
                }
            }
        }


        // dd($defaultTabAFD);
        //mutu buah
        $defaultMTbh = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($queryAfd as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defaultMTbh[$est['est']][$month][$afd['nama']] = 0;
                    }
                }
            }
        }
        //mutu transport
        $defaultTrans = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($queryAfd as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defaultTrans[$est['est']][$month][$afd['nama']] = 0;
                    }
                }
            }
        }


        //membuat nilai default untuk table terakhir tahunan EST > AFD

        // dd($defaultMTbh);
        //end  nilai defalt
        //bagian menimpa nilai dengan menggunakan defaultNEw
        //menimpa nilai default dengan value mutu ancak yang ada isinya sehingga yang tidak ada value menjadi 0
        foreach ($defaultNew as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($dataPerBulan as $dataKey => $dataValue) {
                    if ($dataKey == $monthKey) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {
                            if ($dataEstKey == $key) {
                                $defaultNew[$key][$monthKey] = array_merge($monthValue, $dataEstValue);
                            }
                        }
                    }
                }
            }
        }



        // dd($defaultTabAFD);
        // menimpa nilai defaultnew dengan value mutu buah yang ada isi nya
        // dd($defaultMTbh, $dataPerBulanMTbh);
        foreach ($defaultMTbh as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($dataPerBulanMTbh as $dataKey => $dataValue) {
                    if ($dataKey == $monthKey) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {
                            if ($dataEstKey == $key) {
                                $defaultMTbh[$key][$monthKey] = array_merge($monthValue, $dataEstValue);
                            }
                        }
                    }
                }
            }
        }
        // dd($defaultMTbh);
        //menimpa nilai default mutu transport dengan yang memiliki value
        foreach ($defaultTrans as $key => $estValue) {
            foreach ($estValue as $monthKey => $monthValue) {
                foreach ($dataBulananMTtrans as $dataKey => $dataValue) {
                    if ($dataKey == $monthKey) {
                        foreach ($dataValue as $dataEstKey => $dataEstValue) {
                            if ($dataEstKey == $key) {
                                $defaultTrans[$key][$monthKey] = array_merge($monthValue, $dataEstValue);
                            }
                        }
                    }
                }
            }
        }
        // dd($defaultTrans);


        //bagian untuk table ke 2 menghitung berdasarkan wilayah
        //mutu ancak membuat nilai berdasrakan bulan
        //membuat nilai default mutu ancak untuk bulan>estate>afdeling>value = 0;
        // dd($dataPerBulan);
        $defPerbulanWil = array();
        foreach ($bulan as $key => $value) {
            foreach ($queryEste as $key2 => $value2) {
                foreach ($queryAfd as $key3 => $value3) {
                    if ($value2['est'] == $value3['est']) {
                        $defPerbulanWil[$value][$value2['est']][$value3['nama']] = 0;
                        // $defPerbulanWil[$value][$value2['est']][$value] = 0;
                    }
                }
            }
        }

        //menimpa nilai default di atas dengan dataperbulan mutu ancak yang ada isinya sehingga yang kosong menjadi 0
        foreach ($dataPerBulan as $key2 => $value2) {
            foreach ($value2 as $key3 => $value3) {
                foreach ($value3 as $key4 => $value4) {
                    // foreach ($defPerbulanWil[$key2][$key3][$key4] as $key => $value) {
                    $defPerbulanWil[$key2][$key3][$key4] = $value4;
                }
            }
        }
        //membuat data mutu ancak berdasarakan wilayah 1,2,3
        $mtAncakWil = array();
        foreach ($queryEste as $key => $value) {
            foreach ($defPerbulanWil as $key2 => $value2) {
                // dd($value2);
                foreach ($value2 as $key3 => $value3) {
                    if ($value['est'] == $key3) {
                        $mtAncakWil[$value['wil']][$key2][$key3] = $value3;
                    }
                }
            }
        }


        //perhitungan data untuk mutu transport
        //menghitung afd perbulan
        $mutuTransAFD = array();
        foreach ($defaultTrans as $key => $value) {
            foreach ($value as $key1 => $value2) {
                foreach ($value2 as $key2 => $value3)
                    if (is_array($value3)) {
                        $sum_bt = 0;
                        $sum_rst = 0;
                        $brdPertph = 0;
                        $buahPerTPH = 0;
                        $totalSkor = 0;
                        $dataBLok = 0;
                        $combination_counts = array();
                        foreach ($value3 as $key3 => $value4) {
                            // dd($value4);
                            $combination = $value4['blok'] . ' ' . $value4['estate'] . ' ' . $value4['afdeling'] . ' ' . $value4['tph_baris'];
                            if (!isset($combination_counts[$combination])) {
                                $combination_counts[$combination] = 0;
                            }
                            $combination_counts[$combination]++;
                            $sum_bt += $value4['bt'];
                            $sum_rst += $value4['rst'];
                        }
                        $dataBLok = count($combination_counts);
                        $brdPertph = round($sum_bt / $dataBLok, 2);
                        $buahPerTPH = round($sum_rst / $dataBLok, 2);

                        //menghitung skor butir
                        $skor_brdPertph = 0;
                        if ($brdPertph <= 3) {
                            $skor_brdPertph = 10;
                        } else if ($brdPertph >= 3 && $brdPertph <= 5) {
                            $skor_brdPertph = 8;
                        } else if ($brdPertph >= 5 && $brdPertph <= 7) {
                            $skor_brdPertph = 6;
                        } else if ($brdPertph >= 7 && $brdPertph <= 9) {
                            $skor_brdPertph = 4;
                        } else if ($brdPertph >= 9 && $brdPertph <= 11) {
                            $skor_brdPertph = 2;
                        } else if ($brdPertph >= 11) {
                            $skor_brdPertph = 0;
                        }
                        //menghitung Skor Restant
                        $skor_buahPerTPH = 0;
                        if ($buahPerTPH <= 0.0) {
                            $skor_buahPerTPH = 10;
                        } else if ($buahPerTPH >= 0.0 && $buahPerTPH <= 0.5) {
                            $skor_buahPerTPH = 8;
                        } else if ($buahPerTPH >= 0.5 && $buahPerTPH <= 1) {
                            $skor_buahPerTPH = 6;
                        } else if ($buahPerTPH >= 1.0 && $buahPerTPH <= 1.5) {
                            $skor_buahPerTPH = 4;
                        } else if ($buahPerTPH >= 1.5 && $buahPerTPH <= 2.0) {
                            $skor_buahPerTPH = 2;
                        } else if ($buahPerTPH >= 2.0 && $buahPerTPH <= 2.5) {
                            $skor_buahPerTPH = 0;
                        } else if ($buahPerTPH >= 2.5 && $buahPerTPH <= 3.0) {
                            $skor_buahPerTPH = 2;
                        } else if ($buahPerTPH >= 3.0 && $buahPerTPH <= 3.5) {
                            $skor_buahPerTPH = 4;
                        } else if ($buahPerTPH >= 3.5 && $buahPerTPH <= 4.0) {
                            $skor_buahPerTPH = 6;
                        } else if ($buahPerTPH >= 4.0) {
                            $skor_buahPerTPH = 8;
                        }

                        $totalSkor = $skor_buahPerTPH + $skor_brdPertph;

                        $mutuTransAFD[$key][$key1][$key2]['tph_sample'] = $dataBLok;
                        $mutuTransAFD[$key][$key1][$key2]['total_brd'] = $sum_bt;
                        $mutuTransAFD[$key][$key1][$key2]['total_brd/TPH'] = $brdPertph;
                        $mutuTransAFD[$key][$key1][$key2]['total_buah'] = $sum_rst;
                        $mutuTransAFD[$key][$key1][$key2]['total_buahPerTPH'] = $buahPerTPH;
                        $mutuTransAFD[$key][$key1][$key2]['skor_brdPertph'] = $skor_brdPertph;
                        $mutuTransAFD[$key][$key1][$key2]['skor_buahPerTPH'] = $skor_buahPerTPH;
                        $mutuTransAFD[$key][$key1][$key2]['totalSkor'] = $totalSkor;
                    } else {
                        $mutuTransAFD[$key][$key1][$key2]['tph_sample'] = 0;
                        $mutuTransAFD[$key][$key1][$key2]['total_brd'] = 0;
                        $mutuTransAFD[$key][$key1][$key2]['total_brd/TPH'] = 0;
                        $mutuTransAFD[$key][$key1][$key2]['total_buah'] = 0;
                        $mutuTransAFD[$key][$key1][$key2]['total_buahPerTPH'] = 0;
                        $mutuTransAFD[$key][$key1][$key2]['skor_brdPertph'] = 0;
                        $mutuTransAFD[$key][$key1][$key2]['skor_buahPerTPH'] = 0;
                        $mutuTransAFD[$key][$key1][$key2]['totalSkor'] = 0;
                    }
            }
        }
        // dd($mutuTransAFD);
        // hitungan per est per bulan
        $mutuTransEst = array();
        foreach ($mutuTransAFD as $key => $value) {
            foreach ($value as $key1 => $value2) {
                $total_sample = 0;
                $total_brd = 0;
                $total_buah = 0;
                $brdPertph = 0;
                $buahPerTPH = 0;
                $totalSkor = 0;
                if (!empty($value2)) {
                    foreach ($value2 as $key2 => $value3) {
                        // dd($value3);
                        $total_sample += $value3['tph_sample'];
                        $total_brd += $value3['total_brd'];
                        $total_buah += $value3['total_buah'];
                    }

                    if ($total_sample != 0) {
                        $brdPertph = round($total_brd / $total_sample, 2);
                    } else {
                        $brdPertph = 0;
                    }

                    if ($total_sample != 0) {
                        $buahPerTPH = round($total_buah / $total_sample, 2);
                    } else {
                        $buahPerTPH = 0;
                    }

                    $skor_brdPertph = 0;
                    if ($brdPertph <= 3) {
                        $skor_brdPertph = 10;
                    } else if ($brdPertph >= 3 && $brdPertph <= 5) {
                        $skor_brdPertph = 8;
                    } else if ($brdPertph >= 5 && $brdPertph <= 7) {
                        $skor_brdPertph = 6;
                    } else if ($brdPertph >= 7 && $brdPertph <= 9) {
                        $skor_brdPertph = 4;
                    } else if ($brdPertph >= 9 && $brdPertph <= 11) {
                        $skor_brdPertph = 2;
                    } else if ($brdPertph >= 11) {
                        $skor_brdPertph = 0;
                    }

                    // if ($buahPerTPH != 0) {
                    //     //menghitung Skor Restant
                    //     $skor_buahPerTPH = 0;
                    //     if ($buahPerTPH <= 0.0) {
                    //         $skor_buahPerTPH = 10;
                    //     } else if ($buahPerTPH >= 0.0 && $buahPerTPH <= 0.5) {
                    //         $skor_buahPerTPH = 8;
                    //     } else if ($buahPerTPH >= 0.5 && $buahPerTPH <= 1) {
                    //         $skor_buahPerTPH = 6;
                    //     } else if ($buahPerTPH >= 1.0 && $buahPerTPH <= 1.5) {
                    //         $skor_buahPerTPH = 4;
                    //     } else if ($buahPerTPH >= 1.5 && $buahPerTPH <= 2.0) {
                    //         $skor_buahPerTPH = 2;
                    //     } else if ($buahPerTPH >= 2.0 && $buahPerTPH <= 2.5) {
                    //         $skor_buahPerTPH = 0;
                    //     } else if ($buahPerTPH >= 2.5 && $buahPerTPH <= 3.0) {
                    //         $skor_buahPerTPH = 2;
                    //     } else if ($buahPerTPH >= 3.0 && $buahPerTPH <= 3.5) {
                    //         $skor_buahPerTPH = 4;
                    //     } else if ($buahPerTPH >= 3.5 && $buahPerTPH <= 4.0) {
                    //         $skor_buahPerTPH = 6;
                    //     } else if ($buahPerTPH >= 4.0) {
                    //         $skor_buahPerTPH = 8;
                    //     }
                    // } else {
                    //     $skor_buahPerTPH = 0;
                    // }

                    $skor_buahPerTPH = 0;
                    if ($buahPerTPH <= 0.0) {
                        $skor_buahPerTPH = 10;
                    } else if ($buahPerTPH >= 0.0 && $buahPerTPH <= 0.5) {
                        $skor_buahPerTPH = 8;
                    } else if ($buahPerTPH >= 0.5 && $buahPerTPH <= 1) {
                        $skor_buahPerTPH = 6;
                    } else if ($buahPerTPH >= 1.0 && $buahPerTPH <= 1.5) {
                        $skor_buahPerTPH = 4;
                    } else if ($buahPerTPH >= 1.5 && $buahPerTPH <= 2.0) {
                        $skor_buahPerTPH = 2;
                    } else if ($buahPerTPH >= 2.0 && $buahPerTPH <= 2.5) {
                        $skor_buahPerTPH = 0;
                    } else if ($buahPerTPH >= 2.5 && $buahPerTPH <= 3.0) {
                        $skor_buahPerTPH = 2;
                    } else if ($buahPerTPH >= 3.0 && $buahPerTPH <= 3.5) {
                        $skor_buahPerTPH = 4;
                    } else if ($buahPerTPH >= 3.5 && $buahPerTPH <= 4.0) {
                        $skor_buahPerTPH = 6;
                    } else if ($buahPerTPH >= 4.0) {
                        $skor_buahPerTPH = 8;
                    }



                    $totalSkor = $skor_buahPerTPH + $skor_brdPertph;

                    $mutuTransEst[$key][$key1]['total_sampleEST'] = $total_sample;
                    $mutuTransEst[$key][$key1]['total_brdEST'] = $total_brd;
                    $mutuTransEst[$key][$key1]['total_brdPertphEST'] = $brdPertph;
                    $mutuTransEst[$key][$key1]['total_buahEST'] = $total_buah;
                    $mutuTransEst[$key][$key1]['total_buahPertphEST'] = $buahPerTPH;
                    $mutuTransEst[$key][$key1]['skor_brd'] = $skor_brdPertph;
                    $mutuTransEst[$key][$key1]['skor_buah'] = $skor_buahPerTPH;
                    $mutuTransEst[$key][$key1]['total_skor'] = $totalSkor;
                } else {
                    $mutuTransEst[$key][$key1]['total_sampleEST'] = 0;
                    $mutuTransEst[$key][$key1]['total_brdEST'] = 0;
                    $mutuTransEst[$key][$key1]['total_brdPertphEST'] = 0;
                    $mutuTransEst[$key][$key1]['total_buahEST'] = 0;
                    $mutuTransEst[$key][$key1]['total_buahPertphEST'] = 0;
                    $mutuTransEst[$key][$key1]['skor_brd'] = 0;
                    $mutuTransEst[$key][$key1]['skor_buah'] = 0;
                    $mutuTransEst[$key][$key1]['total_skor'] = 0;
                }
            }
        }

        // dd($mutuTransEst);
        //menghitung estate per tahun
        $mutuTransTahun = array();
        foreach ($mutuTransEst as $key => $value)
            if (!empty($value)) {
                $sum_brd = 0;
                $sum_buah = 0;
                $sum_TPH = 0;
                $brdPertph = 0;
                $buahPerTPH = 0;
                $totalSkor = 0;
                foreach ($value as $key1 => $value2) {
                    // dd($value2);
                    $sum_brd += $value2['total_brdEST'];
                    $sum_buah += $value2['total_buahEST'];
                    $sum_TPH += $value2['total_sampleEST'];
                }

                if ($sum_TPH != 0) {
                    $brdPertph = round($sum_brd / $sum_TPH, 2);
                } else {
                    $brdPertph = 0;
                }

                if ($total_sample != 0) {
                    $buahPerTPH = round($sum_buah / $sum_TPH, 2);
                } else {
                    $buahPerTPH = 0;
                }

                //menghitung skor butir
                $skor_brdPertph = 0;
                if ($brdPertph <= 3) {
                    $skor_brdPertph = 10;
                } else if ($brdPertph >= 3 && $brdPertph <= 5) {
                    $skor_brdPertph = 8;
                } else if ($brdPertph >= 5 && $brdPertph <= 7) {
                    $skor_brdPertph = 6;
                } else if ($brdPertph >= 7 && $brdPertph <= 9) {
                    $skor_brdPertph = 4;
                } else if ($brdPertph >= 9 && $brdPertph <= 11) {
                    $skor_brdPertph = 2;
                } else if ($brdPertph >= 11) {
                    $skor_brdPertph = 0;
                }


                $skor_buahPerTPH = 0;
                if ($buahPerTPH <= 0.0) {
                    $skor_buahPerTPH = 10;
                } else if ($buahPerTPH >= 0.0 && $buahPerTPH <= 0.5) {
                    $skor_buahPerTPH = 8;
                } else if ($buahPerTPH >= 0.5 && $buahPerTPH <= 1) {
                    $skor_buahPerTPH = 6;
                } else if ($buahPerTPH >= 1.0 && $buahPerTPH <= 1.5) {
                    $skor_buahPerTPH = 4;
                } else if ($buahPerTPH >= 1.5 && $buahPerTPH <= 2.0) {
                    $skor_buahPerTPH = 2;
                } else if ($buahPerTPH >= 2.0 && $buahPerTPH <= 2.5) {
                    $skor_buahPerTPH = 0;
                } else if ($buahPerTPH >= 2.5 && $buahPerTPH <= 3.0) {
                    $skor_buahPerTPH = 2;
                } else if ($buahPerTPH >= 3.0 && $buahPerTPH <= 3.5) {
                    $skor_buahPerTPH = 4;
                } else if ($buahPerTPH >= 3.5 && $buahPerTPH <= 4.0) {
                    $skor_buahPerTPH = 6;
                } else if ($buahPerTPH >= 4.0) {
                    $skor_buahPerTPH = 8;
                }



                $totalSkor = $skor_buahPerTPH + $skor_brdPertph;


                $mutuTransTahun[$key]['total_brd'] = $sum_brd;
                $mutuTransTahun[$key]['total_brdPerTPH'] = $brdPertph;
                $mutuTransTahun[$key]['total_buah'] = $sum_buah;
                $mutuTransTahun[$key]['total_sample'] = $sum_TPH;
                $mutuTransTahun[$key]['skor_brd'] = $skor_brdPertph;
                $mutuTransTahun[$key]['skor_buah'] = $skor_buahPerTPH;
                $mutuTransTahun[$key]['skor_total'] = $totalSkor;
            } else {
                $mutuTransTahun[$key]['total_brd']  = 0;
                $mutuTransTahun[$key]['total_brdPerTPH'] = 0;
                $mutuTransTahun[$key]['total_buah'] = 0;
                $mutuTransTahun[$key]['total_sample'] = 0;
                $mutuTransTahun[$key]['skor_brd']  = 0;
                $mutuTransTahun[$key]['skor_buah']  = 0;
                $mutuTransTahun[$key]['skor_total']  = 0;
            }
        // dd($mutuTransTahun);




        //end perhitungan untuk transport



        // untuk hitung hitungan 
        //perhitungan data untuk mutu buah afd per bulan
        $bulananBh = array();
        foreach ($defaultMTbh as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2)
                    if (is_array($value2)) {
                        $sum_bmt = 0;
                        $sum_bmk = 0;
                        $sum_over = 0;
                        $sum_Samplejjg = 0;
                        $PerMth = 0;
                        $PerMsk = 0;
                        $PerOver = 0;
                        $sum_abnor = 0;
                        $sum_kosongjjg = 0;
                        $Perkosongjjg = 0;
                        $sum_vcut = 0;
                        $PerVcut = 0;
                        $PerAbr = 0;
                        $sum_kr = 0;
                        $total_kr = 0;
                        $per_kr = 0;
                        $totalSkor = 0;
                        $combination_counts = array();
                        foreach ($value2 as $key3 => $value3) {
                            $combination = $value3['blok'] . ' ' . $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'];
                            if (!isset($combination_counts[$combination])) {
                                $combination_counts[$combination] = 0;
                            }
                            $combination_counts[$combination]++;
                            $sum_bmt += $value3['bmt'];
                            $sum_bmk += $value3['bmk'];
                            $sum_over += $value3['overripe'];
                            $sum_kosongjjg += $value3['empty'];
                            $sum_vcut += $value3['vcut'];
                            $sum_kr += $value3['alas_br'];


                            $sum_Samplejjg += $value3['jumlah_jjg'];
                            $sum_abnor += $value3['abnormal'];
                        }

                        $dataBLok = count($combination_counts);


                        if ($sum_kr != 0) {
                            $total_kr = round($dataBLok / $sum_kr, 2);
                        } else {
                            $total_kr = 0;
                        }

                        $per_kr = round($total_kr * 100, 2);
                        $PerMth = round(($sum_bmt / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                        $PerMsk = round(($sum_bmk / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                        $PerOver = round(($sum_over / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                        $Perkosongjjg = round(($sum_kosongjjg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                        $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
                        $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);

                        // skoring buah mentah
                        $skor_PerMth = 0;
                        if ($PerMth <= 1.0) {
                            $skor_PerMth = 10;
                        } else if ($PerMth >= 1.0 && $PerMth <= 2.0) {
                            $skor_PerMth = 8;
                        } else if ($PerMth >= 2.0 && $PerMth <= 3.0) {
                            $skor_PerMth = 6;
                        } else if ($PerMth >= 3.0 && $PerMth <= 4.0) {
                            $skor_PerMth = 4;
                        } else if ($PerMth >= 4.0 && $PerMth <= 5.0) {
                            $skor_PerMth = 2;
                        } else if ($PerMth >= 5.0) {
                            $skor_PerMth = 0;
                        }

                        // skoring buah masak
                        $skor_PerMsk = 0;
                        if ($PerMsk <= 75.0) {
                            $skor_PerMsk = 0;
                        } else if ($PerMsk >= 75.0 && $PerMsk <= 80.0) {
                            $skor_PerMsk = 1;
                        } else if ($PerMsk >= 80.0 && $PerMsk <= 85.0) {
                            $skor_PerMsk = 2;
                        } else if ($PerMsk >= 85.0 && $PerMsk <= 90.0) {
                            $skor_PerMsk = 3;
                        } else if ($PerMsk >= 90.0 && $PerMsk <= 95.0) {
                            $skor_PerMsk = 4;
                        } else if ($PerMsk >= 95.0) {
                            $skor_PerMsk = 5;
                        }

                        // skoring buah over
                        $skor_PerOver = 0;
                        if ($PerOver <= 2.0) {
                            $skor_PerOver = 5;
                        } else if ($PerOver >= 2.0 && $PerOver <= 4.0) {
                            $skor_PerOver = 4;
                        } else if ($PerOver >= 4.0 && $PerOver <= 6.0) {
                            $skor_PerOver = 3;
                        } else if ($PerOver >= 6.0 && $PerOver <= 8.0) {
                            $skor_PerOver = 2;
                        } else if ($PerOver >= 8.0 && $PerOver <= 10.0) {
                            $skor_PerOver = 1;
                        } else if ($PerOver >= 10.0) {
                            $skor_PerOver = 0;
                        }


                        //skor janjang kosong
                        $skor_Perkosongjjg = 0;
                        if ($Perkosongjjg <= 1.0) {
                            $skor_Perkosongjjg = 5;
                        } else if ($Perkosongjjg >= 1.0 && $Perkosongjjg <= 2.0) {
                            $skor_Perkosongjjg = 4;
                        } else if ($Perkosongjjg >= 2.0 && $Perkosongjjg <= 3.0) {
                            $skor_Perkosongjjg = 3;
                        } else if ($Perkosongjjg >= 3.0 && $Perkosongjjg <= 4.0) {
                            $skor_Perkosongjjg = 2;
                        } else if ($Perkosongjjg >= 4.0 && $Perkosongjjg <= 5.0) {
                            $skor_Perkosongjjg = 1;
                        } else if ($Perkosongjjg >= 5.0) {
                            $skor_Perkosongjjg = 0;
                        }

                        //skore Vcut
                        $skor_PerVcut = 0;
                        if ($PerVcut <= 2.0) {
                            $skor_PerVcut = 5;
                        } else if ($PerVcut >= 2.0 && $PerVcut <= 4.0) {
                            $skor_PerVcut = 4;
                        } else if ($PerVcut >= 4.0 && $PerVcut <= 6.0) {
                            $skor_PerVcut = 3;
                        } else if ($PerVcut >= 6.0 && $PerVcut <= 8.0) {
                            $skor_PerVcut = 2;
                        } else if ($PerVcut >= 8.0 && $PerVcut <= 10.0) {
                            $skor_PerVcut = 1;
                        } else if ($PerVcut >= 10.0) {
                            $skor_PerVcut = 0;
                        }

                        // blum di cek skornya di bawah
                        //skore PEnggunnan Brondolan
                        $skor_PerAbr = 0;
                        if ($PerAbr <= 75.0) {
                            $skor_PerAbr = 0;
                        } else if ($PerAbr >= 75.0 && $PerAbr <= 80.0) {
                            $skor_PerAbr = 1;
                        } else if ($PerAbr >= 80.0 && $PerAbr <= 85.0) {
                            $skor_PerAbr = 2;
                        } else if ($PerAbr >= 85.0 && $PerAbr <= 90.0) {
                            $skor_PerAbr = 3;
                        } else if ($PerAbr >= 90.0 && $PerAbr <= 95.0) {
                            $skor_PerAbr = 4;
                        } else if ($PerAbr >= 95.0) {
                            $skor_PerAbr = 5;
                        }

                        $skor_per_kr = 0;
                        if ($per_kr <= 60) {
                            $skor_per_kr = 0;
                        } else if ($per_kr >= 60 && $per_kr <= 70) {
                            $skor_per_kr = 1;
                        } else if ($per_kr >= 70 && $per_kr <= 80) {
                            $skor_per_kr = 2;
                        } else if ($per_kr >= 80 && $per_kr <= 90) {
                            $skor_per_kr = 3;
                        } else if ($per_kr >= 90 && $per_kr <= 100) {
                            $skor_per_kr = 4;
                        } else if ($per_kr >= 100) {
                            $skor_per_kr = 5;
                        }

                        $totalSkor =  $skor_PerMth + $skor_PerMsk + $skor_PerOver +  $skor_Perkosongjjg + $skor_PerVcut + $skor_PerAbr +  $skor_per_kr;

                        $bulananBh[$key][$key1][$key2]['tph_baris_blok'] = $dataBLok;
                        $bulananBh[$key][$key1][$key2]['sampleJJG_total'] = $sum_Samplejjg;
                        $bulananBh[$key][$key1][$key2]['total_mentah'] = $sum_bmt;
                        $bulananBh[$key][$key1][$key2]['total_perMentah'] = $PerMth;
                        $bulananBh[$key][$key1][$key2]['total_masak'] = $sum_bmk;
                        $bulananBh[$key][$key1][$key2]['total_perMasak'] = $PerMsk;
                        $bulananBh[$key][$key1][$key2]['total_over'] = $sum_over;
                        $bulananBh[$key][$key1][$key2]['total_perOver'] = $PerOver;
                        $bulananBh[$key][$key1][$key2]['total_abnormal'] = $sum_abnor;
                        $bulananBh[$key][$key1][$key2]['total_jjgKosong'] = $sum_kosongjjg;
                        $bulananBh[$key][$key1][$key2]['total_perKosongjjg'] = $Perkosongjjg;
                        $bulananBh[$key][$key1][$key2]['total_vcut'] = $sum_vcut;

                        $bulananBh[$key][$key1][$key2]['jum_kr'] = $sum_kr;
                        $bulananBh[$key][$key1][$key2]['total_kr'] = $total_kr;
                        $bulananBh[$key][$key1][$key2]['persen_kr'] = $per_kr;

                        // skoring
                        $bulananBh[$key][$key1][$key2]['skor_mentah'] = $skor_PerMth;
                        $bulananBh[$key][$key1][$key2]['skor_masak'] = $skor_PerMsk;
                        $bulananBh[$key][$key1][$key2]['skor_over'] = $skor_PerOver;
                        $bulananBh[$key][$key1][$key2]['skor_jjgKosong'] = $skor_Perkosongjjg;
                        $bulananBh[$key][$key1][$key2]['skor_vcut'] = $skor_PerVcut;
                        $bulananBh[$key][$key1][$key2]['skor_abnormal'] = $skor_PerAbr;
                        $bulananBh[$key][$key1][$key2]['skor_kr'] = $skor_per_kr;
                        $bulananBh[$key][$key1][$key2]['TOTAL_SKOR'] = $totalSkor;
                    } else {

                        $bulananBh[$key][$key1][$key2]['tph_baris_blok'] = 0;
                        $bulananBh[$key][$key1][$key2]['sampleJJG_total'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_mentah'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_perMentah'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_masak'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_perMasak'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_over'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_perOver'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_abnormal'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_jjgKosong'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_perKosongjjg'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_vcut'] = 0;
                        $bulananBh[$key][$key1][$key2]['jum_kr'] = 0;
                        $bulananBh[$key][$key1][$key2]['total_kr'] = 0;
                        $bulananBh[$key][$key1][$key2]['persen_kr'] = 0;

                        // skoring
                        $bulananBh[$key][$key1][$key2]['skor_mentah'] = 0;
                        $bulananBh[$key][$key1][$key2]['skor_masak'] = 0;
                        $bulananBh[$key][$key1][$key2]['skor_over'] = 0;
                        $bulananBh[$key][$key1][$key2]['skor_jjgKosong'] = 0;
                        $bulananBh[$key][$key1][$key2]['skor_vcut'] = 0;
                        $bulananBh[$key][$key1][$key2]['skor_abnormal'] = 0;;
                        $bulananBh[$key][$key1][$key2]['skor_kr'] = 0;
                        $bulananBh[$key][$key1][$key2]['TOTAL_SKOR'] = 0;
                    }
            }
        }
        // dd($bulananBh);
        //mutu buah perbulan per estate
        $bulananEST = array();
        foreach ($bulananBh as $key => $value) {
            foreach ($value as $key1 => $value2)
                if (!empty($value2)) {
                    $tph_blok = 0;
                    $jjgMth = 0;
                    $sampleJJG = 0;
                    $jjgAbn = 0;
                    $PerMth = 0;
                    $PerMsk = 0;
                    $PerOver = 0;
                    $Perkosongjjg = 0;
                    $PerVcut = 0;
                    $PerAbr = 0;
                    $per_kr = 0;
                    $jjgMsk = 0;
                    $jjgOver = 0;
                    $jjgKosng = 0;
                    $vcut = 0;
                    $jum_kr = 0;
                    $total_kr = 0;
                    $totalSkor = 0;
                    foreach ($value2 as $key2 => $value3) {
                        // dd($value3);
                        $tph_blok += $value3['tph_baris_blok'];
                        $sampleJJG += $value3['sampleJJG_total'];
                        $jjgMth += $value3['total_mentah'];
                        $jjgMsk += $value3['total_masak'];
                        $jjgOver += $value3['total_over'];
                        $jjgKosng += $value3['total_jjgKosong'];
                        $vcut += $value3['total_vcut'];
                        $jum_kr += $value3['jum_kr'];

                        $jjgAbn += $value3['total_abnormal'];
                    }

                    if ($jum_kr != 0) {
                        $total_kr = round($tph_blok / $jum_kr, 2);
                    } else {
                        $total_kr = 0;
                    }

                    if ($sampleJJG != 0 && $jjgAbn != 0) {
                        $PerMth = round(($jjgMth / ($sampleJJG - $jjgAbn)) * 100, 2);
                    } else {
                        $PerMth = 0;
                    }

                    if ($sampleJJG != 0 && $jjgAbn != 0) {
                        $PerMsk = round(($jjgMsk / ($sampleJJG - $jjgAbn)) * 100, 2);
                    } else {
                        $PerMsk = 0;
                    }

                    if ($sampleJJG != 0 && $jjgAbn != 0) {
                        $PerOver = round(($jjgOver / ($sampleJJG - $jjgAbn)) * 100, 2);
                    } else {
                        $PerOver = 0;
                    }

                    if ($sampleJJG != 0 && $jjgAbn != 0) {
                        $Perkosongjjg = round(($jjgKosng / ($sampleJJG - $jjgAbn)) * 100, 2);
                    } else {
                        $Perkosongjjg = 0;
                    }

                    if ($sampleJJG != 0) {
                        $PerVcut = round(($vcut / $sampleJJG) * 100, 2);
                    } else {
                        $PerVcut = 0;
                    }

                    if ($sampleJJG != 0) {
                        $PerAbr = round(($jjgAbn / $sampleJJG) * 100, 2);
                    } else {
                        $PerAbr = 0;
                    }

                    $per_kr = round($total_kr * 100, 2);

                    // skoring buah mentah
                    $skor_PerMth = 0;
                    if ($PerMth <= 1.0) {
                        $skor_PerMth = 10;
                    } else if ($PerMth >= 1.0 && $PerMth <= 2.0) {
                        $skor_PerMth = 8;
                    } else if ($PerMth >= 2.0 && $PerMth <= 3.0) {
                        $skor_PerMth = 6;
                    } else if ($PerMth >= 3.0 && $PerMth <= 4.0) {
                        $skor_PerMth = 4;
                    } else if ($PerMth >= 4.0 && $PerMth <= 5.0) {
                        $skor_PerMth = 2;
                    } else if ($PerMth >= 5.0) {
                        $skor_PerMth = 0;
                    }

                    // skoring buah masak
                    $skor_PerMsk = 0;
                    if ($PerMsk <= 75.0) {
                        $skor_PerMsk = 0;
                    } else if ($PerMsk >= 75.0 && $PerMsk <= 80.0) {
                        $skor_PerMsk = 1;
                    } else if ($PerMsk >= 80.0 && $PerMsk <= 85.0) {
                        $skor_PerMsk = 2;
                    } else if ($PerMsk >= 85.0 && $PerMsk <= 90.0) {
                        $skor_PerMsk = 3;
                    } else if ($PerMsk >= 90.0 && $PerMsk <= 95.0) {
                        $skor_PerMsk = 4;
                    } else if ($PerMsk >= 95.0) {
                        $skor_PerMsk = 5;
                    }

                    // skoring buah over
                    $skor_PerOver = 0;
                    if ($PerOver <= 2.0) {
                        $skor_PerOver = 5;
                    } else if ($PerOver >= 2.0 && $PerOver <= 4.0) {
                        $skor_PerOver = 4;
                    } else if ($PerOver >= 4.0 && $PerOver <= 6.0) {
                        $skor_PerOver = 3;
                    } else if ($PerOver >= 6.0 && $PerOver <= 8.0) {
                        $skor_PerOver = 2;
                    } else if ($PerOver >= 8.0 && $PerOver <= 10.0) {
                        $skor_PerOver = 1;
                    } else if ($PerOver >= 10.0) {
                        $skor_PerOver = 0;
                    }


                    //skor janjang kosong
                    $skor_Perkosongjjg = 0;
                    if ($Perkosongjjg <= 1.0) {
                        $skor_Perkosongjjg = 5;
                    } else if ($Perkosongjjg >= 1.0 && $Perkosongjjg <= 2.0) {
                        $skor_Perkosongjjg = 4;
                    } else if ($Perkosongjjg >= 2.0 && $Perkosongjjg <= 3.0) {
                        $skor_Perkosongjjg = 3;
                    } else if ($Perkosongjjg >= 3.0 && $Perkosongjjg <= 4.0) {
                        $skor_Perkosongjjg = 2;
                    } else if ($Perkosongjjg >= 4.0 && $Perkosongjjg <= 5.0) {
                        $skor_Perkosongjjg = 1;
                    } else if ($Perkosongjjg >= 5.0) {
                        $skor_Perkosongjjg = 0;
                    }

                    //skore Vcut
                    $skor_PerVcut = 0;
                    if ($PerVcut <= 2.0) {
                        $skor_PerVcut = 5;
                    } else if ($PerVcut >= 2.0 && $PerVcut <= 4.0) {
                        $skor_PerVcut = 4;
                    } else if ($PerVcut >= 4.0 && $PerVcut <= 6.0) {
                        $skor_PerVcut = 3;
                    } else if ($PerVcut >= 6.0 && $PerVcut <= 8.0) {
                        $skor_PerVcut = 2;
                    } else if ($PerVcut >= 8.0 && $PerVcut <= 10.0) {
                        $skor_PerVcut = 1;
                    } else if ($PerVcut >= 10.0) {
                        $skor_PerVcut = 0;
                    }

                    // blum di cek skornya di bawah
                    //skore PEnggunnan Brondolan
                    $skor_PerAbr = 0;
                    if ($PerAbr <= 75.0) {
                        $skor_PerAbr = 0;
                    } else if ($PerAbr >= 75.0 && $PerAbr <= 80.0) {
                        $skor_PerAbr = 1;
                    } else if ($PerAbr >= 80.0 && $PerAbr <= 85.0) {
                        $skor_PerAbr = 2;
                    } else if ($PerAbr >= 85.0 && $PerAbr <= 90.0) {
                        $skor_PerAbr = 3;
                    } else if ($PerAbr >= 90.0 && $PerAbr <= 95.0) {
                        $skor_PerAbr = 4;
                    } else if ($PerAbr >= 95.0) {
                        $skor_PerAbr = 5;
                    }

                    $skor_per_kr = 0;
                    if ($per_kr <= 60) {
                        $skor_per_kr = 0;
                    } else if ($per_kr >= 60 && $per_kr <= 70) {
                        $skor_per_kr = 1;
                    } else if ($per_kr >= 70 && $per_kr <= 80) {
                        $skor_per_kr = 2;
                    } else if ($per_kr >= 80 && $per_kr <= 90) {
                        $skor_per_kr = 3;
                    } else if ($per_kr >= 90 && $per_kr <= 100) {
                        $skor_per_kr = 4;
                    } else if ($per_kr >= 100) {
                        $skor_per_kr = 5;
                    }

                    $totalSkor =  $skor_PerMth + $skor_PerMsk + $skor_PerOver +  $skor_Perkosongjjg + $skor_PerVcut + $skor_PerAbr +  $skor_per_kr;

                    $bulananEST[$key][$key1]['blok'] = $tph_blok;
                    $bulananEST[$key][$key1]['sample_jjg'] = $sampleJJG;

                    $bulananEST[$key][$key1]['jjg_mentah'] = $jjgMth;
                    $bulananEST[$key][$key1]['mentahPerjjg'] = $PerMth;

                    $bulananEST[$key][$key1]['jjg_msk'] = $jjgMsk;
                    $bulananEST[$key][$key1]['mskPerjjg'] = $PerMsk;

                    $bulananEST[$key][$key1]['jjg_over'] = $jjgOver;
                    $bulananEST[$key][$key1]['overPerjjg'] = $PerOver;

                    $bulananEST[$key][$key1]['jjg_kosong'] = $jjgKosng;
                    $bulananEST[$key][$key1]['kosongPerjjg'] = $Perkosongjjg;

                    $bulananEST[$key][$key1]['v_cut'] = $vcut;
                    $bulananEST[$key][$key1]['vcutPerjjg'] = $PerVcut;

                    $bulananEST[$key][$key1]['jjg_abr'] = $jjgAbn;
                    $bulananEST[$key][$key1]['krPer'] = $per_kr;

                    $bulananEST[$key][$key1]['jum_kr'] = $jum_kr;
                    $bulananEST[$key][$key1]['abrPerjjg'] = $PerAbr;

                    $bulananEST[$key][$key1]['skor_mentah'] = $skor_PerMth;
                    $bulananEST[$key][$key1]['skor_msak'] =   $skor_PerMsk;
                    $bulananEST[$key][$key1]['skor_over'] =  $skor_PerOver;
                    $bulananEST[$key][$key1]['skor_kosong'] = $skor_Perkosongjjg;
                    $bulananEST[$key][$key1]['skor_vcut'] = $skor_PerVcut;
                    $bulananEST[$key][$key1]['skor_karung'] = $skor_per_kr;
                    $bulananEST[$key][$key1]['skor_abnormal'] = $skor_PerAbr;
                    $bulananEST[$key][$key1]['totalSkor'] = $totalSkor;
                } else {
                    $bulananEST[$key][$key1]['blok'] = 0;
                    $bulananEST[$key][$key1]['sample_jjg'] = 0;

                    $bulananEST[$key][$key1]['jjg_mentah'] = 0;
                    $bulananEST[$key][$key1]['mentahPerjjg'] = 0;

                    $bulananEST[$key][$key1]['jjg_msk'] = 0;
                    $bulananEST[$key][$key1]['mskPerjjg'] = 0;

                    $bulananEST[$key][$key1]['jjg_over'] = 0;
                    $bulananEST[$key][$key1]['overPerjjg'] = 0;

                    $bulananEST[$key][$key1]['jjg_kosong'] = 0;
                    $bulananEST[$key][$key1]['kosongPerjjg'] = 0;

                    $bulananEST[$key][$key1]['v_cut'] = 0;
                    $bulananEST[$key][$key1]['vcutPerjjg'] = 0;

                    $bulananEST[$key][$key1]['jjg_abr'] = 0;
                    $bulananEST[$key][$key1]['krPer'] = 0;

                    $bulananEST[$key][$key1]['jum_kr'] = 0;
                    $bulananEST[$key][$key1]['abrPerjjg'] = 0;

                    $bulananEST[$key][$key1]['skor_mentah'] = 0;
                    $bulananEST[$key][$key1]['skor_msak'] =  0;
                    $bulananEST[$key][$key1]['skor_over'] = 0;
                    $bulananEST[$key][$key1]['skor_kosong'] = 0;
                    $bulananEST[$key][$key1]['skor_vcut'] = 0;
                    $bulananEST[$key][$key1]['skor_karung'] = 0;
                    $bulananEST[$key][$key1]['skor_abnormal'] = 0;
                    $bulananEST[$key][$key1]['totalSkor'] = 0;
                }
        }

        // dd($bulananEST);
        // mutu buah pertahun 
        $TahunMtBuah = array();
        foreach ($bulananEST as $key => $value)
            if (!empty($value)) {
                $tph_blok = 0;
                $jjgMth = 0;
                $sampleJJG = 0;
                $jjgAbn = 0;
                $PerMth = 0;
                $PerMsk = 0;
                $PerOver = 0;
                $Perkosongjjg = 0;
                $PerVcut = 0;
                $PerAbr = 0;
                $per_kr = 0;
                $jjgMsk = 0;
                $jjgOver = 0;
                $jjgKosng = 0;
                $vcut = 0;
                $jum_kr = 0;
                $total_kr = 0;
                $totalSkor = 0;
                foreach ($value as $key2 => $value2) {
                    $tph_blok += $value2['blok'];
                    $sampleJJG += $value2['sample_jjg'];
                    $jjgMth += $value2['jjg_mentah'];
                    $jjgMsk += $value2['jjg_msk'];
                    $jjgOver += $value2['jjg_over'];
                    $jjgKosng += $value2['jjg_kosong'];
                    $vcut += $value2['v_cut'];
                    $jum_kr += $value2['jum_kr'];

                    $jjgAbn += $value2['jjg_abr'];
                }


                if ($jum_kr != 0) {
                    $total_kr = round($tph_blok / $jum_kr, 2);
                } else {
                    $total_kr = 0;
                }

                if ($sampleJJG != 0 && $jjgAbn != 0) {
                    $PerMth = round(($jjgMth / ($sampleJJG - $jjgAbn)) * 100, 2);
                } else {
                    $PerMth = 0;
                }

                if ($sampleJJG != 0 && $jjgAbn != 0) {
                    $PerMsk = round(($jjgMsk / ($sampleJJG - $jjgAbn)) * 100, 2);
                } else {
                    $PerMsk = 0;
                }

                if ($sampleJJG != 0 && $jjgAbn != 0) {
                    $PerOver = round(($jjgOver / ($sampleJJG - $jjgAbn)) * 100, 2);
                } else {
                    $PerOver = 0;
                }

                if ($sampleJJG != 0 && $jjgAbn != 0) {
                    $Perkosongjjg = round(($jjgKosng / ($sampleJJG - $jjgAbn)) * 100, 2);
                } else {
                    $Perkosongjjg = 0;
                }

                if ($sampleJJG != 0) {
                    $PerVcut = round(($vcut / $sampleJJG) * 100, 2);
                } else {
                    $PerVcut = 0;
                }

                if ($sampleJJG != 0) {
                    $PerAbr = round(($jjgAbn / $sampleJJG) * 100, 2);
                } else {
                    $PerAbr = 0;
                }

                $per_kr = round($total_kr * 100, 2);

                // skoring buah mentah
                $skor_PerMth = 0;
                if ($PerMth <= 1.0) {
                    $skor_PerMth = 10;
                } else if ($PerMth >= 1.0 && $PerMth <= 2.0) {
                    $skor_PerMth = 8;
                } else if ($PerMth >= 2.0 && $PerMth <= 3.0) {
                    $skor_PerMth = 6;
                } else if ($PerMth >= 3.0 && $PerMth <= 4.0) {
                    $skor_PerMth = 4;
                } else if ($PerMth >= 4.0 && $PerMth <= 5.0) {
                    $skor_PerMth = 2;
                } else if ($PerMth >= 5.0) {
                    $skor_PerMth = 0;
                }

                // skoring buah masak
                $skor_PerMsk = 0;
                if ($PerMsk <= 75.0) {
                    $skor_PerMsk = 0;
                } else if ($PerMsk >= 75.0 && $PerMsk <= 80.0) {
                    $skor_PerMsk = 1;
                } else if ($PerMsk >= 80.0 && $PerMsk <= 85.0) {
                    $skor_PerMsk = 2;
                } else if ($PerMsk >= 85.0 && $PerMsk <= 90.0) {
                    $skor_PerMsk = 3;
                } else if ($PerMsk >= 90.0 && $PerMsk <= 95.0) {
                    $skor_PerMsk = 4;
                } else if ($PerMsk >= 95.0) {
                    $skor_PerMsk = 5;
                }

                // skoring buah over
                $skor_PerOver = 0;
                if ($PerOver <= 2.0) {
                    $skor_PerOver = 5;
                } else if ($PerOver >= 2.0 && $PerOver <= 4.0) {
                    $skor_PerOver = 4;
                } else if ($PerOver >= 4.0 && $PerOver <= 6.0) {
                    $skor_PerOver = 3;
                } else if ($PerOver >= 6.0 && $PerOver <= 8.0) {
                    $skor_PerOver = 2;
                } else if ($PerOver >= 8.0 && $PerOver <= 10.0) {
                    $skor_PerOver = 1;
                } else if ($PerOver >= 10.0) {
                    $skor_PerOver = 0;
                }


                //skor janjang kosong
                $skor_Perkosongjjg = 0;
                if ($Perkosongjjg <= 1.0) {
                    $skor_Perkosongjjg = 5;
                } else if ($Perkosongjjg >= 1.0 && $Perkosongjjg <= 2.0) {
                    $skor_Perkosongjjg = 4;
                } else if ($Perkosongjjg >= 2.0 && $Perkosongjjg <= 3.0) {
                    $skor_Perkosongjjg = 3;
                } else if ($Perkosongjjg >= 3.0 && $Perkosongjjg <= 4.0) {
                    $skor_Perkosongjjg = 2;
                } else if ($Perkosongjjg >= 4.0 && $Perkosongjjg <= 5.0) {
                    $skor_Perkosongjjg = 1;
                } else if ($Perkosongjjg >= 5.0) {
                    $skor_Perkosongjjg = 0;
                }

                //skore Vcut
                $skor_PerVcut = 0;
                if ($PerVcut <= 2.0) {
                    $skor_PerVcut = 5;
                } else if ($PerVcut >= 2.0 && $PerVcut <= 4.0) {
                    $skor_PerVcut = 4;
                } else if ($PerVcut >= 4.0 && $PerVcut <= 6.0) {
                    $skor_PerVcut = 3;
                } else if ($PerVcut >= 6.0 && $PerVcut <= 8.0) {
                    $skor_PerVcut = 2;
                } else if ($PerVcut >= 8.0 && $PerVcut <= 10.0) {
                    $skor_PerVcut = 1;
                } else if ($PerVcut >= 10.0) {
                    $skor_PerVcut = 0;
                }

                // blum di cek skornya di bawah
                //skore PEnggunnan Brondolan
                $skor_PerAbr = 0;
                if ($PerAbr <= 75.0) {
                    $skor_PerAbr = 0;
                } else if ($PerAbr >= 75.0 && $PerAbr <= 80.0) {
                    $skor_PerAbr = 1;
                } else if ($PerAbr >= 80.0 && $PerAbr <= 85.0) {
                    $skor_PerAbr = 2;
                } else if ($PerAbr >= 85.0 && $PerAbr <= 90.0) {
                    $skor_PerAbr = 3;
                } else if ($PerAbr >= 90.0 && $PerAbr <= 95.0) {
                    $skor_PerAbr = 4;
                } else if ($PerAbr >= 95.0) {
                    $skor_PerAbr = 5;
                }

                $skor_per_kr = 0;
                if ($per_kr <= 60) {
                    $skor_per_kr = 0;
                } else if ($per_kr >= 60 && $per_kr <= 70) {
                    $skor_per_kr = 1;
                } else if ($per_kr >= 70 && $per_kr <= 80) {
                    $skor_per_kr = 2;
                } else if ($per_kr >= 80 && $per_kr <= 90) {
                    $skor_per_kr = 3;
                } else if ($per_kr >= 90 && $per_kr <= 100) {
                    $skor_per_kr = 4;
                } else if ($per_kr >= 100) {
                    $skor_per_kr = 5;
                }

                $totalSkor =  $skor_PerMth + $skor_PerMsk + $skor_PerOver +  $skor_Perkosongjjg + $skor_PerVcut + $skor_PerAbr +  $skor_per_kr;

                $TahunMtBuah[$key]['blok'] = $tph_blok;
                $TahunMtBuah[$key]['sample_jjg'] = $sampleJJG;

                $TahunMtBuah[$key]['jjg_mentah'] = $jjgMth;
                $TahunMtBuah[$key]['mentahPerjjg'] = $PerMth;

                $TahunMtBuah[$key]['jjg_msk'] = $jjgMsk;
                $TahunMtBuah[$key]['mskPerjjg'] = $PerMsk;

                $TahunMtBuah[$key]['jjg_over'] = $jjgOver;
                $TahunMtBuah[$key]['overPerjjg'] = $PerOver;

                $TahunMtBuah[$key]['jjg_kosong'] = $jjgKosng;
                $TahunMtBuah[$key]['kosongPerjjg'] = $Perkosongjjg;

                $TahunMtBuah[$key]['v_cut'] = $vcut;
                $TahunMtBuah[$key]['vcutPerjjg'] = $PerVcut;

                $TahunMtBuah[$key]['jjg_abr'] = $jjgAbn;
                $TahunMtBuah[$key]['krPer'] = $per_kr;

                $TahunMtBuah[$key]['jum_kr'] = $jum_kr;
                $TahunMtBuah[$key]['abrPerjjg'] = $PerAbr;

                $TahunMtBuah[$key]['skor_mentah'] = $skor_PerMth;
                $TahunMtBuah[$key]['skor_msak'] =   $skor_PerMsk;
                $TahunMtBuah[$key]['skor_over'] =  $skor_PerOver;
                $TahunMtBuah[$key]['skor_kosong'] = $skor_Perkosongjjg;
                $TahunMtBuah[$key]['skor_vcut'] = $skor_PerVcut;
                $TahunMtBuah[$key]['skor_karung'] = $skor_per_kr;
                $TahunMtBuah[$key]['skor_abnormal'] = $skor_PerAbr;
                $TahunMtBuah[$key]['totalTahun_skor'] = $totalSkor;
            } else {
                $TahunMtBuah[$key]['blok'] = 0;
                $TahunMtBuah[$key]['sample_jjg'] = 0;

                $TahunMtBuah[$key]['jjg_mentah'] = 0;
                $TahunMtBuah[$key]['mentahPerjjg'] = 0;

                $TahunMtBuah[$key]['jjg_msk'] = 0;
                $TahunMtBuah[$key]['mskPerjjg'] = 0;

                $TahunMtBuah[$key]['jjg_over'] = 0;
                $TahunMtBuah[$key]['overPerjjg'] = 0;

                $TahunMtBuah[$key]['jjg_kosong'] = 0;
                $TahunMtBuah[$key]['kosongPerjjg'] = 0;

                $TahunMtBuah[$key]['v_cut'] = 0;
                $TahunMtBuah[$key]['vcutPerjjg'] = 0;

                $TahunMtBuah[$key]['jjg_abr'] = 0;
                $TahunMtBuah[$key]['krPer'] = 0;

                $TahunMtBuah[$key]['jum_kr'] = 0;
                $TahunMtBuah[$key]['abrPerjjg'] = 0;

                $TahunMtBuah[$key]['skor_mentah'] = 0;
                $TahunMtBuah[$key]['skor_msak'] =  0;
                $TahunMtBuah[$key]['skor_over'] = 0;
                $TahunMtBuah[$key]['skor_kosong'] = 0;
                $TahunMtBuah[$key]['skor_vcut'] = 0;
                $TahunMtBuah[$key]['skor_karung'] = 0;
                $TahunMtBuah[$key]['skor_abnormal'] = 0;
                $TahunMtBuah[$key]['totalTahun_skor'] = 0;
            }

        // dd($TahunMtBuah);
        //end perhitungan data untuk mutu buah

        //perhitungan data untu mutu ancak
        //hitung per afdeling 
        $dataTahunEst = array();
        foreach ($defaultNew as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) if (is_array($value3)) {
                    $akp = 0;
                    $skor_bTinggal = 0;
                    $brdPerjjg = 0;
                    $pokok_panen = 0;
                    $janjang_panen = 0;
                    $p_panen = 0;
                    $k_panen = 0;
                    $bhts_panen  = 0;
                    $bhtm1_panen  = 0;
                    $bhtm2_panen  = 0;
                    $bhtm3_oanen  = 0;
                    $ttlSkorMA = 0;
                    $listBlokPerAfd = array();
                    $jum_ha = 0;
                    $pelepah_s = 0;
                    foreach ($value3 as $key4 => $value4) if (is_array($value4)) {
                        if (!in_array($value4['estate'] . ' ' . $value4['afdeling'] . ' ' . $value4['blok'], $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $value4['estate'] . ' ' . $value4['afdeling'] . ' ' . $value4['blok'];
                        }
                        $jum_ha = count($listBlokPerAfd);

                        $pokok_panen = json_decode($value4["pokok_dipanen"], true);
                        $jajang_panen = json_decode($value4["jjg_dipanen"], true);
                        $brtp = json_decode($value4["brtp"], true);
                        $brtk = json_decode($value4["brtk"], true);
                        $brtgl = json_decode($value4["brtgl"], true);

                        $pokok_panen  = count($pokok_panen);
                        $janjang_panen = array_sum($jajang_panen);
                        $p_panen = array_sum($brtp);
                        $k_panen = array_sum($brtk);
                        $brtgl_panen = array_sum($brtgl);

                        // $akp = ($janjang_panen / $pokok_panen) %
                        $akp = ($janjang_panen / $pokok_panen) * 100;
                        $skor_bTinggal = $p_panen + $k_panen + $brtgl_panen;
                        $brdPerjjg = $skor_bTinggal / $pokok_panen;

                        //skore PEnggunnan Brondolan
                        $skor_brdPerjjg = 0;
                        if ($brdPerjjg <= 1.0) {
                            $skor_brdPerjjg = 20;
                        } else if ($brdPerjjg >= 1.5 && $brdPerjjg <= 2.0) {
                            $skor_brdPerjjg = 12;
                        } else if ($brdPerjjg >= 2.0 && $brdPerjjg <= 2.5) {
                            $skor_brdPerjjg = 8;
                        } else if ($brdPerjjg >= 2.5 && $brdPerjjg <= 3.0) {
                            $skor_brdPerjjg = 4;
                        } else if ($brdPerjjg >= 3.0 && $brdPerjjg <= 3.5) {
                            $skor_brdPerjjg = 0;
                        } else if ($brdPerjjg >= 4.0 && $brdPerjjg <= 4.5) {
                            $skor_brdPerjjg = 8;
                        } else if ($brdPerjjg >=  4.5 && $brdPerjjg <= 5.0) {
                            $skor_brdPerjjg = 12;
                        } else if ($brdPerjjg >=  5.0) {
                            $skor_brdPerjjg = 16;
                        }

                        // bagian buah tinggal
                        $bhts = json_decode($value4["bhts"], true);
                        $bhtm1 = json_decode($value4["bhtm1"], true);
                        $bhtm2 = json_decode($value4["bhtm2"], true);
                        $bhtm3 = json_decode($value4["bhtm3"], true);


                        $bhts_panen = array_sum($bhts);
                        $bhtm1_panen = array_sum($bhtm1);
                        $bhtm2_panen = array_sum($bhtm2);
                        $bhtm3_oanen = array_sum($bhtm3);

                        $sumBH = $bhts_panen +  $bhtm1_panen +  $bhtm2_panen +  $bhtm3_oanen;

                        $sumPerBH = $sumBH / ($janjang_panen + $sumBH) * 100;

                        $skor_bh = 0;
                        if ($sumPerBH <=  0.0) {
                            $skor_bh = 20;
                        } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                            $skor_bh = 18;
                        } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                            $skor_bh = 16;
                        } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                            $skor_bh = 12;
                        } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                            $skor_bh = 8;
                        } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                            $skor_bh = 4;
                        } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                            $skor_bh = 0;
                        } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                            $skor_bh = 0;
                        } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                            $skor_bh = 4;
                        } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                            $skor_bh = 8;
                        } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                            $skor_bh = 12;
                        } else if ($sumPerBH >= 5.0) {
                            $skor_bh = 10;
                        }
                        // data untuk pelepah sengklek

                        $ps = json_decode($value4["ps"], true);
                        $pelepah_s = array_sum($ps);

                        if ($pelepah_s != 0) {
                            $perPl = ($pokok_panen / $pelepah_s) * 100;
                        } else {
                            $perPl = 0;
                        }
                        $skor_perPl = 0;
                        if ($perPl <=  0.5) {
                            $skor_perPl = 5;
                        } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                            $skor_perPl = 4;
                        } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                            $skor_perPl = 3;
                        } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                            $skor_perPl = 2;
                        } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                            $skor_perPl = 1;
                        } else if ($perPl >= 2.5) {
                            $skor_perPl = 0;
                        }
                    }

                    $ttlSkorMA = $skor_brdPerjjg + $skor_bh + $skor_perPl;

                    $dataTahunEst[$key][$key2][$key3]['pokok_sample'] = $pokok_panen;
                    $dataTahunEst[$key][$key2][$key3]['ha_sample'] = $jum_ha;
                    $dataTahunEst[$key][$key2][$key3]['jumlah_panen'] = $janjang_panen;
                    $dataTahunEst[$key][$key2][$key3]['akp_rl'] =  number_format($akp, 2);

                    $dataTahunEst[$key][$key2][$key3]['p'] = $p_panen;
                    $dataTahunEst[$key][$key2][$key3]['k'] = $k_panen;
                    $dataTahunEst[$key][$key2][$key3]['tgl'] = $skor_bTinggal;

                    // $dataTahunEst[$key][$key2][$key3]['total_brd'] = $skor_bTinggal;
                    $dataTahunEst[$key][$key2][$key3]['brd/jjg'] = number_format($brdPerjjg, 2);

                    // data untuk buah tinggal
                    $dataTahunEst[$key][$key2][$key3]['bhts_s'] = $bhts_panen;
                    $dataTahunEst[$key][$key2][$key3]['bhtm1'] = $bhtm1_panen;
                    $dataTahunEst[$key][$key2][$key3]['bhtm2'] = $bhtm2_panen;
                    $dataTahunEst[$key][$key2][$key3]['bhtm3'] = $bhtm3_oanen;


                    // $dataTahunEst[$key][$key2][$key3]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek

                    $dataTahunEst[$key][$key2][$key3]['palepah_pokok'] = $pelepah_s;
                    // total skor akhir
                    $dataTahunEst[$key][$key2][$key3]['skor_bh'] = number_format($skor_bh, 2);
                    $dataTahunEst[$key][$key2][$key3]['skor_brd'] = number_format($skor_brdPerjjg, 2);
                    $dataTahunEst[$key][$key2][$key3]['skor_ps'] = number_format($skor_perPl, 2);
                    $dataTahunEst[$key][$key2][$key3]['skor_akhir'] = number_format($ttlSkorMA, 2);
                } else {
                    $dataTahunEst[$key][$key2][$key3]['pokok_sample'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['ha_sample'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['jumlah_panen'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['akp_rl'] = 0;

                    $dataTahunEst[$key][$key2][$key3]['p'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['k'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['tgl'] = 0;

                    // $dataTahunEst[$key][$key2][$key3]['total_brd'] = $skor_bTinggal;
                    $dataTahunEst[$key][$key2][$key3]['brd/jjg'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['skor_brd'] = 0;
                    // data untuk buah tinggal
                    $dataTahunEst[$key][$key2][$key3]['bhts_s'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['bhtm1'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['bhtm2'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['bhtm3'] = 0;

                    $dataTahunEst[$key][$key2][$key3]['skor_bh'] = 0;
                    // $dataTahunEst[$key][$key2][$key3]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek
                    $dataTahunEst[$key][$key2][$key3]['skor_ps'] = 0;
                    $dataTahunEst[$key][$key2][$key3]['palepah_pokok'] = 0;
                    // total skor akhir
                    $dataTahunEst[$key][$key2][$key3]['skor_akhir'] = 0;
                }
            }
        }
        // dd($dataTahunEst);
        //hitung untuk per estate
        $FinalTahun = array();
        foreach ($dataTahunEst as $key => $value) {
            foreach ($value as $key1 => $value2) {
                $total_brd = 0;
                $total_buah = 0;
                $total_skor = 0;
                $sum_p = 0;
                $sum_k = 0;
                $sum_gl = 0;
                $sum_panen = 0;
                $total_BrdperJJG = 0;
                $sum_pokok = 0;
                $sum_Restan = 0;
                $sum_s = 0;
                $sum_m1 = 0;
                $sum_m2 = 0;
                $sum_m3 = 0;
                $sumPerBH = 0;

                $sum_pelepah = 0;
                $perPl = 0;
                foreach ($value2 as $key2 => $value3) {
                    $sum_panen += $value3['jumlah_panen'];
                    $sum_pokok += $value3['pokok_sample'];
                    //brondolamn
                    $sum_p += $value3['p'];
                    $sum_k += $value3['k'];
                    $sum_gl += $value3['tgl'];
                    //buah tianggal
                    $sum_s += $value3['bhts_s'];
                    $sum_m1 += $value3['bhtm1'];
                    $sum_m2 += $value3['bhtm2'];
                    $sum_m3 += $value3['bhtm3'];
                    //pelepah
                    $sum_pelepah += $value3['palepah_pokok'];
                }
                $total_brd = $sum_p + $sum_k + $sum_gl;
                $total_buah = $sum_s + $sum_m1 + $sum_m2 + $sum_m3;
                // $persenPalepah = $sum_palepah/$sum_pokok 

                if ($sum_panen != 0) {
                    $total_BrdperJJG = round($total_brd / $sum_panen, 2);
                } else {
                    $total_BrdperJJG = 0;
                }

                if ($sum_panen != 0) {
                    $sumPerBH = round($total_buah / ($sum_panen + $total_buah) * 100, 2);
                } else {
                    $sumPerBH = 0;
                }

                if ($sum_pelepah != 0) {
                    $perPl = round(($sum_pokok / $sum_pelepah) * 100, 2);
                } else {
                    $perPl = 0;
                }
                $skor_brdPerjjg = 0;
                $skor_perPl = 0;
                $skor_bh = 0;
                if ($total_BrdperJJG <= 1.0) {
                    $skor_brdPerjjg = 20;
                } else if ($total_BrdperJJG >= 1.5 && $total_BrdperJJG <= 2.0) {
                    $skor_brdPerjjg = 12;
                } else if ($total_BrdperJJG >= 2.0 && $total_BrdperJJG <= 2.5) {
                    $skor_brdPerjjg = 8;
                } else if ($total_BrdperJJG >= 2.5 && $total_BrdperJJG <= 3.0) {
                    $skor_brdPerjjg = 4;
                } else if ($total_BrdperJJG >= 3.0 && $total_BrdperJJG <= 3.5) {
                    $skor_brdPerjjg = 0;
                } else if ($total_BrdperJJG >= 4.0 && $total_BrdperJJG <= 4.5) {
                    $skor_brdPerjjg = 8;
                } else if ($total_BrdperJJG >=  4.5 && $total_BrdperJJG <= 5.0) {
                    $skor_brdPerjjg = 12;
                } else if ($total_BrdperJJG >=  5.0) {
                    $skor_brdPerjjg = 16;
                }


                if ($sumPerBH <=  0.0) {
                    $skor_bh = 20;
                } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                    $skor_bh = 18;
                } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                    $skor_bh = 16;
                } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                    $skor_bh = 12;
                } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                    $skor_bh = 8;
                } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                    $skor_bh = 4;
                } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                    $skor_bh = 0;
                } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                    $skor_bh = 0;
                } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                    $skor_bh = 4;
                } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                    $skor_bh = 8;
                } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                    $skor_bh = 12;
                } else if ($sumPerBH >= 5.0) {
                    $skor_bh = 10;
                }


                if ($perPl <=  0.5) {
                    $skor_perPl = 5;
                } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                    $skor_perPl = 4;
                } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                    $skor_perPl = 3;
                } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                    $skor_perPl = 2;
                } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                    $skor_perPl = 1;
                } else if ($perPl >= 2.5) {
                    $skor_perPl = 0;
                }

                $total_skor = $skor_brdPerjjg + $skor_bh + $skor_perPl;


                $FinalTahun[$key][$key1]['total_p.k.gl'] = $total_brd ? $total_brd : 0;
                $FinalTahun[$key][$key1]['total_jumPanen'] = $sum_panen ? $sum_panen : 0;
                $FinalTahun[$key][$key1]['total_jumPokok'] = $sum_pokok ? $sum_pokok : 0;
                $FinalTahun[$key][$key1]['total_brd/jjg'] = $total_BrdperJJG ? $total_BrdperJJG : 0;
                $FinalTahun[$key][$key1]['skor_brd'] = $skor_brdPerjjg ? $skor_brdPerjjg : 0;
                //buah tinggal
                $FinalTahun[$key][$key1]['s'] = $sum_s  ? $sum_s : 0;
                $FinalTahun[$key][$key1]['m1'] = $sum_m1 ? $sum_m1 : 0;
                $FinalTahun[$key][$key1]['m2'] = $sum_m2 ? $sum_m2 : 0;
                $FinalTahun[$key][$key1]['m3'] = $sum_m3 ? $sum_m3 : 0;
                $FinalTahun[$key][$key1]['total_bh'] = $total_buah ? $total_buah : 0;
                $FinalTahun[$key][$key1]['total_bh/jjg'] = $sumPerBH ? $sumPerBH : 0;
                $FinalTahun[$key][$key1]['skor_bh'] = $skor_bh ? $skor_bh : 0;
                //palepah sengklek
                $FinalTahun[$key][$key1]['pokok_palepah'] = $sum_pelepah ? $sum_pelepah : 0;
                $FinalTahun[$key][$key1]['perPalepah'] = $perPl ? $perPl : 0;
                $FinalTahun[$key][$key1]['skor_perPl'] = $skor_perPl ? $skor_perPl : 0;
                //total skor akhir
                $FinalTahun[$key][$key1]['skor_final'] = $total_skor ? $total_skor : 0;
            }
        }
        // dd($FinalTahun);
        //hitung untuk perbulan per estate
        $Final_end = array();
        foreach ($FinalTahun as $key => $value) {
            $sum_Panen = 0;
            $sum_Pokok = 0;
            $sum_PKGL = 0;
            $BrdperPanen = 0;
            $sum_SM1M2M3 = 0;
            $sumPerBH = 0;
            $sum_pelepah = 0;
            $final_skorTH = 0;
            foreach ($value as $key1 => $value2) {
                $sum_Panen += $value2['total_jumPanen'];
                $sum_Pokok += $value2['total_jumPokok'];
                $sum_PKGL += $value2['total_p.k.gl'];
                $sum_SM1M2M3 += $value2['total_bh'];
                $sum_pelepah += $value2['pokok_palepah'];
            }

            if ($sum_PKGL != 0) {
                $BrdperPanen = round($sum_PKGL / $sum_Panen, 2);
            } else {
                $BrdperPanen = 0;
            }

            if ($sum_Panen != 0) {
                $sumPerBH = round($sum_SM1M2M3 / ($sum_Panen + $sum_SM1M2M3) * 100, 2);
            } else {
                $sumPerBH = 0;
            }

            if ($sum_pelepah != 0) {
                $perPl = round(($sum_Pokok / $sum_pelepah) * 100, 2);
            } else {
                $perPl = 0;
            }

            $skor_brdPerjjg = 0;
            if ($BrdperPanen <= 1.0) {
                $skor_brdPerjjg = 20;
            } else if ($BrdperPanen >= 1.5 && $BrdperPanen <= 2.0) {
                $skor_brdPerjjg = 12;
            } else if ($BrdperPanen >= 2.0 && $BrdperPanen <= 2.5) {
                $skor_brdPerjjg = 8;
            } else if ($BrdperPanen >= 2.5 && $BrdperPanen <= 3.0) {
                $skor_brdPerjjg = 4;
            } else if ($BrdperPanen >= 3.0 && $BrdperPanen <= 3.5) {
                $skor_brdPerjjg = 0;
            } else if ($BrdperPanen >= 4.0 && $BrdperPanen <= 4.5) {
                $skor_brdPerjjg = 8;
            } else if ($BrdperPanen >=  4.5 && $BrdperPanen <= 5.0) {
                $skor_brdPerjjg = 12;
            } else if ($BrdperPanen >=  5.0) {
                $skor_brdPerjjg = 16;
            }


            $skor_bh = 0;
            if ($sumPerBH <=  0.0) {
                $skor_bh = 20;
            } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                $skor_bh = 18;
            } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                $skor_bh = 16;
            } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                $skor_bh = 12;
            } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                $skor_bh = 8;
            } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                $skor_bh = 4;
            } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                $skor_bh = 0;
            } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                $skor_bh = 0;
            } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                $skor_bh = 4;
            } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                $skor_bh = 8;
            } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                $skor_bh = 12;
            } else if ($sumPerBH >= 5.0) {
                $skor_bh = 10;
            }

            $skor_perPl = 0;
            if ($perPl <=  0.5) {
                $skor_perPl = 5;
            } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                $skor_perPl = 4;
            } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                $skor_perPl = 3;
            } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                $skor_perPl = 2;
            } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                $skor_perPl = 1;
            } else if ($perPl >= 2.5) {
                $skor_perPl = 0;
            }

            $final_skorTH = $skor_brdPerjjg + $skor_bh + $skor_perPl;
            $Final_end[$key]['tahun/panen'] = $sum_Panen ? $sum_Panen : 0;
            $Final_end[$key]['tahun/pokok'] = $sum_Pokok ? $sum_Pokok : 0;

            $Final_end[$key]['total_brd'] = $sum_PKGL ? $sum_PKGL : 0;
            $Final_end[$key]['total_brd/panen'] = $BrdperPanen ? $BrdperPanen : 0;
            $Final_end[$key]['skor_brd'] = $skor_brdPerjjg ? $skor_brdPerjjg : 0;
            $Final_end[$key]['total_buah'] = $sum_SM1M2M3 ? $sum_SM1M2M3 : 0;
            $Final_end[$key]['total_buah/jjg'] = $sumPerBH ? $sumPerBH : 0;
            $Final_end[$key]['skor_buah'] = $skor_bh ? $skor_bh : 0;
            $Final_end[$key]['skor_palepah'] = $skor_perPl ? $skor_perPl : 0;
            $Final_end[$key]['skor_tahun'] = $final_skorTH ? $final_skorTH : 0;
        }

        // dd($Final_end, $mutuTransTahun);


        // end menghitung table untuk data pertahun

        // mentotal kan skor bulanan untuk mt ancak mt buah mt transport
        $RekapBulan = array();
        foreach ($mutuTransEst as $key => $value) {
            foreach ($value as $key2  => $value2) {
                foreach ($bulananEST as $key3 => $value3) {
                    foreach ($value3 as $key4 => $value4) {
                        foreach ($FinalTahun as $key5 => $value5) {
                            foreach ($value5 as $key6 => $value6)
                                if ($key == $key3 && $key3 == $key5) {
                                    $RekapBulan[$key][$key2]['bulan_skor'] = $value2['total_skor'] + $value4['totalSkor'] + $value6['skor_final'];
                                }
                        }
                    }
                }
            }
        }
        // dd($RekapBulan);
        // mentotal kan skor tahunan untuk mt ancak mt buah mt transport
        $RekapTahun = array();
        foreach ($Final_end as $key => $value) {
            foreach ($mutuTransTahun as $key2 => $value2) {
                foreach ($TahunMtBuah as $key3 => $value3) {
                    if ($key == $key2 && $key2 == $key3) {
                        $RekapTahun[$key]['tahun_skor'] = $value['skor_tahun'] + $value2['skor_total'] + $value3['totalTahun_skor'];
                    }
                }
            }
        }


        //perhitungan untuk data perwilayah
        $bulanMTancak = array();
        foreach ($mtAncakWil as $key => $value) {
            foreach ($value as $key1 => $value2) {
                foreach ($value2 as $key2 => $value3) {
                    foreach ($value3 as $key3 => $value4)
                        if (!empty($value4)) {
                            $akp = 0;
                            $totalPKGL = 0;
                            $brdPerjjg = 0;
                            $pokok_panen = 0;
                            $janjang_panen = 0;
                            $p_panen = 0;
                            $k_panen = 0;
                            $brtgl_panen = 0;
                            $bhts_panen  = 0;
                            $bhtm1_panen  = 0;
                            $bhtm2_panen  = 0;
                            $bhtm3_oanen  = 0;
                            $ttlSkorMA = 0;
                            $listBlokPerAfd = array();
                            $jum_ha = 0;
                            $pelepah_s = 0;
                            foreach ($value4 as $key4 => $value5) {
                                // dd($value5);
                                if (!in_array($value5['estate'] . ' ' . $value5['afdeling'] . ' ' . $value5['blok'], $listBlokPerAfd)) {
                                    $listBlokPerAfd[] = $value5['estate'] . ' ' . $value5['afdeling'] . ' ' . $value5['blok'];
                                }
                                $jum_ha = count($listBlokPerAfd);

                                $pokok_panen = json_decode($value5["pokok_dipanen"], true);
                                $jajang_panen = json_decode($value5["jjg_dipanen"], true);
                                $brtp = json_decode($value5["brtp"], true);
                                $brtk = json_decode($value5["brtk"], true);
                                $brtgl = json_decode($value5["brtgl"], true);

                                $pokok_panen  = count($pokok_panen);
                                $janjang_panen = array_sum($jajang_panen);
                                $p_panen = array_sum($brtp);
                                $k_panen = array_sum($brtk);
                                $brtgl_panen = array_sum($brtgl);

                                // $akp = ($janjang_panen / $pokok_panen) %
                                $akp = ($janjang_panen / $pokok_panen) * 100;
                                $totalPKGL = $p_panen + $k_panen + $brtgl_panen;
                                $brdPerjjg = $totalPKGL / $janjang_panen;

                                //skore PEnggunnan Brondolan
                                $skor_brdPerjjg = 0;
                                if ($brdPerjjg <= 1.0) {
                                    $skor_brdPerjjg = 20;
                                } else if ($brdPerjjg >= 1.5 && $brdPerjjg <= 2.0) {
                                    $skor_brdPerjjg = 12;
                                } else if ($brdPerjjg >= 2.0 && $brdPerjjg <= 2.5) {
                                    $skor_brdPerjjg = 8;
                                } else if ($brdPerjjg >= 2.5 && $brdPerjjg <= 3.0) {
                                    $skor_brdPerjjg = 4;
                                } else if ($brdPerjjg >= 3.0 && $brdPerjjg <= 3.5) {
                                    $skor_brdPerjjg = 0;
                                } else if ($brdPerjjg >= 4.0 && $brdPerjjg <= 4.5) {
                                    $skor_brdPerjjg = 8;
                                } else if ($brdPerjjg >=  4.5 && $brdPerjjg <= 5.0) {
                                    $skor_brdPerjjg = 12;
                                } else if ($brdPerjjg >=  5.0) {
                                    $skor_brdPerjjg = 16;
                                }

                                // bagian buah tinggal
                                $bhts = json_decode($value5["bhts"], true);
                                $bhtm1 = json_decode($value5["bhtm1"], true);
                                $bhtm2 = json_decode($value5["bhtm2"], true);
                                $bhtm3 = json_decode($value5["bhtm3"], true);


                                $bhts_panen = array_sum($bhts);
                                $bhtm1_panen = array_sum($bhtm1);
                                $bhtm2_panen = array_sum($bhtm2);
                                $bhtm3_oanen = array_sum($bhtm3);

                                $sumBH = $bhts_panen +  $bhtm1_panen +  $bhtm2_panen +  $bhtm3_oanen;

                                $sumPerBH = $sumBH / ($janjang_panen + $sumBH) * 100;

                                $skor_bh = 0;
                                if ($sumPerBH <=  0.0) {
                                    $skor_bh = 20;
                                } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                                    $skor_bh = 18;
                                } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                                    $skor_bh = 16;
                                } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                                    $skor_bh = 12;
                                } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                                    $skor_bh = 8;
                                } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                                    $skor_bh = 4;
                                } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                                    $skor_bh = 0;
                                } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                                    $skor_bh = 0;
                                } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                                    $skor_bh = 4;
                                } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                                    $skor_bh = 8;
                                } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                                    $skor_bh = 12;
                                } else if ($sumPerBH >= 5.0) {
                                    $skor_bh = 10;
                                }
                                // data untuk pelepah sengklek

                                $ps = json_decode($value5["ps"], true);
                                $pelepah_s = array_sum($ps);

                                if ($pelepah_s != 0) {
                                    $perPl = ($pokok_panen / $pelepah_s) * 100;
                                } else {
                                    $perPl = 0;
                                }
                                $skor_perPl = 0;
                                if ($perPl <=  0.5) {
                                    $skor_perPl = 5;
                                } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                                    $skor_perPl = 4;
                                } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                                    $skor_perPl = 3;
                                } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                                    $skor_perPl = 2;
                                } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                                    $skor_perPl = 1;
                                } else if ($perPl >= 2.5) {
                                    $skor_perPl = 0;
                                }
                            }

                            $ttlSkorMA = $skor_brdPerjjg + $skor_bh + $skor_perPl;

                            $bulanMTancak[$key][$key1][$key2][$key3]['pokok_sample'] = $pokok_panen;
                            $bulanMTancak[$key][$key1][$key2][$key3]['ha_sample'] = $jum_ha;
                            $bulanMTancak[$key][$key1][$key2][$key3]['jumlah_panen'] = $janjang_panen;
                            $bulanMTancak[$key][$key1][$key2][$key3]['akp_rl'] =  number_format($akp, 2);

                            $bulanMTancak[$key][$key1][$key2][$key3]['p'] = $p_panen;
                            $bulanMTancak[$key][$key1][$key2][$key3]['k'] = $k_panen;
                            $bulanMTancak[$key][$key1][$key2][$key3]['tgl'] = $brtgl_panen;

                            // $bulanMTancak[$key][$key2][$key3]['total_brd'] = $skor_bTinggal;
                            $bulanMTancak[$key][$key1][$key2][$key3]['brd/jjg'] = number_format($brdPerjjg, 2);

                            // data untuk buah tinggal
                            $bulanMTancak[$key][$key1][$key2][$key3]['bhts'] = $bhts_panen;
                            $bulanMTancak[$key][$key1][$key2][$key3]['bhtm1'] = $bhtm1_panen;
                            $bulanMTancak[$key][$key1][$key2][$key3]['bhtm2'] = $bhtm2_panen;
                            $bulanMTancak[$key][$key1][$key2][$key3]['bhtm3'] = $bhtm3_oanen;


                            // $bulanMTancak[$key][$key2][$key3]['jjgperBuah'] = number_format($sumPerBH, 2);
                            // data untuk pelepah sengklek

                            $bulanMTancak[$key][$key1][$key2][$key3]['palepah_pokok'] = $pelepah_s;
                            // total skor akhir

                            $bulanMTancak[$key][$key1][$key2][$key3]['skor_brd'] = number_format($skor_brdPerjjg, 2);
                            $bulanMTancak[$key][$key1][$key2][$key3]['skor_bh'] = number_format($skor_bh, 2);
                            $bulanMTancak[$key][$key1][$key2][$key3]['skor_ps'] = number_format($skor_perPl, 2);
                            $bulanMTancak[$key][$key1][$key2][$key3]['skor_akhir'] = number_format($ttlSkorMA, 2);
                        } else {

                            $bulanMTancak[$key][$key1][$key2][$key3]['pokok_sample'] = 0;
                            $bulanMTancak[$key][$key1][$key2][$key3]['ha_sample'] = 0;
                            $bulanMTancak[$key][$key1][$key2][$key3]['jumlah_panen'] = 0;
                            $bulanMTancak[$key][$key1][$key2][$key3]['akp_rl'] =  0;

                            $bulanMTancak[$key][$key1][$key2][$key3]['p'] = 0;
                            $bulanMTancak[$key][$key1][$key2][$key3]['k'] = 0;
                            $bulanMTancak[$key][$key1][$key2][$key3]['tgl'] = 0;

                            // $bulanMTancak[$key][$key2][$key3]['total_brd'] = $skor_bTinggal;
                            $bulanMTancak[$key][$key1][$key2][$key3]['brd/jjg'] = 0;

                            // data untuk buah tinggal
                            $bulanMTancak[$key][$key1][$key2][$key3]['bhts'] = 0;
                            $bulanMTancak[$key][$key1][$key2][$key3]['bhtm1'] = 0;
                            $bulanMTancak[$key][$key1][$key2][$key3]['bhtm2'] = 0;
                            $bulanMTancak[$key][$key1][$key2][$key3]['bhtm3'] = 0;


                            // $bulanMTancak[$key][$key2][$key3]['jjgperBuah'] = number_format($sumPerBH, 2);
                            // data untuk pelepah sengklek

                            $bulanMTancak[$key][$key1][$key2][$key3]['palepah_pokok'] = 0;
                            // total skor akhi0;
                            $bulanMTancak[$key][$key1][$key2][$key3]['skor_brd'] = 0;
                            $bulanMTancak[$key][$key1][$key2][$key3]['skor_bh'] = 0;
                            $bulanMTancak[$key][$key1][$key2][$key3]['skor_ps'] = 0;
                            $bulanMTancak[$key][$key1][$key2][$key3]['skor_akhir'] = 0;
                        }
                }
            }
        }
        // dd($bulanMTancak);
        //membuat perhitungan mutu ancak berdasarkan perbulan > est
        $bulanAncakEST = array();
        foreach ($bulanMTancak as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2)
                    if (!empty($value2)) {
                        $pokok_sample = 0;
                        $jum_ha = 0;
                        $pokok_panen = 0;
                        $p_panen = 0;
                        $k_panen = 0;
                        $tgl_panen = 0;
                        $totalPKGL = 0;
                        $brdPerjjg = 0;
                        $bmts = 0;
                        $bhtm1 = 0;
                        $bhtm2 = 0;
                        $bhtm3 = 0;
                        $totalSM123 = 0;
                        $palepah_pokok = 0;
                        $perPl = 0;
                        $sumPerBH = 0;
                        $ttlSkorMA = 0;
                        foreach ($value2 as $key3 => $value3) {
                            // dd($value3);
                            $pokok_sample += $value3['pokok_sample'];
                            $jum_ha += $value3['ha_sample'];
                            $pokok_panen += $value3['jumlah_panen'];
                            $p_panen += $value3['p'];
                            $k_panen += $value3['k'];
                            $tgl_panen += $value3['tgl'];

                            $bmts += $value3['bhts'];
                            $bhtm1 += $value3['bhtm1'];
                            $bhtm2 += $value3['bhtm2'];
                            $bhtm3 += $value3['bhtm3'];

                            $palepah_pokok += $value3['palepah_pokok'];
                        }
                        $totalPKGL = $p_panen + $k_panen + $tgl_panen;
                        if ($pokok_panen != 0) {
                            $brdPerjjg = round($totalPKGL / $pokok_panen, 2);
                        } else {
                            $brdPerjjg = 0;
                        }

                        $skor_brdPerjjg = 0;
                        if ($brdPerjjg <= 1.0) {
                            $skor_brdPerjjg = 20;
                        } else if ($brdPerjjg >= 1.5 && $brdPerjjg <= 2.0) {
                            $skor_brdPerjjg = 12;
                        } else if ($brdPerjjg >= 2.0 && $brdPerjjg <= 2.5) {
                            $skor_brdPerjjg = 8;
                        } else if ($brdPerjjg >= 2.5 && $brdPerjjg <= 3.0) {
                            $skor_brdPerjjg = 4;
                        } else if ($brdPerjjg >= 3.0 && $brdPerjjg <= 3.5) {
                            $skor_brdPerjjg = 0;
                        } else if ($brdPerjjg >= 4.0 && $brdPerjjg <= 4.5) {
                            $skor_brdPerjjg = 8;
                        } else if ($brdPerjjg >=  4.5 && $brdPerjjg <= 5.0) {
                            $skor_brdPerjjg = 12;
                        } else if ($brdPerjjg >=  5.0) {
                            $skor_brdPerjjg = 16;
                        }

                        $totalSM123  =  $bmts +  $bhtm1 + $bhtm2 + $bhtm3;

                        if ($pokok_panen != 0) {
                            $sumPerBH = $totalSM123 / ($pokok_panen + $totalSM123) * 100;
                        } else {
                            $sumPerBH = 0;
                        }


                        $skor_bh = 0;
                        if ($sumPerBH <=  0.0) {
                            $skor_bh = 20;
                        } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                            $skor_bh = 18;
                        } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                            $skor_bh = 16;
                        } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                            $skor_bh = 12;
                        } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                            $skor_bh = 8;
                        } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                            $skor_bh = 4;
                        } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                            $skor_bh = 0;
                        } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                            $skor_bh = 0;
                        } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                            $skor_bh = 4;
                        } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                            $skor_bh = 8;
                        } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                            $skor_bh = 12;
                        } else if ($sumPerBH >= 5.0) {
                            $skor_bh = 10;
                        }


                        if ($pelepah_s != 0) {
                            $perPl = round(($pokok_sample / $pelepah_s) * 100, 2);
                        } else {
                            $perPl = 0;
                        }

                        $skor_perPl = 0;
                        if ($perPl <=  0.5) {
                            $skor_perPl = 5;
                        } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                            $skor_perPl = 4;
                        } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                            $skor_perPl = 3;
                        } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                            $skor_perPl = 2;
                        } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                            $skor_perPl = 1;
                        } else if ($perPl >= 2.5) {
                            $skor_perPl = 0;
                        }

                        $ttlSkorMA = $skor_brdPerjjg + $skor_bh + $skor_perPl;
                        $bulanAncakEST[$key][$key1][$key2]['pokok_sample'] = $pokok_sample;
                        $bulanAncakEST[$key][$key1][$key2]['ha_sample'] = $jum_ha;
                        $bulanAncakEST[$key][$key1][$key2]['pokok_panen'] = $pokok_panen;
                        $bulanAncakEST[$key][$key1][$key2]['p_panen'] = $p_panen;
                        $bulanAncakEST[$key][$key1][$key2]['k_panen'] = $k_panen;
                        $bulanAncakEST[$key][$key1][$key2]['tgl_panen'] = $tgl_panen;
                        $bulanAncakEST[$key][$key1][$key2]['brdPerjjg'] = $brdPerjjg;
                        $bulanAncakEST[$key][$key1][$key2]['skor_brdPerjjg'] = $skor_brdPerjjg;

                        $bulanAncakEST[$key][$key1][$key2]['bmts'] = $bmts;
                        $bulanAncakEST[$key][$key1][$key2]['bhtm1'] = $bhtm1;
                        $bulanAncakEST[$key][$key1][$key2]['bhtm2'] = $bhtm2;
                        $bulanAncakEST[$key][$key1][$key2]['bhtm3'] = $bhtm3;
                        $bulanAncakEST[$key][$key1][$key2]['skor_bh'] = $skor_bh;

                        $bulanAncakEST[$key][$key1][$key2]['palepah_pokok'] = $palepah_pokok;
                        $bulanAncakEST[$key][$key1][$key2]['perPl'] = $perPl;
                        $bulanAncakEST[$key][$key1][$key2]['skor_perPl'] = $skor_perPl;
                        $bulanAncakEST[$key][$key1][$key2]['total_skor'] = $ttlSkorMA;
                    } else {
                        $bulanAncakEST[$key][$key1][$key2]['pokok_sample'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['ha_sample'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['pokok_panen'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['p_panen'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['k_panen'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['tgl_panen'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['brdPerjjg'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['skor_brdPerjjg'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['bmts'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['bhtm1'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['bhtm2'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['bhtm3'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['skor_bh'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['palepah_pokok'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['perPl'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['skor_perPl'] = 0;
                        $bulanAncakEST[$key][$key1][$key2]['total_skor'] = 0;;
                    }
            }
        }
        // dd($bulanAncakEST);
        //membuat perhitungan mutu ancak berdasarkan perbulan semua estate
        $bulanAllEST = array();
        foreach ($bulanAncakEST as $key => $value) {
            foreach ($value as $key1 => $value1)
                if (!empty($value1)) {
                    $pokok_sample = 0;
                    $jum_ha = 0;
                    $pokok_panen = 0;
                    $p_panen = 0;
                    $k_panen = 0;
                    $tgl_panen = 0;
                    $totalPKGL = 0;
                    $brdPerjjg = 0;
                    $bmts = 0;
                    $bhtm1 = 0;
                    $bhtm2 = 0;
                    $bhtm3 = 0;
                    $totalSM123 = 0;
                    $palepah_pokok = 0;
                    $perPl = 0;
                    $sumPerBH = 0;
                    $ttlSkorMA = 0;

                    foreach ($value1 as $key2 => $value2) {
                        // dd($value2);
                        $pokok_sample += $value2['pokok_sample'];
                        $jum_ha += $value2['ha_sample'];
                        $pokok_panen += $value2['pokok_panen'];
                        $p_panen += $value2['p_panen'];
                        $k_panen += $value2['k_panen'];
                        $tgl_panen += $value2['tgl_panen'];

                        $bmts += $value2['bmts'];
                        $bhtm1 += $value2['bhtm1'];
                        $bhtm2 += $value2['bhtm2'];
                        $bhtm3 += $value2['bhtm3'];

                        $palepah_pokok += $value2['palepah_pokok'];
                    }

                    $totalPKGL = $p_panen + $k_panen + $tgl_panen;
                    if ($pokok_panen != 0) {
                        $brdPerjjg = round($totalPKGL / $pokok_panen, 2);
                    } else {
                        $brdPerjjg = 0;
                    }

                    $skor_brdPerjjg = 0;
                    if ($brdPerjjg <= 1.0) {
                        $skor_brdPerjjg = 20;
                    } else if ($brdPerjjg >= 1.5 && $brdPerjjg <= 2.0) {
                        $skor_brdPerjjg = 12;
                    } else if ($brdPerjjg >= 2.0 && $brdPerjjg <= 2.5) {
                        $skor_brdPerjjg = 8;
                    } else if ($brdPerjjg >= 2.5 && $brdPerjjg <= 3.0) {
                        $skor_brdPerjjg = 4;
                    } else if ($brdPerjjg >= 3.0 && $brdPerjjg <= 3.5) {
                        $skor_brdPerjjg = 0;
                    } else if ($brdPerjjg >= 4.0 && $brdPerjjg <= 4.5) {
                        $skor_brdPerjjg = 8;
                    } else if ($brdPerjjg >=  4.5 && $brdPerjjg <= 5.0) {
                        $skor_brdPerjjg = 12;
                    } else if ($brdPerjjg >=  5.0) {
                        $skor_brdPerjjg = 16;
                    }

                    $totalSM123  =  $bmts +  $bhtm1 + $bhtm2 + $bhtm3;

                    if ($pokok_panen != 0) {
                        $sumPerBH = $totalSM123 / ($pokok_panen + $totalSM123) * 100;
                    } else {
                        $sumPerBH = 0;
                    }


                    $skor_bh = 0;
                    if ($sumPerBH <=  0.0) {
                        $skor_bh = 20;
                    } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                        $skor_bh = 18;
                    } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                        $skor_bh = 16;
                    } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                        $skor_bh = 12;
                    } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                        $skor_bh = 8;
                    } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                        $skor_bh = 4;
                    } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                        $skor_bh = 0;
                    } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                        $skor_bh = 0;
                    } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                        $skor_bh = 4;
                    } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                        $skor_bh = 8;
                    } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                        $skor_bh = 12;
                    } else if ($sumPerBH >= 5.0) {
                        $skor_bh = 10;
                    }


                    if ($palepah_pokok != 0) {
                        $perPl = round(($pokok_sample / $palepah_pokok) * 100, 2);
                    } else {
                        $perPl = 0;
                    }

                    $skor_perPl = 0;
                    if ($perPl <=  0.5) {
                        $skor_perPl = 5;
                    } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                        $skor_perPl = 4;
                    } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                        $skor_perPl = 3;
                    } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                        $skor_perPl = 2;
                    } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                        $skor_perPl = 1;
                    } else if ($perPl >= 2.5) {
                        $skor_perPl = 0;
                    }


                    $ttlSkorMA = $skor_brdPerjjg + $skor_bh + $skor_perPl;

                    $bulanAllEST[$key][$key1]['pokok_sample'] = $pokok_sample;
                    $bulanAllEST[$key][$key1]['ha_sample'] = $jum_ha;
                    $bulanAllEST[$key][$key1]['pokok_panen'] = $pokok_panen;
                    $bulanAllEST[$key][$key1]['p_panen'] = $p_panen;
                    $bulanAllEST[$key][$key1]['k_panen'] = $k_panen;
                    $bulanAllEST[$key][$key1]['tgl_panen'] = $tgl_panen;
                    $bulanAllEST[$key][$key1]['brdPerjjg'] = $brdPerjjg;
                    $bulanAllEST[$key][$key1]['skor_brdPerjjg'] = $skor_brdPerjjg;

                    $bulanAllEST[$key][$key1]['bmts'] = $bmts;
                    $bulanAllEST[$key][$key1]['bhtm1'] = $bhtm1;
                    $bulanAllEST[$key][$key1]['bhtm2'] = $bhtm2;
                    $bulanAllEST[$key][$key1]['bhtm3'] = $bhtm3;
                    $bulanAllEST[$key][$key1]['skor_bh'] = $skor_bh;

                    $bulanAllEST[$key][$key1]['palepah_pokok'] = $palepah_pokok;
                    $bulanAllEST[$key][$key1]['perPl'] = $perPl;
                    $bulanAllEST[$key][$key1]['skor_perPl'] = $skor_perPl;
                    $bulanAllEST[$key][$key1]['total_skor'] = $ttlSkorMA;
                } else {

                    $bulanAllEST[$key][$key1]['pokok_sample'] = 0;
                    $bulanAllEST[$key][$key1]['ha_sample'] = 0;
                    $bulanAllEST[$key][$key1]['pokok_panen'] = 0;
                    $bulanAllEST[$key][$key1]['p_panen'] = 0;
                    $bulanAllEST[$key][$key1]['k_panen'] = 0;
                    $bulanAllEST[$key][$key1]['tgl_panen'] = 0;
                    $bulanAllEST[$key][$key1]['brdPerjjg'] = 0;
                    $bulanAllEST[$key][$key1]['skor_brdPerjjg'] = 0;

                    $bulanAllEST[$key][$key1]['bmts'] = 0;
                    $bulanAllEST[$key][$key1]['bhtm1'] = 0;
                    $bulanAllEST[$key][$key1]['bhtm2'] = 0;
                    $bulanAllEST[$key][$key1]['bhtm3'] = 0;
                    $bulanAllEST[$key][$key1]['skor_bh'] = 0;

                    $bulanAllEST[$key][$key1]['palepah_pokok'] = 0;
                    $bulanAllEST[$key][$key1]['perPl'] = 0;
                    $bulanAllEST[$key][$key1]['skor_perPl'] = 0;
                    $bulanAllEST[$key][$key1]['total_skor'] = 0;
                }
        }
        // dd($bulanAllEST);

        $WilMtAncakThn = array();
        //hitung tahunan mutu ancak untuk perwilayah 
        foreach ($bulanAllEST as $key => $value)
            if (!empty($value)) {
                $pokok_sample = 0;
                $jum_ha = 0;
                $pokok_panen = 0;
                $p_panen = 0;
                $k_panen = 0;
                $tgl_panen = 0;
                $totalPKGL = 0;
                $brdPerjjg = 0;
                $bmts = 0;
                $bhtm1 = 0;
                $bhtm2 = 0;
                $bhtm3 = 0;
                $totalSM123 = 0;
                $palepah_pokok = 0;
                $perPl = 0;
                $sumPerBH = 0;
                $ttlSkorMA = 0;
                foreach ($value as $key1 => $value1) {
                    // dd($value2);
                    $pokok_sample += $value1['pokok_sample'];
                    $jum_ha += $value1['ha_sample'];
                    $pokok_panen += $value1['pokok_panen'];
                    $p_panen += $value1['p_panen'];
                    $k_panen += $value1['k_panen'];
                    $tgl_panen += $value1['tgl_panen'];

                    $bmts += $value1['bmts'];
                    $bhtm1 += $value1['bhtm1'];
                    $bhtm2 += $value1['bhtm2'];
                    $bhtm3 += $value1['bhtm3'];

                    $palepah_pokok += $value1['palepah_pokok'];
                }

                $totalPKGL = $p_panen + $k_panen + $tgl_panen;
                if ($pokok_panen != 0) {
                    $brdPerjjg = round($totalPKGL / $pokok_panen, 2);
                } else {
                    $brdPerjjg = 0;
                }

                $skor_brdPerjjg = 0;
                if ($brdPerjjg <= 1.0) {
                    $skor_brdPerjjg = 20;
                } else if ($brdPerjjg >= 1.5 && $brdPerjjg <= 2.0) {
                    $skor_brdPerjjg = 12;
                } else if ($brdPerjjg >= 2.0 && $brdPerjjg <= 2.5) {
                    $skor_brdPerjjg = 8;
                } else if ($brdPerjjg >= 2.5 && $brdPerjjg <= 3.0) {
                    $skor_brdPerjjg = 4;
                } else if ($brdPerjjg >= 3.0 && $brdPerjjg <= 3.5) {
                    $skor_brdPerjjg = 0;
                } else if ($brdPerjjg >= 4.0 && $brdPerjjg <= 4.5) {
                    $skor_brdPerjjg = 8;
                } else if ($brdPerjjg >=  4.5 && $brdPerjjg <= 5.0) {
                    $skor_brdPerjjg = 12;
                } else if ($brdPerjjg >=  5.0) {
                    $skor_brdPerjjg = 16;
                }

                $totalSM123  =  $bmts +  $bhtm1 + $bhtm2 + $bhtm3;

                if ($pokok_panen != 0) {
                    $sumPerBH = $totalSM123 / ($pokok_panen + $totalSM123) * 100;
                } else {
                    $sumPerBH = 0;
                }


                $skor_bh = 0;
                if ($sumPerBH <=  0.0) {
                    $skor_bh = 20;
                } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                    $skor_bh = 18;
                } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                    $skor_bh = 16;
                } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                    $skor_bh = 12;
                } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                    $skor_bh = 8;
                } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                    $skor_bh = 4;
                } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                    $skor_bh = 0;
                } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                    $skor_bh = 0;
                } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                    $skor_bh = 4;
                } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                    $skor_bh = 8;
                } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                    $skor_bh = 12;
                } else if ($sumPerBH >= 5.0) {
                    $skor_bh = 10;
                }


                if ($palepah_pokok != 0) {
                    $perPl = round(($pokok_sample / $palepah_pokok) * 100, 2);
                } else {
                    $perPl = 0;
                }

                $skor_perPl = 0;
                if ($perPl <=  0.5) {
                    $skor_perPl = 5;
                } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                    $skor_perPl = 4;
                } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                    $skor_perPl = 3;
                } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                    $skor_perPl = 2;
                } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                    $skor_perPl = 1;
                } else if ($perPl >= 2.5) {
                    $skor_perPl = 0;
                }


                $ttlSkorMA = $skor_brdPerjjg + $skor_bh + $skor_perPl;

                $WilMtAncakThn[$key]['pokok_sample'] = $pokok_sample;
                $WilMtAncakThn[$key]['ha_sample'] = $jum_ha;
                $WilMtAncakThn[$key]['pokok_panen'] = $pokok_panen;
                $WilMtAncakThn[$key]['p_panen'] = $p_panen;
                $WilMtAncakThn[$key]['k_panen'] = $k_panen;
                $WilMtAncakThn[$key]['tgl_panen'] = $tgl_panen;
                $WilMtAncakThn[$key]['brdPerjjg'] = $brdPerjjg;
                $WilMtAncakThn[$key]['skor_brdPerjjg'] = $skor_brdPerjjg;

                $WilMtAncakThn[$key]['bmts'] = $bmts;
                $WilMtAncakThn[$key]['bhtm1'] = $bhtm1;
                $WilMtAncakThn[$key]['bhtm2'] = $bhtm2;
                $WilMtAncakThn[$key]['bhtm3'] = $bhtm3;
                $WilMtAncakThn[$key]['skor_bh'] = $skor_bh;

                $WilMtAncakThn[$key]['palepah_pokok'] = $palepah_pokok;
                $WilMtAncakThn[$key]['perPl'] = $perPl;
                $WilMtAncakThn[$key]['skor_perPl'] = $skor_perPl;
                $WilMtAncakThn[$key]['total_skor'] = $ttlSkorMA;
            } else {

                $WilMtAncakThn[$key]['pokok_sample'] = 0;
                $WilMtAncakThn[$key]['ha_sample'] = 0;
                $WilMtAncakThn[$key]['pokok_panen'] = 0;
                $WilMtAncakThn[$key]['p_panen'] = 0;
                $WilMtAncakThn[$key]['k_panen'] = 0;
                $WilMtAncakThn[$key]['tgl_panen'] = 0;
                $WilMtAncakThn[$key]['brdPerjjg'] = 0;
                $WilMtAncakThn[$key]['skor_brdPerjjg'] = 0;

                $WilMtAncakThn[$key]['bmts'] = 0;
                $WilMtAncakThn[$key]['bhtm1'] = 0;
                $WilMtAncakThn[$key]['bhtm2'] = 0;
                $WilMtAncakThn[$key]['bhtm3'] = 0;
                $WilMtAncakThn[$key]['skor_bh'] = 0;

                $WilMtAncakThn[$key]['palepah_pokok'] = 0;
                $WilMtAncakThn[$key]['perPl'] = 0;
                $WilMtAncakThn[$key]['skor_perPl'] = 0;
                $WilMtAncakThn[$key]['total_skor'] = 0;
            }
        // dd($WilMtAncakThn);
        //menghitung region  perbulan all estate
        $RegperbulanMTancak = array();
        foreach ($bulan as $key => $value) {
            foreach ($queryEste as $key2 => $value2) {
                foreach ($queryAfd as $key3 => $value3) {
                    if ($value2['est'] == $value3['est']) {
                        $RegperbulanMTancak[$value][$value2['est']][$value3['nama']] = 0;
                        // $RegperbulanMTancak[$value][$value2['est']][$value] = 0;
                    }
                }
            }
        }

        $mutuAncakReg = array();
        foreach ($querytahun as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $mutuAncakReg)) {
                        $mutuAncakReg[$month] = array();
                    }
                    if (!array_key_exists($key, $mutuAncakReg[$month])) {
                        $mutuAncakReg[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $mutuAncakReg[$month][$key])) {
                        $mutuAncakReg[$month][$key][$key2] = array();
                    }
                    $mutuAncakReg[$month][$key][$key2][$key3] = $value3;
                }
            }
        }
        // dd($mutuAncakReg);

        // dd($dataPerBulan)
        //menimpa nilai default di atas dengan dataperbulan mutu transport yang ada isinya sehingga yang kosong menjadi 0
        foreach ($mutuAncakReg as $key2 => $value2) {
            foreach ($value2 as $key3 => $value3) {
                foreach ($value3 as $key4 => $value4) {
                    // foreach ($RegperbulanMTancak[$key2][$key3][$key4] as $key => $value) {
                    $RegperbulanMTancak[$key2][$key3][$key4] = $value4;
                }
            }
        }

        // dd($RegperbulanMTancak);
        $regMTancakAFD = array();
        foreach ($RegperbulanMTancak as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    $akp = 0;
                    $skor_bTinggal = 0;
                    $brdPerjjg = 0;
                    $pokok_panen = 0;
                    $janjang_panen = 0;
                    $p_panen = 0;
                    $k_panen = 0;
                    $bhts_panen  = 0;
                    $bhtm1_panen  = 0;
                    $bhtm2_panen  = 0;
                    $bhtm3_oanen  = 0;
                    $ttlSkorMA = 0;
                    $listBlokPerAfd = array();
                    $jum_ha = 0;
                    $pelepah_s = 0;
                    foreach ($value2 as $key3 => $value3) {
                        if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'];
                        }
                        $jum_ha = count($listBlokPerAfd);

                        $pokok_panen = json_decode($value3["pokok_dipanen"], true);
                        $jajang_panen = json_decode($value3["jjg_dipanen"], true);
                        $brtp = json_decode($value3["brtp"], true);
                        $brtk = json_decode($value3["brtk"], true);
                        $brtgl = json_decode($value3["brtgl"], true);
                        $pokok_panen  = count($pokok_panen);
                        $janjang_panen = array_sum($jajang_panen);
                        $p_panen = array_sum($brtp);
                        $k_panen = array_sum($brtk);
                        $brtgl_panen = array_sum($brtgl);
                        $bhts = json_decode($value3["bhts"], true);
                        $bhtm1 = json_decode($value3["bhtm1"], true);
                        $bhtm2 = json_decode($value3["bhtm2"], true);
                        $bhtm3 = json_decode($value3["bhtm3"], true);
                        $bhts_panen = array_sum($bhts);
                        $bhtm1_panen = array_sum($bhtm1);
                        $bhtm2_panen = array_sum($bhtm2);
                        $bhtm3_oanen = array_sum($bhtm3);
                        $ps = json_decode($value3["ps"], true);
                        $pelepah_s = array_sum($ps);
                    }
                    // $akp = ($janjang_panen / $pokok_panen) %
                    $akp = ($janjang_panen / $pokok_panen) * 100;
                    $skor_bTinggal = $p_panen + $k_panen + $brtgl_panen;
                    $brdPerjjg = $skor_bTinggal / $pokok_panen;
                    //skore PEnggunnan Brondolan
                    $skor_brdPerjjg = 0;
                    if ($brdPerjjg <= 1.0) {
                        $skor_brdPerjjg = 20;
                    } else if ($brdPerjjg >= 1.5 && $brdPerjjg <= 2.0) {
                        $skor_brdPerjjg = 12;
                    } else if ($brdPerjjg >= 2.0 && $brdPerjjg <= 2.5) {
                        $skor_brdPerjjg = 8;
                    } else if ($brdPerjjg >= 2.5 && $brdPerjjg <= 3.0) {
                        $skor_brdPerjjg = 4;
                    } else if ($brdPerjjg >= 3.0 && $brdPerjjg <= 3.5) {
                        $skor_brdPerjjg = 0;
                    } else if ($brdPerjjg >= 4.0 && $brdPerjjg <= 4.5) {
                        $skor_brdPerjjg = 8;
                    } else if ($brdPerjjg >=  4.5 && $brdPerjjg <= 5.0) {
                        $skor_brdPerjjg = 12;
                    } else if ($brdPerjjg >=  5.0) {
                        $skor_brdPerjjg = 16;
                    }


                    $sumBH = $bhts_panen +  $bhtm1_panen +  $bhtm2_panen +  $bhtm3_oanen;

                    $sumPerBH = $sumBH / ($janjang_panen + $sumBH) * 100;

                    $skor_bh = 0;
                    if ($sumPerBH <=  0.0) {
                        $skor_bh = 20;
                    } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                        $skor_bh = 18;
                    } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                        $skor_bh = 16;
                    } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                        $skor_bh = 12;
                    } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                        $skor_bh = 8;
                    } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                        $skor_bh = 4;
                    } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                        $skor_bh = 0;
                    } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                        $skor_bh = 0;
                    } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                        $skor_bh = 4;
                    } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                        $skor_bh = 8;
                    } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                        $skor_bh = 12;
                    } else if ($sumPerBH >= 5.0) {
                        $skor_bh = 10;
                    }
                    // data untuk pelepah sengklek

                    if ($pelepah_s != 0) {
                        $perPl = ($pokok_panen / $pelepah_s) * 100;
                    } else {
                        $perPl = 0;
                    }
                    $skor_perPl = 0;
                    if ($perPl <=  0.5) {
                        $skor_perPl = 5;
                    } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                        $skor_perPl = 4;
                    } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                        $skor_perPl = 3;
                    } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                        $skor_perPl = 2;
                    } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                        $skor_perPl = 1;
                    } else if ($perPl >= 2.5) {
                        $skor_perPl = 0;
                    }


                    $ttlSkorMA = $skor_brdPerjjg + $skor_bh + $skor_perPl;

                    $regMTancakAFD[$key][$key1][$key2]['pokok_sample'] = $pokok_panen;
                    $regMTancakAFD[$key][$key1][$key2]['ha_sample'] = $jum_ha;
                    $regMTancakAFD[$key][$key1][$key2]['jumlah_panen'] = $janjang_panen;
                    $regMTancakAFD[$key][$key1][$key2]['akp_rl'] =  number_format($akp, 2);

                    $regMTancakAFD[$key][$key1][$key2]['p'] = $p_panen;
                    $regMTancakAFD[$key][$key1][$key2]['k'] = $k_panen;
                    $regMTancakAFD[$key][$key1][$key2]['tgl'] = $skor_bTinggal;

                    // $regMTancakAFD[$key][$key1][$key2]['total_brd'] = $skor_bTinggal;
                    $regMTancakAFD[$key][$key1][$key2]['brd/jjg'] = number_format($brdPerjjg, 2);

                    // data untuk buah tinggal
                    $regMTancakAFD[$key][$key1][$key2]['bhts_s'] = $bhts_panen;
                    $regMTancakAFD[$key][$key1][$key2]['bhtm1'] = $bhtm1_panen;
                    $regMTancakAFD[$key][$key1][$key2]['bhtm2'] = $bhtm2_panen;
                    $regMTancakAFD[$key][$key1][$key2]['bhtm3'] = $bhtm3_oanen;


                    // $regMTancakAFD[$key][$key1][$key2]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek

                    $regMTancakAFD[$key][$key1][$key2]['palepah_pokok'] = $pelepah_s;
                    // total skor akhir
                    $regMTancakAFD[$key][$key1][$key2]['skor_bh'] = number_format($skor_bh, 2);
                    $regMTancakAFD[$key][$key1][$key2]['skor_brd'] = number_format($skor_brdPerjjg, 2);
                    $regMTancakAFD[$key][$key1][$key2]['skor_ps'] = number_format($skor_perPl, 2);
                    $regMTancakAFD[$key][$key1][$key2]['skor_akhir'] = number_format($ttlSkorMA, 2);
                } else {
                    $regMTancakAFD[$key][$key1][$key2]['pokok_sample'] = 0;
                    $regMTancakAFD[$key][$key1][$key2]['ha_sample'] = 0;
                    $regMTancakAFD[$key][$key1][$key2]['jumlah_panen'] = 0;
                    $regMTancakAFD[$key][$key1][$key2]['akp_rl'] = 0;

                    $regMTancakAFD[$key][$key1][$key2]['p'] = 0;
                    $regMTancakAFD[$key][$key1][$key2]['k'] = 0;
                    $regMTancakAFD[$key][$key1][$key2]['tgl'] = 0;

                    // $regMTancakAFD[$key][$key1][$key2]['total_brd'] = $skor_bTinggal;
                    $regMTancakAFD[$key][$key1][$key2]['brd/jjg'] = 0;
                    $regMTancakAFD[$key][$key1][$key2]['skor_brd'] = 0;
                    // data untuk buah tinggal
                    $regMTancakAFD[$key][$key1][$key2]['bhts_s'] = 0;
                    $regMTancakAFD[$key][$key1][$key2]['bhtm1'] = 0;
                    $regMTancakAFD[$key][$key1][$key2]['bhtm2'] = 0;
                    $regMTancakAFD[$key][$key1][$key2]['bhtm3'] = 0;

                    $regMTancakAFD[$key][$key1][$key2]['skor_bh'] = 0;
                    // $regMTancakAFD[$key][$key1][$key2]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek
                    $regMTancakAFD[$key][$key1][$key2]['skor_ps'] = 0;
                    $regMTancakAFD[$key][$key1][$key2]['palepah_pokok'] = 0;
                    // total skor akhir
                    $regMTancakAFD[$key][$key1][$key2]['skor_akhir'] = 0;
                }
            }
        }
        // dd($regMTancakAFD);

        $regMTancakEST = array();
        foreach ($regMTancakAFD as $key => $value) {
            foreach ($value as $key1 => $value1) if (!empty($value1)) {
                $pokok_sample = 0;
                $jum_ha = 0;
                $pokok_panen = 0;
                $p_panen = 0;
                $k_panen = 0;
                $tgl_panen = 0;
                $totalPKGL = 0;
                $brdPerjjg = 0;
                $bmts = 0;
                $bhtm1 = 0;
                $bhtm2 = 0;
                $bhtm3 = 0;
                $totalSM123 = 0;
                $palepah_pokok = 0;
                $perPl = 0;
                $sumPerBH = 0;
                $ttlSkorMA = 0;
                foreach ($value1 as $key2 => $value2) {
                    // dd($value2);
                    $pokok_sample += $value2['pokok_sample'];
                    $jum_ha += $value2['ha_sample'];
                    $pokok_panen += $value2['jumlah_panen'];
                    $p_panen += $value2['p'];
                    $k_panen += $value2['k'];
                    $tgl_panen += $value2['tgl'];

                    $bmts += $value2['bhts_s'];
                    $bhtm1 += $value2['bhtm1'];
                    $bhtm2 += $value2['bhtm2'];
                    $bhtm3 += $value2['bhtm3'];

                    $palepah_pokok += $value2['palepah_pokok'];
                }
                $totalPKGL = $p_panen + $k_panen + $tgl_panen;
                if ($pokok_panen != 0) {
                    $brdPerjjg = round($totalPKGL / $pokok_panen, 2);
                } else {
                    $brdPerjjg = 0;
                }

                $skor_brdPerjjg = 0;
                if ($brdPerjjg <= 1.0) {
                    $skor_brdPerjjg = 20;
                } else if ($brdPerjjg >= 1.5 && $brdPerjjg <= 2.0) {
                    $skor_brdPerjjg = 12;
                } else if ($brdPerjjg >= 2.0 && $brdPerjjg <= 2.5) {
                    $skor_brdPerjjg = 8;
                } else if ($brdPerjjg >= 2.5 && $brdPerjjg <= 3.0) {
                    $skor_brdPerjjg = 4;
                } else if ($brdPerjjg >= 3.0 && $brdPerjjg <= 3.5) {
                    $skor_brdPerjjg = 0;
                } else if ($brdPerjjg >= 4.0 && $brdPerjjg <= 4.5) {
                    $skor_brdPerjjg = 8;
                } else if ($brdPerjjg >=  4.5 && $brdPerjjg <= 5.0) {
                    $skor_brdPerjjg = 12;
                } else if ($brdPerjjg >=  5.0) {
                    $skor_brdPerjjg = 16;
                }

                $totalSM123  =  $bmts +  $bhtm1 + $bhtm2 + $bhtm3;

                if ($pokok_panen != 0) {
                    $sumPerBH = $totalSM123 / ($pokok_panen + $totalSM123) * 100;
                } else {
                    $sumPerBH = 0;
                }


                $skor_bh = 0;
                if ($sumPerBH <=  0.0) {
                    $skor_bh = 20;
                } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                    $skor_bh = 18;
                } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                    $skor_bh = 16;
                } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                    $skor_bh = 12;
                } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                    $skor_bh = 8;
                } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                    $skor_bh = 4;
                } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                    $skor_bh = 0;
                } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                    $skor_bh = 0;
                } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                    $skor_bh = 4;
                } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                    $skor_bh = 8;
                } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                    $skor_bh = 12;
                } else if ($sumPerBH >= 5.0) {
                    $skor_bh = 10;
                }


                if ($pelepah_s != 0) {
                    $perPl = round(($pokok_sample / $pelepah_s) * 100, 2);
                } else {
                    $perPl = 0;
                }

                $skor_perPl = 0;
                if ($perPl <=  0.5) {
                    $skor_perPl = 5;
                } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                    $skor_perPl = 4;
                } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                    $skor_perPl = 3;
                } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                    $skor_perPl = 2;
                } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                    $skor_perPl = 1;
                } else if ($perPl >= 2.5) {
                    $skor_perPl = 0;
                }

                $ttlSkorMA = $skor_brdPerjjg + $skor_bh + $skor_perPl;
                $regMTancakEST[$key][$key1]['pokok_sample'] = $pokok_sample;
                $regMTancakEST[$key][$key1]['ha_sample'] = $jum_ha;
                $regMTancakEST[$key][$key1]['pokok_panen'] = $pokok_panen;
                $regMTancakEST[$key][$key1]['p_panen'] = $p_panen;
                $regMTancakEST[$key][$key1]['k_panen'] = $k_panen;
                $regMTancakEST[$key][$key1]['tgl_panen'] = $tgl_panen;
                $regMTancakEST[$key][$key1]['brdPerjjg'] = $brdPerjjg;
                $regMTancakEST[$key][$key1]['skor_brdPerjjg'] = $skor_brdPerjjg;

                $regMTancakEST[$key][$key1]['bmts'] = $bmts;
                $regMTancakEST[$key][$key1]['bhtm1'] = $bhtm1;
                $regMTancakEST[$key][$key1]['bhtm2'] = $bhtm2;
                $regMTancakEST[$key][$key1]['bhtm3'] = $bhtm3;
                $regMTancakEST[$key][$key1]['skor_bh'] = $skor_bh;

                $regMTancakEST[$key][$key1]['palepah_pokok'] = $palepah_pokok;
                $regMTancakEST[$key][$key1]['perPl'] = $perPl;
                $regMTancakEST[$key][$key1]['skor_perPl'] = $skor_perPl;
                $regMTancakEST[$key][$key1]['total_skor'] = $ttlSkorMA;
            } else {
                $regMTancakEST[$key][$key1]['pokok_sample'] = 0;
                $regMTancakEST[$key][$key1]['ha_sample'] = 0;
                $regMTancakEST[$key][$key1]['pokok_panen'] = 0;
                $regMTancakEST[$key][$key1]['p_panen'] = 0;
                $regMTancakEST[$key][$key1]['k_panen'] = 0;
                $regMTancakEST[$key][$key1]['tgl_panen'] = 0;
                $regMTancakEST[$key][$key1]['brdPerjjg'] = 0;
                $regMTancakEST[$key][$key1]['skor_brdPerjjg'] = 0;
                $regMTancakEST[$key][$key1]['bmts'] = 0;
                $regMTancakEST[$key][$key1]['bhtm1'] = 0;
                $regMTancakEST[$key][$key1]['bhtm2'] = 0;
                $regMTancakEST[$key][$key1]['bhtm3'] = 0;
                $regMTancakEST[$key][$key1]['skor_bh'] = 0;
                $regMTancakEST[$key][$key1]['palepah_pokok'] = 0;
                $regMTancakEST[$key][$key1]['perPl'] = 0;
                $regMTancakEST[$key][$key1]['skor_perPl'] = 0;
                $regMTancakEST[$key][$key1]['total_skor'] = 0;;
            }
        }
        // dd($regMTancakEST);
        $RegMTancakBln = array();
        foreach ($regMTancakEST as $key => $value)    if (!empty($value)) {
            $pokok_sample = 0;
            $jum_ha = 0;
            $pokok_panen = 0;
            $p_panen = 0;
            $k_panen = 0;
            $tgl_panen = 0;
            $totalPKGL = 0;
            $brdPerjjg = 0;
            $bmts = 0;
            $bhtm1 = 0;
            $bhtm2 = 0;
            $bhtm3 = 0;
            $totalSM123 = 0;
            $palepah_pokok = 0;
            $perPl = 0;
            $sumPerBH = 0;
            $ttlSkorMA = 0;
            foreach ($value as $key1 => $value1) {
                // dd($value3);
                $pokok_sample += $value1['pokok_sample'];
                $jum_ha += $value1['ha_sample'];
                $pokok_panen += $value1['pokok_panen'];
                $p_panen += $value1['p_panen'];
                $k_panen += $value1['k_panen'];
                $tgl_panen += $value1['tgl_panen'];

                $bmts += $value1['bmts'];
                $bhtm1 += $value1['bhtm1'];
                $bhtm2 += $value1['bhtm2'];
                $bhtm3 += $value1['bhtm3'];

                $palepah_pokok += $value1['palepah_pokok'];
            }
            $totalPKGL = $p_panen + $k_panen + $tgl_panen;
            if ($pokok_panen != 0) {
                $brdPerjjg = round($totalPKGL / $pokok_panen, 2);
            } else {
                $brdPerjjg = 0;
            }

            $skor_brdPerjjg = 0;
            if ($brdPerjjg <= 1.0) {
                $skor_brdPerjjg = 20;
            } else if ($brdPerjjg >= 1.5 && $brdPerjjg <= 2.0) {
                $skor_brdPerjjg = 12;
            } else if ($brdPerjjg >= 2.0 && $brdPerjjg <= 2.5) {
                $skor_brdPerjjg = 8;
            } else if ($brdPerjjg >= 2.5 && $brdPerjjg <= 3.0) {
                $skor_brdPerjjg = 4;
            } else if ($brdPerjjg >= 3.0 && $brdPerjjg <= 3.5) {
                $skor_brdPerjjg = 0;
            } else if ($brdPerjjg >= 4.0 && $brdPerjjg <= 4.5) {
                $skor_brdPerjjg = 8;
            } else if ($brdPerjjg >=  4.5 && $brdPerjjg <= 5.0) {
                $skor_brdPerjjg = 12;
            } else if ($brdPerjjg >=  5.0) {
                $skor_brdPerjjg = 16;
            }

            $totalSM123  =  $bmts +  $bhtm1 + $bhtm2 + $bhtm3;

            if ($pokok_panen != 0) {
                $sumPerBH = $totalSM123 / ($pokok_panen + $totalSM123) * 100;
            } else {
                $sumPerBH = 0;
            }


            $skor_bh = 0;
            if ($sumPerBH <=  0.0) {
                $skor_bh = 20;
            } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                $skor_bh = 18;
            } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                $skor_bh = 16;
            } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                $skor_bh = 12;
            } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                $skor_bh = 8;
            } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                $skor_bh = 4;
            } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                $skor_bh = 0;
            } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                $skor_bh = 0;
            } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                $skor_bh = 4;
            } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                $skor_bh = 8;
            } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                $skor_bh = 12;
            } else if ($sumPerBH >= 5.0) {
                $skor_bh = 10;
            }


            if ($pelepah_s != 0) {
                $perPl = round(($pokok_sample / $pelepah_s) * 100, 2);
            } else {
                $perPl = 0;
            }

            $skor_perPl = 0;
            if ($perPl <=  0.5) {
                $skor_perPl = 5;
            } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                $skor_perPl = 4;
            } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                $skor_perPl = 3;
            } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                $skor_perPl = 2;
            } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                $skor_perPl = 1;
            } else if ($perPl >= 2.5) {
                $skor_perPl = 0;
            }

            $ttlSkorMA = $skor_brdPerjjg + $skor_bh + $skor_perPl;
            $RegMTancakBln[$key]['pokok_sample'] = $pokok_sample;
            $RegMTancakBln[$key]['ha_sample'] = $jum_ha;
            $RegMTancakBln[$key]['pokok_panen'] = $pokok_panen;
            $RegMTancakBln[$key]['p_panen'] = $p_panen;
            $RegMTancakBln[$key]['k_panen'] = $k_panen;
            $RegMTancakBln[$key]['tgl_panen'] = $tgl_panen;
            $RegMTancakBln[$key]['brdPerjjg'] = $brdPerjjg;
            $RegMTancakBln[$key]['skor_brdPerjjg'] = $skor_brdPerjjg;

            $RegMTancakBln[$key]['bmts'] = $bmts;
            $RegMTancakBln[$key]['bhtm1'] = $bhtm1;
            $RegMTancakBln[$key]['bhtm2'] = $bhtm2;
            $RegMTancakBln[$key]['bhtm3'] = $bhtm3;
            $RegMTancakBln[$key]['skor_bh'] = $skor_bh;

            $RegMTancakBln[$key]['palepah_pokok'] = $palepah_pokok;
            $RegMTancakBln[$key]['perPl'] = $perPl;
            $RegMTancakBln[$key]['skor_perPl'] = $skor_perPl;
            $RegMTancakBln[$key]['total_skor'] = $ttlSkorMA;
        } else {
            $RegMTancakBln[$key]['pokok_sample'] = 0;
            $RegMTancakBln[$key]['ha_sample'] = 0;
            $RegMTancakBln[$key]['pokok_panen'] = 0;
            $RegMTancakBln[$key]['p_panen'] = 0;
            $RegMTancakBln[$key]['k_panen'] = 0;
            $RegMTancakBln[$key]['tgl_panen'] = 0;
            $RegMTancakBln[$key]['brdPerjjg'] = 0;
            $RegMTancakBln[$key]['skor_brdPerjjg'] = 0;
            $RegMTancakBln[$key]['bmts'] = 0;
            $RegMTancakBln[$key]['bhtm1'] = 0;
            $RegMTancakBln[$key]['bhtm2'] = 0;
            $RegMTancakBln[$key]['bhtm3'] = 0;
            $RegMTancakBln[$key]['skor_bh'] = 0;
            $RegMTancakBln[$key]['palepah_pokok'] = 0;
            $RegMTancakBln[$key]['perPl'] = 0;
            $RegMTancakBln[$key]['skor_perPl'] = 0;
            $RegMTancakBln[$key]['total_skor'] = 0;;
        }
        // dd($RegMTancakBln);
        //menghitung MT ancak peregion PErthaun all estate
        $RegMTanckTHn = array();
        $pokok_sample = 0;
        $jum_ha = 0;
        $pokok_panen = 0;
        $p_panen = 0;
        $k_panen = 0;
        $tgl_panen = 0;
        $totalPKGL = 0;
        $brdPerjjg = 0;
        $bmts = 0;
        $bhtm1 = 0;
        $bhtm2 = 0;
        $bhtm3 = 0;
        $totalSM123 = 0;
        $palepah_pokok = 0;
        $perPl = 0;
        $sumPerBH = 0;
        $ttlSkorMA = 0;
        foreach ($WilMtAncakThn as $key => $value) {
            $pokok_sample += $value['pokok_sample'];
            $jum_ha += $value['ha_sample'];
            $pokok_panen += $value['pokok_panen'];
            $p_panen += $value['p_panen'];
            $k_panen += $value['k_panen'];
            $tgl_panen += $value['tgl_panen'];

            $bmts += $value['bmts'];
            $bhtm1 += $value['bhtm1'];
            $bhtm2 += $value['bhtm2'];
            $bhtm3 += $value['bhtm3'];

            $palepah_pokok += $value['palepah_pokok'];
        }

        $totalPKGL = $p_panen + $k_panen + $tgl_panen;
        if ($pokok_panen != 0) {
            $brdPerjjg = round($totalPKGL / $pokok_panen, 2);
        } else {
            $brdPerjjg = 0;
        }

        $skor_brdPerjjg = 0;
        if ($brdPerjjg <= 1.0) {
            $skor_brdPerjjg = 20;
        } else if ($brdPerjjg >= 1.5 && $brdPerjjg <= 2.0) {
            $skor_brdPerjjg = 12;
        } else if ($brdPerjjg >= 2.0 && $brdPerjjg <= 2.5) {
            $skor_brdPerjjg = 8;
        } else if ($brdPerjjg >= 2.5 && $brdPerjjg <= 3.0) {
            $skor_brdPerjjg = 4;
        } else if ($brdPerjjg >= 3.0 && $brdPerjjg <= 3.5) {
            $skor_brdPerjjg = 0;
        } else if ($brdPerjjg >= 4.0 && $brdPerjjg <= 4.5) {
            $skor_brdPerjjg = 8;
        } else if ($brdPerjjg >=  4.5 && $brdPerjjg <= 5.0) {
            $skor_brdPerjjg = 12;
        } else if ($brdPerjjg >=  5.0) {
            $skor_brdPerjjg = 16;
        }

        $totalSM123  =  $bmts +  $bhtm1 + $bhtm2 + $bhtm3;

        if ($pokok_panen != 0) {
            $sumPerBH = $totalSM123 / ($pokok_panen + $totalSM123) * 100;
        } else {
            $sumPerBH = 0;
        }


        $skor_bh = 0;
        if ($sumPerBH <=  0.0) {
            $skor_bh = 20;
        } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
            $skor_bh = 18;
        } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
            $skor_bh = 16;
        } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
            $skor_bh = 12;
        } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
            $skor_bh = 8;
        } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
            $skor_bh = 4;
        } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
            $skor_bh = 0;
        } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
            $skor_bh = 0;
        } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
            $skor_bh = 4;
        } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
            $skor_bh = 8;
        } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
            $skor_bh = 12;
        } else if ($sumPerBH >= 5.0) {
            $skor_bh = 10;
        }


        if ($palepah_pokok != 0) {
            $perPl = round(($pokok_sample / $palepah_pokok) * 100, 2);
        } else {
            $perPl = 0;
        }

        $skor_perPl = 0;
        if ($perPl <=  0.5) {
            $skor_perPl = 5;
        } else if ($perPl >=  0.5 && $perPl <= 1.0) {
            $skor_perPl = 4;
        } else if ($perPl >= 1.0 && $perPl <= 1.5) {
            $skor_perPl = 3;
        } else if ($perPl >= 1.5 && $perPl <= 2.0) {
            $skor_perPl = 2;
        } else if ($perPl >= 2.0 && $perPl <= 2.5) {
            $skor_perPl = 1;
        } else if ($perPl >= 2.5) {
            $skor_perPl = 0;
        }


        $ttlSkorMA = $skor_brdPerjjg + $skor_bh + $skor_perPl;

        $RegMTanckTHn['I']['pokok_sample'] = $pokok_sample;
        $RegMTanckTHn['I']['pokok_sample'] = $pokok_sample;
        $RegMTanckTHn['I']['ha_sample'] = $jum_ha;
        $RegMTanckTHn['I']['pokok_panen'] = $pokok_panen;
        $RegMTanckTHn['I']['p_panen'] = $p_panen;
        $RegMTanckTHn['I']['k_panen'] = $k_panen;
        $RegMTanckTHn['I']['tgl_panen'] = $tgl_panen;
        $RegMTanckTHn['I']['brdPerjjg'] = $brdPerjjg;
        $RegMTanckTHn['I']['skor_brdPerjjg'] = $skor_brdPerjjg;

        $RegMTanckTHn['I']['bmts'] = $bmts;
        $RegMTanckTHn['I']['bhtm1'] = $bhtm1;
        $RegMTanckTHn['I']['bhtm2'] = $bhtm2;
        $RegMTanckTHn['I']['bhtm3'] = $bhtm3;
        $RegMTanckTHn['I']['skor_bh'] = $skor_bh;

        $RegMTanckTHn['I']['palepah_pokok'] = $palepah_pokok;
        $RegMTanckTHn['I']['perPl'] = $perPl;
        $RegMTanckTHn['I']['skor_perPl'] = $skor_perPl;
        $RegMTanckTHn['I']['total_skor'] = $ttlSkorMA;

        // dd($RegMTanckTHn);

        //end perhitungan MT ancak
        $defPerbulanWil = array();
        foreach ($bulan as $key => $value) {
            foreach ($queryEste as $key2 => $value2) {
                foreach ($queryAfd as $key3 => $value3) {
                    if ($value2['est'] == $value3['est']) {
                        $defPerbulanWil[$value][$value2['est']][$value3['nama']] = 0;
                        // $defPerbulanWil[$value][$value2['est']][$value] = 0;
                    }
                }
            }
        }

        $dataTransportBulan = array();
        foreach ($queryMTtrans as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $dataTransportBulan)) {
                        $dataTransportBulan[$month] = array();
                    }
                    if (!array_key_exists($key, $dataTransportBulan[$month])) {
                        $dataTransportBulan[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $dataTransportBulan[$month][$key])) {
                        $dataTransportBulan[$month][$key][$key2] = array();
                    }
                    $dataTransportBulan[$month][$key][$key2][$key3] = $value3;
                }
            }
        }
        // dd($dataTransportBulan);

        // dd($dataPerBulan)
        //menimpa nilai default di atas dengan dataperbulan mutu transport yang ada isinya sehingga yang kosong menjadi 0
        foreach ($dataTransportBulan as $key2 => $value2) {
            foreach ($value2 as $key3 => $value3) {
                foreach ($value3 as $key4 => $value4) {
                    // foreach ($defPerbulanWil[$key2][$key3][$key4] as $key => $value) {
                    $defPerbulanWil[$key2][$key3][$key4] = $value4;
                }
            }
        }

        // dd($defPerbulanWil);
        //membuat data mutu transpot berdasarakan wilayah 1,2,3
        $mtTransWil = array();
        foreach ($queryEste as $key => $value) {
            foreach ($defPerbulanWil as $key2 => $value2) {
                // dd($value2);
                foreach ($value2 as $key3 => $value3) {
                    if ($value['est'] == $key3) {
                        $mtTransWil[$value['wil']][$key2][$key3] = $value3;
                    }
                }
            }
        }
        // dd($mtTransWil);

        //perhitungan untuk mutu transport
        //hitungan berdsarkan bulan > afd
        $mtTransAFDblan = array();
        foreach ($mtTransWil as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) if (is_array($value3)) {
                        $sum_bt = 0;
                        $sum_rst = 0;
                        $brdPertph = 0;
                        $buahPerTPH = 0;
                        $totalSkor = 0;
                        $dataBLok = 0;
                        $listBlokPerAfd = array();
                        foreach ($value3 as $key4 => $value4) {
                            // dd($value4);

                            if (!in_array($value4['estate'] . ' ' . $value4['afdeling'] . ' ' . $value4['blok'] . ' ' . $value4['tph_baris'], $listBlokPerAfd)) {
                                $listBlokPerAfd[] = $value4['estate'] . ' ' . $value4['afdeling'] . ' ' . $value4['blok'] . ' ' . $value4['tph_baris'];
                            }
                            $dataBLok = count($listBlokPerAfd);
                            $sum_bt += $value4['bt'];
                            $sum_rst += $value4['rst'];
                        }

                        $brdPertph = round($sum_bt / $dataBLok, 2);
                        $buahPerTPH = round($sum_rst / $dataBLok, 2);

                        //menghitung skor butir
                        $skor_brdPertph = 0;
                        if ($brdPertph <= 3) {
                            $skor_brdPertph = 10;
                        } else if ($brdPertph >= 3 && $brdPertph <= 5) {
                            $skor_brdPertph = 8;
                        } else if ($brdPertph >= 5 && $brdPertph <= 7) {
                            $skor_brdPertph = 6;
                        } else if ($brdPertph >= 7 && $brdPertph <= 9) {
                            $skor_brdPertph = 4;
                        } else if ($brdPertph >= 9 && $brdPertph <= 11) {
                            $skor_brdPertph = 2;
                        } else if ($brdPertph >= 11) {
                            $skor_brdPertph = 0;
                        }
                        //menghitung Skor Restant
                        $skor_buahPerTPH = 0;
                        if ($buahPerTPH <= 0.0) {
                            $skor_buahPerTPH = 10;
                        } else if ($buahPerTPH >= 0.0 && $buahPerTPH <= 0.5) {
                            $skor_buahPerTPH = 8;
                        } else if ($buahPerTPH >= 0.5 && $buahPerTPH <= 1) {
                            $skor_buahPerTPH = 6;
                        } else if ($buahPerTPH >= 1.0 && $buahPerTPH <= 1.5) {
                            $skor_buahPerTPH = 4;
                        } else if ($buahPerTPH >= 1.5 && $buahPerTPH <= 2.0) {
                            $skor_buahPerTPH = 2;
                        } else if ($buahPerTPH >= 2.0 && $buahPerTPH <= 2.5) {
                            $skor_buahPerTPH = 0;
                        } else if ($buahPerTPH >= 2.5 && $buahPerTPH <= 3.0) {
                            $skor_buahPerTPH = 2;
                        } else if ($buahPerTPH >= 3.0 && $buahPerTPH <= 3.5) {
                            $skor_buahPerTPH = 4;
                        } else if ($buahPerTPH >= 3.5 && $buahPerTPH <= 4.0) {
                            $skor_buahPerTPH = 6;
                        } else if ($buahPerTPH >= 4.0) {
                            $skor_buahPerTPH = 8;
                        }

                        $totalSkor = $skor_buahPerTPH + $skor_brdPertph;

                        $mtTransAFDblan[$key][$key1][$key2][$key3]['tph_sample'] = $dataBLok;
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['total_brd'] = $sum_bt;
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['total_brd/TPH'] = $brdPertph;
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['total_buah'] = $sum_rst;
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['total_buahPerTPH'] = $buahPerTPH;
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['skor_brdPertph'] = $skor_brdPertph;
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['skor_buahPerTPH'] = $skor_buahPerTPH;
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['totalSkor'] = $totalSkor;
                    } else {
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['tph_sample'] = 0;
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['total_brd'] = 0;
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['total_brd/TPH'] = 0;
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['total_buah'] = 0;
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['total_buahPerTPH'] = 0;
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['skor_brdPertph'] = 0;
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['skor_buahPerTPH'] = 0;
                        $mtTransAFDblan[$key][$key1][$key2][$key3]['totalSkor'] = 0;
                    }
                }
            }
        }
        // dd($mtTransAFDblan);
        //perhitungan mutu transport bulan per > estate
        $mtTransESTblan = array();
        foreach ($mtTransAFDblan as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) if (!empty($value2)) {
                    $sum_bt = 0;
                    $sum_rst = 0;
                    $brdPertph = 0;
                    $buahPerTPH = 0;
                    $totalSkor = 0;
                    $dataBLok = 0;
                    foreach ($value2 as $key3 => $value3) {
                        // dd($value3);
                        $sum_bt += $value3['total_brd'];
                        $sum_rst += $value3['total_buah'];
                        $dataBLok += $value3['tph_sample'];
                    }

                    if ($dataBLok != 0) {
                        $brdPertph = round($sum_bt / $dataBLok, 2);
                    } else {
                        $brdPertph = 0;
                    }

                    if ($dataBLok != 0) {
                        $buahPerTPH = round($sum_rst / $dataBLok, 2);
                    } else {
                        $buahPerTPH = 0;
                    }

                    //menghitung skor butir
                    $skor_brdPertph = 0;
                    if ($brdPertph <= 3) {
                        $skor_brdPertph = 10;
                    } else if ($brdPertph >= 3 && $brdPertph <= 5) {
                        $skor_brdPertph = 8;
                    } else if ($brdPertph >= 5 && $brdPertph <= 7) {
                        $skor_brdPertph = 6;
                    } else if ($brdPertph >= 7 && $brdPertph <= 9) {
                        $skor_brdPertph = 4;
                    } else if ($brdPertph >= 9 && $brdPertph <= 11) {
                        $skor_brdPertph = 2;
                    } else if ($brdPertph >= 11) {
                        $skor_brdPertph = 0;
                    }
                    //menghitung Skor Restant
                    $skor_buahPerTPH = 0;
                    if ($buahPerTPH <= 0.0) {
                        $skor_buahPerTPH = 10;
                    } else if ($buahPerTPH >= 0.0 && $buahPerTPH <= 0.5) {
                        $skor_buahPerTPH = 8;
                    } else if ($buahPerTPH >= 0.5 && $buahPerTPH <= 1) {
                        $skor_buahPerTPH = 6;
                    } else if ($buahPerTPH >= 1.0 && $buahPerTPH <= 1.5) {
                        $skor_buahPerTPH = 4;
                    } else if ($buahPerTPH >= 1.5 && $buahPerTPH <= 2.0) {
                        $skor_buahPerTPH = 2;
                    } else if ($buahPerTPH >= 2.0 && $buahPerTPH <= 2.5) {
                        $skor_buahPerTPH = 0;
                    } else if ($buahPerTPH >= 2.5 && $buahPerTPH <= 3.0) {
                        $skor_buahPerTPH = 2;
                    } else if ($buahPerTPH >= 3.0 && $buahPerTPH <= 3.5) {
                        $skor_buahPerTPH = 4;
                    } else if ($buahPerTPH >= 3.5 && $buahPerTPH <= 4.0) {
                        $skor_buahPerTPH = 6;
                    } else if ($buahPerTPH >= 4.0) {
                        $skor_buahPerTPH = 8;
                    }

                    $totalSkor = $skor_buahPerTPH + $skor_brdPertph;


                    $mtTransESTblan[$key][$key1][$key2]['tph_sample'] = $dataBLok;
                    $mtTransESTblan[$key][$key1][$key2]['total_brd'] = $sum_bt;
                    $mtTransESTblan[$key][$key1][$key2]['total_brd/TPH'] = $brdPertph;
                    $mtTransESTblan[$key][$key1][$key2]['total_buah'] = $sum_rst;
                    $mtTransESTblan[$key][$key1][$key2]['total_buahPerTPH'] = $buahPerTPH;
                    $mtTransESTblan[$key][$key1][$key2]['skor_brdPertph'] = $skor_brdPertph;
                    $mtTransESTblan[$key][$key1][$key2]['skor_buahPerTPH'] = $skor_buahPerTPH;
                    $mtTransESTblan[$key][$key1][$key2]['totalSkor'] = $totalSkor;
                } else {

                    $mtTransESTblan[$key][$key1][$key2]['tph_sample'] = 0;
                    $mtTransESTblan[$key][$key1][$key2]['total_brd'] = 0;
                    $mtTransESTblan[$key][$key1][$key2]['total_brd/TPH'] = 0;
                    $mtTransESTblan[$key][$key1][$key2]['total_buah'] = 0;
                    $mtTransESTblan[$key][$key1][$key2]['total_buahPerTPH'] = 0;
                    $mtTransESTblan[$key][$key1][$key2]['skor_brdPertph'] = 0;
                    $mtTransESTblan[$key][$key1][$key2]['skor_buahPerTPH'] = 0;
                    $mtTransESTblan[$key][$key1][$key2]['totalSkor'] = 0;
                }
            }
        }
        // dd($mtTransESTblan);
        // menghitung mututransprt unutk data perbulan dari semua estate
        $mtTranstAllbln = array();
        foreach ($mtTransESTblan as $key => $value) {
            foreach ($value as $key1 => $value1) if (!empty($value1)) {
                $dataBLok = 0;
                $sum_bt = 0;
                $sum_rst = 0;
                $brdPertph = 0;
                $buahPerTPH = 0;

                foreach ($value1 as $Key2 => $value2) {
                    $sum_bt += $value2['total_brd'];
                    $sum_rst += $value2['total_buah'];
                    $dataBLok += $value2['tph_sample'];
                }

                if ($dataBLok != 0) {
                    $brdPertph = round($sum_bt / $dataBLok, 2);
                } else {
                    $brdPertph = 0;
                }

                if ($dataBLok != 0) {
                    $buahPerTPH = round($sum_rst / $dataBLok, 2);
                } else {
                    $buahPerTPH = 0;
                }

                //menghitung skor butir
                $skor_brdPertph = 0;
                if ($brdPertph <= 3) {
                    $skor_brdPertph = 10;
                } else if ($brdPertph >= 3 && $brdPertph <= 5) {
                    $skor_brdPertph = 8;
                } else if ($brdPertph >= 5 && $brdPertph <= 7) {
                    $skor_brdPertph = 6;
                } else if ($brdPertph >= 7 && $brdPertph <= 9) {
                    $skor_brdPertph = 4;
                } else if ($brdPertph >= 9 && $brdPertph <= 11) {
                    $skor_brdPertph = 2;
                } else if ($brdPertph >= 11) {
                    $skor_brdPertph = 0;
                }
                //menghitung Skor Restant
                $skor_buahPerTPH = 0;
                if ($buahPerTPH <= 0.0) {
                    $skor_buahPerTPH = 10;
                } else if ($buahPerTPH >= 0.0 && $buahPerTPH <= 0.5) {
                    $skor_buahPerTPH = 8;
                } else if ($buahPerTPH >= 0.5 && $buahPerTPH <= 1) {
                    $skor_buahPerTPH = 6;
                } else if ($buahPerTPH >= 1.0 && $buahPerTPH <= 1.5) {
                    $skor_buahPerTPH = 4;
                } else if ($buahPerTPH >= 1.5 && $buahPerTPH <= 2.0) {
                    $skor_buahPerTPH = 2;
                } else if ($buahPerTPH >= 2.0 && $buahPerTPH <= 2.5) {
                    $skor_buahPerTPH = 0;
                } else if ($buahPerTPH >= 2.5 && $buahPerTPH <= 3.0) {
                    $skor_buahPerTPH = 2;
                } else if ($buahPerTPH >= 3.0 && $buahPerTPH <= 3.5) {
                    $skor_buahPerTPH = 4;
                } else if ($buahPerTPH >= 3.5 && $buahPerTPH <= 4.0) {
                    $skor_buahPerTPH = 6;
                } else if ($buahPerTPH >= 4.0) {
                    $skor_buahPerTPH = 8;
                }

                $totalSkor = $skor_buahPerTPH + $skor_brdPertph;


                $mtTranstAllbln[$key][$key1]['tph_sample'] = $dataBLok;
                $mtTranstAllbln[$key][$key1]['total_brd'] = $sum_bt;
                $mtTranstAllbln[$key][$key1]['total_brd/TPH'] = $brdPertph;
                $mtTranstAllbln[$key][$key1]['total_buah'] = $sum_rst;
                $mtTranstAllbln[$key][$key1]['total_buahPerTPH'] = $buahPerTPH;
                $mtTranstAllbln[$key][$key1]['skor_brdPertph'] = $skor_brdPertph;
                $mtTranstAllbln[$key][$key1]['skor_buahPerTPH'] = $skor_buahPerTPH;
                $mtTranstAllbln[$key][$key1]['totalSkor'] = $totalSkor;
            } else {
                $mtTranstAllbln[$key][$key1]['tph_sample'] = 0;
                $mtTranstAllbln[$key][$key1]['total_brd'] = 0;
                $mtTranstAllbln[$key][$key1]['total_brd/TPH'] = 0;
                $mtTranstAllbln[$key][$key1]['total_buah'] = 0;
                $mtTranstAllbln[$key][$key1]['total_buahPerTPH'] = 0;
                $mtTranstAllbln[$key][$key1]['skor_brdPertph'] = 0;
                $mtTranstAllbln[$key][$key1]['skor_buahPerTPH'] = 0;
                $mtTranstAllbln[$key][$key1]['totalSkor'] = 0;
            }
        }

        // dd($mtTranstAllbln);
        //perhitungan mutu transprt pertahun
        $mtTransTahun = array();
        foreach ($mtTranstAllbln as $key => $value) if (!empty($value)) {
            $dataBLok = 0;
            $sum_bt = 0;
            $sum_rst = 0;
            $brdPertph = 0;
            $buahPerTPH = 0;
            foreach ($value as $key1 => $value2) {
                // dd($value2);
                $sum_bt += $value2['total_brd'];
                $sum_rst += $value2['total_buah'];
                $dataBLok += $value2['tph_sample'];
            }
            if ($dataBLok != 0) {
                $brdPertph = round($sum_bt / $dataBLok, 2);
            } else {
                $brdPertph = 0;
            }

            if ($dataBLok != 0) {
                $buahPerTPH = round($sum_rst / $dataBLok, 2);
            } else {
                $buahPerTPH = 0;
            }

            //menghitung skor butir
            $skor_brdPertph = 0;
            if ($brdPertph <= 3) {
                $skor_brdPertph = 10;
            } else if ($brdPertph >= 3 && $brdPertph <= 5) {
                $skor_brdPertph = 8;
            } else if ($brdPertph >= 5 && $brdPertph <= 7) {
                $skor_brdPertph = 6;
            } else if ($brdPertph >= 7 && $brdPertph <= 9) {
                $skor_brdPertph = 4;
            } else if ($brdPertph >= 9 && $brdPertph <= 11) {
                $skor_brdPertph = 2;
            } else if ($brdPertph >= 11) {
                $skor_brdPertph = 0;
            }
            //menghitung Skor Restant
            $skor_buahPerTPH = 0;
            if ($buahPerTPH <= 0.0) {
                $skor_buahPerTPH = 10;
            } else if ($buahPerTPH >= 0.0 && $buahPerTPH <= 0.5) {
                $skor_buahPerTPH = 8;
            } else if ($buahPerTPH >= 0.5 && $buahPerTPH <= 1) {
                $skor_buahPerTPH = 6;
            } else if ($buahPerTPH >= 1.0 && $buahPerTPH <= 1.5) {
                $skor_buahPerTPH = 4;
            } else if ($buahPerTPH >= 1.5 && $buahPerTPH <= 2.0) {
                $skor_buahPerTPH = 2;
            } else if ($buahPerTPH >= 2.0 && $buahPerTPH <= 2.5) {
                $skor_buahPerTPH = 0;
            } else if ($buahPerTPH >= 2.5 && $buahPerTPH <= 3.0) {
                $skor_buahPerTPH = 2;
            } else if ($buahPerTPH >= 3.0 && $buahPerTPH <= 3.5) {
                $skor_buahPerTPH = 4;
            } else if ($buahPerTPH >= 3.5 && $buahPerTPH <= 4.0) {
                $skor_buahPerTPH = 6;
            } else if ($buahPerTPH >= 4.0) {
                $skor_buahPerTPH = 8;
            }

            $totalSkor = $skor_buahPerTPH + $skor_brdPertph;


            $mtTransTahun[$key]['tph_sample'] = $dataBLok;
            $mtTransTahun[$key]['total_brd'] = $sum_bt;
            $mtTransTahun[$key]['total_brd/TPH'] = $brdPertph;
            $mtTransTahun[$key]['total_buah'] = $sum_rst;
            $mtTransTahun[$key]['total_buahPerTPH'] = $buahPerTPH;
            $mtTransTahun[$key]['skor_brdPertph'] = $skor_brdPertph;
            $mtTransTahun[$key]['skor_buahPerTPH'] = $skor_buahPerTPH;
            $mtTransTahun[$key]['totalSkor'] = $totalSkor;
        } else {
            $mtTransTahun[$key]['tph_sample'] = $dataBLok;
            $mtTransTahun[$key]['total_brd'] = $sum_bt;
            $mtTransTahun[$key]['total_brd/TPH'] = $brdPertph;
            $mtTransTahun[$key]['total_buah'] = $sum_rst;
            $mtTransTahun[$key]['total_buahPerTPH'] = $buahPerTPH;
            $mtTransTahun[$key]['skor_brdPertph'] = $skor_brdPertph;
            $mtTransTahun[$key]['skor_buahPerTPH'] = $skor_buahPerTPH;
            $mtTransTahun[$key]['totalSkor'] = $totalSkor;
        }

        //menghitung untuk regional 1
        $RegTransAFD = array();
        foreach ($defPerbulanWil as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) if (is_array($value2)) {
                    $sum_bt = 0;
                    $sum_rst = 0;
                    $brdPertph = 0;
                    $buahPerTPH = 0;
                    $totalSkor = 0;
                    $dataBLok = 0;
                    $listBlokPerAfd = array();
                    foreach ($value2 as $key4 => $value3) {
                        if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'] . ' ' . $value3['tph_baris'], $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'] . ' ' . $value3['tph_baris'];
                        }
                        $dataBLok = count($listBlokPerAfd);
                        $sum_bt += $value3['bt'];
                        $sum_rst += $value3['rst'];
                    }
                    $brdPertph = round($sum_bt / $dataBLok, 2);
                    $buahPerTPH = round($sum_rst / $dataBLok, 2);

                    //menghitung skor butir
                    $skor_brdPertph = 0;
                    if ($brdPertph <= 3) {
                        $skor_brdPertph = 10;
                    } else if ($brdPertph >= 3 && $brdPertph <= 5) {
                        $skor_brdPertph = 8;
                    } else if ($brdPertph >= 5 && $brdPertph <= 7) {
                        $skor_brdPertph = 6;
                    } else if ($brdPertph >= 7 && $brdPertph <= 9) {
                        $skor_brdPertph = 4;
                    } else if ($brdPertph >= 9 && $brdPertph <= 11) {
                        $skor_brdPertph = 2;
                    } else if ($brdPertph >= 11) {
                        $skor_brdPertph = 0;
                    }
                    //menghitung Skor Restant
                    $skor_buahPerTPH = 0;
                    if ($buahPerTPH <= 0.0) {
                        $skor_buahPerTPH = 10;
                    } else if ($buahPerTPH >= 0.0 && $buahPerTPH <= 0.5) {
                        $skor_buahPerTPH = 8;
                    } else if ($buahPerTPH >= 0.5 && $buahPerTPH <= 1) {
                        $skor_buahPerTPH = 6;
                    } else if ($buahPerTPH >= 1.0 && $buahPerTPH <= 1.5) {
                        $skor_buahPerTPH = 4;
                    } else if ($buahPerTPH >= 1.5 && $buahPerTPH <= 2.0) {
                        $skor_buahPerTPH = 2;
                    } else if ($buahPerTPH >= 2.0 && $buahPerTPH <= 2.5) {
                        $skor_buahPerTPH = 0;
                    } else if ($buahPerTPH >= 2.5 && $buahPerTPH <= 3.0) {
                        $skor_buahPerTPH = 2;
                    } else if ($buahPerTPH >= 3.0 && $buahPerTPH <= 3.5) {
                        $skor_buahPerTPH = 4;
                    } else if ($buahPerTPH >= 3.5 && $buahPerTPH <= 4.0) {
                        $skor_buahPerTPH = 6;
                    } else if ($buahPerTPH >= 4.0) {
                        $skor_buahPerTPH = 8;
                    }

                    $totalSkor = $skor_buahPerTPH + $skor_brdPertph;

                    $RegTransAFD[$key][$key1][$key2]['tph_sample'] = $dataBLok;
                    $RegTransAFD[$key][$key1][$key2]['total_brd'] = $sum_bt;
                    $RegTransAFD[$key][$key1][$key2]['total_brd/TPH'] = $brdPertph;
                    $RegTransAFD[$key][$key1][$key2]['total_buah'] = $sum_rst;
                    $RegTransAFD[$key][$key1][$key2]['total_buahPerTPH'] = $buahPerTPH;
                    $RegTransAFD[$key][$key1][$key2]['skor_brdPertph'] = $skor_brdPertph;
                    $RegTransAFD[$key][$key1][$key2]['skor_buahPerTPH'] = $skor_buahPerTPH;
                    $RegTransAFD[$key][$key1][$key2]['totalSkor'] = $totalSkor;
                } else {
                    $RegTransAFD[$key][$key1][$key2]['tph_sample'] = 0;
                    $RegTransAFD[$key][$key1][$key2]['total_brd'] = 0;
                    $RegTransAFD[$key][$key1][$key2]['total_brd/TPH'] = 0;
                    $RegTransAFD[$key][$key1][$key2]['total_buah'] = 0;
                    $RegTransAFD[$key][$key1][$key2]['total_buahPerTPH'] = 0;
                    $RegTransAFD[$key][$key1][$key2]['skor_brdPertph'] = 0;
                    $RegTransAFD[$key][$key1][$key2]['skor_buahPerTPH'] = 0;
                    $RegTransAFD[$key][$key1][$key2]['totalSkor'] = 0;
                }
            }
        }

        // dd($RegTransAFD);
        $RegTransEst = array();
        foreach ($RegTransAFD as $key => $value) {
            foreach ($value as $key1 => $value1) if (is_array($value1)) {
                $sum_bt = 0;
                $sum_rst = 0;
                $brdPertph = 0;
                $buahPerTPH = 0;
                $totalSkor = 0;
                $dataBLok = 0;
                foreach ($value1 as $key2 => $value2) {
                    $sum_bt += $value2['total_brd'];
                    $sum_rst += $value2['total_buah'];
                    $dataBLok += $value2['tph_sample'];
                }
                if ($dataBLok != 0) {
                    $brdPertph = round($sum_bt / $dataBLok, 2);
                } else {
                    $brdPertph = 0;
                }

                if ($dataBLok != 0) {
                    $buahPerTPH = round($sum_rst / $dataBLok, 2);
                } else {
                    $buahPerTPH = 0;
                }

                //menghitung skor butir
                $skor_brdPertph = 0;
                if ($brdPertph <= 3) {
                    $skor_brdPertph = 10;
                } else if ($brdPertph >= 3 && $brdPertph <= 5) {
                    $skor_brdPertph = 8;
                } else if ($brdPertph >= 5 && $brdPertph <= 7) {
                    $skor_brdPertph = 6;
                } else if ($brdPertph >= 7 && $brdPertph <= 9) {
                    $skor_brdPertph = 4;
                } else if ($brdPertph >= 9 && $brdPertph <= 11) {
                    $skor_brdPertph = 2;
                } else if ($brdPertph >= 11) {
                    $skor_brdPertph = 0;
                }
                //menghitung Skor Restant
                $skor_buahPerTPH = 0;
                if ($buahPerTPH <= 0.0) {
                    $skor_buahPerTPH = 10;
                } else if ($buahPerTPH >= 0.0 && $buahPerTPH <= 0.5) {
                    $skor_buahPerTPH = 8;
                } else if ($buahPerTPH >= 0.5 && $buahPerTPH <= 1) {
                    $skor_buahPerTPH = 6;
                } else if ($buahPerTPH >= 1.0 && $buahPerTPH <= 1.5) {
                    $skor_buahPerTPH = 4;
                } else if ($buahPerTPH >= 1.5 && $buahPerTPH <= 2.0) {
                    $skor_buahPerTPH = 2;
                } else if ($buahPerTPH >= 2.0 && $buahPerTPH <= 2.5) {
                    $skor_buahPerTPH = 0;
                } else if ($buahPerTPH >= 2.5 && $buahPerTPH <= 3.0) {
                    $skor_buahPerTPH = 2;
                } else if ($buahPerTPH >= 3.0 && $buahPerTPH <= 3.5) {
                    $skor_buahPerTPH = 4;
                } else if ($buahPerTPH >= 3.5 && $buahPerTPH <= 4.0) {
                    $skor_buahPerTPH = 6;
                } else if ($buahPerTPH >= 4.0) {
                    $skor_buahPerTPH = 8;
                }

                $totalSkor = $skor_buahPerTPH + $skor_brdPertph;


                $RegTransEst[$key][$key1]['tph_sample'] = $dataBLok;
                $RegTransEst[$key][$key1]['total_brd'] = $sum_bt;
                $RegTransEst[$key][$key1]['total_brd/TPH'] = $brdPertph;
                $RegTransEst[$key][$key1]['total_buah'] = $sum_rst;
                $RegTransEst[$key][$key1]['total_buahPerTPH'] = $buahPerTPH;
                $RegTransEst[$key][$key1]['skor_brdPertph'] = $skor_brdPertph;
                $RegTransEst[$key][$key1]['skor_buahPerTPH'] = $skor_buahPerTPH;
                $RegTransEst[$key][$key1]['totalSkor'] = $totalSkor;
            } else {
                $RegTransEst[$key][$key1]['tph_sample'] = 0;
                $RegTransEst[$key][$key1]['total_brd'] = 0;
                $RegTransEst[$key][$key1]['total_brd/TPH'] = 0;
                $RegTransEst[$key][$key1]['total_buah'] = 0;
                $RegTransEst[$key][$key1]['total_buahPerTPH'] = 0;
                $RegTransEst[$key][$key1]['skor_brdPertph'] = 0;
                $RegTransEst[$key][$key1]['skor_buahPerTPH'] = 0;
                $RegTransEst[$key][$key1]['totalSkor'] = 0;
            }
        }
        // dd($RegTransEst);
        $RegMTtransBln = array();
        foreach ($RegTransEst as $key => $value) if (!empty($value)) {
            $sum_bt = 0;
            $sum_rst = 0;
            $brdPertph = 0;
            $buahPerTPH = 0;
            $totalSkor = 0;
            $dataBLok = 0;
            foreach ($value as $key1 => $value1) {
                // dd($value3);
                $sum_bt += $value1['total_brd'];
                $sum_rst += $value1['total_buah'];
                $dataBLok += $value1['tph_sample'];
            }

            if ($dataBLok != 0) {
                $brdPertph = round($sum_bt / $dataBLok, 2);
            } else {
                $brdPertph = 0;
            }

            if ($dataBLok != 0) {
                $buahPerTPH = round($sum_rst / $dataBLok, 2);
            } else {
                $buahPerTPH = 0;
            }

            //menghitung skor butir
            $skor_brdPertph = 0;
            if ($brdPertph <= 3) {
                $skor_brdPertph = 10;
            } else if ($brdPertph >= 3 && $brdPertph <= 5) {
                $skor_brdPertph = 8;
            } else if ($brdPertph >= 5 && $brdPertph <= 7) {
                $skor_brdPertph = 6;
            } else if ($brdPertph >= 7 && $brdPertph <= 9) {
                $skor_brdPertph = 4;
            } else if ($brdPertph >= 9 && $brdPertph <= 11) {
                $skor_brdPertph = 2;
            } else if ($brdPertph >= 11) {
                $skor_brdPertph = 0;
            }
            //menghitung Skor Restant
            $skor_buahPerTPH = 0;
            if ($buahPerTPH <= 0.0) {
                $skor_buahPerTPH = 10;
            } else if ($buahPerTPH >= 0.0 && $buahPerTPH <= 0.5) {
                $skor_buahPerTPH = 8;
            } else if ($buahPerTPH >= 0.5 && $buahPerTPH <= 1) {
                $skor_buahPerTPH = 6;
            } else if ($buahPerTPH >= 1.0 && $buahPerTPH <= 1.5) {
                $skor_buahPerTPH = 4;
            } else if ($buahPerTPH >= 1.5 && $buahPerTPH <= 2.0) {
                $skor_buahPerTPH = 2;
            } else if ($buahPerTPH >= 2.0 && $buahPerTPH <= 2.5) {
                $skor_buahPerTPH = 0;
            } else if ($buahPerTPH >= 2.5 && $buahPerTPH <= 3.0) {
                $skor_buahPerTPH = 2;
            } else if ($buahPerTPH >= 3.0 && $buahPerTPH <= 3.5) {
                $skor_buahPerTPH = 4;
            } else if ($buahPerTPH >= 3.5 && $buahPerTPH <= 4.0) {
                $skor_buahPerTPH = 6;
            } else if ($buahPerTPH >= 4.0) {
                $skor_buahPerTPH = 8;
            }

            $totalSkor = $skor_buahPerTPH + $skor_brdPertph;


            $RegMTtransBln[$key]['tph_sample'] = $dataBLok;
            $RegMTtransBln[$key]['total_brd'] = $sum_bt;
            $RegMTtransBln[$key]['total_brd/TPH'] = $brdPertph;
            $RegMTtransBln[$key]['total_buah'] = $sum_rst;
            $RegMTtransBln[$key]['total_buahPerTPH'] = $buahPerTPH;
            $RegMTtransBln[$key]['skor_brdPertph'] = $skor_brdPertph;
            $RegMTtransBln[$key]['skor_buahPerTPH'] = $skor_buahPerTPH;
            $RegMTtransBln[$key]['totalSkor'] = $totalSkor;
        } else {
            $RegMTtransBln[$key]['tph_sample'] = 0;
            $RegMTtransBln[$key]['total_brd'] = 0;
            $RegMTtransBln[$key]['total_brd/TPH'] = 0;
            $RegMTtransBln[$key]['total_buah'] = 0;
            $RegMTtransBln[$key]['total_buahPerTPH'] = 0;
            $RegMTtransBln[$key]['skor_brdPertph'] = 0;
            $RegMTtransBln[$key]['skor_buahPerTPH'] = 0;
            $RegMTtransBln[$key]['totalSkor'] = 0;
        }
        // dd($RegMTtransBln);

        // dd($mtTransTahun);
        //perhitungan mt trans Reg 1 
        $RegMTtransTHn = array();
        $dataBLok = 0;
        $sum_bt = 0;
        $sum_rst = 0;
        $brdPertph = 0;
        $buahPerTPH = 0;
        foreach ($mtTransTahun as $key => $value) {
            $sum_bt += $value['total_brd'];
            $sum_rst += $value['total_buah'];
            $dataBLok += $value['tph_sample'];
        }
        if ($dataBLok != 0) {
            $brdPertph = round($sum_bt / $dataBLok, 2);
        } else {
            $brdPertph = 0;
        }

        if ($dataBLok != 0) {
            $buahPerTPH = round($sum_rst / $dataBLok, 2);
        } else {
            $buahPerTPH = 0;
        }

        //menghitung skor butir
        $skor_brdPertph = 0;
        if ($brdPertph <= 3) {
            $skor_brdPertph = 10;
        } else if ($brdPertph >= 3 && $brdPertph <= 5) {
            $skor_brdPertph = 8;
        } else if ($brdPertph >= 5 && $brdPertph <= 7) {
            $skor_brdPertph = 6;
        } else if ($brdPertph >= 7 && $brdPertph <= 9) {
            $skor_brdPertph = 4;
        } else if ($brdPertph >= 9 && $brdPertph <= 11) {
            $skor_brdPertph = 2;
        } else if ($brdPertph >= 11) {
            $skor_brdPertph = 0;
        }
        //menghitung Skor Restant
        $skor_buahPerTPH = 0;
        if ($buahPerTPH <= 0.0) {
            $skor_buahPerTPH = 10;
        } else if ($buahPerTPH >= 0.0 && $buahPerTPH <= 0.5) {
            $skor_buahPerTPH = 8;
        } else if ($buahPerTPH >= 0.5 && $buahPerTPH <= 1) {
            $skor_buahPerTPH = 6;
        } else if ($buahPerTPH >= 1.0 && $buahPerTPH <= 1.5) {
            $skor_buahPerTPH = 4;
        } else if ($buahPerTPH >= 1.5 && $buahPerTPH <= 2.0) {
            $skor_buahPerTPH = 2;
        } else if ($buahPerTPH >= 2.0 && $buahPerTPH <= 2.5) {
            $skor_buahPerTPH = 0;
        } else if ($buahPerTPH >= 2.5 && $buahPerTPH <= 3.0) {
            $skor_buahPerTPH = 2;
        } else if ($buahPerTPH >= 3.0 && $buahPerTPH <= 3.5) {
            $skor_buahPerTPH = 4;
        } else if ($buahPerTPH >= 3.5 && $buahPerTPH <= 4.0) {
            $skor_buahPerTPH = 6;
        } else if ($buahPerTPH >= 4.0) {
            $skor_buahPerTPH = 8;
        }

        $totalSkor = $skor_buahPerTPH + $skor_brdPertph;


        $RegMTtransTHn['I']['tph_sample'] = $dataBLok;
        $RegMTtransTHn['I']['total_brd'] = $sum_bt;
        $RegMTtransTHn['I']['total_brd/TPH'] = $brdPertph;
        $RegMTtransTHn['I']['total_buah'] = $sum_rst;
        $RegMTtransTHn['I']['total_buahPerTPH'] = $buahPerTPH;
        $RegMTtransTHn['I']['skor_brdPertph'] = $skor_brdPertph;
        $RegMTtransTHn['I']['skor_buahPerTPH'] = $skor_buahPerTPH;
        $RegMTtransTHn['I']['totalSkor'] = $totalSkor;



        // dd($RegMTtransTHn);

        //end perhitungna mt trans

        //perhitungan untuk mutu buah
        $defperbulanMTbh = array();
        foreach ($bulan as $key => $value) {
            foreach ($queryEste as $key2 => $value2) {
                foreach ($queryAfd as $key3 => $value3) {
                    if ($value2['est'] == $value3['est']) {
                        $defperbulanMTbh[$value][$value2['est']][$value3['nama']] = 0;
                        // $defperbulanMTbh[$value][$value2['est']][$value] = 0;
                    }
                }
            }
        }

        $dataMtBuahWil = array();
        foreach ($queryMTbuah as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $month = date('F', strtotime($value3['datetime']));
                    if (!array_key_exists($month, $dataMtBuahWil)) {
                        $dataMtBuahWil[$month] = array();
                    }
                    if (!array_key_exists($key, $dataMtBuahWil[$month])) {
                        $dataMtBuahWil[$month][$key] = array();
                    }
                    if (!array_key_exists($key2, $dataMtBuahWil[$month][$key])) {
                        $dataMtBuahWil[$month][$key][$key2] = array();
                    }
                    $dataMtBuahWil[$month][$key][$key2][$key3] = $value3;
                }
            }
        }
        // dd($dataMtBuahWil);

        // dd($dataPerBulan)
        //menimpa nilai default di atas dengan dataperbulan mutu buah yang ada isinya sehingga yang kosong menjadi 0
        foreach ($dataMtBuahWil as $key2 => $value2) {
            foreach ($value2 as $key3 => $value3) {
                foreach ($value3 as $key4 => $value4) {
                    // foreach ($defperbulanMTbh[$key2][$key3][$key4] as $key => $value) {
                    $defperbulanMTbh[$key2][$key3][$key4] = $value4;
                }
            }
        }

        // dd($defperbulanMTbh);
        //membuat data mutu transpot berdasarakan wilayah 1,2,3
        $mtBuahwil = array();
        foreach ($queryEste as $key => $value) {
            foreach ($defperbulanMTbh as $key2 => $value2) {
                // dd($value2);
                foreach ($value2 as $key3 => $value3) {
                    if ($value['est'] == $key3) {
                        $mtBuahwil[$value['wil']][$key2][$key3] = $value3;
                    }
                }
            }
        }
        // dd($mtBuahwil);
        //menghitung mutu buah perbulan > afdeling
        $mtBuahAFDbln = array();
        foreach ($mtBuahwil as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        if (!empty($value3)) {
                            $dataBLok = 0;
                            $sum_bmt = 0;
                            $sum_bmk = 0;
                            $sum_over = 0;
                            $sum_Samplejjg = 0;
                            $PerMth = 0;
                            $PerMsk = 0;
                            $PerOver = 0;
                            $sum_abnor = 0;
                            $sum_kosongjjg = 0;
                            $Perkosongjjg = 0;
                            $sum_vcut = 0;
                            $PerVcut = 0;
                            $PerAbr = 0;
                            $sum_kr = 0;
                            $total_kr = 0;
                            $per_kr = 0;
                            $totalSkor = 0;

                            $combination_counts = array();
                            foreach ($value3 as $key4 => $value4) {
                                $combination = $value4['blok'] . ' ' . $value4['estate'] . ' ' . $value4['afdeling'] . ' ' . $value4['tph_baris'];
                                if (!isset($combination_counts[$combination])) {
                                    $combination_counts[$combination] = 0;
                                }
                                $combination_counts[$combination]++;

                                $sum_bmt += $value4['bmt'];
                                $sum_bmk += $value4['bmk'];
                                $sum_over += $value4['overripe'];
                                $sum_kosongjjg += $value4['empty'];
                                $sum_vcut += $value4['vcut'];
                                $sum_kr += $value4['alas_br'];


                                $sum_Samplejjg += $value4['jumlah_jjg'];
                                $sum_abnor += $value4['abnormal'];
                            }
                            $dataBLok = count($combination_counts);

                            if ($sum_kr != 0) {
                                $total_kr = round($dataBLok / $sum_kr, 2);
                            } else {
                                $total_kr = 0;
                            }

                            $per_kr = round($total_kr * 100, 2);
                            $PerMth = round(($sum_bmt / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                            $PerMsk = round(($sum_bmk / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                            $PerOver = round(($sum_over / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                            $Perkosongjjg = round(($sum_kosongjjg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                            $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
                            $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);

                            // skoring buah mentah
                            $skor_PerMth = 0;
                            if ($PerMth <= 1.0) {
                                $skor_PerMth = 10;
                            } else if ($PerMth >= 1.0 && $PerMth <= 2.0) {
                                $skor_PerMth = 8;
                            } else if ($PerMth >= 2.0 && $PerMth <= 3.0) {
                                $skor_PerMth = 6;
                            } else if ($PerMth >= 3.0 && $PerMth <= 4.0) {
                                $skor_PerMth = 4;
                            } else if ($PerMth >= 4.0 && $PerMth <= 5.0) {
                                $skor_PerMth = 2;
                            } else if ($PerMth >= 5.0) {
                                $skor_PerMth = 0;
                            }

                            // skoring buah masak
                            $skor_PerMsk = 0;
                            if ($PerMsk <= 75.0) {
                                $skor_PerMsk = 0;
                            } else if ($PerMsk >= 75.0 && $PerMsk <= 80.0) {
                                $skor_PerMsk = 1;
                            } else if ($PerMsk >= 80.0 && $PerMsk <= 85.0) {
                                $skor_PerMsk = 2;
                            } else if ($PerMsk >= 85.0 && $PerMsk <= 90.0) {
                                $skor_PerMsk = 3;
                            } else if ($PerMsk >= 90.0 && $PerMsk <= 95.0) {
                                $skor_PerMsk = 4;
                            } else if ($PerMsk >= 95.0) {
                                $skor_PerMsk = 5;
                            }

                            // skoring buah over
                            $skor_PerOver = 0;
                            if ($PerOver <= 2.0) {
                                $skor_PerOver = 5;
                            } else if ($PerOver >= 2.0 && $PerOver <= 4.0) {
                                $skor_PerOver = 4;
                            } else if ($PerOver >= 4.0 && $PerOver <= 6.0) {
                                $skor_PerOver = 3;
                            } else if ($PerOver >= 6.0 && $PerOver <= 8.0) {
                                $skor_PerOver = 2;
                            } else if ($PerOver >= 8.0 && $PerOver <= 10.0) {
                                $skor_PerOver = 1;
                            } else if ($PerOver >= 10.0) {
                                $skor_PerOver = 0;
                            }


                            //skor janjang kosong
                            $skor_Perkosongjjg = 0;
                            if ($Perkosongjjg <= 1.0) {
                                $skor_Perkosongjjg = 5;
                            } else if ($Perkosongjjg >= 1.0 && $Perkosongjjg <= 2.0) {
                                $skor_Perkosongjjg = 4;
                            } else if ($Perkosongjjg >= 2.0 && $Perkosongjjg <= 3.0) {
                                $skor_Perkosongjjg = 3;
                            } else if ($Perkosongjjg >= 3.0 && $Perkosongjjg <= 4.0) {
                                $skor_Perkosongjjg = 2;
                            } else if ($Perkosongjjg >= 4.0 && $Perkosongjjg <= 5.0) {
                                $skor_Perkosongjjg = 1;
                            } else if ($Perkosongjjg >= 5.0) {
                                $skor_Perkosongjjg = 0;
                            }

                            //skore Vcut
                            $skor_PerVcut = 0;
                            if ($PerVcut <= 2.0) {
                                $skor_PerVcut = 5;
                            } else if ($PerVcut >= 2.0 && $PerVcut <= 4.0) {
                                $skor_PerVcut = 4;
                            } else if ($PerVcut >= 4.0 && $PerVcut <= 6.0) {
                                $skor_PerVcut = 3;
                            } else if ($PerVcut >= 6.0 && $PerVcut <= 8.0) {
                                $skor_PerVcut = 2;
                            } else if ($PerVcut >= 8.0 && $PerVcut <= 10.0) {
                                $skor_PerVcut = 1;
                            } else if ($PerVcut >= 10.0) {
                                $skor_PerVcut = 0;
                            }

                            // blum di cek skornya di bawah
                            //skore PEnggunnan Brondolan
                            $skor_PerAbr = 0;
                            if ($PerAbr <= 75.0) {
                                $skor_PerAbr = 0;
                            } else if ($PerAbr >= 75.0 && $PerAbr <= 80.0) {
                                $skor_PerAbr = 1;
                            } else if ($PerAbr >= 80.0 && $PerAbr <= 85.0) {
                                $skor_PerAbr = 2;
                            } else if ($PerAbr >= 85.0 && $PerAbr <= 90.0) {
                                $skor_PerAbr = 3;
                            } else if ($PerAbr >= 90.0 && $PerAbr <= 95.0) {
                                $skor_PerAbr = 4;
                            } else if ($PerAbr >= 95.0) {
                                $skor_PerAbr = 5;
                            }

                            $skor_per_kr = 0;
                            if ($per_kr <= 60) {
                                $skor_per_kr = 0;
                            } else if ($per_kr >= 60 && $per_kr <= 70) {
                                $skor_per_kr = 1;
                            } else if ($per_kr >= 70 && $per_kr <= 80) {
                                $skor_per_kr = 2;
                            } else if ($per_kr >= 80 && $per_kr <= 90) {
                                $skor_per_kr = 3;
                            } else if ($per_kr >= 90 && $per_kr <= 100) {
                                $skor_per_kr = 4;
                            } else if ($per_kr >= 100) {
                                $skor_per_kr = 5;
                            }

                            $totalSkor =  $skor_PerMth + $skor_PerMsk + $skor_PerOver +  $skor_Perkosongjjg + $skor_PerVcut + $skor_PerAbr +  $skor_per_kr;

                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['tph_baris_blok'] = $dataBLok;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['sampleJJG_total'] = $sum_Samplejjg;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_mentah'] = $sum_bmt;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_perMentah'] = $PerMth;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_masak'] = $sum_bmk;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_perMasak'] = $PerMsk;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_over'] = $sum_over;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_perOver'] = $PerOver;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_abnormal'] = $sum_abnor;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_jjgKosong'] = $sum_kosongjjg;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_perKosongjjg'] = $Perkosongjjg;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_vcut'] = $sum_vcut;

                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['jum_kr'] = $sum_kr;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_kr'] = $total_kr;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['persen_kr'] = $per_kr;

                            // skoring
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['skor_mentah'] = $skor_PerMth;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['skor_masak'] = $skor_PerMsk;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['skor_over'] = $skor_PerOver;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['skor_jjgKosong'] = $skor_Perkosongjjg;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['skor_vcut'] = $skor_PerVcut;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['skor_abnormal'] = $skor_PerAbr;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['skor_kr'] = $skor_per_kr;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['TOTAL_SKOR'] = $totalSkor;
                        } else {
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['tph_baris_blok'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['sampleJJG_total'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_mentah'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_perMentah'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_masak'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_perMasak'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_over'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_perOver'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_abnormal'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_jjgKosong'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_perKosongjjg'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_vcut'] = 0;

                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['jum_kr'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['total_kr'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['persen_kr'] = 0;

                            // skoring
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['skor_mentah'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['skor_masak'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['skor_over'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['skor_jjgKosong'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['skor_vcut'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['skor_abnormal'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['skor_kr'] = 0;
                            $mtBuahAFDbln[$key][$key1][$key2][$key3]['TOTAL_SKOR'] = 0;
                        }
                    }
                }
            }
        }

        // dd($mtBuahAFDbln);
        //menghitung mt buah bulan > afd berdasrakan rergional 1
        $RegmtBuahAFDbln = array();
        foreach ($defperbulanMTbh as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2)      if (!empty($value2)) {
                    $dataBLok = 0;
                    $sum_bmt = 0;
                    $sum_bmk = 0;
                    $sum_over = 0;
                    $sum_Samplejjg = 0;
                    $PerMth = 0;
                    $PerMsk = 0;
                    $PerOver = 0;
                    $sum_abnor = 0;
                    $sum_kosongjjg = 0;
                    $Perkosongjjg = 0;
                    $sum_vcut = 0;
                    $PerVcut = 0;
                    $PerAbr = 0;
                    $sum_kr = 0;
                    $total_kr = 0;
                    $per_kr = 0;
                    $totalSkor = 0;
                    $combination_counts = array();
                    foreach ($value2 as $key3 => $value3) {
                        $combination = $value4['blok'] . ' ' . $value4['estate'] . ' ' . $value4['afdeling'] . ' ' . $value4['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $combination_counts[$combination]++;

                        $sum_bmt += $value4['bmt'];
                        $sum_bmk += $value4['bmk'];
                        $sum_over += $value4['overripe'];
                        $sum_kosongjjg += $value4['empty'];
                        $sum_vcut += $value4['vcut'];
                        $sum_kr += $value4['alas_br'];

                        $sum_Samplejjg += $value4['jumlah_jjg'];
                        $sum_abnor += $value4['abnormal'];
                    }
                    $dataBLok = count($combination_counts);
                    $RegmtBuahAFDbln[$key][$key1][$key2]['blok'] = $dataBLok;
                } else {
                    $RegmtBuahAFDbln[$key][$key1][$key2]['blok'] = 0;
                }
            }
        }
        // dd($RegmtBuahAFDbln);
        //hitung mutu buah perbulan > est 
        $mtBuahESTbln = array();
        foreach ($mtBuahAFDbln as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) if (!empty($value2)) {
                    $dataBLok = 0;
                    $sum_bmt = 0;
                    $sum_bmk = 0;
                    $sum_over = 0;
                    $sum_Samplejjg = 0;
                    $PerMth = 0;
                    $PerMsk = 0;
                    $PerOver = 0;
                    $sum_abnor = 0;
                    $sum_kosongjjg = 0;
                    $Perkosongjjg = 0;
                    $sum_vcut = 0;
                    $PerVcut = 0;
                    $PerAbr = 0;
                    $sum_kr = 0;
                    $total_kr = 0;
                    $per_kr = 0;
                    $totalSkor = 0;
                    $perbagi = 0;
                    foreach ($value2 as $key3 => $value3) {
                        $dataBLok += $value3['tph_baris_blok'];
                        $sum_bmt += $value3['total_mentah'];
                        $sum_bmk += $value3['total_masak'];
                        $sum_over += $value3['total_over'];
                        $sum_kosongjjg += $value3['total_jjgKosong'];
                        $sum_vcut += $value3['total_vcut'];
                        $sum_kr += $value3['jum_kr'];

                        $sum_Samplejjg += $value3['sampleJJG_total'];
                        $sum_abnor += $value3['total_abnormal'];
                    }

                    if ($sum_kr != 0) {
                        $total_kr = round($dataBLok / $sum_kr, 2);
                    } else {
                        $total_kr = 0;
                    }

                    $per_kr = round($total_kr * 100, 2);

                    $perbagi = $sum_Samplejjg - $sum_abnor;
                    if ($perbagi != 0) {
                        $PerMth = round($sum_bmt / $perbagi * 100, 2);
                        $PerMsk = round($sum_bmk / $perbagi * 100, 2);
                        $PerOver = round($sum_over / $perbagi * 100, 2);
                        $Perkosongjjg = round($sum_kosongjjg / $perbagi * 100, 2);
                    } else {
                        $PerMth = 0;
                        $PerMsk = 0;
                        $PerOver = 0;
                        $Perkosongjjg = 0;
                    }

                    if ($sum_Samplejjg != 0) {
                        $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
                        $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);
                    } else {
                        $PerVcut = 0;
                        $PerAbr = 0;
                    }


                    // skoring buah mentah
                    $skor_PerMth = 0;
                    if ($PerMth <= 1.0) {
                        $skor_PerMth = 10;
                    } else if ($PerMth >= 1.0 && $PerMth <= 2.0) {
                        $skor_PerMth = 8;
                    } else if ($PerMth >= 2.0 && $PerMth <= 3.0) {
                        $skor_PerMth = 6;
                    } else if ($PerMth >= 3.0 && $PerMth <= 4.0) {
                        $skor_PerMth = 4;
                    } else if ($PerMth >= 4.0 && $PerMth <= 5.0) {
                        $skor_PerMth = 2;
                    } else if ($PerMth >= 5.0) {
                        $skor_PerMth = 0;
                    }

                    // skoring buah masak
                    $skor_PerMsk = 0;
                    if ($PerMsk <= 75.0) {
                        $skor_PerMsk = 0;
                    } else if ($PerMsk >= 75.0 && $PerMsk <= 80.0) {
                        $skor_PerMsk = 1;
                    } else if ($PerMsk >= 80.0 && $PerMsk <= 85.0) {
                        $skor_PerMsk = 2;
                    } else if ($PerMsk >= 85.0 && $PerMsk <= 90.0) {
                        $skor_PerMsk = 3;
                    } else if ($PerMsk >= 90.0 && $PerMsk <= 95.0) {
                        $skor_PerMsk = 4;
                    } else if ($PerMsk >= 95.0) {
                        $skor_PerMsk = 5;
                    }

                    // skoring buah over
                    $skor_PerOver = 0;
                    if ($PerOver <= 2.0) {
                        $skor_PerOver = 5;
                    } else if ($PerOver >= 2.0 && $PerOver <= 4.0) {
                        $skor_PerOver = 4;
                    } else if ($PerOver >= 4.0 && $PerOver <= 6.0) {
                        $skor_PerOver = 3;
                    } else if ($PerOver >= 6.0 && $PerOver <= 8.0) {
                        $skor_PerOver = 2;
                    } else if ($PerOver >= 8.0 && $PerOver <= 10.0) {
                        $skor_PerOver = 1;
                    } else if ($PerOver >= 10.0) {
                        $skor_PerOver = 0;
                    }


                    //skor janjang kosong
                    $skor_Perkosongjjg = 0;
                    if ($Perkosongjjg <= 1.0) {
                        $skor_Perkosongjjg = 5;
                    } else if ($Perkosongjjg >= 1.0 && $Perkosongjjg <= 2.0) {
                        $skor_Perkosongjjg = 4;
                    } else if ($Perkosongjjg >= 2.0 && $Perkosongjjg <= 3.0) {
                        $skor_Perkosongjjg = 3;
                    } else if ($Perkosongjjg >= 3.0 && $Perkosongjjg <= 4.0) {
                        $skor_Perkosongjjg = 2;
                    } else if ($Perkosongjjg >= 4.0 && $Perkosongjjg <= 5.0) {
                        $skor_Perkosongjjg = 1;
                    } else if ($Perkosongjjg >= 5.0) {
                        $skor_Perkosongjjg = 0;
                    }

                    //skore Vcut
                    $skor_PerVcut = 0;
                    if ($PerVcut <= 2.0) {
                        $skor_PerVcut = 5;
                    } else if ($PerVcut >= 2.0 && $PerVcut <= 4.0) {
                        $skor_PerVcut = 4;
                    } else if ($PerVcut >= 4.0 && $PerVcut <= 6.0) {
                        $skor_PerVcut = 3;
                    } else if ($PerVcut >= 6.0 && $PerVcut <= 8.0) {
                        $skor_PerVcut = 2;
                    } else if ($PerVcut >= 8.0 && $PerVcut <= 10.0) {
                        $skor_PerVcut = 1;
                    } else if ($PerVcut >= 10.0) {
                        $skor_PerVcut = 0;
                    }

                    // blum di cek skornya di bawah
                    //skore PEnggunnan Brondolan
                    $skor_PerAbr = 0;
                    if ($PerAbr <= 75.0) {
                        $skor_PerAbr = 0;
                    } else if ($PerAbr >= 75.0 && $PerAbr <= 80.0) {
                        $skor_PerAbr = 1;
                    } else if ($PerAbr >= 80.0 && $PerAbr <= 85.0) {
                        $skor_PerAbr = 2;
                    } else if ($PerAbr >= 85.0 && $PerAbr <= 90.0) {
                        $skor_PerAbr = 3;
                    } else if ($PerAbr >= 90.0 && $PerAbr <= 95.0) {
                        $skor_PerAbr = 4;
                    } else if ($PerAbr >= 95.0) {
                        $skor_PerAbr = 5;
                    }

                    $skor_per_kr = 0;
                    if ($per_kr <= 60) {
                        $skor_per_kr = 0;
                    } else if ($per_kr >= 60 && $per_kr <= 70) {
                        $skor_per_kr = 1;
                    } else if ($per_kr >= 70 && $per_kr <= 80) {
                        $skor_per_kr = 2;
                    } else if ($per_kr >= 80 && $per_kr <= 90) {
                        $skor_per_kr = 3;
                    } else if ($per_kr >= 90 && $per_kr <= 100) {
                        $skor_per_kr = 4;
                    } else if ($per_kr >= 100) {
                        $skor_per_kr = 5;
                    }

                    $totalSkor =  $skor_PerMth + $skor_PerMsk + $skor_PerOver +  $skor_Perkosongjjg + $skor_PerVcut + $skor_PerAbr +  $skor_per_kr;

                    $mtBuahESTbln[$key][$key1][$key2]['tph_baris_blok'] = $dataBLok;
                    $mtBuahESTbln[$key][$key1][$key2]['sampleJJG_total'] = $sum_Samplejjg;
                    $mtBuahESTbln[$key][$key1][$key2]['total_mentah'] = $sum_bmt;
                    $mtBuahESTbln[$key][$key1][$key2]['total_perMentah'] = $PerMth;
                    $mtBuahESTbln[$key][$key1][$key2]['total_masak'] = $sum_bmk;
                    $mtBuahESTbln[$key][$key1][$key2]['total_perMasak'] = $PerMsk;
                    $mtBuahESTbln[$key][$key1][$key2]['total_over'] = $sum_over;
                    $mtBuahESTbln[$key][$key1][$key2]['total_perOver'] = $PerOver;
                    $mtBuahESTbln[$key][$key1][$key2]['total_abnormal'] = $sum_abnor;
                    $mtBuahESTbln[$key][$key1][$key2]['total_jjgKosong'] = $sum_kosongjjg;
                    $mtBuahESTbln[$key][$key1][$key2]['total_perKosongjjg'] = $Perkosongjjg;
                    $mtBuahESTbln[$key][$key1][$key2]['total_vcut'] = $sum_vcut;

                    $mtBuahESTbln[$key][$key1][$key2]['jum_kr'] = $sum_kr;
                    $mtBuahESTbln[$key][$key1][$key2]['total_kr'] = $total_kr;
                    $mtBuahESTbln[$key][$key1][$key2]['persen_kr'] = $per_kr;

                    // skoring
                    $mtBuahESTbln[$key][$key1][$key2]['skor_mentah'] = $skor_PerMth;
                    $mtBuahESTbln[$key][$key1][$key2]['skor_masak'] = $skor_PerMsk;
                    $mtBuahESTbln[$key][$key1][$key2]['skor_over'] = $skor_PerOver;
                    $mtBuahESTbln[$key][$key1][$key2]['skor_jjgKosong'] = $skor_Perkosongjjg;
                    $mtBuahESTbln[$key][$key1][$key2]['skor_vcut'] = $skor_PerVcut;
                    $mtBuahESTbln[$key][$key1][$key2]['skor_abnormal'] = $skor_PerAbr;
                    $mtBuahESTbln[$key][$key1][$key2]['skor_kr'] = $skor_per_kr;
                    $mtBuahESTbln[$key][$key1][$key2]['TOTAL_SKOR'] = $totalSkor;
                } else {
                    $mtBuahESTbln[$key][$key1][$key2]['tph_baris_blok'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['sampleJJG_total'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['total_mentah'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['total_perMentah'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['total_masak'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['total_perMasak'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['total_over'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['total_perOver'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['total_abnormal'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['total_jjgKosong'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['total_perKosongjjg'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['total_vcut'] = 0;

                    $mtBuahESTbln[$key][$key1][$key2]['jum_kr'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['total_kr'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['persen_kr'] = 0;

                    // skoring
                    $mtBuahESTbln[$key][$key1][$key2]['skor_mentah'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['skor_masak'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['skor_over'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['skor_jjgKosong'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['skor_vcut'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['skor_abnormal'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['skor_kr'] = 0;
                    $mtBuahESTbln[$key][$key1][$key2]['TOTAL_SKOR'] = 0;
                }
            }
        }
        // dd($mtBuahESTbln);
        //perhitungan mutu buah untuk perbulan semua estate
        $mtBuahAllEst = array();
        foreach ($mtBuahESTbln as $key => $value) {
            foreach ($value as $key1 => $value1) if (!empty($value1)) {
                $dataBLok = 0;
                $sum_bmt = 0;
                $sum_bmk = 0;
                $sum_over = 0;
                $sum_Samplejjg = 0;
                $PerMth = 0;
                $PerMsk = 0;
                $PerOver = 0;
                $sum_abnor = 0;
                $sum_kosongjjg = 0;
                $Perkosongjjg = 0;
                $sum_vcut = 0;
                $PerVcut = 0;
                $PerAbr = 0;
                $sum_kr = 0;
                $total_kr = 0;
                $per_kr = 0;
                $totalSkor = 0;
                $perbagi = 0;
                foreach ($value1 as $key2 => $value2) {
                    // dd($value2);
                    $dataBLok += $value2['tph_baris_blok'];
                    $sum_bmt += $value2['total_mentah'];
                    $sum_bmk += $value2['total_masak'];
                    $sum_over += $value2['total_over'];
                    $sum_kosongjjg += $value2['total_jjgKosong'];
                    $sum_vcut += $value2['total_vcut'];
                    $sum_kr += $value2['jum_kr'];

                    $sum_Samplejjg += $value2['sampleJJG_total'];
                    $sum_abnor += $value2['total_abnormal'];
                }


                if ($sum_kr != 0) {
                    $total_kr = round($dataBLok / $sum_kr, 2);
                } else {
                    $total_kr = 0;
                }

                $per_kr = round($total_kr * 100, 2);

                $perbagi = $sum_Samplejjg - $sum_abnor;
                if ($perbagi != 0) {
                    $PerMth = round($sum_bmt / $perbagi * 100, 2);
                    $PerMsk = round($sum_bmk / $perbagi * 100, 2);
                    $PerOver = round($sum_over / $perbagi * 100, 2);
                    $Perkosongjjg = round($sum_kosongjjg / $perbagi * 100, 2);
                } else {
                    $PerMth = 0;
                    $PerMsk = 0;
                    $PerOver = 0;
                    $Perkosongjjg = 0;
                }

                if ($sum_Samplejjg != 0) {
                    $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
                    $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);
                } else {
                    $PerVcut = 0;
                    $PerAbr = 0;
                }


                // skoring buah mentah
                $skor_PerMth = 0;
                if ($PerMth <= 1.0) {
                    $skor_PerMth = 10;
                } else if ($PerMth >= 1.0 && $PerMth <= 2.0) {
                    $skor_PerMth = 8;
                } else if ($PerMth >= 2.0 && $PerMth <= 3.0) {
                    $skor_PerMth = 6;
                } else if ($PerMth >= 3.0 && $PerMth <= 4.0) {
                    $skor_PerMth = 4;
                } else if ($PerMth >= 4.0 && $PerMth <= 5.0) {
                    $skor_PerMth = 2;
                } else if ($PerMth >= 5.0) {
                    $skor_PerMth = 0;
                }

                // skoring buah masak
                $skor_PerMsk = 0;
                if ($PerMsk <= 75.0) {
                    $skor_PerMsk = 0;
                } else if ($PerMsk >= 75.0 && $PerMsk <= 80.0) {
                    $skor_PerMsk = 1;
                } else if ($PerMsk >= 80.0 && $PerMsk <= 85.0) {
                    $skor_PerMsk = 2;
                } else if ($PerMsk >= 85.0 && $PerMsk <= 90.0) {
                    $skor_PerMsk = 3;
                } else if ($PerMsk >= 90.0 && $PerMsk <= 95.0) {
                    $skor_PerMsk = 4;
                } else if ($PerMsk >= 95.0) {
                    $skor_PerMsk = 5;
                }

                // skoring buah over
                $skor_PerOver = 0;
                if ($PerOver <= 2.0) {
                    $skor_PerOver = 5;
                } else if ($PerOver >= 2.0 && $PerOver <= 4.0) {
                    $skor_PerOver = 4;
                } else if ($PerOver >= 4.0 && $PerOver <= 6.0) {
                    $skor_PerOver = 3;
                } else if ($PerOver >= 6.0 && $PerOver <= 8.0) {
                    $skor_PerOver = 2;
                } else if ($PerOver >= 8.0 && $PerOver <= 10.0) {
                    $skor_PerOver = 1;
                } else if ($PerOver >= 10.0) {
                    $skor_PerOver = 0;
                }


                //skor janjang kosong
                $skor_Perkosongjjg = 0;
                if ($Perkosongjjg <= 1.0) {
                    $skor_Perkosongjjg = 5;
                } else if ($Perkosongjjg >= 1.0 && $Perkosongjjg <= 2.0) {
                    $skor_Perkosongjjg = 4;
                } else if ($Perkosongjjg >= 2.0 && $Perkosongjjg <= 3.0) {
                    $skor_Perkosongjjg = 3;
                } else if ($Perkosongjjg >= 3.0 && $Perkosongjjg <= 4.0) {
                    $skor_Perkosongjjg = 2;
                } else if ($Perkosongjjg >= 4.0 && $Perkosongjjg <= 5.0) {
                    $skor_Perkosongjjg = 1;
                } else if ($Perkosongjjg >= 5.0) {
                    $skor_Perkosongjjg = 0;
                }

                //skore Vcut
                $skor_PerVcut = 0;
                if ($PerVcut <= 2.0) {
                    $skor_PerVcut = 5;
                } else if ($PerVcut >= 2.0 && $PerVcut <= 4.0) {
                    $skor_PerVcut = 4;
                } else if ($PerVcut >= 4.0 && $PerVcut <= 6.0) {
                    $skor_PerVcut = 3;
                } else if ($PerVcut >= 6.0 && $PerVcut <= 8.0) {
                    $skor_PerVcut = 2;
                } else if ($PerVcut >= 8.0 && $PerVcut <= 10.0) {
                    $skor_PerVcut = 1;
                } else if ($PerVcut >= 10.0) {
                    $skor_PerVcut = 0;
                }

                // blum di cek skornya di bawah
                //skore PEnggunnan Brondolan
                $skor_PerAbr = 0;
                if ($PerAbr <= 75.0) {
                    $skor_PerAbr = 0;
                } else if ($PerAbr >= 75.0 && $PerAbr <= 80.0) {
                    $skor_PerAbr = 1;
                } else if ($PerAbr >= 80.0 && $PerAbr <= 85.0) {
                    $skor_PerAbr = 2;
                } else if ($PerAbr >= 85.0 && $PerAbr <= 90.0) {
                    $skor_PerAbr = 3;
                } else if ($PerAbr >= 90.0 && $PerAbr <= 95.0) {
                    $skor_PerAbr = 4;
                } else if ($PerAbr >= 95.0) {
                    $skor_PerAbr = 5;
                }

                $skor_per_kr = 0;
                if ($per_kr <= 60) {
                    $skor_per_kr = 0;
                } else if ($per_kr >= 60 && $per_kr <= 70) {
                    $skor_per_kr = 1;
                } else if ($per_kr >= 70 && $per_kr <= 80) {
                    $skor_per_kr = 2;
                } else if ($per_kr >= 80 && $per_kr <= 90) {
                    $skor_per_kr = 3;
                } else if ($per_kr >= 90 && $per_kr <= 100) {
                    $skor_per_kr = 4;
                } else if ($per_kr >= 100) {
                    $skor_per_kr = 5;
                }

                $totalSkor =  $skor_PerMth + $skor_PerMsk + $skor_PerOver +  $skor_Perkosongjjg + $skor_PerVcut + $skor_PerAbr +  $skor_per_kr;

                $mtBuahAllEst[$key][$key1]['tph_baris_blok'] = $dataBLok;
                $mtBuahAllEst[$key][$key1]['sampleJJG_total'] = $sum_Samplejjg;
                $mtBuahAllEst[$key][$key1]['total_mentah'] = $sum_bmt;
                $mtBuahAllEst[$key][$key1]['total_perMentah'] = $PerMth;
                $mtBuahAllEst[$key][$key1]['total_masak'] = $sum_bmk;
                $mtBuahAllEst[$key][$key1]['total_perMasak'] = $PerMsk;
                $mtBuahAllEst[$key][$key1]['total_over'] = $sum_over;
                $mtBuahAllEst[$key][$key1]['total_perOver'] = $PerOver;
                $mtBuahAllEst[$key][$key1]['total_abnormal'] = $sum_abnor;
                $mtBuahAllEst[$key][$key1]['total_jjgKosong'] = $sum_kosongjjg;
                $mtBuahAllEst[$key][$key1]['total_perKosongjjg'] = $Perkosongjjg;
                $mtBuahAllEst[$key][$key1]['total_vcut'] = $sum_vcut;

                $mtBuahAllEst[$key][$key1]['jum_kr'] = $sum_kr;
                $mtBuahAllEst[$key][$key1]['total_kr'] = $total_kr;
                $mtBuahAllEst[$key][$key1]['persen_kr'] = $per_kr;

                // skoring
                $mtBuahAllEst[$key][$key1]['skor_mentah'] = $skor_PerMth;
                $mtBuahAllEst[$key][$key1]['skor_masak'] = $skor_PerMsk;
                $mtBuahAllEst[$key][$key1]['skor_over'] = $skor_PerOver;
                $mtBuahAllEst[$key][$key1]['skor_jjgKosong'] = $skor_Perkosongjjg;
                $mtBuahAllEst[$key][$key1]['skor_vcut'] = $skor_PerVcut;
                $mtBuahAllEst[$key][$key1]['skor_abnormal'] = $skor_PerAbr;
                $mtBuahAllEst[$key][$key1]['skor_kr'] = $skor_per_kr;
                $mtBuahAllEst[$key][$key1]['TOTAL_SKOR'] = $totalSkor;
            } else {
                $mtBuahAllEst[$key][$key1]['tph_baris_blok'] = 0;
                $mtBuahAllEst[$key][$key1]['sampleJJG_total'] = 0;
                $mtBuahAllEst[$key][$key1]['total_mentah'] = 0;
                $mtBuahAllEst[$key][$key1]['total_perMentah'] = 0;
                $mtBuahAllEst[$key][$key1]['total_masak'] = 0;
                $mtBuahAllEst[$key][$key1]['total_perMasak'] = 0;
                $mtBuahAllEst[$key][$key1]['total_over'] = 0;
                $mtBuahAllEst[$key][$key1]['total_perOver'] = 0;
                $mtBuahAllEst[$key][$key1]['total_abnormal'] = 0;
                $mtBuahAllEst[$key][$key1]['total_jjgKosong'] = 0;
                $mtBuahAllEst[$key][$key1]['total_perKosongjjg'] = 0;
                $mtBuahAllEst[$key][$key1]['total_vcut'] = 0;

                $mtBuahAllEst[$key][$key1]['jum_kr'] = 0;
                $mtBuahAllEst[$key][$key1]['total_kr'] = 0;
                $mtBuahAllEst[$key][$key1]['persen_kr'] = 0;

                // skoring
                $mtBuahAllEst[$key][$key1]['skor_mentah'] = 0;
                $mtBuahAllEst[$key][$key1]['skor_masak'] = 0;
                $mtBuahAllEst[$key][$key1]['skor_over'] = 0;
                $mtBuahAllEst[$key][$key1]['skor_jjgKosong'] = 0;
                $mtBuahAllEst[$key][$key1]['skor_vcut'] = 0;
                $mtBuahAllEst[$key][$key1]['skor_abnormal'] = 0;
                $mtBuahAllEst[$key][$key1]['skor_kr'] = 0;
                $mtBuahAllEst[$key][$key1]['TOTAL_SKOR'] = 0;
            }
        }
        // dd($mtBuahAllEst);
        //perhitungan mutu buah unutuk wilayah pertahun
        $mtBuahTahunall = array();
        foreach ($mtBuahAllEst as $key => $value) if (!empty($value)) {
            $dataBLok = 0;
            $sum_bmt = 0;
            $sum_bmk = 0;
            $sum_over = 0;
            $sum_Samplejjg = 0;
            $PerMth = 0;
            $PerMsk = 0;
            $PerOver = 0;
            $sum_abnor = 0;
            $sum_kosongjjg = 0;
            $Perkosongjjg = 0;
            $sum_vcut = 0;
            $PerVcut = 0;
            $PerAbr = 0;
            $sum_kr = 0;
            $total_kr = 0;
            $per_kr = 0;
            $totalSkor = 0;
            $perbagi = 0;
            foreach ($value as $key1 => $value1) {
                $dataBLok += $value1['tph_baris_blok'];
                $sum_bmt += $value1['total_mentah'];
                $sum_bmk += $value1['total_masak'];
                $sum_over += $value1['total_over'];
                $sum_kosongjjg += $value1['total_jjgKosong'];
                $sum_vcut += $value1['total_vcut'];
                $sum_kr += $value1['jum_kr'];

                $sum_Samplejjg += $value1['sampleJJG_total'];
                $sum_abnor += $value1['total_abnormal'];
            }


            if ($sum_kr != 0) {
                $total_kr = round($dataBLok / $sum_kr, 2);
            } else {
                $total_kr = 0;
            }

            $per_kr = round($total_kr * 100, 2);

            $perbagi = $sum_Samplejjg - $sum_abnor;
            if ($perbagi != 0) {
                $PerMth = round($sum_bmt / $perbagi * 100, 2);
                $PerMsk = round($sum_bmk / $perbagi * 100, 2);
                $PerOver = round($sum_over / $perbagi * 100, 2);
                $Perkosongjjg = round($sum_kosongjjg / $perbagi * 100, 2);
            } else {
                $PerMth = 0;
                $PerMsk = 0;
                $PerOver = 0;
                $Perkosongjjg = 0;
            }

            if ($sum_Samplejjg != 0) {
                $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
                $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);
            } else {
                $PerVcut = 0;
                $PerAbr = 0;
            }


            // skoring buah mentah
            $skor_PerMth = 0;
            if ($PerMth <= 1.0) {
                $skor_PerMth = 10;
            } else if ($PerMth >= 1.0 && $PerMth <= 2.0) {
                $skor_PerMth = 8;
            } else if ($PerMth >= 2.0 && $PerMth <= 3.0) {
                $skor_PerMth = 6;
            } else if ($PerMth >= 3.0 && $PerMth <= 4.0) {
                $skor_PerMth = 4;
            } else if ($PerMth >= 4.0 && $PerMth <= 5.0) {
                $skor_PerMth = 2;
            } else if ($PerMth >= 5.0) {
                $skor_PerMth = 0;
            }

            // skoring buah masak
            $skor_PerMsk = 0;
            if ($PerMsk <= 75.0) {
                $skor_PerMsk = 0;
            } else if ($PerMsk >= 75.0 && $PerMsk <= 80.0) {
                $skor_PerMsk = 1;
            } else if ($PerMsk >= 80.0 && $PerMsk <= 85.0) {
                $skor_PerMsk = 2;
            } else if ($PerMsk >= 85.0 && $PerMsk <= 90.0) {
                $skor_PerMsk = 3;
            } else if ($PerMsk >= 90.0 && $PerMsk <= 95.0) {
                $skor_PerMsk = 4;
            } else if ($PerMsk >= 95.0) {
                $skor_PerMsk = 5;
            }

            // skoring buah over
            $skor_PerOver = 0;
            if ($PerOver <= 2.0) {
                $skor_PerOver = 5;
            } else if ($PerOver >= 2.0 && $PerOver <= 4.0) {
                $skor_PerOver = 4;
            } else if ($PerOver >= 4.0 && $PerOver <= 6.0) {
                $skor_PerOver = 3;
            } else if ($PerOver >= 6.0 && $PerOver <= 8.0) {
                $skor_PerOver = 2;
            } else if ($PerOver >= 8.0 && $PerOver <= 10.0) {
                $skor_PerOver = 1;
            } else if ($PerOver >= 10.0) {
                $skor_PerOver = 0;
            }


            //skor janjang kosong
            $skor_Perkosongjjg = 0;
            if ($Perkosongjjg <= 1.0) {
                $skor_Perkosongjjg = 5;
            } else if ($Perkosongjjg >= 1.0 && $Perkosongjjg <= 2.0) {
                $skor_Perkosongjjg = 4;
            } else if ($Perkosongjjg >= 2.0 && $Perkosongjjg <= 3.0) {
                $skor_Perkosongjjg = 3;
            } else if ($Perkosongjjg >= 3.0 && $Perkosongjjg <= 4.0) {
                $skor_Perkosongjjg = 2;
            } else if ($Perkosongjjg >= 4.0 && $Perkosongjjg <= 5.0) {
                $skor_Perkosongjjg = 1;
            } else if ($Perkosongjjg >= 5.0) {
                $skor_Perkosongjjg = 0;
            }

            //skore Vcut
            $skor_PerVcut = 0;
            if ($PerVcut <= 2.0) {
                $skor_PerVcut = 5;
            } else if ($PerVcut >= 2.0 && $PerVcut <= 4.0) {
                $skor_PerVcut = 4;
            } else if ($PerVcut >= 4.0 && $PerVcut <= 6.0) {
                $skor_PerVcut = 3;
            } else if ($PerVcut >= 6.0 && $PerVcut <= 8.0) {
                $skor_PerVcut = 2;
            } else if ($PerVcut >= 8.0 && $PerVcut <= 10.0) {
                $skor_PerVcut = 1;
            } else if ($PerVcut >= 10.0) {
                $skor_PerVcut = 0;
            }

            // blum di cek skornya di bawah
            //skore PEnggunnan Brondolan
            $skor_PerAbr = 0;
            if ($PerAbr <= 75.0) {
                $skor_PerAbr = 0;
            } else if ($PerAbr >= 75.0 && $PerAbr <= 80.0) {
                $skor_PerAbr = 1;
            } else if ($PerAbr >= 80.0 && $PerAbr <= 85.0) {
                $skor_PerAbr = 2;
            } else if ($PerAbr >= 85.0 && $PerAbr <= 90.0) {
                $skor_PerAbr = 3;
            } else if ($PerAbr >= 90.0 && $PerAbr <= 95.0) {
                $skor_PerAbr = 4;
            } else if ($PerAbr >= 95.0) {
                $skor_PerAbr = 5;
            }

            $skor_per_kr = 0;
            if ($per_kr <= 60) {
                $skor_per_kr = 0;
            } else if ($per_kr >= 60 && $per_kr <= 70) {
                $skor_per_kr = 1;
            } else if ($per_kr >= 70 && $per_kr <= 80) {
                $skor_per_kr = 2;
            } else if ($per_kr >= 80 && $per_kr <= 90) {
                $skor_per_kr = 3;
            } else if ($per_kr >= 90 && $per_kr <= 100) {
                $skor_per_kr = 4;
            } else if ($per_kr >= 100) {
                $skor_per_kr = 5;
            }

            $totalSkor =  $skor_PerMth + $skor_PerMsk + $skor_PerOver +  $skor_Perkosongjjg + $skor_PerVcut + $skor_PerAbr +  $skor_per_kr;

            $mtBuahTahunall[$key]['tph_blok'] = $dataBLok;
            $mtBuahTahunall[$key]['sampleJJG_total'] = $sum_Samplejjg;
            $mtBuahTahunall[$key]['total_mentah'] = $sum_bmt;
            $mtBuahTahunall[$key]['total_perMentah'] = $PerMth;
            $mtBuahTahunall[$key]['total_masak'] = $sum_bmk;
            $mtBuahTahunall[$key]['total_perMasak'] = $PerMsk;
            $mtBuahTahunall[$key]['total_over'] = $sum_over;
            $mtBuahTahunall[$key]['total_perOver'] = $PerOver;
            $mtBuahTahunall[$key]['total_abnormal'] = $sum_abnor;
            $mtBuahTahunall[$key]['total_jjgKosong'] = $sum_kosongjjg;
            $mtBuahTahunall[$key]['total_perKosongjjg'] = $Perkosongjjg;
            $mtBuahTahunall[$key]['total_vcut'] = $sum_vcut;

            $mtBuahTahunall[$key]['jum_kr'] = $sum_kr;
            $mtBuahTahunall[$key]['total_kr'] = $total_kr;
            $mtBuahTahunall[$key]['persen_kr'] = $per_kr;

            // skoring
            $mtBuahTahunall[$key]['skor_mentah'] = $skor_PerMth;
            $mtBuahTahunall[$key]['skor_masak'] = $skor_PerMsk;
            $mtBuahTahunall[$key]['skor_over'] = $skor_PerOver;
            $mtBuahTahunall[$key]['skor_jjgKosong'] = $skor_Perkosongjjg;
            $mtBuahTahunall[$key]['skor_vcut'] = $skor_PerVcut;
            $mtBuahTahunall[$key]['skor_abnormal'] = $skor_PerAbr;
            $mtBuahTahunall[$key]['skor_kr'] = $skor_per_kr;
            $mtBuahTahunall[$key]['TOTAL_SKOR'] = $totalSkor;
        } else {
            $mtBuahTahunall[$key]['tph_blok'] = 0;
            $mtBuahTahunall[$key]['sampleJJG_total'] = 0;
            $mtBuahTahunall[$key]['total_mentah'] = 0;
            $mtBuahTahunall[$key]['total_perMentah'] = 0;
            $mtBuahTahunall[$key]['total_masak'] = 0;
            $mtBuahTahunall[$key]['total_perMasak'] = 0;
            $mtBuahTahunall[$key]['total_over'] = 0;
            $mtBuahTahunall[$key]['total_perOver'] = 0;
            $mtBuahTahunall[$key]['total_abnormal'] = 0;
            $mtBuahTahunall[$key]['total_jjgKosong'] = 0;
            $mtBuahTahunall[$key]['total_perKosongjjg'] = 0;
            $mtBuahTahunall[$key]['total_vcut'] = 0;

            $mtBuahTahunall[$key]['jum_kr'] = 0;
            $mtBuahTahunall[$key]['total_kr'] = 0;
            $mtBuahTahunall[$key]['persen_kr'] = 0;

            // skoring
            $mtBuahTahunall[$key]['skor_mentah'] = 0;
            $mtBuahTahunall[$key]['skor_masak'] = 0;
            $mtBuahTahunall[$key]['skor_over'] = 0;
            $mtBuahTahunall[$key]['skor_jjgKosong'] = 0;
            $mtBuahTahunall[$key]['skor_vcut'] = 0;
            $mtBuahTahunall[$key]['skor_abnormal'] = 0;
            $mtBuahTahunall[$key]['skor_kr'] = 0;
            $mtBuahTahunall[$key]['TOTAL_SKOR'] = 0;
        }
        // dd($mtBuahAllEst);
        //mutu buah regional perbulan]
        $RegMtBhAfdBln = array();
        foreach ($defperbulanMTbh as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2)  if (!empty($value2)) {
                    $dataBLok = 0;
                    $sum_bmt = 0;
                    $sum_bmk = 0;
                    $sum_over = 0;
                    $sum_Samplejjg = 0;
                    $PerMth = 0;
                    $PerMsk = 0;
                    $PerOver = 0;
                    $sum_abnor = 0;
                    $sum_kosongjjg = 0;
                    $Perkosongjjg = 0;
                    $sum_vcut = 0;
                    $PerVcut = 0;
                    $PerAbr = 0;
                    $sum_kr = 0;
                    $total_kr = 0;
                    $per_kr = 0;
                    $totalSkor = 0;
                    $combination_counts = array();
                    foreach ($value2 as $key3 => $value3) {
                        $combination = $value3['blok'] . ' ' . $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $combination_counts[$combination]++;

                        $sum_bmt += $value3['bmt'];
                        $sum_bmk += $value3['bmk'];
                        $sum_over += $value3['overripe'];
                        $sum_kosongjjg += $value3['empty'];
                        $sum_vcut += $value3['vcut'];
                        $sum_kr += $value3['alas_br'];


                        $sum_Samplejjg += $value3['jumlah_jjg'];
                        $sum_abnor += $value3['abnormal'];
                    }
                    $dataBLok = count($combination_counts);


                    if ($sum_kr != 0) {
                        $total_kr = round($dataBLok / $sum_kr, 2);
                    } else {
                        $total_kr = 0;
                    }

                    $per_kr = round($total_kr * 100, 2);
                    $PerMth = round(($sum_bmt / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    $PerMsk = round(($sum_bmk / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    $PerOver = round(($sum_over / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    $Perkosongjjg = round(($sum_kosongjjg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
                    $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);

                    // skoring buah mentah
                    $skor_PerMth = 0;
                    if ($PerMth <= 1.0) {
                        $skor_PerMth = 10;
                    } else if ($PerMth >= 1.0 && $PerMth <= 2.0) {
                        $skor_PerMth = 8;
                    } else if ($PerMth >= 2.0 && $PerMth <= 3.0) {
                        $skor_PerMth = 6;
                    } else if ($PerMth >= 3.0 && $PerMth <= 4.0) {
                        $skor_PerMth = 4;
                    } else if ($PerMth >= 4.0 && $PerMth <= 5.0) {
                        $skor_PerMth = 2;
                    } else if ($PerMth >= 5.0) {
                        $skor_PerMth = 0;
                    }

                    // skoring buah masak
                    $skor_PerMsk = 0;
                    if ($PerMsk <= 75.0) {
                        $skor_PerMsk = 0;
                    } else if ($PerMsk >= 75.0 && $PerMsk <= 80.0) {
                        $skor_PerMsk = 1;
                    } else if ($PerMsk >= 80.0 && $PerMsk <= 85.0) {
                        $skor_PerMsk = 2;
                    } else if ($PerMsk >= 85.0 && $PerMsk <= 90.0) {
                        $skor_PerMsk = 3;
                    } else if ($PerMsk >= 90.0 && $PerMsk <= 95.0) {
                        $skor_PerMsk = 4;
                    } else if ($PerMsk >= 95.0) {
                        $skor_PerMsk = 5;
                    }

                    // skoring buah over
                    $skor_PerOver = 0;
                    if ($PerOver <= 2.0) {
                        $skor_PerOver = 5;
                    } else if ($PerOver >= 2.0 && $PerOver <= 4.0) {
                        $skor_PerOver = 4;
                    } else if ($PerOver >= 4.0 && $PerOver <= 6.0) {
                        $skor_PerOver = 3;
                    } else if ($PerOver >= 6.0 && $PerOver <= 8.0) {
                        $skor_PerOver = 2;
                    } else if ($PerOver >= 8.0 && $PerOver <= 10.0) {
                        $skor_PerOver = 1;
                    } else if ($PerOver >= 10.0) {
                        $skor_PerOver = 0;
                    }


                    //skor janjang kosong
                    $skor_Perkosongjjg = 0;
                    if ($Perkosongjjg <= 1.0) {
                        $skor_Perkosongjjg = 5;
                    } else if ($Perkosongjjg >= 1.0 && $Perkosongjjg <= 2.0) {
                        $skor_Perkosongjjg = 4;
                    } else if ($Perkosongjjg >= 2.0 && $Perkosongjjg <= 3.0) {
                        $skor_Perkosongjjg = 3;
                    } else if ($Perkosongjjg >= 3.0 && $Perkosongjjg <= 4.0) {
                        $skor_Perkosongjjg = 2;
                    } else if ($Perkosongjjg >= 4.0 && $Perkosongjjg <= 5.0) {
                        $skor_Perkosongjjg = 1;
                    } else if ($Perkosongjjg >= 5.0) {
                        $skor_Perkosongjjg = 0;
                    }

                    //skore Vcut
                    $skor_PerVcut = 0;
                    if ($PerVcut <= 2.0) {
                        $skor_PerVcut = 5;
                    } else if ($PerVcut >= 2.0 && $PerVcut <= 4.0) {
                        $skor_PerVcut = 4;
                    } else if ($PerVcut >= 4.0 && $PerVcut <= 6.0) {
                        $skor_PerVcut = 3;
                    } else if ($PerVcut >= 6.0 && $PerVcut <= 8.0) {
                        $skor_PerVcut = 2;
                    } else if ($PerVcut >= 8.0 && $PerVcut <= 10.0) {
                        $skor_PerVcut = 1;
                    } else if ($PerVcut >= 10.0) {
                        $skor_PerVcut = 0;
                    }

                    // blum di cek skornya di bawah
                    //skore PEnggunnan Brondolan
                    $skor_PerAbr = 0;
                    if ($PerAbr <= 75.0) {
                        $skor_PerAbr = 0;
                    } else if ($PerAbr >= 75.0 && $PerAbr <= 80.0) {
                        $skor_PerAbr = 1;
                    } else if ($PerAbr >= 80.0 && $PerAbr <= 85.0) {
                        $skor_PerAbr = 2;
                    } else if ($PerAbr >= 85.0 && $PerAbr <= 90.0) {
                        $skor_PerAbr = 3;
                    } else if ($PerAbr >= 90.0 && $PerAbr <= 95.0) {
                        $skor_PerAbr = 4;
                    } else if ($PerAbr >= 95.0) {
                        $skor_PerAbr = 5;
                    }

                    $skor_per_kr = 0;
                    if ($per_kr <= 60) {
                        $skor_per_kr = 0;
                    } else if ($per_kr >= 60 && $per_kr <= 70) {
                        $skor_per_kr = 1;
                    } else if ($per_kr >= 70 && $per_kr <= 80) {
                        $skor_per_kr = 2;
                    } else if ($per_kr >= 80 && $per_kr <= 90) {
                        $skor_per_kr = 3;
                    } else if ($per_kr >= 90 && $per_kr <= 100) {
                        $skor_per_kr = 4;
                    } else if ($per_kr >= 100) {
                        $skor_per_kr = 5;
                    }

                    $totalSkor =  $skor_PerMth + $skor_PerMsk + $skor_PerOver +  $skor_Perkosongjjg + $skor_PerVcut + $skor_PerAbr +  $skor_per_kr;

                    $RegMtBhAfdBln[$key][$key1][$key2]['tph_baris_blok'] = $dataBLok;
                    $RegMtBhAfdBln[$key][$key1][$key2]['sampleJJG_total'] = $sum_Samplejjg;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_mentah'] = $sum_bmt;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_perMentah'] = $PerMth;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_masak'] = $sum_bmk;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_perMasak'] = $PerMsk;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_over'] = $sum_over;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_perOver'] = $PerOver;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_abnormal'] = $sum_abnor;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_jjgKosong'] = $sum_kosongjjg;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_perKosongjjg'] = $Perkosongjjg;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_vcut'] = $sum_vcut;

                    $RegMtBhAfdBln[$key][$key1][$key2]['jum_kr'] = $sum_kr;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_kr'] = $total_kr;
                    $RegMtBhAfdBln[$key][$key1][$key2]['persen_kr'] = $per_kr;

                    // skoring
                    $RegMtBhAfdBln[$key][$key1][$key2]['skor_mentah'] = $skor_PerMth;
                    $RegMtBhAfdBln[$key][$key1][$key2]['skor_masak'] = $skor_PerMsk;
                    $RegMtBhAfdBln[$key][$key1][$key2]['skor_over'] = $skor_PerOver;
                    $RegMtBhAfdBln[$key][$key1][$key2]['skor_jjgKosong'] = $skor_Perkosongjjg;
                    $RegMtBhAfdBln[$key][$key1][$key2]['skor_vcut'] = $skor_PerVcut;
                    $RegMtBhAfdBln[$key][$key1][$key2]['skor_abnormal'] = $skor_PerAbr;
                    $RegMtBhAfdBln[$key][$key1][$key2]['skor_kr'] = $skor_per_kr;
                    $RegMtBhAfdBln[$key][$key1][$key2]['TOTAL_SKOR'] = $totalSkor;
                } else {
                    $RegMtBhAfdBln[$key][$key1][$key2]['tph_baris_blok'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['sampleJJG_total'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_mentah'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_perMentah'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_masak'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_perMasak'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_over'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_perOver'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_abnormal'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_jjgKosong'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_perKosongjjg'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_vcut'] = 0;

                    $RegMtBhAfdBln[$key][$key1][$key2]['jum_kr'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['total_kr'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['persen_kr'] = 0;

                    // skoring
                    $RegMtBhAfdBln[$key][$key1][$key2]['skor_mentah'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['skor_masak'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['skor_over'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['skor_jjgKosong'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['skor_vcut'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['skor_abnormal'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['skor_kr'] = 0;
                    $RegMtBhAfdBln[$key][$key1][$key2]['TOTAL_SKOR'] = 0;
                }
            }
        }

        // dd($RegMtBhAfdBln);

        $RegMTBHESTbln = array();
        foreach ($RegMtBhAfdBln as $key => $value) {
            foreach ($value as $key1 => $value1) if (!empty($value1)) {
                $dataBLok = 0;
                $sum_bmt = 0;
                $sum_bmk = 0;
                $sum_over = 0;
                $sum_Samplejjg = 0;
                $PerMth = 0;
                $PerMsk = 0;
                $PerOver = 0;
                $sum_abnor = 0;
                $sum_kosongjjg = 0;
                $Perkosongjjg = 0;
                $sum_vcut = 0;
                $PerVcut = 0;
                $PerAbr = 0;
                $sum_kr = 0;
                $total_kr = 0;
                $per_kr = 0;
                $totalSkor = 0;
                $perbagi = 0;
                foreach ($value1 as $key2 => $value2) {
                    $dataBLok += $value2['tph_baris_blok'];
                    $sum_bmt += $value2['total_mentah'];
                    $sum_bmk += $value2['total_masak'];
                    $sum_over += $value2['total_over'];
                    $sum_kosongjjg += $value2['total_jjgKosong'];
                    $sum_vcut += $value2['total_vcut'];
                    $sum_kr += $value2['jum_kr'];

                    $sum_Samplejjg += $value2['sampleJJG_total'];
                    $sum_abnor += $value2['total_abnormal'];
                }
                if ($sum_kr != 0) {
                    $total_kr = round($dataBLok / $sum_kr, 2);
                } else {
                    $total_kr = 0;
                }

                $per_kr = round($total_kr * 100, 2);

                $perbagi = $sum_Samplejjg - $sum_abnor;
                if ($perbagi != 0) {
                    $PerMth = round($sum_bmt / $perbagi * 100, 2);
                    $PerMsk = round($sum_bmk / $perbagi * 100, 2);
                    $PerOver = round($sum_over / $perbagi * 100, 2);
                    $Perkosongjjg = round($sum_kosongjjg / $perbagi * 100, 2);
                } else {
                    $PerMth = 0;
                    $PerMsk = 0;
                    $PerOver = 0;
                    $Perkosongjjg = 0;
                }

                if ($sum_Samplejjg != 0) {
                    $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
                    $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);
                } else {
                    $PerVcut = 0;
                    $PerAbr = 0;
                }


                // skoring buah mentah
                $skor_PerMth = 0;
                if ($PerMth <= 1.0) {
                    $skor_PerMth = 10;
                } else if ($PerMth >= 1.0 && $PerMth <= 2.0) {
                    $skor_PerMth = 8;
                } else if ($PerMth >= 2.0 && $PerMth <= 3.0) {
                    $skor_PerMth = 6;
                } else if ($PerMth >= 3.0 && $PerMth <= 4.0) {
                    $skor_PerMth = 4;
                } else if ($PerMth >= 4.0 && $PerMth <= 5.0) {
                    $skor_PerMth = 2;
                } else if ($PerMth >= 5.0) {
                    $skor_PerMth = 0;
                }

                // skoring buah masak
                $skor_PerMsk = 0;
                if ($PerMsk <= 75.0) {
                    $skor_PerMsk = 0;
                } else if ($PerMsk >= 75.0 && $PerMsk <= 80.0) {
                    $skor_PerMsk = 1;
                } else if ($PerMsk >= 80.0 && $PerMsk <= 85.0) {
                    $skor_PerMsk = 2;
                } else if ($PerMsk >= 85.0 && $PerMsk <= 90.0) {
                    $skor_PerMsk = 3;
                } else if ($PerMsk >= 90.0 && $PerMsk <= 95.0) {
                    $skor_PerMsk = 4;
                } else if ($PerMsk >= 95.0) {
                    $skor_PerMsk = 5;
                }

                // skoring buah over
                $skor_PerOver = 0;
                if ($PerOver <= 2.0) {
                    $skor_PerOver = 5;
                } else if ($PerOver >= 2.0 && $PerOver <= 4.0) {
                    $skor_PerOver = 4;
                } else if ($PerOver >= 4.0 && $PerOver <= 6.0) {
                    $skor_PerOver = 3;
                } else if ($PerOver >= 6.0 && $PerOver <= 8.0) {
                    $skor_PerOver = 2;
                } else if ($PerOver >= 8.0 && $PerOver <= 10.0) {
                    $skor_PerOver = 1;
                } else if ($PerOver >= 10.0) {
                    $skor_PerOver = 0;
                }


                //skor janjang kosong
                $skor_Perkosongjjg = 0;
                if ($Perkosongjjg <= 1.0) {
                    $skor_Perkosongjjg = 5;
                } else if ($Perkosongjjg >= 1.0 && $Perkosongjjg <= 2.0) {
                    $skor_Perkosongjjg = 4;
                } else if ($Perkosongjjg >= 2.0 && $Perkosongjjg <= 3.0) {
                    $skor_Perkosongjjg = 3;
                } else if ($Perkosongjjg >= 3.0 && $Perkosongjjg <= 4.0) {
                    $skor_Perkosongjjg = 2;
                } else if ($Perkosongjjg >= 4.0 && $Perkosongjjg <= 5.0) {
                    $skor_Perkosongjjg = 1;
                } else if ($Perkosongjjg >= 5.0) {
                    $skor_Perkosongjjg = 0;
                }

                //skore Vcut
                $skor_PerVcut = 0;
                if ($PerVcut <= 2.0) {
                    $skor_PerVcut = 5;
                } else if ($PerVcut >= 2.0 && $PerVcut <= 4.0) {
                    $skor_PerVcut = 4;
                } else if ($PerVcut >= 4.0 && $PerVcut <= 6.0) {
                    $skor_PerVcut = 3;
                } else if ($PerVcut >= 6.0 && $PerVcut <= 8.0) {
                    $skor_PerVcut = 2;
                } else if ($PerVcut >= 8.0 && $PerVcut <= 10.0) {
                    $skor_PerVcut = 1;
                } else if ($PerVcut >= 10.0) {
                    $skor_PerVcut = 0;
                }

                // blum di cek skornya di bawah
                //skore PEnggunnan Brondolan
                $skor_PerAbr = 0;
                if ($PerAbr <= 75.0) {
                    $skor_PerAbr = 0;
                } else if ($PerAbr >= 75.0 && $PerAbr <= 80.0) {
                    $skor_PerAbr = 1;
                } else if ($PerAbr >= 80.0 && $PerAbr <= 85.0) {
                    $skor_PerAbr = 2;
                } else if ($PerAbr >= 85.0 && $PerAbr <= 90.0) {
                    $skor_PerAbr = 3;
                } else if ($PerAbr >= 90.0 && $PerAbr <= 95.0) {
                    $skor_PerAbr = 4;
                } else if ($PerAbr >= 95.0) {
                    $skor_PerAbr = 5;
                }

                $skor_per_kr = 0;
                if ($per_kr <= 60) {
                    $skor_per_kr = 0;
                } else if ($per_kr >= 60 && $per_kr <= 70) {
                    $skor_per_kr = 1;
                } else if ($per_kr >= 70 && $per_kr <= 80) {
                    $skor_per_kr = 2;
                } else if ($per_kr >= 80 && $per_kr <= 90) {
                    $skor_per_kr = 3;
                } else if ($per_kr >= 90 && $per_kr <= 100) {
                    $skor_per_kr = 4;
                } else if ($per_kr >= 100) {
                    $skor_per_kr = 5;
                }

                $totalSkor =  $skor_PerMth + $skor_PerMsk + $skor_PerOver +  $skor_Perkosongjjg + $skor_PerVcut + $skor_PerAbr +  $skor_per_kr;

                $RegMTBHESTbln[$key][$key1]['tph_baris_blok'] = $dataBLok;
                $RegMTBHESTbln[$key][$key1]['sampleJJG_total'] = $sum_Samplejjg;
                $RegMTBHESTbln[$key][$key1]['total_mentah'] = $sum_bmt;
                $RegMTBHESTbln[$key][$key1]['total_perMentah'] = $PerMth;
                $RegMTBHESTbln[$key][$key1]['total_masak'] = $sum_bmk;
                $RegMTBHESTbln[$key][$key1]['total_perMasak'] = $PerMsk;
                $RegMTBHESTbln[$key][$key1]['total_over'] = $sum_over;
                $RegMTBHESTbln[$key][$key1]['total_perOver'] = $PerOver;
                $RegMTBHESTbln[$key][$key1]['total_abnormal'] = $sum_abnor;
                $RegMTBHESTbln[$key][$key1]['total_jjgKosong'] = $sum_kosongjjg;
                $RegMTBHESTbln[$key][$key1]['total_perKosongjjg'] = $Perkosongjjg;
                $RegMTBHESTbln[$key][$key1]['total_vcut'] = $sum_vcut;

                $RegMTBHESTbln[$key][$key1]['jum_kr'] = $sum_kr;
                $RegMTBHESTbln[$key][$key1]['total_kr'] = $total_kr;
                $RegMTBHESTbln[$key][$key1]['persen_kr'] = $per_kr;

                // skoring
                $RegMTBHESTbln[$key][$key1]['skor_mentah'] = $skor_PerMth;
                $RegMTBHESTbln[$key][$key1]['skor_masak'] = $skor_PerMsk;
                $RegMTBHESTbln[$key][$key1]['skor_over'] = $skor_PerOver;
                $RegMTBHESTbln[$key][$key1]['skor_jjgKosong'] = $skor_Perkosongjjg;
                $RegMTBHESTbln[$key][$key1]['skor_vcut'] = $skor_PerVcut;
                $RegMTBHESTbln[$key][$key1]['skor_abnormal'] = $skor_PerAbr;
                $RegMTBHESTbln[$key][$key1]['skor_kr'] = $skor_per_kr;
                $RegMTBHESTbln[$key][$key1]['TOTAL_SKOR'] = $totalSkor;
            } else {
                $RegMTBHESTbln[$key][$key1]['tph_baris_blok'] = 0;
                $RegMTBHESTbln[$key][$key1]['sampleJJG_total'] = 0;
                $RegMTBHESTbln[$key][$key1]['total_mentah'] = 0;
                $RegMTBHESTbln[$key][$key1]['total_perMentah'] = 0;
                $RegMTBHESTbln[$key][$key1]['total_masak'] = 0;
                $RegMTBHESTbln[$key][$key1]['total_perMasak'] = 0;
                $RegMTBHESTbln[$key][$key1]['total_over'] = 0;
                $RegMTBHESTbln[$key][$key1]['total_perOver'] = 0;
                $RegMTBHESTbln[$key][$key1]['total_abnormal'] = 0;
                $RegMTBHESTbln[$key][$key1]['total_jjgKosong'] = 0;
                $RegMTBHESTbln[$key][$key1]['total_perKosongjjg'] = 0;
                $RegMTBHESTbln[$key][$key1]['total_vcut'] = 0;

                $RegMTBHESTbln[$key][$key1]['jum_kr'] = 0;
                $RegMTBHESTbln[$key][$key1]['total_kr'] = 0;
                $RegMTBHESTbln[$key][$key1]['persen_kr'] = 0;

                // skoring
                $RegMTBHESTbln[$key][$key1]['skor_mentah'] = 0;
                $RegMTBHESTbln[$key][$key1]['skor_masak'] = 0;
                $RegMTBHESTbln[$key][$key1]['skor_over'] = 0;
                $RegMTBHESTbln[$key][$key1]['skor_jjgKosong'] = 0;
                $RegMTBHESTbln[$key][$key1]['skor_vcut'] = 0;
                $RegMTBHESTbln[$key][$key1]['skor_abnormal'] = 0;
                $RegMTBHESTbln[$key][$key1]['skor_kr'] = 0;
                $RegMTBHESTbln[$key][$key1]['TOTAL_SKOR'] = 0;
            }
        }
        // dd($RegMTBHESTbln);
        $RegMTbuahBln = array();
        foreach ($RegMTBHESTbln as $key => $value) if (!empty($value)) {
            $dataBLok = 0;
            $sum_bmt = 0;
            $sum_bmk = 0;
            $sum_over = 0;
            $sum_Samplejjg = 0;
            $PerMth = 0;
            $PerMsk = 0;
            $PerOver = 0;
            $sum_abnor = 0;
            $sum_kosongjjg = 0;
            $Perkosongjjg = 0;
            $sum_vcut = 0;
            $PerVcut = 0;
            $PerAbr = 0;
            $sum_kr = 0;
            $total_kr = 0;
            $per_kr = 0;
            $totalSkor = 0;
            $perbagi = 0;
            foreach ($value as $key1 => $value2) {
                $dataBLok += $value2['tph_baris_blok'];
                $sum_bmt += $value2['total_mentah'];
                $sum_bmk += $value2['total_masak'];
                $sum_over += $value2['total_over'];
                $sum_kosongjjg += $value2['total_jjgKosong'];
                $sum_vcut += $value2['total_vcut'];
                $sum_kr += $value2['jum_kr'];

                $sum_Samplejjg += $value2['sampleJJG_total'];
                $sum_abnor += $value2['total_abnormal'];
            }
            if ($sum_kr != 0) {
                $total_kr = round($dataBLok / $sum_kr, 2);
            } else {
                $total_kr = 0;
            }

            $per_kr = round($total_kr * 100, 2);
            // if () {
            //     $PerMth = round(($sum_bmt / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
            // } else {
            //     $PerMth = 0;
            // }



            if ($sum_bmt != 0 && $sum_bmk != 0 && $sum_over != 0 && $sum_kosongjjg != 0) {
                $perbagi = abs($sum_Samplejjg - $sum_abnor);
                $PerMth = round($sum_bmt / $perbagi * 100, 2);
                $PerMsk = round($sum_bmk / $perbagi * 100, 2);
                $PerOver = round($sum_over / $perbagi * 100, 2);
                $Perkosongjjg = round($sum_kosongjjg / $perbagi * 100, 2);
            } else {
                $PerMth = 0;
                $PerMsk = 0;
                $PerOver = 0;
                $Perkosongjjg = 0;
            }


            if ($sum_Samplejjg != 0) {
                $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
                $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);
            } else {
                $PerVcut = 0;
                $PerAbr = 0;
            }


            // skoring buah mentah
            $skor_PerMth = 0;
            if ($PerMth <= 1.0) {
                $skor_PerMth = 10;
            } else if ($PerMth >= 1.0 && $PerMth <= 2.0) {
                $skor_PerMth = 8;
            } else if ($PerMth >= 2.0 && $PerMth <= 3.0) {
                $skor_PerMth = 6;
            } else if ($PerMth >= 3.0 && $PerMth <= 4.0) {
                $skor_PerMth = 4;
            } else if ($PerMth >= 4.0 && $PerMth <= 5.0) {
                $skor_PerMth = 2;
            } else if ($PerMth >= 5.0) {
                $skor_PerMth = 0;
            }

            // skoring buah masak
            $skor_PerMsk = 0;
            if ($PerMsk <= 75.0) {
                $skor_PerMsk = 0;
            } else if ($PerMsk >= 75.0 && $PerMsk <= 80.0) {
                $skor_PerMsk = 1;
            } else if ($PerMsk >= 80.0 && $PerMsk <= 85.0) {
                $skor_PerMsk = 2;
            } else if ($PerMsk >= 85.0 && $PerMsk <= 90.0) {
                $skor_PerMsk = 3;
            } else if ($PerMsk >= 90.0 && $PerMsk <= 95.0) {
                $skor_PerMsk = 4;
            } else if ($PerMsk >= 95.0) {
                $skor_PerMsk = 5;
            }

            // skoring buah over
            $skor_PerOver = 0;
            if ($PerOver <= 2.0) {
                $skor_PerOver = 5;
            } else if ($PerOver >= 2.0 && $PerOver <= 4.0) {
                $skor_PerOver = 4;
            } else if ($PerOver >= 4.0 && $PerOver <= 6.0) {
                $skor_PerOver = 3;
            } else if ($PerOver >= 6.0 && $PerOver <= 8.0) {
                $skor_PerOver = 2;
            } else if ($PerOver >= 8.0 && $PerOver <= 10.0) {
                $skor_PerOver = 1;
            } else if ($PerOver >= 10.0) {
                $skor_PerOver = 0;
            }


            //skor janjang kosong
            $skor_Perkosongjjg = 0;
            if ($Perkosongjjg <= 1.0) {
                $skor_Perkosongjjg = 5;
            } else if ($Perkosongjjg >= 1.0 && $Perkosongjjg <= 2.0) {
                $skor_Perkosongjjg = 4;
            } else if ($Perkosongjjg >= 2.0 && $Perkosongjjg <= 3.0) {
                $skor_Perkosongjjg = 3;
            } else if ($Perkosongjjg >= 3.0 && $Perkosongjjg <= 4.0) {
                $skor_Perkosongjjg = 2;
            } else if ($Perkosongjjg >= 4.0 && $Perkosongjjg <= 5.0) {
                $skor_Perkosongjjg = 1;
            } else if ($Perkosongjjg >= 5.0) {
                $skor_Perkosongjjg = 0;
            }

            //skore Vcut
            $skor_PerVcut = 0;
            if ($PerVcut <= 2.0) {
                $skor_PerVcut = 5;
            } else if ($PerVcut >= 2.0 && $PerVcut <= 4.0) {
                $skor_PerVcut = 4;
            } else if ($PerVcut >= 4.0 && $PerVcut <= 6.0) {
                $skor_PerVcut = 3;
            } else if ($PerVcut >= 6.0 && $PerVcut <= 8.0) {
                $skor_PerVcut = 2;
            } else if ($PerVcut >= 8.0 && $PerVcut <= 10.0) {
                $skor_PerVcut = 1;
            } else if ($PerVcut >= 10.0) {
                $skor_PerVcut = 0;
            }

            // blum di cek skornya di bawah
            //skore PEnggunnan Brondolan
            $skor_PerAbr = 0;
            if ($PerAbr <= 75.0) {
                $skor_PerAbr = 0;
            } else if ($PerAbr >= 75.0 && $PerAbr <= 80.0) {
                $skor_PerAbr = 1;
            } else if ($PerAbr >= 80.0 && $PerAbr <= 85.0) {
                $skor_PerAbr = 2;
            } else if ($PerAbr >= 85.0 && $PerAbr <= 90.0) {
                $skor_PerAbr = 3;
            } else if ($PerAbr >= 90.0 && $PerAbr <= 95.0) {
                $skor_PerAbr = 4;
            } else if ($PerAbr >= 95.0) {
                $skor_PerAbr = 5;
            }

            $skor_per_kr = 0;
            if ($per_kr <= 60) {
                $skor_per_kr = 0;
            } else if ($per_kr >= 60 && $per_kr <= 70) {
                $skor_per_kr = 1;
            } else if ($per_kr >= 70 && $per_kr <= 80) {
                $skor_per_kr = 2;
            } else if ($per_kr >= 80 && $per_kr <= 90) {
                $skor_per_kr = 3;
            } else if ($per_kr >= 90 && $per_kr <= 100) {
                $skor_per_kr = 4;
            } else if ($per_kr >= 100) {
                $skor_per_kr = 5;
            }

            $totalSkor =  $skor_PerMth + $skor_PerMsk + $skor_PerOver +  $skor_Perkosongjjg + $skor_PerVcut + $skor_PerAbr +  $skor_per_kr;

            $RegMTbuahBln[$key]['tph_baris_blok'] = $dataBLok;
            $RegMTbuahBln[$key]['sampleJJG_total'] = $sum_Samplejjg;
            $RegMTbuahBln[$key]['total_mentah'] = $sum_bmt;
            $RegMTbuahBln[$key]['total_perMentah'] = $PerMth;
            $RegMTbuahBln[$key]['total_masak'] = $sum_bmk;
            $RegMTbuahBln[$key]['total_perMasak'] = $PerMsk;
            $RegMTbuahBln[$key]['total_over'] = $sum_over;
            $RegMTbuahBln[$key]['total_perOver'] = $PerOver;
            $RegMTbuahBln[$key]['total_abnormal'] = $sum_abnor;
            $RegMTbuahBln[$key]['total_jjgKosong'] = $sum_kosongjjg;
            $RegMTbuahBln[$key]['total_perKosongjjg'] = $Perkosongjjg;
            $RegMTbuahBln[$key]['total_vcut'] = $sum_vcut;

            $RegMTbuahBln[$key]['jum_kr'] = $sum_kr;
            $RegMTbuahBln[$key]['total_kr'] = $total_kr;
            $RegMTbuahBln[$key]['persen_kr'] = $per_kr;

            // skoring
            $RegMTbuahBln[$key]['skor_mentah'] = $skor_PerMth;
            $RegMTbuahBln[$key]['skor_masak'] = $skor_PerMsk;
            $RegMTbuahBln[$key]['skor_over'] = $skor_PerOver;
            $RegMTbuahBln[$key]['skor_jjgKosong'] = $skor_Perkosongjjg;
            $RegMTbuahBln[$key]['skor_vcut'] = $skor_PerVcut;
            $RegMTbuahBln[$key]['skor_abnormal'] = $skor_PerAbr;
            $RegMTbuahBln[$key]['skor_kr'] = $skor_per_kr;
            $RegMTbuahBln[$key]['TOTAL_SKOR'] = $totalSkor;
        } else {
            $RegMTbuahBln[$key]['tph_baris_blok'] = 0;
            $RegMTbuahBln[$key]['sampleJJG_total'] = 0;
            $RegMTbuahBln[$key]['total_mentah'] = 0;
            $RegMTbuahBln[$key]['total_perMentah'] = 0;
            $RegMTbuahBln[$key]['total_masak'] = 0;
            $RegMTbuahBln[$key]['total_perMasak'] = 0;
            $RegMTbuahBln[$key]['total_over'] = 0;
            $RegMTbuahBln[$key]['total_perOver'] = 0;
            $RegMTbuahBln[$key]['total_abnormal'] = 0;
            $RegMTbuahBln[$key]['total_jjgKosong'] = 0;
            $RegMTbuahBln[$key]['total_perKosongjjg'] = 0;
            $RegMTbuahBln[$key]['total_vcut'] = 0;

            $RegMTbuahBln[$key]['jum_kr'] = 0;
            $RegMTbuahBln[$key]['total_kr'] = 0;
            $RegMTbuahBln[$key]['persen_kr'] = 0;

            // skoring
            $RegMTbuahBln[$key]['skor_mentah'] = 0;
            $RegMTbuahBln[$key]['skor_masak'] = 0;
            $RegMTbuahBln[$key]['skor_over'] = 0;
            $RegMTbuahBln[$key]['skor_jjgKosong'] = 0;
            $RegMTbuahBln[$key]['skor_vcut'] = 0;
            $RegMTbuahBln[$key]['skor_abnormal'] = 0;
            $RegMTbuahBln[$key]['skor_kr'] = 0;
            $RegMTbuahBln[$key]['TOTAL_SKOR'] = 0;
        }
        // dd($RegMTbuahBln);
        // foreach ($mtBuahAllEst as)
        //mutu buah regionan pertahun
        $RegMTbuahTHn = array();
        $dataBLok = 0;
        $sum_bmt = 0;
        $sum_bmk = 0;
        $sum_over = 0;
        $sum_Samplejjg = 0;
        $PerMth = 0;
        $PerMsk = 0;
        $PerOver = 0;
        $sum_abnor = 0;
        $sum_kosongjjg = 0;
        $Perkosongjjg = 0;
        $sum_vcut = 0;
        $PerVcut = 0;
        $PerAbr = 0;
        $sum_kr = 0;
        $total_kr = 0;
        $per_kr = 0;
        $totalSkor = 0;
        $perbagi = 0;
        foreach ($mtBuahTahunall as $key => $value) {
            // dd($value);
            $dataBLok += $value['tph_blok'];
            $sum_bmt += $value['total_mentah'];
            $sum_bmk += $value['total_masak'];
            $sum_over += $value['total_over'];
            $sum_kosongjjg += $value['total_jjgKosong'];
            $sum_vcut += $value['total_vcut'];
            $sum_kr += $value['jum_kr'];

            $sum_Samplejjg += $value['sampleJJG_total'];
            $sum_abnor += $value['total_abnormal'];
        }

        if ($sum_kr != 0) {
            $total_kr = round($dataBLok / $sum_kr, 2);
        } else {
            $total_kr = 0;
        }

        $per_kr = round($total_kr * 100, 2);

        $perbagi = $sum_Samplejjg - $sum_abnor;
        if ($perbagi != 0) {
            $PerMth = round($sum_bmt / $perbagi * 100, 2);
            $PerMsk = round($sum_bmk / $perbagi * 100, 2);
            $PerOver = round($sum_over / $perbagi * 100, 2);
            $Perkosongjjg = round($sum_kosongjjg / $perbagi * 100, 2);
        } else {
            $PerMth = 0;
            $PerMsk = 0;
            $PerOver = 0;
            $Perkosongjjg = 0;
        }

        if ($sum_Samplejjg != 0) {
            $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
            $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);
        } else {
            $PerVcut = 0;
            $PerAbr = 0;
        }


        // skoring buah mentah
        $skor_PerMth = 0;
        if ($PerMth <= 1.0) {
            $skor_PerMth = 10;
        } else if ($PerMth >= 1.0 && $PerMth <= 2.0) {
            $skor_PerMth = 8;
        } else if ($PerMth >= 2.0 && $PerMth <= 3.0) {
            $skor_PerMth = 6;
        } else if ($PerMth >= 3.0 && $PerMth <= 4.0) {
            $skor_PerMth = 4;
        } else if ($PerMth >= 4.0 && $PerMth <= 5.0) {
            $skor_PerMth = 2;
        } else if ($PerMth >= 5.0) {
            $skor_PerMth = 0;
        }

        // skoring buah masak
        $skor_PerMsk = 0;
        if ($PerMsk <= 75.0) {
            $skor_PerMsk = 0;
        } else if ($PerMsk >= 75.0 && $PerMsk <= 80.0) {
            $skor_PerMsk = 1;
        } else if ($PerMsk >= 80.0 && $PerMsk <= 85.0) {
            $skor_PerMsk = 2;
        } else if ($PerMsk >= 85.0 && $PerMsk <= 90.0) {
            $skor_PerMsk = 3;
        } else if ($PerMsk >= 90.0 && $PerMsk <= 95.0) {
            $skor_PerMsk = 4;
        } else if ($PerMsk >= 95.0) {
            $skor_PerMsk = 5;
        }

        // skoring buah over
        $skor_PerOver = 0;
        if ($PerOver <= 2.0) {
            $skor_PerOver = 5;
        } else if ($PerOver >= 2.0 && $PerOver <= 4.0) {
            $skor_PerOver = 4;
        } else if ($PerOver >= 4.0 && $PerOver <= 6.0) {
            $skor_PerOver = 3;
        } else if ($PerOver >= 6.0 && $PerOver <= 8.0) {
            $skor_PerOver = 2;
        } else if ($PerOver >= 8.0 && $PerOver <= 10.0) {
            $skor_PerOver = 1;
        } else if ($PerOver >= 10.0) {
            $skor_PerOver = 0;
        }


        //skor janjang kosong
        $skor_Perkosongjjg = 0;
        if ($Perkosongjjg <= 1.0) {
            $skor_Perkosongjjg = 5;
        } else if ($Perkosongjjg >= 1.0 && $Perkosongjjg <= 2.0) {
            $skor_Perkosongjjg = 4;
        } else if ($Perkosongjjg >= 2.0 && $Perkosongjjg <= 3.0) {
            $skor_Perkosongjjg = 3;
        } else if ($Perkosongjjg >= 3.0 && $Perkosongjjg <= 4.0) {
            $skor_Perkosongjjg = 2;
        } else if ($Perkosongjjg >= 4.0 && $Perkosongjjg <= 5.0) {
            $skor_Perkosongjjg = 1;
        } else if ($Perkosongjjg >= 5.0) {
            $skor_Perkosongjjg = 0;
        }

        //skore Vcut
        $skor_PerVcut = 0;
        if ($PerVcut <= 2.0) {
            $skor_PerVcut = 5;
        } else if ($PerVcut >= 2.0 && $PerVcut <= 4.0) {
            $skor_PerVcut = 4;
        } else if ($PerVcut >= 4.0 && $PerVcut <= 6.0) {
            $skor_PerVcut = 3;
        } else if ($PerVcut >= 6.0 && $PerVcut <= 8.0) {
            $skor_PerVcut = 2;
        } else if ($PerVcut >= 8.0 && $PerVcut <= 10.0) {
            $skor_PerVcut = 1;
        } else if ($PerVcut >= 10.0) {
            $skor_PerVcut = 0;
        }

        // blum di cek skornya di bawah
        //skore PEnggunnan Brondolan
        $skor_PerAbr = 0;
        if ($PerAbr <= 75.0) {
            $skor_PerAbr = 0;
        } else if ($PerAbr >= 75.0 && $PerAbr <= 80.0) {
            $skor_PerAbr = 1;
        } else if ($PerAbr >= 80.0 && $PerAbr <= 85.0) {
            $skor_PerAbr = 2;
        } else if ($PerAbr >= 85.0 && $PerAbr <= 90.0) {
            $skor_PerAbr = 3;
        } else if ($PerAbr >= 90.0 && $PerAbr <= 95.0) {
            $skor_PerAbr = 4;
        } else if ($PerAbr >= 95.0) {
            $skor_PerAbr = 5;
        }

        $skor_per_kr = 0;
        if ($per_kr <= 60) {
            $skor_per_kr = 0;
        } else if ($per_kr >= 60 && $per_kr <= 70) {
            $skor_per_kr = 1;
        } else if ($per_kr >= 70 && $per_kr <= 80) {
            $skor_per_kr = 2;
        } else if ($per_kr >= 80 && $per_kr <= 90) {
            $skor_per_kr = 3;
        } else if ($per_kr >= 90 && $per_kr <= 100) {
            $skor_per_kr = 4;
        } else if ($per_kr >= 100) {
            $skor_per_kr = 5;
        }

        $totalSkor =  $skor_PerMth + $skor_PerMsk + $skor_PerOver +  $skor_Perkosongjjg + $skor_PerVcut + $skor_PerAbr +  $skor_per_kr;

        $RegMTbuahTHn['I']['tph_blok'] = $dataBLok;
        $RegMTbuahTHn['I']['sampleJJG_total'] = $sum_Samplejjg;
        $RegMTbuahTHn['I']['total_mentah'] = $sum_bmt;
        $RegMTbuahTHn['I']['total_perMentah'] = $PerMth;
        $RegMTbuahTHn['I']['total_masak'] = $sum_bmk;
        $RegMTbuahTHn['I']['total_perMasak'] = $PerMsk;
        $RegMTbuahTHn['I']['total_over'] = $sum_over;
        $RegMTbuahTHn['I']['total_perOver'] = $PerOver;
        $RegMTbuahTHn['I']['total_abnormal'] = $sum_abnor;
        $RegMTbuahTHn['I']['total_jjgKosong'] = $sum_kosongjjg;
        $RegMTbuahTHn['I']['total_perKosongjjg'] = $Perkosongjjg;
        $RegMTbuahTHn['I']['total_vcut'] = $sum_vcut;

        $RegMTbuahTHn['I']['jum_kr'] = $sum_kr;
        $RegMTbuahTHn['I']['total_kr'] = $total_kr;
        $RegMTbuahTHn['I']['persen_kr'] = $per_kr;

        // skoring
        $RegMTbuahTHn['I']['skor_mentah'] = $skor_PerMth;
        $RegMTbuahTHn['I']['skor_masak'] = $skor_PerMsk;
        $RegMTbuahTHn['I']['skor_over'] = $skor_PerOver;
        $RegMTbuahTHn['I']['skor_jjgKosong'] = $skor_Perkosongjjg;
        $RegMTbuahTHn['I']['skor_vcut'] = $skor_PerVcut;
        $RegMTbuahTHn['I']['skor_abnormal'] = $skor_PerAbr;
        $RegMTbuahTHn['I']['skor_kr'] = $skor_per_kr;
        $RegMTbuahTHn['I']['TOTAL_SKOR'] = $totalSkor;

        // dd($RegMTbuahTHn);
        // dd($mtBuahAllEst, $mtTranstAllbln, $bulanAllEST);
        // dd($bulanAllEST);
        // dd($WilMtAncakThn, $mtTransTahun, $mtBuahTahunall);

        $datamtBuahAFD = array();
        foreach ($queryMTbuah as $key => $value) {
            $datamtBuahAFD[$key] = array();
            foreach ($value as $key2 => $value2) {
                $datamtBuahAFD[$key][$key2] = array();
                foreach ($bulan as $month) {
                    $datamtBuahAFD[$key][$key2][$month] = array();
                    foreach ($value2 as $key3 => $value3) {
                        $date_month = date('F', strtotime($value3['datetime']));
                        if ($date_month == $month) {
                            array_push($datamtBuahAFD[$key][$key2][$month], $value3);
                        }
                    }
                }
            }
        }
        // dd($datamtBuahAFD);
        //membuat nilai untuk mutu ancakan berdasarkan afdeling perbulan
        $datamtAncakAFD = array();
        foreach ($querytahun as $key => $value) {
            $datamtAncakAFD[$key] = array();
            foreach ($value as $key2 => $value2) {
                $datamtAncakAFD[$key][$key2] = array();
                foreach ($bulan as $month) {
                    $datamtAncakAFD[$key][$key2][$month] = array();
                    foreach ($value2 as $key3 => $value3) {
                        $date_month = date('F', strtotime($value3['datetime']));
                        if ($date_month == $month) {
                            array_push($datamtAncakAFD[$key][$key2][$month], $value3);
                        }
                    }
                }
            }
        }
        // dd($datamtAncakAFD);
        //membuat nilai untuk mutu transport berdasarkan afdeling perbulan
        $datamtTransAFD = array();
        foreach ($queryMTtrans as $key => $value) {
            $datamtTransAFD[$key] = array();
            foreach ($value as $key2 => $value2) {
                $datamtTransAFD[$key][$key2] = array();
                foreach ($bulan as $month) {
                    $datamtTransAFD[$key][$key2][$month] = array();
                    foreach ($value2 as $key3 => $value3) {
                        $date_month = date('F', strtotime($value3['datetime']));
                        if ($date_month == $month) {
                            array_push($datamtTransAFD[$key][$key2][$month], $value3);
                        }
                    }
                }
            }
        }
        // dd($datamtTransAFD);
        //membuat nilai mutu buah afdeling perbulan untuk nilai default
        $defBuahAFDtab = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($queryAfd as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defBuahAFDtab[$est['est']][$afd['nama']][$month] = 0;
                    }
                }
            }
        }
        //membuat nilai mutu ancak afdeling perbulan untuk nilai default
        $defaultTabAFD = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($queryAfd as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defaultTabAFD[$est['est']][$afd['nama']][$month] = 0;
                    }
                }
            }
        }
        //membuat nilai mutu trans afdeling perbulan untuk nilai default
        $defTransAFDtab = array();
        foreach ($bulan as $month) {
            foreach ($queryEste as $est) {
                foreach ($queryAfd as $afd) {
                    if ($est['est'] == $afd['est']) {
                        $defTransAFDtab[$est['est']][$afd['nama']][$month] = 0;
                    }
                }
            }
        }
        //meninmpa nilai defaul dengan isi data yang di atas tadi untuk mutu buah
        foreach ($datamtBuahAFD as $estKey => $estValue) {
            foreach ($estValue as $afdKey => $afdValue) {
                foreach ($afdValue as $monthKey => $monthValue) {
                    if (!empty($monthValue)) {
                        $defBuahAFDtab[$estKey][$afdKey][$monthKey] = $monthValue;
                    } else {
                        $defBuahAFDtab[$estKey][$afdKey][$monthKey] = 0;
                    }
                }
            }
        }
        // dd($datamtBuahAFD, $defBuahAFDtab);
        //meninmpa nilai defaul dengan isi data yang di atas tadi untuk mutu ancak
        foreach ($datamtAncakAFD as $estKey => $estValue) {
            foreach ($estValue as $afdKey => $afdValue) {
                foreach ($afdValue as $monthKey => $monthValue) {
                    if (!empty($monthValue)) {
                        $defaultTabAFD[$estKey][$afdKey][$monthKey] = $monthValue;
                    } else {
                        $defaultTabAFD[$estKey][$afdKey][$monthKey] = 0;
                    }
                }
            }
        }
        //meninmpa nilai defaul dengan isi data yang di atas tadi untuk mutu trans
        foreach ($datamtTransAFD as $estKey => $estValue) {
            foreach ($estValue as $afdKey => $afdValue) {
                foreach ($afdValue as $monthKey => $monthValue) {
                    if (!empty($monthValue)) {
                        $defTransAFDtab[$estKey][$afdKey][$monthKey] = $monthValue;
                    } else {
                        $defTransAFDtab[$estKey][$afdKey][$monthKey] = 0;
                    }
                }
            }
        }
        // dd($defTransAFDtab);
        //perhitungan mutu buah untuk afd perbulan untuk tabel paling akhir mencari tahun
        $MtBuahtabAFDbln = array();
        foreach ($defBuahAFDtab as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2)  if (is_array($value2)) {
                    $sum_bmt = 0;
                    $sum_bmk = 0;
                    $sum_over = 0;
                    $sum_Samplejjg = 0;
                    $PerMth = 0;
                    $PerMsk = 0;
                    $PerOver = 0;
                    $sum_abnor = 0;
                    $sum_kosongjjg = 0;
                    $Perkosongjjg = 0;
                    $sum_vcut = 0;
                    $PerVcut = 0;
                    $PerAbr = 0;
                    $sum_kr = 0;
                    $total_kr = 0;
                    $per_kr = 0;
                    $totalSkor = 0;
                    $combination_counts = array();
                    foreach ($value2 as $ke3 => $value3) {
                        $combination = $value3['blok'] . ' ' . $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $combination_counts[$combination]++;
                        $sum_bmt += $value3['bmt'];
                        $sum_bmk += $value3['bmk'];
                        $sum_over += $value3['overripe'];
                        $sum_kosongjjg += $value3['empty'];
                        $sum_vcut += $value3['vcut'];
                        $sum_kr += $value3['alas_br'];


                        $sum_Samplejjg += $value3['jumlah_jjg'];
                        $sum_abnor += $value3['abnormal'];
                    }
                    $dataBLok = count($combination_counts);


                    if ($sum_kr != 0) {
                        $total_kr = round($dataBLok / $sum_kr, 2);
                    } else {
                        $total_kr = 0;
                    }

                    $per_kr = round($total_kr * 100, 2);
                    $PerMth = round(($sum_bmt / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    $PerMsk = round(($sum_bmk / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    $PerOver = round(($sum_over / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    $Perkosongjjg = round(($sum_kosongjjg / ($sum_Samplejjg - $sum_abnor)) * 100, 2);
                    $PerVcut = round(($sum_vcut / $sum_Samplejjg) * 100, 2);
                    $PerAbr = round(($sum_abnor / $sum_Samplejjg) * 100, 2);

                    // skoring buah mentah
                    $skor_PerMth = 0;
                    if ($PerMth <= 1.0) {
                        $skor_PerMth = 10;
                    } else if ($PerMth >= 1.0 && $PerMth <= 2.0) {
                        $skor_PerMth = 8;
                    } else if ($PerMth >= 2.0 && $PerMth <= 3.0) {
                        $skor_PerMth = 6;
                    } else if ($PerMth >= 3.0 && $PerMth <= 4.0) {
                        $skor_PerMth = 4;
                    } else if ($PerMth >= 4.0 && $PerMth <= 5.0) {
                        $skor_PerMth = 2;
                    } else if ($PerMth >= 5.0) {
                        $skor_PerMth = 0;
                    }

                    // skoring buah masak
                    $skor_PerMsk = 0;
                    if ($PerMsk <= 75.0) {
                        $skor_PerMsk = 0;
                    } else if ($PerMsk >= 75.0 && $PerMsk <= 80.0) {
                        $skor_PerMsk = 1;
                    } else if ($PerMsk >= 80.0 && $PerMsk <= 85.0) {
                        $skor_PerMsk = 2;
                    } else if ($PerMsk >= 85.0 && $PerMsk <= 90.0) {
                        $skor_PerMsk = 3;
                    } else if ($PerMsk >= 90.0 && $PerMsk <= 95.0) {
                        $skor_PerMsk = 4;
                    } else if ($PerMsk >= 95.0) {
                        $skor_PerMsk = 5;
                    }

                    // skoring buah over
                    $skor_PerOver = 0;
                    if ($PerOver <= 2.0) {
                        $skor_PerOver = 5;
                    } else if ($PerOver >= 2.0 && $PerOver <= 4.0) {
                        $skor_PerOver = 4;
                    } else if ($PerOver >= 4.0 && $PerOver <= 6.0) {
                        $skor_PerOver = 3;
                    } else if ($PerOver >= 6.0 && $PerOver <= 8.0) {
                        $skor_PerOver = 2;
                    } else if ($PerOver >= 8.0 && $PerOver <= 10.0) {
                        $skor_PerOver = 1;
                    } else if ($PerOver >= 10.0) {
                        $skor_PerOver = 0;
                    }


                    //skor janjang kosong
                    $skor_Perkosongjjg = 0;
                    if ($Perkosongjjg <= 1.0) {
                        $skor_Perkosongjjg = 5;
                    } else if ($Perkosongjjg >= 1.0 && $Perkosongjjg <= 2.0) {
                        $skor_Perkosongjjg = 4;
                    } else if ($Perkosongjjg >= 2.0 && $Perkosongjjg <= 3.0) {
                        $skor_Perkosongjjg = 3;
                    } else if ($Perkosongjjg >= 3.0 && $Perkosongjjg <= 4.0) {
                        $skor_Perkosongjjg = 2;
                    } else if ($Perkosongjjg >= 4.0 && $Perkosongjjg <= 5.0) {
                        $skor_Perkosongjjg = 1;
                    } else if ($Perkosongjjg >= 5.0) {
                        $skor_Perkosongjjg = 0;
                    }

                    //skore Vcut
                    $skor_PerVcut = 0;
                    if ($PerVcut <= 2.0) {
                        $skor_PerVcut = 5;
                    } else if ($PerVcut >= 2.0 && $PerVcut <= 4.0) {
                        $skor_PerVcut = 4;
                    } else if ($PerVcut >= 4.0 && $PerVcut <= 6.0) {
                        $skor_PerVcut = 3;
                    } else if ($PerVcut >= 6.0 && $PerVcut <= 8.0) {
                        $skor_PerVcut = 2;
                    } else if ($PerVcut >= 8.0 && $PerVcut <= 10.0) {
                        $skor_PerVcut = 1;
                    } else if ($PerVcut >= 10.0) {
                        $skor_PerVcut = 0;
                    }

                    // blum di cek skornya di bawah
                    //skore PEnggunnan Brondolan
                    $skor_PerAbr = 0;
                    if ($PerAbr <= 75.0) {
                        $skor_PerAbr = 0;
                    } else if ($PerAbr >= 75.0 && $PerAbr <= 80.0) {
                        $skor_PerAbr = 1;
                    } else if ($PerAbr >= 80.0 && $PerAbr <= 85.0) {
                        $skor_PerAbr = 2;
                    } else if ($PerAbr >= 85.0 && $PerAbr <= 90.0) {
                        $skor_PerAbr = 3;
                    } else if ($PerAbr >= 90.0 && $PerAbr <= 95.0) {
                        $skor_PerAbr = 4;
                    } else if ($PerAbr >= 95.0) {
                        $skor_PerAbr = 5;
                    }

                    $skor_per_kr = 0;
                    if ($per_kr <= 60) {
                        $skor_per_kr = 0;
                    } else if ($per_kr >= 60 && $per_kr <= 70) {
                        $skor_per_kr = 1;
                    } else if ($per_kr >= 70 && $per_kr <= 80) {
                        $skor_per_kr = 2;
                    } else if ($per_kr >= 80 && $per_kr <= 90) {
                        $skor_per_kr = 3;
                    } else if ($per_kr >= 90 && $per_kr <= 100) {
                        $skor_per_kr = 4;
                    } else if ($per_kr >= 100) {
                        $skor_per_kr = 5;
                    }

                    $totalSkor =  $skor_PerMth + $skor_PerMsk + $skor_PerOver +  $skor_Perkosongjjg + $skor_PerVcut + $skor_PerAbr +  $skor_per_kr;

                    $MtBuahtabAFDbln[$key][$key1][$key2]['tph_baris_blok'] = $dataBLok;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['sampleJJG_total'] = $sum_Samplejjg;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_mentah'] = $sum_bmt;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_perMentah'] = $PerMth;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_masak'] = $sum_bmk;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_perMasak'] = $PerMsk;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_over'] = $sum_over;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_perOver'] = $PerOver;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_abnormal'] = $sum_abnor;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_jjgKosong'] = $sum_kosongjjg;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_perKosongjjg'] = $Perkosongjjg;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_vcut'] = $sum_vcut;

                    $MtBuahtabAFDbln[$key][$key1][$key2]['jum_kr'] = $sum_kr;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_kr'] = $total_kr;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['persen_kr'] = $per_kr;

                    // skoring
                    $MtBuahtabAFDbln[$key][$key1][$key2]['skor_mentah'] = $skor_PerMth;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['skor_masak'] = $skor_PerMsk;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['skor_over'] = $skor_PerOver;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['skor_jjgKosong'] = $skor_Perkosongjjg;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['skor_vcut'] = $skor_PerVcut;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['skor_abnormal'] = $skor_PerAbr;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['skor_kr'] = $skor_per_kr;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['TOTAL_SKOR'] = $totalSkor;
                } else {
                    $MtBuahtabAFDbln[$key][$key1][$key2]['tph_baris_blok'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['sampleJJG_total'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_mentah'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_perMentah'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_masak'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_perMasak'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_over'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_perOver'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_abnormal'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_jjgKosong'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_perKosongjjg'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_vcut'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['jum_kr'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['total_kr'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['persen_kr'] = 0;

                    // skoring
                    $MtBuahtabAFDbln[$key][$key1][$key2]['skor_mentah'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['skor_masak'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['skor_over'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['skor_jjgKosong'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['skor_vcut'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['skor_abnormal'] = 0;;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['skor_kr'] = 0;
                    $MtBuahtabAFDbln[$key][$key1][$key2]['TOTAL_SKOR'] = 0;
                }
            }
        }
        // dd($MtBuahtabAFDbln);
        $AfdThnMtBuah = array();
        foreach ($MtBuahtabAFDbln as $key => $value) {
            foreach ($value as $key1 => $value1)  if (!empty($value1)) {
                $tph_blok = 0;
                $jjgMth = 0;
                $sampleJJG = 0;
                $jjgAbn = 0;
                $PerMth = 0;
                $PerMsk = 0;
                $PerOver = 0;
                $Perkosongjjg = 0;
                $PerVcut = 0;
                $PerAbr = 0;
                $per_kr = 0;
                $jjgMsk = 0;
                $jjgOver = 0;
                $jjgKosng = 0;
                $vcut = 0;
                $jum_kr = 0;
                $total_kr = 0;
                $totalSkor = 0;
                foreach ($value1 as $key2 => $value2) {
                    // dd($value3);
                    $tph_blok += $value2['tph_baris_blok'];
                    $sampleJJG += $value2['sampleJJG_total'];
                    $jjgMth += $value2['total_mentah'];
                    $jjgMsk += $value2['total_masak'];
                    $jjgOver += $value2['total_over'];
                    $jjgKosng += $value2['total_jjgKosong'];
                    $vcut += $value2['total_vcut'];
                    $jum_kr += $value2['jum_kr'];

                    $jjgAbn += $value2['total_abnormal'];
                }

                if ($jum_kr != 0) {
                    $total_kr = round($tph_blok / $jum_kr, 2);
                } else {
                    $total_kr = 0;
                }

                if ($sampleJJG != 0 && $jjgAbn != 0) {
                    $PerMth = round(($jjgMth / ($sampleJJG - $jjgAbn)) * 100, 2);
                } else {
                    $PerMth = 0;
                }

                if ($sampleJJG != 0 && $jjgAbn != 0) {
                    $PerMsk = round(($jjgMsk / ($sampleJJG - $jjgAbn)) * 100, 2);
                } else {
                    $PerMsk = 0;
                }

                if ($sampleJJG != 0 && $jjgAbn != 0) {
                    $PerOver = round(($jjgOver / ($sampleJJG - $jjgAbn)) * 100, 2);
                } else {
                    $PerOver = 0;
                }

                if ($sampleJJG != 0 && $jjgAbn != 0) {
                    $Perkosongjjg = round(($jjgKosng / ($sampleJJG - $jjgAbn)) * 100, 2);
                } else {
                    $Perkosongjjg = 0;
                }

                if ($sampleJJG != 0) {
                    $PerVcut = round(($vcut / $sampleJJG) * 100, 2);
                } else {
                    $PerVcut = 0;
                }

                if ($sampleJJG != 0) {
                    $PerAbr = round(($jjgAbn / $sampleJJG) * 100, 2);
                } else {
                    $PerAbr = 0;
                }

                $per_kr = round($total_kr * 100, 2);

                // skoring buah mentah
                $skor_PerMth = 0;
                if ($PerMth <= 1.0) {
                    $skor_PerMth = 10;
                } else if ($PerMth >= 1.0 && $PerMth <= 2.0) {
                    $skor_PerMth = 8;
                } else if ($PerMth >= 2.0 && $PerMth <= 3.0) {
                    $skor_PerMth = 6;
                } else if ($PerMth >= 3.0 && $PerMth <= 4.0) {
                    $skor_PerMth = 4;
                } else if ($PerMth >= 4.0 && $PerMth <= 5.0) {
                    $skor_PerMth = 2;
                } else if ($PerMth >= 5.0) {
                    $skor_PerMth = 0;
                }

                // skoring buah masak
                $skor_PerMsk = 0;
                if ($PerMsk <= 75.0) {
                    $skor_PerMsk = 0;
                } else if ($PerMsk >= 75.0 && $PerMsk <= 80.0) {
                    $skor_PerMsk = 1;
                } else if ($PerMsk >= 80.0 && $PerMsk <= 85.0) {
                    $skor_PerMsk = 2;
                } else if ($PerMsk >= 85.0 && $PerMsk <= 90.0) {
                    $skor_PerMsk = 3;
                } else if ($PerMsk >= 90.0 && $PerMsk <= 95.0) {
                    $skor_PerMsk = 4;
                } else if ($PerMsk >= 95.0) {
                    $skor_PerMsk = 5;
                }

                // skoring buah over
                $skor_PerOver = 0;
                if ($PerOver <= 2.0) {
                    $skor_PerOver = 5;
                } else if ($PerOver >= 2.0 && $PerOver <= 4.0) {
                    $skor_PerOver = 4;
                } else if ($PerOver >= 4.0 && $PerOver <= 6.0) {
                    $skor_PerOver = 3;
                } else if ($PerOver >= 6.0 && $PerOver <= 8.0) {
                    $skor_PerOver = 2;
                } else if ($PerOver >= 8.0 && $PerOver <= 10.0) {
                    $skor_PerOver = 1;
                } else if ($PerOver >= 10.0) {
                    $skor_PerOver = 0;
                }


                //skor janjang kosong
                $skor_Perkosongjjg = 0;
                if ($Perkosongjjg <= 1.0) {
                    $skor_Perkosongjjg = 5;
                } else if ($Perkosongjjg >= 1.0 && $Perkosongjjg <= 2.0) {
                    $skor_Perkosongjjg = 4;
                } else if ($Perkosongjjg >= 2.0 && $Perkosongjjg <= 3.0) {
                    $skor_Perkosongjjg = 3;
                } else if ($Perkosongjjg >= 3.0 && $Perkosongjjg <= 4.0) {
                    $skor_Perkosongjjg = 2;
                } else if ($Perkosongjjg >= 4.0 && $Perkosongjjg <= 5.0) {
                    $skor_Perkosongjjg = 1;
                } else if ($Perkosongjjg >= 5.0) {
                    $skor_Perkosongjjg = 0;
                }

                //skore Vcut
                $skor_PerVcut = 0;
                if ($PerVcut <= 2.0) {
                    $skor_PerVcut = 5;
                } else if ($PerVcut >= 2.0 && $PerVcut <= 4.0) {
                    $skor_PerVcut = 4;
                } else if ($PerVcut >= 4.0 && $PerVcut <= 6.0) {
                    $skor_PerVcut = 3;
                } else if ($PerVcut >= 6.0 && $PerVcut <= 8.0) {
                    $skor_PerVcut = 2;
                } else if ($PerVcut >= 8.0 && $PerVcut <= 10.0) {
                    $skor_PerVcut = 1;
                } else if ($PerVcut >= 10.0) {
                    $skor_PerVcut = 0;
                }

                // blum di cek skornya di bawah
                //skore PEnggunnan Brondolan
                $skor_PerAbr = 0;
                if ($PerAbr <= 75.0) {
                    $skor_PerAbr = 0;
                } else if ($PerAbr >= 75.0 && $PerAbr <= 80.0) {
                    $skor_PerAbr = 1;
                } else if ($PerAbr >= 80.0 && $PerAbr <= 85.0) {
                    $skor_PerAbr = 2;
                } else if ($PerAbr >= 85.0 && $PerAbr <= 90.0) {
                    $skor_PerAbr = 3;
                } else if ($PerAbr >= 90.0 && $PerAbr <= 95.0) {
                    $skor_PerAbr = 4;
                } else if ($PerAbr >= 95.0) {
                    $skor_PerAbr = 5;
                }

                $skor_per_kr = 0;
                if ($per_kr <= 60) {
                    $skor_per_kr = 0;
                } else if ($per_kr >= 60 && $per_kr <= 70) {
                    $skor_per_kr = 1;
                } else if ($per_kr >= 70 && $per_kr <= 80) {
                    $skor_per_kr = 2;
                } else if ($per_kr >= 80 && $per_kr <= 90) {
                    $skor_per_kr = 3;
                } else if ($per_kr >= 90 && $per_kr <= 100) {
                    $skor_per_kr = 4;
                } else if ($per_kr >= 100) {
                    $skor_per_kr = 5;
                }

                $totalSkor =  $skor_PerMth + $skor_PerMsk + $skor_PerOver +  $skor_Perkosongjjg + $skor_PerVcut + $skor_PerAbr +  $skor_per_kr;

                $AfdThnMtBuah[$key][$key1]['blok'] = $tph_blok;
                $AfdThnMtBuah[$key][$key1]['sample_jjg'] = $sampleJJG;

                $AfdThnMtBuah[$key][$key1]['jjg_mentah'] = $jjgMth;
                $AfdThnMtBuah[$key][$key1]['mentahPerjjg'] = $PerMth;

                $AfdThnMtBuah[$key][$key1]['jjg_msk'] = $jjgMsk;
                $AfdThnMtBuah[$key][$key1]['mskPerjjg'] = $PerMsk;

                $AfdThnMtBuah[$key][$key1]['jjg_over'] = $jjgOver;
                $AfdThnMtBuah[$key][$key1]['overPerjjg'] = $PerOver;

                $AfdThnMtBuah[$key][$key1]['jjg_kosong'] = $jjgKosng;
                $AfdThnMtBuah[$key][$key1]['kosongPerjjg'] = $Perkosongjjg;

                $AfdThnMtBuah[$key][$key1]['v_cut'] = $vcut;
                $AfdThnMtBuah[$key][$key1]['vcutPerjjg'] = $PerVcut;

                $AfdThnMtBuah[$key][$key1]['jjg_abr'] = $jjgAbn;
                $AfdThnMtBuah[$key][$key1]['krPer'] = $per_kr;

                $AfdThnMtBuah[$key][$key1]['jum_kr'] = $jum_kr;
                $AfdThnMtBuah[$key][$key1]['abrPerjjg'] = $PerAbr;

                $AfdThnMtBuah[$key][$key1]['skor_mentah'] = $skor_PerMth;
                $AfdThnMtBuah[$key][$key1]['skor_msak'] =   $skor_PerMsk;
                $AfdThnMtBuah[$key][$key1]['skor_over'] =  $skor_PerOver;
                $AfdThnMtBuah[$key][$key1]['skor_kosong'] = $skor_Perkosongjjg;
                $AfdThnMtBuah[$key][$key1]['skor_vcut'] = $skor_PerVcut;
                $AfdThnMtBuah[$key][$key1]['skor_karung'] = $skor_per_kr;
                $AfdThnMtBuah[$key][$key1]['skor_abnormal'] = $skor_PerAbr;
                $AfdThnMtBuah[$key][$key1]['totalSkor'] = $totalSkor;
            } else {
                $AfdThnMtBuah[$key][$key1]['blok'] = 0;
                $AfdThnMtBuah[$key][$key1]['sample_jjg'] = 0;

                $AfdThnMtBuah[$key][$key1]['jjg_mentah'] = 0;
                $AfdThnMtBuah[$key][$key1]['mentahPerjjg'] = 0;

                $AfdThnMtBuah[$key][$key1]['jjg_msk'] = 0;
                $AfdThnMtBuah[$key][$key1]['mskPerjjg'] = 0;

                $AfdThnMtBuah[$key][$key1]['jjg_over'] = 0;
                $AfdThnMtBuah[$key][$key1]['overPerjjg'] = 0;

                $AfdThnMtBuah[$key][$key1]['jjg_kosong'] = 0;
                $AfdThnMtBuah[$key][$key1]['kosongPerjjg'] = 0;

                $AfdThnMtBuah[$key][$key1]['v_cut'] = 0;
                $AfdThnMtBuah[$key][$key1]['vcutPerjjg'] = 0;

                $AfdThnMtBuah[$key][$key1]['jjg_abr'] = 0;
                $AfdThnMtBuah[$key][$key1]['krPer'] = 0;

                $AfdThnMtBuah[$key][$key1]['jum_kr'] = 0;
                $AfdThnMtBuah[$key][$key1]['abrPerjjg'] = 0;

                $AfdThnMtBuah[$key][$key1]['skor_mentah'] = 0;
                $AfdThnMtBuah[$key][$key1]['skor_msak'] =  0;
                $AfdThnMtBuah[$key][$key1]['skor_over'] = 0;
                $AfdThnMtBuah[$key][$key1]['skor_kosong'] = 0;
                $AfdThnMtBuah[$key][$key1]['skor_vcut'] = 0;
                $AfdThnMtBuah[$key][$key1]['skor_karung'] = 0;
                $AfdThnMtBuah[$key][$key1]['skor_abnormal'] = 0;
                $AfdThnMtBuah[$key][$key1]['totalSkor'] = 0;
            }
        }


        // dd($AfdThnMtBuah );
        //perhitungan mutu trans untuk afd perbulan untuk tabel paling akhir mencari tahun
        $MttranstabAFDbln = array();
        foreach ($defTransAFDtab as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2)  if (is_array($value2)) {
                    $sum_bt = 0;
                    $sum_rst = 0;
                    $brdPertph = 0;
                    $buahPerTPH = 0;
                    $totalSkor = 0;
                    $dataBLok = 0;
                    $combination_counts = array();
                    foreach ($value2 as $key3 => $value3) {
                        // dd($value4);
                        $combination = $value3['blok'] . ' ' . $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['tph_baris'];
                        if (!isset($combination_counts[$combination])) {
                            $combination_counts[$combination] = 0;
                        }
                        $combination_counts[$combination]++;
                        $sum_bt += $value3['bt'];
                        $sum_rst += $value3['rst'];
                    }
                    $dataBLok = count($combination_counts);
                    $brdPertph = round($sum_bt / $dataBLok, 2);
                    $buahPerTPH = round($sum_rst / $dataBLok, 2);

                    //menghitung skor butir
                    $skor_brdPertph = 0;
                    if ($brdPertph <= 3) {
                        $skor_brdPertph = 10;
                    } else if ($brdPertph >= 3 && $brdPertph <= 5) {
                        $skor_brdPertph = 8;
                    } else if ($brdPertph >= 5 && $brdPertph <= 7) {
                        $skor_brdPertph = 6;
                    } else if ($brdPertph >= 7 && $brdPertph <= 9) {
                        $skor_brdPertph = 4;
                    } else if ($brdPertph >= 9 && $brdPertph <= 11) {
                        $skor_brdPertph = 2;
                    } else if ($brdPertph >= 11) {
                        $skor_brdPertph = 0;
                    }
                    //menghitung Skor Restant
                    $skor_buahPerTPH = 0;
                    if ($buahPerTPH <= 0.0) {
                        $skor_buahPerTPH = 10;
                    } else if ($buahPerTPH >= 0.0 && $buahPerTPH <= 0.5) {
                        $skor_buahPerTPH = 8;
                    } else if ($buahPerTPH >= 0.5 && $buahPerTPH <= 1) {
                        $skor_buahPerTPH = 6;
                    } else if ($buahPerTPH >= 1.0 && $buahPerTPH <= 1.5) {
                        $skor_buahPerTPH = 4;
                    } else if ($buahPerTPH >= 1.5 && $buahPerTPH <= 2.0) {
                        $skor_buahPerTPH = 2;
                    } else if ($buahPerTPH >= 2.0 && $buahPerTPH <= 2.5) {
                        $skor_buahPerTPH = 0;
                    } else if ($buahPerTPH >= 2.5 && $buahPerTPH <= 3.0) {
                        $skor_buahPerTPH = 2;
                    } else if ($buahPerTPH >= 3.0 && $buahPerTPH <= 3.5) {
                        $skor_buahPerTPH = 4;
                    } else if ($buahPerTPH >= 3.5 && $buahPerTPH <= 4.0) {
                        $skor_buahPerTPH = 6;
                    } else if ($buahPerTPH >= 4.0) {
                        $skor_buahPerTPH = 8;
                    }

                    $totalSkor = $skor_buahPerTPH + $skor_brdPertph;

                    $MttranstabAFDbln[$key][$key1][$key2]['tph_sample'] = $dataBLok;
                    $MttranstabAFDbln[$key][$key1][$key2]['total_brd'] = $sum_bt;
                    $MttranstabAFDbln[$key][$key1][$key2]['total_brd/TPH'] = $brdPertph;
                    $MttranstabAFDbln[$key][$key1][$key2]['total_buah'] = $sum_rst;
                    $MttranstabAFDbln[$key][$key1][$key2]['total_buahPerTPH'] = $buahPerTPH;
                    $MttranstabAFDbln[$key][$key1][$key2]['skor_brdPertph'] = $skor_brdPertph;
                    $MttranstabAFDbln[$key][$key1][$key2]['skor_buahPerTPH'] = $skor_buahPerTPH;
                    $MttranstabAFDbln[$key][$key1][$key2]['totalSkor'] = $totalSkor;
                } else {
                    $MttranstabAFDbln[$key][$key1][$key2]['tph_sample'] = 0;
                    $MttranstabAFDbln[$key][$key1][$key2]['total_brd'] = 0;
                    $MttranstabAFDbln[$key][$key1][$key2]['total_brd/TPH'] = 0;
                    $MttranstabAFDbln[$key][$key1][$key2]['total_buah'] = 0;
                    $MttranstabAFDbln[$key][$key1][$key2]['total_buahPerTPH'] = 0;
                    $MttranstabAFDbln[$key][$key1][$key2]['skor_brdPertph'] = 0;
                    $MttranstabAFDbln[$key][$key1][$key2]['skor_buahPerTPH'] = 0;
                    $MttranstabAFDbln[$key][$key1][$key2]['totalSkor'] = 0;
                }
            }
        }
        // dd($MttranstabAFDbln);
        //perhitungan untuk tahun di afd perbulan unutk mutu trans
        $AfdThnMtTrans = array();
        foreach ($MttranstabAFDbln as $key => $value) {
            foreach ($value as $key1 => $value1)  if (!empty($value1)) {
                $total_sample = 0;
                $total_brd = 0;
                $total_buah = 0;
                $brdPertph = 0;
                $buahPerTPH = 0;
                $totalSkor = 0;
                foreach ($value1 as $key2 => $value2) {
                    // dd($value3);
                    $total_sample += $value2['tph_sample'];
                    $total_brd += $value2['total_brd'];
                    $total_buah += $value2['total_buah'];
                }

                if ($total_sample != 0) {
                    $brdPertph = round($total_brd / $total_sample, 2);
                } else {
                    $brdPertph = 0;
                }

                if ($total_sample != 0) {
                    $buahPerTPH = round($total_buah / $total_sample, 2);
                } else {
                    $buahPerTPH = 0;
                }

                $skor_brdPertph = 0;
                if ($brdPertph <= 3) {
                    $skor_brdPertph = 10;
                } else if ($brdPertph >= 3 && $brdPertph <= 5) {
                    $skor_brdPertph = 8;
                } else if ($brdPertph >= 5 && $brdPertph <= 7) {
                    $skor_brdPertph = 6;
                } else if ($brdPertph >= 7 && $brdPertph <= 9) {
                    $skor_brdPertph = 4;
                } else if ($brdPertph >= 9 && $brdPertph <= 11) {
                    $skor_brdPertph = 2;
                } else if ($brdPertph >= 11) {
                    $skor_brdPertph = 0;
                }


                $skor_buahPerTPH = 0;
                if ($buahPerTPH <= 0.0) {
                    $skor_buahPerTPH = 10;
                } else if ($buahPerTPH >= 0.0 && $buahPerTPH <= 0.5) {
                    $skor_buahPerTPH = 8;
                } else if ($buahPerTPH >= 0.5 && $buahPerTPH <= 1) {
                    $skor_buahPerTPH = 6;
                } else if ($buahPerTPH >= 1.0 && $buahPerTPH <= 1.5) {
                    $skor_buahPerTPH = 4;
                } else if ($buahPerTPH >= 1.5 && $buahPerTPH <= 2.0) {
                    $skor_buahPerTPH = 2;
                } else if ($buahPerTPH >= 2.0 && $buahPerTPH <= 2.5) {
                    $skor_buahPerTPH = 0;
                } else if ($buahPerTPH >= 2.5 && $buahPerTPH <= 3.0) {
                    $skor_buahPerTPH = 2;
                } else if ($buahPerTPH >= 3.0 && $buahPerTPH <= 3.5) {
                    $skor_buahPerTPH = 4;
                } else if ($buahPerTPH >= 3.5 && $buahPerTPH <= 4.0) {
                    $skor_buahPerTPH = 6;
                } else if ($buahPerTPH >= 4.0) {
                    $skor_buahPerTPH = 8;
                }



                $totalSkor = $skor_buahPerTPH + $skor_brdPertph;

                $AfdThnMtTrans[$key][$key1]['total_sampleEST'] = $total_sample;
                $AfdThnMtTrans[$key][$key1]['total_brdEST'] = $total_brd;
                $AfdThnMtTrans[$key][$key1]['total_brdPertphEST'] = $brdPertph;
                $AfdThnMtTrans[$key][$key1]['total_buahEST'] = $total_buah;
                $AfdThnMtTrans[$key][$key1]['total_buahPertphEST'] = $buahPerTPH;
                $AfdThnMtTrans[$key][$key1]['skor_brd'] = $skor_brdPertph;
                $AfdThnMtTrans[$key][$key1]['skor_buah'] = $skor_buahPerTPH;
                $AfdThnMtTrans[$key][$key1]['total_skor'] = $totalSkor;
            } else {
                $AfdThnMtTrans[$key][$key1]['total_sampleEST'] = 0;
                $AfdThnMtTrans[$key][$key1]['total_brdEST'] = 0;
                $AfdThnMtTrans[$key][$key1]['total_brdPertphEST'] = 0;
                $AfdThnMtTrans[$key][$key1]['total_buahEST'] = 0;
                $AfdThnMtTrans[$key][$key1]['total_buahPertphEST'] = 0;
                $AfdThnMtTrans[$key][$key1]['skor_brd'] = 0;
                $AfdThnMtTrans[$key][$key1]['skor_buah'] = 0;
                $AfdThnMtTrans[$key][$key1]['total_skor'] = 0;
            }
        }

        //perhitungan mutu ancak untuk afd perbulan unutk tabel paling akhir untuk mencari tahun
        $MtancaktabAFDbln = array();
        foreach ($defaultTabAFD as $key => $value) {
            foreach ($value as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) if (!empty($value2)) {
                    $listBlokPerAfd = array();
                    $blok = 0;
                    $akp = 0;
                    $skor_bTinggal = 0;
                    $brdPerjjg = 0;
                    $pokok_panen = 0;
                    $janjang_panen = 0;
                    $p_panen = 0;
                    $k_panen = 0;
                    $bhts_panen  = 0;
                    $bhtm1_panen  = 0;
                    $bhtm2_panen  = 0;
                    $bhtm3_oanen  = 0;
                    $ttlSkorMA = 0;
                    $jum_ha = 0;
                    $pelepah_s = 0;
                    foreach ($value2 as $key3 => $value3) {
                        if (!in_array($value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'], $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $value3['estate'] . ' ' . $value3['afdeling'] . ' ' . $value3['blok'];
                        }
                        $jum_ha = count($listBlokPerAfd);
                        $pokok_panen = json_decode($value3["pokok_dipanen"], true);
                        $jajang_panen = json_decode($value3["jjg_dipanen"], true);
                        $brtp = json_decode($value3["brtp"], true);
                        $brtk = json_decode($value3["brtk"], true);
                        $brtgl = json_decode($value3["brtgl"], true);

                        $pokok_panen  = count($pokok_panen);
                        $janjang_panen = array_sum($jajang_panen);
                        $p_panen = array_sum($brtp);
                        $k_panen = array_sum($brtk);
                        $brtgl_panen = array_sum($brtgl);

                        $bhts = json_decode($value3["bhts"], true);
                        $bhtm1 = json_decode($value3["bhtm1"], true);
                        $bhtm2 = json_decode($value3["bhtm2"], true);
                        $bhtm3 = json_decode($value3["bhtm3"], true);


                        $bhts_panen = array_sum($bhts);
                        $bhtm1_panen = array_sum($bhtm1);
                        $bhtm2_panen = array_sum($bhtm2);
                        $bhtm3_oanen = array_sum($bhtm3);
                        $ps = json_decode($value3["ps"], true);
                        $pelepah_s = array_sum($ps);
                    }
                    $blok = $jum_ha;
                    // $akp = ($janjang_panen / $pokok_panen) %
                    $akp = ($janjang_panen / $pokok_panen) * 100;
                    $skor_bTinggal = $p_panen + $k_panen + $brtgl_panen;
                    $brdPerjjg = $skor_bTinggal / $pokok_panen;

                    //skore PEnggunnan Brondolan
                    $skor_brdPerjjg = 0;
                    if ($brdPerjjg <= 1.0) {
                        $skor_brdPerjjg = 20;
                    } else if ($brdPerjjg >= 1.5 && $brdPerjjg <= 2.0) {
                        $skor_brdPerjjg = 12;
                    } else if ($brdPerjjg >= 2.0 && $brdPerjjg <= 2.5) {
                        $skor_brdPerjjg = 8;
                    } else if ($brdPerjjg >= 2.5 && $brdPerjjg <= 3.0) {
                        $skor_brdPerjjg = 4;
                    } else if ($brdPerjjg >= 3.0 && $brdPerjjg <= 3.5) {
                        $skor_brdPerjjg = 0;
                    } else if ($brdPerjjg >= 4.0 && $brdPerjjg <= 4.5) {
                        $skor_brdPerjjg = 8;
                    } else if ($brdPerjjg >=  4.5 && $brdPerjjg <= 5.0) {
                        $skor_brdPerjjg = 12;
                    } else if ($brdPerjjg >=  5.0) {
                        $skor_brdPerjjg = 16;
                    }

                    // bagian buah tinggal


                    $sumBH = $bhts_panen +  $bhtm1_panen +  $bhtm2_panen +  $bhtm3_oanen;

                    $sumPerBH = $sumBH / ($janjang_panen + $sumBH) * 100;

                    $skor_bh = 0;
                    if ($sumPerBH <=  0.0) {
                        $skor_bh = 20;
                    } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                        $skor_bh = 18;
                    } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                        $skor_bh = 16;
                    } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                        $skor_bh = 12;
                    } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                        $skor_bh = 8;
                    } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                        $skor_bh = 4;
                    } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                        $skor_bh = 0;
                    } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                        $skor_bh = 0;
                    } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                        $skor_bh = 4;
                    } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                        $skor_bh = 8;
                    } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                        $skor_bh = 12;
                    } else if ($sumPerBH >= 5.0) {
                        $skor_bh = 10;
                    }
                    // data untuk pelepah sengklek



                    if ($pelepah_s != 0) {
                        $perPl = ($pokok_panen / $pelepah_s) * 100;
                    } else {
                        $perPl = 0;
                    }
                    $skor_perPl = 0;
                    if ($perPl <=  0.5) {
                        $skor_perPl = 5;
                    } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                        $skor_perPl = 4;
                    } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                        $skor_perPl = 3;
                    } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                        $skor_perPl = 2;
                    } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                        $skor_perPl = 1;
                    } else if ($perPl >= 2.5) {
                        $skor_perPl = 0;
                    }

                    $ttlSkorMA = $skor_brdPerjjg + $skor_bh + $skor_perPl;

                    // $MtancaktabAFDbln[$key][$key1][$key2]['blok'] = $blok;

                    $MtancaktabAFDbln[$key][$key1][$key2]['pokok_sample'] = $pokok_panen;
                    $MtancaktabAFDbln[$key][$key1][$key2]['ha_sample'] = $blok;
                    $MtancaktabAFDbln[$key][$key1][$key2]['jumlah_panen'] = $janjang_panen;
                    $MtancaktabAFDbln[$key][$key1][$key2]['akp_rl'] =  number_format($akp, 2);

                    $MtancaktabAFDbln[$key][$key1][$key2]['p'] = $p_panen;
                    $MtancaktabAFDbln[$key][$key1][$key2]['k'] = $k_panen;
                    $MtancaktabAFDbln[$key][$key1][$key2]['tgl'] = $skor_bTinggal;

                    // $MtancaktabAFDbln[$key][$key1][$key2]['total_brd'] = $skor_bTinggal;
                    $MtancaktabAFDbln[$key][$key1][$key2]['brd/jjg'] = number_format($brdPerjjg, 2);

                    // data untuk buah tinggal
                    $MtancaktabAFDbln[$key][$key1][$key2]['bhts_s'] = $bhts_panen;
                    $MtancaktabAFDbln[$key][$key1][$key2]['bhtm1'] = $bhtm1_panen;
                    $MtancaktabAFDbln[$key][$key1][$key2]['bhtm2'] = $bhtm2_panen;
                    $MtancaktabAFDbln[$key][$key1][$key2]['bhtm3'] = $bhtm3_oanen;


                    // $MtancaktabAFDbln[$key][$key1][$key2]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek

                    $MtancaktabAFDbln[$key][$key1][$key2]['palepah_pokok'] = $pelepah_s;
                    // total skor akhir
                    $MtancaktabAFDbln[$key][$key1][$key2]['skor_bh'] = $skor_bh;
                    $MtancaktabAFDbln[$key][$key1][$key2]['skor_brd'] = $skor_brdPerjjg;
                    $MtancaktabAFDbln[$key][$key1][$key2]['skor_ps'] = $skor_perPl;
                    $MtancaktabAFDbln[$key][$key1][$key2]['skor_akhir'] = $ttlSkorMA;
                } else {
                    $MtancaktabAFDbln[$key][$key1][$key2]['pokok_sample'] = 0;
                    $MtancaktabAFDbln[$key][$key1][$key2]['ha_sample'] = 0;
                    $MtancaktabAFDbln[$key][$key1][$key2]['jumlah_panen'] = 0;
                    $MtancaktabAFDbln[$key][$key1][$key2]['akp_rl'] =  0;

                    $MtancaktabAFDbln[$key][$key1][$key2]['p'] = 0;
                    $MtancaktabAFDbln[$key][$key1][$key2]['k'] = 0;
                    $MtancaktabAFDbln[$key][$key1][$key2]['tgl'] = 0;

                    // $MtancaktabAFDbln[$key][$key1][$key2]['total_brd'] = $skor_bTinggal;
                    $MtancaktabAFDbln[$key][$key1][$key2]['brd/jjg'] = 0;

                    // data untuk buah tinggal
                    $MtancaktabAFDbln[$key][$key1][$key2]['bhts_s'] = 0;
                    $MtancaktabAFDbln[$key][$key1][$key2]['bhtm1'] = 0;
                    $MtancaktabAFDbln[$key][$key1][$key2]['bhtm2'] = 0;
                    $MtancaktabAFDbln[$key][$key1][$key2]['bhtm3'] = 0;


                    // $MtancaktabAFDbln[$key][$key1][$key2]['jjgperBuah'] = number_format($sumPerBH, 2);
                    // data untuk pelepah sengklek

                    $MtancaktabAFDbln[$key][$key1][$key2]['palepah_pokok'] = 0;
                    // total skor akhir
                    $MtancaktabAFDbln[$key][$key1][$key2]['skor_bh'] = 0;
                    $MtancaktabAFDbln[$key][$key1][$key2]['skor_brd'] = 0;
                    $MtancaktabAFDbln[$key][$key1][$key2]['skor_ps'] = 0;
                    $MtancaktabAFDbln[$key][$key1][$key2]['skor_akhir'] = 0;
                }
            }
        }
        // dd($MtancaktabAFDbln);
        //mutu ancak hitung perthaun untuk tab afd tahunan
        $AfdThnMtAncak = array();
        foreach ($MtancaktabAFDbln as $key => $value) {
            foreach ($value as $key1 => $value1) if (!empty($value1)) {
                $total_brd = 0;
                $total_buah = 0;
                $total_skor = 0;
                $sum_p = 0;
                $sum_k = 0;
                $sum_gl = 0;
                $sum_panen = 0;
                $total_BrdperJJG = 0;
                $sum_pokok = 0;
                $sum_Restan = 0;
                $sum_s = 0;
                $sum_m1 = 0;
                $sum_m2 = 0;
                $sum_m3 = 0;
                $sumPerBH = 0;
                $sum_pelepah = 0;
                $perPl = 0;
                foreach ($value1 as $key2 => $value2) {
                    $sum_panen += $value2['jumlah_panen'];
                    $sum_pokok += $value2['pokok_sample'];
                    //brondolamn
                    $sum_p += $value2['p'];
                    $sum_k += $value2['k'];
                    $sum_gl += $value2['tgl'];
                    //buah tianggal
                    $sum_s += $value2['bhts_s'];
                    $sum_m1 += $value2['bhtm1'];
                    $sum_m2 += $value2['bhtm2'];
                    $sum_m3 += $value2['bhtm3'];
                    //pelepah
                    $sum_pelepah += $value2['palepah_pokok'];
                }

                $total_brd = $sum_p + $sum_k + $sum_gl;
                $total_buah = $sum_s + $sum_m1 + $sum_m2 + $sum_m3;
                // $persenPalepah = $sum_palepah/$sum_pokok 

                if ($sum_panen != 0) {
                    $total_BrdperJJG = round($total_brd / $sum_panen, 2);
                } else {
                    $total_BrdperJJG = 0;
                }

                if ($sum_panen != 0) {
                    $sumPerBH = round($total_buah / ($sum_panen + $total_buah) * 100, 2);
                } else {
                    $sumPerBH = 0;
                }

                if ($sum_pelepah != 0) {
                    $perPl = round(($sum_pokok / $sum_pelepah) * 100, 2);
                } else {
                    $perPl = 0;
                }
                $skor_brdPerjjg = 0;
                $skor_perPl = 0;
                $skor_bh = 0;
                if ($total_BrdperJJG <= 1.0) {
                    $skor_brdPerjjg = 20;
                } else if ($total_BrdperJJG >= 1.5 && $total_BrdperJJG <= 2.0) {
                    $skor_brdPerjjg = 12;
                } else if ($total_BrdperJJG >= 2.0 && $total_BrdperJJG <= 2.5) {
                    $skor_brdPerjjg = 8;
                } else if ($total_BrdperJJG >= 2.5 && $total_BrdperJJG <= 3.0) {
                    $skor_brdPerjjg = 4;
                } else if ($total_BrdperJJG >= 3.0 && $total_BrdperJJG <= 3.5) {
                    $skor_brdPerjjg = 0;
                } else if ($total_BrdperJJG >= 4.0 && $total_BrdperJJG <= 4.5) {
                    $skor_brdPerjjg = 8;
                } else if ($total_BrdperJJG >=  4.5 && $total_BrdperJJG <= 5.0) {
                    $skor_brdPerjjg = 12;
                } else if ($total_BrdperJJG >=  5.0) {
                    $skor_brdPerjjg = 16;
                }


                if ($sumPerBH <=  0.0) {
                    $skor_bh = 20;
                } else if ($sumPerBH >=  0.0 && $sumPerBH <= 1.0) {
                    $skor_bh = 18;
                } else if ($sumPerBH >= 1 && $sumPerBH <= 1.5) {
                    $skor_bh = 16;
                } else if ($sumPerBH >= 1.5 && $sumPerBH <= 2.0) {
                    $skor_bh = 12;
                } else if ($sumPerBH >= 2.0 && $sumPerBH <= 2.5) {
                    $skor_bh = 8;
                } else if ($sumPerBH >= 2.5 && $sumPerBH <= 3.0) {
                    $skor_bh = 4;
                } else if ($sumPerBH >= 3.0 && $sumPerBH <= 3.5) {
                    $skor_bh = 0;
                } else if ($sumPerBH >=  3.5 && $sumPerBH <= 3.5) {
                    $skor_bh = 0;
                } else if ($sumPerBH >= 3.5 && $sumPerBH <= 4.0) {
                    $skor_bh = 4;
                } else if ($sumPerBH >= 4.0 && $sumPerBH <= 4.5) {
                    $skor_bh = 8;
                } else if ($sumPerBH >= 4.5 && $sumPerBH <= 5.0) {
                    $skor_bh = 12;
                } else if ($sumPerBH >= 5.0) {
                    $skor_bh = 10;
                }


                if ($perPl <=  0.5) {
                    $skor_perPl = 5;
                } else if ($perPl >=  0.5 && $perPl <= 1.0) {
                    $skor_perPl = 4;
                } else if ($perPl >= 1.0 && $perPl <= 1.5) {
                    $skor_perPl = 3;
                } else if ($perPl >= 1.5 && $perPl <= 2.0) {
                    $skor_perPl = 2;
                } else if ($perPl >= 2.0 && $perPl <= 2.5) {
                    $skor_perPl = 1;
                } else if ($perPl >= 2.5) {
                    $skor_perPl = 0;
                }

                $total_skor = $skor_brdPerjjg + $skor_bh + $skor_perPl;


                $AfdThnMtAncak[$key][$key1]['total_p.k.gl'] = $total_brd;
                $AfdThnMtAncak[$key][$key1]['total_jumPanen'] = $sum_panen;
                $AfdThnMtAncak[$key][$key1]['total_jumPokok'] = $sum_pokok;
                $AfdThnMtAncak[$key][$key1]['total_brd/jjg'] = $total_BrdperJJG;
                $AfdThnMtAncak[$key][$key1]['skor_brd'] = $skor_brdPerjjg;
                $AfdThnMtAncak[$key][$key1]['s'] = $sum_s;
                $AfdThnMtAncak[$key][$key1]['m1'] = $sum_m1;
                $AfdThnMtAncak[$key][$key1]['m2'] = $sum_m2;
                $AfdThnMtAncak[$key][$key1]['m3'] = $sum_m3;
                $AfdThnMtAncak[$key][$key1]['total_bh'] = $total_buah;
                $AfdThnMtAncak[$key][$key1]['total_bh/jjg'] = $sumPerBH;
                $AfdThnMtAncak[$key][$key1]['skor_bh'] = $skor_bh;
                $AfdThnMtAncak[$key][$key1]['pokok_palepah'] = $sum_pelepah;
                $AfdThnMtAncak[$key][$key1]['perPalepah'] = $perPl;
                $AfdThnMtAncak[$key][$key1]['skor_perPl'] = $skor_perPl;
                //total skor akhir
                $AfdThnMtAncak[$key][$key1]['skor_final'] = $total_skor;
            } else {
                $AfdThnMtAncak[$key][$key1]['total_p.k.gl'] = 0;
                $AfdThnMtAncak[$key][$key1]['total_jumPanen'] = 0;
                $AfdThnMtAncak[$key][$key1]['total_jumPokok'] = 0;
                $AfdThnMtAncak[$key][$key1]['total_brd/jjg'] = 0;
                $AfdThnMtAncak[$key][$key1]['skor_brd'] = 0;
                $AfdThnMtAncak[$key][$key1]['s'] = 0;
                $AfdThnMtAncak[$key][$key1]['m1'] = 0;
                $AfdThnMtAncak[$key][$key1]['m2'] = 0;
                $AfdThnMtAncak[$key][$key1]['m3'] = 0;
                $AfdThnMtAncak[$key][$key1]['total_bh'] = 0;
                $AfdThnMtAncak[$key][$key1]['total_bh/jjg'] = 0;
                $AfdThnMtAncak[$key][$key1]['skor_bh'] = 0;
                $AfdThnMtAncak[$key][$key1]['pokok_palepah'] = 0;
                $AfdThnMtAncak[$key][$key1]['perPalepah'] = 0;
                $AfdThnMtAncak[$key][$key1]['skor_perPl'] = 0;
                //total skor akhir
                $AfdThnMtAncak[$key][$key1]['skor_final'] = 0;
            }
        }

        // dd($AfdThnMtAncak);

        $RekapTahunAFD = array();
        foreach ($AfdThnMtAncak  as $key => $value) {
            foreach ($value  as $key1 => $value1) {
                foreach ($AfdThnMtTrans  as $key2 => $value2) {
                    foreach ($value2  as $key3 => $value3) {
                        foreach ($AfdThnMtBuah  as $key4 => $value4) {
                            foreach ($value4  as $key5 => $value5) {
                                if ($key == $key2 && $key2 == $key4) {
                                    $RekapTahunAFD[$key][$key1]['tahun_skorwil'] = $value1['skor_final'] + $value3['total_skor'] + $value5['totalSkor'];
                                }
                            }
                        }
                    }
                }
            }
        }
        // dd($RekapTahunAFD);


        //pentotalan skor mt ancak mt transprt mt buah
        $RekapBulanwil = array();
        foreach ($mtBuahAllEst as $key => $value) {
            foreach ($value as $key2  => $value2) {
                foreach ($mtTranstAllbln as $key3 => $value3) {
                    foreach ($value3 as $key4 => $value4) {
                        foreach ($bulanAllEST as $key5 => $value5) {
                            foreach ($value5 as $key6 => $value6)
                                if ($key == $key3 && $key3 == $key5) {
                                    $RekapBulanwil[$key][$key2]['skor_bulanTotal'] = $value2['TOTAL_SKOR'] + $value4['totalSkor'] + $value6['total_skor'];
                                }
                        }
                    }
                }
            }
        }
        // dd($RekapBulanwil);
        // dd($WilMtAncakThn);
        $RekapTahunwil = array();
        foreach ($WilMtAncakThn as $key => $value) {
            foreach ($mtTransTahun as $key2 => $value2) {
                foreach ($mtBuahTahunall as $key3 => $value3) {
                    if ($key == $key2 && $key2 == $key3) {
                        $RekapTahunwil[$key]['tahun_skorwil'] = $value['total_skor'] + $value2['totalSkor'] + $value3['TOTAL_SKOR'];
                    }
                }
            }
        }
        // dd($RekapTahunwil);
        // dd($RegMTbuahBln, $RegMTtransBln, $RegMTancakBln);
        //rekap bulan regional 1
        $RekapBulanReg = array();
        foreach ($RegMTbuahBln as $key => $value) {
            foreach ($RegMTtransBln as $key1 => $value2) {
                foreach ($RegMTancakBln as $key2 => $value3) {
                    if ($key == $key1 && $key1 == $key2)
                        $RekapBulanReg[$key]['skor_bulanTotal'] = $value['TOTAL_SKOR'] + $value2['totalSkor'] + $value3['total_skor'];
                }
            }
        }
        // dd($RekapBulanReg);
        //rekap tahun regional 1
        $RekapTahunReg = array();
        foreach ($RegMTanckTHn as $key => $value) {
            foreach ($RegMTtransTHn as $key2 => $value2) {
                foreach ($RegMTbuahTHn as $key3 => $value3) {
                    if ($key == $key2 && $key2 == $key3) {
                        $RekapTahunReg[$key]['tahun_skorwil'] = $value['total_skor'] + $value2['totalSkor'] + $value3['TOTAL_SKOR'];
                    }
                }
            }
        }
        // dd($RegMTanckTHn, $RegMTtransTHn,$RegMTbuahTHn);
        // dd($mtBuahAFDbln);

        // rekap untuk table perfadling table tarakhir
        $RekapBulanAFD = array();

        // $RekapBulanAFD = array();
        // dd($mutuTransAFD);
        foreach ($mutuTransAFD as $mtKey => $mtValue) {
            foreach ($bulananBh as $bbKey => $bbValue) {
                foreach ($dataTahunEst as $dteKey => $dteValue) {
                    if ($mtKey == $bbKey && $bbKey == $dteKey) {
                        foreach ($mtValue as $mtKey1 => $mtValue1) {
                            foreach ($bbValue as $bbKey1 => $bbValue1) {
                                foreach ($dteValue as $dteKey1 => $dteValue1) {
                                    if ($mtKey1 == $bbKey1 && $bbKey1 == $dteKey1) {
                                        foreach ($mtValue1 as $mtKey2 => $mtValue2) {
                                            foreach ($bbValue1 as $bbKey2 => $bbValue2) {
                                                foreach ($dteValue1 as $dteKey2 => $dteValue2) {
                                                    if ($mtKey2 == $bbKey2 && $bbKey2 == $dteKey2) {
                                                        $RekapBulanAFD[$mtKey][$mtKey1][$mtKey2]['bulan_afd'] = intval($mtValue2['totalSkor'] + $bbValue2['TOTAL_SKOR'] + $dteValue2['skor_akhir']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // dd($RekapBulanAFD);
        // //end

        $arrView = array();
        $arrView['list_estate'] =  $queryEsta;
        $arrView['RekapBulan'] =  $RekapBulan;
        $arrView['RekapTahun'] =  $RekapTahun;
        $arrView['value_tblWIl'] =  $TotalperEstate;
        $arrView['value_tblEST'] =  $dataPerWil;
        $arrView['chart_brd'] = $chartBTT;
        $arrView['chart_buah'] = $chartBuahTT;
        $arrView['chart_brdwil'] =  $chartPerwil;
        $arrView['chart_buahwil'] = $buahPerwil;
        $arrView['table_utama'] = $dataPerWil;
        $arrView['FinalTahun'] = $FinalTahun;
        $arrView['Final_end'] = $Final_end;
        $arrView['RekapBulanwil'] = $RekapBulanwil;
        $arrView['RekapTahunwil'] = $RekapTahunwil;
        $arrView['RekapBulanReg'] = $RekapBulanReg;
        $arrView['RekapTahunReg'] = $RekapTahunReg;
        $arrView['RekapBulanAFD'] = $RekapBulanAFD;
        $arrView['RekapTahunAFD'] = $RekapTahunAFD;

        // dd($arrView);

        // dd($FinalTahun);
        echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
        exit();
    }
}