<!DOCTYPE html>
<html lang="en">

<link rel="stylesheet" type="text/css" href="http://w2ui.com/src/w2ui-1.4.2.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />


@include('layout/header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

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
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }


    /* Modal Content */
    .modal-content {
        position: relative;
        background-color: #fefefe;
        margin: 10% auto;
        padding: 10px;
        border: 1px solid #888;
        width: 60%;
        /* Adjust this value to change the modal width */
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        animation-name: animatetop;
        animation-duration: 0.4s;
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

    /* The image inside the modal */
</style>

<div class="content-wrapper">
    <div class="card table_wrapper">
        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
            <h2>BERITA ACARA REKAPITULASI PEMERIKSAAN KUALITAS PANEN QUALITY CONTROL</h2>
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
                        <select class="form-control" name="date" id="inputDate">
                            <optgroup label="Mutu Ancak">
                                @foreach($ancakDates as $ancakDate)
                                <option value="{{ $ancakDate->date }}" {{ $ancakDate->date === $tanggal ? 'selected' : '' }}>{{ $ancakDate->date }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Mutu Buah">
                                @foreach($buahDates as $buahDate)
                                <option value="{{ $buahDate->date }}" {{ $buahDate->date === $tanggal ? 'selected' : '' }}>{{ $buahDate->date }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Mutu Transport">
                                @foreach($TransportDates as $TransportDate)
                                <option value="{{ $TransportDate->date }}" {{ $buahDate->date === $tanggal ? 'selected' : '' }}>{{ $TransportDate->date }}</option>
                                @endforeach
                            </optgroup>
                        </select>

                    </div>
                    <button type="button" class="ml-2">Show</button>
                </form>

                <div class="afd"> ESTATE/ AFD : {{$est}}-{{$afd}}</div>
                <div class="afd">TANGGAL {{$tanggal}} </div>
            </div>

        </div>
        <!-- animasi loading -->
        <div id="lottie-container" style="width: 100%; height: 100%; position: fixed; top: 0; left: 0; background-color: rgba(255, 255, 255, 0.8); display: none; z-index: 9999;">
            <div id="lottie-animation" style="width: 200px; height: 200px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></div>
        </div>
        <!-- end animasi -->
    </div>

    <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3">
        <form action="" method="" class="form-inline" style="display: inline;">
            {{ csrf_field() }}
            <button type="submit" class="ml-2">PDF</button>
        </form>
    </div>
    <div class="d-flex justify-content-center mt-3 mb-4 ml-3 mr-3 border border-dark ">
        <div class="Wraping">
            <h1 class="text-center">Tabel Mutu Ancak</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>estate</th>
                        <th>afdeling</th>
                        <th>blok</th>
                        <th>petugas</th>
                        <th>datetime</th>
                        <th>lat_awal</th>
                        <th>lon_awal</th>
                        <th>lat_akhir</th>
                        <th>lon_akhir</th>
                        <th>sph</th>
                        <th>br1</th>
                        <th>br2</th>
                        <th>jalur_masuk</th>
                        <th>status_panen</th>
                        <th>kemandoran</th>
                        <th>ancak_pemanen</th>
                        <th>sample</th>
                        <th>pokok_kuning</th>
                        <th>piringan_semak</th>
                        <th>underpruning</th>
                        <th>overpruning</th>
                        <th>jjg</th>
                        <th>brtp</th>
                        <th>brtk</th>
                        <th>brtgl</th>
                        <th>bhts</th>
                        <th>bhtm1</th>
                        <th>bhtm2</th>
                        <th>bhtm3</th>
                        <th>ps</th>
                        <th>sp</th>
                        <th>pokok_panen</th>
                        <th>foto_temuan</th>
                        <th>foto_temuan1</th>
                        <th>foto_fu</th>
                        <th>foto_fu1</th>
                        <th>komentar</th>
                        <th>status</th>
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
                        <th>id</th>
                        <th>abnormal</th>
                        <th>afdeling</th>
                        <th>alas_br</th>
                        <th>ancak_pemanen</th>
                        <th>blok</th>
                        <th>bmk</th>
                        <th>bmt</th>
                        <th>bulan</th>
                        <th>datetime</th>
                        <th>empty</th>
                        <th>estate</th>
                        <th>foto_temuan</th>
                        <th>id</th>
                        <th>jumlah_jjg</th>
                        <th>komentar</th>
                        <th>lat</th>
                        <th>lon</th>
                        <th>overripe</th>
                        <th>petugas</th>
                        <th>tahun</th>
                        <th>tph_baris</th>
                        <th>vcut</th>
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
                        <th>id</th>
                        <th>afdeling</th>
                        <th>blok</th>
                        <th>bt</th>
                        <th>bulan</th>
                        <th>datetime</th>
                        <th>estate</th>
                        <th>foto_fu</th>
                        <th>foto_temuan</th>
                        <th>id</th>
                        <th>komentar</th>
                        <th>lat</th>
                        <th>lon</th>
                        <th>petugas</th>
                        <th>rst</th>
                        <th>tahun</th>
                        <th>tph_baris</th>
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


</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.14/lottie.min.js"></script>

@include('layout/footer')

<script>
    const lottieContainer = document.getElementById('lottie-container');

    const lottieAnimation = lottie.loadAnimation({
        container: lottieContainer,
        renderer: "svg",
        loop: true,
        autoplay: false,
        path: "https://assets3.lottiefiles.com/private_files/lf30_fup2uejx.json",
    });

    function Show() {
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

                var parseResult = JSON.parse(result)
                var mutuAncak = Object.entries(parseResult['mutuAncak'])
                var mutuBuah = Object.entries(parseResult['mutuBuah'])
                var mutuTransport = Object.entries(parseResult['mutuTransport'])

                console.log(mutuTransport);

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
                mutuAncak1.forEach(element => {
                    const fotoTemuanUrl = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/" + element[1].foto_temuan;
                    const fotoTemuanUrl2 = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/" + element[1].foto_temuan1;
                    const fotoTemuanUrl3 = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/" + element[1].foto_fu;
                    const items = [
                        element[0][0],

                        element[1].estate,
                        element[1].afdeling,
                        element[1].blok,
                        element[1].petugas,
                        element[1].datetime,
                        element[1].lat_awal,
                        element[1].lon_awal,
                        element[1].lat_akhir,
                        element[1].lon_akhir,
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
                        createImageElement(fotoTemuanUrl),
                        createImageElement(fotoTemuanUrl2),
                        createImageElement(fotoTemuanUrl3),

                        element[1].foto_fu1,
                        element[1].komentar,


                    ];

                    const row = createTableRow(items);
                    tRans.appendChild(row);
                });

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


                var mutuBuahtb = mutuBuah
                var tbuah = document.getElementById('tab2');
                mutuBuahtb.forEach(element => {
                    const items = [
                        element[1].id,
                        element[1].abnormal,
                        element[1].afdeling,
                        element[1].alas_br,
                        element[1].ancak_pemanen,
                        element[1].blok,
                        element[1].bmk,
                        element[1].bmt,
                        element[1].bulan,
                        element[1].datetime,
                        element[1].estate,
                        element[1].foto_temuan,
                        element[1].jumlah_jjg,
                        element[1].komentar,
                        element[1].lat,
                        element[1].lon,
                        element[1].overripe,
                        element[1].petugas,
                        element[1].tahun,
                        element[1].tph_baris,
                        element[1].vcut,
                    ];

                    const row = createTableRow(items);
                    tbuah.appendChild(row);
                });

                var mutuTrans = mutuTransport
                var tTrans = document.getElementById('tab3');
                mutuTrans.forEach(element => {
                    const items = [
                        element[1].id,
                        element[1].abnormal,
                        element[1].afdeling,
                        element[1].alas_br,
                        element[1].ancak_pemanen,
                        element[1].blok,
                        element[1].bmk,
                        element[1].bmt,
                        element[1].bulan,
                        element[1].datetime,
                        element[1].estate,
                        element[1].foto_temuan,
                        element[1].jumlah_jjg,
                        element[1].komentar,
                        element[1].lat,
                        element[1].lon,
                        element[1].overripe,
                        element[1].petugas,
                        element[1].tahun,
                        element[1].tph_baris,
                        element[1].vcut,
                    ];

                    const row = createTableRow(items);
                    tTrans.appendChild(row);
                });


            },
            error: function() {
                lottieAnimation.stop(); // Stop the Lottie animation
                lottieContainer.style.display = 'none'; // Hide the Lottie container
            }
        });
    }


    document.querySelector('button[type="button"]').addEventListener('click', Show);
</script>