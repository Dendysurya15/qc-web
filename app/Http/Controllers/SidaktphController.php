<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Support\Arr;
use Nette\Utils\DateTime;
use Termwind\Components\Dd;

class SidaktphController extends Controller
{
    //
    public $search;
    public function index(Request $request)
    {
        $queryEst = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->where('estate.est', '!=', 'CWS')->where('estate.est', '!=', 'PLASMA')->pluck('est');

        $queryEste = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->where('estate.est', '!=', 'CWS')->where('estate.est', '!=', 'PLASMA')->get();
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
            ->where('estate.est', '!=', 'CWS')->where('estate.est', '!=', 'PLASMA')
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

    public function listAsisten(Request $request)
    {
        $query = DB::connection('mysql2')->table('asisten_qc')
            ->select('asisten_qc.*')
            // ->whereIn('estate.wil', [1, 2, 3])
            // ->join('estate', 'estate.est', '=', 'asisten_qc.est')
            ->get();

        $queryEst = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->get();
        $queryAfd = DB::connection('mysql2')->table('afdeling')->select('nama')->groupBy('nama')->get();

        return view('listAsisten', ['asisten' => $query, 'estate' => $queryEst, 'afdeling' => $queryAfd]);
    }

    public function tambahAsisten(Request $request)
    {
        $query = DB::connection('mysql2')->table('asisten_qc')
            ->where('est', $request->input('est'))
            ->where('afd', $request->input('afd'))
            ->first();

        if (empty($query)) {
            DB::connection('mysql2')->table('asisten_qc')->insert([
                'nama' => $request->input('nama'),
                'est' => $request->input('est'),
                'afd' => $request->input('afd')
            ]);

            return redirect()->route('listAsisten')->with('status', 'Data asisten berhasil ditambahkan!');
        } else {
            return redirect()->route('listAsisten')->with('error', 'Gagal ditambahkan, asisten dengan Estate dan Afdeling tersebut sudah ada!');
        }
    }

    public function perbaruiAsisten(Request $request)
    {

        $est = $request->input('est');
        $afd = $request->input('afd');
        $nama = $request->input('nama');
        $id = $request->input('id');

        $query = DB::connection('mysql2')->table('asisten_qc')
            ->where('id', $id)
            ->first();


        // dd($est, $query->est);

        if ($query->nama != $nama && $query->est == $est && $query->afd == $afd) {
            DB::connection('mysql2')->table('asisten_qc')->where('id', $request->input('id'))
                ->update([
                    'nama' => $request->input('nama'),
                    'est' => $request->input('est'),
                    'afd' => $request->input('afd')
                ]);

            return redirect()->route('listAsisten')->with('status', 'Data asisten berhasil diperbarui!');
        } else if ($est != $query->est) {
            $queryWill2 = DB::connection('mysql2')->table('asisten_qc')
                ->where('est', $est)
                ->where('afd', $afd)
                ->first();

            if (empty($queryWill2)) {
                DB::connection('mysql2')->table('asisten_qc')->where('id', $request->input('id'))
                    ->update([
                        'nama' => $request->input('nama'),
                        'est' => $request->input('est'),
                        'afd' => $request->input('afd')
                    ]);

                return redirect()->route('listAsisten')->with('status', 'Data asisten berhasil diperbarui!');
            } else {
                return redirect()->route('listAsisten')->with('error', 'Gagal diperbarui, asisten dengan Estate dan Afdeling tersebut sudah ada!');
            }
        } else if ($afd != $query->afd) {
            $queryWill2 = DB::connection('mysql2')->table('asisten_qc')
                ->where('est', $est)
                ->where('afd', $afd)
                ->first();

            if (empty($queryWill2)) {
                DB::connection('mysql2')->table('asisten_qc')->where('id', $request->input('id'))
                    ->update([
                        'nama' => $request->input('nama'),
                        'est' => $request->input('est'),
                        'afd' => $request->input('afd')
                    ]);

                return redirect()->route('listAsisten')->with('status', 'Data asisten berhasil diperbarui!');
            } else {
                return redirect()->route('listAsisten')->with('error', 'Gagal diperbarui, asisten dengan Estate dan Afdeling tersebut sudah ada!');
            }
        } else {
            return redirect()->route('listAsisten')->with('error', 'Gagal diperbarui, asisten dengan Estate dan Afdeling tersebut sudah ada!');
        }

        // $query = DB::connection('mysql2')->table('asisten_qc')
        //     ->where('est', $request->input('est'))
        //     ->where('afd', $request->input('afd'))
        //     ->first();

        // // dd($query);
        // if (empty($query)) {
        //     DB::connection('mysql2')->table('asisten_qc')->where('id', $request->input('id'))
        //         ->update([
        //             'nama' => $request->input('nama'),
        //             'est' => $request->input('est'),
        //             'afd' => $request->input('afd')
        //         ]);

        //     return redirect()->route('listAsisten')->with('status', 'Data asisten berhasil diperbarui!');
        // } else {
        //     return redirect()->route('listAsisten')->with('error', 'Gagal diperbarui, asisten dengan Estate dan Afdeling tersebut sudah ada!');
        // }
    }

    public function hapusAsisten(Request $request)
    {
        DB::connection('mysql2')->table('asisten_qc')->where('id', $request->input('id'))->delete();
        return redirect()->route('listAsisten')->with('status', 'Data asisten berhasil dihapus!');
    }

    public function downloadPDF(Request $request)
    {
        $url = $request->get('url');
        $arrView = array();
        $file_headers = @get_headers($url);
        if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $arrView['status'] = '404';
            $arrView['url'] = $url;
        } else {
            $arrView['status'] = '200';
            $arrView['url'] = $url;
        }
        echo json_encode($arrView);
        exit();
    }

