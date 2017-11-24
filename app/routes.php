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
	/*Route::post('/submit_disable_user','UserController@submit_disable_user');
	Route::post('/submit_enable_user','UserController@submit_enable_user');*/
	
});

/*HERRAMIENTAS*/
Route::group(array('prefix'=>'herramientas', 'before'=>'auth'),function(){
	Route::post('/listar_herramientas_disponibles','HerramientaController@listar_herramientas_disponibles');
	Route::post('/submit_agregar_herramientas','HerramientaController@submit_agregar_herramientas_usuario');
	Route::post('/submit_eliminar_herramienta_usuario','HerramientaController@submit_eliminar_herramienta_usuario');
	Route::get('/listar_herramientas','HerramientaController@listar_herramientas');
	Route::get('/buscar_herramientas','HerramientaController@buscar_herramientas');
});

/*ENTIDADES CANALES SECTORES*/
Route::group(array('prefix'=>'entidades_canales_sectores', 'before'=>'auth'),function(){
	Route::get('/listar/{flag_seleccion}','SectorCanalEntidadController@listar_sectores_canales_entidades');
});

/*SOLICITUDES*/
Route::group(array('prefix'=>'solicitudes', 'before'=>'auth'),function(){
	Route::get('/cargar_solicitudes','SolicitudController@cargar_solicitudes');
	Route::get('/listar_solicitudes','SolicitudController@listar_solicitudes');
});

