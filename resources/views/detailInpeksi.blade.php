<!DOCTYPE html>
<html lang="en">

<link rel="stylesheet" type="text/css" href="http://w2ui.com/src/w2ui-1.4.2.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />


@include('layout/header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

<style>
    .label-bidang {
        font-size: 10pt;
        color: white;
        text-align: center;
        opacity: 0.6;
    }

    .popup_image {
        cursor: pointer;
    }

    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    /* Modal styles */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 9999;
        /* Sit on top */
        padding-top: 50px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.9);
        /* Black w/ opacity */
    }

    /* Modal content */
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 800px;
        max-height: 80vh;
    }

    /* Close button */
    .close {
        position: absolute;
        top: 10px;
        right: 25px;
        font-size: 35px;
        font-weight: bold;
        color: white;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #999;
        text-decoration: none;
        cursor: pointer;
    }
</style>




<div class="content-wrapper">



    <!-- //end tst -->
    @if ($buah || $transport || $ancak)
    <section class="content">
        @if(!empty($buah))
        <div class="container-fluid pt-3">
            <div class="card p-4">
                <h4 class="text-center mt-2" style="font-weight: bold">Mutu Buah- {{$est}} {{$afd}}</h4>
                <hr>
                <table id="ala" class="text-center" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2">EST</th>
                            <th rowspan="2">AFD</th>
                            <th rowspan="2">Blok</th>
                            <th rowspan="2">Petugas</th>
                            <th rowspan="2">Ancak Pemanen</th>
                            <th rowspan="2">Janjang Sample</th>
                            <th rowspan="2">Buah Mentah</th>
                            <th rowspan="2">Buah Masak</th>
                            <th rowspan="2">Buah Over</th>
                            <th rowspan="2">Buah Kosong</th>
                            <th rowspan="2">Buah Abnormal</th>
                            <th rowspan="2">v - cut</th>
                            <th rowspan="2">Alas Karung</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buah as $key => $item)
                        @foreach($item as $key1 => $item2)
                        @foreach($item2 as $key2 => $item3)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{$key1}}</td>
                            <td>{{$item3['blok']}}</td>
                            <td>{{$item3['petugas']}}</td>
                            <td>{{$item3['ancak_pemanen']}}</td>
                            <td>{{$item3['jumlah_jjg']}}</td>
                            <td>{{$item3['bmt']}}</td>
                            <td>{{$item3['bmk']}}</td>
                            <td>{{$item3['overripe']}}</td>
                            <td>{{$item3['empty']}}</td>
                            <td>{{$item3['abnormal']}}</td>
                            <td>{{$item3['vcut']}}</td>
                            <td>{{$item3['alas_br']}}</td>
                        </tr>
                        @endforeach
                        @endforeach
                        @endforeach
                    </tbody>
                </table>

                <!-- //table biasa -->
            </div>
            <br>
        </div>
        @else
        @endif

        @if(!empty($transport))
        <div class="container-fluid pt-3">
            <div class="card p-4">
                <h4 class="text-center mt-2" style="font-weight: bold">Mutu Transport- {{$est}} {{$afd}}</h4>
                <hr>
                <table id="listSidakTPH" class="text-center" style="width:100%">
                    {{ csrf_field() }}
                    <thead>
                        <tr>
                            <th rowspan="3">EST</th>
                            <th rowspan="3">AFD</th>
                            <th rowspan="3">TPH</th>
                            <th rowspan="3">Blok</th>
                            <th rowspan="3">Petugas</th>
                            <th rowspan="3">Brondolan Tinggal</th>
                            <th rowspan="3">Buah Tinggal</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($transport as $key=> $item)
                        @foreach($item as $key1 => $item2)
                        @foreach($item2 as $key2 => $item3)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{$key1}}</td>
                            <td>{{$item3['tph_baris']}}</td>
                            <td>{{$item3['blok']}}</td>
                            <td>{{$item3['petugas']}}</td>
                            <td>{{$item3['bt']}}</td>
                            <td>{{$item3['rst']}}</td>
                        </tr>
                        @endforeach
                        @endforeach
                        @endforeach
                    </tbody>
                </table>

            </div>
            <br>
        </div>
        @else
        @endif

        @if(!empty($ancak))
        <div class="container-fluid pt-3">
            <div class="card p-4">
                <h4 class="text-center mt-2" style="font-weight: bold">Mutu Ancak- {{$est}} {{$afd}}</h4>
                <hr>
                <table id="ancak" class="text-center" style="width:100%">
                    {{ csrf_field() }}
                    <thead>
                        <tr>
                            <th rowspan="3">EST</th>
                            <th rowspan="3">AFD</th>
                            <th rowspan="3">Blok</th>
                            <th rowspan="3">Sample</th>
                            <th rowspan="3">Petugas</th>
                            <th rowspan="3">Ancak Pemanen</th>
                            <th colspan="6">Brondolan Tinggal</th>
                            <th colspan="8">Buah Tinggal</th>
                            <th rowspan="3">Palepah sengklek</th>

                            <th rowspan="3">Status Panen</th>
                            <th rowspan="3">Kemandoran</th>
                            <th rowspan="3">Pokok kuning</th>
                            <th rowspan="3">Piringan semak</th>
                            <th rowspan="3">Underpruning</th>
                            <th rowspan="3">Overpruning</th>

                        </tr>
                        <tr>

                            <th rowspan="2" colspan="2">P</th>
                            <th rowspan="2" colspan="2">K</th>
                            <th rowspan="2" colspan="2">GL </th>

                        </tr>
                        <tr>

                            <th rowspan="2" colspan="2">S</th>
                            <th rowspan="2" colspan="2">M1</th>
                            <th rowspan="2" colspan="2">M2 </th>
                            <th rowspan="2" colspan="2">M3 </th>

                        </tr>

                    </thead>
                    <tbody>


                        @foreach($ancak as $key=> $item)
                        @foreach($item as $key1 => $item2)
                        @foreach($item2 as $key2 => $item3)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{$key1}}</td>

                            <td>{{$item3['blok']}}</td>
                            <td>{{$item3['sample']}}</td>
                            <td>{{$item3['petugas']}}</td>
                            <td>{{$item3['ancak_pemanen']}}</td>
                            <td colspan="2">{{$item3['brtp']}}</td>
                            <td colspan="2">{{$item3['brtk']}}</td>
                            <td colspan="2">{{$item3['brtgl']}}</td>
                            <td colspan="2">{{$item3['bhts']}}</td>
                            <td colspan="2">{{$item3['bhtm1']}}</td>
                            <td colspan="2">{{$item3['bhtm2']}}</td>
                            <td colspan="2">{{$item3['bhtm3']}}</td>
                            <td>{{$item3['ps']}}</td>

                            <td>{{$item3['status_panen']}}</td>
                            <td>{{$item3['kemandoran']}}</td>
                            <td>{{$item3['pokok_kuning']}}</td>
                            <td>{{$item3['piringan_semak']}}</td>
                            <td>{{$item3['underpruning']}}</td>
                            <td>{{$item3['overpruning']}}</td>
                        </tr>
                        @endforeach
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            @endif


            <br>

        </div>
    </section>
    @else

    <script>
        // Display an alert message and close the tab after 3 seconds
        setTimeout(function() {
            alert('Tidak ada Data di Temukan. Halaman akan ditutup.');
            window.close();
        }, 1000);
    </script>
    @endif


    <div class="content">
        <div class="row">
            @if(!empty($img))
            <h1 class="text-center">Foto Temuan Ancak</h1>
            @foreach ($img as $item)
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        Mutu ANcak
                    </div>
                    <div class="card-body">
                        @foreach ($item['foto'] as $foto)
                        <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/{{ $foto }}" class="img-fluid zoom popup_image" alt="" data-title="{{ $item['title'] }}" onclick="openModal('{{ $foto }}', '{{ $item['title'] }}')">
                        @endforeach
                        <p class="text-center mt-3" style="font-weight: bold">Mt.Ancak -{{ $item['title'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            @endif

            @if(!empty($imgBuah))
            <h1 class="text-center">Foto Temuan Buah</h1>
            @foreach ($imgBuah as $itemx)
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        Mutu buah
                    </div>
                    <div class="card-body">
                        @foreach ($itemx['foto'] as $foto)
                        <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mb/{{ $foto }}" class="img-fluid zoom popup_image" alt="" data-title="{{ $itemx['title'] }}" onclick="modalBuah('{{ $foto }}', '{{ $itemx['title'] }}')">
                        @endforeach
                        <input type="hidden" value="{{ $itemx['title'] }}" id="titleImg">
                        <p class="text-center mt-3" style="font-weight: bold">Mt.Buah -{{ $itemx['title'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            @endif

            @if(!empty($imgTrans))
            <h1 class="text-center">Foto Temuan Transport</h1>
            @foreach ($imgTrans as $items)
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        Mutu transport
                    </div>
                    <div class="card-body">
                        @foreach ($items['foto'] as $foto)
                        <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mt/{{ $foto }}" class="img-fluid zoom popup_image" alt="" data-title="{{ $items['title'] }}" onclick="modalTrans('{{ $foto }}', '{{ $items['title'] }}')">
                        @endforeach
                        <input type="hidden" value="{{ $items['title'] }}" id="titleImg">
                        <p class="text-center mt-3" style="font-weight: bold">Mt.Trans -{{ $items['title'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            @endif
        </div>


        <!-- map// -->
        <div class="card p-4">
            <h4 class="text-center mt-2" style="font-weight: bold">Tracking Plot Sidak TPH - {{$est}} {{$afd}}</h4>
            <hr>
            <div id="map" style="height:650px"></div>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="fullImg">
    </div>
    <div id="myModalbuah" class="modal">
        <span class="close" id="tutup">&times;</span>
        <img class="modal-content" id="buah">
    </div>
    <div id="myModalTrans" class="modal">
        <span class="close" id="tutups">&times;</span>
        <img class="modal-content" id="trans">
    </div>


</div>


@include('layout/footer')
<script type="text/javascript" src="http://w2ui.com/src/w2ui-1.4.2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script>
    $(document).ready(function() {
        $('#listSidakTPH').DataTable();

    });

    function openModal(src, title) {
        var modal = document.getElementById("myModal");
        var modalImg = document.getElementById("fullImg");
        var modalTitle = document.getElementById("modalTitle");
        modal.style.display = "block";
        modalImg.src = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/" + src;
        modalTitle.innerHTML = "Mt.Ancak - " + title;
    }

    function modalBuah(src, title) {
        var modal = document.getElementById("myModalbuah");
        var modalImg = document.getElementById("buah");
        var modalTitle = document.getElementById("modalTitle");
        modal.style.display = "block";
        modalImg.src = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mb/" + src;
        modalTitle.innerHTML = "Mt.Ancak - " + title;
    }

    function modalTrans(src, title) {
        var modal = document.getElementById("myModalTrans");
        var modalImg = document.getElementById("trans");
        var modalTitle = document.getElementById("modalTitle");
        modal.style.display = "block";
        modalImg.src = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mt/" + src;
        modalTitle.innerHTML = "Mt.Ancak - " + title;
    }
    var span = document.getElementsByClassName("close")[0];
    span.onclick = function() {
        var modal = document.getElementById("myModal");
        var modalBH = document.getElementById("myModalbuah");
        modal.style.display = "none";
        modalBH.style.display = "none";
    }
    var spanx = document.getElementById("tutup");
    spanx.onclick = function() {

        var modalBH = document.getElementById("myModalbuah");

        modalBH.style.display = "none";
    }
    var spanxs = document.getElementById("tutups");
    spanxs.onclick = function() {

        var modalBH = document.getElementById("myModalTrans");

        modalBH.style.display = "none";
    }
    var estate = <?php echo json_encode($estate_plot); ?>;

    var est = estate['plot'];

    console.log(est);


    // console.log(estate['plot']);
    // est = estate['plot']
    // console.log(est);
    var polygonCoords = [
        [-2.26562, 111.66802],
        [-2.27228, 111.670349],
        [-2.271737, 111.675129],
        [-2.276753, 111.679529],
        [-2.292791, 111.687504],
        [-2.322335, 111.685028],
        [-2.321748, 111.67113],
        [-2.329838, 111.666612],
        [-2.327989, 111.664716],
        [-2.331071, 111.660335],
        [-2.322796, 111.662824],
        [-2.3213, 111.651554],
        [-2.313256, 111.651602],
        [-2.316936, 111.650382],
        [-2.313211, 111.647746],
        [-2.312823, 111.63853],
        [-2.315368, 111.635965],
        [-2.321183, 111.640446],
        [-2.324125, 111.639729],
        [-2.324072, 111.634154],
        [-2.324576, 111.637126],
        [-2.328521, 111.6374],
        [-2.328079, 111.630689],
        [-2.326441, 111.634823],
        [-2.325196, 111.629312],
        [-2.322503, 111.632876],
        [-2.322511, 111.624014],
        [-2.289927, 111.624647],
        [-2.29193, 111.636875],
        [-2.287489, 111.653656],
        [-2.265301, 111.653358]
    ];

    console.log(polygonCoords);

    var map = L.map('map').fitBounds(polygonCoords, 13);

    var googleSat = L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);

    var polygon = L.polygon(polygonCoords).addTo(map);
</script>