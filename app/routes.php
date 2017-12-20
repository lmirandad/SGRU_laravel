<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('Login/login');
});

Route::post('/login', 'LoginController@login');
Route::get('/login', 'LoginController@login_expires');

Route::group(array('before'=>'auth'),function(){
	Route::get('/logout','LoginController@logout');
	Route::get('/principal_admin','MenuPrincipalController@home_admin');
	Route::get('/principal_gestor','MenuPrincipalController@home_gestor');

});

Route::group(array('prefix'=>'principal','before'=>'auth'),function(){
	Route::get('/mostrar_solicitudes_estado/{id}','MenuPrincipalController@mostrar_solicitudes_estado');
	Route::get('/mostrar_solicitudes_estado_usuario/{id}/{idusuario}','MenuPrincipalController@mostrar_solicitudes_estado_usuario');
	Route::get('/buscar_solicitudes_usuario','MenuPrincipalController@buscar_solicitudes_usuario');
	Route::get('/buscar_solicitud_codigo','MenuPrincipalController@buscar_solicitud_codigo');
	Route::get('/resumen_usuarios','MenuPrincipalController@resumen_usuarios');
	Route::get('/resumen_usuarios_mes','MenuPrincipalController@resumen_usuarios_mes');
	
});

/* USUARIOS DEL SISTEMA */
Route::group(array('prefix'=>'usuarios', 'before'=>'auth'),function(){
	Route::get('/listar_usuarios','UserController@listar_usuarios');
	Route::get('/buscar_usuarios','UserController@buscar_usuarios');
	Route::get('/crear_usuario','UserController@crear_usuario');
	Route::post('/submit_crear_usuario','UserController@submit_crear_usuario');	
	Route::get('/cambiar_contrasena','UserController@cambiar_contrasena');
	Route::post('/submit_cambiar_contrasena','UserController@submit_cambiar_contrasena');
	Route::get('/editar_usuario/{id}','UserController@editar_usuario');
	Route::post('/reestablecer_contrasena/{id}','UserController@reestablecer_contrasena');
	Route::post('/submit_editar_usuario','UserController@submit_editar_usuario');
	Route::get('/mostrar_usuario/{id}','UserController@mostrar_usuario');
	Route::get('/mostrar_usuario_sesion/{id}','UserController@mostrar_usuario_actual');
	Route::get('/mostrar_herramientas_usuario/{id}','UserController@mostrar_herramientas_usuario');
	Route::post('/submit_agregar_herramientas','UserController@submit_agregar_herramientas');
	Route::get('/mostrar_sectores_usuario/{id}','UserController@mostrar_sectores_usuario');
	Route::post('/submit_agregar_sectores','UserController@submit_agregar_sectores');		
	Route::post('/submit_habilitar_usuario','UserController@submit_habilitar_usuario');
	Route::post('/submit_inhabilitar_usuario','UserController@submit_inhabilitar_usuario');
	
});

/*HERRAMIENTAS*/
Route::group(array('prefix'=>'herramientas', 'before'=>'auth'),function(){
	Route::post('/listar_herramientas_disponibles','HerramientaController@listar_herramientas_disponibles');
	Route::post('/submit_agregar_herramientas','HerramientaController@submit_agregar_herramientas_usuario');
	Route::post('/submit_eliminar_herramienta_usuario','HerramientaController@submit_eliminar_herramienta_usuario');
	Route::post('/submit_eliminar_herramienta_sector','HerramientaController@submit_eliminar_herramienta_sector');
	Route::get('/listar_herramientas','HerramientaController@listar_herramientas');
	Route::get('/buscar_herramientas','HerramientaController@buscar_herramientas');
	Route::get('/crear_herramienta','HerramientaController@crear_herramienta');
	Route::post('/submit_crear_herramienta','HerramientaController@submit_crear_herramienta');	
	Route::get('/editar_herramienta/{id}','HerramientaController@editar_herramienta');
	Route::post('/submit_editar_herramienta','HerramientaController@submit_editar_herramienta');
	Route::get('/mostrar_herramienta/{id}','HerramientaController@mostrar_herramienta');		
	Route::post('/submit_habilitar_herramienta','HerramientaController@submit_habilitar_herramienta');
	Route::post('/submit_inhabilitar_herramienta','HerramientaController@submit_inhabilitar_herramienta');
	Route::post('/submit_agregar_equivalencia','HerramientaController@submit_agregar_equivalencia');	
});

/*ENTIDADES CANALES SECTORES*/
Route::group(array('prefix'=>'entidades_canales_sectores', 'before'=>'auth'),function(){
	Route::get('/listar/{flag_seleccion}','SectorCanalEntidadController@listar_sectores_canales_entidades');
});

/*SECTORES*/
Route::group(array('prefix'=>'sectores', 'before'=>'auth'),function(){
	Route::post('/submit_eliminar_sector_usuario','SectorController@submit_eliminar_sector_usuario');
	Route::get('/crear_sector','SectorController@crear_sector');
	Route::post('/submit_crear_sector','SectorController@submit_crear_sector');	
	Route::get('/editar_sector/{id}','SectorController@editar_sector');
	Route::post('/submit_editar_sector','SectorController@submit_editar_sector');
	Route::get('/buscar_sectores','SectorController@buscar_sectores');
	Route::get('/mostrar_sector/{id}','SectorController@mostrar_sector');
	Route::post('/submit_habilitar_sector','SectorController@submit_habilitar_sector');
	Route::post('/submit_inhabilitar_sector','SectorController@submit_inhabilitar_sector');
	Route::get('/mostrar_herramientas_sector/{id}','SectorController@mostrar_herramientas_sector');
	Route::post('/submit_agregar_herramientas','SectorController@submit_agregar_herramientas');
	Route::post('/mostrar_canales_herramientas','SectorController@mostrar_canales_herramientas');
	
});

