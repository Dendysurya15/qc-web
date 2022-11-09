@extends('template')
<div class="container">
	<form action="/store" method="post">
		@csrf
		<b>Nama</b><br><input type="text" required="required" name="nama"> <br/>
		<b>Jabatan</b><br><input type="text" required="required" name="jabatan"> <br/>
	    <b>Unit</b><br><input type="number" required="required" name="unit"> 
        <br/>
        <a class="btn btn-light mt-3" href="/index" role="button ">kembali</a>
		<input type="submit" class="btn btn-danger mt-3" value="Tambah data">
	</form>
</div>       