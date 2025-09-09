<!DOCTYPE html>
<html lang="en">
<head>
   @include('adminpages.css')
   <style>
    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        width: 100%;
        max-width: 800px; 
        animation: slideDown 0.5s ease;
    }

    .modal-dialog {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        margin: 0;
        padding: 0;
    }

    @media (max-width: 767px) {
        .modal-dialog {
            max-width: 90%; 
        }

        .modal-content {
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        .modal-content {
            padding: 10px;
        }
    }

    .image-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 30px; 
        margin-top: 20px; 
        margin-bottom: 20px; 
    }

    .image-container img {
        max-width: 100%;
        height: auto;
        border: 5px solid #ddd; 
        border-radius: 10px; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
    }

    @media (max-width: 767px) {
        .image-container {
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        .image-container {
            padding: 10px;
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
                <div class="image-container">
                    <img src="{{asset('Capture.PNG')}}" alt="Excel File Image">
                </div>
            </div>

            @include('adminpages.footer')
        </div>
    </div>

    @include('adminpages.js')
    @include('adminpages.ajax')
</body>
</html>
