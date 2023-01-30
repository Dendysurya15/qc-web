@include('layout/header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

@if (session('user_name') == 'Dennis Irawan')
<div class="content-wrapper">
    @if(session('status'))
    <div class="mr-3 ml-3 alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mr-3 ml-3 alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <section class="content">
        <div class="container-fluid pt-3">
            <a href="{{ route('dashboardtph') }}" class="btn btn-dark"> <i
                    class="nav-icon fa-solid fa-arrow-left "></i></a>
            <div class="card mt-2">
                <div class="card-body">
                    <button class="btn btn-primary mb-2" style="float: right;" data-bs-toggle="modal"
                        data-bs-target="#tambahData">Tambah Data</button>
                    <table id="listAsisten" class="table-striped text-center" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Estate</th>
                                <th>Afdeling</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asisten as $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $value->nama }}</td>
                                <td>{{ $value->est }}</td>
                                <td>{{ $value->afd }}</td>
                                <td style="display: inline-flex">
                                    <button class="btn btn-success mr-2" data-bs-toggle="modal"
                                        data-bs-target="#perbarui{{$value->id}}"><i
                                            class="nav-icon fa-solid fa-edit"></i></button>
                                    <form action="{{ route('hapusAsisten') }}" method="POST">{{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{ $value->id }}"><button type="submit"
                                            class="btn btn-danger" onclick="return confirm('Yakin menghapus data?')"><i
                                                class="nav-icon fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="perbarui{{$value->id}}" tabindex="-1"
                                aria-labelledby="perbaruiDataLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="perbaruiDataLabel">Perbarui Data Asisten
                                            </h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('perbaruiAsisten') }}" method="POST">
                                                {{ csrf_field() }}
                                                <div class="mb-3">
                                                    <label for="name" class="col-form-label">Nama</label>
                                                    <input type="hidden" name="id" value="{{$value->id}}">
                                                    <input type="text" class="form-control" id="name" name="nama"
                                                        value="{{$value->nama}}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="message-text" class="col-form-label">Estate</label>
                                                    <select name="est" class="form-control" required>
                                                        <option value="" disabled selected>SILAKAN PILIH</option>
                                                        @foreach ($estate as $value1)
                                                        <option @if ($value->est == $value1->est) selected @endif
                                                            value="{{$value1->est}}">{{$value1->est}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="message-text" class="col-form-label">Afdeling</label>
                                                    <select name="afd" class="form-control" required>
                                                        <option value="" disabled selected>SILAKAN PILIH</option>
                                                        <option @if ($value->afd == 'EM') selected @endif value="EM">EM
                                                        </option>
                                                        @foreach ($afdeling as $value1)
                                                        <option @if ($value->afd == $value1->nama) selected @endif
                                                            value="{{$value1->nama}}">{{$value1->nama}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mt-2 mb-2" style="float: right">
                                                    <button type="submit" class="btn btn-primary"
                                                        onclick="return confirm('Yakin perbarui data?')">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="tambahData" tabindex="-1" aria-labelledby="tambahDataLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambahDataLabel">Tambah Data Asisten</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tambahAsisten') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label for="name" class="col-form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Estate</label>
                        <select name="est" class="form-control" required>
                            <option value="" disabled selected>SILAKAN PILIH</option>
                            @foreach ($estate as $value)
                            <option value="{{$value->est}}">{{$value->est}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Afdeling</label>
                        <select name="afd" class="form-control" required>
                            <option value="" disabled selected>SILAKAN PILIH</option>
                            <option value="EM">EM</option>
                            @foreach ($afdeling as $value)
                            <option value="{{$value->nama}}">{{$value->nama}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-2 mb-2" style="float: right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@include('layout/footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('#listAsisten').DataTable();
    });
</script>