<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link rel="icon" href="{{asset('lite/assets/img/kaiadmin/favicon.ico')}}" type="image/x-icon"/>
    <script src="{{asset('lite/assets/js/plugin/webfont/webfont.min.js')}}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{asset('lite/assets/css/fonts.min.css')}}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <link rel="stylesheet" href="{{asset('lite/assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('lite/assets/css/plugins.min.css')}}" />
    <link rel="stylesheet" href="{{asset('lite/assets/css/kaiadmin.min.css')}}" />
    <link rel="stylesheet" href="{{asset('lite/assets/css/demo.css')}}" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>
      #loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.85);  
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .circle {
      position: absolute;
      border: 3px solid transparent;
      border-top-color: hsl(83, 82%, 53%);
      border-radius: 50%;
      animation: rotate linear infinite;
    }

    .circle.one {
      height: 50px;
      width: 50px;
      animation-duration: 0.85s;
    }

    .circle.two {
      height: 75px;
      width: 75px;
      animation-duration: 0.95s;
    }

    .circle.three {
      height: 100px;
      width: 100px;
      animation-duration: 1.05s;
    }

    @keyframes rotate {
      from {
        transform: rotate(0deg);
      }
      to {
        transform: rotate(360deg);
      }
    }


  .luxury {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;

    background: linear-gradient(45deg,
    #1A2035,
    #1A2035,
    #1A2035,
    #1A2035,
    #1A2035,
    #1A2035,
        #1A2035, 
        #1A2035,  
        #1A2035, 
        #1A2035,
        #1A2035,  
        #1A2035,
        #1A2035,
        #1A2035,
        #1A2035
    );
    background-size: 300% 300%;
    animation: gradient-shift 8s infinite linear;
    color: white;
    border-radius: 5px;
}


@keyframes gradient-shift {
    0% {
        background-position: 0% 0%;
    }
    50% {
        background-position: 100% 100%;
    }
    100% {
        background-position: 0% 0%;
    }
}


    </style>