<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

@if(auth()->user()->dashboard !== 'hide')
<div class="container animated-bg" style="margin-top:-10px">

  @php
    $cards = [
      ['title' => 'Daily Cash Sale', 'value' => $cashSubtotalToday, 'icon' => 'fa-solid fa-sack-dollar', 'color' => '#1A2035'],
      ['title' => 'Daily Sale Return', 'value' => $totalSaleReturnToday, 'icon' => 'fa-solid fa-rotate-left', 'color' => '#1A2035'],
      ['title' => 'Daily Sale', 'value' => $totalSale, 'icon' => 'fa-solid fa-cart-shopping', 'color' => '#1A2035'],
      ['title' => 'Daily Expense', 'value' => $totalExpense, 'icon' => 'fa-solid fa-receipt', 'color' => '#1A2035'],
      ['title' => 'Total Receivable', 'value' => $totalreciveables, 'icon' => 'fa-solid fa-hand-holding-dollar', 'color' => '#1A2035'],
      ['title' => 'Today Purchase', 'value' => $totalPurchase, 'icon' => 'fa-solid fa-box-open', 'color' => '#1A2035'],
      ['title' => 'Monthly Sale', 'value' => $monthlySale, 'icon' => 'fa-solid fa-calendar-days', 'color' => '#1A2035'],
      ['title' => 'Monthly Sale Return', 'value' => $monthlySaleReturn, 'icon' => 'fa-solid fa-calendar-xmark', 'color' => '#1A2035'],
      ['title' => 'Monthly Cash Sale', 'value' => $monthlyCashSubtotal, 'icon' => 'fa-solid fa-coins', 'color' => '#1A2035'],
      ['title' => 'Monthly Credit Sale', 'value' => $monthlyCreditSubtotal, 'icon' => 'fa-solid fa-credit-card', 'color' => '#1A2035'],
      ['title' => 'Monthly Expense', 'value' => $monthlyTotalExpense, 'icon' => 'fa-solid fa-money-bill-wave', 'color' => '#1A2035'],
      ['title' => 'Total Payable', 'value' => $monthlyTotalpayables, 'icon' => 'fa-solid fa-file-invoice-dollar', 'color' => '#1A2035'],
      ['title' => 'Monthly Purchase', 'value' => $monthlyPurchaseTotal, 'icon' => 'fa-solid fa-truck-loading', 'color' => '#1A2035'],
      ['title' => 'Today Discount', 'value' => $totaldiscount, 'icon' => 'fa-solid fa-percent', 'color' => '#1A2035'],
      ['title' => 'Today Fix Discount', 'value' => $totalfixdiscount, 'icon' => 'fa-solid fa-scissors', 'color' => '#1A2035'],
      ['title' => 'Monthly Discount', 'value' => $monthlyDiscount, 'icon' => 'fa-solid fa-tags', 'color' => '#1A2035'],
      ['title' => 'Monthly Fix Discount', 'value' => $monthlyfixDiscount, 'icon' => 'fa-solid fa-scissors', 'color' => '#1A2035'],
      ['title' => 'Other Income', 'value' => $monthlyTotalotherincome, 'icon' => 'fa-solid fa-piggy-bank', 'color' => '#1A2035'],
      ['title' => 'Monthly Payment To Vendor Other Than Purchase', 'value' => $monthlyDifference, 'icon' => 'fa-solid fa-handshake', 'color' => '#1A2035'],
    ];
  @endphp

  <div class="row g-4 mt-1">
    @foreach($cards as $card)
      @php
        $fullTitle = $card['title'];
        $shortTitle = \Illuminate\Support\Str::words($fullTitle, 4, '...');
      @endphp

      <div class="col-6 col-md-3 col-lg-2" style="margin-top:2px">
  <div class="card shadow-sm border-0 rounded-4 mb-1 compact-card">
    <div class="card-body d-flex align-items-center p-2">
      
      <div class="icon-wrapper rounded-4 d-flex justify-content-center align-items-center me-2"
           style="width:26px; height:26px; background-color:{{ $card['color'] }}20; color:{{ $card['color'] }}; font-size:14px;">
        <i class="{{ $card['icon'] }}"></i>
      </div>

      <div>
        <p class="mb-0 text-secondary text-uppercase fw-semibold"
           style="white-space: nowrap; overflow:hidden; text-overflow:ellipsis; max-width:120px; font-size:0.65rem;"
           title="{{ $fullTitle }}">
          {{ $shortTitle }}
        </p>
        <h6 class="mb-0 fw-bold text-dark" style="font-size:0.85rem;">{{ number_format($card['value'], 0) }}</h6>
      </div>

    </div>
  </div>
</div>
    @endforeach
  </div>

  <div class="row justify-content-end mt-3">
<div class="col-12 col-md-9 col-lg-10">
  <div class="card card-round" style="height:auto; background:transparent;">
    <div class="card-header py-2 d-flex justify-content-between align-items-center">
      <div class="card-title" style="font-size:0.9rem; color:#1A2035;">Current Month Daily Sales</div>
    </div>
    <div class="card-body p-2">
      <canvas id="monthDailySalesChart" class="small-chart"></canvas>
    </div>
  </div>
