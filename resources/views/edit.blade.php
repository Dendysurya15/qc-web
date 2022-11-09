@extends('template')
<div class="container">


	<form action="/update" method="post">
		@csrf
		<input type="hidden" name="id" value="{{ $pekerja->id }}"> <br/>
		<b>Nama</b><br><input type="text" required="required" name="nama" value="{{ $pekerja->nama }}"> <br/>
		<b>Jabatan</b><br><input type="text" required="required" name="jabatan" value="{{ $pekerja->jabatan }}"> <br/>
	    <b>Unit</b><br><input type="number" required="required" name="unit" value="{{ $pekerja->unit }}"> 
        <br/>
        <a class="btn btn-light mt-3" href="/index" role="button ">kembali</a>
		<input type="submit" class="btn btn-danger mt-3" value="Simpan Data">
	</form>
</div>       

		