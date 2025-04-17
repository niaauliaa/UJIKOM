<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('Assets/css/alert.css') }}">
    <link rel="icon" href="{{ asset('assets/img/logo.png') }}">
    <title>Log In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            width: 350px;
            height: 350px;
            margin: 160px auto;
            padding: 35px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn{
            margin:15px auto
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h4 class="text-left">Log In Form</h4>
            <form action="{{ route('loginAuth') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label for="email" class="form-label" style="font-size: 14px">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label" style="font-size: 14px">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary w-100">Log In</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('failed'))
        <script>
            Swal.fire({
                title: 'Login gagal!',
                text: '{{ session('failed') }}',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'small-swal'
                }
            });
        </script>
    @endif

    @if (session('logout'))
        <script>
            Swal.fire({
                title: 'Berhasil Logout!',
                text: '{{ session('logout') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