    // chart ajax brondolan tinggal dan pencarian berdasarkan minggu
    public function getBtTph(Request $request)
    {
        $queryWill = DB::connection('mysql2')->table('wil')->whereIn('regional', [1])->get();
        $queryReg = DB::connection('mysql2')->table('wil')->whereIn('regional', [1])->pluck('regional');

        // dapatkan data estate dari table estate dengan wilayah 1 , 2 , 3
        $queryEst = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->where('estate.est', '!=', 'CWS')->where('estate.est', '!=', 'PLASMA')->get();
        // dd($queryEst);
        $queryEste = DB::connection('mysql2')->table('estate')->whereIn('wil', [1, 2, 3])->where('estate.est', '!=', 'CWS')->where('estate.est', '!=', 'PLASMA')->get();
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
            ->where('estate.est', '!=', 'CWS')->where('estate.est', '!=', 'PLASMA')
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
            ->where('estate.est', '!=', 'CWS')->where('estate.est', '!=', 'PLASMA')
            // ->where('sidak_tph.datetime', 'LIKE', '%' . $request->$firstDate . '%')
            ->get();
        //     ->get();
        $queryAFD = DB::connection('mysql2')->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->whereIn('estate.wil', [1, 2, 3])
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->whereBetween('sidak_tph.datetime', [$firstWeek, $lastWeek])
            ->where('estate.est', '!=', 'CWS')->where('estate.est', '!=', 'PLASMA')
            // ->where('sidak_tph.datetime', 'LIKE', '%' . $request->$firstDate . '%')
            ->get();
        // dd($queryAFD);
        $queryAsisten =  DB::connection('mysql2')->Table('asisten_qc')->get();

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
            $queryGroup = $queryAFD->groupBy(function ($item) {
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

                    $skor_tph = $jum_all_blok == 0 ? $sum_all_tph : round($sum_all_tph / $jum_all_blok, 2);
                    $skor_karung = $jum_all_blok == 0 ? $sum_all_karung : round($sum_all_karung / $jum_all_blok, 2);
                    $skor_buah = $jum_all_blok == 0 ? $sum_all_buah : round($sum_all_buah / $jum_all_blok, 2);
                    $skor_restan = $jum_all_blok == 0 ? $sum_all_restant : round($sum_all_restant / $jum_all_blok, 2);

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

                    if ($skoreTotal == 100) {
                        if ($skor_tph == 0 && $skor_karung == 0 && $skor_buah == 0 && $skor_restan == 0) {
                            $skoreTotal = 0;
                        }
                    }
                    // $dataSkorAwaltest[$key]['tot_tph'] = $sum_all_tph;
                    // $dataSkorAwaltest[$key]['tot_karung'] = $sum_all_karung;
                    // $dataSkorAwaltest[$key]['tot_buah'] = $sum_all_buah;
                    // $dataSkorAwaltest[$key]['tot_restant'] = $sum_all_restant;
                    $dataSkorAwaltest[$key]['total_estate_brondol'] = $sum_all_tph;
                    $dataSkorAwaltest[$key]['total_estate_karung'] = $sum_all_karung;
                    $dataSkorAwaltest[$key]['total_estate_buah_tinggal'] = $sum_all_buah;
                    $dataSkorAwaltest[$key]['total_estate_restan_tinggal'] = $sum_all_restant;
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
                            foreach ($queryAsisten as $key4 => $value4) {
                                if ($value4->est == $key2 && $value4->afd == $key3) {
                                    $list_all_will[$key][$inc]['nama'] = $value4->nama;
                                }
                            }
                            if (empty($list_all_will[$key][$inc]['nama'])) {
                                $list_all_will[$key][$inc]['nama'] = '-';
                            }
                            $inc++;
                        }
                    }
                }

                $skor_gm_wil = array();
                foreach ($dataSkorAkhirPerWilEst as $key => $value) {
                    $sum_est_brondol = 0;
                    $sum_est_karung = 0;
                    $sum_est_buah_tinggal = 0;
                    $sum_est_restan_tinggal = 0;
                    $sum_blok = 0;
                    foreach ($value as $key2 => $value2) {
                        $sum_est_brondol += $value2['total_estate_brondol'];
                        $sum_est_karung += $value2['total_estate_karung'];
                        $sum_est_buah_tinggal += $value2['total_estate_buah_tinggal'];
                        $sum_est_restan_tinggal += $value2['total_estate_restan_tinggal'];


                        // dd($value2['total_blokokok']);

                        // if ($value2['total_blokokok'] != 0) {
                        $sum_blok += $value2['total_blokokok'];
                        // } else {
                        //     $sum_blok = 1;
                        // }
                    }

                    $skor_total_brondol = $sum_blok == 0 ? $sum_est_brondol : round($sum_est_brondol / $sum_blok, 2);
                    $skor_total_karung = $sum_blok == 0 ? $sum_est_karung : round($sum_est_karung / $sum_blok, 2);
                    $skor_total_buah_tinggal = $sum_blok == 0 ? $sum_est_buah_tinggal : round($sum_est_buah_tinggal / $sum_blok, 2);
                    $skor_total_restan_tinggal = $sum_blok == 0 ? $sum_est_restan_tinggal : round($sum_est_restan_tinggal / $sum_blok, 2);


                    $skor_tph_akhir = 0;
                    if ($skor_total_brondol <= 18) {
                        $skor_tph_akhir = 30;
                    } else if ($skor_total_brondol >= 18 && $skor_total_brondol <= 30) {
                        $skor_tph_akhir = 26;
                    } else if ($skor_total_brondol >= 30 && $skor_total_brondol <= 42) {
                        $skor_tph_akhir = 22;
                    } else if ($skor_total_brondol >= 42 && $skor_total_brondol <= 54) {
                        $skor_tph_akhir = 18;
                    } else if ($skor_total_brondol >= 54 && $skor_total_brondol <= 66) {
                        $skor_tph_akhir = 14;
                    } else if ($skor_total_brondol >= 66 && $skor_total_brondol <= 78) {
                        $skor_tph_akhir = 10;
                    } else if ($skor_total_brondol >= 78 && $skor_total_brondol <= 90) {
                        $skor_tph_akhir = 6;
                    } else if ($skor_total_brondol >= 96) {
                        $skor_tph_akhir = 0;
                    }


                    //karung
                    $skor_karung_akhir = 0;
                    if ($skor_total_karung <= 0) {
                        $skor_karung_akhir = 20;
                    } else if ($skor_total_karung >= 0 && $skor_total_karung <= 3) {
                        $skor_karung_akhir = 17;
                    } else if ($skor_total_karung >= 3 && $skor_total_karung <= 6) {
                        $skor_karung_akhir = 14;
                    } else if ($skor_total_karung >= 6 && $skor_total_karung <= 9) {
                        $skor_karung_akhir = 11;
                    } else if ($skor_total_karung >= 9 && $skor_total_karung <= 12) {
                        $skor_karung_akhir = 8;
                    } else if ($skor_total_karung >= 12 && $skor_total_karung <= 15) {
                        $skor_karung_akhir = 5;
                    } else if ($skor_total_karung >= 15 && $skor_total_karung <= 18) {
                        $skor_karung_akhir = 2;
                    } else if ($skor_total_karung >= 18) {
                        $skor_karung_akhir = 0;
                    }
                    //buah tinggal
                    $skor_buah_akhir = 0;
                    if ($skor_total_buah_tinggal <= 0) {
                        $skor_buah_akhir = 20;
                    } else if ($skor_total_buah_tinggal >= 0 && $skor_total_buah_tinggal <= 3) {
                        $skor_buah_akhir = 17;
                    } else if ($skor_total_buah_tinggal >= 3 && $skor_total_buah_tinggal <= 6) {
                        $skor_buah_akhir = 14;
                    } else if ($skor_total_buah_tinggal >= 6 && $skor_total_buah_tinggal <= 9) {
                        $skor_buah_akhir = 11;
                    } else if ($skor_total_buah_tinggal >= 9 && $skor_total_buah_tinggal <= 12) {
                        $skor_buah_akhir = 8;
                    } else if ($skor_total_buah_tinggal >= 12 && $skor_total_buah_tinggal <= 15) {
                        $skor_buah_akhir = 5;
                    } else if ($skor_total_buah_tinggal >= 15 && $skor_total_buah_tinggal <= 18) {
                        $skor_buah_akhir = 2;
                    } else if ($skor_total_buah_tinggal >= 18) {
                        $skor_buah_akhir = 0;
                    }

                    //restant
                    $skor_restan_akhir = 0;
                    if ($skor_total_restan_tinggal <= 0) {
                        $skor_restan_akhir = 30;
                    } else if ($skor_total_restan_tinggal >= 0 && $skor_total_restan_tinggal <= 3) {
                        $skor_restan_akhir = 26;
                    } else if ($skor_total_restan_tinggal >= 3 && $skor_total_restan_tinggal <= 6) {
                        $skor_restan_akhir = 22;
                    } else if ($skor_total_restan_tinggal >= 6 && $skor_total_restan_tinggal <= 9) {
                        $skor_restan_akhir = 18;
                    } else if ($skor_total_restan_tinggal >= 9 && $skor_total_restan_tinggal <= 12) {
                        $skor_restan_akhir = 14;
                    } else if ($skor_total_restan_tinggal >= 12 && $skor_total_restan_tinggal <= 15) {
                        $skor_restan_akhir = 10;
                    } else if ($skor_total_restan_tinggal >= 15 && $skor_total_restan_tinggal <= 18) {
                        $skor_restan_akhir = 6;
                    } else if ($skor_total_restan_tinggal >= 18 && $skor_total_restan_tinggal <= 21) {
                        $skor_restan_akhir = 2;
                    } else if ($skor_total_restan_tinggal >= 21) {
                        $skor_restan_akhir = 0;
                    }

                    $skor_gm_wil[$key]['total_brondolan'] = $sum_est_brondol;
                    $skor_gm_wil[$key]['total_karung'] = $sum_est_karung;
                    $skor_gm_wil[$key]['total_buah_tinggal'] = $sum_est_buah_tinggal;
                    $skor_gm_wil[$key]['total_restan'] = $sum_est_restan_tinggal;
                    $skor_gm_wil[$key]['blok'] = $sum_blok;
                    $skor_gm_wil[$key]['skor'] = $skor_tph_akhir + $skor_karung_akhir + $skor_buah_akhir + $skor_restan_akhir;

                    if ($key == 1) {
                        $estWil = 'WIL-I';
                    } else if ($key == 2) {
                        $estWil = 'WIL-II';
                    } else {
                        $estWil = 'WIL-III';
                    }

                    foreach ($queryAsisten as $key5 => $value5) {
                        if ($value5->est == $estWil && $value5->afd == 'GM') {
                            $skor_gm_wil[$key]['nama'] = $value4->nama;
                        }
                    }
                    if (empty($skor_gm_wil[$key]['nama'])) {
                        $skor_gm_wil[$key]['nama'] = '-';
                    }
                }

