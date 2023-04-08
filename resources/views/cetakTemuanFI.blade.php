<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Document</title>

</head>
<style>
    table.table-bordered>thead>tr>th {
        border: 1px solid rgb(0, 0, 0);
    }

    table.table-bordered>tbody>tr>td {
        border: 1px solid rgb(0, 0, 0);
    }

    table.table-active>thead>tr>th {
        border: 1px solid rgb(0, 0, 0);
    }

    table.table-active>tbody>tr>td {
        border: 1px solid rgb(0, 0, 0);
    }

    table.table-primary>thead>tr>th {
        border: 1px solid rgb(0, 0, 0);
    }

    table.table-primary>tbody>tr>td {
        border: 1px solid rgb(0, 0, 0);
    }

    table.table-bordered>tbody>tr>th {
        border: 1px solid rgb(0, 0, 0);
    }

    table.table-warning>thead>tr>th {
        border: 1px solid rgb(0, 0, 0);
    }

    table.table-warning>tbody>tr>td {
        border: 1px solid rgb(0, 0, 0);
    }

    body {
        font-size: 15px;
    }
</style>

<body>
    <table class="table table-bordered text-center">
        <thead>
            <tr bgcolor="#e0ecf4">
                <th style="font-size: 20px; font-weight: bold;">PEMERIKSAAN KUALITAS PANEN</th>
            </tr>
        </thead>
    </table>
    <table class="table table-bordered text-center" style="width: 15%; float:right;">
        <thead>
            <tr>

                <th>TANGGAL PEMERIKSAAN : </th>

            </tr>
        </thead>
    </table>
    <table class="table table-bordered text-center" style="width: 5%; float:right; margin-right: 0.5%;">
        <thead>
            <tr>
                @if (isset($dataResult[0][$id]['datetime']))
                <th>VISIT : {{ $id }}</th>
                @endif
            </tr>
        </thead>
    </table><br><br><br>

    <table class="table table-bordered text-center">
        <thead>
            <tr bgcolor="#e8ecdc">
                <th class="align-middle" rowspan="2">EST</th>
                <th class="align-middle" rowspan="2">AFD</th>
                <th class="align-middle" rowspan="2">ISSUE</th>
                <th colspan="4">FOTO</th>
                <th class="align-middle" rowspan="2">STATUS</th>
            </tr>
            <tr bgcolor="#e8ecdc">
                <th colspan="2">BEFORE</th>
                <th colspan="2">AFTER</th>
            </tr>
        </thead>
        <tbody>
            @php
            $tuntas = array();
            $no_tuntas = array();
            @endphp

            @foreach ($dataResult as $key => $item)
            @if (isset($item[$id]))
            <tr>
                <td class="align-middle" width="5%">{{ $item[$id]['estate'] }}</td>
                <td class="align-middle" width="5%">{{ $item[$id]['afdeling'] }}</td>
                <td class="align-middle" width="20%">
                    @if (strpos($item[$id]['foto_temuan1'], 'IMA') !== false)
                    @php
                    $komen_ma = explode(";",$item[$id]['komentar']);
                    @endphp

                    @foreach ($komen_ma as $items)
                    {{ $items }} <br>
                    @endforeach
                    @else
                    {{ $item[$id]['komentar'] }}
                    @endif
                </td>


                <td class="align-middle" width="15%"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mt/{{$item[$id]['foto_temuan']}}" style="weight:150pt;height:150pt"></td>
                <td class="align-middle" width="15%">&nbsp;</td>

                <td class="align-middle" width="15%"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mb/{{$item[$id]['foto_temuan']}}" style="weight:150pt;height:150pt"></td>
                <td class="align-middle" width="15%">&nbsp;</td>


                <td class="align-middle" width="15%">
                    @if (!empty($foto_temuan_ma[0]))
                    <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/{{$foto_temuan_ma[0]}}" style="weight:150pt;height:150pt">
                    @endif
                </td>

                <td class="align-middle" width="15%">
                    @if (!empty($foto_temuan_ma1[0]))
                    <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/{{$foto_temuan_ma[0]}}" style="weight:150pt;height:150pt">
                    @endif
                </td>
                @else
                <td class="align-middle" width="15%">&nbsp;</td>
                <td class="align-middle" width="15%">&nbsp;</td>
                @endif

                @if (isset($item[$id]['foto_fu']) && strpos($item[$id]['foto_fu'], 'IMT') !== false)
                <td class="align-middle" width="15%"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mt/{{$item[$id]['foto_fu']}}" style="weight:150pt;height:150pt"></td>
                <td class="align-middle" width="15%">&nbsp;</td>
                @elseif (isset($item[$id]['foto_fu']) && strpos($item[$id]['foto_fu'], 'IMA') !== false)
                @php
                $foto_fu_ma = explode(";",$item[$id]['foto_fu']);
                $foto_fu_ma1 = explode(";",$item[$id]['foto_fu1']);
                @endphp

                <td class="align-middle" width="15%">
                    @if (!empty($foto_fu_ma[0]))
                    <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/{{$foto_fu_ma[0]}}" style="weight:150pt;height:150pt">
                    @endif
                </td>

                <td class="align-middle" width="15%">
                    @if (!empty($foto_fu_ma1[0]))
                    <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/{{$foto_fu_ma1[0]}}" style="weight:150pt;height:150pt">
                    @endif
                </td>
                @else
                <td class="align-middle" width="15%">&nbsp;</td>
                <td class="align-middle" width="15%">&nbsp;</td>
                @endif

                @if (!empty($item[$id]['foto_temuan']) && !empty($item[$id]['foto_fu']) && $item[$id]['foto_fu'] != 'MB')
                @php array_push($tuntas, '1'); @endphp
                <td class="align-middle" bgcolor="#00ff00" style="color: black;" width="10%">
                    TUNTAS
                </td>
                @elseif (!empty($item[$id]['foto_temuan']) && $item[$id]['foto_fu'] == 'MB')
                <td class="align-middle" bgcolor="yellow" style="color: black;" width="10%">
                    BERKELANJUTAN
                </td>
                @else
                @php array_push($no_tuntas, '1'); @endphp
                <td class="align-middle" bgcolor="red" style="color: black;" width="10%">
                    BELUM TUNTAS
                </td>
                @endif
            </tr>
            @endif
            @endforeach

            @php
            $tun = count($tuntas);
            $notun = count($no_tuntas);
            $tot = $tun + $notun;
            @endphp
            <tr bgcolor="#e8ecdc">
                <td colspan="3" class="align-middle">&nbsp;</td>
                <td colspan="2" class="align-middle" style="font-weight: bold">TUNTAS</td>
                <td colspan="2" class="align-middle">{{ $tun }}</td>
                <td class="align-middle">{{ count_percent($tun,
                    $tot) }}%</td>
            </tr>
            <tr bgcolor="#e8ecdc">
                <td colspan="3" class="align-middle">&nbsp;</td>
                <td colspan="2" class="align-middle" style="font-weight: bold">BELUM TUNTAS</td>
                <td colspan="2" class="align-middle">{{ $notun }}</td>
                <td class="align-middle">{{ count_percent($notun,
                    $tot) }}%</td>
            </tr>
        </tbody>
    </table>
</body>

</html>