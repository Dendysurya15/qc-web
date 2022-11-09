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
                    foreach ($data as $key3 => $val) {

                        $total_skor = $total_skor + $val;
                        if ((int)$val != 0) {
                            // $dataResult[$key][$key2][$key3] = $val;
                            $dataResult[$key][$key2 . '_' . $inc_count_data] = $key3;

                            $inc_count_data++;
                            $inc++;
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
                    $dataResult[$key][$value] = 0;
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
        $total_column = 0;
        foreach ($resultCount as $key => $value) {
            $total_column = $total_column + $value;
        }



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
                        $inc_count_data = 1;
                        // dd($data);
                        foreach ($data as $key3 => $val) {

                            $total_skor = $total_skor + $val;
                            if ((int)$val != 0) {
                                // $dataResult[$key][$key2][$key3] = $val;
                                $dataResult[$key][$key2 . '_' . $inc_count_data] = $key3;
                                $countDataPerEstate[$key][$key2] = $inc_count_data;
                                $inc_count_data++;
                                $inc++;
                            }
                        }

                        $skor = 0;
                        if ($inc != 0) {
                            $skor = round($total_skor / $inc, 2);
                            // $dataResult[$key][$key2] = $skor;
                            $dataResult[$key]['skor_bulan_' . $key2] = $skor;
                        } else {
                            $dataResult[$key][$key2] = 0;
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
                        $dataResult[$key][$value] = 0;
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

            foreach ($bulan as $key => $value) {
                foreach ($countDataPerEstate as $key => $val) {
                    if (array_key_exists($value, $val)) {
                        $resultCountMax[$value] = max(array_column($countDataPerEstate, $value));
                    }
                }
            }

            // dd($resultCountMax);

            array_multisort(array_column($dataResult, 'skor_tahunan'), SORT_DESC, $dataResult);
            $inc = 1;
            foreach ($dataResult as $key => $value) {
                foreach ($value as $key2 => $data) {
                    $dataResult[$key]['rank'] = $inc;
                }
                $inc++;
            }
            array_multisort(array_column($dataResult, 'wilayah'), SORT_ASC, $dataResult);
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
                // ->editColumn('January', function ($model) {
                //     // $newFormatDate = Carbon::parse($model['tanggal']);
                //     return '<a href="' . route('detailInspeksi', ['wil' => $model['wil'], 'est' => $model['est'], 'bulan' => '01']) . '">' . $model['January'] . '</a>';
                // })
                ->editColumn('February', function ($model) {
                    return '<a href="' . route('detailInspeksi', ['wil' => $model['wil'], 'est' => $model['est'], 'bulan' => '02']) . '">' . $model['February'] . '</a>';
                })
                ->editColumn('March', function ($model) {
                    return '<a href="' . route('detailInspeksi', ['wil' => $model['wil'], 'est' => $model['est'], 'bulan' => '03']) . '">' . $model['March'] . '</a>';
                })
                ->editColumn('April', function ($model) {
                    return '<a href="' . route('detailInspeksi', ['wil' => $model['wil'], 'est' => $model['est'], 'bulan' => '04']) . '">' . $model['April'] . '</a>';
                })
                ->editColumn('May', function ($model) {
                    return '<a href="' . route('detailInspeksi', ['wil' => $model['wil'], 'est' => $model['est'], 'bulan' => '05']) . '">' . $model['May'] . '</a>';
                })
                ->editColumn('June', function ($model) {
                    return '<a href="' . route('detailInspeksi', ['wil' => $model['wil'], 'est' => $model['est'], 'bulan' => '06']) . '">' . $model['June'] . '</a>';
                })
                ->editColumn('July', function ($model) {
                    return '<a href="' . route('detailInspeksi', ['wil' => $model['wil'], 'est' => $model['est'], 'bulan' => '07']) . '">' . $model['July'] . '</a>';
                })
                // ->editColumn('August', function ($model) {
                //     return '<a href="' . route('detailInspeksi', ['wil' => $model['wil'], 'est' => $model['est'], 'bulan' => '08']) . '">' . $model['August'] . '</a>';
                // })
                // ->editColumn('September', function ($model) {
                //     return '<a href="' . route('detailInspeksi', ['wil' => $model['wil'], 'est' => $model['est'], 'bulan' => '09']) . '">' . $model['September'] . '</a>';
                // })
                // ->editColumn('October', function ($model) {
                //     return '<a href="' . route('detailInspeksi', ['wil' => $model['wil'], 'est' => $model['est'], 'bulan' => '10']) . '">' . $model['October'] . '</a>';
                // })
                //    for ($i=0; $i < count($resultCountMax['November']); $i++) { 
                //     # code...
                //    }
                // ->editColumn('November', function ($model) {
                //     return '<a href="' . route('detailInspeksi', ['wil' => $model['wil'], 'est' => $model['est'], 'bulan' => '11']) . '">' . $model['November'] . '</a>';
                // })
                // ->editColumn('December', function ($model) {
                //     return '<a href="' . route('detailInspeksi', ['wil' => $model['wil'], 'est' => $model['est'], 'bulan' => '12']) . '">' . $model['December'] . '</a>';
                // })
                ->rawColumns(['status', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'])
                // ->rawColumns(['January'])
                ->make();
        }
    }
    public function detailInspeksi($wil, $est, $bulan)
    {

        $id = 190;
        // $estateQuery = DB::table('estate')
        //     ->select('estate.*')
        //     ->join('wil', 'wil.id', '=', 'estate.wil')
        //     ->where('estate.est', $est)
        //     ->first();

        // dd($estateQuery);
        $query = DB::table('qc_gudang')
            ->where('id', '=', $id)
            ->first();
        dd($query);
        return view('detail', ['data' => $query]);
    }
    public function cetakpdf($id)
    {

        // dd($id);
        $query = DB::table('qc_gudang')->where('id', $id)->first();
        // dd($query);
        // $context = stream_context_create([
        //     'ssl' => [
        //         'verify_peer' => FALSE,
        //         'verify_peer_name' => FALSE,
        //         'allow_self_signed' => TRUE,
        //     ]
        // ]);

        // $pdf = app('dompdf.wrapper');
        // $pdf = pdf::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        // $pdf->getDomPDF()->setHttpContext($context);
        $pdf = pdf::loadview('cetak', ['data' => $query]);
        $customPaper = array(360, 360, 360, 360);
        $pdf->set_paper('A2', 'potrait');
        return $pdf->stream('user.pdf');
    }
}
