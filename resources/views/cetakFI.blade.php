<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
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
                <th>BULAN : 02/2023</th>
            </tr>
        </thead>
    </table><br><br><br>

    <table class="table table-bordered text-center">
        <thead>
            <tr bgcolor="#e8ecdc">
                <th class="align-middle" rowspan="2" style="padding: 1px;">EST</th>
                <th class="align-middle" rowspan="2" style="padding: 1px;">AFD</th>
                <th class="align-middle" rowspan="2" style="padding: 10px;">ISSUE</th>
                <th colspan="2">FOTO</th>
                <th class="align-middle" rowspan="2" style="padding: 1px;">STATUS</th>
            </tr>
            <tr bgcolor="#e8ecdc">
                <th style="padding: 1px;">BEFORE</th>
                <th>AFTER</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataMTFI as $key => $item)
            <tr>
                <td class="align-middle">{{ $item[0]['estate'] }}</td>
                <td class="align-middle">{{ $item[0]['afdeling'] }}</td>
                <td class="align-middle">{{ $item[0]['komentar'] }}</td>
                <td class="align-middle"><img
                        src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mt/{{$item[0]['foto_temuan']}}"
                        style="weight:150pt;height:150pt"></td>
                <td class="align-middle"><img
                        src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mt/{{$item[0]['foto_fu']}}"
                        style="weight:150pt;height:150pt"></td>
                @if (!empty($item[0]['foto_temuan']) && !empty($item[0]['foto_fu']))
                <td class="align-middle" bgcolor="#00ff00" style="color: black;">
                    TUNTAS
                </td>
                @else
                <td class="align-middle" bgcolor="red" style="color: black;">
                    BELUM TUNTAS
                </td>
                @endif
            </tr>
            @endforeach

            @foreach ($dataMAFI as $key => $item)
            <tr>
                <td class="align-middle">{{ $item[0]['estate'] }}</td>
                <td class="align-middle">{{ $item[0]['afdeling'] }}</td>
                <td class="align-middle">{{ $item[0]['komentar'] }}</td>
                <td class="align-middle"><img
                        src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mt/{{$item[0]['foto_temuan']}}"
                        style="weight:150pt;height:150pt"></td>
                <td class="align-middle"><img
                        src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mt/{{$item[0]['foto_fu']}}"
                        style="weight:150pt;height:150pt"></td>
                @if (!empty($item[0]['foto_temuan']) && !empty($item[0]['foto_fu']))
                <td class="align-middle" bgcolor="#00ff00" style="color: black;">
                    TUNTAS
                </td>
                @else
                <td class="align-middle" bgcolor="red" style="color: black;">
                    BELUM TUNTAS
                </td>
                @endif
            </tr>
            @endforeach

            @foreach ($dataTotal as $key => $item)
            <tr bgcolor="#e8ecdc">
                <td colspan="3" class="align-middle">&nbsp;</td>
                <td class="align-middle">TUNTAS</td>
                <td class="align-middle">{{ $item['tuntas'] }}</td>
                <td class="align-middle">{{ count_percent($item['tuntas'],
                    $item['total_temuan']) }}%</td>
            </tr>
            <tr bgcolor="#e8ecdc">
                <td colspan="3" class="align-middle">&nbsp;</td>
                <td class="align-middle">BELUM TUNTAS</td>
                <td class="align-middle">{{ $item['no_tuntas'] }}</td>
                <td class="align-middle">{{ count_percent($item['no_tuntas'],
                    $item['total_temuan']) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>