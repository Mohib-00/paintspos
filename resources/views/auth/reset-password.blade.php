<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reset Password</title>
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

    .container {
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
      text-align: center;
      font-size: 28px;
      animation: flickerTitle 3s infinite alternate ease-in-out;
    }

    @keyframes flickerTitle {
      0%, 100% {
        text-shadow: 0 0 10px rgba(255, 170, 85, 0.5), 0 0 20px rgba(255, 136, 0, 0.7);
      }
      50% {
        text-shadow: 0 0 20px rgba(255, 170, 85, 0.7), 0 0 40px rgba(255, 136, 0, 0.9);
      }
    }

    .message {
      font-size: 14px;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
    }

    .success {
      background: #d4edda;
      color: #155724;
    }

    .error {
      background: #f8d7da;
      color: #721c24;
    }

    label {
      display: block;
      text-align: left;
      font-weight: bold;
      margin: 10px 0 5px;
      color: #ccc;
    }

    input {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: none;
      border-bottom: 2px solid #ccc;
      background: transparent;
      color: #fff;
      font-size: 16px;
    }

    input:focus {
      outline: none;
      border-color: #4cd137;
    }

    button {
      background-color: #4cd137;
      border: none;
      width: 100%;
      padding: 12px;
      font-weight: bold;
      border-radius: 10px;
      transition: background-color 0.3s;
      color: white;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #44bd32;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Reset Password</h2>

    @if(session('success'))
      <p class="message success">{{ session('success') }}</p>
    @endif
    @if($errors->any())
      <p class="message error">{{ $errors->first() }}</p>
    @endif

    <form action="{{ route('password.update') }}" method="POST">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <label>Email:</label>
      <input type="email" name="email" placeholder="Enter your email" required>

      <label>New Password:</label>
      <input type="password" name="password" placeholder="Enter new password" required>

      <label>Confirm Password:</label>
      <input type="password" name="password_confirmation" placeholder="Confirm new password" required>

      <button type="submit">Reset Password</button>
    </form>
  </div>

</body>
</html>
