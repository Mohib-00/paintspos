<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Document</title>
   <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<!-- Opening Modal -->
<div class="modal fade" id="openingModal" tabindex="-1" role="dialog" aria-labelledby="openingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="openingForm">
        @csrf
        <input type="hidden" name="account_id" id="accountId">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Opening Amount</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <label for="openingAmount">Opening Amount</label>
            <input type="number" min="0" step="1" name="opening" id="openingAmount" class="form-control" required>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  
  
   
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


<script>
  document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll(".export-excel").forEach(button => {
          button.addEventListener("click", function () {
              const table = document.querySelector(".table"); 
              if (!table) {
                  alert("No table found!");
                  return;
              }
  
              const workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
              XLSX.writeFile(workbook, "table_export.xlsx");
          });
      });
  });
  </script>
  
   
<script>
    $(document).on('click', '.open-opening-modal', function () {
      const accountId = $(this).data('account-id');
      const opening = $(this).data('opening');
  
      $('#accountId').val(accountId);
      $('#openingAmount').val(opening);
      $('#openingModal').modal('show');
    });
  
    $('#openingForm').submit(function (e) {
      e.preventDefault();
  
      const formData = $(this).serialize();
      const accountId = $('#accountId').val();
      const newOpening = $('#openingAmount').val();
  
      $.ajax({
        url: '/update-opening',
        method: 'POST',
        data: formData,
        success: function (response) {
            Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: response.message,
            confirmButtonText: 'OK'
         });

  
          $('#openingModal').modal('hide');
  
          $('#opening-value-' + response.account_id).text(response.new_opening);
        },
        error: function () {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong while updating.',
          });
        }
      });
    });
  </script>
  


<script>
//register
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    
    $('#register').on('click', function () {
        Register();
    });

     
    $('#registrationForm').on('keypress', function (e) {
        if (e.which === 13) {  
            e.preventDefault(); 
            Register();
        }
    });

    function Register() {
    $('.text-danger').text('');  

    var formData = {
        name: $('#name').val(),
        email: $('#email').val(),
        password: $('#password').val(),
        confirmPassword: $('#confirmPassword').val()
    };

    var valid = true;

    
    if (!formData.name) {
        $('#nameError').text('The name field is required.');
        valid = false;
    }

    
    if (!formData.email) {
        $('#emailError').text('The email field is required.');
        valid = false;
    }

    
    if (!formData.password) {
        $('#passwordError').text('The password field is required.');
        valid = false;
    } else if (formData.password.length < 8) {
        $('#passwordError').text('Password must be at least 8 characters long.');
        valid = false;
    }

    
    if (!formData.confirmPassword) {
        $('#confirmPasswordError').text('The confirm password field is required.');
        valid = false;
    } else if (formData.password !== formData.confirmPassword) {
        $('#confirmPasswordError').text('Passwords do not match.');
        valid = false;
    }

    if (!valid) {
        return;  
    }

    
    $.ajax({
        url: '/register', 
        type: 'POST',
        data: formData,
        success: function (response) {
            if (response.status) {
                $('#registrationForm')[0].reset();   
                window.location.href = '/login';
            } else {
                if (response.errors) {
                    $.each(response.errors, function (key, error) {
                        $('#' + key + 'Error').text(error[0]);   
                    });
                }
            }
        },
        error: function (xhr) {
             
            if (xhr.status === 401) {
                var response = xhr.responseJSON;
                if (response) {
                    console.error('Registration Failed', response);
                    $('#emailError').text('The email has already been taken');
                } else {
                    $('#emailError').text('The email has already been taken');
                }
            } else {
                $('#emailError').text('The email has already been taken');
            }
        }
    });
}


    
    //login
    $('#login').on('click', function (e) {
        e.preventDefault();
        Login();
    });

    
    $('#loginForm').on('keypress', function (e) {
        if (e.which === 13) {  
            e.preventDefault(); 
            Login();
        }
    });

    function Login() {
    $('.text-danger').text('');  

    var formData = {
        email: $('#loginEmail').val(),
        password: $('#loginPassword').val()
    };

    var valid = true;

    
    if (!formData.email) {
        $('#loginEmailError').text('The email field is required.');
        valid = false;
    }

    if (!formData.password) {
        $('#loginPasswordError').text('The password field is required.');
        valid = false;
    }

    if (!valid) {
        return;  
    }

    $.ajax({
        url: '/login',
        type: 'POST',
        data: formData,
        success: function (response) {
            if (response.status) {
                localStorage.setItem('token', response.token); 
                $('meta[name="csrf-token"]').attr('content', response.csrfToken);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': response.csrfToken
                    }
                });

              
                var url = response.userType === '1' ? '/admin' : '/'; 
                window.location.href = url;
            } else {
                
                if (response.errors) {
                   
                    if (response.errors.email) {
                        $('#loginEmailError').text(response.errors.email[0]);   
                    }
                    if (response.errors.password) {
                        $('#loginPasswordError').text(response.errors.password[0]);   
                    }
                } else {
                    
                    $('#loginEmailError').text(response.message); 
                    $('#loginPasswordError').text(response.message); 
                }
            }
        },
        error: function (xhr) {
            
            if (xhr.status === 401) {
                var response = xhr.responseJSON;
                if (response.errors) {
                    
                    if (response.errors.email) {
                        $('#loginEmailError').text(response.errors.email[0]);
                    } 
                    if (response.errors.password) {
                        $('#loginPasswordError').text(response.errors.password[0]);
                    }
                } else {
                    $('#loginEmailError').text('Invalid credentials');
                    $('#loginPasswordError').text('Invalid credentials');
                }
            }
        }
    });
}

});
     
