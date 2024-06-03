<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>keuangan</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <!-- Styles -->

    <style>
        html,
        body {
            /* background-color: blueviolet; */
            background-image: url('/img/keuangan.jpg');
            /* Perbaikan path */
            background-size: cover;
            /* Menyesuaikan ukuran gambar dengan layar */
            background-position: center;
            /* Posisi gambar di tengah */
            color: #fff;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        /* Efek overlay */
        body::before {
            content: "";
            background: rgba(0, 0, 0, 0.3);
            /* Warna gelap dengan opasitas lebih tinggi */
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {

            font-size: 40px;
            /* Menghapus properti font-weight: bold; */
            color: floralwhite
                /* Warna teks dengan opasitas lebih tinggi */
        }

        .title2 {
            font-size: 30px;
            /* Menghapus properti font-weight: bold; */
            color: rgba(255, 255, 255, 0.9);
            /* Warna teks dengan opasitas lebih tinggi */
        }

        .links>a {
            color: #fff;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
            <div class="top-right links">
                @auth
                    <a href="{{ url('/home') }}">Home</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                @endauth
            </div>
        @endif
        <div class="content">
            <div class="title">
                <b>Aplikasi Keuangan</b>
                <br>
            </div>
        </div>


        <div class="title2 m-b-md">

        </div>
    </div>
    </div>
</body>

</html>
