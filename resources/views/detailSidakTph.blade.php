<link rel="stylesheet" type="text/css" href="http://w2ui.com/src/w2ui-1.4.2.min.css" />

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
</style>


<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid pt-3">
            <div class="card p-4">
                <h4 class="text-center mt-2" style="font-weight: bold">Tabel Sidak TPH - {{$est}} {{$afd}}</h4>
                <hr>
                <?php
                if (session('user_name') == 'Dennis Irawan') {
                ?>
                    <table id="listSidakTPH" class="text-center" style="width:100%">
                        {{ csrf_field() }}
                        <thead>
                            <tr>
                                <th rowspan="3">Blok</th>
                                <th rowspan="3">No. TPH</th>
                                <th rowspan="3">Luas (Ha)</th>
                                <th colspan="3">Brondolan Tinggal</th>
                                {{-- <th>Jumlah</th>
                            <th>Jumlah </th> --}}
                                <th rowspan="2">Karung Isi Brondolan </th>
                                <th rowspan="2">Buah Tinggal di TPH </th>
                                <th rowspan="2">Restan Tidak Dilaporkan </th>
                                <th colspan="2" rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                {{-- <th>Blok</th>
                            <th>No. TPH</th>
                            <th>Luas (Ha)</th> --}}
                                <th>Di TPH</th>
                                <th>Di JALAN</th>
                                <th>Di TPH </th>
                                {{-- <th>Jumlah </th> --}}
                                {{-- <th>Janjang </th> --}}
                                {{-- <th>Janjang </th> --}}
                            </tr>
                            <tr>
                                {{-- <th>Blok</th>
                            <th>No. TPH</th>
                            <th>Luas (Ha)</th> --}}
                                <th>Jumlah</th>
                                <th>Jumlah</th>
                                <th>Jumlah </th>
                                <th>Jumlah </th>
                                <th>Janjang </th>
                                <th>Janjang </th>
                                <form id="hapusDetailSidakForm" action="{{ route('hapusDetailSidak') }}" method="POST">
                                    {{ csrf_field() }}
                                    <th colspan="2">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin menghapus data terpilih?')">
                                            <i class="nav-icon fa-solid fa-trash"></i>
                                        </button>
                                    </th>
                            </tr>
                        </thead>
                        <tbody>

                            <input type="hidden" name="est" value="{{ $data[0]->est }}">
                            <input type="hidden" name="afd" value="{{ $data[0]->afd }}">
                            <input type="hidden" name="start" value="{{ $start}}">
                            <input type="hidden" name="last" value="{{ $last }}">
                            @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->blok }}</td>
                                <td>{{ $item->no_tph }}</td>
                                <td>{{ $item->luas }}</td>
                                <td>{{ $item->bt_tph }}</td>
                                <td>{{ $item->bt_jalan }}</td>
                                <td>{{ $item->bt_bin }}</td>
                                <td>{{ $item->jum_karung }}</td>
                                <td>{{ $item->buah_tinggal }}</td>
                                <td>{{ $item->restan_unreported }}</td>
                                <td>
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}">
                                </td>
                            </tr>
                            @endforeach

                            </form>

                        </tbody>
                    </table>
                <?php
                } else {
                ?>
                    <table id="sidakBiasa" class="text-center" style="width:100%">
                        {{ csrf_field() }}
                        <thead>
                            <tr>
                                <th rowspan="3">Blok</th>
                                <th rowspan="3">No. TPH</th>
                                <th rowspan="3">Luas (Ha)</th>
                                <th colspan="3">Brondolan Tinggal</th>
                                {{-- <th>Jumlah</th>
                            <th>Jumlah </th> --}}
                                <th rowspan="2">Karung Isi Brondolan </th>
                                <th rowspan="2">Buah Tinggal di TPH </th>
                                <th rowspan="2">Restan Tidak Dilaporkan </th>
                                <!-- <th colspan="2" rowspan="2">Aksi</th> -->
                            </tr>
                            <tr>
                                {{-- <th>Blok</th>
                            <th>No. TPH</th>
                            <th>Luas (Ha)</th> --}}
                                <th>Di TPH</th>
                                <th>Di JALAN</th>
                                <th>Di TPH </th>
                                {{-- <th>Jumlah </th> --}}
                                {{-- <th>Janjang </th> --}}
                                {{-- <th>Janjang </th> --}}
                            </tr>
                            <tr>
                                {{-- <th>Blok</th>
                            <th>No. TPH</th>
                            <th>Luas (Ha)</th> --}}
                                <th>Jumlah</th>
                                <th>Jumlah</th>
                                <th>Jumlah </th>
                                <th>Jumlah </th>
                                <th>Janjang </th>
                                <th>Janjang </th>
                                <!-- <form id="hapusDetailSidakForm" action="{{ route('hapusDetailSidak') }}" method="POST">
                                {{ csrf_field() }}
                                <th colspan="2">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin menghapus data terpilih?')">
                                        <i class="nav-icon fa-solid fa-trash"></i>
                                    </button>
                                </th> -->
                            </tr>
                        </thead>
                        <tbody>

                            <input type="hidden" name="est" value="{{ $data[0]->est }}">
                            <input type="hidden" name="afd" value="{{ $data[0]->afd }}">
                            <input type="hidden" name="start" value="{{ $start}}">
                            <input type="hidden" name="last" value="{{ $last }}">
                            @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->blok }}</td>
                                <td>{{ $item->no_tph }}</td>
                                <td>{{ $item->luas }}</td>
                                <td>{{ $item->bt_tph }}</td>
                                <td>{{ $item->bt_jalan }}</td>
                                <td>{{ $item->bt_bin }}</td>
                                <td>{{ $item->jum_karung }}</td>
                                <td>{{ $item->buah_tinggal }}</td>
                                <td>{{ $item->restan_unreported }}</td>
                                <!-- <td>
                                <input type="checkbox" name="ids[]" value="{{ $item->id }}">
                            </td> -->
                            </tr>
                            @endforeach
                            <!-- 
                        </form> -->

                        </tbody>

                    </table>
                <?php
                }
                ?>


                <!-- //table biasa -->


            </div>
            <br>

            <div class="card p-3">

                <h4 class="text-center mt-2" style="font-weight: bold">FOTO TEMUAN</h4>
                <hr>
                <div class="row">
                    @foreach ($img as $item)
                    <div class="col-3">
                        @php
                        $test = $item['foto'];
                        $file = 'https://mobilepro.srs-ssms.com/storage/app/public/qc/sidak_tph/'.$test;
                        $file_headers = @get_headers($file);
                        @endphp
                        @if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found')
                        @else
                        <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/sidak_tph/{{$item['foto']}}" class="img-fluid  popup_image" alt="">
                        <input type="hidden" value="{{$item['title']}}" id="titleImg">
                        <p class="text-center mt-3" style="font-weight: bold">{{$item['title']}}</p>

                        @endif
                    </div>
                    {{-- @break --}}
                    @endforeach
                </div>
            </div>

            <div class="card p-4">
                <h4 class="text-center mt-2" style="font-weight: bold">Tracking Plot Sidak TPH - {{$est}} {{$afd}}</h4>
                <hr>
                <div id="map" style="height:800px"></div>
            </div>
        </div>
    </section>
