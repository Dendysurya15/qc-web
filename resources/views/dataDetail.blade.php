<!DOCTYPE html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

<!-- JavaScript dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>


@include('layout/header')


<style>
    .Wraping {
        width: 100%;
        overflow-x: auto;
        white-space: nowrap;
        padding: 0;
        /* Remove padding */
    }

    table {
        border-collapse: collapse;
        width: 100%;
        /* Remove the margin property to prevent centering */
    }



    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 8px;
    }

    .sticky-footer {
        margin-top: auto;
        /* Push the footer to the bottom */
    }

    .my-table {
        margin-bottom: 50px;
        /* Adjust this value as needed */
    }

    .header {
        align-items: center;
    }

    .logo-container {
        display: flex;
        align-items: center;
    }

    .logo {
        height: 80px;
        width: auto;
    }

    .text-container {
        margin-left: 15px;
    }

    .pt-name,
    .qc-name {
        margin: 0;
    }

    .center-space {
        flex-grow: 1;
    }

    .right-container {
        text-align: right;
    }

    .rights-container {
        display: flex;

        justify-content: flex-end;
    }


    .form-inline {
        display: flex;
        align-items: center;
    }

    /* The Modal (background) */
    /* Add Bootstrap-like styling for the modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.5s;
    }

    .modal-content {
        background-color: #ffffff;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #dee2e6;
        width: 40%;
        max-width: 500px;
        max-height: 70%;
        /* Set a maximum height */
        overflow-y: auto;
        /* Enable vertical scrolling if content overflows */
        border-radius: 0.3rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        animation: scaleUp 0.3s;
    }


    /* Add Bootstrap-like button styling */
    .btn {
        display: inline-block;
        font-weight: 400;
        color: #212529;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        user-select: none;
    }

    .btn-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-primary:hover,
    .btn-secondary:hover {
        filter: brightness(90%);
    }

    .btn-primary:active,
    .btn-secondary:active {
        filter: brightness(80%);
    }

    .btn:focus,
    .btn:active {
        outline: none;
    }

    /* Add Bootstrap-like form control styling */
    .form-control {
        display: block;
        width: 100%;
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        color: #495057;
        background-color: #fff;
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .mb-3 {
        margin-bottom: 1rem !important;
    }


    /* The image inside the modal */
    #modalImage {
        width: 100%;
        /* Adjust this value to change the image width */
        max-height: 70vh;
        /* Limit the height of the image */
        object-fit: contain;
        /* Maintain aspect ratio */
    }

    /* Add Animation */
    @keyframes animatetop {
        from {
            top: -300px;
            opacity: 0;
        }

        to {
            top: 0;
            opacity: 1;
        }
    }

    /* The Close Button */
    .close {
        color: white;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }



    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes scaleUp {
        from {
            transform: scale(0.95);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* The image inside the modal */
</style>


<div class="content-wrapper">
    <div class="card table_wrapper">
        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
            <h2>REKAP HARIAN SIDAK INPEKSI </h2>
        </div>

        <div class="header d-flex justify-content-center mt-3 mb-2 ml-3 mr-3">
            <div class="logo-container">

                <img src="{{ asset('img/logo-SSS.png') }}" alt="Logo" class="logo">
                <div class="text-container">
                    <div class="pt-name">PT. SAWIT SUMBERMAS SARANA, TBK</div>
                    <div class="qc-name">QUALITY CONTROL</div>
                </div>
            </div>
            <div class="center-space"></div>
            <div class="right-container">
                <form action="{{ route('filterDataDetail') }}" method="POST" class="form-inline">
                    <div class="date">
                        {{ csrf_field() }}

                        <input type="hidden" name="est" id="est" value="{{$est}}">
                        <input type="hidden" name="afd" id="afd" value="{{$afd}}">
                        <!-- <input class="form-control" value="{{ date('Y-m') }}" type="date" name="date" id="inputDate"> -->
                        <select class="form-control" name="date" id="inputDate" onchange="updateDate();">
                            <option value="" disabled selected hidden>Pilih tanggal</option>
                            <optgroup label="Mutu Ancak">
                                @foreach($ancakDates as $ancakDate)
                                <option value="{{ $ancakDate->date }}" {{ $ancakDate->date === $tanggal ? 'selected' : '' }}>{{ $ancakDate->date }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Mutu Buah">
                                @foreach($buahDates as $buah)
                                <option value="{{ $buah->date }}" {{ $buah->date === $tanggal ? 'selected' : '' }}>{{ $buah->date }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Mutu Transport">
                                @foreach($TransportDates as $TransportDate)
                                <option value="{{ $TransportDate->date }}" {{ $TransportDate->date === $tanggal ? 'selected' : '' }}>{{ $TransportDate->date }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Mutu all">
                                @foreach($commonDates as $common)
                                <option value="{{ $common->date }}" {{ $common->date === $tanggal ? 'selected' : '' }}>{{ $common->date }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <button type="button" class="ml-2 btn btn-primary" id="show-button" disabled>Show</button>
                </form>

                <div class="afd"> ESTATE/ AFD : {{$est}}-{{$afd}}</div>
                <div class="afd">TANGGAL : <span id="selectedDate">{{ $tanggal }}</span></div>

            </div>

        </div>
        <!-- animasi loading -->
        <div id="lottie-container" style="width: 100%; height: 100%; position: fixed; top: 0; left: 0; background-color: rgba(255, 255, 255, 0.8); display: none; z-index: 9999;">
            <div id="lottie-animation" style="width: 200px; height: 200px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></div>
        </div>
        <div id="lottie-container1" style="width: 100%; height: 100%; position: fixed; top: 0; left: 0; background-color: rgba(255, 255, 255, 0.8); display: none; z-index: 9999;">
            <div id="lottie-animation" style="width: 100px; height: 100px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></div>
        </div>


        <!-- end animasi -->
    </div>

    <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3">
        <form action="{{ route('pdfBA') }}" method="POST" class="form-inline" style="display: inline;" target="_blank">
            {{ csrf_field() }}
            <input type="hidden" name="estBA" id="estpdf" value="{{$est}}">
            <input type="hidden" name="afdBA" id="afdpdf" value="{{$afd}}">
            <input type="hidden" name="tglPDF" id="tglPDF" value="{{ $tanggal }}">

            <button type="submit" class="ml-2" id="download-button" disabled>

                <div id="lottie-download" style="width: 24px; height: 24px; display: inline-block;"></div> Download BA
            </button>


        </form>
    </div>


    <div class="d-flex justify-content-center mt-3 mb-4 ml-3 mr-3 border border-dark ">
        <div class="Wraping">
            <h1 class="text-center">Tabel Mutu Ancak</h1>
            <table border="1" id="mutu_ancak">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Estate</th>
                        <th>Afdeling</th>
                        <th>Blok</th>
                        <th>Petugas</th>
                        <th>Sph</th>
                        <th>Br1</th>
                        <th>Br2</th>
                        <th>Jalur_masuk</th>
                        <th>Status_panen</th>
                        <th>Kemandoran</th>
                        <th>Ancak_pemanen</th>
                        <th>Sample</th>
                        <th>Pokok_kuning</th>
                        <th>Piringan_semak</th>
                        <th>Underpruning</th>
                        <th>Overpruning</th>
                        <th>Jjg</th>
                        <th>Brtp</th>
                        <th>Brtk</th>
                        <th>Brtgl</th>
                        <th>Bhts</th>
                        <th>Bhtm1</th>
                        <th>Bhtm2</th>
                        <th>Bhtm3</th>
                        <th>Ps</th>
                        <th>Sp</th>
                        <th>Pokok_panen</th>
                        <th>Komentar</th>
                        <th>Status</th>
                        @if (session('user_name') == 'Dennis Irawan')
                        <th>Aksi</th>
                        @endif

                    </tr>
                </thead>
                <tbody id="tab1">
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
        <div class="Wraping">
            <h1 class="text-center">Tabel Mutu Buah</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Estate</th>
                        <th>Afdeling</th>
                        <th>Tph Baris</th>
                        <th>Blok</th>
                        <th>Petugas</th>
                        <th>Ancak Pemanen</th>
                        <th>Bmk</th>
                        <th>Bmt</th>
                        <th>Empty</th>
                        <th>Jumlah janjang</th>
                        <th>Overripe</th>
                        <th>Abnormal</th>
                        <th>Alas_br</th>
                        <th>Vcut</th>
                        <th>Komentar</th>
                        @if (session('user_name') == 'Dennis Irawan')
                        <th>Aksi</th>
                        @endif

                    </tr>
                </thead>


                <tbody id="tab2">
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
        <div class="Wraping">
            <h1 class="text-center">Tabel Mutu Transport</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Estate</th>
                        <th>Afdeling</th>
                        <th>Blok</th>
                        <th>Tph_baris</th>
                        <th>Petugas</th>
                        <th>Rst</th>
                        <th>Bt</th>
                        <th>Komentar</th>
                        @if (session('user_name') == 'Dennis Irawan')
                        <th>Aksi</th>
                        @endif

                    </tr>
                </thead>


                <tbody id="tab3">
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img id="modalImage" src="" style="width: 100%;">
        </div>
    </div>
    <div id="update-modal" class="modal">
        <div class="modal-content">
            <h2>Update Mutu Ancak</h2>
            <button id="close-modal" class="btn btn-secondary">Tutup</button>
            <form id="update-form" action="{{ route('updateBA') }}" enctype="multipart/form-data" method="POST">
                {{ csrf_field() }}
                <input type="hidden" id="update-id" name="id">
                <input type="hidden" id="est" name="est" value="{{$est}}">
                <input type="hidden" id="afd" name="afd" value="{{$afd}}">
                <input type="hidden" id="date" name="date">
                <div class="mb-3">
                    <label for="update-blokCak" class="col-form-label">Blok</label>
                    <input type="text" class="form-control" id="update-blokCak" name="blokCak" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-sph" class="col-form-label">SPH</label>
                    <input type="text" class="form-control" id="update-sph" name="sph" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-br1" class="col-form-label">BR 1</label>
                    <input type="text" class="form-control" id="update-br1" name="br1" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-br2" class="col-form-label">BR 2</label>
                    <input type="text" class="form-control" id="update-br2" name="br2" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-sampCak" class="col-form-label">Sample</label>
                    <input type="text" class="form-control" id="update-sampCak" name="sampCak" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-pkKuning" class="col-form-label">Pokok Kuning</label>
                    <input type="text" class="form-control" id="update-pkKuning" name="pkKuning" value="" required>
                </div>

                <div class="mb-3">
                    <label for="update-prSmk" class="col-form-label">Piringan Semak</label>
                    <input type="text" class="form-control" id="update-prSmk" name="prSmk" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-undrPR" class="col-form-label">Underpruning</label>
                    <input type="text" class="form-control" id="update-undrPR" name="undrPR" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-overPR" class="col-form-label">Overpruning</label>
                    <input type="text" class="form-control" id="update-overPR" name="overPR" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-jjgCak" class="col-form-label">Janjang</label>
                    <input type="text" class="form-control" id="update-jjgCak" name="jjgCak" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-brtp" class="col-form-label">BRTP</label>
                    <input type="text" class="form-control" id="update-brtp" name="brtp" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-brtk" class="col-form-label">BRTK</label>
                    <input type="text" class="form-control" id="update-brtk" name="brtk" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-brtgl" class="col-form-label">BRTGL</label>
                    <input type="text" class="form-control" id="update-brtgl" name="brtgl" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-bhts" class="col-form-label">BHTS</label>
                    <input type="text" class="form-control" id="update-bhts" name="bhts" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-bhtm1" class="col-form-label">BHTM1</label>
                    <input type="text" class="form-control" id="update-bhtm1" name="bhtm1" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-bhtm2" class="col-form-label">BHTM2</label>
                    <input type="text" class="form-control" id="update-bhtm2" name="bhtm2" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-bhtm3" class="col-form-label">BHTM3</label>
                    <input type="text" class="form-control" id="update-bhtm3" name="bhtm3" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-ps" class="col-form-label">PS</label>
                    <input type="text" class="form-control" id="update-ps" name="ps" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-sp" class="col-form-label">SP</label>
                    <input type="text" class="form-control" id="update-sp" name="sp" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-pk_panenCAk" class="col-form-label">Pokok Panen</label>
                    <input type="text" class="form-control" id="update-pk_panenCAk" name="pk_panenCAk" value="" required>
                </div>
                <!-- Add your other input fields here -->
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <div id="update-modal-buah" class="modal">
        <div class="modal-content">
            <h2>Update Mutu Buah</h2>
            <button id="close-modal-buah" class="btn btn-secondary">Tutup</button>
            <form id="update-formBuah" action="{{ route('updateBA') }}" enctype="multipart/form-data" method="POST">
                {{ csrf_field() }}
                <input type="hidden" id="update-ids" name="id_bh">
                <input type="hidden" id="est" name="est" value="{{$est}}">
                <input type="hidden" id="afd" name="afd" value="{{$afd}}">
                <input type="hidden" id="date" name="date">
                <div class="mb-3">
                    <label for="update-estBH" class="col-form-label">Estate</label>
                    <input type="text" class="form-control" id="update-estBH" name="estBH" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-afdBH" class="col-form-label">Afdeling</label>
                    <input type="text" class="form-control" id="update-afdBH" name="afdBH" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-tphBH" class="col-form-label">TPH Baris</label>
                    <input type="text" class="form-control" id="update-tphBH" name="tphBH" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-blok_bh" class="col-form-label">Blok</label>
                    <input type="text" class="form-control" id="update-blok_bh" name="blok_bh" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-petugasBH" class="col-form-label">Petugas</label>
                    <input type="text" class="form-control" id="update-petugasBH" name="petugasBH" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-pemanen_bh" class="col-form-label">Ancak Pemanen</label>
                    <input type="text" class="form-control" id="update-pemanen_bh" name="pemanen_bh" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-bmt" class="col-form-label">BMT</label>
                    <input type="text" class="form-control" id="update-bmt" name="bmt" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-bmk" class="col-form-label">BMK </label>
                    <input type="text" class="form-control" id="update-bmk" name="bmk" value="" required>
                </div>

                <div class="mb-3">
                    <label for="update-emptyBH" class="col-form-label">Empty</label>
                    <input type="text" class="form-control" id="update-emptyBH" name="emptyBH" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-jjgBH" class="col-form-label">Jumlah Janjang</label>
                    <input type="text" class="form-control" id="update-jjgBH" name="jjgBH" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-overBH" class="col-form-label">OverRipe</label>
                    <input type="text" class="form-control" id="update-overBH" name="overBH" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-abrBH" class="col-form-label">Abnormal</label>
                    <input type="text" class="form-control" id="update-abrBH" name="abrBH" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-vcutBH" class="col-form-label">V Cut</label>
                    <input type="text" class="form-control" id="update-vcutBH" name="vcutBH" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-alsBR" class="col-form-label">Alas BR</label>
                    <input type="text" class="form-control" id="update-alsBR" name="alsBR" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-kmnBH" class="col-form-label">Komentar</label>
                    <input type="text" class="form-control" id="update-kmnBH" name="kmnBH" value="" required>
                </div>

                <!-- Add your other input fields here -->
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <div id="update-modal-trans" class="modal">
        <div class="modal-content">
            <h2>Update Mutu Trans</h2>
            <button id="close-modal-trans" class="btn btn-secondary">Tutup</button>
            <form id="update-formTrans" action="{{ route('updateBA') }}" enctype="multipart/form-data" method="POST">
                {{ csrf_field() }}
                <input type="hidden" id="update-id_trans" name="id_trans">
                <input type="hidden" id="est" name="est" value="{{$est}}">
                <input type="hidden" id="afd" name="afd" value="{{$afd}}">
                <input type="hidden" id="date" name="date">
                <div class="mb-3">
                    <label for="update-estTrans" class="col-form-label">Estate</label>
                    <input type="text" class="form-control" id="update-estTrans" name="estTrans" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-afd_trans" class="col-form-label">AFD</label>
                    <input type="text" class="form-control" id="update-afd_trans" name="afd_trans" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-blok_trans" class="col-form-label">BLOk</label>
                    <input type="text" class="form-control" id="update-blok_trans" name="blok_trans" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-tphbrTrans" class="col-form-label">TPH Baris</label>
                    <input type="text" class="form-control" id="update-tphbrTrans" name="tphbrTrans" value="" required>
                </div>

                <div class="mb-3">
                    <label for="update-petugasTrans" class="col-form-label">Petugas</label>
                    <input type="text" class="form-control" id="update-petugasTrans" name="petugasTrans" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-bt_trans" class="col-form-label">BT </label>
                    <input type="text" class="form-control" id="update-bt_trans" name="bt_trans" value="" required>
                </div>

                <div class="mb-3">
                    <label for="update-rstTrans" class="col-form-label">Rst</label>
                    <input type="text" class="form-control" id="update-rstTrans" name="rstTrans" value="" required>
                </div>
                <div class="mb-3">
                    <label for="update-komentar_trans" class="col-form-label">Komentar</label>
                    <input type="text" class="form-control" id="update-komentar_trans" name="komentar_trans" value="" required>
                </div>

                <!-- Add your other input fields here -->
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>


    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <h2>Delete Mutu Ancak</h2>
            <button id="close-delete-modal" class="btn btn-secondary">Tutup</button>
            <form id="delete-form" action="{{ route('deleteBA') }}" method="POST" onsubmit="event.preventDefault(); handleDeleteFormSubmit();">

                {{ csrf_field() }}
                <input type="hidden" id="delete-id" name="id">
                <p>Apakah anda Yakin ingin Menghapus?</p>
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <div id="delete-modal-buah" class="modal">
        <div class="modal-content">
            <h2>Delete Mutu Buah</h2>
            <button id="close-delete-modals" class="btn btn-secondary">Tutup</button>
            <form id="delete-forms" action="{{ route('deleteBA') }}" method="POST" onsubmit="event.preventDefault();">
                {{ csrf_field() }}
                <input type="hidden" id="delete-ids" name="ids">
                <p>Apakah anda Yakin ingin Menghapus?</p>
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <div id="delete-modal-transport" class="modal">
        <div class="modal-content">
            <h2>Delete Mutu Transport</h2>
            <button id="close-delete-transport" class="btn btn-secondary">Tutup</button>
            <form id="delete-form-trans" method="POST" onsubmit="event.preventDefault();">
                {{ csrf_field() }}
                <input type="hidden" id="delete-transport" name="id_transport">
                <p>Apakah anda Yakin ingin Menghapus?</p>
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>

        </div>
    </div>



</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.14/lottie.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


@include('layout/footer')

<script>
    var currentUserName = "{{ session('user_name') }}";

    //untuk mengirim parameter tanggal ke download pdf BA
    document.addEventListener('DOMContentLoaded', function() {
        const showButton = document.getElementById('show-button');
        const inputDate = document.getElementById('inputDate');
        const selectedDate = document.getElementById('selectedDate');
        const tglPDF = document.getElementById('tglPDF');
        const downloadButton = document.getElementById('download-button');
        const lottieDownload = document.getElementById('lottie-download');

        // Initialize Lottie animation
        const downloadAnimation = lottie.loadAnimation({
            container: lottieDownload,
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://assets2.lottiefiles.com/packages/lf20_eUext1.json'
        });

        showButton.addEventListener('click', function() {
            selectedDate.textContent = inputDate.value;
            tglPDF.value = inputDate.value;
            downloadButton.disabled = false;
        });
    });
    ///

    function enableShowButton() {
        const showButton = document.getElementById("show-button");
        showButton.disabled = false;
    }

    function updateDate() {
        const inputDate = document.getElementById("inputDate");
        const selectedDate = inputDate.value;
        document.getElementById("date").value = selectedDate;

        // Update the "selectedDate" span
        document.getElementById("selectedDate").textContent = selectedDate;

        // Enable the "Show" button once a date is selected
        enableShowButton();
    }


    document.addEventListener("DOMContentLoaded", function() {
        const inputDate = document.getElementById("inputDate");
        if (inputDate.value) {
            enableShowButton();
        }
    });

    function setInitialDate() {
        const inputDate = document.getElementById("inputDate");
        const selectedDate = inputDate.value;
        document.getElementById("date").value = selectedDate;
    }

    document.addEventListener("DOMContentLoaded", function() {
        setInitialDate();

        // Add an event listener to update the date when the selected date changes
        const inputDate = document.getElementById("inputDate");
        inputDate.addEventListener("change", updateDate);
    });
    //untuk menbuat timbol tutup di modal
    function closeModal() {
        const updateModal = document.getElementById("update-modal");
        updateModal.style.display = "none";
        const updateModal2 = document.getElementById("update-modal-buah");
        updateModal2.style.display = "none";
        const updateModal3 = document.getElementById("update-modal-trans");
        updateModal3.style.display = "none";
    }
    document.addEventListener("DOMContentLoaded", function() {
        setInitialDate();

        // Add an event listener to update the date when the selected date changes
        const inputDate = document.getElementById("inputDate");
        inputDate.addEventListener("change", updateDate);

        // Add event listeners to close the modals when the close buttons are clicked
        const closeModalButton = document.getElementById("close-modal");
        closeModalButton.addEventListener("click", closeModal);

        const closeModalButtonBuah = document.getElementById("close-modal-buah");
        closeModalButtonBuah.addEventListener("click", closeModal);
        const closeModalButtonTrans = document.getElementById("close-modal-trans");
        closeModalButtonTrans.addEventListener("click", closeModal);
    });
    //buat animasi loading ketika tombol show di klik
    const lottieContainer = document.getElementById('lottie-container');
    const lottieAnimation = lottie.loadAnimation({
        container: lottieContainer,
        renderer: "svg",
        loop: true,
        autoplay: false,
        path: "https://assets3.lottiefiles.com/private_files/lf30_fup2uejx.json",
    });
    const lottieContainer1 = document.getElementById('lottie-container1');
    const lottieAnimation1 = lottie.loadAnimation({
        container: lottieContainer1,
        renderer: "svg",
        loop: true,
        autoplay: false,
        path: "https://assets3.lottiefiles.com/packages/lf20_vfcbh2yp.json",
    });




    function fetchAndUpdateData() {
        lottieAnimation.play(); // Start the Lottie animation
        lottieContainer.style.display = 'block'; // Display the Lottie container

        $('#tab1').empty()
        $('#tab2').empty()
        $('#tab3').empty()
        var Tanggal = document.getElementById('inputDate').value;
        var est = document.getElementById('est').value;
        var afd = document.getElementById('afd').value;
        var _token = $('input[name="_token"]').val();

        $.ajax({
            url: "{{ route('filterDataDetail') }}",
            method: "GET",
            data: {
                Tanggal,
                est,
                afd,
                _token: _token
            },
            success: function(result) {
                lottieAnimation.stop(); // Stop the Lottie animation
                lottieContainer.style.display = 'none'; // Hide the Lottie container

                //modal

                // Get the modal
                const modal = document.getElementById("imageModal");

                // Get the image element inside the modal
                const modalImage = document.getElementById("modalImage");

                // Get the close button
                const closeBtn = document.getElementsByClassName("close")[0];

                // Function to show the modal with the clicked image
                function showModal(src) {
                    modalImage.src = src;
                    modal.style.display = "block";
                }

                // When the user clicks on the close button, close the modal
                closeBtn.onclick = function() {
                    modal.style.display = "none";
                }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }
                //

                var parseResult = JSON.parse(result)
                var mutuAncak = Object.entries(parseResult['mutuAncak'])
                var mutuBuah = Object.entries(parseResult['mutuBuah'])
                var mutuTransport = Object.entries(parseResult['mutuTransport'])

                // Set the onclick event for the confirm delete button

                //modal untuk mnerima data untuk mutu ancak
                function openUpdateModal(id,
                    blok_ancak,
                    sph_ancak,
                    br1_cak,
                    br2_cak,
                    sample_cak,
                    pokok_kuning_cak,
                    piringan_semak_cak,
                    underpruning_cak,
                    overpruning_cak,
                    jjg_cak,
                    brtp_cak,
                    brtk_cak,
                    brtgl_cak,
                    bhts_cak,
                    bhtm1_cak,
                    bhtm2_cak,
                    bhtm3_cak,
                    ps_cak,
                    sp_cak,
                    pokok_panen_cak) {
                    const updateModal = document.getElementById('update-modal');
                    const updateForm = document.getElementById('update-form');
                    const updateId = document.getElementById('update-id');
                    const bloks = document.getElementById('update-blokCak');
                    const updateSph = document.getElementById('update-sph');
                    const updateBr1 = document.getElementById('update-br1');
                    const updateBr2 = document.getElementById('update-br2');
                    const sample = document.getElementById('update-sampCak');
                    const pokok_kuning = document.getElementById('update-pkKuning');

                    const piringan_semak = document.getElementById('update-prSmk');
                    const underpruning = document.getElementById('update-undrPR');
                    const overpruning = document.getElementById('update-overPR');
                    const janjang = document.getElementById('update-jjgCak');
                    const brtp = document.getElementById('update-brtp');
                    const brtk = document.getElementById('update-brtk');
                    const brtgl = document.getElementById('update-brtgl');
                    const bhts = document.getElementById('update-bhts');
                    const bhtm1 = document.getElementById('update-bhtm1');
                    const bhtm2 = document.getElementById('update-bhtm2');
                    const bhtm3 = document.getElementById('update-bhtm3');
                    const ps = document.getElementById('update-ps');
                    const sp = document.getElementById('update-sp');
                    const pk_panen = document.getElementById('update-pk_panenCAk');


                    updateId.value = id;
                    bloks.value = blok_ancak;
                    updateSph.value = sph_ancak;
                    updateBr1.value = br1_cak;
                    updateBr2.value = br2_cak;
                    sample.value = sample_cak;
                    pokok_kuning.value = pokok_kuning_cak;

                    piringan_semak.value = piringan_semak_cak;
                    underpruning.value = underpruning_cak;
                    overpruning.value = overpruning_cak;
                    janjang.value = jjg_cak;
                    brtp.value = brtp_cak;
                    brtk.value = brtk_cak;
                    brtgl.value = brtgl_cak;
                    bhts.value = bhts_cak;
                    bhtm1.value = bhtm1_cak;
                    bhtm2.value = bhtm2_cak;
                    bhtm3.value = bhtm3_cak;
                    ps.value = ps_cak;
                    sp.value = sp_cak;
                    pk_panen.value = pokok_panen_cak;

                    updateModal.style.display = 'block';

                    updateForm.onsubmit = function(event) {
                        event.preventDefault();
                        updateMutuAncak(event.target);
                    };
                }

                function createAksiButtons(row,
                    id,
                    blok_ancak,
                    sph_ancak,
                    br1_cak,
                    br2_cak,
                    sample_cak,
                    pokok_kuning_cak,
                    piringan_semak_cak,
                    underpruning_cak,
                    overpruning_cak,
                    jjg_cak,
                    brtp_cak,
                    brtk_cak,
                    brtgl_cak,
                    bhts_cak,
                    bhtm1_cak,
                    bhtm2_cak,
                    bhtm3_cak,
                    ps_cak,
                    sp_cak,
                    pokok_panen_cak) {
                    const td = document.createElement('td');
                    td.style.display = 'inline-flex';
                    if (currentUserName === 'Dennis Irawan') {
                        const updateBtn = document.createElement('button');
                        updateBtn.className = 'btn btn-success mr-2';
                        updateBtn.innerHTML = '<i class="nav-icon fa-solid fa-edit"></i>';
                        updateBtn.onclick = function() {
                            openUpdateModal(id,
                                blok_ancak,
                                sph_ancak,
                                br1_cak,
                                br2_cak,
                                sample_cak,
                                pokok_kuning_cak,
                                piringan_semak_cak,
                                underpruning_cak,
                                overpruning_cak,
                                jjg_cak,
                                brtp_cak,
                                brtk_cak,
                                brtgl_cak,
                                bhts_cak,
                                bhtm1_cak,
                                bhtm2_cak,
                                bhtm3_cak,
                                ps_cak,
                                sp_cak,
                                pokok_panen_cak);
                        };

                        td.appendChild(updateBtn);

                        const deleteBtn = document.createElement('button');
                        deleteBtn.id = 'deleteBtn-' + id;
                        deleteBtn.className = 'btn btn-danger';
                        deleteBtn.innerHTML = '<i class="nav-icon fa-solid fa-trash"></i>';
                        deleteBtn.onclick = function() {
                            const deleteModal = document.getElementById('delete-modal');
                            deleteModal.style.display = 'block';
                            currentRowToDelete = row;
                            currentIdToDelete = id;
                            document.getElementById('delete-id').value = id;
                        };
                        td.appendChild(deleteBtn);
                    }
                    row.appendChild(td);
                }

                document.getElementById('close-delete-modal').addEventListener('click', function() {
                    const deleteModal = document.getElementById('delete-modal');
                    deleteModal.style.display = 'none';
                });

                document.getElementById('delete-form').addEventListener('submit', function(event) {
                    event.preventDefault();

                    // Perform the deletion using the global variables
                    deleteData(currentIdToDelete, currentRowToDelete);

                    // Reset the global variables
                    currentRowToDelete = null;
                    currentIdToDelete = null;

                    // Close the delete modal
                    const deleteModal = document.getElementById('delete-modal');
                    deleteModal.style.display = 'none';
                });

                function handleDeleteFormSubmit() {
                    const deleteId = document.getElementById('delete-id').value;
                    const row = document.querySelector(`tr[data-id="${deleteId}"]`);
                    deleteData(deleteId, row);
                }


                function deleteData(id, row) {
                    // Create an AJAX request to delete the data
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '/deleteBA', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Successfully deleted, remove the row from the table
                            row.remove();

                            // Show the Lottie animation
                            lottieContainer1.style.display = 'block';
                            lottieAnimation1.play();

                            // Hide the Lottie animation after 3 seconds
                            setTimeout(() => {
                                lottieContainer1.style.display = 'none';
                                lottieAnimation1.stop();
                            }, 2000);
                        } else if (xhr.readyState === 4) {
                            // Error occurred, show an error message
                            alert('Error: Unable to delete data.');
                        }
                    };
                    xhr.send('id=' + encodeURIComponent(id));
                }

                //fungsi delete dan update mutu buah
                function mutuBuahUpdate(ids,
                    estateBH,
                    afdelingBH,
                    tph_barisBH,
                    blokBH,
                    petugasBH,
                    ancak_pemanenBH,
                    bmkBH,
                    bmtBH,
                    emptyBH,
                    jumlah_jjgBH,
                    overripeBH,
                    abnormalBH,
                    vcutBH,
                    alas_brBH,
                    komentarBH) {
                    // console.log(ids, blokBH, bmk, bmt, ancak_pemanenBH);
                    const updateModal = document.getElementById('update-modal-buah');
                    const updateForm = document.getElementById('update-formBuah');

                    const updateId = document.getElementById('update-ids');
                    const updateBlok = document.getElementById('update-blok_bh');
                    const updateBmt = document.getElementById('update-bmt');
                    const updateBmk = document.getElementById('update-bmk');
                    const updatePemanen = document.getElementById('update-pemanen_bh');

                    const estBH = document.getElementById('update-estBH');
                    const afdBH = document.getElementById('update-afdBH');

                    const tphBH = document.getElementById('update-tphBH');
                    const petugasBHs = document.getElementById('update-petugasBH');
                    const emptyBHS = document.getElementById('update-emptyBH');
                    const jjgBH = document.getElementById('update-jjgBH');
                    const overBH = document.getElementById('update-overBH');
                    const abrBH = document.getElementById('update-abrBH');
                    const vcutBHs = document.getElementById('update-vcutBH');
                    const alsBR = document.getElementById('update-alsBR');
                    const kmnBH = document.getElementById('update-kmnBH');

                    updateId.value = ids;
                    updateBlok.value = blokBH;
                    updateBmt.value = bmtBH;
                    updateBmk.value = bmkBH;
                    updatePemanen.value = ancak_pemanenBH;

                    estBH.value = estateBH;
                    afdBH.value = afdelingBH;
                    tphBH.value = tph_barisBH;

                    petugasBHs.value = petugasBH;
                    emptyBHS.value = emptyBH;
                    jjgBH.value = jumlah_jjgBH;
                    overBH.value = overripeBH;
                    abrBH.value = abnormalBH;
                    vcutBHs.value = vcutBH;
                    alsBR.value = alas_brBH;
                    kmnBH.value = komentarBH;

                    updateModal.style.display = 'block';

                    updateForm.onsubmit = function(event) {
                        event.preventDefault();
                        updateMutuBuah(event.target);
                    };
                }

                function buahAksibutton(row,
                    ids,
                    estateBH,
                    afdelingBH,
                    tph_barisBH,
                    blokBH,
                    petugasBH,
                    ancak_pemanenBH,
                    bmkBH,
                    bmtBH,
                    emptyBH,
                    jumlah_jjgBH,
                    overripeBH,
                    abnormalBH,
                    vcutBH,
                    alas_brBH,
                    komentarBH
                ) {

                    const td = document.createElement('td');
                    td.style.display = 'inline-flex';
                    if (currentUserName === 'Dennis Irawan') {
                        const updateBtn = document.createElement('button');
                        updateBtn.className = 'btn btn-success mr-2';
                        updateBtn.innerHTML = '<i class="nav-icon fa-solid fa-edit"></i>';
                        updateBtn.onclick = function() {
                            mutuBuahUpdate(ids,
                                estateBH,
                                afdelingBH,
                                tph_barisBH,
                                blokBH,
                                petugasBH,
                                ancak_pemanenBH,
                                bmkBH,
                                bmtBH,
                                emptyBH,
                                jumlah_jjgBH,
                                overripeBH,
                                abnormalBH,
                                vcutBH,
                                alas_brBH,
                                komentarBH)
                        };
                        td.appendChild(updateBtn);

                        const deleteBtn = document.createElement('button');
                        deleteBtn.id = 'deleteBtn-' + ids;
                        deleteBtn.className = 'btn btn-danger';
                        deleteBtn.innerHTML = '<i class="nav-icon fa-solid fa-trash"></i>';
                        deleteBtn.onclick = function() {
                            // Open the delete modal
                            const deleteModal = document.getElementById('delete-modal-buah');
                            deleteModal.style.display = 'block';

                            // Store the current row and id in the global variables
                            currentRowToDelete = row;
                            currentIdToDelete = ids;

                            // Set the value of the delete-id input field
                            document.getElementById('delete-ids').value = ids;
                        };

                        td.appendChild(deleteBtn);
                    }
                    row.appendChild(td);
                }
                document.getElementById('close-delete-modals').addEventListener('click', function() {
                    const deleteModal = document.getElementById('delete-modal-buah');
                    deleteModal.style.display = 'none';
                });

                document.getElementById('delete-forms').addEventListener('submit', function(event) {
                    event.preventDefault();

                    // Perform the deletion using the global variables
                    deleteBuah(currentIdToDelete, currentRowToDelete);

                    // Reset the global variables
                    currentRowToDelete = null;
                    currentIdToDelete = null;

                    // Close the delete modal
                    const deleteModal = document.getElementById('delete-modal-buah');
                    deleteModal.style.display = 'none';
                });

                function deleteBuah(id, row) {
                    // Create an AJAX request to delete the data
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '/deleteBA', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Successfully deleted, remove the row from the table
                            row.remove();

                            // Show the Lottie animation
                            lottieContainer1.style.display = 'block';
                            lottieAnimation1.play();

                            // Hide the Lottie animation after 3 seconds
                            setTimeout(() => {
                                lottieContainer1.style.display = 'none';
                                lottieAnimation1.stop();
                            }, 2000);
                        } else if (xhr.readyState === 4) {
                            // Error occurred, show an error message
                            alert('Error: Unable to delete data.');
                        }
                    };
                    xhr.send('ids=' + encodeURIComponent(id));

                }
                //fungsi delete dan update mutu transport

                function mutuTransUp(id_trans,
                    estate_trans,
                    afd_trans,
                    blok_trans,
                    tphbrs_trans,
                    petugas_trans,
                    rst_trans,
                    bt_trans,
                    komentar_trans) {
                    // console.log(id_trans, afd_trans, blok_trans);
                    const updateModal = document.getElementById('update-modal-trans');
                    const updateForm = document.getElementById('update-formTrans');
                    const updateId = document.getElementById('update-id_trans');
                    const est = document.getElementById('update-estTrans');
                    const afd = document.getElementById('update-afd_trans');
                    const blok = document.getElementById('update-blok_trans');
                    const tphTrans = document.getElementById('update-tphbrTrans');
                    const petugas = document.getElementById('update-petugasTrans');
                    const bt_transs = document.getElementById('update-bt_trans');
                    const rst = document.getElementById('update-rstTrans');
                    const komen = document.getElementById('update-komentar_trans');

                    updateId.value = id_trans;
                    est.value = estate_trans;
                    afd.value = afd_trans;
                    blok.value = blok_trans;
                    tphTrans.value = tphbrs_trans;
                    petugas.value = petugas_trans;
                    bt_transs.value = bt_trans;
                    rst.value = rst_trans;
                    komen.value = komentar_trans;

                    updateModal.style.display = 'block';

                    updateForm.onsubmit = function(event) {
                        event.preventDefault();
                        updateMutuTrans(event.target);
                    };
                }

                function transportAksiButton(row,
                    id_trans,
                    estate_trans,
                    afd_trans,
                    blok_trans,
                    tphbrs_trans,
                    petugas_trans,
                    rst_trans,
                    bt_trans,
                    komentar_trans
                ) {

                    const td = document.createElement('td');
                    td.style.display = 'flex'; // Change from 'inline-flex' to 'flex'
                    td.style.alignItems = 'center';
                    td.style.justifyContent = 'center';
                    if (currentUserName === 'Dennis Irawan') {
                        const updateButton = document.createElement('button');
                        updateButton.className = 'btn btn-success mr-2';
                        updateButton.innerHTML = '<i class="nav-icon fa-solid fa-edit"></i>';
                        updateButton.addEventListener('click', function() {
                            mutuTransUp(id_trans,
                                estate_trans,
                                afd_trans,
                                blok_trans,
                                tphbrs_trans,
                                petugas_trans,
                                rst_trans,
                                bt_trans,
                                komentar_trans);
                        });

                        const deleteBtn = document.createElement('button');
                        deleteBtn.id = 'deleteBtn-' + id_trans;
                        deleteBtn.className = 'btn btn-danger';
                        deleteBtn.innerHTML = '<i class="nav-icon fa-solid fa-trash"></i>';
                        deleteBtn.onclick = function() {
                            // Open the delete modal
                            const deleteModal = document.getElementById('delete-modal-transport');
                            deleteModal.style.display = 'block';

                            // Store the current row and id in the global variables
                            currentRowToDelete = row;
                            currentIdToDelete = id_trans;

                            // Set the value of the delete-id input field
                            document.getElementById('delete-transport').value = id_trans;
                        };

                        td.appendChild(updateButton);
                        td.appendChild(deleteBtn);
                    }
                    row.appendChild(td);
                }


                document.getElementById('close-delete-transport').addEventListener('click', function() {
                    const deleteModal = document.getElementById('delete-modal-transport');
                    deleteModal.style.display = 'none';
                });

                document.getElementById('delete-form-trans').addEventListener('submit', function(event) {
                    event.preventDefault();

                    // Perform the deletion using the global variables
                    deleteTrans(currentIdToDelete, currentRowToDelete);

                    // Reset the global variables
                    currentRowToDelete = null;
                    currentIdToDelete = null;

                    // Close the delete modal
                    const deleteModal = document.getElementById('delete-modal-transport');
                    deleteModal.style.display = 'none';
                });


                function deleteTrans(id, row) {
                    // Create an AJAX request to delete the data
                    const xhr = new XMLHttpRequest();
                    xhr.open('DELETE', `/deleteTrans/${encodeURIComponent(id)}`, true);
                    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Successfully deleted, remove the row from the table
                            row.remove();

                            // Show the Lottie animation
                            lottieContainer1.style.display = 'block';
                            lottieAnimation1.play();

                            // Hide the Lottie animation after 3 seconds
                            setTimeout(() => {
                                lottieContainer1.style.display = 'none';
                                lottieAnimation1.stop();
                            }, 2000);
                        } else if (xhr.readyState === 4) {
                            // Error occurred, show an error message
                            alert('Error: Unable to delete data.');
                        }
                    };
                    xhr.send();
                }


                //bagian menampilkan tabel semua mutu buah ancak dsb
                function createTableCell(value) {
                    const cell = document.createElement('td');
                    cell.innerText = value;
                    return cell;
                }

                function createTableRow(items) {
                    const tr = document.createElement('tr');
                    items.forEach(item => {
                        const td = document.createElement('td');
                        if (item instanceof HTMLElement) {
                            td.appendChild(item);
                        } else {
                            td.textContent = item;
                        }
                        tr.appendChild(td);
                    });
                    return tr;
                }

                function createImageElement(src) {
                    const img = document.createElement('img');
                    img.src = src;
                    img.style.width = '100px';
                    img.addEventListener('click', () => showModal(src));
                    return img;
                }

                var mutuAncak1 = mutuAncak
                var tRans = document.getElementById('tab1');
                mutuAncak1.forEach((element, index) => {

                    const items = [
                        index + 1,
                        element[1].estate,
                        element[1].afdeling,
                        element[1].blok,
                        element[1].petugas,
                        element[1].sph,
                        element[1].br1,
                        element[1].br2,
                        element[1].jalur_masuk,
                        element[1].status_panen,
                        element[1].kemandoran,
                        element[1].ancak_pemanen,
                        element[1].sample,
                        element[1].pokok_kuning,
                        element[1].piringan_semak,
                        element[1].underpruning,
                        element[1].overpruning,
                        element[1].jjg,
                        element[1].brtp,
                        element[1].brtk,
                        element[1].brtgl,
                        element[1].bhts,
                        element[1].bhtm1,
                        element[1].bhtm2,
                        element[1].bhtm3,
                        element[1].ps,
                        element[1].sp,
                        element[1].pokok_panen,
                        element[1].komentar,
                        element[1].aksi,
                    ];
                    const row = createTableRow(items);
                    // Inside the forEach loop

                    createAksiButtons(row, element[1].id,
                        element[1].blok,
                        element[1].sph,
                        element[1].br1,
                        element[1].br2,
                        element[1].sample,
                        element[1].pokok_kuning,
                        element[1].piringan_semak,
                        element[1].underpruning,
                        element[1].overpruning,
                        element[1].jjg,
                        element[1].brtp,
                        element[1].brtk,
                        element[1].brtgl,
                        element[1].bhts,
                        element[1].bhtm1,
                        element[1].bhtm2,
                        element[1].bhtm3,
                        element[1].ps,
                        element[1].sp,
                        element[1].pokok_panen,
                    );


                    tRans.appendChild(row);
                });



                // console.log(mutuBuah);

                var mutuBuahtb = mutuBuah
                var tbuah = document.getElementById('tab2');
                mutuBuahtb.forEach((element, index) => {
                    const items = [
                        index + 1,
                        element[1].estate,
                        element[1].afdeling,
                        element[1].tph_baris,
                        element[1].blok,
                        element[1].petugas,
                        element[1].ancak_pemanen,
                        element[1].bmk,
                        element[1].bmt,
                        element[1].empty,
                        element[1].jumlah_jjg,
                        element[1].overripe,
                        element[1].abnormal,
                        element[1].vcut,
                        element[1].alas_br,
                        element[1].komentar,
                    ];

                    const row = createTableRow(items);

                    buahAksibutton(row, element[1].id,
                        element[1].estate,
                        element[1].afdeling,
                        element[1].tph_baris,
                        element[1].blok,
                        element[1].petugas,
                        element[1].ancak_pemanen,
                        element[1].bmk,
                        element[1].bmt,
                        element[1].empty,
                        element[1].jumlah_jjg,
                        element[1].overripe,
                        element[1].abnormal,
                        element[1].vcut,
                        element[1].alas_br,
                        element[1].komentar
                    );

                    tbuah.appendChild(row);
                });
                // console.log(mutuTransport);
                var mutuTrans = mutuTransport
                var tTrans = document.getElementById('tab3');
                mutuTrans.forEach((element, index) => {
                    const items = [
                        index + 1,
                        element[1].estate,
                        element[1].afdeling,
                        element[1].blok,
                        element[1].tph_baris,
                        element[1].petugas,
                        element[1].rst,
                        element[1].bt,
                        element[1].komentar,
                    ];

                    const row = createTableRow(items);

                    transportAksiButton(row, element[1].id,
                        element[1].estate,
                        element[1].afdeling,
                        element[1].blok,
                        element[1].tph_baris,
                        element[1].petugas,
                        element[1].rst,
                        element[1].bt,
                        element[1].komentar,
                    );

                    tTrans.appendChild(row);
                });

            },
            error: function() {
                lottieAnimation.stop(); // Stop the Lottie animation
                lottieContainer.style.display = 'none'; // Hide the Lottie container
            }
        });



        function updateMutuAncak(form) {
            const formData = new FormData(form);

            $.ajax({
                type: "POST",
                url: "{{ route('updateBA') }}",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                success: function(data, textStatus, xhr) {
                    // Check if the status code is 200 OK
                    if (xhr.status === 200) {
                        // Close the modal
                        const updateModal = document.getElementById('update-modal');
                        updateModal.style.display = 'none';

                        // Show a success animation for 3 seconds
                        alert('Data berhasil di Perbaharui!')

                        // Refresh the data on the page
                        fetchAndUpdateData();
                    } else {
                        alert('Mutu ancak gagal diperbarui');
                        // Refresh the data on the page
                        fetchAndUpdateData();
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error data:", xhr.responseJSON);
                    console.error("There was a problem with the fetch operation:", error);
                },
            });
        }

        function updateMutuBuah(form) {
            const formData = new FormData(form);

            $.ajax({
                type: "POST",
                url: "{{ route('updateBA') }}",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                success: function(data, textStatus, xhr) {
                    // Check if the status code is 200 OK
                    if (xhr.status === 200) {
                        // Close the modal
                        const updateModal = document.getElementById('update-modal-buah');
                        updateModal.style.display = 'none';

                        // Show a success animation for 3 seconds
                        alert('Data berhasil di Perbaharui!')

                        // Refresh the data on the page
                        fetchAndUpdateData();
                    } else {
                        alert('Mutu ancak gagal diperbarui');
                        // Refresh the data on the page
                        fetchAndUpdateData();
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error data:", xhr.responseJSON);
                    console.error("There was a problem with the fetch operation:", error);
                },
            });
        }

        function updateMutuTrans(form) {
            const formData = new FormData(form);

            $.ajax({
                type: "POST",
                url: "{{ route('updateBA') }}",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                success: function(data, textStatus, xhr) {
                    // Check if the status code is 200 OK
                    if (xhr.status === 200) {
                        // Close the modal
                        const updateModal = document.getElementById('update-modal-trans');
                        updateModal.style.display = 'none';

                        // Show a success animation for 3 seconds
                        alert('Data berhasil di Perbaharui!')

                        // Refresh the data on the page
                        fetchAndUpdateData();
                    } else {
                        alert('Mutu ancak gagal diperbarui');
                        // Refresh the data on the page
                        fetchAndUpdateData();
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error data:", xhr.responseJSON);
                    console.error("There was a problem with the fetch operation:", error);
                },
            });
        }
    }

    function Show() {
        fetchAndUpdateData();
    }

    document.querySelector('button[type="button"]').addEventListener('click', Show);
</script>