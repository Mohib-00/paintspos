<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('Investor Group on Climate Change_files/logix.png') }}">

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
      overflow: hidden;
    }

    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Glowing particle background */
    body::before {
      content: "";
      position: absolute;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.2) 1px, transparent 1px);
      background-size: 50px 50px;
      animation: moveParticles 15s linear infinite;
      z-index: 0;
    }

    @keyframes moveParticles {
      from { transform: translate(0, 0); }
      to { transform: translate(-50px, -50px); }
    }

    #loginContent {
      background: rgba(255, 255, 255, 0.1);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37), 0 0 25px rgba(76, 209, 55, 0.5);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      width: 100%;
      max-width: 400px;
      z-index: 1;
      position: relative;
      animation: popUp 1s ease forwards;
    }

    @keyframes popUp {
      from { transform: scale(0.8); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    h2 {
      color: #fff;
      margin-bottom: 20px;
      font-weight: bold;
      text-shadow: 0 0 10px #4cd137, 0 0 20px #4cd137;
      animation: glowText 2s infinite alternate;
    }

    @keyframes glowText {
      from { text-shadow: 0 0 5px #4cd137, 0 0 15px #4cd137; }
      to { text-shadow: 0 0 20px #44bd32, 0 0 40px #44bd32; }
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
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    .input-box input:focus {
      outline: none;
      border-color: #4cd137;
      box-shadow: 0 2px 10px #4cd137;
    }

    .input-box label {
      position: absolute;
      top: -20px;
      left: 0;
      font-size: 14px;
      color: #ddd;
      transition: 0.3s;
    }

    .text-danger {
      font-size: 13px;
      color: #ff6b6b !important;
    }

    .already-account {
      color: #ccc;
      margin-top: 10px;
      font-size: 14px;
    }

    .already-account a {
      color: #4cd137;
      text-decoration: none;
      transition: 0.3s;
    }

    .already-account a:hover {
      color: #fff;
      text-shadow: 0 0 10px #4cd137;
    }

    .btn {
      background: linear-gradient(45deg, #4cd137, #44bd32);
      border: none;
      width: 100%;
      padding: 12px;
      font-weight: bold;
      border-radius: 12px;
      transition: 0.3s;
      color: white;
      box-shadow: 0 0 10px rgba(76, 209, 55, 0.7);
    }

    .btn:hover {
      background: linear-gradient(45deg, #44bd32, #4cd137);
      box-shadow: 0 0 20px rgba(76, 209, 55, 1);
      transform: scale(1.05);
    }

    .userpage {
      color: #fff;
      transition: 0.3s;
    }

    .userpage:hover {
      transform: rotate(15deg) scale(1.2);
      color: #4cd137;
      filter: drop-shadow(0 0 10px #4cd137);
    }
  </style>
</head>
<body>

  <div class="container" id="loginContent">
    <div style="display: flex; align-items: center; justify-content: space-between;">
      <h2>Login</h2>
      <a href="/">
        <svg class="userpage" width="40" height="40" viewBox="0 0 24 24" fill="none"
             xmlns="http://www.w3.org/2000/svg">
          <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" fill="currentColor"/>
        </svg>
      </a>
    </div>
    <form id="loginForm">
      <div class="input-box">
        <input type="email" id="loginEmail" name="email" required>
        <label for="loginEmail">Email</label>
        <span id="loginEmailError" class="text-danger"></span>
      </div>
      <div class="input-box">
        <input type="password" id="loginPassword" name="password" required>
        <label for="loginPassword">Password</label>
        <span id="loginPasswordError" class="text-danger"></span>
      </div>

      <div class="already-account">
        <a href="/forgot-password" class="forgot-password">Forgot Password?</a>
      </div>
      <button type="button" id="login" class="btn mt-3">Login</button>
    </form>
  </div>

  @include('ajax')

</body>
</html>
