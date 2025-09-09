<!DOCTYPE html>
<html lang="en">
<head>
  @include('adminpages.css')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
 <style>
    #bg-video {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      object-fit: cover;   
      z-index: -1;
      filter: brightness(0.5);
    }

    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-8px); }
      100% { transform: translateY(0px); }
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .alert-card {
      perspective: 1000px;
      margin: 20px;
      flex: 1 1 calc(33.333% - 40px);
      min-width: 280px;
      animation: fadeInUp 0.8s ease-in-out;
    }

    .alert-inner {
      transition: transform 0.5s, box-shadow 0.3s;
      position: relative;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 20px;
      padding: 20px;
      backdrop-filter: blur(12px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      color: #fff;
      cursor: pointer;
      animation: float 4s ease-in-out infinite;
    }

    .alert-inner:hover {
      transform: scale(1.07);
      box-shadow: 0 15px 40px rgba(0,0,0,0.5);
    }

    .alert-title {
      font-size: 22px;
      font-weight: bold;
    }

    .alert-message {
      font-size: 15px;
      margin-top: 8px;
    }

    .alert-date {
      margin-top: 12px;
      font-size: 13px;
      opacity: 0.8;
    }

    .btn-add-alert {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: white;
      padding: 12px 20px;
      border-radius: 10px;
      border: none;
      font-size: 16px;
      cursor: pointer;
      margin: 15px;
      transition: 0.3s;
    }
    .btn-add-alert:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 18px rgba(0,0,0,0.4);
    }
#alertModal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0; top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  animation: modalFadeIn 0.4s ease forwards;
  display:none;
}

.modal-content {
  background: linear-gradient(145deg, #1f1c2c, #928dab);
  border-radius: 20px;
  padding: 30px 25px;
  width: 400px;
  max-width: 90%;
  color: #fff;
  box-shadow: 0 15px 50px rgba(0,0,0,0.5);
  transform: scale(0.8);
  opacity: 0;
  animation: modalZoomIn 0.5s forwards;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(255,255,255,0.2);
}

@keyframes modalFadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes modalZoomIn {
  0% { transform: scale(0.8); opacity: 0; }
  60% { transform: scale(1.05); opacity: 1; }
  100% { transform: scale(1); opacity: 1; }
}

.modal-header {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 20px;
  text-align: center;
  position: relative;
  color: white;
  text-shadow: 1px 1px 5px rgba(0,0,0,0.7);
}

.btn-close {
  position: absolute;
  top: 15px;
  right: 15px;
  background: #ff4d4f;
  color: #fff;
  font-weight: bold;
  padding: 5px 12px;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  transition: transform 0.3s, box-shadow 0.3s;
}
.btn-close:hover {
  transform: rotate(90deg) scale(1.1);
  box-shadow: 0 5px 15px rgba(0,0,0,0.4);
}

.form-group label {
  display: block;
  margin-bottom: 6px;
  font-weight: 600;
  color: white;
}

.form-group input, 
.form-group textarea {
  width: 100%;
  padding: 12px 10px;
  border-radius: 10px;
  border: none;
  outline: none;
  background: rgba(255,255,255,0.15);
  color: #fff;
  font-size: 14px;
  transition: 0.3s;
  backdrop-filter: blur(5px);
}
.form-group input:focus,
.form-group textarea:focus {
  background: rgba(255,255,255,0.25);
  box-shadow: 0 0 10px rgba(255,255,255,0.4);
}

.btn-submit {
  width: 100%;
  margin-top: 15px;
  background: linear-gradient(135deg, #43cea2, #185a9d);
  color: #fff;
  font-weight: bold;
  padding: 12px 0;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-size: 16px;
  transition: 0.4s;
}
.btn-submit:hover {
  background: linear-gradient(135deg, #185a9d, #43cea2);
  transform: scale(1.05);
  box-shadow: 0 6px 18px rgba(0,0,0,0.4);
}

.form-group input:focus, .form-group textarea:focus {
  border: 1px solid #ffd700;
}

    .btn-mark-read {
      background: linear-gradient(135deg, #43cea2, #185a9d);
      color: white;
      padding: 8px 14px;
      border: none;
      border-radius: 8px;
      margin-top: 12px;
      cursor: pointer;
      transition: 0.3s;
      font-size: 14px;
    }
    .btn-mark-read:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 15px rgba(0,0,0,0.4);
    }

    .alert-title i,
    .alert-message i,
    .alert-date i {
      margin-right: 6px;
      color: white; 
    }
    .wrapper {
  position: relative;
  min-height: 100vh;
  background: linear-gradient(-45deg, #2b2b2b, #dddddd, #2f2f2f, #eeeeee);
  background-size: 400% 400%;
  animation: gradientBG 30s ease infinite; 
  color: #fff;
}

.wrapper::before {
  content: '';
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.25); 
  pointer-events: none;
}

@keyframes gradientBG {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

</style>

</head>
<body>
  

  <div class="wrapper">
    @include('adminpages.sidebar')

    <div class="main-panel">
      @include('adminpages.header')

      <div class="container">
        <button class="btn-add-alert" onclick="openModal()">+ Add Alert</button>

       <div class="alerts-container d-flex flex-wrap">
  @foreach ($alerts as $alert)
    <div class="alert-card">
      <div class="alert-inner">
        <div class="alert-title">
          <i class="fas fa-bell"></i> {{ $alert->title }}
        </div>

        <div class="alert-message">
          <i class="fas fa-comment-dots"></i> {{ $alert->message }}
        </div>

        <div class="alert-date">
          <i class="fas fa-calendar-alt"></i> {{ $alert->alert_date }}
        </div>

        <button class="btn-mark-read" onclick="markAsRead({{ $alert->id }})">
          <i class="fas fa-check-circle"></i> Mark as Read
        </button>
      </div>
    </div>
  @endforeach
</div>

      </div>

      @include('adminpages.footer')
    </div>
  </div>

  <div id="alertModal" class="modal">
    <div class="modal-content">
      <button class="btn-close" onclick="closeModal()">X</button>
      <div class="modal-header">Add New Alert</div>
      <form id="addAlertForm">
        @csrf
        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" name="title" required>
        </div>
        <div class="form-group">
          <label for="message">Message</label>
          <textarea name="message" rows="3"></textarea>
        </div>
        <div class="form-group">
          <label for="alert_date">Alert Date</label>
          <input type="date" name="alert_date" required>
        </div>
        <button type="submit" class="btn-submit">Save Alert</button>
      </form>
    </div>
  </div>

  @include('adminpages.js')
  @include('adminpages.ajax')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    function openModal() {
      document.getElementById('alertModal').style.display = 'flex';
    }
    function closeModal() {
      document.getElementById('alertModal').style.display = 'none';
    }

    document.getElementById("addAlertForm").addEventListener("submit", function(e) {
      e.preventDefault();

      let formData = new FormData(this);

      fetch("{{ route('alerts.store') }}", {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Alert added successfully!',
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          });
        }
      })
      .catch(() => {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Server error, please try again!'
        });
      });
    });

    function markAsRead(id) {
    fetch("{{ url('/alerts/mark-read') }}/" + id, {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": "{{ csrf_token() }}",
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        Swal.fire({
          icon: 'success',
          title: 'Marked as Read!',
          timer: 1500,
          showConfirmButton: false
        }).then(() => location.reload());
      }
    })
    .catch(() => {
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: 'Could not mark as read.'
      });
    });
  }
  </script>
</body>
</html>