</div>



  <div class="col-12 col-md-3 col-lg-2">
    
    <div class="card border-0 rounded-4 shadow-lg hover-zoom mb-2" 
         style="background: linear-gradient(135deg, #e0f7fa, #ffffff); transition: transform 0.3s;">
      <div class="card-body d-flex align-items-center p-2">
        <div class="icon-wrapper rounded-circle d-flex justify-content-center align-items-center me-2"
             style="width:45px; height:45px; background: linear-gradient(135deg, #4ade80, #16a34a); color:#fff; font-size:20px; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
          <i class="fas fa-wallet fs-4"></i>
        </div>
        <div>
          <p class="mb-1 text-success small text-uppercase fw-bold"
             style="letter-spacing:0.5px; font-size:0.75rem;">Cash In Hand</p>
          <h5 class="mb-0 fw-bold text-dark" style="font-size:1rem;">
            {{ number_format($totalcashinhand, 0) }}
          </h5>
        </div>
      </div>
    </div>

    <div class="card border-0 rounded-4 shadow-lg hover-zoom" 
         style="background: linear-gradient(135deg, #e0f7fa, #ffffff); transition: transform 0.3s;">
      <div class="card-body d-flex align-items-center p-2">
        <div class="icon-wrapper rounded-circle d-flex justify-content-center align-items-center me-2"
             style="width:45px; height:45px; background: linear-gradient(135deg, #4ade80, #16a34a); color:#fff; font-size:20px; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
          <i class="fas fa-university"></i>
        </div>
        <div>
          <p class="mb-1 text-success small text-uppercase fw-bold"
             style="letter-spacing:0.5px; font-size:0.75rem;">Cash at Bank</p>
          <h5 class="mb-0 fw-bold text-dark" style="font-size:1rem;">
            {{ number_format($totalcashatBank, 0) }}
          </h5>
        </div>
      </div>
    </div>

  </div>
</div>

</div>

<style>
  :root {
    --shadow-light: rgba(0, 0, 0, 0.07);
    --shadow-medium: rgba(0, 0, 0, 0.12);
    --shadow-dark: rgba(0, 0, 0, 0.18);
    --transition-speed: 0.45s;
  }
    .hover-zoom:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }

  .fade-in {
    animation: fadeInCard 0.8s ease forwards;
    opacity: 0;
  }

  @keyframes fadeInCard {
    to {
      opacity: 1;
    }
  }

   .small-chart {
    height: 350px !important; 
  }
   .compact-card {
    min-height: auto !important;
    line-height: 1.7;
  }

  .animated-bg {
    background: linear-gradient(270deg, #f9fafb, #e9ecef, #f9fafb);
    background-size: 600% 600%;
    animation: backgroundShift 25s ease infinite;
    padding: 2.5rem 1.5rem;
    border-radius: 1.25rem;
  }

  @keyframes backgroundShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  .card-3d-animated {
    background-color: #fff;
    border: none;
    box-shadow:
      0 1px 3px var(--shadow-light),
      0 4px 6px var(--shadow-medium),
      0 8px 15px var(--shadow-dark);
    transition: transform var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1),
                box-shadow var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    transform-style: preserve-3d;
    will-change: transform;
    animation: float3d 6s ease-in-out infinite;
  }

  .card-3d-animated:hover {
    animation-play-state: paused;
    transform: perspective(900px) rotateX(10deg) rotateY(10deg) scale(1.1);
    box-shadow:
      0 10px 20px var(--shadow-medium),
      0 25px 40px var(--shadow-dark),
      0 40px 60px rgba(0, 0, 0, 0.25);
    z-index: 20;
  }

  @keyframes float3d {
    0%, 100% {
      transform: translateZ(0) translateY(0) rotateX(0) rotateY(0);
      box-shadow:
        0 1px 3px var(--shadow-light),
        0 4px 6px var(--shadow-medium),
        0 8px 15px var(--shadow-dark);
    }
    50% {
      transform: translateZ(12px) translateY(-6px) rotateX(4deg) rotateY(4deg);
      box-shadow:
        0 8px 15px var(--shadow-medium),
        0 20px 30px var(--shadow-dark),
        0 35px 50px rgba(0, 0, 0, 0.2);
    }
  }

  .icon-wrapper {
    transition: transform var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
  }

  .card-3d-animated:hover .icon-wrapper {
    transform: translateZ(35px);
    box-shadow: 0 0 15px 3px rgba(0, 123, 255, 0.4);
    border-radius: 1rem;
  }

  .letter-spacing {
    letter-spacing: 0.05em;
  }
  .letter-spacing-sm {
    letter-spacing: 0.03em;
  }

  .fade-in {
    opacity: 0;
    animation: fadeInUp 0.8s ease forwards;
  }
  .fade-in:nth-child(1) { animation-delay: 0.1s; }
  .fade-in:nth-child(2) { animation-delay: 0.2s; }
  .fade-in:nth-child(3) { animation-delay: 0.3s; }
  .fade-in:nth-child(4) { animation-delay: 0.4s; }
  .fade-in:nth-child(5) { animation-delay: 0.5s; }

  @keyframes fadeInUp {
    0% {
      opacity: 0;
      transform: translateY(15px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
@endif
<script>
const monthDailyCtx = document.getElementById('monthDailySalesChart').getContext('2d');
new Chart(monthDailyCtx, {
  type: 'line',
  data: {
    labels: {!! json_encode($monthDailyLabels) !!}, 
    datasets: [{
      label: 'Daily Sales (Rs)',
      data: {!! json_encode($monthDailyTotals) !!},
      borderColor: '#1A2035',
      backgroundColor: 'rgba(26, 32, 53, 0.2)',
      pointBackgroundColor: '#1A2035',
      pointBorderColor: '#fff',
      fill: true,
      tension: 0.4
    }]
  },
  options: {
    responsive: true,
    plugins: { 
      legend: { display: true, labels: { color: '#1A2035' } } 
    },
    scales: { 
      y: { 
        beginAtZero: true, 
        ticks: { color: '#1A2035' }, 
        grid: { color: 'rgba(26, 32, 53, 0.1)' } 
      },
      x: { 
        ticks: { color: '#1A2035' }, 
        grid: { color: 'rgba(26, 32, 53, 0.1)' } 
      }
    }
  }
});

    </script>
