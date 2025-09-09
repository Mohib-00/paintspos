<!DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logix 199</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">  
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('logix.png')}}">

    @include('css')
 
     
</head>
<body>

   
  <div class="container" id="registerContent" style="height:fit-content;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
      <h2>Register</h2>
      <svg class="userpage" width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" fill="currentColor"/>
      </svg>
    </div>
    <form id="registrationForm">
      <div class="input-box">
        <input type="text" id="name" name="name" required>
        <label for="name">Full Name</label>
        <span id="nameError" class="text-danger"></span>
      </div>
      <div class="input-box">
        <input type="email" id="email" name="email" required>
        <label for="email">Email</label>
        <span id="emailError" class="text-danger"></span>
      </div>
      <div class="input-box">
        <input type="password" id="password" name="password" required>
        <label for="password">Password</label>
        <span id="passwordError" class="text-danger"></span>
      </div>
      <div class="input-box">
        <input type="password" id="confirmPassword" name="confirmPassword" required>
        <label for="confirmPassword">Confirm Password</label>
        <span id="confirmPasswordError" class="text-danger"></span>
      </div>
      <div class="already-account">
        Already have an account? <a class="signIn">Sign In</a>
      </div>
      <button type="button" id="register" class="btn mt-3">Sign Up</button>
    </form>
  </div>

 @include('ajax')

</body>
 
