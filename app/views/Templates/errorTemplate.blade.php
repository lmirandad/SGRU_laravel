<!DOCTYPE html>
<html lang="es">

<head>
	<title>PORTAL 1.0 | ERROR</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/linearicons/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/chartist/css/chartist-custom.css')}}">
    <!-- MAIN CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
    <!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
    <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}">
    <!-- GOOGLE FONTS -->
    <link rel="stylesheet" href="{{asset('assets/css/font-googleapis-familySourceSanPro.css')}}">
    <!-- LINK CON CONTINGENCIA 
    <link href="{{asset('assets/css/font-googleapis-familySourceSanPro.css')}}" rel="stylesheet"> -->
    <!-- ICONS -->
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/apple-icon.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('assets/img/icon_telefonica.png')}}">
    <!-- Javascript -->
    <script src="{{asset('assets/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('assets/jquery.easy-pie-chart/jquery.easypiechart.min.js')}}"></script>
    <script src="{{asset('assets/chartist/js/chartist.min.js')}}"></script>
    <script src="{{asset('assets/js/klorofil-common.js')}}"></script>
    <script type="text/javascript">
        var inside_url = "{{$inside_url}}";
    </script>
</head>

<body>
	<div id="wrapper">
		<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
			@include('layouts.headerLayout', array('user'=>$user))
            @include('layouts.sidebarLayout')            
		</nav>
		<div id="page-wrapper">
        	@yield('content')
        </div>
	</div>
</body>
</html>