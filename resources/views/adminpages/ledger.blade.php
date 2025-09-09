<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
  
  </head>
  <body>
    


       <div class="container" style="margin-top: 20px; max-width: 100%; width: calc(100% - 40px);">
    <div class="page-inner">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <h1 class="mx-3 listttt text-center">General Ledger</h1>

               <form method="GET" action="{{ route('searchgeneralledger') }}" class="row g-3 p-4">
    <div class="col-md-4">
        <label for="employee_id" class="form-label">Account Type</label>
        <select id="employee_id" name="employee_id" class="form-select">
            <option value="">All</option>
            @foreach($gnrlaccounts as $gnrlaccount)
                <option value="{{ $gnrlaccount->account_name }}" {{ request('employee_id') == $gnrlaccount->account_name ? 'selected' : '' }}>
                    {{ $gnrlaccount->account_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label for="sub_head_name" class="form-label">Child</label>
        <select id="sub_head_name" name="sub_head_name" class="form-select">
            <option value="">Select Sub Head</option>
            {{-- Dynamically fill using JS or backend --}}
        </select>
    </div>

   <div class="col-md-4">
    <label for="sub_child" class="form-label">Sub Child</label>
    <select id="sub_child" name="sub_child" class="form-select">
        <option value="">Select Sub Child</option>
        {{-- Fill dynamically --}}
    </select>
</div>

     <div class="col-md-4">
    <label for="from_date" class="form-label">Created From</label>
    <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
</div>

<div class="col-md-4">
    <label for="to_date" class="form-label">Created To</label>
    <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
</div>


    <div class="col-md-4 align-self-end">
        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-search"></i> Search
        </button>
    </div>
</form>



                  <div class="card-header d-flex justify-content-between align-items-center">
                   <div>
  <button class="btn btn-sm btn-outline-primary me-2 print-tables">
    <i class="fas fa-print"></i> Print
  </button>

  <button class="btn btn-sm btn-outline-danger export-pdf-tables">
    <i class="fas fa-file-pdf"></i> PDF
  </button>
</div>

                    <div>
                        <a href="/admin/chart_of_account" onclick="loadaccountPage(); return false;" class="btn btn-sm btn-secondary back-btn" style="margin-right: 0;">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>



                </div>
            </div>
        </div>

    </div>
       </div>



       @if (!empty($matchingGrnAccounts) && count($matchingGrnAccounts) > 0)
        <div class="container" style="margin-top: -25px; max-width: 100%; width: calc(100% - 40px);">
    <div class="page-inner">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <h1 class="mx-3 list text-center mt-3">Asset Accounts</h1>

                    <div class="card-body">
                        <div class="mb-3 mx-3">
                         <input type="text" id="customSearchInput" class="form-control" placeholder="Search Asset records...">
                         </div>
                        <div class="table-responsive">
                            <table class="display table table-striped table-hover searchable-table">
                                <thead>
                                    <tr>
                                        <th width="30">#</th>
                                        <th width="30">Trans</th>
                                        <th width="30">Date</th>
                                        <th width="30">Time</th>
                                        <th width="100">Account</th>
                                        <th width="400">Narration</th>
                                        <th width="30">Debit</th>
                                        <th width="30">Credit</th>
                                        <th width="30">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 1; 
                                    $runningBalance = $totalAssetOpening;
                                    @endphp
                                    <tr>
                                    <td></td>
                                    <td></td> 
                                    <td></td> 
                                    <td></td> 
                                    <td></td> 
                                    <td>Opening Balance</td> 
                                    <td></td> 
                                    <td></td>
                                    <td>{{ number_format($totalAssetOpening, 2) }}</td> 
                                    </tr>
                                    @foreach($matchingGrnAccounts as $grn)
                                     @php
                                     $runningBalance = $runningBalance + ($grn->debit ?? 0) - ($grn->vendor_net_amount ?? 0);
                                     @endphp
                                        <tr class="user-row" id="grn-{{ $grn->id }}">
                                            <td>{{ $counter }}</td>
                                            <td>#{{ $grn->id }}</td>
                                            <td style="white-space: nowrap">{{ $grn->created_at->format('d-m-Y') }}</td>
                                            <td>{{ $grn->created_at->format('H:i') }}</td>
                                            <td>{{ $grn->vendorAccount->sub_head_name }}</td>
                                            <td>{{ $grn->custom_narration }}</td>
                                            <td>{{ number_format($grn->debit ?? 0, 2) }}</td> 
                                            <td>{{ number_format($grn->vendor_net_amount ?? 0, 2) }}</td>
                                            <td>{{ number_format($runningBalance, 2) }}</td>

                                            
                                        </tr>
                                        @php $counter++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
       </div>
       @endif


@if (!empty($matchingExpenseAccounts) && count($matchingExpenseAccounts) > 0)
         <div class="container" style="margin-top: -25px; max-width: 100%; width: calc(100% - 40px);">
    <div class="page-inner">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <h1 class="mx-3 list text-center mt-3">Expense Accounts</h1>

                    <div class="card-body">
                         <div class="mb-3 mx-3">
                         <input type="text" id="customSearchInputexpense" class="form-control" placeholder="Search Expense Records...">
                         </div>
                        <div class="table-responsive">
                            <table class="display table table-striped table-hover searchableexpense-table">
                                <thead>
                                    <tr>
                                        <th width="30">#</th>
                                        <th width="30">Trans</th>
                                        <th width="30">Date</th>
                                        <th width="30">Time</th>
                                        <th width="100">Account</th>
                                        <th width="400">Narration</th>
                                        <th width="30">Debit</th>
                                        <th width="30">Credit</th>
                                        <th width="30">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 1; 
                                    $runningBalance = $totalExpenseOpening;
                                    @endphp
                                    <tr>
                                    <td></td>
                                    <td></td> 
                                    <td></td> 
                                    <td></td> 
                                    <td></td> 
                                    <td>Opening Balance</td> 
                                    <td></td> 
                                    <td></td>
                                    <td>{{ number_format($totalExpenseOpening, 2) }}</td> 
                                    </tr>
                                    @foreach($matchingExpenseAccounts as $grn)
                                     @php
                                     $runningBalance = $runningBalance + ($grn->debit ?? 0) - ($grn->vendor_net_amount ?? 0);
                                     @endphp
                                        <tr class="user-row" id="grn-{{ $grn->id }}">
                                            <td>{{ $counter }}</td>
                                            <td>#{{ $grn->id }}</td>
                                            <td style="white-space: nowrap">{{ $grn->created_at->format('d-m-Y') }}</td>
                                            <td>{{ $grn->created_at->format('H:i') }}</td>
                                            <td>{{ $grn->vendorAccount->sub_head_name }}</td>
                                            <td>{{ $grn->custom_narration }}</td>
                                            <td>{{ number_format($grn->debit ?? 0, 2) }}</td> 
                                            <td>{{ number_format($grn->vendor_net_amount ?? 0, 2) }}</td>
                                            <td>{{ number_format($runningBalance, 2) }}</td>

                                            
                                        </tr>
                                        @php $counter++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
       </div>
       @endif


@if (!empty($matchingLiabilityAccounts) && count($matchingLiabilityAccounts) > 0)
       <div class="container" style="margin-top: -25px; max-width: 100%; width: calc(100% - 40px);">
    <div class="page-inner">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <h1 class="mx-3 list text-center mt-3">Liability Accounts</h1>

                    <div class="card-body">
                         <div class="mb-3 mx-3">
                         <input type="text" id="customSearchInputliability" class="form-control" placeholder="Search Expense Records...">
                         </div>
                        <div class="table-responsive">
                            <table class="display table table-striped table-hover searchableliability-table">
                                <thead>
                                    <tr>
                                        <th width="30">#</th>
                                        <th width="30">Trans</th>
                                        <th width="30">Date</th>
                                        <th width="30">Time</th>
                                        <th width="100">Account</th>
                                        <th width="400">Narration</th>
                                        <th width="30">Debit</th>
                                        <th width="30">Credit</th>
                                        <th width="30">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 1; 
                                    $runningBalance = $totalliabilityOpening;
                                    @endphp
                                    <tr>
                                    <td></td>
                                    <td></td> 
                                    <td></td> 
                                    <td></td> 
                                    <td></td> 
                                    <td>Opening Balance</td> 
                                    <td></td> 
                                    <td></td>
                                    <td>{{ number_format($totalliabilityOpening, 2) }}</td> 
                                    </tr>
                                    @foreach($matchingLiabilityAccounts as $grn)
                                     @php
                                     $runningBalance = $runningBalance - ($grn->debit ?? 0) + ($grn->vendor_net_amount ?? 0);
                                     @endphp
                                        <tr class="user-row" id="grn-{{ $grn->id }}">
                                            <td>{{ $counter }}</td>
                                            <td>#{{ $grn->id }}</td>
                                            <td style="white-space: nowrap">{{ $grn->created_at->format('d-m-Y') }}</td>
                                            <td>{{ $grn->created_at->format('H:i') }}</td>
                                            <td>{{ $grn->vendorAccount->sub_head_name }}</td>
                                            <td>{{ $grn->custom_narration }}</td>
                                            <td>{{ number_format($grn->debit ?? 0, 2) }}</td> 
                                            <td>{{ number_format($grn->vendor_net_amount ?? 0, 2) }}</td>
                                            <td>{{ number_format($runningBalance, 2) }}</td>

                                            
                                        </tr>
                                        @php $counter++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
       </div>
       @endif


     @if (!empty($matchingRevenueAccounts) && count($matchingRevenueAccounts) > 0)
       <div class="container" style="margin-top: -25px; max-width: 100%; width: calc(100% - 40px);">
    <div class="page-inner">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <h1 class="mx-3 list text-center mt-3">Revenue Accounts</h1>

                    <div class="card-body">
                         <div class="mb-3 mx-3">
                         <input type="text" id="customSearchInputrevenue" class="form-control" placeholder="Search Revenue Records...">
                         </div>
                        <div class="table-responsive">
                            <table class="display table table-striped table-hover searchablerevenue-table">
                                <thead>
                                    <tr>
                                        <th width="30">#</th>
                                        <th width="30">Trans</th>
                                        <th width="30">Date</th>
                                        <th width="30">Time</th>
                                        <th width="100">Account</th>
                                        <th width="400">Narration</th>
                                        <th width="30">Debit</th>
                                        <th width="30">Credit</th>
                                        <th width="30">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 1; 
                                    $runningBalance = $totalrevenueOpening;
                                    @endphp
                                    <tr>
                                    <td></td>
                                    <td></td> 
                                    <td></td> 
                                    <td></td> 
                                    <td></td> 
                                    <td>Opening Balance</td> 
                                    <td></td> 
                                    <td></td>
                                    <td>{{ number_format($totalrevenueOpening, 2) }}</td> 
                                    </tr>
                                    @foreach($matchingRevenueAccounts as $grn)
                                     @php
                                     $runningBalance = $runningBalance - ($grn->debit ?? 0) + ($grn->vendor_net_amount ?? 0);
                                     @endphp
                                        <tr class="user-row" id="grn-{{ $grn->id }}">
                                            <td>{{ $counter }}</td>
                                            <td>#{{ $grn->id }}</td>
                                            <td style="white-space: nowrap">{{ $grn->created_at->format('d-m-Y') }}</td>
                                            <td>{{ $grn->created_at->format('H:i') }}</td>
                                            <td>{{ $grn->vendorAccount->sub_head_name }}</td>
                                            <td>{{ $grn->custom_narration }}</td>
                                            <td>{{ number_format($grn->debit ?? 0, 2) }}</td> 
                                            <td>{{ number_format($grn->vendor_net_amount ?? 0, 2) }}</td>
                                            <td>{{ number_format($runningBalance, 2) }}</td>

                                            
                                        </tr>
                                        @php $counter++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    </div>
    @endif

 @if (!empty($matchingEquityAccounts) && count($matchingEquityAccounts) > 0)
    <div class="container" style="margin-top: -25px; max-width: 100%; width: calc(100% - 40px);">
    <div class="page-inner">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <h1 class="mx-3 list text-center mt-3">Equity Accounts</h1>

                    <div class="card-body">
                         <div class="mb-3 mx-3">
                         <input type="text" id="customSearchInputequity" class="form-control" placeholder="Search Equity Records...">
                         </div>
                        <div class="table-responsive">
                            <table class="display table table-striped table-hover searchableequity-table">
                                <thead>
                                    <tr>
                                        <th width="30">#</th>
                                        <th width="30">Trans</th>
                                        <th width="30">Date</th>
                                        <th width="30">Time</th>
                                        <th width="100">Account</th>
                                        <th width="400">Narration</th>
                                        <th width="30">Debit</th>
                                        <th width="30">Credit</th>
                                        <th width="30">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 1; 
                                    $runningBalance = $totalequityOpening;
                                    @endphp
                                    <tr>
                                    <td></td>
                                    <td></td> 
                                    <td></td> 
                                    <td></td> 
                                    <td></td> 
                                    <td>Opening Balance</td> 
                                    <td></td> 
                                    <td></td>
                                    <td>{{ number_format($totalequityOpening, 2) }}</td> 
                                    </tr>
                                    @foreach($matchingEquityAccounts as $grn)
                                     @php
                                     $runningBalance = $runningBalance - ($grn->debit ?? 0) + ($grn->vendor_net_amount ?? 0);
                                     @endphp
                                        <tr class="user-row" id="grn-{{ $grn->id }}">
                                            <td>{{ $counter }}</td>
                                            <td>#{{ $grn->id }}</td>
                                            <td style="white-space: nowrap">{{ $grn->created_at->format('d-m-Y') }}</td>
                                            <td>{{ $grn->created_at->format('H:i') }}</td>
                                            <td>{{ $grn->vendorAccount->sub_head_name }}</td>
                                            <td>{{ $grn->custom_narration }}</td>
                                            <td>{{ number_format($grn->debit ?? 0, 2) }}</td> 
                                            <td>{{ number_format($grn->vendor_net_amount ?? 0, 2) }}</td>
                                            <td>{{ number_format($runningBalance, 2) }}</td>

                                            
                                        </tr>
                                        @php $counter++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    </div>
    @endif


     

        @include('adminpages.footer')
     
   


    @include('adminpages.js')
    @include('adminpages.ajax')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>


   <script>
$(document).ready(function() {
  const tableClasses = [
    '.searchable-table',
    '.searchableexpense-table',
    '.searchableliability-table',
    '.searchablerevenue-table',
    '.searchableequity-table'
  ];

  function getHeadings() {
    let headings = [];
    $('.list').each(function() {
      headings.push($(this).text().trim());
    });
    return headings;
  }

  function getFormattedDate() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return now.toLocaleDateString(undefined, options);
  }

  $('.print-tables').click(function() {
    const headings = getHeadings();
    let printContent = '';

    printContent += `<h1 style="text-align:center; font-size: 28px; margin-bottom: 0;">General Ledger</h1>`;
    printContent += `<p style="text-align:center; font-size: 18px; margin-top: 4px; margin-bottom: 20px; color: #555;">${getFormattedDate()}</p>`;

    tableClasses.forEach((cls, idx) => {
      const table = $(cls).first(); 

      if (table.length) {
        printContent += `<h2>${headings[idx] || 'Table ' + (idx + 1)}</h2>`;
        printContent += table.prop('outerHTML');
        printContent += '<br>';
      }
    });

    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print Tables</title>');
    printWindow.document.write(`
      <style>
        table {width: 100%; border-collapse: collapse;}
        table, th, td {border: 1px solid black;}
        th, td {padding: 6px; text-align: left;}
        h1 {margin-top: 10px; margin-bottom: 0;}
        h2 {margin-top: 20px;}
        p {margin: 0;}
      </style>`);
    printWindow.document.write('</head><body>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
  });

  $('.export-pdf-tables').click(function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const headings = getHeadings();
    let yPos = 20;

    doc.setFontSize(22);
    const pageWidth = doc.internal.pageSize.getWidth();
    const mainTitle = "General Ledger";
    const titleWidth = doc.getTextWidth(mainTitle);
    doc.text(mainTitle, (pageWidth - titleWidth) / 2, yPos);

    yPos += 10;

    doc.setFontSize(14);
    const dateStr = getFormattedDate();
    const dateWidth = doc.getTextWidth(dateStr);
    doc.setTextColor(100);
    doc.text(dateStr, (pageWidth - dateWidth) / 2, yPos);

    yPos += 15;

    function extractTableData(table) {
      const headers = [];
      const rows = [];

      table.find('thead tr th').each(function() {
        headers.push($(this).text().trim());
      });

      table.find('tbody tr').each(function() {
        const row = [];
        $(this).find('td').each(function() {
          row.push($(this).text().trim());
        });
        rows.push(row);
      });

      return { headers, rows };
    }

    tableClasses.forEach((cls, idx) => {
      const table = $(cls).first();
      if (!table.length) return;

      doc.setFontSize(14);
      doc.setTextColor(0);
      doc.text(headings[idx] || `Table ${idx + 1}`, 14, yPos);
      yPos += 8;

      const { headers, rows } = extractTableData(table);

      doc.autoTable({
        startY: yPos,
        head: [headers],
        body: rows,
        theme: 'grid',
        styles: { fontSize: 10 },
        margin: { left: 14, right: 14 },
        didDrawPage: (data) => {
          yPos = data.cursor.y + 10;
        }
      });

      if (idx < tableClasses.length - 1 && yPos > 250) {
        doc.addPage();
        yPos = 20;
      }
    });

    doc.save('tables.pdf');
  });
});
</script>



<script>
document.addEventListener("DOMContentLoaded", function () {

    const employeeSelect = document.getElementById("employee_id");
    const subHeadDropdown = document.getElementById("sub_head_name");
    const subChildDropdown = document.getElementById("sub_child");

    // When employee/account type changes
    employeeSelect.addEventListener("change", function () {
        const selectedAccount = this.value;

        subHeadDropdown.innerHTML = '<option value="">Select Sub Head</option>';
        subChildDropdown.innerHTML = '<option value="">Select Sub Child</option>';

        if (selectedAccount) {
            fetch(`/api/subheads/by-head/${encodeURIComponent(selectedAccount)}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(subHead => {
                        const option = document.createElement("option");
                        option.value = subHead;
                        option.text = subHead;
                        subHeadDropdown.appendChild(option);
                    });
                });
        }
    });

    // When sub head changes
    subHeadDropdown.addEventListener("change", function () {
        const selectedSubHead = this.value.trim();
        subChildDropdown.innerHTML = '<option value="">Select Sub Child</option>';

        if (!selectedSubHead) return;

        const fetchUrl = `/sub-heads/${encodeURIComponent(encodeURIComponent(selectedSubHead))}`;

        fetch(fetchUrl)
            .then(res => res.json())
            .then(data => {
                data.forEach(subChild => {
                    const option = document.createElement("option");
                    option.value = subChild;
                    option.text = subChild;
                    subChildDropdown.appendChild(option);
                });
            })
            .catch(err => console.error("Fetch error:", err));
    });
});

</script>


    <script>
$(document).ready(function() {
    var table = $('.searchable-table').DataTable({
        "dom": 'rt', 
        "pageLength": 900000
    });

    $('#customSearchInput').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>

 <script>
$(document).ready(function() {
    var table = $('.searchableexpense-table').DataTable({
        "dom": 'rt', 
        "pageLength": 900000
    });

    $('#customSearchInputexpense').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>

<script>
$(document).ready(function() {
    var table = $('.searchableequity-table').DataTable({
        "dom": 'rt', 
        "pageLength": 900000
    });

    $('#customSearchInputequity').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>


 <script>
$(document).ready(function() {
    var table = $('.searchableliability-table').DataTable({
        "dom": 'rt', 
        "pageLength": 900000
    });

    $('#customSearchInputliability').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>


 <script>
$(document).ready(function() {
    var table = $('.searchablerevenue-table').DataTable({
        "dom": 'rt', 
        "pageLength": 900000
    });

    $('#customSearchInputrevenue').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>

  
  </body>
</html>
