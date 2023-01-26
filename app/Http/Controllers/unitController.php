<?php

namespace App\Http\Controllers;

// use Barryvdh\DomPDF\PDF as DomPDFPDF;

use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Nette\Utils\DateTime;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

// use \PDF;
class unitController extends Controller
{
    public function index()
    {
        $year = $request->get('year');
        $regional = $request->get('regional');

        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $queryEstate = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->get();

        $queryEstate = json_decode($queryEstate, true);

        $dataRaw = array();

        foreach ($queryEstate as $value) {

            // dd($year);
            $queryPerEstate = DB::connection('mysql2')->table('qc_gudang')
                ->select("qc_gudang.*", DB::raw('DATE_FORMAT(qc_gudang.tanggal, "%M") as bulan'))
                ->where('unit', $value['id'])
                ->whereYear('tanggal', $year)
                ->get();



            if ($queryPerEstate->first() != '') {

                $inc = 0;
                $total_skor = 0;
                foreach ($queryPerEstate as $key2 => $val) {

                    foreach ($bulan as $key => $val2) {
                        if ($val2 == $val->bulan) {
                            // if ($total_skor != 0) {
                            // $dataRaw[$value['est']][$val2]['skor_rerata'] = $total_skor / $inc;
                            // }
                            // $total_skor = $total_skor + $val->skor_total;
                            // $dataRaw[$value['est']][$val2]['total'] = $total_skor;
                            $dataRaw[$value['est']][$val2][$val->id] = $val->skor_total;
                        } else {
                            $dataRaw[$value['est']][$val2][] = 0;
                        }
                    }
                    $inc++;
                }
            } else {
                $dataRaw[$value['est']] = 'kosong';
            }
        }

        // dd($dataRaw);

        $dataResult = array();
        $countDataPerEstate = array();
        foreach ($dataRaw as $key => $value) {
            if ($value != 'kosong') {
                $total_skor = 0;
                $total_bulan = 0;
                $inc_bulan = 0;
                foreach ($value as $key2 => $data) {
                    $inc = 0;
                    $inc_count_data = 0;
                    // dd($data);
                    $inc_count_data_2 = 1;
                    foreach ($data as $key3 => $val) {

                        $total_skor = $total_skor + $val;
                        if ((int)$val != 0) {
                            // $dataResult[$key][$key2][$key3] = $val;
                            $dataResult[$key][$key2][$key3] = $val;

                            $inc++;
                            $inc_count_data_2++;
                            $inc_count_data++;
                        }
                        $countDataPerEstate[$key][$key2] = $inc_count_data;
                    }

                    $skor = 0;
                    if ($inc != 0) {
                        $skor = round($total_skor / $inc, 2);
                        // $dataResult[$key][$key2] = $skor;
                        $dataResult[$key]['skor_bulan_' . $key2] = $skor;
                    } else {
                        $dataResult[$key][$key2] = 0;
                        $dataResult[$key]['skor_bulan_' . $key2] = $skor;
                    }
                    $total_skor = 0;
                    $total_bulan = $total_bulan + $skor;
                    $inc_bulan++;
                }
                $skor_tahunan = round($total_bulan / $inc_bulan, 2);
                $dataResult[$key]['skor_tahunan'] = $skor_tahunan;
                if ($skor_tahunan >= 95) {
                    $dataResult[$key]['status'] = 'Excellent';
                } else if ($skor_tahunan >= 85 && $skor_tahunan < 95) {
                    $dataResult[$key]['status'] = 'Good';
                } else if ($skor_tahunan >= 75 && $skor_tahunan < 85) {
                    $dataResult[$key]['status'] = 'Satisfactory';
                } else if ($skor_tahunan >= 65 && $skor_tahunan < 75) {
                    $dataResult[$key]['status'] = 'Fair';
                } else if ($skor_tahunan < 65) {
                    $dataResult[$key]['status'] = 'Poor';
                }

                $estateQuery = DB::connection('mysql2')->table('estate')
                    ->select('estate.*')
                    ->join('wil', 'wil.id', '=', 'estate.wil')
                    ->where('estate.est', $key)
                    ->first();

                $dataResult[$key]['estate'] = $estateQuery->nama;
                $wilayah =  $estateQuery->wil;
                $dataResult[$key]['wilayah'] = $wilayah;
                if ($wilayah == 1)  $dataResult[$key]['wil'] = 'I';
                else if ($wilayah == 2)  $dataResult[$key]['wil'] = 'II';
                else if ($wilayah == 3)  $dataResult[$key]['wil'] = 'III';
                else if ($wilayah == 4)  $dataResult[$key]['wil'] = 'IV';
                else if ($wilayah == 5)  $dataResult[$key]['wil'] = 'V';
                else if ($wilayah == 6)  $dataResult[$key]['wil'] = 'VI';
                else if ($wilayah == 7)  $dataResult[$key]['wil'] = 'VII';
                else if ($wilayah == 8)  $dataResult[$key]['wil'] = 'VIII';
                $dataResult[$key]['est'] = $estateQuery->est;
            } else {
                foreach ($bulan as $key4 => $value) {
                    $dataResult[$key][$value] = 0;
                    $dataResult[$key]['skor_bulan_' . $value] = 0;
                }
                $estateQuery = DB::connection('mysql2')->table('estate')
                    ->select('estate.*')
                    ->join('wil', 'wil.id', '=', 'estate.wil')
                    ->where('estate.est', $key)
                    ->first();
                $dataResult[$key]['estate'] = $estateQuery->nama;
                $dataResult[$key]['est'] = $estateQuery->est;
                $wilayah =  $estateQuery->wil;
                $dataResult[$key]['wilayah'] = $wilayah;
                if ($wilayah == 1)  $dataResult[$key]['wil'] = 'I';
                else if ($wilayah == 2)  $dataResult[$key]['wil'] = 'II';
                else if ($wilayah == 3)  $dataResult[$key]['wil'] = 'III';
                else if ($wilayah == 4)  $dataResult[$key]['wil'] = 'IV';
                else if ($wilayah == 5)  $dataResult[$key]['wil'] = 'V';
                else if ($wilayah == 6)  $dataResult[$key]['wil'] = 'VI';
                else if ($wilayah == 7)  $dataResult[$key]['wil'] = 'VII';
                else if ($wilayah == 8)  $dataResult[$key]['wil'] = 'VIII';
                $dataResult[$key]['skor_tahunan'] = 0;
                $dataResult[$key]['status'] = 'Poor';
            }
        }
        //khusus untuk menghitung record setiap bulan per estate
        // dd($countDataPerEstate);
        $resultCountMax = array();
        foreach ($bulan as $key => $value) {
            foreach ($countDataPerEstate as $key2 => $val) {

                if (array_key_exists($value, $val)) {
                    $resultCountMax[$value] = max(array_column($countDataPerEstate, $value));
                }
            }
        }

        // dd($bulan);
        // dd($resultCountMax);
        $resultCount = array();
        foreach ($resultCountMax as $key => $value) {
            if ($value == 0 || $value == 1) {
                $resultCount[$key] = 1;
            } else {
                $resultCount[$key] = $value;
            }
        }

        // dd($resultCount);

        $resultCountJson = json_encode($resultCount);
        // dd($resultCountJson);
        $total_column = 0;
        foreach ($resultCount as $key => $value) {
            $total_column = $total_column + $value;
        }

        // dd($total_column);

        $total_column_bulan = $total_column + 12;
        array_multisort(array_column($dataResult, 'skor_tahunan'), SORT_DESC, $dataResult);
        $inc = 1;
        foreach ($dataResult as $key => $value) {
            foreach ($value as $key2 => $data) {
                $dataResult[$key]['rank'] = $inc;
            }
            $inc++;
        }
        array_multisort(array_column($dataResult, 'wilayah'), SORT_ASC, $dataResult);

        foreach ($resultCount as $key => $value) {
            foreach ($dataResult as $key2 => $data) {
                if (array_key_exists($key, $data)) {
                    if ($value != 1) {
                        if (is_array($data[$key])) {
                            if (count($data[$key]) != $value) {
                                // dd(count($data[$key]));
                                for ($i = 0; $i < $value - count($data[$key]); $i++) {
                                    $dataResult[$key2][$key]['null' . $i] = '-';
                                }
                            }
                        } else {
                            unset($dataResult[$key2][$key]);
                            for ($i = 0; $i < $value; $i++) {
                                $dataResult[$key2][$key]['null' . $i] = '-';
                            }
                        }
                    }
                }
            }
        }

        $arrView = array();
        $arrId = array();
        foreach ($dataResult as $key => $value) {
            $arrView[$key][] = $value['wil'];
            $arrId[$key][] = '-';
            $arrView[$key][] = $value['estate'];
            $arrId[$key][] = '-';
            $arrView[$key][] = $value['est'];
            $arrId[$key][] = '-';
            $arrView[$key][] = $value['wil'];
            $arrId[$key][] = '-';
            $arrView[$key][] = $value['wil'];
            $arrId[$key][] = '-';
            foreach ($bulan as $key2 => $data) {
                if (is_array($value[$data])) {
                    $inc = 0;
                    foreach ($value[$data] as $key3 => $val) {
                        $arrView[$key][] = $val;
                        $arrId[$key][] = $key3;
                        $inc++;
                    }
                } else {
                    $arrView[$key][] = '-';
                    $arrId[$key][] = 0;
                }
                $arrView[$key][] = $value['skor_bulan_' . $data];
                $arrId[$key][] = '-';
            }
            $arrView[$key][] = $value['skor_tahunan'];
            $arrId[$key][] = '-';
            $arrView[$key][] = $value['status'];
            $arrId[$key][] = '-';
            $arrView[$key][] = $value['rank'];
            $arrId[$key][] = '-';
        }

        // dd($arrId);
        // dd($arrView);
        $arrHeader = array();
        $arrHeader = ['WILAYAH', 'ESTATE', 'KODE', 'KTU', 'EM'];
        if (empty($resultCount)) {
            foreach ($bulan as $key => $value) {
                $resultCount[$value] = 1;
            }
        }

        foreach ($resultCount as $key => $value) {

            if ($value > 1) {
                for ($i = 1; $i <= $value; $i++) {
                    $arrHeader[] = $i;
                }
            } else {
                $arrHeader[] = '1';
            }
            $arrHeader[] = 'SKOR';
        }
        array_push($arrHeader, 'SKOR', 'STATUS', 'RANK');

        $arrResult['arrView'] = $arrView;
        $arrResult['arrId'] = $arrId;
        $arrResult['arrHeader'] = $arrHeader;
        $arrResult['arrMonth'] = $bulan;
        echo json_encode($arrResult);
    }
    public function dashboard_sidak_tph()
    {
        $query = DB::table('sidak_tph')
            ->select("sidak_tph.*")
            ->get();

        return view('dashboard_sidak_tph');
    }

