<!DOCTYPE html>
<html lang="en">
<head>
  @include('adminpages.css')
  
</head>
<body>
  <div class="wrapper">
    @include('adminpages.sidebar')

    <div class="main-panel">
      @include('adminpages.header')

      <div class="container" style="margin-top: 6%; max-width: 800px; padding: 20px; background-color: #f9f9f9; border-radius: 8px;">
        <h2 style="font-family: 'Arial', sans-serif; text-align: center; color: #333;">📊 Import Your Excel File</h2>
      
        <div class="upload-container" style="margin-top: 20px; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
          <form id="excelUploadForm" enctype="multipart/form-data" method="POST" style="display: flex; flex-direction: column; align-items: center;">
            @csrf
            <label class="file-label" for="excelFile" style="font-size: 18px; font-weight: bold; color: #555; margin-bottom: 10px;">📁 Choose CSV File</label>
            
            <input type="file" id="excelFile" name="excelFile" accept=".csv" style="padding: 10px; font-size: 16px; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 20px; width: 100%; max-width: 400px;" />
            
            <button type="submit" style="background-color: #4CAF50; color: white; padding: 12px 30px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; transition: background-color 0.3s;">
              Upload
            </button>
          </form>
      
          <div id="excelData" style="margin-top: 20px;"></div>
        </div>
      </div>

      @include('adminpages.footer')
    </div>
  </div>



  @include('adminpages.js')
  @include('adminpages.ajax')
  <script>
   $('#excelUploadForm').on('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
      url: '/import-csv',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function () {
        Swal.fire({
          title: 'Uploading...',
          text: 'Please wait while your file is being imported.',
          icon: 'info',
          showConfirmButton: false,
          allowOutsideClick: false,
        });
      },
      success: function (response) {
        Swal.fire({
          title: 'Success!',
          text: 'CSV imported successfully!',
          icon: 'success',
          confirmButtonText: 'OK', 
          timer: 2000,
          showConfirmButton: true,
        }).then(() => {
          $('#excelUploadForm')[0].reset(); 
        });
      },
      error: function (xhr) {
        let message = xhr.responseJSON?.message || 'Something went wrong!';
        Swal.fire({
          title: 'Error!',
          text: message,
          icon: 'error',
          confirmButtonText: 'OK', 
          showConfirmButton: true,
        });
      }
    });
  });
  </script>
</body>
</html>
