<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>{{$title}}</title>
<meta
  content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
  name="viewport"
/>
<link
  rel="icon"
  href="{{asset('assets/img/logo.png')}}"
  type="image/x-icon"
/>

<!-- Fonts and icons -->
<script src="{{asset('assets/js/plugin/webfont/webfont.min.js')}}"></script>
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
      urls: ["{{asset('assets/css/fonts.min.css')}}"],
    },
    active: function () {
      sessionStorage.fonts = true;
    },
  });
</script>
<!--Map Box-->
<link href='https://api.mapbox.com/mapbox-gl-js/v3.6.0/mapbox-gl.css' rel='stylesheet' />
<link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.0.0/mapbox-gl-directions.css" />
<!-- CSS Files -->
<link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/css/plugins.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/css/kaiadmin.min.css')}}" />
<link rel="stylesheet" href="/assets/css/loader.css">
<!-- CSS Just for demo purpose, don't include it in your project -->
<link rel="stylesheet" href="{{asset('assets/css/demo.css')}}" />