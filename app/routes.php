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
	Route::get('/principal','MenuPrincipalController@home');
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
	Route::get('/listar_herramientas','HerramientaController@listar_herramientas');
	Route::get('/buscar_herramientas','HerramientaController@buscar_herramientas');
	Route::get('/crear_herramienta','HerramientaController@crear_herramienta');
	Route::post('/submit_crear_herramienta','HerramientaController@submit_crear_herramienta');	
	Route::get('/editar_herramienta/{id}','HerramientaController@editar_herramienta');
	Route::post('/submit_editar_herramienta','HerramientaController@submit_editar_herramienta');
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
});

/*CANALES*/
Route::group(array('prefix'=>'canales', 'before'=>'auth'),function(){
	Route::get('/crear_canal','CanalController@crear_canal');
	Route::post('/submit_crear_canal','CanalController@submit_crear_canal');	
	Route::get('/editar_canal/{id}','CanalController@editar_canal');
	Route::post('/submit_editar_canal','CanalController@submit_editar_canal');
	Route::get('/buscar_canales','CanalController@buscar_canales');
	Route::get('/mostrar_canal/{id}','CanalController@mostrar_canal');
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
});




/*SOLICITUDES*/
Route::group(array('prefix'=>'solicitudes', 'before'=>'auth'),function(){
	Route::get('/cargar_solicitudes','SolicitudController@cargar_solicitudes');
	Route::get('/listar_solicitudes','SolicitudController@listar_solicitudes');
});

/*TIPOS_SOLICITUD*/
Route::group(array('prefix'=>'tipos_solicitudes', 'before'=>'auth'),function(){
	Route::get('/ver_acciones_herramienta_usuario/{id}','TipoSolicitudController@ver_acciones_herramienta');
	Route::post('/eliminar_tipo_solicitud','TipoSolicitudController@eliminar_acciones_herramienta');
});

