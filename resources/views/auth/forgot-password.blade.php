<!DOCTYPE html>
<html lang="en">
<head>  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot-Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">  
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{asset('Investor Group on Climate Change_files/logix.png')}}">

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      height: 100vh;
      background: linear-gradient(-45deg, #0f2027, #203a43, #2c5364, #1e3c72);
      background-size: 400% 400%;
      animation: gradientBG 12s ease infinite;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    #loginContent {
      background: rgba(255, 255, 255, 0.1);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.18);
      width: 100%;
      max-width: 400px;
    }

    h2 {
      color: #fff;
      margin-bottom: 20px;
    }

    .input-box {
      position: relative;
      margin-bottom: 25px;
    }

    .input-box input {
      width: 100%;
      padding: 12px;
      border: none;
      border-bottom: 2px solid #ccc;
      background: transparent;
      color: #fff;
      font-size: 16px;
    }

    .input-box label {
      position: absolute;
      top: -20px;
      left: 0;
      font-size: 14px;
      color: #ddd;
    }

    .input-box input:focus {
      outline: none;
      border-color: #4cd137;
    }

    .text-danger {
      font-size: 13px;
      color: #ff6b6b !important;
    }

    .already-account {
      color: #ccc;
      margin-top: 10px;
    }

    .already-account a {
      color: #4cd137;
      text-decoration: none;
      transition: color 0.3s;
    }

    .already-account a:hover {
      color: #fff;
    }

    .btn {
      background-color: #4cd137;
      border: none;
      width: 100%;
      padding: 10px;
      font-weight: bold;
      border-radius: 10px;
      transition: background-color 0.3s;
      color: white;
    }

    .btn:hover {
      background-color: #44bd32;
    }

    .userpage {
      color: #fff;
      transition: transform 0.3s;
    }

    .userpage:hover {
      transform: scale(1.1);
      color: #4cd137;
    }
  </style>
</head>
<body>

  <div class="container" id="loginContent">
    <div style="display: flex; align-items: center; justify-content: space-between;">
      <h2>Forgot Password</h2>
      <a href="/">
        <svg class="userpage" width="40" height="40" viewBox="0 0 24 24" fill="none"
          xmlns="http://www.w3.org/2000/svg">
          <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" fill="currentColor" />
        </svg>
      </a>
    </div>

    @if(session('success'))
    <p style="color: #4cd137;">{{ session('success') }}</p>
    @endif

    @if($errors->any())
    <p class="text-danger">{{ $errors->first() }}</p>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
      @csrf
      <div class="input-box">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
      </div>

      <button type="submit" class="btn">Send Reset Link</button>
    </form>

    <div class="already-account">
      <p>Remember your password? <a href="/">Back to Login</a></p>
    </div>
  </div>

</body>
</html>
