<!DOCTYPE html>
<html lang="en">

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
            alert('Data tidak ada. Halaman akan ditutup.');
            window.close();
        }, 1000);
    </script>
    @endif

</div>
@include('layout/footer')
<script type="text/javascript" src="http://w2ui.com/src/w2ui-1.4.2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#listSidakTPH').DataTable();

    });
</script>