    public function getDataByYear(Request $request)
    {
        $year = $request->get('year');
        $regional = $request->get('regional');

        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $queryEstate = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', $regional)
            ->get();

        $queryEstate = json_decode($queryEstate, true);

        $dataRaw = array();

        foreach ($queryEstate as $value) {

            // dd($year);
            $queryPerEstate = DB::connection('mysql2')->table('qc_gudang')
                ->select("qc_gudang.*", DB::raw('DATE_FORMAT(qc_gudang.tanggal, "%M") as bulan'))
                ->where(function ($query) use ($value) {
                    $query->where('unit', '=', $value['id'])
                        ->orWhere('unit', '=', $value['est']);
                })
                ->whereYear('tanggal', $year)
                ->get();



            if ($queryPerEstate->first() != '') {

                $inc = 0;
                $total_skor = 0;
                foreach ($queryPerEstate as $key2 => $val) {

                    foreach ($bulan as $key => $val2) {
                        if ($val2 == $val->bulan) {
                            // if ($total_skor != 0) {
                            // $dataRaw[$value['est']][$val2]['skor_rerata'] = $total_skor / $inc;
                            // }
                            // $total_skor = $total_skor + $val->skor_total;
                            // $dataRaw[$value['est']][$val2]['total'] = $total_skor;
                            $dataRaw[$value['est']][$val2][$val->id] = $val->skor_total;
                        } else {
                            $dataRaw[$value['est']][$val2][] = 0;
                        }
                    }
                    $inc++;
                }
            } else {
                $dataRaw[$value['est']] = 'kosong';
            }
        }

        // dd($dataRaw);

        $dataResult = array();
        $countDataPerEstate = array();
        $bulanKe = Carbon::now()->month;
        foreach ($dataRaw as $key => $value) {
            if ($value != 'kosong') {
                $total_skor = 0;
                $total_bulan = 0;
                $inc_bulan = 0;
                foreach ($value as $key2 => $data) {
                    $inc = 0;
                    $inc_count_data = 0;
                    // dd($data);
                    $inc_count_data_2 = 1;
                    foreach ($data as $key3 => $val) {

                        $total_skor = $total_skor + $val;
                        if ((int)$val != 0) {
                            // $dataResult[$key][$key2][$key3] = $val;
                            $dataResult[$key][$key2][$key3] = $val;

                            $inc++;
                            $inc_count_data_2++;
                            $inc_count_data++;
                        }
                        $countDataPerEstate[$key][$key2] = $inc_count_data;
                    }

                    $skor = 0;
                    if ($inc != 0) {
                        $skor = round($total_skor / $inc, 2);
                        // $dataResult[$key][$key2] = $skor;
                        $dataResult[$key]['skor_bulan_' . $key2] = $skor;
                    } else {
                        $dataResult[$key][$key2] = 0;
                        $dataResult[$key]['skor_bulan_' . $key2] = $skor;
                    }
                    $total_skor = 0;
                    $total_bulan = $total_bulan + $skor;
                    $inc_bulan++;
                }
                $skor_tahunan = round($total_bulan / $bulanKe, 2);

                $dataResult[$key]['skor_tahunan'] = $skor_tahunan;
                if ($skor_tahunan >= 95) {
                    $dataResult[$key]['status'] = 'Excellent';
                } else if ($skor_tahunan >= 85 && $skor_tahunan < 95) {
                    $dataResult[$key]['status'] = 'Good';
                } else if ($skor_tahunan >= 75 && $skor_tahunan < 85) {
                    $dataResult[$key]['status'] = 'Satisfactory';
                } else if ($skor_tahunan >= 65 && $skor_tahunan < 75) {
                    $dataResult[$key]['status'] = 'Fair';
                } else if ($skor_tahunan < 65) {
                    $dataResult[$key]['status'] = 'Poor';
                }

                $estateQuery = DB::connection('mysql2')->table('estate')
                    ->select('estate.*')
                    ->join('wil', 'wil.id', '=', 'estate.wil')
                    ->where('estate.est', $key)
                    ->first();

                $dataResult[$key]['estate'] = $estateQuery->nama;
                $wilayah =  $estateQuery->wil;
                $dataResult[$key]['wilayah'] = $wilayah;
                if ($wilayah == 1)  $dataResult[$key]['wil'] = 'I';
                else if ($wilayah == 2)  $dataResult[$key]['wil'] = 'II';
                else if ($wilayah == 3)  $dataResult[$key]['wil'] = 'III';
                else if ($wilayah == 4)  $dataResult[$key]['wil'] = 'IV';
                else if ($wilayah == 5)  $dataResult[$key]['wil'] = 'V';
                else if ($wilayah == 6)  $dataResult[$key]['wil'] = 'VI';
                else if ($wilayah == 7)  $dataResult[$key]['wil'] = 'VII';
                else if ($wilayah == 8)  $dataResult[$key]['wil'] = 'VIII';
                $dataResult[$key]['est'] = $estateQuery->est;
            } else {
                foreach ($bulan as $key4 => $value) {
                    $dataResult[$key][$value] = 0;
                    $dataResult[$key]['skor_bulan_' . $value] = 0;
                }
                $estateQuery = DB::connection('mysql2')->table('estate')
                    ->select('estate.*')
                    ->join('wil', 'wil.id', '=', 'estate.wil')
                    ->where('estate.est', $key)
                    ->first();
                $dataResult[$key]['estate'] = $estateQuery->nama;
                $dataResult[$key]['est'] = $estateQuery->est;
                $wilayah =  $estateQuery->wil;
                $dataResult[$key]['wilayah'] = $wilayah;
                if ($wilayah == 1)  $dataResult[$key]['wil'] = 'I';
                else if ($wilayah == 2)  $dataResult[$key]['wil'] = 'II';
                else if ($wilayah == 3)  $dataResult[$key]['wil'] = 'III';
                else if ($wilayah == 4)  $dataResult[$key]['wil'] = 'IV';
                else if ($wilayah == 5)  $dataResult[$key]['wil'] = 'V';
                else if ($wilayah == 6)  $dataResult[$key]['wil'] = 'VI';
                else if ($wilayah == 7)  $dataResult[$key]['wil'] = 'VII';
                else if ($wilayah == 8)  $dataResult[$key]['wil'] = 'VIII';
                $dataResult[$key]['skor_tahunan'] = 0;
                $dataResult[$key]['status'] = 'Poor';
            }
        }

        // dd($dataResult);
        //khusus untuk menghitung record setiap bulan per estate
        // dd($countDataPerEstate);
        $resultCountMax = array();
        foreach ($bulan as $key => $value) {
            foreach ($countDataPerEstate as $key2 => $val) {

                if (array_key_exists($value, $val)) {
                    $resultCountMax[$value] = max(array_column($countDataPerEstate, $value));
                }
            }
        }

        // dd($bulan);
        // dd($resultCountMax);
        $resultCount = array();
        foreach ($resultCountMax as $key => $value) {
            if ($value == 0 || $value == 1) {
                $resultCount[$key] = 1;
            } else {
                $resultCount[$key] = $value;
            }
        }

        // dd($resultCount);

        $resultCountJson = json_encode($resultCount);
        // dd($resultCountJson);
        $total_column = 0;
        foreach ($resultCount as $key => $value) {
            $total_column = $total_column + $value;
        }

        // dd($total_column);

        $total_column_bulan = $total_column + 12;
        array_multisort(array_column($dataResult, 'skor_tahunan'), SORT_DESC, $dataResult);
        $inc = 1;
        foreach ($dataResult as $key => $value) {
            foreach ($value as $key2 => $data) {
                $dataResult[$key]['rank'] = $inc;
            }
            $inc++;
        }
        array_multisort(array_column($dataResult, 'wilayah'), SORT_ASC, $dataResult);

        foreach ($resultCount as $key => $value) {
            foreach ($dataResult as $key2 => $data) {
                if (array_key_exists($key, $data)) {
                    if ($value != 1) {
                        if (is_array($data[$key])) {
                            if (count($data[$key]) != $value) {
                                // dd(count($data[$key]));
                                for ($i = 0; $i < $value - count($data[$key]); $i++) {
                                    $dataResult[$key2][$key]['null' . $i] = '-';
                                }
                            }
                        } else {
                            unset($dataResult[$key2][$key]);
                            for ($i = 0; $i < $value; $i++) {
                                $dataResult[$key2][$key]['null' . $i] = '-';
                            }
                        }
                    }
                }
            }
        }

        // dd($dataResult);

        $arrView = array();
        $arrId = array();
        foreach ($dataResult as $key => $value) {
            $arrView[$key][] = $value['wil'];
            $arrId[$key][] = '-';
            $arrView[$key][] = $value['estate'];
            $arrId[$key][] = '-';
            $arrView[$key][] = $value['est'];
            $arrId[$key][] = '-';
            $arrView[$key][] = $value['wil'];
            $arrId[$key][] = '-';
            $arrView[$key][] = $value['wil'];
            $arrId[$key][] = '-';
            foreach ($bulan as $key2 => $data) {
                if (is_array($value[$data])) {
                    $inc = 0;
                    foreach ($value[$data] as $key3 => $val) {
                        $arrView[$key][] = $val;
                        $arrId[$key][] = $key3;
                        $inc++;
                    }
                } else {
                    $arrView[$key][] = '-';
                    $arrId[$key][] = 0;
                }
                $arrView[$key][] = $value['skor_bulan_' . $data];
                $arrId[$key][] = '-';
            }
            $arrView[$key][] = $value['skor_tahunan'];
            $arrId[$key][] = '-';
            $arrView[$key][] = $value['status'];
            $arrId[$key][] = '-';
            $arrView[$key][] = $value['rank'];
            $arrId[$key][] = '-';
        }

        // dd($arrId);
        // dd($arrView);
        $arrHeader = array();
        $arrHeader = ['WILAYAH', 'ESTATE', 'KODE', 'KTU', 'EM'];
        if (empty($resultCount)) {
            foreach ($bulan as $key => $value) {
                $resultCount[$value] = 1;
            }
        }

        foreach ($resultCount as $key => $value) {

            if ($value > 1) {
                for ($i = 1; $i <= $value; $i++) {
                    $arrHeader[] = $i;
                }
            } else {
                $arrHeader[] = '1';
            }
            $arrHeader[] = 'SKOR';
        }
        array_push($arrHeader, 'SKOR', 'STATUS', 'RANK');

        $countRes = array();
        $inc2 = 0;
        foreach ($resultCount as $key => $value) {
            $countRes[$inc2] = $value;
            $inc2++;
        }

        $arrResult['arrView'] = $arrView;
        $arrResult['arrId'] = $arrId;
        $arrResult['arrHeader'] = $arrHeader;
        $arrResult['arrMonth'] = $bulan;
        $arrResult['arrCount'] = $countRes;
        $arrResult['arrReg'] = $regional;

        echo json_encode($arrResult);
        exit();
    }
    public function dashboard_gudang()
    {
        $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $queryEstate = DB::connection('mysql2')->table('estate')
            ->select('estate.*')
            ->join('wil', 'wil.id', '=', 'estate.wil')
            ->where('wil.regional', 1)
            ->get();

        $queryEstate = json_decode($queryEstate, true);

        $dataRaw = array();

        foreach ($queryEstate as $value) {

            // dd($value);
            $queryPerEstate = DB::connection('mysql2')->table('qc_gudang')
                ->select("qc_gudang.*", DB::raw('DATE_FORMAT(qc_gudang.tanggal, "%M") as bulan'))
                ->where('unit', $value['id'])
                ->orWhere('unit', $value['est'])
                ->whereYear('tanggal', '2022')
                ->get();


            if ($queryPerEstate->first() != '') {

                $inc = 0;
                $total_skor = 0;
                foreach ($queryPerEstate as $key2 => $val) {

                    foreach ($bulan as $key => $val2) {
                        if ($val2 == $val->bulan) {
                            // if ($total_skor != 0) {
                            // $dataRaw[$value['est']][$val2]['skor_rerata'] = $total_skor / $inc;
                            // }
                            // $total_skor = $total_skor + $val->skor_total;
                            // $dataRaw[$value['est']][$val2]['total'] = $total_skor;
                            $dataRaw[$value['est']][$val2][$val->id] = $val->skor_total;
                        } else {
                            $dataRaw[$value['est']][$val2][] = 0;
                        }
                    }
                    $inc++;
                }
            } else {
                $dataRaw[$value['est']] = 'kosong';
            }
        }

        // dd($dataRaw);

        $dataResult = array();
        $countDataPerEstate = array();
        foreach ($dataRaw as $key => $value) {
            if ($value != 'kosong') {
                $total_skor = 0;
                $total_bulan = 0;
                $inc_bulan = 0;
                foreach ($value as $key2 => $data) {
                    $inc = 0;
                    $inc_count_data = 0;
                    // dd($data);
                    $inc_count_data_2 = 1;
                    foreach ($data as $key3 => $val) {

                        $total_skor = $total_skor + $val;
                        if ((int)$val != 0) {
                            // $dataResult[$key][$key2][$key3] = $val;
                            $dataResult[$key][$key2][$key3] = $val;

                            $inc++;
                            $inc_count_data_2++;
                            $inc_count_data++;
                        }
                        $countDataPerEstate[$key][$key2] = $inc_count_data;
                    }

                    $skor = 0;
                    if ($inc != 0) {
                        $skor = round($total_skor / $inc, 2);
                        // $dataResult[$key][$key2] = $skor;
                        $dataResult[$key]['skor_bulan_' . $key2] = $skor;
                    } else {
                        $dataResult[$key][$key2] = 0;
                        $dataResult[$key]['skor_bulan_' . $key2] = $skor;
                    }
                    $total_skor = 0;
                    $total_bulan = $total_bulan + $skor;
                    $inc_bulan++;
                }
                $skor_tahunan = round($total_bulan / $inc_bulan, 2);
                $dataResult[$key]['skor_tahunan'] = $skor_tahunan;
                if ($skor_tahunan >= 95) {
                    $dataResult[$key]['status'] = 'Excellent';
                } else if ($skor_tahunan >= 85 && $skor_tahunan < 95) {
                    $dataResult[$key]['status'] = 'Good';
                } else if ($skor_tahunan >= 75 && $skor_tahunan < 85) {
                    $dataResult[$key]['status'] = 'Satisfactory';
                } else if ($skor_tahunan >= 65 && $skor_tahunan < 75) {
                    $dataResult[$key]['status'] = 'Fair';
                } else if ($skor_tahunan < 65) {
                    $dataResult[$key]['status'] = 'Poor';
                }

                $estateQuery = DB::connection('mysql2')->table('estate')
                    ->select('estate.*')
                    ->join('wil', 'wil.id', '=', 'estate.wil')
                    ->where('estate.est', $key)
                    ->first();

                $dataResult[$key]['estate'] = $estateQuery->nama;
                $wilayah =  $estateQuery->wil;
                $dataResult[$key]['wilayah'] = $wilayah;
                if ($wilayah == 1)  $dataResult[$key]['wil'] = 'I';
                else if ($wilayah == 2)  $dataResult[$key]['wil'] = 'II';
                else if ($wilayah == 3)  $dataResult[$key]['wil'] = 'III';
                $dataResult[$key]['est'] = $estateQuery->est;
            } else {
                foreach ($bulan as $key4 => $value) {
                    $dataResult[$key][$value] = 0;
                    $dataResult[$key]['skor_bulan_' . $value] = 0;
                }
                $estateQuery = DB::connection('mysql2')->table('estate')
                    ->select('estate.*')
                    ->join('wil', 'wil.id', '=', 'estate.wil')
                    ->where('estate.est', $key)
                    ->first();
                $dataResult[$key]['estate'] = $estateQuery->nama;
                $dataResult[$key]['est'] = $estateQuery->est;
                $wilayah =  $estateQuery->wil;
                $dataResult[$key]['wilayah'] = $wilayah;
                if ($wilayah == 1)  $dataResult[$key]['wil'] = 'I';
                else if ($wilayah == 2)  $dataResult[$key]['wil'] = 'II';
                else if ($wilayah == 3)  $dataResult[$key]['wil'] = 'III';
                $dataResult[$key]['skor_tahunan'] = 0;
                $dataResult[$key]['status'] = 'Poor';
            }
        }
        //khusus untuk menghitung record setiap bulan per estate
        // dd($countDataPerEstate);
        foreach ($bulan as $key => $value) {
            foreach ($countDataPerEstate as $key2 => $val) {

                if (array_key_exists($value, $val)) {
                    $resultCountMax[$value] = max(array_column($countDataPerEstate, $value));
                }
            }
        }

        // dd($bulan);
        $resultCount = array();
        foreach ($resultCountMax as $key => $value) {
            if ($value == 0 || $value == 1) {
                $resultCount[$key] = 1;
            } else {
                $resultCount[$key] = $value;
            }
        }

        // dd($resultCount);

        $resultCountJson = json_encode($resultCount);
        // dd($resultCountJson);
        $total_column = 0;
        foreach ($resultCount as $key => $value) {
            $total_column = $total_column + $value;
        }

        // dd($total_column);

        $total_column_bulan = $total_column + 12;
        $arrCol = array_column($dataResult, 'skor_tahunan');


        array_multisort($arrCol, SORT_DESC, $dataResult);
        $inc = 1;
        foreach ($dataResult as $key => $value) {
            foreach ($value as $key2 => $data) {
                $dataResult[$key]['rank'] = $inc;
            }
            $inc++;
        }
        array_multisort(array_column($dataResult, 'wilayah'), SORT_ASC, $dataResult);

        foreach ($resultCount as $key => $value) {
            foreach ($dataResult as $key2 => $data) {
                if (array_key_exists($key, $data)) {
                    if ($value != 1) {
                        if (is_array($data[$key])) {
                            if (count($data[$key]) != $value) {
                                // dd(count($data[$key]));
                                for ($i = 0; $i < $value - count($data[$key]); $i++) {
                                    $dataResult[$key2][$key][$i] = '-';
                                }
                            }
                        } else {
                            unset($dataResult[$key2][$key]);
                            for ($i = 0; $i < $value; $i++) {
                                $dataResult[$key2][$key][] = '-';
                            }
                        }
                    }
                }
            }
        }



        // foreach ($dataResult as $key => $value) {
        //     foreach ($resultCount as $key2 => $data) {
        //         // dd($key2);
        //         for ($i = 1; $i <= $data; $i++) {
        //             if (array_key_exists($key2 . '_' . $i, $value)) {
        //                 // $dataResult[$key][$key2 . '_' . $i] = 0;

        //             } else {
        //                 if (!array_key_exists('skor_bulan_' . $key2, $value)) {
        //                     $dataResult[$key]['skor_bulan_' . $key2] = 0;
        //                 }
        //                 $dataResult[$key][$key2 . '_' . $i] = 0;
        //             }
        //         }
        //     }
        // }

        // dd($dataResult);
        // dd($dataResult['KNE']['November']);
        // if (array_key_exists(('November'), $dataResult['KNE'])) {
        //     if (is_array($dataResult['KNE']['November'])) {
        //         foreach ($dataResult['KNE']['November'] as $key => $value) {
        //             print_r($value);
        //         }
        //     } else {
        //         dd('tidak');
        //     }
        // } else {
        //     dd('tidak ada');
        // }

        // dd($dataResult);
        $bulanJson = json_encode($bulan);

        return view('dashboard_gudang', ['dataResult' => $dataResult, 'resultCount' => $resultCount, 'bulanJson' => $bulanJson, 'bulan' => $bulan, 'total_column_bulan' => $total_column_bulan, 'resultCountJson' => $resultCountJson]);
    }
    public function tambah()
    {
        $query = DB::table('qc_gudang')
            ->select("qc_gudang.*", DB::raw('DATE_FORMAT(qc_gudang.tanggal, "%M") as bulan'))
            ->get();
        // dd($query[0]->bulan);
        return view('tambah');
    }
    public function store(Request $request)
    {
        // dd($request->all());
        DB::table('pekerja')->insert([
            'id' => $request->id,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'unit' => $request->unit
        ]);
        return redirect('/index');
    }
    public function edit($id)
    {
        $data = DB::table('pekerja')->where('id', $id)->first();
        // dd($data->nama);
        return view('edit', ['pekerja' => $data]);
    }
    public function update(Request $request)
    {
        DB::table('pekerja')->where('id', $request->id)->update([
            'id' => $request->id,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'unit' => $request->unit
        ]);
        // dd($request->nama);
        return redirect('/index');
    }
    public function hapus($id)
    {
        DB::table('pekerja')->where('id', $id)->delete();
        return redirect('/index');
    }
    public function load_qc_gudang()
    {
        if (request()->ajax()) {
            $query = DB::table('qc_gudang')
                ->select("qc_gudang.*", 'estate.*', DB::raw('DATE_FORMAT(qc_gudang.tanggal, "%M") as bulan'))
                ->join('estate', 'estate.id', '=', 'qc_gudang.unit')
                ->get();
            $bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            $queryEstate = DB::table('estate')
                ->select('estate.*')
                ->join('wil', 'wil.id', '=', 'estate.wil')
                ->where('wil.regional', 1)
                ->get();

            $queryEstate = json_decode($queryEstate, true);

            $dataRaw = array();

            foreach ($queryEstate as $value) {

                $queryPerEstate = DB::table('qc_gudang')
                    ->select("qc_gudang.*", DB::raw('DATE_FORMAT(qc_gudang.tanggal, "%M") as bulan'))
                    ->where('unit', $value['id'])
                    ->get();

                if ($queryPerEstate->first() != '') {

                    $inc = 0;
                    $total_skor = 0;
                    foreach ($queryPerEstate as $key2 => $val) {

                        foreach ($bulan as $key => $val2) {
                            if ($val2 == $val->bulan) {
                                // if ($total_skor != 0) {
                                // $dataRaw[$value['est']][$val2]['skor_rerata'] = $total_skor / $inc;
                                // }
                                // $total_skor = $total_skor + $val->skor_total;
                                // $dataRaw[$value['est']][$val2]['total'] = $total_skor;
                                $dataRaw[$value['est']][$val2][$val->id] = $val->skor_total;
                            } else {
                                $dataRaw[$value['est']][$val2][] = 0;
                            }
                        }
                        $inc++;
                    }
                } else {
                    $dataRaw[$value['est']] = 'kosong';
                }
            }

            // dd($dataRaw);

            $dataResult = array();
            $countDataPerEstate = array();
            foreach ($dataRaw as $key => $value) {
                if ($value != 'kosong') {
                    $total_skor = 0;
                    $total_bulan = 0;
                    $inc_bulan = 0;
                    foreach ($value as $key2 => $data) {
                        $inc = 0;
                        $inc_count_data = 0;
                        // dd($data);
                        $inc_count_data_2 = 1;
                        foreach ($data as $key3 => $val) {

                            $total_skor = $total_skor + $val;
                            if ((int)$val != 0) {
                                // $dataResult[$key][$key2][$key3] = $val;
                                $dataResult[$key][$key2 . '_' . $inc_count_data_2] = $key3;

                                $inc++;
                                $inc_count_data_2++;
                                $inc_count_data++;
                            }
                            $countDataPerEstate[$key][$key2] = $inc_count_data;
                        }

                        $skor = 0;
                        if ($inc != 0) {
                            $skor = round($total_skor / $inc, 2);
                            // $dataResult[$key][$key2] = $skor;
                            $dataResult[$key]['skor_bulan_' . $key2] = $skor;
                        } else {
                            // $dataResult[$key][$key2] = 0;
                        }
                        $total_skor = 0;
                        $total_bulan = $total_bulan + $skor;
                        $inc_bulan++;
                    }
                    $skor_tahunan = round($total_bulan / $inc_bulan, 2);
                    $dataResult[$key]['skor_tahunan'] = $skor_tahunan;
                    if ($skor_tahunan >= 95) {
                        $dataResult[$key]['status'] = 'Excellent';
                    } else if ($skor_tahunan >= 85 && $skor_tahunan < 95) {
                        $dataResult[$key]['status'] = 'Good';
                    } else if ($skor_tahunan >= 75 && $skor_tahunan < 85) {
                        $dataResult[$key]['status'] = 'Satisfactory';
                    } else if ($skor_tahunan >= 65 && $skor_tahunan < 75) {
                        $dataResult[$key]['status'] = 'Fair';
                    } else if ($skor_tahunan < 65) {
                        $dataResult[$key]['status'] = 'Poor';
                    }

                    $estateQuery = DB::table('estate')
                        ->select('estate.*')
                        ->join('wil', 'wil.id', '=', 'estate.wil')
                        ->where('estate.est', $key)
                        ->first();

                    $dataResult[$key]['estate'] = $estateQuery->nama;
                    $wilayah =  $estateQuery->wil;
                    $dataResult[$key]['wilayah'] = $wilayah;
                    if ($wilayah == 1)  $dataResult[$key]['wil'] = 'I';
                    else if ($wilayah == 2)  $dataResult[$key]['wil'] = 'II';
                    else if ($wilayah == 3)  $dataResult[$key]['wil'] = 'III';
                    $dataResult[$key]['est'] = $estateQuery->est;
                } else {
                    foreach ($bulan as $key4 => $value) {
                        // $dataResult[$key][$value] = 0;
                    }
                    $estateQuery = DB::table('estate')
                        ->select('estate.*')
                        ->join('wil', 'wil.id', '=', 'estate.wil')
                        ->where('estate.est', $key)
                        ->first();
                    $dataResult[$key]['estate'] = $estateQuery->nama;
                    $dataResult[$key]['est'] = $estateQuery->est;
                    $wilayah =  $estateQuery->wil;
                    $dataResult[$key]['wilayah'] = $wilayah;
                    if ($wilayah == 1)  $dataResult[$key]['wil'] = 'I';
                    else if ($wilayah == 2)  $dataResult[$key]['wil'] = 'II';
                    else if ($wilayah == 3)  $dataResult[$key]['wil'] = 'III';
                    $dataResult[$key]['skor_tahunan'] = 0;
                    $dataResult[$key]['status'] = 'Poor';
                }
            }
            // dd($dataResult);
            //khusus untuk menghitung record setiap bulan per estate
            // dd($countDataPerEstate);
            foreach ($bulan as $key => $value) {
                foreach ($countDataPerEstate as $key2 => $val) {

                    if (array_key_exists($value, $val)) {
                        $resultCountMax[$value] = max(array_column($countDataPerEstate, $value));
                    }
                }
            }

            // dd($resultCountMax);
            // dd($bulan);
            $resultCount = array();
            foreach ($resultCountMax as $key => $value) {
                if ($value == 0 || $value == 1) {
                    $resultCount[$key] = 1;
                } else {
                    $resultCount[$key] = $value;
                }
            }

            // dd($resultCount);

            $resultCountJson = json_encode($resultCount);
            // dd($resultCountJson);
            $total_column = 0;
            foreach ($resultCount as $key => $value) {
                $total_column = $total_column + $value;
            }

            // dd($resultCount);

            $total_column_bulan = $total_column + 12;
            array_multisort(array_column($dataResult, 'skor_tahunan'), SORT_DESC, $dataResult);
            $inc = 1;
            foreach ($dataResult as $key => $value) {
                foreach ($value as $key2 => $data) {
                    $dataResult[$key]['rank'] = $inc;
                }
                $inc++;
            }
            array_multisort(array_column($dataResult, 'wilayah'), SORT_ASC, $dataResult);
        }

        foreach ($dataResult as $key => $value) {
            foreach ($resultCount as $key2 => $data) {
                // dd($key2);
                for ($i = 1; $i <= $data; $i++) {
                    if (array_key_exists($key2 . '_' . $i, $value)) {
                        // $dataResult[$key][$key2 . '_' . $i] = 0;

                    } else {
                        if (!array_key_exists('skor_bulan_' . $key2, $value)) {
                            $dataResult[$key]['skor_bulan_' . $key2] = 0;
                        }
                        $dataResult[$key][$key2 . '_' . $i] = 0;
                    }
                }
            }
        }
        // dd($dataResult);
        // dd($resultCountMax['November']);

    }
    public function detailInspeksi($id)
    {

        // $estateQuery = DB::table('estate')
        //     ->select('estate.*')
        //     ->join('wil', 'wil.id', '=', 'estate.wil')
        //     ->where('estate.est', $est)
        //     ->first();

        $query = DB::connection('mysql2')->table('qc_gudang')
            ->select('qc_gudang.*', DB::raw("DATE_FORMAT(qc_gudang.tanggal,'%d-%M-%y') as tanggal_formatted"), DB::raw("DATE_FORMAT(qc_gudang.tanggal,'%d%m%y') as name_format"))
            ->where('qc_gudang.id', '=', $id)
            ->first();

        $unit = $query->unit;

        $estate = DB::connection('mysql2')->table('estate')
            ->where('estate.id', '=', $unit)
            ->orWhere('estate.est', '=', $unit)
            ->first();

        $pekerja = DB::connection('mysql2')->table('pekerja')
            ->select('pekerja.*',  'estate.nama as nama_estate', 'estate.est')
            ->join('estate', 'estate.id', '=', 'pekerja.unit')
            ->where('unit', '=', $unit)
            ->orWhere('est', '=', $unit)
            ->first();

        // dd($pekerja);
        if ($pekerja != null) {
            $query->nama_ktu = $pekerja->nama;
        } else {
            $query->nama_ktu = '-';
        }

        $query->nama = $estate->nama;

        if ($query->foto_kesesuaian_ppro != null) {
            if (str_contains($query->foto_kesesuaian_ppro, ';')) {
                $exp_foto_kesesuaian_ppro = explode(';', $query->foto_kesesuaian_ppro);
                $query->foto_kesesuaian_ppro_1 = $exp_foto_kesesuaian_ppro[0];
                $query->foto_kesesuaian_ppro_2 = $exp_foto_kesesuaian_ppro[1];
            } else {
                $query->foto_kesesuaian_ppro_1 = $query->foto_kesesuaian_ppro;
                $query->foto_kesesuaian_ppro_2 = '';
            }
        } else {
            $query->foto_kesesuaian_ppro_1 = '';
            $query->foto_kesesuaian_ppro_2 = '';
        }

        if ($query->foto_kesesuaian_bincard != null) {
            if (str_contains($query->foto_kesesuaian_bincard, ';')) {
                $exp_foto_kesesuaian_bincard = explode(';', $query->foto_kesesuaian_bincard);
                $query->foto_kesesuaian_bincard_1 = $exp_foto_kesesuaian_bincard[0];
                $query->foto_kesesuaian_bincard_2 = $exp_foto_kesesuaian_bincard[1];
            } else {
                $query->foto_kesesuaian_bincard_1 = $query->foto_kesesuaian_bincard;
                $query->foto_kesesuaian_bincard_2 = '';
            }
        } else {
            $query->foto_kesesuaian_bincard_1 = 0;
            $query->foto_kesesuaian_bincard_2 = 0;
        }

        if ($query->foto_chemical_expired != null) {

            if (str_contains($query->foto_chemical_expired, ';')) {
                $exp_foto_chemical_expired = explode(';', $query->foto_chemical_expired);
                $query->foto_chemical_expired_1 = $exp_foto_chemical_expired[0];
                $query->foto_chemical_expired_2 = $exp_foto_chemical_expired[1];
            } else {
                $query->foto_chemical_expired_1 = $query->foto_chemical_expired;
                $query->foto_chemical_expired_2 = '';
            }
        } else {
            $query->foto_chemical_expired_1 = 0;
            $query->foto_chemical_expired_2 = 0;
        }

        if ($query->foto_barang_nonstok != null) {
            if (str_contains($query->foto_barang_nonstok, ';')) {
                $exp_foto_barang_nonstok = explode(';', $query->foto_barang_nonstok);
                $query->foto_barang_nonstok_1 = $exp_foto_barang_nonstok[0];
                $query->foto_barang_nonstok_2 = $exp_foto_barang_nonstok[1];
            } else {
                $query->foto_barang_nonstok_1 = $query->foto_barang_nonstok;
                $query->foto_barang_nonstok_2 = '';
            }
        } else {
            $query->foto_barang_nonstok_1 = 0;
            $query->foto_barang_nonstok_2 = 0;
        }

        if ($query->foto_kebersihan_gudang != null) {
            if (str_contains($query->foto_kebersihan_gudang, ';')) {
                $exp_foto_kebersihan_gudang = explode(';', $query->foto_kebersihan_gudang);
                $query->foto_kebersihan_gudang_1 = $exp_foto_kebersihan_gudang[0];
                $query->foto_kebersihan_gudang_2 = $exp_foto_kebersihan_gudang[1];
            } else {
                $query->foto_kebersihan_gudang_1 = $query->foto_kebersihan_gudang;
                $query->foto_kebersihan_gudang_2 = '';
            }
        } else {
            $query->foto_kebersihan_gudang_1 = 0;
            $query->foto_kebersihan_gudang_2 = 0;
        }

        if ($query->foto_mr_ditandatangani != null) {
            if (str_contains($query->foto_mr_ditandatangani, ';')) {
                $exp_foto_mr_ditandatangani = explode(';', $query->foto_mr_ditandatangani);
                $query->foto_mr_ditandatangani_1 = $exp_foto_mr_ditandatangani[0];
                $query->foto_mr_ditandatangani_2 = $exp_foto_mr_ditandatangani[1];
            } else {
                $query->foto_mr_ditandatangani_1 = $query->foto_mr_ditandatangani;
                $query->foto_mr_ditandatangani_2 = '';
            }
        } else {
            $query->foto_mr_ditandatangani_1 = 0;
            $query->foto_mr_ditandatangani_2 = 0;
        }

        if ($query->foto_inspeksi_ktu != null) {
            if (str_contains($query->foto_inspeksi_ktu, ';')) {
                $exp_foto_inspeksi_ktu = explode(';', $query->foto_inspeksi_ktu);
                $query->foto_inspeksi_ktu_1 = $exp_foto_inspeksi_ktu[0];
                $query->foto_inspeksi_ktu_2 = $exp_foto_inspeksi_ktu[1];
            } else {
                $query->foto_inspeksi_ktu_1 = $query->foto_inspeksi_ktu;
                $query->foto_inspeksi_ktu_2 = '';
            }
        } else {
            $query->foto_inspeksi_ktu_1 = 0;
            $query->foto_inspeksi_ktu_2 = 0;
        }

        return view('detail', ['data' => $query]);
    }
    public function cetakpdf($id)
    {


        // $query =  DB::table('qc_gudang')
        //     ->select('estate.*', 'pekerja.nama as nama_ktu', 'qc_gudang.*', DB::raw("DATE_FORMAT(qc_gudang.tanggal,'%d-%M-%y') as tanggal_formatted"), DB::raw("DATE_FORMAT(qc_gudang.tanggal,'%d%m%y') as name_format"))
        //     ->join('estate', 'estate.id', '=', 'qc_gudang.unit')
        //     ->join('pekerja', 'pekerja.unit', '=', 'qc_gudang.unit')
        //     ->where('qc_gudang.id', '=', $id)
        //     ->first();

        $query = DB::connection('mysql2')->table('qc_gudang')
            ->select('qc_gudang.*', DB::raw("DATE_FORMAT(qc_gudang.tanggal,'%d-%M-%y') as tanggal_formatted"), DB::raw("DATE_FORMAT(qc_gudang.tanggal,'%d%m%y') as name_format"))
            ->where('qc_gudang.id', '=', $id)
            ->first();

        $unit = $query->unit;

        $estate = DB::connection('mysql2')->table('estate')
            ->where('estate.id', '=', $unit)
            ->orWhere('estate.est', '=', $unit)
            ->first();


        $pekerja = DB::connection('mysql2')->table('pekerja')
            ->select('pekerja.*',  'estate.nama as nama_estate', 'estate.est')
            ->join('estate', 'estate.id', '=', 'pekerja.unit')
            ->where('unit', '=', $unit)
            ->orWhere('est', '=', $unit)
            ->first();

        // dd($pekerja);
        if ($pekerja != null) {
            $query->nama_ktu = $pekerja->nama;
        } else {
            $query->nama_ktu = '-';
        }

        $query->nama = $estate->nama;
        $query->est = $estate->est;

        if ($query->foto_kesesuaian_ppro != null) {
            if (str_contains($query->foto_kesesuaian_ppro, ';')) {
                $exp_foto_kesesuaian_ppro = explode(';', $query->foto_kesesuaian_ppro);
                $query->foto_kesesuaian_ppro_1 = $exp_foto_kesesuaian_ppro[0];
                $query->foto_kesesuaian_ppro_2 = $exp_foto_kesesuaian_ppro[1];
            } else {
                $query->foto_kesesuaian_ppro_1 = $query->foto_kesesuaian_ppro;
                $query->foto_kesesuaian_ppro_2 = '';
            }
        } else {
            $query->foto_kesesuaian_ppro_1 = '';
            $query->foto_kesesuaian_ppro_2 = '';
        }

        if ($query->foto_kesesuaian_bincard != null) {
            if (str_contains($query->foto_kesesuaian_bincard, ';')) {
                $exp_foto_kesesuaian_bincard = explode(';', $query->foto_kesesuaian_bincard);
                $query->foto_kesesuaian_bincard_1 = $exp_foto_kesesuaian_bincard[0];
                $query->foto_kesesuaian_bincard_2 = $exp_foto_kesesuaian_bincard[1];
            } else {
                $query->foto_kesesuaian_bincard_1 = $query->foto_kesesuaian_bincard;
                $query->foto_kesesuaian_bincard_2 = '';
            }
        } else {
            $query->foto_kesesuaian_bincard_1 = 0;
            $query->foto_kesesuaian_bincard_2 = 0;
        }

        if ($query->foto_chemical_expired != null) {

            if (str_contains($query->foto_chemical_expired, ';')) {
                $exp_foto_chemical_expired = explode(';', $query->foto_chemical_expired);
                $query->foto_chemical_expired_1 = $exp_foto_chemical_expired[0];
                $query->foto_chemical_expired_2 = $exp_foto_chemical_expired[1];
            } else {
                $query->foto_chemical_expired_1 = $query->foto_chemical_expired;
                $query->foto_chemical_expired_2 = '';
            }
        } else {
            $query->foto_chemical_expired_1 = 0;
            $query->foto_chemical_expired_2 = 0;
        }

        if ($query->foto_barang_nonstok != null) {
            if (str_contains($query->foto_barang_nonstok, ';')) {
                $exp_foto_barang_nonstok = explode(';', $query->foto_barang_nonstok);
                $query->foto_barang_nonstok_1 = $exp_foto_barang_nonstok[0];
                $query->foto_barang_nonstok_2 = $exp_foto_barang_nonstok[1];
            } else {
                $query->foto_barang_nonstok_1 = $query->foto_barang_nonstok;
                $query->foto_barang_nonstok_2 = '';
            }
        } else {
            $query->foto_barang_nonstok_1 = 0;
            $query->foto_barang_nonstok_2 = 0;
        }

        if ($query->foto_kebersihan_gudang != null) {
            if (str_contains($query->foto_kebersihan_gudang, ';')) {
                $exp_foto_kebersihan_gudang = explode(';', $query->foto_kebersihan_gudang);
                $query->foto_kebersihan_gudang_1 = $exp_foto_kebersihan_gudang[0];
                $query->foto_kebersihan_gudang_2 = $exp_foto_kebersihan_gudang[1];
            } else {
                $query->foto_kebersihan_gudang_1 = $query->foto_kebersihan_gudang;
                $query->foto_kebersihan_gudang_2 = '';
            }
        } else {
            $query->foto_kebersihan_gudang_1 = 0;
            $query->foto_kebersihan_gudang_2 = 0;
        }

        if ($query->foto_mr_ditandatangani != null) {
            if (str_contains($query->foto_mr_ditandatangani, ';')) {
                $exp_foto_mr_ditandatangani = explode(';', $query->foto_mr_ditandatangani);
                $query->foto_mr_ditandatangani_1 = $exp_foto_mr_ditandatangani[0];
                $query->foto_mr_ditandatangani_2 = $exp_foto_mr_ditandatangani[1];
            } else {
                $query->foto_mr_ditandatangani_1 = $query->foto_mr_ditandatangani;
                $query->foto_mr_ditandatangani_2 = '';
            }
        } else {
            $query->foto_mr_ditandatangani_1 = 0;
            $query->foto_mr_ditandatangani_2 = 0;
        }

        if ($query->foto_inspeksi_ktu != null) {
            if (str_contains($query->foto_inspeksi_ktu, ';')) {
                $exp_foto_inspeksi_ktu = explode(';', $query->foto_inspeksi_ktu);
                $query->foto_inspeksi_ktu_1 = $exp_foto_inspeksi_ktu[0];
                $query->foto_inspeksi_ktu_2 = $exp_foto_inspeksi_ktu[1];
            } else {
                $query->foto_inspeksi_ktu_1 = $query->foto_inspeksi_ktu;
                $query->foto_inspeksi_ktu_2 = '';
            }
        } else {
            $query->foto_inspeksi_ktu_1 = 0;
            $query->foto_inspeksi_ktu_2 = 0;
        }

        $pdf = pdf::loadview('cetak', ['data' => $query]);
        $customPaper = array(360, 360, 360, 360);
        $pdf->set_paper('A2', 'potrait');

        $filename = 'QC-gudang-' . $query->name_format . '-' . $query->est . '.pdf';
        return $pdf->stream($filename);
    }

    public function hapusRecord($id)
    {
        DB::connection('mysql2')->table('qc_gudang')->delete($id);
        return redirect()->route('dashboard_gudang');
    }
}
