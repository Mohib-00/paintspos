<!DOCTYPE html>
<html lang="en">
  <head>
    @include('adminpages.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      .card-round {
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 20px;
      }
      .card-title {
        font-weight: bold;
        font-size: 16px;
      }
      .chart-container {
        min-height: 200px;
      }
    </style>
  </head>
  <body>
    <div class="wrapper">
      @include('adminpages.sidebar')

      <div class="main-panel">
        @include('adminpages.header')

        <div class="container" style="margin-top:5%">
          <div class="row">
            <div class="col-md-8">
              <div class="card card-round">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div class="card-title">Sales Overview</div>
                  <div class="card-tools">
                    <button class="btn btn-sm btn-success me-2">
                      <i class="fa fa-download"></i> Export
                    </button>
                    <button class="btn btn-sm btn-info">
                      <i class="fa fa-print"></i> Print
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                  </div>
                </div>
              </div>
                            <div class="card card-round mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div class="card-title">Current Month Daily Sales</div>
  </div>
  <div class="card-body">
    <canvas id="monthDailySalesChart" style="height:120px;"></canvas>
  </div>
</div>
            </div>

            <div class="col-md-4">
              <div class="card card-round mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div class="card-title">Daily Sales</div>
                  <div class="card-tools dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                      Export
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="#">CSV</a>
                      <a class="dropdown-item" href="#">PDF</a>
                      <a class="dropdown-item" href="#">Print</a>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <h3>Rs {!! number_format($todaySales, 2) !!}</h3>
                  <canvas id="dailySalesChart" style="height:120px;"></canvas>
                </div>
              </div>

              <div class="card card-round">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                    </div>
                  </div>
                  <canvas id="usersChart" style="height:80px;"></canvas>
                </div>
              </div>


     
            </div>
          </div>
        </div>

        @include('adminpages.footer')
      </div>
    </div>

    @include('adminpages.js')
    @include('adminpages.ajax')

    <script>
      const months = {!! json_encode($months) !!};
      const subtotals = {!! json_encode($totals) !!}; 
      const days = {!! json_encode($days) !!};
      const dailyTotals = {!! json_encode($dailyTotals) !!};

      const salesCtx = document.getElementById('salesChart').getContext('2d');
      new Chart(salesCtx, {
        type: 'bar',
        data: {
          labels: months,
          datasets: [{
            label: 'Monthly Sales (Rs: {!! $currentMonthTotal !!})',
            data: subtotals,
            backgroundColor: 'rgba(38, 198, 218, 0.8)',
            borderRadius: 5
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { display: true } },
          scales: { y: { beginAtZero: true } }
        }
      });

      const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
      new Chart(dailySalesCtx, {
        type: 'line',
        data: {
          labels: days,
          datasets: [{
            label: 'Daily Sales',
            data: dailyTotals,
            borderColor: '#6a11cb',
            backgroundColor: 'rgba(106,17,203,0.2)',
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { display: true } },
          scales: { y: { beginAtZero: true } }
        }
      });

const usersCtx = document.getElementById('usersChart').getContext('2d');
new Chart(usersCtx, {
  type: 'bar', 
  data: {
    labels: {!! json_encode($userLabels) !!},  
    datasets: [{
      label: 'Sales by User ',
      data: {!! json_encode($userTotals) !!},   
      backgroundColor: 'rgba(0, 200, 83, 0.8)',
      borderRadius: 5
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: true } },
    scales: {
      y: { beginAtZero: true },
      x: { title: { display: true, text: 'Users' } },
    }
  }
});

const monthDailyCtx = document.getElementById('monthDailySalesChart').getContext('2d');
  new Chart(monthDailyCtx, {
    type: 'line',
    data: {
      labels: {!! json_encode($monthDailyLabels) !!}, 
      datasets: [{
        label: 'Daily Sales',
        data: {!! json_encode($monthDailyTotals) !!},
        borderColor: '#ff5722',
        backgroundColor: 'rgba(255,87,34,0.2)',
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: true } },
      scales: { y: { beginAtZero: true } }
    }
  });
    </script>
  </body>
</html>