</div>
@include('layout/footer')

<script type="text/javascript" src="http://w2ui.com/src/w2ui-1.4.2.min.js"></script>
<script>
    document.getElementById("hapusDetailSidakForm").addEventListener("submit", function() {
        var ids = [];
        var checkboxes = document.getElementsByName("ids[]");
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                ids.push(checkboxes[i].value);
            }
        }
        document.querySelector("input[name='ids']").value = ids.join(",");
    });

    // console.log(checkboxes);
    $(document).ready(function() {

        $(".popup_image").on('click', function() {

            var titleImg = document.getElementById('titleImg').value
            w2popup.open({
                title: titleImg,
                body: '<div class="w2ui-centered" ><img src="' + $(this).attr('src') + '" ></img></div>',
                width: 1280, // width of the popup
                height: 720 // height of the popup
            });
        });

    });
    $(document).ready(function() {
        $('#listSidakTPH').DataTable();
        $('#sidakBiasa').DataTable();
    });

    date = new Date().toISOString().slice(0, 10)

    var map = L.map('map').setView([-2.2745234, 111.61404248], 13);

    googleSat = L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);

    // const googleSat = L.tileLayer(
    //     "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
    // ).addTo(map);


    const params = new URLSearchParams(window.location.search)
    var paramArr = [];
    for (const param of params) {
        paramArr = param
    }

    var est = '<?php echo $est; ?>';
    var afd = '<?php echo $afd; ?>';
    var start = '<?php echo $start; ?>';
    var last = '<?php echo $last; ?>';

    var _token = $('input[name="_token"]').val();

    $.ajax({
        url: "{{ route('getPlotLine') }}",
        method: "post",
        data: {
            est: est,
            afd: afd,
            _token: _token,
            start: start,
            last: last
        },
        success: function(result) {

            var plot = JSON.parse(result);

            const plotResult = Object.entries(plot['plot']);
            const markerResult = Object.entries(plot['marker']);
            const blokResult = Object.entries(plot['blok']);
            // console.log(plotResult.length)
            // console.log(plotResult)

            // console.log(blokResult)
            drawPlot(plotResult)
            drawBlok(blokResult)


            //  for (let i = 0; i < markerResult.length; i++) {

            //     for (let j = 0; j < markerResult[i][1].length; j++) {
            //         let numberIcon = new L.Icon({
            //         iconUrl: "https://raw.githubusercontent.com/sheiun/leaflet-color-number-markers/main/dist/img/blue/marker-icon-2x-blue-" + j + ".png",
            //         shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
            //         iconSize: [25, 41],
            //         iconAnchor: [12, 41],
            //         popupAnchor: [1, -34],
            //         shadowSize: [41, 41],
            //     });
            //     L.marker(JSON.parse(markerResult[i][1][j]), {
            //         icon: numberIcon
            //     }).addTo(map);

            //     }

            // }

            // console.log(markerResult)
            for (let i = 0; i < markerResult.length; i++) {
                let numberIcon = new L.Icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41],
                });
                var popupOptions = {
                    className: "customPopup"
                };
                var template =
                    "<div> <span style='font-weight:bold'>Jam Sidak : </span>" + markerResult[i][1]['jam'] + "</div>" +
                    "<div> <span style='font-weight:bold'>Nomor TPH : </span>" + markerResult[i][1]['notph'] + "</div>" +
                    "<div ><span style='font-weight:bold'>Blok </span>: " + markerResult[i][1]['blok'] + "</div>";

                //   if (markerResult[i][1]['brondol_tinggal'] != 0){
                //     template +=   "<div ><span style='font-weight:bold'>Brondol Tinggal </span>: "+markerResult[i][1]['brondol_tinggal']+"</div>" ;
                //      numberIcon = new L.Icon({
                //                 iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                //                 shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                //             iconSize: [25, 41],
                //             iconAnchor: [12, 41],
                //             popupAnchor: [1, -34],
                //             shadowSize: [41, 41],
                //         });
                //   } 
                //   if (markerResult[i][1]['jum_karung'] != 0){
                //     template +=   "<div ><span style='font-weight:bold'>Karung Tinggal </span>: "+markerResult[i][1]['jum_karung']+"</div>" ;
                //      numberIcon = new L.Icon({
                //                 iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                //                 shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                //             iconSize: [25, 41],
                //             iconAnchor: [12, 41],
                //             popupAnchor: [1, -34],
                //             shadowSize: [41, 41],
                //         });
                //   }
                //   if (markerResult[i][1]['buah_tinggal'] != 0){
                //     template +=   "<div ><span style='font-weight:bold'>Buah Tinggal </span>: "+markerResult[i][1]['buah_tinggal']+"</div>" ;
                //      numberIcon = new L.Icon({
                //                 iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                //                 shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                //             iconSize: [25, 41],
                //             iconAnchor: [12, 41],
                //             popupAnchor: [1, -34],
                //             shadowSize: [41, 41],
                //         });
                //   }
                //   if (markerResult[i][1]['restan_unreported'] != 0){
                //     template +=   "<div ><span style='font-weight:bold'>Restan Tidak Dilaporkan </span>: "+markerResult[i][1]['restan_unreported']+"</div>" ;

                //     numberIcon = new L.Icon({
                //                 iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                //                 shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                //             iconSize: [25, 41],
                //             iconAnchor: [12, 41],
                //             popupAnchor: [1, -34],
                //             shadowSize: [41, 41],
                //         });
                //   }

                //         L.marker(JSON.parse(markerResult[i][1]['latln']), {
                //             icon: numberIcon
                //         }).addTo(map).bindPopup(template, popupOptions)
                //     .openPopup();

                //     //     // }

                // }
            }
        }
    })


    function drawPlot(plot) {

        var getLineStr = '{"type"'
        getLineStr += ":"
        getLineStr += '"FeatureCollection",'
        getLineStr += '"features"'
        getLineStr += ":"
        getLineStr += '['

        for (let i = 0; i < plot.length; i++) {
            getLineStr += '{"type"'
            getLineStr += ":"
            getLineStr += '"Feature",'
            getLineStr += '"properties"'
            getLineStr += ":"
            getLineStr += '{},'
            getLineStr += '"geometry"'
            getLineStr += ":"
            getLineStr += '{"coordinates"'
            getLineStr += ":"
            getLineStr += plot[i][1]
            getLineStr += ',"type"'
            getLineStr += ":"
            getLineStr += '"Point"'
            getLineStr += '}},'
        }

        getLineStr = getLineStr.substring(0, getLineStr.length - 1);
        getLineStr += ']}'

        var line2 = JSON.parse(getLineStr)

        var test = L.geoJSON(line2['features'], {
                // onEachFeature: function(feature, layer){
                //     layer.myTag = 'LineMarker'
                //     layer.addTo(map);
                // },
                style: function(feature) {
                    return {
                        weight: 2,
                        opacity: 1,
                        color: 'yellow',
                        fillOpacity: 0.7
                    };
                }
            })
            .addTo(map);

        // map.fitBounds(test.getBounds());

    }


    function drawBlok(blok) {
        var getPlotStr = '{"type"'
        getPlotStr += ":"
        getPlotStr += '"FeatureCollection",'
        getPlotStr += '"features"'
        getPlotStr += ":"
        getPlotStr += '['

        // console.log(blok)
        for (let i = 0; i < blok.length; i++) {
            getPlotStr += '{"type"'
            getPlotStr += ":"
            getPlotStr += '"Feature",'
            getPlotStr += '"properties"'
            getPlotStr += ":"
            getPlotStr += '{"blok"'
            getPlotStr += ":"
            getPlotStr += '"' + blok[i][1]['blok'] + '",'
            getPlotStr += '"estate"'
            getPlotStr += ":"
            getPlotStr += '"' + blok[i][1]['estate'] + '"'
            getPlotStr += '},'
            getPlotStr += '"geometry"'
            getPlotStr += ":"
            getPlotStr += '{"coordinates"'
            getPlotStr += ":"
            getPlotStr += '[['
            getPlotStr += blok[i][1]['latln']
            getPlotStr += ']],"type"'
            getPlotStr += ":"
            getPlotStr += '"Polygon"'
            getPlotStr += '}},'
        }
        getPlotStr = getPlotStr.substring(0, getPlotStr.length - 1);
        getPlotStr += ']}'


        var blok = JSON.parse(getPlotStr)

        var test = L.geoJSON(blok, {
                onEachFeature: function(feature, layer) {

                    layer.myTag = 'BlokMarker'
                    var label = L.marker(layer.getBounds().getCenter(), {
                        icon: L.divIcon({
                            className: 'label-bidang',
                            html: feature.properties.blok,
                            iconSize: [50, 10]
                        })
                    }).addTo(map);

                    layer.addTo(map);
                },
                style: function(feature) {
                    switch (feature.properties.afdeling) {
                        case 'OA':
                            return {
                                fillColor: "#ff1744",
                                    color: 'white',
                                    fillOpacity: 0.4,
                                    opacity: 0.4,
                            };
                        case 'OB':
                            return {
                                fillColor: "#d500f9",
                                    color: 'white',
                                    fillOpacity: 0.4,
                                    opacity: 0.4,
                            };
                        case 'OC':
                            return {
                                fillColor: "#ffa000",
                                    color: 'white',
                                    fillOpacity: 0.4,
                                    opacity: 0.4,
                            };
                        case 'OD':
                            return {
                                fillColor: "#00b0ff",
                                    color: 'white',
                                    fillOpacity: 0.4,
                                    opacity: 0.4,
                            };

                        case 'OE':
                            return {
                                fillColor: "#67D98A",
                                    color: 'white',
                                    fillOpacity: 0.4,
                                    opacity: 0.4,

                            };
                        case 'OF':
                            return {
                                fillColor: "#666666",
                                    color: 'white',
                                    fillOpacity: 0.4,
                                    opacity: 0.4,

                            };
                    }
                }
            })
            .addTo(map);
        map.fitBounds(test.getBounds());
    }
</script>