                $sum_wil_blok = 0;
                $sum_wil_brondolan = 0;
                $sum_wil_karung = 0;
                $sum_wil_buah_tinggal = 0;
                $sum_wil_restan = 0;

                foreach ($skor_gm_wil as $key => $value) {
                    $sum_wil_blok += $value['blok'];
                    $sum_wil_brondolan += $value['total_brondolan'];
                    $sum_wil_karung += $value['total_karung'];
                    $sum_wil_buah_tinggal += $value['total_buah_tinggal'];
                    $sum_wil_restan += $value['total_restan'];
                }

                $skor_total_wil_brondol = round($sum_wil_brondolan / $sum_wil_blok, 2);
                $skor_total_wil_karung = round($sum_wil_karung / $sum_wil_blok, 2);
                $skor_total_wil_buah_tinggal = round($sum_wil_buah_tinggal / $sum_wil_blok, 2);
                $skor_total_wil_restan = round($sum_wil_restan / $sum_wil_blok, 2);

                $skor_tph_akhir = 0;
                if ($skor_total_wil_brondol <= 18) {
                    $skor_tph_akhir = 30;
                } else if ($skor_total_wil_brondol >= 18 && $skor_total_wil_brondol <= 30) {
                    $skor_tph_akhir = 26;
                } else if ($skor_total_wil_brondol >= 30 && $skor_total_wil_brondol <= 42) {
                    $skor_tph_akhir = 22;
                } else if ($skor_total_wil_brondol >= 42 && $skor_total_wil_brondol <= 54) {
                    $skor_tph_akhir = 18;
                } else if ($skor_total_wil_brondol >= 54 && $skor_total_wil_brondol <= 66) {
                    $skor_tph_akhir = 14;
                } else if ($skor_total_wil_brondol >= 66 && $skor_total_wil_brondol <= 78) {
                    $skor_tph_akhir = 10;
                } else if ($skor_total_wil_brondol >= 78 && $skor_total_wil_brondol <= 90) {
                    $skor_tph_akhir = 6;
                } else if ($skor_total_wil_brondol >= 96) {
                    $skor_tph_akhir = 0;
                }


                //karung
                $skor_karung_akhir = 0;
                if ($skor_total_wil_karung <= 0) {
                    $skor_karung_akhir = 20;
                } else if ($skor_total_wil_karung >= 0 && $skor_total_wil_karung <= 3) {
                    $skor_karung_akhir = 17;
                } else if ($skor_total_wil_karung >= 3 && $skor_total_wil_karung <= 6) {
                    $skor_karung_akhir = 14;
                } else if ($skor_total_wil_karung >= 6 && $skor_total_wil_karung <= 9) {
                    $skor_karung_akhir = 11;
                } else if ($skor_total_wil_karung >= 9 && $skor_total_wil_karung <= 12) {
                    $skor_karung_akhir = 8;
                } else if ($skor_total_wil_karung >= 12 && $skor_total_wil_karung <= 15) {
                    $skor_karung_akhir = 5;
                } else if ($skor_total_wil_karung >= 15 && $skor_total_wil_karung <= 18) {
                    $skor_karung_akhir = 2;
                } else if ($skor_total_wil_karung >= 18) {
                    $skor_karung_akhir = 0;
                }
                //buah tinggal
                $skor_buah_akhir = 0;
                if ($skor_total_wil_buah_tinggal <= 0) {
                    $skor_buah_akhir = 20;
                } else if ($skor_total_wil_buah_tinggal >= 0 && $skor_total_wil_buah_tinggal <= 3) {
                    $skor_buah_akhir = 17;
                } else if ($skor_total_wil_buah_tinggal >= 3 && $skor_total_wil_buah_tinggal <= 6) {
                    $skor_buah_akhir = 14;
                } else if ($skor_total_wil_buah_tinggal >= 6 && $skor_total_wil_buah_tinggal <= 9) {
                    $skor_buah_akhir = 11;
                } else if ($skor_total_wil_buah_tinggal >= 9 && $skor_total_wil_buah_tinggal <= 12) {
                    $skor_buah_akhir = 8;
                } else if ($skor_total_wil_buah_tinggal >= 12 && $skor_total_wil_buah_tinggal <= 15) {
                    $skor_buah_akhir = 5;
                } else if ($skor_total_wil_buah_tinggal >= 15 && $skor_total_wil_buah_tinggal <= 18) {
                    $skor_buah_akhir = 2;
                } else if ($skor_total_wil_buah_tinggal >= 18) {
                    $skor_buah_akhir = 0;
                }

