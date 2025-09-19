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
    /* Background Gradient Animation */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      background: linear-gradient(-45deg, #0f2027, #203a43, #2c5364, #1e3c72);
      background-size: 400% 400%;
      animation: gradientBG 15s ease infinite;
    }

    @keyframes gradientBG {
      0% {background-position:0% 50%;}
      50% {background-position:100% 50%;}
      100% {background-position:0% 50%;}
    }

    /* Floating Particles */
    body::before {
      content: "";
      position: absolute;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.15) 1px, transparent 1px);
      background-size: 50px 50px;
      animation: moveParticles 20s linear infinite;
      z-index: 0;
    }

    @keyframes moveParticles {
      0% {transform: translate(0,0);}
      100% {transform: translate(-100px,-100px);}
    }

    /* Login Container Glassmorphism */
    #loginContent {
      position: relative;
      z-index: 1;
      background: rgba(255, 255, 255, 0.1);
      padding: 50px 40px;
      border-radius: 25px;
      border: 1px solid rgba(255,255,255,0.2);
      box-shadow: 0 0 40px rgba(76, 209, 55, 0.5), 0 0 20px rgba(76, 209, 55, 0.3);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      width: 100%;
      max-width: 400px;
      animation: fadeInUp 1s ease forwards;
    }

    @keyframes fadeInUp {
      from {opacity: 0; transform: translateY(50px);}
      to {opacity: 1; transform: translateY(0);}
    }

    /* Title Glow */
    h2 {
      color: #fff;
      text-align: center;
      font-weight: 900;
      margin-bottom: 30px;
      font-size: 2rem;
      text-shadow: 0 0 10px #4cd137, 0 0 20px #4cd137, 0 0 30px #4cd137;
      animation: neonGlow 1.5s infinite alternate;
    }

    @keyframes neonGlow {
      0% {text-shadow: 0 0 5px #4cd137, 0 0 10px #4cd137;}
      50% {text-shadow: 0 0 15px #4cd137, 0 0 30px #44bd32;}
      100% {text-shadow: 0 0 25px #44bd32, 0 0 50px #4cd137;}
    }

    /* Input Boxes */
    .input-box {
      position: relative;
      margin-bottom: 30px;
    }

    .input-box input {
      width: 100%;
      padding: 15px;
      border: none;
      border-bottom: 2px solid #ccc;
      background: transparent;
      color: #fff;
      font-size: 16px;
      border-radius: 8px;
      transition: 0.3s;
      box-shadow: 0 0 5px rgba(76,209,55,0.2);
    }

    .input-box input:focus {
      outline: none;
      border-color: #4cd137;
      box-shadow: 0 0 10px #4cd137, 0 0 20px #44bd32;
    }

    .input-box label {
      position: absolute;
      top: -20px;
      left: 5px;
      color: #ccc;
      font-size: 14px;
      transition: 0.3s;
    }

    .text-danger {
      font-size: 13px;
      color: #ff6b6b !important;
    }

    .already-account {
      color: #ccc;
      text-align: center;
      margin-bottom: 20px;
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

    /* Shiny Gradient Button */
    .btn {
      position: relative;
      overflow: hidden;
      background: linear-gradient(45deg, #4cd137, #44bd32, #4cd137);
      background-size: 200% 200%;
      color: #fff;
      font-weight: bold;
      border-radius: 12px;
      padding: 15px;
      width: 100%;
      font-size: 16px;
      transition: 0.3s;
      box-shadow: 0 0 15px rgba(76,209,55,0.7);
      animation: shine 5s linear infinite;
    }

    .btn:hover {
      transform: scale(1.05);
      box-shadow: 0 0 25px rgba(76,209,55,1);
    }

    @keyframes shine {
      0% {background-position: 0%;}
      50% {background-position: 100%;}
      100% {background-position: 0%;}
    }

    /* Home SVG Button */
    .userpage {
      color: #fff;
      transition: 0.3s;
    }

    .userpage:hover {
      transform: rotate(20deg) scale(1.3);
      color: #4cd137;
      filter: drop-shadow(0 0 15px #4cd137);
    }
  </style>
</head>
<body>

  <div class="container" id="loginContent">
    <h2>Login</h2>
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
