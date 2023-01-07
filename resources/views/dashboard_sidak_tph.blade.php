@include('layout/header')
<style>
    table.dataTable thead tr th {
        border: 1px solid black
    }

    .dataTables_scrollBody thead tr[role="row"] {
        visibility: collapse !important;
    }

    .dataTables_scrollHeaderInner table {
        margin-bottom: 0px !important;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid pt-3">
            <h4 class="text-center pt-3 pb-3" style="border: 1px solid black">SIDAK PEMERIKSAAN TPH REGIONAL - I</h4>
        </div>
        <div class="d-flex justify-content-end pt-3 pb-3" style="border: 1px solid red">
            <div class="row">

            </div>
            <div class="col-2">
                <select name="week" class="form-control">
                    <option>Pilih Minggu Ke -</option>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                </select>
            </div>

            <div class="col-2">
                <input class="form-control" value="" type="month" name="tgl" id="inputDate">
            </div>

            <button class="btn btn-primary">Show</button>

            <button class="btn btn-primary">PDF</button>
        </div>
    </section>
</div>
@include('layout/footer')