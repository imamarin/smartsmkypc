<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-smk.png') }}">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(to right, #263788, #3f51b5, #2196f3);">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h4 class="mb-4 text-center">Lupa Password</h4>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('forgot.password.send') }}">
                @csrf
                <div class="mb-3">
                    <label for="phone" class="form-label">Nomor WhatsApp</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Contoh: 628xxxxxxxxxx" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Kirim Link Reset</button>
            </form>
        </div>
    </div>
</body>
</html>