/*CANALES*/
Route::group(array('prefix'=>'canales', 'before'=>'auth'),function(){
	Route::get('/crear_canal','CanalController@crear_canal');
	Route::post('/submit_crear_canal','CanalController@submit_crear_canal');	
	Route::get('/editar_canal/{id}','CanalController@editar_canal');
	Route::post('/submit_editar_canal','CanalController@submit_editar_canal');
	Route::get('/buscar_canales','CanalController@buscar_canales');
	Route::get('/mostrar_canal/{id}','CanalController@mostrar_canal');
	Route::post('/submit_habilitar_canal','CanalController@submit_habilitar_canal');
	Route::post('/submit_inhabilitar_canal','CanalController@submit_inhabilitar_canal');
});

/*ENTIDADES*/
Route::group(array('prefix'=>'entidades', 'before'=>'auth'),function(){
	Route::get('/crear_entidad','EntidadController@crear_entidad');
	Route::post('/submit_crear_entidad','EntidadController@submit_crear_entidad');	
	Route::get('/editar_entidad/{id}','EntidadController@editar_entidad');
	Route::post('/submit_editar_entidad','EntidadController@submit_editar_entidad');
	Route::get('/buscar_entidades','EntidadController@buscar_entidades');
	Route::get('/mostrar_entidad/{id}','EntidadController@mostrar_entidad');
	Route::post('/mostrar_canales','EntidadController@mostrar_canales');
	Route::post('/mostrar_entidades','EntidadController@mostrar_entidades');
	Route::post('/submit_habilitar_entidad','EntidadController@submit_habilitar_entidad');
	Route::post('/submit_inhabilitar_entidad','EntidadController@submit_inhabilitar_entidad');
});


/*SLA's*/
Route::group(array('prefix'=>'slas', 'before'=>'auth'),function(){
	Route::get('/crear_sla/{id}','SlaController@crear_sla');
	Route::post('/submit_crear_sla','SlaController@submit_crear_sla');	
	Route::get('/mostrar_slas/{id}','SlaController@mostrar_slas');
	Route::post('/mostrar_datos','SlaController@mostrar_datos');
	Route::post('/obtener_slas','SlaController@obtener_slas');
	Route::post('/validar_slas','SlaController@validar_slas');
	Route::get('/editar_sla/{id}','SlaController@editar_sla');
	Route::post('/submit_editar_sla','SlaController@submit_editar_sla');	
});

/*SOLICITUDES*/
Route::group(array('prefix'=>'solicitudes', 'before'=>'auth'),function(){
	Route::get('/cargar_solicitudes','SolicitudController@cargar_solicitudes');
	Route::get('/buscar_solicitudes','SolicitudController@buscar_solicitudes');
	Route::get('/listar_solicitudes','SolicitudController@listar_solicitudes');
	Route::post('/cargar_archivo_solicitudes','SolicitudController@cargar_archivo_solicitudes');
	Route::post('/descargar_logs','SolicitudController@descargar_logs');
	Route::get('/mostrar_solicitud/{id}','SolicitudController@mostrar_solicitud');
	Route::post('/obtener_herramientas','SolicitudController@obtener_herramientas');
	Route::post('/mostrar_usuarios_disponibles_reasignacion','SolicitudController@mostrar_usuarios_disponibles_reasignacion');
	Route::post('/submit_reasignar_solicitud','SolicitudController@submit_reasignar_solicitud');
	Route::get('/crear_solicitud','SolicitudController@crear_solicitud');
	Route::post('/submit_crear_solicitud','SolicitudController@submit_crear_solicitud');	
	
});

/*TIPOS_SOLICITUD*/
Route::group(array('prefix'=>'tipos_solicitudes', 'before'=>'auth'),function(){
	Route::get('/ver_acciones_herramienta_usuario/{id}','TipoSolicitudController@ver_acciones_herramienta');
	Route::post('/eliminar_tipo_solicitud','TipoSolicitudController@eliminar_acciones_herramienta');
});

/*HERRAMIENTA EQUIVALENCIA*/
Route::group(array('prefix'=>'herramienta_equivalencia', 'before'=>'auth'),function(){
	Route::post('/eliminar_equivalencia','HerramientaController@eliminar_equivalencia');
});

/*ASIGNACIONES*/
Route::group(array('prefix'=>'asignaciones', 'before'=>'auth'),function(){
	Route::post('/submit_asignacion','AsignacionController@submit_asignacion');
});

/*EQUIVALENCIAS TIPO SOLICITUD*/
Route::group(array('prefix'=>'equivalencias_tipo_solicitud', 'before'=>'auth'),function(){
	Route::get('/listar_equivalencias','EquivalenciaTipoSolicitudController@mostrar_equivalencias_tipo_solicitud');
	Route::post('/mostrar_datos','EquivalenciaTipoSolicitudController@mostrar_equivalencias_ajax');
	Route::post('/submit_eliminar_equivalencia_tipo_solicitud','EquivalenciaTipoSolicitudController@submit_eliminar_equivalencia_tipo_solicitud');
	Route::post('/submit_crear_equivalencia_tipo_solicitud','EquivalenciaTipoSolicitudController@submit_crear_equivalencia_tipo_solicitud');
});

/*FERIADOS*/
Route::group(array('prefix'=>'feriados', 'before'=>'auth'),function(){
	Route::get('/listar_feriados','FeriadoController@listar_feriados');
	Route::post('/submit_crear_feriado','FeriadoController@submit_crear_feriado');
	Route::post('/submit_eliminar_feriado','FeriadoController@submit_eliminar_feriado');
	Route::get('/buscar_feriados','FeriadoController@buscar_feriados');
});