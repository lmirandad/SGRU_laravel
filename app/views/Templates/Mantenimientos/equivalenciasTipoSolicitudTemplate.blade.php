<!doctype html>
<html lang="en">

<head>
	<title>PORTAL 1.0 | EQUIVALENCIAS TIPO SOLICITUD</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	
	<link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">	
	<link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap-datetimepicker.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap-dialog.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}">	
	<link rel="stylesheet" href="{{asset('assets/linearicons/style.css')}}">	
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
	<!-- GOOGLE FONTS -->
	<link rel="stylesheet" href="{{asset('assets/css/font-googleapis-familySourceSanPro.css')}}">
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/apple-icon.png')}}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{asset('assets/img/icon_telefonica.png')}}">	
	<!-- Javascript -->
	<script src="{{asset('assets/jquery/jquery.min.js')}}"></script>
	<script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('assets/bootstrap/js/bootstrap-dialog.min.js')}}"></script>
	<script src="{{asset('assets/bootstrap/js/moment.min.js')}}"></script>		
	<script src="{{asset('assets/bootstrap/js/bootstrap-datetimepicker.min.js')}}"></script>
	<script type="text/javascript">
		var inside_url = "{{$inside_url}}";
	</script>
	<script src="{{asset('assets/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
	<script src="{{asset('assets/jquery.easy-pie-chart/jquery.easypiechart.min.js')}}"></script>
	<script src="{{asset('assets/chartist/js/chartist.min.js')}}"></script>
	<script src="{{asset('assets/js/klorofil-common.js')}}"></script>
	<script src="{{asset('assets/bootstrap/js/locale-es.js')}}"></script>
	<script src="{{asset('js/equivalencia_tipo_solicitud.js')}}"></script>
</head>

<body>
	
	<!-- WRAPPER -->
	<div id="wrapper">
		@include('layouts.headerLayout')
		@include('layouts.sidebarLayout')		
		<!-- MAIN -->
		<div class="main">
			@yield('content')
		</div>
		<!-- END MAIN -->
		<div class="clearfix"></div>
		@include('layouts.footerLayout')		
	</div>
	<!-- END WRAPPER -->
	
	
</body>

</html>
