@include('layout/header')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <a href="/cetakpdf/{{ $data->id }}" class="fa fa-file-pdf">cetak pdf</a>
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
                                <td>{{ $data->nama }}</td>
                            </tr>
                            <tr>
                                <th>TANGGAL</th>
                                <td>{{ $data->tanggal_formatted }}</td>
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
                                @if ($data->skor_total >= 95)
                                <th class="table-primary text-center">EXCELLENT</th>
                                @elseif($data->skor_total >= 85 && $data->skor_total <95) <th
                                    class="table-success text-center">Good</th>
                                    @elseif($data->skor_total >= 75 && $data->skor_total <85) <th
                                        class="table text-center" style="background-color: yellow">Satisfactory</th>
                                        @elseif($data->skor_total >= 65 && $data->skor_total <75) <th
                                            class="table-warning text-center">Fair</th>
                                            @elseif($data->skor_total <75) <th class="table text-center"
                                                style="background-color: red">Poor
                                                </th>
                                                @endif

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
                            <td colspan="2" class="text-center">{{ $data->komentar_kesesuaian_bincard }}</td>
                            <td colspan="2" class="text-center">{{ $data->komentar_kesesuaian_ppro }}</td>
                            <td colspan="2" class="text-center">{{ $data->komentar_chemical_expired }}</td>
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
                        <td colspan="2" class="text-center">{{ $data->komentar_barang_nonstok }}</td>
                        <td colspan="2" class="text-center">{{ $data->komentar_mr_ditandatangani }}</td>
                        <td colspan="2" class="text-center">{{ $data->komentar_kebersihan_gudang }}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th>7. BUKU INSPEKSI KTU</th>
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
                            <td colspan="2" class="text-center">{{ $data->komentar_inspeksi_ktu }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </section>
</div>
@include('layout/footer')