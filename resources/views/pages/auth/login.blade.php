<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-smk.png') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #fff;
    }
    .login-container {
      min-height: 100vh;
    }
    .login-left {
      flex: 2;
      background: #fff;
      padding: 40px;
    }
    .login-right {
      flex: 1;
      background: #263788;
      /* background: linear-gradient(to right, #263788, #6a5acd); */
      background: linear-gradient(to right, #263788, #3f51b5, #2196f3);

      color: white;
      padding: 60px 40px;
    }
    .login-box {
      max-width: 400px;
      width: 100%;
      margin: auto;
    }
    .form-control {
      border-radius: 10px;
      padding: 10px 15px;
    }
    .btn-login {
      background-color: #f97316;
      color: white;
      border-radius: 10px;
      padding: 10px 0;
      border: solid 1px white;
    }
    .btn-login:hover {
        background: #263788;
        color: white;
        border: solid 1px #f97316;
    }
    .form-text a {
      color: white;
      text-decoration: underline;
    }
    .form-group {
      position: relative;
    }
    .toggle-password {
      position: absolute;
      top: 70%;
      right: 15px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #999;
    }
    .logo-smk {
      width: 150px;
      height: auto;
    }

  </style>
</head>
<body>

<div class="login-container d-block d-md-flex">
  <div class="login-left d-none d-md-flex align-items-center justify-content-center">
    <img src="{{ asset('assets/images/login-smart.png') }}" class="img-fluid" alt="Login Illustration" />
  </div>
  <div class="login-right d-md-flex align-items-center justify-content-center min-vh-100">
    <div class="login-box">
        <img src="{{ asset('assets/images/logo-smk.png') }}" class="logo-smk mx-auto d-block mb-3" alt="Login Illustration" />
        <div style="height: 100px" class="text-center text-white">
            <h4 class="mb-3" id="typewriter"></h4>
        </div>
        <p class="mb-4">Silakan login untuk masuk ke layanan SMART</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
                <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter username">
                </div>
            <div class="mb-3 form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
                <span class="toggle-password" onclick="togglePassword()"><i class="fa-regular fa-eye"></i></span>
            </div>
            <div class="d-grid mb-3">
                <a href="{{ route('forgot.password.form') }}" class="text-white">Klik disini lupa password</button>
            </div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn-login">Login</button>
            </div>
        </form>
    </div>
  </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.querySelector('.toggle-password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
        } else {
            passwordInput.type = 'password';
            toggleIcon.innerHTML = '<i class="fa-regular fa-eye"></i>';
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        const text = "Assalamualaikum Wr. Wb.<br>Selamat Datang, di Aplikasi SMART SMK YPC Tasikmalaya";
        const target = document.getElementById("typewriter");
        let i = 0;
        function typeWriter() {
            if (i < text.length) {
                if (text.substring(i, i+4) === "<br>") {
                    target.innerHTML += "<br>";
                    i += 4;
                } else {
                    target.innerHTML += text.charAt(i);
                    i++;
                }
                setTimeout(typeWriter, 60);
            } else {
                setTimeout(() => {
                    target.innerHTML = "";
                    i = 0;
                    typeWriter();
                }, 1500); 
            }
        }
        typeWriter();
    });
</script>
</body>
</html>