                //restant
                $skor_restan_akhir = 0;
                if ($skor_total_wil_restan <= 0) {
                    $skor_restan_akhir = 30;
                } else if ($skor_total_wil_restan >= 0 && $skor_total_wil_restan <= 3) {
                    $skor_restan_akhir = 26;
                } else if ($skor_total_wil_restan >= 3 && $skor_total_wil_restan <= 6) {
                    $skor_restan_akhir = 22;
                } else if ($skor_total_wil_restan >= 6 && $skor_total_wil_restan <= 9) {
                    $skor_restan_akhir = 18;
                } else if ($skor_total_wil_restan >= 9 && $skor_total_wil_restan <= 12) {
                    $skor_restan_akhir = 14;
                } else if ($skor_total_wil_restan >= 12 && $skor_total_wil_restan <= 15) {
                    $skor_restan_akhir = 10;
                } else if ($skor_total_wil_restan >= 15 && $skor_total_wil_restan <= 18) {
                    $skor_restan_akhir = 6;
                } else if ($skor_total_wil_restan >= 18 && $skor_total_wil_restan <= 21) {
                    $skor_restan_akhir = 2;
                } else if ($skor_total_wil_restan >= 21) {
                    $skor_restan_akhir = 0;
                }

                $skor_rh = array();
                foreach ($queryReg as $key => $value) {
                    if ($value == 1) {
                        $est = 'REG-I';
                    } else if ($value == 2) {
                        $est = 'REG-II';
                    } else {
                        $est = 'REG-III';
                    }
                    foreach ($queryAsisten as $key2 => $value2) {
                        if ($value2->est == $est && $value2->afd == 'RH') {
                            $skor_rh[$value]['nama'] = $value2->nama;
                        }
                    }
                    if (empty($skor_rh[$value]['nama'])) {
                        $skor_rh[$value]['nama'] = '-';
                    }
                    $skor_rh[$value]['skor'] =  $skor_tph_akhir + $skor_karung_akhir + $skor_buah_akhir + $skor_restan_akhir;
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
                        foreach ($queryAsisten as $key4 => $value4) {
                            if ($value4->est == $key2 && $value4->afd == 'EM') {
                                $list_all_est[$key][$inc]['nama'] = $value4->nama;
                            }
                        }
                        if (empty($list_all_est[$key][$inc]['nama'])) {
                            $list_all_est[$key][$inc]['nama'] = '-';
                        }
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
                // dd($list_all_est);

                //untuk chart!!!
                foreach ($queryGroup as $key => $value) {
                    $sum_bt_tph = 0;
                    $sum_bt_jalan = 0;
                    $sum_bt_bin = 0;
                    $skor_brd = 0;
                    $listBlokPerAfd = array();
                    foreach ($value as $val) {
                        if (!in_array($val->est . ' ' . $val->afd . ' ' . $val->blok, $listBlokPerAfd)) {
                            $listBlokPerAfd[] = $val->est . ' ' . $val->afd . ' ' . $val->blok;
                        }
                        $jum_blok = count($listBlokPerAfd);
                        $sum_bt_tph += $val->bt_tph;
                        $sum_bt_jalan += $val->bt_jalan;
                        $sum_bt_bin += $val->bt_bin;
                    }
                    $total_btt = ($sum_bt_tph + $sum_bt_jalan + $sum_bt_bin);
                    $skor_brd = round($total_btt / $jum_blok, 2);
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
                    if ($key == 1 || $key == 2 || $key == 3) {
                        $arrBtTPHperWil[$key] = round($sum_bt_tph / $skor_gm_wil[$key]['blok'], 2);
                    }
                }

                //sebelum di masukan ke aray harus looping karung berisi brondolan untuk mengambil datanya 2 lapis
                foreach ($queryGroupWil as $key => $value) {
                    $sum_jum_karung = 0;
                    foreach ($value as $key2 => $vale) {
                        $sum_jum_karung += $vale->jum_karung;
                    }
                    if ($key == 1 || $key == 2 || $key == 3) {
                        $arrKRestWil[$key] = round($sum_jum_karung / $skor_gm_wil[$key]['blok'], 2);
                    }
                }
                //looping buah tinggal 
                foreach ($queryGroupWil as $key => $value) {
                    $sum_buah_tinggal = 0;
                    foreach ($value as $key2 => $val2) {
                        $sum_buah_tinggal += $val2->buah_tinggal;
                    }
                    if ($key == 1 || $key == 2 || $key == 3) {
                        $arrBHestWil[$key] = round($sum_buah_tinggal / $skor_gm_wil[$key]['blok'], 2);
                    }
                }
                foreach ($queryGroupWil as $key => $value) {
                    $sum_restan_unreported = 0;
                    foreach ($value as $key2 => $val3) {
                        $sum_restan_unreported += $val3->restan_unreported;
                    }
                    if ($key == 1 || $key == 2 || $key == 3) {
                        $arrRestWill[$key] = round($sum_restan_unreported / $skor_gm_wil[$key]['blok'], 2);
                    }
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
            $arrView['list_skor_gm'] = $skor_gm_wil;
            $arrView['list_skor_rh'] = $skor_rh;
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
        $start = '2023-01-23';
        $last = '2023-01-29';



        $queryEste = DB::connection('mysql2')->table('estate')
            ->whereIn('wil', [1, 2, 3])
            ->get();

        $listEstaeWil1 = $queryEste;


        $listEstaeWil1 = json_decode($listEstaeWil1, true);

        $queryEste = $queryEste->groupBy(function ($item) {
            return $item->wil;
        });


        $queryEste = json_decode($queryEste, true);





        $queryAfd = DB::connection('mysql2')->Table('afdeling')
            ->select(
                'afdeling.id',
                'afdeling.nama',
                'estate.est'
            )
            ->join('estate', 'estate.id', '=', 'afdeling.estate')
            ->get();
        // dd($queryAfd);
        $queryAfd = json_decode($queryAfd, true);


        // dd($queryAfd);
        $querySidak = DB::connection('mysql2')->table('sidak_tph')
            ->whereBetween('sidak_tph.datetime', [$start, $last])
            // ->whereBetween('sidak_tph.datetime', ['2022-12-19', '2022-12-25'])
            ->get();
        $querySidak = json_decode($querySidak, true);
        $dataAfdEst = array();

        $querySidakPerEstate = DB::connection('mysql2')->table('sidak_tph')
            ->select('sidak_tph.*',  DB::raw("DATE_FORMAT(sidak_tph.datetime,'%Y-%m-%d') as tanggal"))
            ->whereBetween('sidak_tph.datetime', [$start, $last])
            // ->whereBetween('sidak_tph.datetime', ['2022-12-19', '2022-12-25'])
            ->get();



        // cari table pertanggal 
        $queryEste1 = $querySidakPerEstate->groupBy(function ($item) {
            return $item->tanggal;
        });

        $queryEste2 = $querySidakPerEstate->groupBy(function ($item) {
            return $item->est;
        });
        // dd($queryEste2);
        $queryEste1 = json_decode($queryEste1, true);
        $queryEste2 = json_decode($queryEste2, true);



        $dataPerEst = array();
        foreach ($listEstaeWil1 as $key => $value) {
            foreach ($queryEste1 as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    if ($value3['est'] == $value['est']) {
                        foreach ($queryAfd as $key4 => $value4) {
                            if ($value4['est'] == $value['est']) {
                                if ($value3['afd'] == $value4['nama']) {
                                    $str = substr($value3['blok'], 0, -2);
                                    if (strpos($str, '0') !== false) {
                                        $str = substr_replace($str, "", strpos($str, "0"), 1);
                                    }
                                    $dataPerEst[$key2][$value3['est']][$value4['nama']][$str] = $value3;
                                }
                            }
                        }
                    }
                }
            }
        }


        $grouped = array_reduce($listEstaeWil1, function ($acc, $item) {
            $acc[$item['wil']][] = $item;
            return $acc;
        }, []);

        // dd($queryEste2);

        $dataFoto = array();
        foreach ($grouped as $key => $value) {
            foreach ($queryEste2 as $key3 => $value3) {
                // if ($value3['est'] == $value2['est']) {
                //     $dataFoto[$key][$key3] = $value3;
                //     # code...
                // }
            }
        }


        // dd($dataFoto);





        foreach ($dataPerEst as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $sum_est_ha_sample = 0;
                $sum_est_ha_sample = 0;
                $sum_est_bt_sample = 0;
                $sum_est_JlBin_sample = 0;
                $sum_est_Karung_sample = 0;
                $sum_est_Buah_sample = 0;
                $sum_est_Restan_sample = 0;
                foreach ($value2 as $key3 => $value3) {
                    $sum_bt_tph = 0;
                    $sum_bt_jalan = 0;
                    $sum_bt_bin = 0;
                    $sum_jum_karung = 0;
                    $sum_buah_tinggal = 0;
                    $sum_restan_unreported = 0;
                    $sum_HA = 0;
                    $sum_all = 0;
                    if (is_array($value3)) {
                        foreach ($value3 as $key4 => $value4) {
                            // dd($value4);
                            $sum_bt_tph += $value4['bt_tph'];
                            $sum_bt_jalan += $value4['bt_jalan'];
                            $sum_bt_bin += $value4['bt_bin'];
                            $sum_HA += $value4['luas'];

                            $sum_jum_karung += $value4['jum_karung'];
                            $sum_buah_tinggal += $value4['buah_tinggal'];
                            $sum_restan_unreported += $value4['restan_unreported'];
                            $dataSkorAwalBlok[$key][$key2][$key3][$key4] = $value4;
                        }
                    }
                    $sum_all = $sum_bt_jalan + $sum_bt_bin;
                    $sum_est_bt_sample = $sum_bt_tph;
                    $sum_est_JlBin_sample = $sum_all;
                    $sum_est_Karung_sample = $sum_jum_karung;
                    $sum_est_Buah_sample = $sum_buah_tinggal;
                    $sum_est_Restan_sample = $sum_restan_unreported;
                    $sum_est_ha_sample = $sum_HA;
                    //  else {
                    $dataSkorAwalBlok[$key][$key2][$key3]['ha_total'] = $sum_est_ha_sample;
                    $dataSkorAwalBlok[$key][$key2][$key3]['brondolan_total'] = $sum_est_bt_sample;
                    $dataSkorAwalBlok[$key][$key2][$key3]['JalanBin_total'] = $sum_est_JlBin_sample;
                    $dataSkorAwalBlok[$key][$key2][$key3]['Karung_total'] = $sum_est_Karung_sample;
                    $dataSkorAwalBlok[$key][$key2][$key3]['BuahTinggal_total'] = $sum_est_Buah_sample;
                    $dataSkorAwalBlok[$key][$key2][$key3]['RestanUnreported_total'] = $sum_est_Restan_sample;
                }
            }
        }

