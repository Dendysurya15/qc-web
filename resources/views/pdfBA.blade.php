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

    .form-inline {
        display: flex;
        align-items: center;
    }
</style>

<div class="content-wrapper">
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
                    <input class="form-control" value="{{ date('Y-m') }}" type="date" name="date" id="inputDate">
                </div>
                <button type="button" class="ml-2">Show</button>
            </form>
            <div class="afd"> ESTATE/ AFD : {{$est}}-{{$afd}}</div>
            <div class="afd">TANGGAL </div>
        </div>

    </div>


    <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
        <div class="Wraping">
            <table class="my-table">
                <thead>
                    <tr>
                        <th rowspan="4">Status Panen (H+…)</th>
                        <th colspan="8">Data Blok Sample</th>
                        <th colspan="13">MUTU ANCAK (MA)</th>
                        <th colspan="5">MUTU TRANSPOT (MT)</th>
                    </tr>
                    <tr>
                        <th rowspan="3">Nomor Blok</th>
                        <th rowspan="3">Luas Blok</th>
                        <th rowspan="3">SPH</th>
                        <th rowspan="3">Jumlah Pokok Sampel</th>
                        <th rowspan="3">Luas Sampel (Ha)</th>
                        <th rowspan="3">Persen Sampel (%)</th>
                        <th rowspan="3">Jumlah Janjang Panen</th>
                        <th rowspan="3">AKP Realisasi</th>

                    </tr>
                    <tr>
                        <th colspan="5">Brondolan Tinggal</th>

                        <th colspan="6">Buah Tinggal</th>
                        <th colspan="2">Palepah Sengklek</th>
                        <th rowspan="3">TPH Sample</th>
                        <th colspan="2">Brondolan Tingal</th>
                        <th colspan="2">Buah TInggal</th>

                    </tr>
                    <tr>
                        <!-- brondolan tinggal -->
                        <th>P</th>
                        <th>K</th>
                        <th>GL</th>
                        <th>Total</th>
                        <th>Total Butir/Jjg</th>
                        <!-- buah tinggal -->
                        <th>S</th>
                        <th>M1</th>
                        <th>M2</th>
                        <th>M3</th>
                        <th>Total</th>
                        <th>Persen (%)</th>
                        <!-- palepah -->
                        <th>Pokok</th>
                        <th> Persen (%)</th>
                        <!-- Transport -->
                        <th> Persen (%)</th>
                        <th> Persen (%)</th>
                        <th> Persen (%)</th>
                        <th> Persen (%)</th>
                    </tr>

                </thead>
                <tbody id="tab1">
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
        <div class="Wraping">
            <table class="my-table">
                <thead>
                    <tr>

                        <th colspan="16">Mutu Buah</th>

                    </tr>
                    <tr>
                        <th rowspan="2">Nomor Blok</th>
                        <th rowspan="3">Total Janjang Sample</th>
                        <th colspan="2">Mentah</th>
                        <th colspan="2">Matang</th>
                        <th colspan="2">Lewat matang</th>
                        <th colspan="2">Janjang Kosong</th>
                        <th colspan="2">Abnormal</th>
                        <th colspan="2">Tidak Standar Vcut</th>
                        <th colspan="2">Alas Brondol</th>
                    </tr>
                    <tr>
                        <th>jjg</th>
                        <th>%</th>
                        <th>jjg</th>
                        <th>%</th>
                        <th>jjg</th>
                        <th>%</th>
                        <th>jjg</th>
                        <th>%</th>
                        <th>jjg</th>
                        <th>%</th>
                        <th>jjg</th>
                        <th>%</th>
                        <th>Ya</th>
                        <th>%</th>
                    </tr>


                </thead>
                <tbody id="tab2">
                </tbody>
            </table>
        </div>
    </div>

</div>




@include('layout/footer')

<script>
    function Show() {
        $('#tab1').empty()
        $('#tab2').empty()


        var Tanggal = document.getElementById('inputDate').value;
        var est = document.getElementById('est').value;
        var afd = document.getElementById('afd').value;
        var _token = $('input[name="_token"]').val();
        // console.log('Tanggal:', Tanggal);
        // console.log('est:', est);
        console.log(Tanggal);

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
                var parseResult = JSON.parse(result)
                var mutuAncak = Object.entries(parseResult['mutuAncak'])
                // console.log(listBtTph)
                var mutuBuah = Object.entries(parseResult['mutuBuah'])
                var mutuTransport = Object.entries(parseResult['mutuTransport'])

                console.log(mutuAncak);
                // console.log(mutuBuah);
                // console.log(mutuTransport);


                var tbody1 = document.getElementById('tab1');
                var mutuAncak1 = mutuAncak

                function createTableCell(value) {
                    const cell = document.createElement('td');
                    cell.innerText = value;
                    return cell;
                }

                function createTableRow(items) {
                    const row = document.createElement('tr');
                    items.forEach(item => row.appendChild(createTableCell(item)));
                    return row;
                }

                mutuAncak1.forEach(element => {
                    const items = [
                        element[1].status,
                        element[0],
                        element[1].luas_blok,
                        element[1].SPH,
                        element[1].jum_pok,
                        element[1].luas_ha,
                        element[1].persen_samp,
                        element[1].jum_pan,
                        element[1].akp,
                        element[1].p,
                        element[1].k,
                        element[1].gl,
                        element[1].toBT,
                        element[1].brd_jjg,
                        element[1].s,
                        element[1].m1,
                        element[1].m2,
                        element[1].m3,
                        element[1].toBH,
                        element[1].buah_jjg,
                        element[1].ps,
                        element[1].ps_sen
                    ];

                    const row = createTableRow(items);
                    tbody1.appendChild(row);
                });

            }
        });
    }

    document.querySelector('button[type="button"]').addEventListener('click', Show);
</script>