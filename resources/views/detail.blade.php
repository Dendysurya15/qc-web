@include('layout/header')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <a href="/cetakpdf/" class="fa fa-file-pdf">cetak pdf</a>
            <table class="table table-warning col-xs-1 text-center">
                <thead>
                    <tr>
                        <th>III.PEMERIKSAAN GUDANG</th>
                    </tr>
                </thead>
            </table>
            <div class="row g-3">
                <div class="col-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>ESTATE</th>
                                <td>Sulung</td>
                            </tr>
                            <tr>
                                <th>TANGGAL</th>
                                <td>{{ $data->tanggal }}</td>
                            </tr>
                            <tr>
                                <th>KTU</th>
                                <td>{{ $data->ktu }}</td>
                            </tr>
                            <tr>
                                <th>KEPALA GUDANG</th>
                                <td>{{ $data->kpl_gudang }}</td>
                            </tr>
                            <tr>
                                <th>DIPERIKSA OLEH</th>
                                <td>{{ $data->qc }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-3 offset-6">
                    <table class="table table-bordered-warning">
                        <tbody>
                            <tr>
                                <th class="table-warning text-center">SKOR</th>
                            </tr>
                            <tr>
                                <th class="text-center">{{ $data->skor_total }}</th>
                            </tr>
                            <tr>
                                <th class="table-primary text-center">EXCELLENT</th>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th></th>
                            <th>1.KESESUAIAN FISIK VS BINCARD</th>
                            <th></th>
                            <th>2.KESESUAIAN FISIK VS PPRO</th>
                            <th></th>
                            <th>3.BARANG CHEMICAL EXPIRED</th>
                        </tr>
                        <tr>
                            <td>HASIL</td>
                            <td>FOTO</td>
                            <td>HASIL</td>
                            <td>FOTO</td>
                            <td>HASIL</td>
                            <td>FOTO</td>
                        </tr>
                        <tr>
                            <td rowspan="2">sesuai</td>
                            <td><img src="{{ asset('img/CBI-logo.png') }}" class="img-fluid"></td>
                            <td rowspan="2">sesuai</td>
                            <td><img src="{{ asset('img/CBI-logo.png') }}" class="img-fluid"></td>
                            <td rowspan="2">sesuai</td>
                            <td><img src="{{ asset('img/CBI-logo.png') }}" class="img-fluid"></td>
                        </tr>
                        <tr>
                            <td><img src="{{ asset('img/CBI-logo.png') }}" class="img-fluid"></td>
                            <td><img src="{{ asset('img/CBI-logo.png') }}" class="img-fluid"></td>
                            <td><img src="{{ asset('img/CBI-logo.png') }}" class="img-fluid"></td>
                        </tr>
                        <tr>
                            <td colspan="2">Tidak terdapat selisih fisik vs bincard</td>
                            <td colspan="2">Tidak terdapat selisih fisik vs PPRO</td>
                            <td colspan="2">Tidak ditemukan chemical expired</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th>4.BARANG NON-STOCK</th>
                            <th></th>
                            <th>5.SELURUH MR DITANDATANGANI EM</th>
                            <th></th>
                            <th>6.KEBERSIHAN DAN KERAPIHAN GUDANG </th>
                        </tr>
                        <tr>
                            <td>HASIL</td>
                            <td>FOTO</td>
                            <td>HASIL</td>
                            <td>FOTO</td>
                            <td>HASIL</td>
                            <td>FOTO</td>

                        </tr>
                        <tr>
                            <td rowspan="2">sesuai</td>
                            <td><img src="{{ asset('img/CBI-logo.png') }}" class="img-fluid"></td>
                            <td rowspan="2">sesuai</td>
                            <td><img src="{{ asset('img/CBI-logo.png') }}" class="img-fluid"></td>
                            <td rowspan="2">sesuai</td>
                            <td><img src="{{ asset('img/CBI-logo.png') }}" class="img-fluid"></td>
                        </tr>
                        <tr>
                            <td><img src="{{ asset('img/CBI-logo.png') }}" class="img-fluid"></td>
                            <td><img src="{{ asset('img/CBI-logo.png') }}" class="img-fluid"></td>
                            <td><img src="{{ asset('img/CBI-logo.png') }}" class="img-fluid"></td>
                        </tr>
                        <td colspan="2">Tidak terdapat barang non stock</td>
                        <td colspan="2">Seluruh MR sudah ditandatangan lengakap oleh EM</td>
                        <td colspan="2">Secara umum gudang dan kantor gudang sudah rapi</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th>7.BARANG NON-STOCK</th>
                        <tr>
                            <td>HASIL</td>
                            <td>FOTO</td>
                        </tr>
                        <tr>
                            <td rowspan="2">SELESAI</td>
                            <td><img src="{{ asset('img/CBI-logo.png') }}" class="img-fluid"></td>
                        </tr>
                        <tr>
                            <td><img src="{{ asset('img/CBI-logo.png') }}" class="img-fluid"></td>
                        </tr>
                        <tr>
                            <td colspan="2">Logbook Tersedia Dan Todate</td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </section>
</div>
@include('layout/footer')