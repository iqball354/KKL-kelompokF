<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Student Online</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Font Inknut Antiqua -->
    <link href="https://fonts.googleapis.com/css2?family=Inknut+Antiqua:wght@600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('asset/logo-itsm.png') }}">
    <style>
        body {
            background: url('{{ asset("asset/tampilan-login.png") }}') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        .header-logos {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            gap: 15px;
            z-index: 10;
        }

        .header-logos img {
            height: 80px;
        }

        .title-top {
            font-family: 'Inknut Antiqua', serif;
            font-size: 34px;
            font-weight: 700;
            text-align: center;
            color: #111;
            margin-bottom: 25px;
        }

        .login-box {
            width: 380px;
            background: rgba(255, 255, 255, 0.92);
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 0 13px rgba(0, 0, 0, 0.22);
        }

        .login-title {
            font-family: 'Inknut Antiqua', serif;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .input-icon-right {
            background: #fff;
            border-left: 0;
            padding-right: 12px;
            border-radius: 0 6px 6px 0 !important;
        }

        .form-control {
            border-right: 0 !important;
            border-radius: 6px 0 0 6px !important;
        }

        .btn-purple {
            background: #5F6BEF;
            color: #fff;
            border: none;
            font-weight: 700;
            padding: 10px 0;
            font-size: 16px;
            border-radius: 8px;
        }

        .btn-purple:hover {
            background: #4C55D8;
        }
    </style>
</head>

<body>

    <div class="header-logos">
        <img src="{{ asset('asset/logo-kampus.png') }}">
    </div>

    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div>
            <div class="title-top">Student Online</div>

            <div class="login-box text-center">

                <div class="login-title">Login untuk memulai sesi</div>

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="m-0 p-0 ps-3 text-start">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <label class="form-label text-start w-100">Email</label>
                    <div class="input-group mb-3">
                        <input type="text" name="email" class="form-control" placeholder="Masukkan email" required>
                        <span class="input-group-text input-icon-right">
                            <i class="fa fa-user"></i>
                        </span>
                    </div>

                    <label class="form-label text-start w-100">Password</label>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        <span class="input-group-text input-icon-right">
                            <i class="fa fa-lock"></i>
                        </span>
                    </div>

                    <button type="submit" class="btn btn-purple w-100">
                        Login
                    </button>

                </form>

            </div>
        </div>
    </div>

</body>

</html>