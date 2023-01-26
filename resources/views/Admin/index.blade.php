<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sidak TPH</title>
    <link rel="stylesheet" href="{{ asset('css/loginadmin.css') }}">


<body>

    <div class="container-lg">

        <div class="login-box">


            {{-- <h2>Login</h2> --}}
            <div class="logo-srs">
                <img src="{{ asset('img/logo-SSS.png') }}" style="height: 100%;width:50%">
            </div>
            <div class="text-center mt-4">
                <p class="text-secondary text-center" style="margin:0 0 30px;
        font-style: normal;
        font-size: 14px;
        font-family: Arial, Helvetica, sans-serif;
        font-weight: 600 ; color: #1e1e1f">
                    Silakan masukkan Nama atau Email dan Password yang ada miliki untuk mengakses portal <span
                        style="color: #4CAF50">Sidak TPH</span>
                </p>
            </div>

            <form class="text-center mt-4" action="{{ route('login') }}" method="post">
                @csrf
                <div class="user-box">
                    <input type="text" name="email_or_nama_lengkap" required id="email_or_nama_lengkap" autofocus
                        value="{{old('email_or_nama_lengkap')}}">
                    <label for="email_or_nama_lengkap">Email / Nama</label>
                    @error('error')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="user-box">
                    <input type="password" name="password" id="password" required>
                    <label for="password">Password</label>
                    @error('error')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="logo-srs">
                    <input type="submit" class="tombol" value="Submit" style="height: 100%;width:100%;
                background-color: #013C5E;
                font-size: 20px;
                color:  #ffffff;
                font-style: normal;
        font-family: Arial, Helvetica, sans-serif;
        font-weight: 600 ;">
                </div>
                <div class="logo-srs">
                    <img src="{{ asset('img/logo-srs.png') }}" style="height: 80%;width:15%">
                </div>
            </form>

        </div>


    </div>
    {{-- <footer class="main-footer">
        <strong>Copyright © 2021-2026 <a href="https://srs-ssms.com">SRS-SSMS.COM</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.0.5
        </div>
    </footer> --}}
    @stack('scripts')
    <script src="{{ asset('js/js_tabel/jquery-3.5.1.js') }}"></script>
    <script src="{{ asset('js/js_tabel/jquery.dataTables.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</body>

</html>