//logout
$(document).ready(function () {
       
       $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });

     
    $('.logout').on('click', function () {
   
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    $.ajax({
        url: '/logout',
        type: 'POST',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        
        success: function (response) {
            if (response.status) {
                localStorage.removeItem('token');
                window.location.href = '/';

                
                $.ajax({
                    url: '/',
                    type: 'GET',
                    success: function (content) {
                        $('body').html(content);
                    },
                    error: function () {
                        alert('Failed to load content.');
                    }
                });
            } else {
                alert('Logout failed. Please try again.');
            }
        },
        error: function (xhr) {
            console.error(xhr);
            alert('An error occurred while logging out.');
        }
    });
});

$(document).ready(function() {
  
  $('.logoutuser').click(function(e) {
      e.preventDefault();  

       
      $.ajax({
          url: '/logoutuser',   
          type: 'POST',
          headers: {
              'Authorization': 'Bearer ' + localStorage.getItem('token') 
          },
          success: function(response) {
              
              if (response.status) {
                  localStorage.removeItem('token');
                  window.location.href ='/';
              }
          },
          error: function(xhr, status, error) {
               alert('There was an error logging out.');
          }
      });
  });
});
    
});



//to open login page
$(document).ready(function() {
    $('.signIn').on('click', function() {
        $.ajax({
            url: '/login',
            method: 'GET',
            success: function(response) {
                window.location.href = '/login';
            },
            error: function(xhr) {
                alert('Error: ' + xhr.statusText);
            }
        });
    });
});

//to open register page
$(document).ready(function() {
    $('.signUp').on('click', function() {
        $.ajax({
            url: '/register',
            method: 'GET',
            success: function(response) {
                window.location.href = '/register';
            },
            error: function(xhr) {
                alert('Error: ' + xhr.statusText);
            }
        });
    });
});

