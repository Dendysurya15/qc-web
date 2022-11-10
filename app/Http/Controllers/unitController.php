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
        $table = DB::table('pekerja')->get();

        //  dd($table);
        return view('index', ['pekerja' => $table]);
    }
    public function dashboard()
    {
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
                            $dataResult[$key][$key2 . '_' . $inc_count_data_2] = $val;

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

        $bulanJson = json_encode($bulan);
        return view('dashboard', ['resultCount' => $resultCount, 'bulanJson' => $bulanJson, 'bulan' => $bulan, 'total_column_bulan' => $total_column_bulan, 'resultCountJson' => $resultCountJson]);
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
            return DataTables::of($dataResult)
                ->editColumn('status', function ($model) {
                    if ($model['status'] == 'Excellent') {
                        $style =  '<div style="background-color:blue"> ' . $model['status'] . '  </div>';
                    } else if ($model['status'] == 'Good') {
                        $style =  '<div style="background-color:green"> ' . $model['status'] . '   </div>';
                    } else if ($model['status'] == 'Satisfactory') {
                        $style =  '<div style="background-color:yellow">' . $model['status'] . '    </div>';
                    } else if ($model['status'] == 'Fair') {
                        $style =  '<div style="background-color:purple"> ' . $model['status'] . '   </div>';
                    } else if ($model['status'] == 'Poor') {
                        $style =  '<div style="background-color:red">  ' . $model['status'] . '  </div>';
                    }
                    return $style;
                })
                ->editColumn('January_1', function ($model) {
                    $link = 0;
                    if (array_key_exists('January_1', $model)) {
                        if ($model['January_1'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['January_1'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['January_1']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('January_2', function ($model) {
                    $link = 0;
                    if (array_key_exists('January_2', $model)) {
                        if ($model['January_2'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['January_2'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['January_2']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('January_3', function ($model) {
                    $link = 0;
                    if (array_key_exists('January_3', $model)) {
                        if ($model['January_3'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['January_3'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['January_3']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('February_1', function ($model) {
                    $link = 0;
                    if (array_key_exists('February_1', $model)) {
                        if ($model['February_1'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['February_1'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['February_1']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('February_2', function ($model) {
                    $link = 0;
                    if (array_key_exists('February_2', $model)) {
                        if ($model['February_2'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['February_2'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['February_2']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('February_3', function ($model) {
                    $link = 0;
                    if (array_key_exists('February_3', $model)) {
                        if ($model['February_3'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['February_3'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['February_3']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('March_1', function ($model) {
                    $link = 0;
                    if (array_key_exists('March_1', $model)) {
                        if ($model['March_1'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['March_1'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['March_1']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('March_2', function ($model) {
                    $link = 0;
                    if (array_key_exists('March_2', $model)) {
                        if ($model['March_2'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['March_2'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['March_2']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('March_3', function ($model) {
                    $link = 0;
                    if (array_key_exists('March_3', $model)) {
                        if ($model['March_3'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['March_3'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['March_3']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('April_1', function ($model) {
                    $link = 0;
                    if (array_key_exists('April_1', $model)) {
                        if ($model['April_1'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['April_1'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['April_1']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('April_2', function ($model) {
                    $link = 0;
                    if (array_key_exists('April_2', $model)) {
                        if ($model['April_2'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['April_2'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['April_2']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('April_3', function ($model) {
                    $link = 0;
                    if (array_key_exists('April_3', $model)) {
                        if ($model['April_3'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['April_3'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['April_3']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('May_1', function ($model) {
                    $link = 0;
                    if (array_key_exists('May_1', $model)) {
                        if ($model['May_1'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['May_1'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['May_1']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('May_2', function ($model) {
                    $link = 0;
                    if (array_key_exists('May_2', $model)) {
                        if ($model['May_2'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['May_2'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['May_2']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('May_2', function ($model) {
                    $link = 0;
                    if (array_key_exists('May_2', $model)) {
                        if ($model['May_2'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['May_2'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['May_2']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('May_3', function ($model) {
                    $link = 0;
                    if (array_key_exists('May_3', $model)) {
                        if ($model['May_3'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['May_3'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['May_3']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('June_1', function ($model) {
                    $link = 0;
                    if (array_key_exists('June_1', $model)) {
                        if ($model['June_1'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['June_1'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['June_1']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('June_2', function ($model) {
                    $link = 0;
                    if (array_key_exists('June_2', $model)) {
                        if ($model['June_2'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['June_2'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['June_2']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('June_3', function ($model) {
                    $link = 0;
                    if (array_key_exists('June_3', $model)) {
                        if ($model['June_3'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['June_3'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['June_3']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('July_1', function ($model) {
                    $link = 0;
                    if (array_key_exists('July_1', $model)) {
                        if ($model['July_1'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['July_1'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['July_1']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('July_2', function ($model) {
                    $link = 0;
                    if (array_key_exists('July_2', $model)) {
                        if ($model['July_2'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['July_2'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['July_2']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('July_3', function ($model) {
                    $link = 0;
                    if (array_key_exists('July_3', $model)) {
                        if ($model['July_3'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['July_3'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['July_3']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('August_1', function ($model) {
                    $link = 0;
                    if (array_key_exists('August_1', $model)) {
                        if ($model['August_1'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['August_1'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['August_1']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('August_2', function ($model) {
                    $link = 0;
                    if (array_key_exists('August_2', $model)) {
                        if ($model['August_2'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['August_2'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['August_2']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('August_3', function ($model) {
                    $link = 0;
                    if (array_key_exists('August_3', $model)) {
                        if ($model['August_3'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['August_3'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['August_3']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('September_1', function ($model) {
                    $link = 0;
                    if (array_key_exists('September_1', $model)) {
                        if ($model['September_1'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['September_1'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['September_1']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('September_2', function ($model) {
                    $link = 0;
                    if (array_key_exists('September_2', $model)) {
                        if ($model['September_2'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['September_2'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['September_2']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('September_3', function ($model) {
                    $link = 0;
                    if (array_key_exists('September_3', $model)) {
                        if ($model['September_3'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['September_3'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['September_3']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('October_1', function ($model) {
                    $link = 0;
                    if (array_key_exists('October_1', $model)) {
                        if ($model['October_1'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['October_1'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['October_1']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('October_2', function ($model) {
                    $link = 0;
                    if (array_key_exists('October_2', $model)) {
                        if ($model['October_2'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['October_2'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['October_2']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('October_3', function ($model) {
                    $link = 0;
                    if (array_key_exists('October_3', $model)) {
                        if ($model['October_3'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['October_3'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['October_3']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('November_1', function ($model) {
                    $link = 0;
                    if (array_key_exists('November_1', $model)) {
                        if ($model['November_1'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['November_1'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['November_1']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('November_2', function ($model) {
                    $link = 0;
                    if (array_key_exists('November_2', $model)) {
                        if ($model['November_2'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['November_2'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['November_2']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('November_3', function ($model) {
                    $link = 0;
                    if (array_key_exists('November_3', $model)) {
                        if ($model['November_3'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['November_3'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['November_3']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('December_1', function ($model) {
                    $link = 0;
                    if (array_key_exists('December_1', $model)) {
                        if ($model['December_1'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['December_1'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['December_1']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('December_2', function ($model) {
                    $link = 0;
                    if (array_key_exists('December_2', $model)) {
                        if ($model['December_2'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['December_2'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['December_2']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->editColumn('December_3', function ($model) {
                    $link = 0;
                    if (array_key_exists('December_3', $model)) {
                        if ($model['December_3'] != 0) {
                            $skor_total = DB::table('qc_gudang')->where('id', '=', $model['December_3'])->first()->skor_total;
                            $link = '<a href="' . route('detailInspeksi', ['id' => $model['December_3']]) . '">' . $skor_total . '</a>';
                        }
                    }
                    return $link;
                })
                ->rawColumns([
                    'status', 'January_1', 'January_2', 'fsdfsd',
                    'February_1', 'February_2', 'February_3',
                    'March_1', 'March_2', 'March_3',
                    'April_1', 'April_2', 'April_3',
                    'May_1', 'May_2', 'May_3',
                    'June_1', 'June_2', 'June_3',
                    'July_1', 'July_2', 'July_3',
                    'August_1', 'August_2', 'August_3',
                    'September_1', 'September_2', 'September_3',
                    'October_1', 'October_2', 'October_3',
                    'November_1', 'November_2', 'November_3',
                    'December_1', 'December_2', 'December_3',
                ])
                // ->rawColumns(['January'])
                ->make();
        }
    }
    public function detailInspeksi($id)
    {

        // $estateQuery = DB::table('estate')
        //     ->select('estate.*')
        //     ->join('wil', 'wil.id', '=', 'estate.wil')
        //     ->where('estate.est', $est)
        //     ->first();

        $query = DB::table('qc_gudang')
            ->select('estate.*', 'qc_gudang.*', DB::raw("DATE_FORMAT(qc_gudang.tanggal,'%d-%M-%y') as tanggal_formatted"))
            ->join('estate', 'estate.id', '=', 'qc_gudang.unit')
            ->where('qc_gudang.id', '=', $id)
            ->first();
        // dd($query);
        return view('detail', ['data' => $query]);
    }
    public function cetakpdf($id)
    {


        $query =  DB::table('qc_gudang')
            ->select('estate.*', 'qc_gudang.*', DB::raw("DATE_FORMAT(qc_gudang.tanggal,'%d-%M-%y') as tanggal_formatted"))
            ->join('estate', 'estate.id', '=', 'qc_gudang.unit')
            ->where('qc_gudang.id', '=', $id)
            ->first();
        // dd($query);
        // $context = stream_context_create([
        //     'ssl' => [
        //         'verify_peer' => FALSE,
        //         'verify_peer_name' => FALSE,
        //         'allow_self_signed' => TRUE,
        //     ]
        // ]);
        // dd($query);

        // $pdf = app('dompdf.wrapper');
        // $pdf = pdf::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        // $pdf->getDomPDF()->setHttpContext($context);
        $pdf = pdf::loadview('cetak', ['data' => $query]);
        $customPaper = array(360, 360, 360, 360);
        $pdf->set_paper('A2', 'potrait');
        return $pdf->stream('user.pdf');
    }
}
