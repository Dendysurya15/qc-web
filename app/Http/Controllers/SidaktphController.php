<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class SidaktphController extends Controller
{

    //
    public $search;
    public function index(Request $request)
    {
        $queryEst = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->pluck('est');

        $queryEste = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        $queryEste = $queryEste->groupBy(function ($item) {
            return $item->wil;
        });

        $queryEste = json_decode($queryEste, true);

        // dd($queryEste);
        $queryAfd = DB::connection('mysql2')->Table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'

            ) //buat mengambil data di estate db dan willayah db

            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();

        $queryAfd = json_decode($queryAfd, true);
        // dd($queryAfd);



        $querySidak = DB::connection('mysql2')->table('sidak_tph')
            ->whereBetween('sidak_tph.datetime', ['2023-01-22', '2023-01-29'])
            ->get();
        $querySidak = json_decode($querySidak, true);

        $dataAfdEst = array();
        // menyimpan array nested dari  wil -> est -> afd
        foreach ($queryEste as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($queryAfd as $key3 => $value3) {
                    if ($value2['est'] == $value3['est']) {
                        foreach ($querySidak as $key4 => $value4) {
                            if (($value2['est'] == $value4['est']) && ($value3['nama'] == $value4['afd'])) {
                                if (!isset($dataAfdEst[$value2['est']][$value3['nama']])) {
                                    $dataAfdEst[$value2['est']][$value3['nama']] = array();
                                }
                                $dataAfdEst[$value2['est']][$value3['nama']][] = $value4;
                            }
                        }
                    }
                }
            }
        }

        foreach ($dataAfdEst as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if (empty($value2)) {
                    unset($dataAfdEst[$key][$key2]);
                }
            }
            if (empty($dataAfdEst[$key])) {
                unset($dataAfdEst[$key]);
            }
        }

        // dd($dataSkorAkhirPerWil);
        $queryWill = DB::connection('mysql2')->table('wil')->whereIn('regional', [1])->pluck('nama');
        return view(
            'dashboardtph',
            ['list_estate' => $queryEst],
            ['list_wilayah' => $queryWill],
        );
    }

    // chart ajax brondolan tinggal dan pencarian berdasarkan minggu
    public function getBtTph(Request $request)
    {
        $queryWill = DB::connection('mysql2')->table('wil')->whereIn('regional', [1])->get();;
        // dd($queryWill);
        // dapatkan data estate dari table estate dengan wilayah 1 , 2 , 3
        $queryEst = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        // dd($queryEst);
        $queryEste = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        $queryEste = $queryEste->groupBy(function ($item) {
            return $item->wil;
        });

        $queryEste = json_decode($queryEste, true);

        // dd($queryEst);
        $queryAfd = DB::connection('mysql2')->Table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'

            ) //buat mengambil data di estate db dan willayah db

            ->join('estate', 'estate.id', '=', 'afdeling.estate') //kemudian di join untuk mengambil est perwilayah
            ->get();

        $queryAfd = json_decode($queryAfd, true);

        //array untuk tampung nilai bt tph per estate dari table bt_jalan & bt_tph dll
        $arrBtTPHperEst = array(); //table dari brondolan di buat jadi array agar bisa di parse ke json
        $arrKRest = array(); //table dari jum_jkarung di buat jadi array agar bisa di parse ke json
        $arrBHest = array(); //table dari Buah di buat jadi array agar bisa di parse ke json
        $arrRSest = array(); //table array untuk buah restant tidak di laporkan

        ///array untuk table nya

        $dataSkorAwal = array();

        $list_all_will = array();

        //memberi nilai 0 default kesemua estate
        foreach ($queryEst as $key => $value) {

            $arrBtTPHperEst[$value->est] = 0; //est mengambil value dari table estate
            $arrKRest[$value->est] = 0;
            $arrBHest[$value->est] = 0;
            $arrRSest[$value->est] = 0;
        }
        // dd($queryEst);
        foreach ($queryWill as $key => $value) {

            $arrBtTPHperWil[$value->nama] = 0; //est mengambil value dari table estate
            $arrKRestWil[$value->nama] = 0;
            $arrBHestWil[$value->nama] = 0;
            $arrRestWill[$value->nama] = 0;
        }

        $firstWeek = $request->get('start');
        $lastWeek = $request->get('finish');

        // dd($firstWeek, $lastWeek);
        $query = DB::connection('mysql2')->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->whereBetween('sidak_tph.datetime', [$firstWeek, $lastWeek])
            // ->where('sidak_tph.datetime', 'LIKE', '%' . $request->$firstDate . '%')
            ->get();
        //     ->get();
        $queryAFD = DB::connection('mysql2')->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->whereIn('estate.wil', [1])
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->whereBetween('sidak_tph.datetime', [$firstWeek, $lastWeek])
            // ->where('sidak_tph.datetime', 'LIKE', '%' . $request->$firstDate . '%')
            ->get();
        // dd($queryAFD);
        $dataAfdEst = array();

        $querySidak = DB::connection('mysql2')->table('sidak_tph')
            ->whereBetween('sidak_tph.datetime', [$firstWeek, $lastWeek])
            // ->whereBetween('sidak_tph.datetime', ['2023-01-23', '202-12-25'])
            ->get();
        $querySidak = json_decode($querySidak, true);


        $allBlok = $query->groupBy(function ($item) {
            return $item->blok;
        });


        if (!empty($query && $queryAFD && $querySidak)) {
            $queryGroup = $query->groupBy(function ($item) {
                return $item->est;
            });
            // dd($queryGroup);
            $queryWi = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();

            $queryWill = $queryWi->groupBy(function ($item) {
                return $item->nama;
            });

            $queryWill2 = $queryWi->groupBy(function ($item) {
                return $item->nama;
            });


            //untuk table!!
            // store wil -> est -> afd
            // menyimpan array nested dari  wil -> est -> afd
            foreach ($queryEste as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    foreach ($queryAfd as $key3 => $value3) {
                        $est = $value2['est'];
                        $afd = $value3['nama'];
                        if ($value2['est'] == $value3['est']) {
                            foreach ($querySidak as $key4 => $value4) {
                                if (($est == $value4['est']) && $afd == $value4['afd']) {
                                    $dataAfdEst[$est][$afd][] = $value4;
                                } else {
                                    $dataAfdEst[$est][$afd]['null'] = 0;
                                }
                            }
                        }
                    }
                }
            }

            foreach ($dataAfdEst as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        unset($dataAfdEst[$key][$key2]['null']);
                        if (empty($dataAfdEst[$key][$key2])) {
                            $dataAfdEst[$key][$key2] = 0;
                        }
                    }
                }
            }

            $listBlokPerAfd = array();
            foreach ($dataAfdEst as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {

                            // dd($key3);
                            foreach ($allBlok as $key4 => $value4) {
                                if ($value3['blok'] == $key4) {
                                    $listBlokPerAfd[$key][$key2][$key3] = $value4;
                                }
                            }
                        }
                    }
                }


                // //menghitung data skor untuk brd/blok
                foreach ($dataAfdEst as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        if (is_array($value2)) {
                            $jum_blok = 0;
                            $sum_bt_tph = 0;
                            $sum_bt_jalan = 0;
                            $sum_bt_bin = 0;
                            $sum_all = 0;
                            $sum_all_karung = 0;
                            $sum_jum_karung = 0;
                            $sum_buah_tinggal = 0;
                            $sum_all_bt_tgl = 0;
                            $sum_restan_unreported = 0;
                            $sum_all_restan_unreported = 0;
                            $listBlokPerAfd = array();
                            foreach ($value2 as $key3 => $value3) {
                                if (is_array($value3)) {
                                    $blok = $value3['est'] . ' ' . $value3['afd'] . ' ' . $value3['blok'];

                                    if (!in_array($blok, $listBlokPerAfd)) {
                                        array_push($listBlokPerAfd, $blok);
                                    }

                                    // $jum_blok = count(float)($listBlokPerAfd);
                                    $jum_blok = count($listBlokPerAfd);

                                    $sum_bt_tph += $value3['bt_tph'];
                                    $sum_bt_jalan += $value3['bt_jalan'];
                                    $sum_bt_bin += $value3['bt_bin'];

                                    $sum_jum_karung += $value3['jum_karung'];
                                    $sum_buah_tinggal += $value3['buah_tinggal'];
                                    $sum_restan_unreported += $value3['restan_unreported'];
                                }
                            }
                            //change value 3to float type
                            $sum_all = $sum_bt_tph + $sum_bt_jalan + $sum_bt_bin;
                            $sum_all_karung = $sum_jum_karung;
                            $sum_all_bt_tgl = $sum_buah_tinggal;
                            $sum_all_restan_unreported = $sum_restan_unreported;
                            $skor_brd = round($sum_all / $jum_blok, 1);
                            // dd($skor_brd);
                            $skor_kr = round($sum_all_karung / $jum_blok, 1);
                            $skor_buahtgl = round($sum_all_bt_tgl / $jum_blok, 1);
                            $skor_restan = round($sum_all_restan_unreported / $jum_blok, 1);
                            $skor_brd_akhir = 0;
                            if ($skor_brd <= 18) {
                                $skor_brd_akhir = 30;
                            } else if ($skor_brd >= 18 && $skor_brd <= 30) {
                                $skor_brd_akhir = 26;
                            } else if ($skor_brd >= 30 && $skor_brd <= 42) {
                                $skor_brd_akhir = 22;
                            } else if ($skor_brd >= 42 && $skor_brd <= 54) {
                                $skor_brd_akhir = 18;
                            } else if ($skor_brd >= 54 && $skor_brd <= 66) {
                                $skor_brd_akhir = 14;
                            } else if ($skor_brd >= 66 && $skor_brd <= 78) {
                                $skor_brd_akhir = 10;
                            } else if ($skor_brd >= 78 && $skor_brd <= 90) {
                                $skor_brd_akhir = 6;
                            } else if ($skor_brd >= 96) {
                                $skor_brd_akhir = 0;
                            }

                            //karung
                            $skor_kr_akhir = 0;
                            if ($skor_kr <= 0) {
                                $skor_kr_akhir = 20;
                            } else if ($skor_kr >= 0 && $skor_kr <= 3) {
                                $skor_kr_akhir = 17;
                            } else if ($skor_kr >= 3 && $skor_kr <= 6) {
                                $skor_kr_akhir = 14;
                            } else if ($skor_kr >= 6 && $skor_kr <= 9) {
                                $skor_kr_akhir = 11;
                            } else if ($skor_kr >= 9 && $skor_kr <= 12) {
                                $skor_kr_akhir = 8;
                            } else if ($skor_kr >= 12 && $skor_kr <= 15) {
                                $skor_kr_akhir = 5;
                            } else if ($skor_kr >= 15 && $skor_kr <= 18) {
                                $skor_kr_akhir = 2;
                            } else if ($skor_kr >= 18) {
                                $skor_kr_akhir = 0;
                            }
                            //buah tinggal
                            $skor_buahtgl_akhir = 0;
                            if ($skor_buahtgl <= 0) {
                                $skor_buahtgl_akhir = 20;
                            } else if ($skor_buahtgl >= 0 && $skor_buahtgl <= 3) {
                                $skor_buahtgl_akhir = 17;
                            } else if ($skor_buahtgl >= 3 && $skor_buahtgl <= 6) {
                                $skor_buahtgl_akhir = 14;
                            } else if ($skor_buahtgl >= 6 && $skor_buahtgl <= 9) {
                                $skor_buahtgl_akhir = 11;
                            } else if ($skor_buahtgl >= 9 && $skor_buahtgl <= 12) {
                                $skor_buahtgl_akhir = 8;
                            } else if ($skor_buahtgl >= 12 && $skor_buahtgl <= 15) {
                                $skor_buahtgl_akhir = 5;
                            } else if ($skor_buahtgl >= 15 && $skor_buahtgl <= 18) {
                                $skor_buahtgl_akhir = 2;
                            } else if ($skor_buahtgl >= 18) {
                                $skor_buahtgl_akhir = 0;
                            }
                            //restant
                            $skor_restan_akhir = 0;
                            if ($skor_restan <= 0) {
                                $skor_restan_akhir = 30;
                            } else if ($skor_restan >= 0 && $skor_restan <= 3) {
                                $skor_restan_akhir = 26;
                            } else if ($skor_restan >= 3 && $skor_restan <= 6) {
                                $skor_restan_akhir = 22;
                            } else if ($skor_restan >= 6 && $skor_restan <= 9) {
                                $skor_restan_akhir = 18;
                            } else if ($skor_restan >= 9 && $skor_restan <= 12) {
                                $skor_restan_akhir = 14;
                            } else if ($skor_restan >= 12 && $skor_restan <= 15) {
                                $skor_restan_akhir = 10;
                            } else if ($skor_restan >= 15 && $skor_restan <= 18) {
                                $skor_restan_akhir = 6;
                            } else if ($skor_restan >= 18 && $skor_restan <= 21) {
                                $skor_restan_akhir = 2;
                            } else if ($skor_restan >= 21) {
                                $skor_restan_akhir = 0;
                            }
                            $skoreTotal = $skor_brd_akhir + $skor_kr_akhir + $skor_buahtgl_akhir + $skor_restan_akhir;

                            $dataSkorAwal[$key][$key2]['karung_tes'] = $sum_all_karung;
                            $dataSkorAwal[$key][$key2]['tph_test'] = $sum_all;
                            $dataSkorAwal[$key][$key2]['buah_test'] = $sum_all_bt_tgl;
                            $dataSkorAwal[$key][$key2]['restant_tes'] = $sum_all_restan_unreported;

                            $dataSkorAwal[$key][$key2]['jumlah_blok'] = $jum_blok;

                            $dataSkorAwal[$key][$key2]['brd_blok'] = $skor_brd_akhir;
                            $dataSkorAwal[$key][$key2]['kr_blok'] = $skor_kr_akhir;
                            $dataSkorAwal[$key][$key2]['buah_blok'] = $skor_buahtgl_akhir;
                            $dataSkorAwal[$key][$key2]['restan_blok'] = $skor_restan_akhir;
                            $dataSkorAwal[$key][$key2]['skore_akhir'] = $skoreTotal;
                        } else {
                            $dataSkorAwal[$key][$key2]['karung_tes'] = 0;
                            $dataSkorAwal[$key][$key2]['restant_tes'] = 0;
                            $dataSkorAwal[$key][$key2]['jumlah_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['tph_test'] = 0;
                            $dataSkorAwal[$key][$key2]['buah_test'] = 0;

                            $dataSkorAwal[$key][$key2]['brd_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['kr_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['buah_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['restan_blok'] = 0;
                            $dataSkorAwal[$key][$key2]['skore_akhir'] = 0;
                        }
                    }
                }

                // dd($dataSkorAwal);


                foreach ($dataSkorAwal as $key => $value) {
                    $jum_blok = 0;
                    $jum_all_blok = 0;
                    $sum_all_tph = 0;
                    $sum_tph = 0;
                    $sum_all_karung = 0;
                    $sum_karung = 0;
                    $sum_all_buah = 0;
                    $sum_buah = 0;
                    $sum_all_restant = 0;
                    $sum_restant = 0;
                    foreach ($value as $key2 => $value2)
                        if (is_array($value2)) {
                            $jum_blok += $value2['jumlah_blok'];
                            $sum_karung += $value2['karung_tes'];
                            $sum_restant += $value2['restant_tes'];
                            $sum_tph += $value2['tph_test'];
                            $sum_buah += $value2['buah_test'];
                        }
                    $sum_all_tph = $sum_tph;
                    $jum_all_blok = $jum_blok;
                    $sum_all_karung = $sum_karung;
                    $sum_all_buah = $sum_buah;
                    $sum_all_restant = $sum_restant;

                    if ($jum_all_blok != 0) {
                        $skor_tph = round($sum_all_tph / $jum_all_blok, 2);
                        $skor_karung = round($sum_all_karung / $jum_all_blok, 2);
                        $skor_buah = round($sum_all_buah / $jum_all_blok, 2);
                        $skor_restan = round($sum_all_restant / $jum_all_blok, 2);
                    } else {
                        $skor_tph = 0;
                        $skor_karung = 0;
                        $skor_buah = 0;
                        $skor_restan = 0;
                    }

                    $skor_tph_akhir = 0;
                    if ($skor_tph <= 18) {
                        $skor_tph_akhir = 30;
                    } else if ($skor_tph >= 18 && $skor_tph <= 30) {
                        $skor_tph_akhir = 26;
                    } else if ($skor_tph >= 30 && $skor_tph <= 42) {
                        $skor_tph_akhir = 22;
                    } else if ($skor_tph >= 42 && $skor_tph <= 54) {
                        $skor_tph_akhir = 18;
                    } else if ($skor_tph >= 54 && $skor_tph <= 66) {
                        $skor_tph_akhir = 14;
                    } else if ($skor_tph >= 66 && $skor_tph <= 78) {
                        $skor_tph_akhir = 10;
                    } else if ($skor_tph >= 78 && $skor_tph <= 90) {
                        $skor_tph_akhir = 6;
                    } else if ($skor_tph >= 96) {
                        $skor_tph_akhir = 0;
                    }


                    //karung
                    $skor_karung_akhir = 0;
                    if ($skor_karung <= 0) {
                        $skor_karung_akhir = 20;
                    } else if ($skor_karung >= 0 && $skor_karung <= 3) {
                        $skor_karung_akhir = 17;
                    } else if ($skor_karung >= 3 && $skor_karung <= 6) {
                        $skor_karung_akhir = 14;
                    } else if ($skor_karung >= 6 && $skor_karung <= 9) {
                        $skor_karung_akhir = 11;
                    } else if ($skor_karung >= 9 && $skor_karung <= 12) {
                        $skor_karung_akhir = 8;
                    } else if ($skor_karung >= 12 && $skor_karung <= 15) {
                        $skor_karung_akhir = 5;
                    } else if ($skor_karung >= 15 && $skor_karung <= 18) {
                        $skor_karung_akhir = 2;
                    } else if ($skor_karung >= 18) {
                        $skor_karung_akhir = 0;
                    }
                    //buah tinggal
                    $skor_buah_akhir = 0;
                    if ($skor_buah <= 0) {
                        $skor_buah_akhir = 20;
                    } else if ($skor_buah >= 0 && $skor_buah <= 3) {
                        $skor_buah_akhir = 17;
                    } else if ($skor_buah >= 3 && $skor_buah <= 6) {
                        $skor_buah_akhir = 14;
                    } else if ($skor_buah >= 6 && $skor_buah <= 9) {
                        $skor_buah_akhir = 11;
                    } else if ($skor_buah >= 9 && $skor_buah <= 12) {
                        $skor_buah_akhir = 8;
                    } else if ($skor_buah >= 12 && $skor_buah <= 15) {
                        $skor_buah_akhir = 5;
                    } else if ($skor_buah >= 15 && $skor_buah <= 18) {
                        $skor_buah_akhir = 2;
                    } else if ($skor_buah >= 18) {
                        $skor_buah_akhir = 0;
                    }

                    //restant
                    $skor_restan_akhir = 0;
                    if ($skor_restan <= 0) {
                        $skor_restan_akhir = 30;
                    } else if ($skor_restan >= 0 && $skor_restan <= 3) {
                        $skor_restan_akhir = 26;
                    } else if ($skor_restan >= 3 && $skor_restan <= 6) {
                        $skor_restan_akhir = 22;
                    } else if ($skor_restan >= 6 && $skor_restan <= 9) {
                        $skor_restan_akhir = 18;
                    } else if ($skor_restan >= 9 && $skor_restan <= 12) {
                        $skor_restan_akhir = 14;
                    } else if ($skor_restan >= 12 && $skor_restan <= 15) {
                        $skor_restan_akhir = 10;
                    } else if ($skor_restan >= 15 && $skor_restan <= 18) {
                        $skor_restan_akhir = 6;
                    } else if ($skor_restan >= 18 && $skor_restan <= 21) {
                        $skor_restan_akhir = 2;
                    } else if ($skor_restan >= 21) {
                        $skor_restan_akhir = 0;
                    }

                    $skoreTotal = $skor_tph_akhir + $skor_karung_akhir + $skor_buah_akhir + $skor_restan_akhir;
                    $dataSkorAwaltest[$key]['tph'] = $skor_tph_akhir;
                    $dataSkorAwaltest[$key]['karung'] = $skor_karung_akhir;
                    $dataSkorAwaltest[$key]['buah_tinggal'] = $skor_buah_akhir;
                    $dataSkorAwaltest[$key]['restant'] = $skor_restan_akhir;
                    $dataSkorAwaltest[$key]['total_blokokok'] = $jum_all_blok;
                    $dataSkorAwaltest[$key]['skor_akhir'] = $skoreTotal;
                }
                // dd($dataSkorAwaltest);

                foreach ($queryEste as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($dataSkorAwal as $key3 => $value3) {
                            if ($value2['est'] == $key3) {
                                $dataSkorAkhirPerWil[$key][$key3] = $value3;
                            }
                        }
                    }
                }


                foreach ($queryEste as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        foreach ($dataSkorAwaltest as $key3 => $value3) {
                            if ($value2['est'] == $key3) {
                                $dataSkorAkhirPerWilEst[$key][$key3] = $value3;
                            }
                        }
                    }
                }
                // dd($dataSkorAkhirPerWilEst);
                //menshort nilai masing masing
                $sortList = array();
                foreach ($dataSkorAkhirPerWil as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $sortList[$key][$key2 . '_' . $key3] =  $value3['skore_akhir'];
                            $inc++;
                        }
                    }
                }

                //short list untuk mengurutkan valuenya
                foreach ($sortList as  &$value) {
                    arsort($value);
                }
                // dd($sortList);
                $sortListEstate = array();
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        $sortListEstate[$key][$key2] =  $value2['skor_akhir'];
                        $inc++;
                    }
                }

                //short list untuk mengurutkan valuenya
                foreach ($sortListEstate as  &$value) {
                    arsort($value);
                }

                // dd($sortListEstate);
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {

                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                        }
                    }
                }

                //menambahkan nilai rank ketia semua total skor sudah di uritkan
                $test = array();
                $listRank = array();
                foreach ($dataSkorAkhirPerWil as $key => $value) {
                    // create an array to store the skore_akhir values
                    $skore_akhir_values = array();
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $skore_akhir_values[] = $value3['skore_akhir'];
                        }
                    }
                    // sort the skore_akhir values in descending order
                    rsort($skore_akhir_values);
                    // assign ranks to each skore_akhir value
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $rank = array_search($value3['skore_akhir'], $skore_akhir_values) + 1;
                            $dataSkorAkhirPerWil[$key][$key2][$key3]['rank'] = $rank;
                            $test[$key][] = $value3['skore_akhir'];
                        }
                    }
                }

                // perbaiki rank saya berdasarkan skore_akhir di mana jika $value3['skore_akhir'] terkecil merupakan rank 1 dan seterusnya
                $list_all_will = array();
                foreach ($dataSkorAkhirPerWil as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            $list_all_will[$key][$inc]['est_afd'] = $key2 . '_' . $key3;
                            $list_all_will[$key][$inc]['est'] = $key2;
                            $list_all_will[$key][$inc]['afd'] = $key3;
                            $list_all_will[$key][$inc]['skor'] = $value3['skore_akhir'];
                            $list_all_will[$key][$inc]['nama'] = '-';
                            $inc++;
                        }
                    }
                }

                foreach ($list_all_will as $key => $value) {
                    array_multisort(array_column($list_all_will[$key], 'skor'), SORT_DESC, $list_all_will[$key]);
                    $rank = 1;
                    foreach ($value as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            $list_all_will[$key][$key1]['rank'] = $rank;
                        }
                        $rank++;
                    }
                    array_multisort(array_column($list_all_will[$key], 'est_afd'), SORT_ASC, $list_all_will[$key]);
                }

                // $list_all_will = array();
                // foreach ($dataSkorAkhirPerWil as $key => $value) {
                //     $inc = 0;
                //     foreach ($value as $key2 => $value2) {
                //         foreach ($value2 as $key3 => $value3) {
                //             $list_all_will[$key][$inc]['est'] = $key2;
                //             $list_all_will[$key][$inc]['afd'] = $key3;
                //             $list_all_will[$key][$inc]['skor'] = $value3['skore_akhir'];
                //             $list_all_will[$key][$inc]['nama'] = '-';
                //             $list_all_will[$key][$inc]['rank'] = '-';
                //             $inc++;
                //         }
                //     }
                // }

                // foreach ($list_all_will as $key1 => $value1) {
                //     $filtered_subarray = array_filter($value1, function ($element) {
                //         return $element['skor'] != '-';
                //     });
                //     $rank = 1;
                //     foreach ($filtered_subarray as $key2 => $value2) {
                //         $filtered_subarray[$key2]['rank'] = $rank;
                //         $rank++;
                //     }
                //     $list_all_will[$key1] = $filtered_subarray;
                // }


                $list_all_est = array();
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    $inc = 0;
                    foreach ($value as $key2 => $value2) {
                        $list_all_est[$key][$inc]['est'] = $key2;
                        $list_all_est[$key][$inc]['skor'] = $value2['skor_akhir'];
                        $list_all_est[$key][$inc]['EM'] = 'EM';
                        $list_all_est[$key][$inc]['nama'] = '-';
                        $inc++;
                    }
                }

                foreach ($list_all_est as $key => $value) {
                    array_multisort(array_column($list_all_est[$key], 'skor'), SORT_DESC, $list_all_est[$key]);
                    $rank = 1;
                    foreach ($value as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            $list_all_est[$key][$key1]['rank'] = $rank;
                        }
                        $rank++;
                    }
                    array_multisort(array_column($list_all_est[$key], 'est'), SORT_ASC, $list_all_est[$key]);
                }
                // dd($list_all_est);

                //untuk chart!!!
                foreach ($queryGroup as $key => $value) {
                    $sum_bt_tph = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = array();
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_bt_tph += $val->bt_tph;
                    }
                    $skor_brd = round($sum_bt_tph / $jum_blok, 2);
                    $arrBtTPHperEst[$key] = $skor_brd;
                }
                // dd($arrBtTPHperEst);
                //sebelum di masukan ke aray harus looping karung berisi brondolan untuk mengambil datanya 2 lapis
                foreach ($queryGroup as $key => $value) {
                    $sum_jum_karung = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = array();
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_jum_karung += $val->jum_karung;
                    }
                    $skor_brd = round($sum_jum_karung / $jum_blok, 2);
                    $arrKRest[$key] = $skor_brd;
                }
                //looping buah tinggal 
                foreach ($queryGroup as $key => $value) {
                    $sum_buah_tinggal = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = array();
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_buah_tinggal += $val->buah_tinggal;
                    }
                    $skor_brd = round($sum_buah_tinggal / $jum_blok, 2);
                    $arrBHest[$key] = $skor_brd;
                }
                //looping buah restrant tidak di  laporkan
                foreach ($queryGroup as $key => $value) {
                    $sum_restan_unreported = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = array();
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_restan_unreported += $val->restan_unreported;
                    }
                    $skor_brd = round($sum_restan_unreported / $jum_blok, 2);
                    $arrRSest[$key] = $skor_brd;
                }

                //query untuk wilayah menambhakna data
                //jadikan dulu query dalam group memakai data querry untuk wilayah
                $queryGroupWil = $query->groupBy(function ($item) {
                    return $item->wil;
                });

                // dd($queryGroupWil);
                foreach ($queryGroupWil as $key => $value) {
                    $sum_bt_tph = 0;
                    foreach ($value as $key2 => $val) {
                        $sum_bt_tph += $val->bt_tph;
                    }
                    $arrBtTPHperWil[$key] = $sum_bt_tph;
                }

                //sebelum di masukan ke aray harus looping karung berisi brondolan untuk mengambil datanya 2 lapis
                foreach ($queryGroupWil as $key => $value) {
                    $sum_jum_karung = 0;
                    foreach ($value as $key2 => $vale) {
                        $sum_jum_karung += $vale->jum_karung;
                    }
                    $arrKRestWil[$key] = $sum_jum_karung;
                }
                //looping buah tinggal 
                foreach ($queryGroupWil as $key => $value) {
                    $sum_buah_tinggal = 0;
                    foreach ($value as $key2 => $val2) {
                        $sum_buah_tinggal += $val2->buah_tinggal;
                    }
                    $arrBHestWil[$key] = $sum_buah_tinggal;
                }
                foreach ($queryGroupWil as $key => $value) {
                    $sum_restan_unreported = 0;
                    foreach ($value as $key2 => $val3) {
                        $sum_restan_unreported += $val3->restan_unreported;
                    }
                    $arrRestWill[$key] = $sum_restan_unreported;
                }
            }
            // dd($arrBtTPHperWil, $arrKRestWil, $arrBHestWil, $arrRestWill);
            // dd($queryGroup);






            //masukan semua yang sudah selese di olah di atas ke dalam vaiabel terserah kemudian masukan kedalam aray
            //karena chart hanya bisa menerima inputan json 

            $arrView = array();

            $arrView['list_estate'] = $queryEst;
            $arrView['list_wilayah'] = $queryWill;
            // $arrView['restant'] = $dataSkorAwalRestant;

            $arrView['list_all_wil'] = $list_all_will;
            $arrView['list_all_est'] = $list_all_est;
            // $arrView['karung'] = $dataSkorAwalKr;
            // $arrView['buah'] = $dataSkorAwalBuah;
            // // dd($queryEst);
            // masukan ke array data penjumlahan dari estate
            $arrView['val_bt_tph'] = $arrBtTPHperEst; //data jsen brondolan tinggal di tph
            $arrView['val_kr_tph'] = $arrKRest; //data jsen karung yang berisi buah
            $arrView['val_bh_tph'] = $arrBHest; //data jsen buah yang tinggal
            $arrView['val_rs_tph'] = $arrRSest; //data jsen restan yang tidak dilaporkan
            //masukan ke array data penjumlahan dari wilayah
            $arrView['val_kr_tph_wil'] = $arrKRestWil; //data jsen karung yang berisi buah
            $arrView['val_bt_tph_wil'] = $arrBtTPHperWil; //data jsen brondolan tinggal di tph
            $arrView['val_bh_tph_wil'] = $arrBHestWil; //data jsen buah yang tinggal
            $arrView['val_rs_tph_wil'] = $arrRestWill; //data jsen restan yang tidak dilaporkan
            // dd($arrBtTPHperEst);
            echo json_encode($arrView); //di decode ke dalam bentuk json dalam vaiavel arrview yang dapat menampung banyak isi array
            exit();
        }
        // dd($queryEst);
        // dd($arrBtTPHperEst);

        // }
    }

    public function exportPDF(Request $request)
    {


        // $start = $request->start;
        // $last = $request->last;



        // $queryEste = DB::connection('mysql2')->table('estate')
        //     ->whereIn('wil', [1, 2, 3])
        //     ->get();

        // $listEstaeWil1 = $queryEste;


        // $listEstaeWil1 = json_decode($listEstaeWil1, true);

        // $queryEste = $queryEste->groupBy(function ($item) {
        //     return $item->wil;
        // });


        // $queryEste = json_decode($queryEste, true);





        // $queryAfd = DB::connection('mysql2')->Table('afdeling')
        //     ->select(
        //         'afdeling.id',
        //         'afdeling.nama',
        //         'estate.est'
        //     )
        //     ->join('estate', 'estate.id', '=', 'afdeling.estate')
        //     ->get();
        // // dd($queryAfd);
        // $queryAfd = json_decode($queryAfd, true);


        // // dd($queryAfd);
        // $querySidak = DB::connection('mysql2')->table('sidak_tph')
        //     ->whereBetween('sidak_tph.datetime', [$start, $last])
        //     // ->whereBetween('sidak_tph.datetime', ['2022-12-19', '2022-12-25'])
        //     ->get();
        // $querySidak = json_decode($querySidak, true);
        // $dataAfdEst = array();

        // $querySidakPerEstate = DB::connection('mysql2')->table('sidak_tph')
        //     ->select('sidak_tph.*',  DB::raw("DATE_FORMAT(sidak_tph.datetime,'%Y-%m-%d') as tanggal"))
        //     ->whereBetween('sidak_tph.datetime', [$start, $last])
        //     // ->whereBetween('sidak_tph.datetime', ['2022-12-19', '2022-12-25'])
        //     ->get();



        // // cari table pertanggal 
        // $queryEste1 = $querySidakPerEstate->groupBy(function ($item) {
        //     return $item->tanggal;
        // });

        // $queryEste2 = $querySidakPerEstate->groupBy(function ($item) {
        //     return $item->est;
        // });
        // // dd($queryEste2);
        // $queryEste1 = json_decode($queryEste1, true);
        // $queryEste2 = json_decode($queryEste2, true);



        // $dataPerEst = array();
        // foreach ($listEstaeWil1 as $key => $value) {
        //     foreach ($queryEste1 as $key2 => $value2) {
        //         foreach ($value2 as $key3 => $value3) {
        //             if ($value3['est'] == $value['est']) {
        //                 foreach ($queryAfd as $key4 => $value4) {
        //                     if ($value4['est'] == $value['est']) {
        //                         if ($value3['afd'] == $value4['nama']) {
        //                             $str = substr($value3['blok'], 0, -2);
        //                             if (strpos($str, '0') !== false) {
        //                                 $str = substr_replace($str, "", strpos($str, "0"), 1);
        //                             }
        //                             $dataPerEst[$key2][$value3['est']][$value4['nama']][$str] = $value3;
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }


        // $grouped = array_reduce($listEstaeWil1, function ($acc, $item) {
        //     $acc[$item['wil']][] = $item;
        //     return $acc;
        // }, []);

        // // dd($queryEste2);

        // $dataFoto = array();
        // foreach ($grouped as $key => $value) {
        //     foreach ($queryEste2 as $key3 => $value3) {
        //         // if ($value3['est'] == $value2['est']) {
        //         //     $dataFoto[$key][$key3] = $value3;
        //         //     # code...
        //         // }
        //     }
        // }


        // // dd($dataFoto);





        // foreach ($dataPerEst as $key => $value) {
        //     foreach ($value as $key2 => $value2) {
        //         $sum_est_ha_sample = 0;
        //         $sum_est_ha_sample = 0;
        //         $sum_est_bt_sample = 0;
        //         $sum_est_JlBin_sample = 0;
        //         $sum_est_Karung_sample = 0;
        //         $sum_est_Buah_sample = 0;
        //         $sum_est_Restan_sample = 0;
        //         foreach ($value2 as $key3 => $value3) {
        //             $sum_bt_tph = 0;
        //             $sum_bt_jalan = 0;
        //             $sum_bt_bin = 0;
        //             $sum_jum_karung = 0;
        //             $sum_buah_tinggal = 0;
        //             $sum_restan_unreported = 0;
        //             $sum_HA = 0;
        //             $sum_all = 0;
        //             if (is_array($value3)) {
        //                 foreach ($value3 as $key4 => $value4) {
        //                     // dd($value4);
        //                     $sum_bt_tph += $value4['bt_tph'];
        //                     $sum_bt_jalan += $value4['bt_jalan'];
        //                     $sum_bt_bin += $value4['bt_bin'];
        //                     $sum_HA += $value4['luas'];

        //                     $sum_jum_karung += $value4['jum_karung'];
        //                     $sum_buah_tinggal += $value4['buah_tinggal'];
        //                     $sum_restan_unreported += $value4['restan_unreported'];
        //                     $dataSkorAwalBlok[$key][$key2][$key3][$key4] = $value4;
        //                 }
        //             }
        //             $sum_all = $sum_bt_jalan + $sum_bt_bin;
        //             $sum_est_bt_sample = $sum_bt_tph;
        //             $sum_est_JlBin_sample = $sum_all;
        //             $sum_est_Karung_sample = $sum_jum_karung;
        //             $sum_est_Buah_sample = $sum_buah_tinggal;
        //             $sum_est_Restan_sample = $sum_restan_unreported;
        //             $sum_est_ha_sample = $sum_HA;
        //             //  else {
        //             $dataSkorAwalBlok[$key][$key2][$key3]['ha_total'] = $sum_est_ha_sample;
        //             $dataSkorAwalBlok[$key][$key2][$key3]['brondolan_total'] = $sum_est_bt_sample;
        //             $dataSkorAwalBlok[$key][$key2][$key3]['JalanBin_total'] = $sum_est_JlBin_sample;
        //             $dataSkorAwalBlok[$key][$key2][$key3]['Karung_total'] = $sum_est_Karung_sample;
        //             $dataSkorAwalBlok[$key][$key2][$key3]['BuahTinggal_total'] = $sum_est_Buah_sample;
        //             $dataSkorAwalBlok[$key][$key2][$key3]['RestanUnreported_total'] = $sum_est_Restan_sample;
        //         }
        //     }
        // }

        // // dd($dataSkorAwalBlok);



        // if (!empty($dataSkorAwalBlok)) {
        //     foreach ($listEstaeWil1 as $key => $value) {
        //         foreach ($dataSkorAwalBlok as $key2 => $value2) {
        //             foreach ($value2 as $key3 => $value3) {
        //                 if ($value['est'] == $key3) {
        //                     $dataSkorAwalBlok[$key2][$key3]['estate'] = $value['nama'];
        //                     $dataSkorAwalBlok[$key2][$key3]['tanggal'] = $key2;
        //                 }
        //             }
        //         }
        //     }
        // }





        // $dataAfdEst = array();
        // // menyimpan array nested dari  wil -> est -> afd
        // foreach ($queryEste as $key => $value) {
        //     foreach ($value as $key2 => $value2) {
        //         foreach ($queryAfd as $key3 => $value3) {
        //             $est = $value2['est'];
        //             $afd = $value3['nama'];
        //             if ($value2['est'] == $value3['est']) {
        //                 foreach ($querySidak as $key4 => $value4) {
        //                     if (($est == $value4['est']) && $afd == $value4['afd']) {
        //                         $dataAfdEst[$est][$afd][] = $value4;
        //                     } else {
        //                         $dataAfdEst[$est][$afd]['null'] = 0;
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
        // foreach ($dataAfdEst as $key => $value) {
        //     foreach ($value as $key2 => $value2) {
        //         foreach ($value2 as $key3 => $value3) {
        //             if ($key3 == 'null') {;
        //                 unset($dataAfdEst[$key][$key2][$key3]);
        //                 if (empty($dataAfdEst[$key][$key2])) {
        //                     $dataAfdEst[$key][$key2] = 0;
        //                 }
        //             }
        //         }
        //     }
        // }

        // // bagian untuk export ke foto 
        // $DataFotoperWil = array();
        // foreach ($queryEste as $key => $value) {
        //     foreach ($value as $key2 => $value2) {
        //         foreach ($dataAfdEst as $key3 => $value3) {
        //             if ($value2['est'] == $key3) {
        //                 $DataFotoperWil[$key][$key3] = $value3;
        //             }
        //         }
        //     }
        // }

        // foreach ($DataFotoperWil as $key1 => $value1) {
        //     foreach ($value1 as $key2 => $value2) {
        //         foreach ($value2 as $key3 => $value3)
        //             if (is_array($value3)) {

        //                 $DataFotoperWil[$key1][$key2][$key3] = reset($value3);
        //             }
        //     }
        // }



        // // dd($DataFotoperWil);

        // // endnya
        // // cari table pertama 
        // if (!isset($dataSkorAwal)) {
        //     $dataSkorAwal = array();
        // }
        // foreach ($dataAfdEst as $key => $value) {
        //     //...
        //     $sum_est_ha_sample = 0;
        //     $sum_est_bt_sample = 0;
        //     $sum_est_JlBin_sample = 0;
        //     $sum_est_Karung_sample = 0;
        //     $sum_est_Buah_sample = 0;
        //     $sum_est_Restan_sample = 0;
        //     foreach ($value as $key2 => $value2) {
        //         $sum_bt_tph = 0;
        //         $sum_bt_jalan = 0;
        //         $sum_bt_bin = 0;
        //         $sum_jum_karung = 0;
        //         $sum_buah_tinggal = 0;
        //         $sum_restan_unreported = 0;
        //         $sum_ha_sample = 0;
        //         if (is_array($value2)) {
        //             foreach ($value2 as $key3 => $value3) {

        //                 $sum_bt_tph += $value3['bt_tph'];
        //                 $sum_bt_jalan += $value3['bt_jalan'];
        //                 $sum_bt_bin += $value3['bt_bin'];

        //                 $sum_ha_sample += $value3['luas'];

        //                 $sum_jum_karung += $value3['jum_karung'];
        //                 $sum_buah_tinggal += $value3['buah_tinggal'];
        //                 $sum_restan_unreported += $value3['restan_unreported'];
        //             }

        //             $dataSkorAwal[$key][$key2]['tph'] = $sum_bt_tph;
        //             $dataSkorAwal[$key][$key2]['ha_sample'] = $sum_ha_sample;
        //             $dataSkorAwal[$key][$key2]['jalan_bin'] = $sum_bt_jalan + $sum_bt_bin;
        //             $dataSkorAwal[$key][$key2]['karung'] = $sum_jum_karung;
        //             $dataSkorAwal[$key][$key2]['buah_tinggal'] = $sum_buah_tinggal;
        //             $dataSkorAwal[$key][$key2]['restan_unreported'] = $sum_restan_unreported;
        //         } else {
        //             $dataSkorAwal[$key][$key2]['tph'] = 0;
        //             $dataSkorAwal[$key][$key2]['ha_sample'] = 0;
        //             $dataSkorAwal[$key][$key2]['jalan_bin'] = 0;
        //             $dataSkorAwal[$key][$key2]['karung'] = 0;
        //             $dataSkorAwal[$key][$key2]['buah_tinggal'] = 0;
        //             $dataSkorAwal[$key][$key2]['restan_unreported'] = 0;
        //         }
        //         $sum_est_ha_sample += $sum_ha_sample;
        //         $sum_est_bt_sample += $sum_bt_tph;
        //         $sum_est_JlBin_sample += $sum_bt_jalan + $sum_bt_bin;
        //         $sum_est_Karung_sample += $sum_jum_karung;
        //         $sum_est_Buah_sample += $sum_buah_tinggal;
        //         $sum_est_Restan_sample += $sum_restan_unreported;
        //     }
        //     $dataSkorAwal[$key]['ha_total'] = $sum_est_ha_sample;
        //     $dataSkorAwal[$key]['brondolan_total'] = $sum_est_bt_sample;
        //     $dataSkorAwal[$key]['JalanBin_total'] = $sum_est_JlBin_sample;
        //     $dataSkorAwal[$key]['Karung_total'] = $sum_est_Karung_sample;
        //     $dataSkorAwal[$key]['BuahTinggal_total'] = $sum_est_Buah_sample;
        //     $dataSkorAwal[$key]['RestanUnreported_total'] = $sum_est_Restan_sample;
        // }


        // // dd($dataAfdEst);
        // // if (empty($dataSkorAwal && $dataSkorAwalBlok)) {
        // //     // echo "No data to display";
        // //     return redirect()->route('404')->with('status', 'No data to display');
        // // } else {
        //     $dataSkorAkhirPerWil = array();
        //     foreach ($queryEste as $key => $value) {
        //         foreach ($value as $key2 => $value2) {
        //             foreach ($dataSkorAwal as $key3 => $value3) {
        //                 if ($value2['est'] == $key3) {
        //                     $dataSkorAkhirPerWil[$key][$key3] = $value3;
        //                 }
        //             }
        //         }
        //     }


        //     // dd($dataSkorAkhirPerWil);
        //     // cari table highest finding
        //     $max_brd = [];
        //     foreach ($dataSkorAkhirPerWil as $key => $value) {
        //         foreach ($value as $key2 => $value2) {
        //             $max_tph = 0;
        //             $max_tph_id = 0;
        //             foreach ($value2 as $key3 => $value3) {
        //                 if (is_array($value3) && $value3['tph'] > $max_tph) {
        //                     $max_tph = $value3['tph'];
        //                     $max_tph_id = $key3;
        //                 }
        //             }
        //             if ($max_tph > 0) {
        //                 $max_brd[$key][$key2][$max_tph_id]['brd_max'] = $max_tph;
        //                 $max_wil_1[$key]['brd_max'] = $max_tph;
        //             }
        //         }
        //     }
        //     // dd($max_wil_1);
        //     $max_brd_fix = [];
        //     foreach ($max_brd as $key => $value) {
        //         $max_tph = 0;
        //         $max_tph_id = 0;
        //         foreach ($value as $key2 => $value2) {

        //             foreach ($value2 as $key3 => $value3) {
        //                 if (is_array($value3) && $value3['brd_max'] > $max_tph) {
        //                     $max_tph = $value3['brd_max'];
        //                     $max_tph_id = $key3;
        //                 }
        //             }
        //         }
        //         if ($max_tph > 0) {
        //             $max_brd_fix[$key][$key2][$max_tph_id]['brondolan_maxx'] = $max_tph;
        //         } else {
        //             $max_brd_fix[$key][$key2][$max_tph_id]['brondolan_maxx'] = 0;
        //         }
        //     }
        //     // dd($max_brd_fix)
        //     // menghitung karung 
        //     $max_Karung = [];
        //     foreach ($dataSkorAkhirPerWil as $key => $value) {
        //         foreach ($value as $key2 => $value2) {
        //             $max_tph = 0;
        //             $max_tph_id = 0;
        //             foreach ($value2 as $key3 => $value3) {
        //                 if (is_array($value3) && $value3['karung'] > $max_tph) {
        //                     $max_tph = $value3['karung'];
        //                     $max_tph_id = $key3;
        //                 }
        //             }
        //             if ($max_tph > 0) {
        //                 $max_Karung[$key][$key2][$max_tph_id]['krg_max'] = $max_tph;
        //                 $max_wil_1[$key]['krg_max'] = $max_tph;
        //             }
        //         }
        //     }
        //     $max_krg_fix = [];
        //     foreach ($max_Karung as $key => $value) {
        //         $max_krg = 0;
        //         $max_krg_id = 0;
        //         foreach ($value as $key2 => $value2) {

        //             foreach ($value2 as $key3 => $value3) {
        //                 if (is_array($value3) && $value3['krg_max'] > $max_krg) {
        //                     $max_krg = $value3['krg_max'];
        //                     $max_krg_id = $key3;
        //                 }
        //             }
        //         }
        //         if ($max_krg > 0) {
        //             $max_krg_fix[$key][$key2][$max_krg_id]['karung_max'] = $max_krg;
        //         } else {
        //             $max_krg_fix[$key][$key2][$max_krg_id]['karung_max'] = 0;
        //         }
        //     }
        //     //menghitung buah tinggal
        //     $max_buahTgl = [];
        //     foreach ($dataSkorAkhirPerWil as $key => $value) {
        //         foreach ($value as $key2 => $value2) {
        //             $max_tph = 0;
        //             $max_tph_id = 0;
        //             foreach ($value2 as $key3 => $value3) {
        //                 if (is_array($value3) && $value3['buah_tinggal'] > $max_tph) {
        //                     $max_tph = $value3['buah_tinggal'];
        //                     $max_tph_id = $key3;
        //                 }
        //             }
        //             if ($max_tph > 0) {
        //                 $max_buahTgl[$key][$key2][$max_tph_id]['buahTGL_max'] = $max_tph;
        //                 $max_wil_1[$key]['buahTGL_max'] = $max_tph;
        //             }
        //         }
        //     }
        //     $max_buahtgal_fix = [];
        //     foreach ($max_buahTgl as $key => $value) {
        //         $max_bhTgl = 0;
        //         $max_bhTgl_id = 0;
        //         foreach ($value as $key2 => $value2) {

        //             foreach ($value2 as $key3 => $value3) {
        //                 if (is_array($value3) && $value3['buahTGL_max'] > $max_bhTgl) {
        //                     $max_bhTgl = $value3['buahTGL_max'];
        //                     $max_bhTgl_id = $key3;
        //                 }
        //             }
        //         }
        //         if ($max_bhTgl > 0) {
        //             $max_buahtgal_fix[$key][$key2][$max_bhTgl_id]['buah_tgl_max_fix'] = $max_bhTgl;
        //         } else {
        //             $max_buahtgal_fix[$key][$key2][$max_bhTgl_id]['buah_tgl_max_fix'] = 0;
        //         }
        //     }
        //     //buah restant
        //     $max_restant = [];
        //     foreach ($dataSkorAkhirPerWil as $key => $value) {
        //         foreach ($value as $key2 => $value2) {
        //             $max_tph = 0;
        //             $max_tph_id = 0;

        //             foreach ($value2 as $key3 => $value3) {
        //                 if (is_array($value3) && $value3['restan_unreported'] > $max_tph) {
        //                     $max_tph = $value3['restan_unreported'];
        //                     $max_tph_id = $key3;
        //                 }
        //             }
        //             if ($max_tph > 0) {
        //                 $max_restant[$key][$key2][$max_tph_id]['restant_max'] = $max_tph;
        //                 $max_wil_1[$key]['restant_max'] = $max_tph;
        //             }
        //         }
        //     }

        //     $max_restanFix = [];
        //     foreach ($max_restant as $key => $value) {
        //         $max_bhTgl = 0;
        //         $max_bhTgl_id = 0;
        //         foreach ($value as $key2 => $value2) {

        //             foreach ($value2 as $key3 => $value3) {
        //                 if (is_array($value3) && $value3['restant_max'] > $max_bhTgl) {
        //                     $max_bhTgl = $value3['restant_max'];
        //                     $max_bhTgl_id = $key3;
        //                 }
        //             }
        //         }
        //         if ($max_bhTgl > 0) {
        //             $max_restanFix[$key][$key2][$max_bhTgl_id]['restant_max_fix'] = $max_bhTgl;
        //         } else {
        //             $max_restanFix[$key][$key2][$max_bhTgl_id]['restant_max_fix'] = 0;
        //         }
        //     }

        //     // membuat bagian untuk export chart ke pdf



        //     // end chart

        //     //hasil akhir  untuk di kirim ke blade view

        //     //foto akhir
        //     $will_1_foto = isset($DataFotoperWil) ? $DataFotoperWil : '';
        //     // dd($will_1_foto);
        //     //


        //     // dd($dataSkorAkhirPerWil);
        //     $wil_1_sidak_tph = $dataSkorAkhirPerWil[1];
        //     $wil_2_sidak_tph = $dataSkorAkhirPerWil[2];
        //     $wil_3_sidak_tph = $dataSkorAkhirPerWil[3];
        //     //untuk max id wil 1



        //     $wil_1_sidak_tph_max = isset($max_brd_fix[1]) ? $max_brd_fix[1] : '';
        //     $wil_1_sidak_krng_max = isset($max_krg_fix[1]) ? $max_krg_fix[1] : '';
        //     $wil_1_sidak_buah_max = isset($max_buahtgal_fix[1]) ? $max_buahtgal_fix[1] : '';
        //     $wil_1_sidak_restant_max = isset($max_restanFix[1]) ? $max_restanFix[1] : '';
        //     //untuk max id wil 3
        //     $wil_2_sidak_tph_max = isset($max_brd_fix[2]) ? $max_brd_fix[2] : '';
        //     $wil_2_sidak_krng_max = isset($max_krg_fix[2]) ? $max_krg_fix[2] : '';
        //     $wil_2_sidak_buah_max = isset($max_buahtgal_fix[2]) ? $max_buahtgal_fix[2] : '';
        //     $wil_2_sidak_restant_max = isset($max_restanFix[2]) ? $max_restanFix[2] : '';
        //     // //untuk max id wil 3
        //     $wil_3_sidak_tph_max = isset($max_brd_fix[3]) ? $max_brd_fix[3] : '';
        //     $wil_3_sidak_krng_max = isset($max_krg_fix[3]) ? $max_krg_fix[3] : '';
        //     $wil_3_sidak_buah_max = isset($max_buahtgal_fix[3]) ? $max_buahtgal_fix[3] : '';
        //     $wil_3_sidak_restant_max = isset($max_restanFix[3]) ? $max_restanFix[3] : '';

        //     // data untuk menampilkan data pertanggal di table
        //     // dd($dataSkorAwalBlok);
        //     $dataFixSkorAwalBlok = array();
        //     foreach ($dataSkorAwalBlok as $key => $value) {
        //         foreach ($value as $key2 => $value2) {
        //             $dataFixSkorAwalBlok[] = $value2;
        //         }
        //     }

        //     // dd($dataFixSkorAwalBlok);
        //     $DataPerTanggal = isset($dataFixSkorAwalBlok) ? $dataFixSkorAwalBlok : '';

        //     // $DataPerTanggal = isset($dataSkorAwalBlok) ? $dataSkorAwalBlok : '';

        //     // dd($wil_1_sidak_tph_max, $wil_1_sidak_krng_max);
        //     // $pdf = PDF::loadView('cetakSidak', [
        //     //     'wil_1_sidak_tph' => $wil_1_sidak_tph,
        //     //     'wil_2_sidak_tph' => $wil_2_sidak_tph,
        //     //     'wil_3_sidak_tph' => $wil_3_sidak_tph,
        //     //     'start' => $start,
        //     //     'last' => $last,
        //     //     'wil_1_sidak_tph_max' => $wil_1_sidak_tph_max,
        //     //     'wil_1_sidak_krng_max' => $wil_1_sidak_krng_max,
        //     //     'wil_1_sidak_buah_max' => $wil_1_sidak_buah_max,
        //     //     'wil_1_sidak_restant_max' => $wil_1_sidak_restant_max,
        //     //     'wil_2_sidak_tph_max' => $wil_2_sidak_tph_max,
        //     //     'wil_2_sidak_krng_max' => $wil_2_sidak_krng_max,
        //     //     'wil_2_sidak_buah_max' => $wil_2_sidak_buah_max,
        //     //     'wil_2_sidak_restant_max' => $wil_2_sidak_restant_max,
        //     //     'wil_3_sidak_tph_max' => $wil_3_sidak_tph_max,
        //     //     'wil_3_sidak_krng_max' => $wil_3_sidak_krng_max,
        //     //     'wil_3_sidak_buah_max' => $wil_3_sidak_buah_max,
        //     //     'wil_3_sidak_restant_max' => $wil_3_sidak_restant_max,
        //     //     'DataPerTanggal' => $DataPerTanggal,
        //     //     'will_1_foto' => $will_1_foto

        //     // ]);






        //     // $pdf->set_paper('A3', 'potrait');
        //     // $pdf->setOption('enable-javascript', true);
        //     // $filename = 'REKAPITULASI Pemeriksaan TPH & BIN Reg-I Tanggal: ' . $start . ' sampai ' . $last . '.pdf';
        //     // $filename = 'REKAPITULASI-' .$start . '-' .$last '.pdf';

        //     // return $pdf->stream($filename, array('Attachment' => 0));
        //     // dd($request);
        $data = $request->chartData;
        $pdf = PDF::loadView('cetakSidak', ['data' => $data]);
        return $pdf->stream('charts.pdf');
        // }
    }

    public function notfound()
    {

        return view('404');
    }
}