        // dd($dataSkorAwalBlok);



        if (!empty($dataSkorAwalBlok)) {
            foreach ($listEstaeWil1 as $key => $value) {
                foreach ($dataSkorAwalBlok as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        if ($value['est'] == $key3) {
                            $dataSkorAwalBlok[$key2][$key3]['estate'] = $value['nama'];
                            $dataSkorAwalBlok[$key2][$key3]['tanggal'] = $key2;
                        }
                    }
                }
            }
        }





        $dataAfdEst = array();
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
                    if ($key3 == 'null') {;
                        unset($dataAfdEst[$key][$key2][$key3]);
                        if (empty($dataAfdEst[$key][$key2])) {
                            $dataAfdEst[$key][$key2] = 0;
                        }
                    }
                }
            }
        }

        // bagian untuk export ke foto 
        $DataFotoperWil = array();
        foreach ($queryEste as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($dataAfdEst as $key3 => $value3) {
                    if ($value2['est'] == $key3) {
                        $DataFotoperWil[$key][$key3] = $value3;
                    }
                }
            }
        }

        foreach ($DataFotoperWil as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3)
                    if (is_array($value3)) {

                        $DataFotoperWil[$key1][$key2][$key3] = reset($value3);
                    }
            }
        }



        // dd($DataFotoperWil);

        // endnya
        // cari table pertama 
        if (!isset($dataSkorAwal)) {
            $dataSkorAwal = array();
        }
        foreach ($dataAfdEst as $key => $value) {
            //...
            $sum_est_ha_sample = 0;
            $sum_est_bt_sample = 0;
            $sum_est_JlBin_sample = 0;
            $sum_est_Karung_sample = 0;
            $sum_est_Buah_sample = 0;
            $sum_est_Restan_sample = 0;
            foreach ($value as $key2 => $value2) {
                $sum_bt_tph = 0;
                $sum_bt_jalan = 0;
                $sum_bt_bin = 0;
                $sum_jum_karung = 0;
                $sum_buah_tinggal = 0;
                $sum_restan_unreported = 0;
                $sum_ha_sample = 0;
                if (is_array($value2)) {
                    foreach ($value2 as $key3 => $value3) {

                        $sum_bt_tph += $value3['bt_tph'];
                        $sum_bt_jalan += $value3['bt_jalan'];
                        $sum_bt_bin += $value3['bt_bin'];

                        $sum_ha_sample += $value3['luas'];

                        $sum_jum_karung += $value3['jum_karung'];
                        $sum_buah_tinggal += $value3['buah_tinggal'];
                        $sum_restan_unreported += $value3['restan_unreported'];
                    }

                    $dataSkorAwal[$key][$key2]['tph'] = $sum_bt_tph;
                    $dataSkorAwal[$key][$key2]['ha_sample'] = $sum_ha_sample;
                    $dataSkorAwal[$key][$key2]['jalan_bin'] = $sum_bt_jalan + $sum_bt_bin;
                    $dataSkorAwal[$key][$key2]['karung'] = $sum_jum_karung;
                    $dataSkorAwal[$key][$key2]['buah_tinggal'] = $sum_buah_tinggal;
                    $dataSkorAwal[$key][$key2]['restan_unreported'] = $sum_restan_unreported;
                } else {
                    $dataSkorAwal[$key][$key2]['tph'] = 0;
                    $dataSkorAwal[$key][$key2]['ha_sample'] = 0;
                    $dataSkorAwal[$key][$key2]['jalan_bin'] = 0;
                    $dataSkorAwal[$key][$key2]['karung'] = 0;
                    $dataSkorAwal[$key][$key2]['buah_tinggal'] = 0;
                    $dataSkorAwal[$key][$key2]['restan_unreported'] = 0;
                }
                $sum_est_ha_sample += $sum_ha_sample;
                $sum_est_bt_sample += $sum_bt_tph;
                $sum_est_JlBin_sample += $sum_bt_jalan + $sum_bt_bin;
                $sum_est_Karung_sample += $sum_jum_karung;
                $sum_est_Buah_sample += $sum_buah_tinggal;
                $sum_est_Restan_sample += $sum_restan_unreported;
            }
            $dataSkorAwal[$key]['ha_total'] = $sum_est_ha_sample;
            $dataSkorAwal[$key]['brondolan_total'] = $sum_est_bt_sample;
            $dataSkorAwal[$key]['JalanBin_total'] = $sum_est_JlBin_sample;
            $dataSkorAwal[$key]['Karung_total'] = $sum_est_Karung_sample;
            $dataSkorAwal[$key]['BuahTinggal_total'] = $sum_est_Buah_sample;
            $dataSkorAwal[$key]['RestanUnreported_total'] = $sum_est_Restan_sample;
        }


        // dd($dataAfdEst);
        // if (empty($dataSkorAwal && $dataSkorAwalBlok)) {
        //     // echo "No data to display";
        //     return redirect()->route('404')->with('status', 'No data to display');
        // } else {
        $dataSkorAkhirPerWil = array();
        foreach ($queryEste as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($dataSkorAwal as $key3 => $value3) {
                    if ($value2['est'] == $key3) {
                        $dataSkorAkhirPerWil[$key][$key3] = $value3;
                    }
                }
            }
        }


        // dd($dataSkorAkhirPerWil);
        // cari table highest finding
        $max_brd = [];
        foreach ($dataSkorAkhirPerWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $max_tph = 0;
                $max_tph_id = 0;
                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3) && $value3['tph'] > $max_tph) {
                        $max_tph = $value3['tph'];
                        $max_tph_id = $key3;
                    }
                }
                if ($max_tph > 0) {
                    $max_brd[$key][$key2][$max_tph_id]['brd_max'] = $max_tph;
                    $max_wil_1[$key]['brd_max'] = $max_tph;
                }
            }
        }
        // dd($max_wil_1);
        $max_brd_fix = [];
        foreach ($max_brd as $key => $value) {
            $max_tph = 0;
            $max_tph_id = 0;
            foreach ($value as $key2 => $value2) {

                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3) && $value3['brd_max'] > $max_tph) {
                        $max_tph = $value3['brd_max'];
                        $max_tph_id = $key3;
                    }
                }
            }
            if ($max_tph > 0) {
                $max_brd_fix[$key][$key2][$max_tph_id]['brondolan_maxx'] = $max_tph;
            } else {
                $max_brd_fix[$key][$key2][$max_tph_id]['brondolan_maxx'] = 0;
            }
        }
        // dd($max_brd_fix)
        // menghitung karung 
        $max_Karung = [];
        foreach ($dataSkorAkhirPerWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $max_tph = 0;
                $max_tph_id = 0;
                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3) && $value3['karung'] > $max_tph) {
                        $max_tph = $value3['karung'];
                        $max_tph_id = $key3;
                    }
                }
                if ($max_tph > 0) {
                    $max_Karung[$key][$key2][$max_tph_id]['krg_max'] = $max_tph;
                    $max_wil_1[$key]['krg_max'] = $max_tph;
                }
            }
        }
        $max_krg_fix = [];
        foreach ($max_Karung as $key => $value) {
            $max_krg = 0;
            $max_krg_id = 0;
            foreach ($value as $key2 => $value2) {

                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3) && $value3['krg_max'] > $max_krg) {
                        $max_krg = $value3['krg_max'];
                        $max_krg_id = $key3;
                    }
                }
            }
            if ($max_krg > 0) {
                $max_krg_fix[$key][$key2][$max_krg_id]['karung_max'] = $max_krg;
            } else {
                $max_krg_fix[$key][$key2][$max_krg_id]['karung_max'] = 0;
            }
        }
        //menghitung buah tinggal
        $max_buahTgl = [];
        foreach ($dataSkorAkhirPerWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $max_tph = 0;
                $max_tph_id = 0;
                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3) && $value3['buah_tinggal'] > $max_tph) {
                        $max_tph = $value3['buah_tinggal'];
                        $max_tph_id = $key3;
                    }
                }
                if ($max_tph > 0) {
                    $max_buahTgl[$key][$key2][$max_tph_id]['buahTGL_max'] = $max_tph;
                    $max_wil_1[$key]['buahTGL_max'] = $max_tph;
                }
            }
        }
        $max_buahtgal_fix = [];
        foreach ($max_buahTgl as $key => $value) {
            $max_bhTgl = 0;
            $max_bhTgl_id = 0;
            foreach ($value as $key2 => $value2) {

                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3) && $value3['buahTGL_max'] > $max_bhTgl) {
                        $max_bhTgl = $value3['buahTGL_max'];
                        $max_bhTgl_id = $key3;
                    }
                }
            }
            if ($max_bhTgl > 0) {
                $max_buahtgal_fix[$key][$key2][$max_bhTgl_id]['buah_tgl_max_fix'] = $max_bhTgl;
            } else {
                $max_buahtgal_fix[$key][$key2][$max_bhTgl_id]['buah_tgl_max_fix'] = 0;
            }
        }
        //buah restant
        $max_restant = [];
        foreach ($dataSkorAkhirPerWil as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $max_tph = 0;
                $max_tph_id = 0;

                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3) && $value3['restan_unreported'] > $max_tph) {
                        $max_tph = $value3['restan_unreported'];
                        $max_tph_id = $key3;
                    }
                }
                if ($max_tph > 0) {
                    $max_restant[$key][$key2][$max_tph_id]['restant_max'] = $max_tph;
                    $max_wil_1[$key]['restant_max'] = $max_tph;
                }
            }
        }

        $max_restanFix = [];
        foreach ($max_restant as $key => $value) {
            $max_bhTgl = 0;
            $max_bhTgl_id = 0;
            foreach ($value as $key2 => $value2) {

                foreach ($value2 as $key3 => $value3) {
                    if (is_array($value3) && $value3['restant_max'] > $max_bhTgl) {
                        $max_bhTgl = $value3['restant_max'];
                        $max_bhTgl_id = $key3;
                    }
                }
            }
            if ($max_bhTgl > 0) {
                $max_restanFix[$key][$key2][$max_bhTgl_id]['restant_max_fix'] = $max_bhTgl;
            } else {
                $max_restanFix[$key][$key2][$max_bhTgl_id]['restant_max_fix'] = 0;
            }
        }

        // membuat bagian untuk export chart ke pdf



        // end chart

        //hasil akhir  untuk di kirim ke blade view

        //foto akhir
        $will_1_foto = isset($DataFotoperWil) ? $DataFotoperWil : '';
        // dd($will_1_foto);
        //


        // dd($dataSkorAkhirPerWil);
        $wil_1_sidak_tph = $dataSkorAkhirPerWil[1];
        $wil_2_sidak_tph = $dataSkorAkhirPerWil[2];
        $wil_3_sidak_tph = $dataSkorAkhirPerWil[3];

        dd($dataSkorAkhirPerWil);
        //untuk max id wil 1



        $wil_1_sidak_tph_max = isset($max_brd_fix[1]) ? $max_brd_fix[1] : '';
        $wil_1_sidak_krng_max = isset($max_krg_fix[1]) ? $max_krg_fix[1] : '';
        $wil_1_sidak_buah_max = isset($max_buahtgal_fix[1]) ? $max_buahtgal_fix[1] : '';
        $wil_1_sidak_restant_max = isset($max_restanFix[1]) ? $max_restanFix[1] : '';
        //untuk max id wil 3
        $wil_2_sidak_tph_max = isset($max_brd_fix[2]) ? $max_brd_fix[2] : '';
        $wil_2_sidak_krng_max = isset($max_krg_fix[2]) ? $max_krg_fix[2] : '';
        $wil_2_sidak_buah_max = isset($max_buahtgal_fix[2]) ? $max_buahtgal_fix[2] : '';
        $wil_2_sidak_restant_max = isset($max_restanFix[2]) ? $max_restanFix[2] : '';
        // //untuk max id wil 3
        $wil_3_sidak_tph_max = isset($max_brd_fix[3]) ? $max_brd_fix[3] : '';
        $wil_3_sidak_krng_max = isset($max_krg_fix[3]) ? $max_krg_fix[3] : '';
        $wil_3_sidak_buah_max = isset($max_buahtgal_fix[3]) ? $max_buahtgal_fix[3] : '';
        $wil_3_sidak_restant_max = isset($max_restanFix[3]) ? $max_restanFix[3] : '';

        // data untuk menampilkan data pertanggal di table
        // dd($dataSkorAwalBlok);
        $dataFixSkorAwalBlok = array();
        foreach ($dataSkorAwalBlok as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $dataFixSkorAwalBlok[] = $value2;
            }
        }

        // dd($dataFixSkorAwalBlok);
        $DataPerTanggal = isset($dataFixSkorAwalBlok) ? $dataFixSkorAwalBlok : '';

        // $DataPerTanggal = isset($dataSkorAwalBlok) ? $dataSkorAwalBlok : '';

        // dd($wil_1_sidak_tph_max, $wil_1_sidak_krng_max);
        // $pdf = PDF::loadView('cetakSidak', [
        //     'wil_1_sidak_tph' => $wil_1_sidak_tph,
        //     'wil_2_sidak_tph' => $wil_2_sidak_tph,
        //     'wil_3_sidak_tph' => $wil_3_sidak_tph,
        //     'start' => $start,
        //     'last' => $last,
        //     'wil_1_sidak_tph_max' => $wil_1_sidak_tph_max,
        //     'wil_1_sidak_krng_max' => $wil_1_sidak_krng_max,
        //     'wil_1_sidak_buah_max' => $wil_1_sidak_buah_max,
        //     'wil_1_sidak_restant_max' => $wil_1_sidak_restant_max,
        //     'wil_2_sidak_tph_max' => $wil_2_sidak_tph_max,
        //     'wil_2_sidak_krng_max' => $wil_2_sidak_krng_max,
        //     'wil_2_sidak_buah_max' => $wil_2_sidak_buah_max,
        //     'wil_2_sidak_restant_max' => $wil_2_sidak_restant_max,
        //     'wil_3_sidak_tph_max' => $wil_3_sidak_tph_max,
        //     'wil_3_sidak_krng_max' => $wil_3_sidak_krng_max,
        //     'wil_3_sidak_buah_max' => $wil_3_sidak_buah_max,
        //     'wil_3_sidak_restant_max' => $wil_3_sidak_restant_max,
        //     'DataPerTanggal' => $DataPerTanggal,
        //     'will_1_foto' => $will_1_foto

        // ]);






        // $pdf->set_paper('A3', 'potrait');
        // $pdf->setOption('enable-javascript', true);
        // $filename = 'REKAPITULASI Pemeriksaan TPH & BIN Reg-I Tanggal: ' . $start . ' sampai ' . $last . '.pdf';
        // $filename = 'REKAPITULASI-' .$start . '-' .$last '.pdf';

        // return $pdf->stream($filename, array('Attachment' => 0));
        //     // dd($request);
        // $data = $request->chartData;
        // $pdf = PDF::loadView('cetakSidak', ['data' => $data]);
        // return $pdf->stream('charts.pdf');
        // }
    }

    public function notfound()
    {

        return view('404');
    }

    public function detailSidakTph($est, $afd, $start, $last)
    {
        $query = DB::connection('mysql2')->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->where('sidak_tph.est', $est)
            ->where('sidak_tph.afd', $afd)
            ->whereBetween('sidak_tph.datetime', [$start, $last])
            ->get();

        $query = $query->groupBy(function ($item) {
            return $item->blok;
        });

        $blok = $query;
        $blok = json_decode($blok, true);
        ksort($blok);

        $datas = array();
        $img = array();
        foreach ($query as $key => $value) {
            $inc = 0;
            foreach ($value as $key2 => $value2) {
                $datas[] = $value2;
                if (!empty($value2->foto_temuan)) {
                    $img[$key][$inc]['foto'] = $value2->foto_temuan;
                    $img[$key][$inc]['title'] = $value2->est . ' ' .  $value2->afd . ' - ' . $value2->blok;
                    $inc++;
                }
            }
        }

        $imgNew = array();
        foreach ($img as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $imgNew[] = $value2;
            }
        }

        return view('detailSidakTPH', ['est' => $est, 'afd' => $afd, 'start' => $start, 'last' => $last, 'data' => $datas, 'img' => $imgNew, 'blok' => $blok]);
    }

    public function getDetailTPH(Request $request)
    {
        $afd = $request->get('afd');
        $est = $request->get('est');
        $start = $request->get('start');
        $last = $request->get('last');
        $blok = $request->get('blok');

        $query = DB::connection('mysql2')->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->where('sidak_tph.est', $est)
            ->where('sidak_tph.afd', $afd)
            ->whereBetween('sidak_tph.datetime', [$start, $last])
            ->get();

        if ($blok != 'Semua Blok') {
            $query = $query->where('blok', $blok);
        }

        $query = $query->groupBy(function ($item) {
            return $item->blok;
        });

        $datas = array();
        foreach ($query as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $datas[] = $value2;
            }
        }

        $json_data['data'] = $datas;
        echo json_encode($json_data);
    }

    public function getPlotLine(Request $request)
    {
        $afd = $request->get('afd');
        $est = $request->get('est');
        $start = $request->get('start');
        $last = $request->get('last');

        $query = DB::connection('mysql2')->Table('sidak_tph')
            ->select('sidak_tph.*', 'estate.wil') //buat mengambil data di estate db dan willayah db
            ->join('estate', 'estate.est', '=', 'sidak_tph.est') //kemudian di join untuk mengambil est perwilayah
            ->where('sidak_tph.est', $est)
            ->where('sidak_tph.afd', $afd)
            ->whereBetween('sidak_tph.datetime', [$start, $last])
            ->get();

        $query = $query->groupBy(function ($item) {
            return $item->blok;
        });

        $datas = array();
        $img = array();
        foreach ($query as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $datas[] = $value2;
                if (!empty($value2->foto_temuan)) {
                    $img[] = $value2->foto_temuan;
                }
            }
        }

        $plotTitik = array();
        $plotMarker = array();
        $inc = 0;

        foreach ($datas as $key => $value) {


            if (!empty($value->lat)) {
                $plotTitik[] =  '[' . $value->lon . ',' . $value->lat     . ']';
                $plotMarker[$inc]['latln'] =  '[' . $value->lat   . ',' . $value->lon . ']';
                $plotMarker[$inc]['notph'] = $value->no_tph;
                $plotMarker[$inc]['blok'] = $value->blok;
                $plotMarker[$inc]['brondol_tinggal'] = $value->bt_tph + $value->bt_jalan + $value->bt_bin;
                $plotMarker[$inc]['jum_karung'] = $value->jum_karung;
                $plotMarker[$inc]['buah_tinggal'] = $value->buah_tinggal;
                $plotMarker[$inc]['restan_unreported'] = $value->restan_unreported;
                $plotMarker[$inc]['jam'] = Carbon::parse($value->datetime)->format('H:i');
            }
            $inc++;
        }

        // dd($plotMarker);

        $list_blok = array();
        foreach ($datas as $key => $value) {
            $list_blok[$est][] = $value->blok;
        }

        $blokPerEstate = array();
        $estateQuery = DB::connection('mysql2')->Table('estate')
            ->join('afdeling', 'afdeling.estate', 'estate.id')
            ->where('est', $est)->get();

        $listIdAfd = array();
        // dd($estateQuery);

        foreach ($estateQuery as $key => $value) {

            $blokPerEstate[$est][$value->nama] =  DB::connection('mysql2')->Table('blok')
                // ->join('blok', 'blok.afdeling', 'afdeling.id')
                // ->where('afdeling.estate', $value->id)->get();
                ->where('afdeling', $value->id)->pluck('nama', 'id');
            $listIdAfd[] = $value->id;
        }

        // dd($blokPerEstate);


        $result_list_blok = array();
        foreach ($list_blok as $key => $value) {
            foreach ($value as $key2 => $data) {
                if (strlen($data) == 5) {
                    $result_list_blok[$key][$data] = substr($data, 0, -2);
                } else if (strlen($data) == 6) {
                    $sliced = substr_replace($data, '', 1, 1);
                    $result_list_blok[$key][$data] = substr($sliced, 0, -2);
                } else if (strlen($data) == 3) {
                    $result_list_blok[$key][$data] = $data;
                } else if (strpos($data, 'CBI') !== false) {
                    $result_list_blok[$key][$data] = substr($data, 0, -4);
                } else if (strpos($data, 'CB') !== false) {
                    $sliced = substr_replace($data, '', 1, 1);
                    $result_list_blok[$key][$data] = substr($sliced, 0, -3);
                }
            }
        }

        $result_list_all_blok = array();
        foreach ($blokPerEstate as $key2 => $value) {
            foreach ($value as $key3 => $afd) {
                foreach ($afd as $key4 => $data) {
                    if (strlen($data) == 4) {
                        $result_list_all_blok[$key2][] = substr_replace($data, '', 1, 1);
                    }
                }
            }
        }

        // //bandingkan list blok query dan list all blok dan get hanya blok yang cocok
        $result_blok = array();
        if (array_key_exists($est, $result_list_all_blok)) {
            $query = array_unique($result_list_all_blok[$est]);
            $result_blok[$est] = array_intersect($result_list_blok[$est], $query);
        }
        // dd($result_list_blok, $result_blok, $listIdAfd);


        //get lat lang dan key $result_blok atau semua list_blok

        $blokLatLn = array();

        foreach ($result_list_blok as $key => $value) {
            $inc = 0;
            foreach ($value as $key2 => $data) {
                $newData = substr_replace($data, '0', 1, 0);
                $query = '';
                $query = DB::connection('mysql2')->table('blok')
                    ->select('blok.*')
                    // ->where('blok.nama', $newData)
                    // ->orWhere('blok.nama', $data)
                    ->whereIn('blok.afdeling', $listIdAfd)
                    ->get();

                // dd($newData, $data);

                $latln = '';
                foreach ($query as $key3 => $val) {
                    if ($val->nama == $newData || $val->nama == $data) {
                        $latln .= '[' . $val->lon . ',' . $val->lat . '],';
                    }
                }

                $estate = DB::connection('mysql2')->table('estate')
                    ->select('estate.*')
                    ->where('estate.est', $est)
                    ->first();

                $nama_estate = $estate->nama;

                $blokLatLn[$inc]['blok'] = $key2;
                $blokLatLn[$inc]['estate'] = $nama_estate;
                $blokLatLn[$inc]['latln'] = rtrim($latln, ',');
                $inc++;
            }
        }

        // dd($plotTitik);
        $plot['plot'] = $plotTitik;
        $plot['marker'] = $plotMarker;
        $plot['blok'] = $blokLatLn;
        // dd($plot);
        echo json_encode($plot);
    }


    public function hapusDetailSidak(Request $request)
    {
        $ids = $request->input('ids');
        $start = $request->input('start');
        $last = $request->input('last');
        $est = $request->input('est');
        $afd = $request->input('afd');

        if (is_array($ids)) {
            // Delete each item with the corresponding id
            foreach ($ids as $id) {
                DB::connection('mysql2')->table('sidak_tph')
                    ->where('id', $id)
                    ->delete();
            }
        } else {
            // If only one id is present, delete the item with that id
            DB::connection('mysql2')->table('sidak_tph')
                ->where('id', $ids)
                ->delete();
        }

        session()->flash('status', 'Data Sidak berhasil dihapus!');
        return redirect()->route('detailSidakTph', ['est' => $est, 'afd' => $afd, 'start' => $start, 'last' => $last]);
    }
}
