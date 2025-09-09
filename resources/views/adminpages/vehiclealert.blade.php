<!DOCTYPE html>
<html lang="en">
<head>
  @include('adminpages.css')
  <style>
    body {
      margin: 0;
      padding: 0;
      background: linear-gradient(-45deg, #9be7ff, #d1f4ff, #e1f5fe, #e0f7fa);
      background-size: 400% 400%;
      animation: gradientBG 15s ease infinite;
      font-family: 'Poppins', sans-serif;
    }

    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .container {
      padding: 40px 25px;
    }

    .section-heading {
      font-size: 2.5rem;
      font-weight: 700;
      text-align: center;
      margin-bottom: 40px;
      color: #00796B;
      animation: glow 2s ease-in-out infinite alternate;
    }

    @keyframes glow {
      from {
        text-shadow: 0 0 10px #66bb6a, 0 0 20px #66bb6a;
      }
      to {
        text-shadow: 0 0 20px #2e7d32, 0 0 40px #2e7d32;
      }
    }

    .card {
      backdrop-filter: blur(8px);
      background-color: rgba(255, 255, 255, 0.7);
      border-radius: 20px;
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: none;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 16px 30px rgba(0, 0, 0, 0.25);
    }

    .card-title {
      font-size: 1.3rem;
      font-weight: 600;
      color: #00796B;
    }

    .card-text {
      font-size: 1rem;
      color: #333;
      margin: 8px 0;
    }

    .alert-info {
      background-color: rgba(255, 255, 255, 0.85);
      font-size: 1.1rem;
      color: #555;
      border-radius: 10px;
      padding: 15px;
    }

    @media (max-width: 768px) {
      .section-heading {
        font-size: 1.8rem;
      }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    @include('adminpages.sidebar')

    <div class="main-panel">
      @include('adminpages.header')

      <div class="container">
        <h4 class="section-heading">🔔 Today's Vehicle Alerts</h4>

        <div class="row">
          @forelse ($vehicles as $alert)
            <div class="col-md-4 mb-4">
              <div class="card p-3">
                <div class="card-body">
                  <h5 class="card-title">🚗 Vehicle ID: {{ $alert->vehicle->id }}</h5>
                  <p class="card-text"><strong>Owner:</strong> {{ $alert->vehicle->owner_name }}</p>
                  <p class="card-text"><strong>Alert:</strong> {{ $alert->alert }}</p>
                  <p class="card-text"><strong>Date:</strong> {{ \Carbon\Carbon::parse($alert->created_at)->format('d-m-Y') }}</p>
                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="alert alert-info text-center">
                No alerts for today.
              </div>
            </div>
          @endforelse
        </div>
      </div>

      @include('adminpages.footer')
    </div>
  </div>

  @include('adminpages.js')
  @include('adminpages.ajax')
</body>
</html>
