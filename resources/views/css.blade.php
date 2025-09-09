<style>
  {
       margin: 0;
       padding: 0;
       box-sizing: border-box;
       font-family: 'Poppins', sans-serif;
     }
 
     body {
       height: 100vh;
       background: #f0f0f0;
       display: flex;
       justify-content: center;
       align-items: center;
       overflow: hidden;
       position: relative;
     }
 
      
     body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("{{ asset('logix.png') }}") no-repeat center;
    background-size: contain;   
    background-attachment: fixed;  
    opacity: 0.8;
    z-index: -1;
}



     /* Form container */
     .container {
       position: relative;
       background: rgba(255, 239, 213, 0.3);
       padding: 40px;
       border-radius: 20px;
       backdrop-filter: blur(10px);
       box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
       z-index: 3;
       width: 500px;
       height:600px;
       animation: slideIn 1.5s ease-in-out;
     }
 
     @keyframes slideIn {
       0% {
         transform: translateY(100vh);
       }
       100% {
         transform: translateY(0);
       }
     }
 
     h2 {
       text-align: center;
       color: #fff;
       margin-bottom: 20px;
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
 
     .input-box {
       position: relative;
       margin-bottom: 30px;
     }
 
     .input-box input {
     width: 100%;
     padding: 15px;  
     font-size: 16px;
     color: black;
     background: rgba(255, 255, 255, 0.1);
     border: none;
     border-radius: 25px;
     outline: none;
     backdrop-filter: blur(3px);
     transition: 0.5s ease;
 }
 
 
     .input-box input:focus {
       background: rgba(255, 255, 255, 0.2);
       box-shadow: 0 0 15px rgba(255, 183, 77, 0.5);
     }
 
     .input-box label {
       position: absolute;
       top: 50%;
       left: 20px;
       transform: translateY(-50%);
       color: black;
       font-size: 16px;
       pointer-events: none;
       transition: 0.5s;
     }
 
     .input-box input:focus ~ label,
     .input-box input:valid ~ label {
       top: -10px;
       left: 20px;
       font-size: 12px;
       color: black;
     }
 
     .btn {
       width: 100%;
       padding: 15px;
       background: linear-gradient(45deg, #ffbb93, #ff7043);
       border: none;
       border-radius: 25px;
       color: #fff;
       font-size: 18px;
       cursor: pointer;
       transition: background 0.5s ease;
       box-shadow: 0 10px 20px rgba(255, 118, 158, 0.2);
     }
 
     .btn:hover {
       background: linear-gradient(45deg, #ff9e57, #ff5722);
       box-shadow: 0 10px 20px rgba(255, 118, 158, 0.3);
     }
 
     .already-account {
   text-align: center;
   margin-top: 10px;
   font-size: 20px;
 }
 
 .already-account a {
   color: #ff5722;  
   text-decoration: none;
   font-weight: bold;
   transition: color 0.3s;
 }
 
 .already-account a:hover {
   color: #ff7043;
 }
 
  
 
 </style>