//to open frontend user page
$(document).ready(function() {
    $('.userpage').on('click', function() {
        $.ajax({
            url: '/',
            method: 'GET',
            success: function(response) {
                window.location.href = '/';
            },
            error: function(xhr) {
                alert('Error: ' + xhr.statusText);
            }
        });
    });
});
 
 
//to chnage password
$(document).on('click', '#submitpassword', function(e) {
 
 $('#passwordError').text('');
 $('#confirmPasswordError').text('');
 $('#message').html('');

 const password = document.getElementById('password').value;
 const confirmPassword = document.getElementById('confirm_password').value;

 $.ajax({
     url: '/changePassword',
     type: 'POST',
     data: {
         password: password,
         password_confirmation: confirmPassword
     },
     headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     },
     success: function (response) {
         Swal.fire({
             icon: 'success',
             title: 'Success',
             text: response.message,
             confirmButtonText: 'OK'
         });
         $('#changePasswordForm')[0].reset();  
     },
     error: function (xhr) {
         if (xhr.status === 422) {
             const errors = xhr.responseJSON.errors;
             if (errors.password) {
                 $('#passwordError').text(errors.password[0]);
             }
             if (errors.password_confirmation) {
                 $('#confirmPasswordError').text(errors.password_confirmation[0]);
             }
         } else {
             Swal.fire({
                 icon: 'error',
                 title: 'Error',
                 text: 'An error occurred. Please try again.',
                 confirmButtonText: 'OK'
             });
         }
     }
 });
});
</script>
<script>
    document.addEventListener("keydown", function(event) {
        if (event.key === "Backspace") {
            const tag = event.target.tagName.toLowerCase();
            if (tag !== 'input' && tag !== 'textarea' && !event.target.isContentEditable) {
                event.preventDefault();
                localStorage.setItem("reloadAfterBack", "true");
                window.history.back(); 
            }
        }
    });

    window.addEventListener("popstate", function() {
        if (localStorage.getItem("reloadAfterBack") === "true") {
            localStorage.removeItem("reloadAfterBack");
            location.reload();
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        if (localStorage.getItem("reloadAfterBack") === "true") {
            localStorage.removeItem("reloadAfterBack");
            location.reload();
        }
    });
</script>


<script>
    function createLoader() {
       const loader = document.createElement('div');
       loader.id = 'loader';
       loader.style.position = 'fixed';
       loader.style.top = '0';
       loader.style.left = '0';
       loader.style.width = '100%';
       loader.style.height = '100%';
       loader.style.backgroundColor = 'rgba(128, 128, 128, 0.6)';
       loader.style.display = 'flex';
       loader.style.alignItems = 'center';
       loader.style.justifyContent = 'center';
       loader.style.zIndex = '9999';
   
       const spinner = document.createElement('div');
       spinner.style.border = '6px solid #f3f3f3';
       spinner.style.borderTop = '6px solid #3498db';
       spinner.style.borderRadius = '50%';
       spinner.style.width = '50px';
       spinner.style.height = '50px';
       spinner.style.animation = 'spin 0.8s linear infinite';
   
       const style = document.createElement('style');
       style.innerHTML = `
           @keyframes spin {
               0% { transform: rotate(0deg); }
               100% { transform: rotate(360deg); }
           }
       `;
       document.head.appendChild(style);
   
       loader.appendChild(spinner);
       document.body.appendChild(loader);
   }
   
   function loadPage(url, pushStateUrl) {
       createLoader();
       fetch(url)
           .then(response => response.text())
           .then(html => {
               document.open();
               document.write(html);
               document.close();
               window.history.pushState({}, '', pushStateUrl);
           })
           .catch(error => {
               console.error('Error loading page:', error);
           })
           .finally(() => {
               const existingLoader = document.getElementById('loader');
               if (existingLoader) {
                   existingLoader.remove();
               }
           });
   }
   
   function loadUsersPage() {
       loadPage('/admin/users', '/admin/users');
   }

   function loadvoucherPage() {
       loadPage('/admin/add_voucher', '/admin/add_voucher');
   }
    function loadjvvoucherPage() {
       loadPage('/admin/jv_voucher', '/admin/jv_voucher');
   }

   function loadvoucher() {
       loadPage('/admin/voucher', '/admin/voucher');
   }

   function loadsalelistPage() {
       loadPage('/admin/sale_list', '/admin/sale_list');
   }
   
   function loadHomePage() {
       loadPage('/admin', '/admin/');
   }
   
   function loadProfilePage() {
       loadPage('/admin/admin_profile', '/admin/admin_profile');
   }
   
   function loadAddUserPage() {
       loadPage('/admin/add_user', '/admin/add_user');
   }

   function loadVendorsPage() {
       loadPage('/admin/add_vendor', '/admin/add_vendor');
   }

   function loadAreaPage() {
       loadPage('/admin/area', '/admin/area');
   }

   function loadCustomerPage() {
       loadPage('/admin/customer_list', '/admin/customer_list');
   }

   function loadAddCustomerPage() {
       loadPage('/admin/add_customer', '/admin/add_customer');
   }

   function loadblockedclientPage() {
       loadPage('/admin/blocked_client_list', '/admin/blocked_client_list');
   }
   function loadEmployeesPage() {
       loadPage('/admin/employees_list', '/admin/employees_list');
   }
   function loadEmployeePage() {
       loadPage('/admin/add_employee', '/admin/add_employee');
   }
   function loadEmployeleavePage() {
       loadPage('/admin/employees_leave', '/admin/employees_leave');
   }

   function loadAttendancePage() {
       loadPage('/admin/employee_attendance', '/admin/employee_attendance');
   }

   function loadAttendanceReportPage() {
       loadPage('/admin/employee_attendance_report', '/admin/employee_attendance_report');
   }
  
   function loadCompanyPage() {
       loadPage('/admin/company_list', '/admin/company_list');
   }
  
   function loadCategoryPage() {
       loadPage('/admin/category_list', '/admin/category_list');
   }

   function loadsubPage() {
       loadPage('/admin/subcategory_list', '/admin/subcategory_list');
   }

   function loadProductPage() {
       loadPage('/admin/products_list', '/admin/products_list');
   }

   function loadpricePage() {
       loadPage('/admin/product_price_list', '/admin/product_price_list');
   }

   function loadimportPage() {
       loadPage('/admin/product_import', '/admin/product_import');
   }

   function loadpurchasePage() {
       loadPage('/admin/purchase_list', '/admin/purchase_list');
   }

   function loadaddpurchasePage() {
       loadPage('/admin/purchase', '/admin/purchase');
   }

   function loadgrnPage() {
       loadPage('/admin/GRN', '/admin/GRN');
   }

   function loadaccountPage() {
       loadPage('/admin/chart_of_account', '/admin/chart_of_account');
   }

   function loadaddaccountPage() {
       loadPage('/admin/add_account', '/admin/add_account');
   }

   function loadpayemntPage() {
       loadPage('/admin/payment', '/admin/payment');
   }

   function loadposPage() {
       loadPage('/admin/POS', '/admin/POS');
   }

    function loadalertsPage() {
       loadPage('/admin/alerts', '/admin/alerts');
   }

      function loadalertslistPage() {
       loadPage('/admin/alerts_list', '/admin/alerts_list');
   }

   function loadpos2Page() {
       loadPage('/admin/POS_2', '/admin/POS_2');
   }

   function loadassetchildPage(headName) {
    loadPage('/admin/assets_child/' + headName, '/admin/assets_child/' + headName);
}

function loadliabilitychildPage(headName) {
    loadPage('/admin/liability_child/' + headName, '/admin/liability_child/' + headName);
}

function loadRevenuePage(headName) {
    loadPage('/admin/revenue_child/' + headName, '/admin/revenue_child/' + headName);
}

function loadequityPage(headName) {
    loadPage('/admin/equity_child/' + headName, '/admin/equity_child/' + headName);
}

function loadexpensePage(headName) {
    loadPage('/admin/expense_child/' + headName, '/admin/expense_child/' + headName);
}

function loadvendorPage(headName) {
    loadPage('/admin/vendor_account/' + headName, '/admin/vendor_account/' + headName);
}

function loadcustomersaccountPage(headName) {
    const url = '/admin/customers_account/' + encodeURIComponent(headName);
    loadPage(url, url);
}



   function loadeditpurchasePage(element) {
    const purchaseId = element.getAttribute('data-purchase-id');
    loadPage(`/admin/edit_purchase_list/${purchaseId}`, `/admin/edit_purchase_list/${purchaseId}`);
}

function loadpurchaseinvoicePage(element) {
    const purchaseId = element.getAttribute('data-purchase-id');
    loadPage(`/admin/purchase_invoice/${purchaseId}`, `/admin/purchase_invoice/${purchaseId}`);
}

function loadformatPage() {
       loadPage('/admin/format', '/admin/format');
   }

   function loadsalary() {
       loadPage('/admin/salary', '/admin/salary');
   }

    function loaddesignationPage() {
       loadPage('/admin/designation', '/admin/designation');
   }

     function loadsalereport() {
       loadPage('/admin/sale_report', '/admin/sale_report');
   }

 function loadstockeport() {
       loadPage('/admin/stock_report', '/admin/stock_report');
   }

   function loadsaleinvoicePage(element) {
    const saleId = element.getAttribute('data-sale-id');
    loadPage(`/admin/sale_invoice/${saleId}`, `/admin/sale_invoice/${saleId}`);
}

function loadsaleprintPage(element) {
    const saleprintId = element.getAttribute('data-sale-id');
    loadPage(`/admin/sale_print_invoice/${saleprintId}`, `/admin/sale_print_invoice/${saleprintId}`);
}


function loadvoucheritemsPage(element) {
    const voucherId = element.getAttribute('data-voucher-id');
    loadPage(`/admin/voucher_items/${voucherId}`, `/admin/voucher_items/${voucherId}`);
}

 function loadeditvoucherPage(element) {
    const editvoucherId = element.getAttribute('data-voucher-id');
    loadPage(`/admin/edit_voucher/${editvoucherId}`, `/admin/edit_voucher/${editvoucherId}`);
}

function loadsaledetailsPage(element) {
    const saledetailsPageId = element.getAttribute('data-saledetailsPage-id');
    loadPage(`/admin/sale_items_detail/${saledetailsPageId}`, `/admin/sale_items_detail/${saledetailsPageId}`);
}


 function loadjveditvoucherPage(element) {
    const editjvvoucherId = element.getAttribute('data-voucher-id');
    loadPage(`/admin/edit_jv_voucher/${editjvvoucherId}`, `/admin/edit_jv_voucher/${editjvvoucherId}`);
}

 function loadcompletejvvoucherPage(element) {
    const editcompletejvoucherId = element.getAttribute('data-voucher-id');
    loadPage(`/admin/complete_jv_voucher/${editcompletejvoucherId}`, `/admin/complete_jv_voucher/${editcompletejvoucherId}`);
}


function loadpaysalry(element) {
    const paySalaryId = element.getAttribute('data-employee-id');
    loadPage(`/admin/pay_salary/${paySalaryId}`, `/admin/pay_salary/${paySalaryId}`);
}


 function loaddayclosereport() {
       loadPage('/admin/day_close_report', '/admin/day_close_report');
   }


    function loadledgerreport() {
       loadPage('/admin/general_ledger', '/admin/general_ledger');
   }


    function loadpaidsalary() {
       loadPage('/admin/payed_salary', '/admin/payed_salary');
   }


    function loadpurchasereturnPage() {
       loadPage('/admin/purchase_return', '/admin/purchase_return');
   }



   function loadeditUser(element) {
    const edituserId = element.getAttribute('data-user-id');
    loadPage(`/admin/edit_user/${edituserId}`, `/admin/edit_user/${edituserId}`);
}

 function loadprofitlossreport() {
       loadPage('/admin/profit_loss_report', '/admin/profit_loss_report');
   }

    function loadbackupPage() {
       loadPage('/admin/backup_reset', '/admin/backup_reset');
   }

     function loadtrialbalancereport() {
       loadPage('/admin/trial_balance', '/admin/trial_balance');
   }

   function loadbalancesheet() {
       loadPage('/admin/balance_sheet', '/admin/balance_sheet');
   }


     function loadcustomerreport() {
       loadPage('/admin/customer_report', '/admin/customer_report');
   }
  
    function loadvendorreport() {
       loadPage('/admin/vendor_report', '/admin/vendor_report');
   }

   function loaddealPage() {
       loadPage('/admin/deal_list', '/admin/deal_list');
   }

   function loadadddealPage() {
       loadPage('/admin/add_deal', '/admin/add_deal');
   }
  

   function loadeditdealPage(element) {
    const dealId = element.getAttribute('data-deal-id');
    loadPage(`/admin/edit_deal_list/${dealId}`, `/admin/edit_deal_list/${dealId}`);
}

 function loadmanufactureCompanyPage() {
       loadPage('/admin/manufacture_company_list', '/admin/manufacture_company_list');
   }


   
   function loadmanufactureCategoryPage() {
       loadPage('/admin/manufacture_category_list', '/admin/manufacture_category_list');
   }


    function loadrawmaterialPage() {
       loadPage('/admin/raw_material_list', '/admin/raw_material_list');
   }


    function loadcreateproductPage() {
       loadPage('/admin/create_product', '/admin/create_product');
   }


     function loadaddmaterialpurchasePage() {
       loadPage('/admin/material_purchase', '/admin/material_purchase');
   }

   function loadpurchaseereport() {
       loadPage('/admin/purchase_report', '/admin/purchase_report');
   }


   function loadVehicleRecordAddPage() {
       loadPage('/admin/vehicle_record_add', '/admin/vehicle_record_add');
   }

   function loadVehiclelistPage() {
       loadPage('/admin/vehicle_list', '/admin/vehicle_list');
   }
    function loadVehiclealertPage() {
       loadPage('/admin/vehicle_alert', '/admin/vehicle_alert');
   }
  
      function loadfinePage() {
       loadPage('/admin/fine', '/admin/fine');
   }
   </script>
  

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      function removeActionColumn(clonedTable) {
        const headerCells = clonedTable.querySelectorAll('thead th');
        let actionIndex = -1;
    
        headerCells.forEach((th, index) => {
          if (th.innerText.trim().toLowerCase() === 'action') {
            actionIndex = index;
          }
        });
    
        if (actionIndex > -1) {
          clonedTable.querySelectorAll('thead tr').forEach(row => {
            row.deleteCell(actionIndex);
          });
    
          clonedTable.querySelectorAll('tbody tr').forEach(row => {
            if (row.cells.length > actionIndex) {
              row.deleteCell(actionIndex);
            }
          });
        }
      }
    
      const printBtn = document.querySelector('.print-table');
      if (printBtn) {
        printBtn.addEventListener('click', function () {
          const table = document.querySelector('.table-striped'); 
          const listTitle = document.querySelector('.list')?.innerText || 'List';
    
          if (!table) {
            alert('Table not found!');
            return;
          }
    
          const clonedTable = table.cloneNode(true);
          removeActionColumn(clonedTable);
    
          const newWin = window.open('', '_blank');
          newWin.document.write(`
            <html>
              <head>
                <title>${listTitle}</title>
                <style>
                  table {
                    border-collapse: collapse;
                    width: 100%;
                  }
                  th, td {
                    border: 1px solid #000;
                    padding: 8px;
                    text-align: left;
                  }
                  img {
                    max-width: 80px;
                    max-height: 80px;
                  }
                  h1 {
                    text-align: center;
                  }
                </style>
              </head>
              <body>
                <h1>${listTitle}</h1>
                ${clonedTable.outerHTML}
              </body>
            </html>
          `);
          newWin.document.close();
          newWin.focus();
          newWin.print();
          newWin.close();
        });
      }
    
      // PDF button
      const pdfBtn = document.querySelector('.export-pdf');
      if (pdfBtn) {
        pdfBtn.addEventListener('click', function () {
          const listTitle = document.querySelector('.list')?.innerText || 'List';
          const table = document.querySelector('.table-striped');
          if (!table) {
            alert("Table not found!");
            return;
          }
    
          const clonedTable = table.cloneNode(true);
          removeActionColumn(clonedTable);
    
          const wrapper = document.createElement('div');
          const heading = document.createElement('h1');
          heading.innerText = listTitle;
          heading.style.textAlign = 'center';
          wrapper.appendChild(heading);
          wrapper.appendChild(clonedTable);
    
          document.body.appendChild(wrapper);
          html2canvas(wrapper).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
    
            pdf.addImage(imgData, 'PNG', 0, 10, pdfWidth, pdfHeight);
            pdf.save(`${listTitle}.pdf`);
            document.body.removeChild(wrapper);
          });
        });
      }
    });
    </script>
    
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
          const fromDateInput = document.getElementById("from_date");
          const toDateInput = document.getElementById("to_date");
      
          fromDateInput.addEventListener("focus", function () {
            this.showPicker && this.showPicker(); 
          });
      
          toDateInput.addEventListener("focus", function () {
            this.showPicker && this.showPicker();
          });
      
          fromDateInput.addEventListener("click", function () {
            this.showPicker && this.showPicker();
          });
      
          toDateInput.addEventListener("click", function () {
            this.showPicker && this.showPicker();
          });
        });
      </script>
      <script>
        $(document).on('click', '.delaccount', function () {
            const accountId = $(this).data('account-id');
            const element = $(this); 
        
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the account permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/delete-account/${accountId}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
                            element.closest('tr').remove(); 
                        },
                        error: function (xhr) {
                            Swal.fire(
                                'Error!',
                                'Failed to delete account.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
        </script>

<script>
    $(document).on('click', '.edit-account-btn', function () {
      const accountId = $(this).data('account-id');
  
      loadPage(`/admin/get_account/${accountId}`, `/admin/get_account/${accountId}`);
  
      $.ajax({
        url: `/admin/get_account/${accountId}`,
        method: 'GET',
        success: function(response) {
          const account = response.account;
          const accounts = response.accounts;
  
          const subHeadName = account.sub_head_name || '';
          const isBracketed = subHeadName.includes('(') && subHeadName.includes(')');
  
          if (isBracketed) {
            $('#modalNoBrackets').modal('hide');
            $('#accountseditform').show();
            $('.hideit').hide();
  
            const $headSelect = $('#head_name');
            $headSelect.empty().append('<option value="">Choose Account</option>');
  
            let addedHeadNames = new Set();
            accounts.forEach(acc => {
              const headName = acc.head_name || '';
              const accountName = acc.account_name || headName;
              const selected = headName === account.head_name ? 'selected' : '';
  
              if (!addedHeadNames.has(headName)) {
                $headSelect.append(
                  `<option value="${headName}" ${selected}>${accountName}</option>`
                );
                addedHeadNames.add(headName);
              }
            });
  
            const match = subHeadName.match(/^([^(]+)\s*\(([^)]+)\)$/);
            if (match) {
              $('#sub_head_name').val(match[1].trim());          
              $('#childsub_head_name').val(match[2].trim());       
            } else {
              $('#sub_head_name').val(subHeadName);
              $('#childsub_head_name').val(account.child_sub_head_name || '');
            }
  
          } else {
            
            $('#modalNoBrackets').modal('show');
  
            const $select = $('#accountSelect');
            $select.empty().append('<option>Choose One</option>');
  
            let added = new Set();
            accounts.forEach(acc => {
              const headName = acc.head_name || '';
              const accountName = acc.account_name || headName;
  
              if (!added.has(headName)) {
                const selected = headName === account.head_name ? 'selected' : '';
                $select.append(`<option value="${headName}" ${selected}>${accountName}</option>`);
                added.add(headName);
              }
            });
  
            $('#subHeadName').val(subHeadName);
          }
        },
        error: function() {
          alert("Failed to load account details.");
        }
      });
    });
  </script>
  
    

 
<script>
$(document).on('click', '#openAddHeadModal', function () {
    const urlParts = window.location.pathname.split('/');
    const accountId = urlParts[urlParts.length - 1];  

    $.ajax({
        url: `/admin/get_account/${accountId}`,
        method: 'GET',
        success: function(response) {
            const account = response.account;
            const accounts = response.accounts;

            const subHeadName = account.sub_head_name || '';
            const isBracketed = /^\(.*\)$/.test(subHeadName);  

            if (!isBracketed) {
                $('#accountSelect').empty().append('<option>Choose One</option>');

                let addedHeadNames = new Set();

                accounts.forEach(acc => {
                    const headName = acc.head_name || '';
                    const accountName = acc.account_name || headName; 
                    const selected = headName === account.head_name ? 'selected' : '';

                    if (!addedHeadNames.has(headName)) {
                        $('#accountSelect').append(
                            `<option value="${headName}" ${selected}>${accountName}</option>`
                        );
                        addedHeadNames.add(headName);  
                    }
                });

                $('#subHeadName').val(subHeadName);

                $('#modalNoBrackets').modal('show');
            }
        },
        error: function() {
            alert("Failed to load account details.");
        }
    });
});

</script>

<script>
    $('#accountseditform').submit(function(e) {
      e.preventDefault();
  
      var accountId = $('#account_id').val();
      var headName = $('#head_name').val();
      var subHeadName = $('#sub_head_name').val();
      var childSubHeadName = $('#childsub_head_name').val();
  
      $.ajax({
        url: '/save-account/' + accountId,
        method: 'PUT',
        data: {
          head_name: headName,
          sub_head_name: subHeadName,
          child_sub_head_name: childSubHeadName,
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: response.message,
            confirmButtonText: 'OK'
          }).then(() => {
            loadaccountPage(); 
          });
        },
        error: function(xhr) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong!',
            confirmButtonText: 'OK'
          });
        }
      });
    });
  </script>
  

<script>
    $('#submitAccount').click(function (e) {
      e.preventDefault();
  
      var accountId = $('#accountId').val(); 
      var headName = $('#accountSelect').val();
      var subHeadName = $('#subHeadName').val();
  
      if (!accountId || !headName || !subHeadName) {
        Swal.fire("Please fill all fields");
        return;
      }
  
      $.ajax({
        url: '/edit-account/' + accountId,
        method: 'POST',
        data: {
          head_name: headName,
          sub_head_name: subHeadName,
          _method: 'PUT', 
          _token: '{{ csrf_token() }}'
        },
        success: function (response) {
          Swal.fire({
            icon: 'success',
            title: 'Updated',
            text: response.message
          }).then(() => {
            $('#myforedit')[0].reset();
            loadaccountPage();
          });
        },
        error: function () {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong!'
          });
        }
      });
    });
  </script>
  
    
    
</body>
    