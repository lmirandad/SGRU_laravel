<!doctype html>
<html lang="en" class="fullscreen-bg">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/linearicons/style.css')}}">
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
	<!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
	<link rel="stylesheet" href="{{asset('assets/css/demo.css')}}">
	<!-- GOOGLE FONTS -->
	<link rel="stylesheet" href="{{asset('assets/css/font-googleapis-familySourceSanPro.css')}}">
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/apple-icon.png')}}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{asset('assets/img/favicon.png')}}">
	<script src="{{asset('assets/jquery/jquery.min.js')}}"></script>
	<script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('assets/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
	<script src="{{asset('assets/jquery.easy-pie-chart/jquery.easypiechart.min.js')}}"></script>
	<script src="{{asset('assets/chartist/js/chartist.min.js')}}"></script>
	<script src="{{asset('assets/js/klorofil-common.js')}}"></script>
</head>

<body>
	<div id="wrapper">
		@yield('content')
	</div>
</body>

</html>