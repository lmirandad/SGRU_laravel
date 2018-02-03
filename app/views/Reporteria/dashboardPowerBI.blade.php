@extends('Templates/dashboardTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>DASHBOARD SOLICITUDES y REQUERIMIENTOS</strong></h3>
		<div class="row">
			<div class="col-md-12">
				@if (Session::has('message'))
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<i class="fa fa-check-circle"></i> 
						{{ Session::get('message') }}
					</div>
				@endif
				@if (Session::has('error'))
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<i class="fa fa-times-circle"></i> 
						{{ Session::get('error') }}
					</div>
				@endif
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<iframe width="1200" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMWEzMmFkNmYtMzU0MC00NTlmLWIwOWUtNjRkYWFjZTc3YjcxIiwidCI6Ijk3NDQ2MDBlLTNlMDQtNDkyZS1iYWExLTI1ZWMyNDVjNmYxMCIsImMiOjh9" frameborder="0" allowFullScreen="true"></iframe>
			</div>
		</div>

	</div>		
</div>
@stop