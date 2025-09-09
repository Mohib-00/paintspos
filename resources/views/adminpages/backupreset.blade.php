<!DOCTYPE html>
<html lang="en">
<head>
  @include('adminpages.css')
  <style>
    .container {
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 50px;
      min-height: 80vh;
      overflow: hidden;
      background: linear-gradient(-45deg, #8aaecb, #a7c3dd, #8aaecb, #7fa1c2);
      background-size: 400% 400%;
      animation: animateBG 20s ease infinite;
    }

    @keyframes animateBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .container .bubble {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.4);
      box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
      animation: floatBubble 20s linear infinite;
    }

    @keyframes floatBubble {
      0% {
        transform: translateY(100vh) scale(0.5);
        opacity: 0;
      }
      50% {
        opacity: 1;
      }
      100% {
        transform: translateY(-20vh) scale(1);
        opacity: 0;
      }
    }

    .card {
      position: relative;
      background: #ffffff; 
      border-radius: 20px;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
      width: 300px;
      height: 200px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      transition: transform 0.5s, box-shadow 0.5s;
      cursor: pointer;
      z-index: 1; 
    }

    .card:hover {
      transform: translateY(-10px) scale(1.05);
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
    }

    .card h2 {
      margin-bottom: 10px;
      color: #333;
    }

    .card p {
      text-align: center;
      padding: 0 20px;
      margin-bottom: 20px;
      color: #555;
    }

    .card button {
      padding: 10px 20px;
      border: none;
      border-radius: 10px;
      background: #007BFF;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    .card button:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    @include('adminpages.sidebar')

    <div class="main-panel">
      @include('adminpages.header')

      <div class="container">
        <span class="bubble" style="left: 10%; width: 40px; height: 40px; animation-delay: 0s;"></span>
        <span class="bubble" style="left: 25%; width: 60px; height: 60px; animation-delay: 5s;"></span>
        <span class="bubble" style="left: 40%; width: 30px; height: 30px; animation-delay: 2s;"></span>
        <span class="bubble" style="left: 60%; width: 50px; height: 50px; animation-delay: 8s;"></span>
        <span class="bubble" style="left: 80%; width: 40px; height: 40px; animation-delay: 4s;"></span>
        <span class="bubble" style="left: 90%; width: 70px; height: 70px; animation-delay: 7s;"></span>

        <div class="card">
          <h2>Backup</h2>
          <p>Backup your important data securely.</p>
          <button id="startBackup">Start Backup</button>
        </div>

        <div class="card">
          <h2>Reset</h2>
          <p>Reset your system to default settings.</p>
          <button id="reset">Reset Now</button>
        </div>
      </div>

      @include('adminpages.footer')
    </div>
  </div>

  @include('adminpages.js')
  @include('adminpages.ajax')

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
  $('#startBackup').on('click', function() {
    Swal.fire({
      title: 'Backing up database...',
      didOpen: () => {
        Swal.showLoading();
      },
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false,
    });

    $.ajax({
      url: '/backup',
      method: 'GET',
      xhrFields: {
        responseType: 'blob'
      },
      success: function(data) {
        Swal.close();

        const blob = new Blob([data], { type: 'application/sql' });
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = "backup_" + new Date().toISOString().replace(/[:.]/g, "_") + ".sql";
        link.click();

        Swal.fire({
          icon: 'success',
          title: 'Backup completed!',
          timer: 2000,
          showConfirmButton: false,
        });
      },
      error: function(xhr) {
        Swal.close();

        Swal.fire({
          icon: 'error',
          title: 'Backup failed!',
          text: xhr.responseText || 'An error occurred during backup.',
        });
      }
    });
  });
</script>

<script>
  $('#reset').on('click', function() {
    Swal.fire({
      title: 'Are you sure?',
      text: "This will delete ALL data except Accounts and Users!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, reset it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '/reset',
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response) {
            Swal.fire(
              'Reset!',
              response.message,
              'success'
            );
          },
          error: function(xhr) {
            Swal.fire(
              'Error!',
              'Reset failed: ' + xhr.responseText,
              'error'
            );
          }
        });
      }
    });
  });
</script>


</body>
</html>
