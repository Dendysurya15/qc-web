<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script
    src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></>
<link rel="stylesheet"
    href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <title>Bootstrap demo</title>
  </head>
  <body>

    <div class="col-8 offset-2">
        <table class="table table-warning" border="1">
            <tr>
                <th class="table-dark">Id</th>
                <th class="table-dark">Nama</th>
                <th class="table-dark">Jabatan</th>
            <th class="table-dark">Unit</th>
            <th class="table-dark">Opsi</th>
            </tr>
            <a class="btn btn-primary" href="/tambah" role="button ">tambah</a>
        @foreach ($pekerja as $p)
        <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->jabatan }}</td>
                <td>{{ $p->unit }}</td>
                <td>
                    <a class="btn btn-primary" href="/edit/{{ $p->id}}" role="button">edit</a>
                    <a class="btn btn-primary" href="/hapus/{{ $p->id}}" role="button">hapus</a>
                </td>
            </tr> 
            @endforeach
        </table>
    </div>
    </body>
    </html>
    