<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	protected $softDelete = true;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	//Query para listar todos los usuarios de los sistemas
	public function scopeListarUsuarios($query)
	{
		$query->withTrashed()
			  ->join('rol','rol.idrol','=','users.idrol')
			  ->select('rol.nombre as nombre_rol','users.*');
		return $query;
	}

	//Query para listar todos los gestores (idrol = 2)
	public function scopeListarGestores($query)
	{
		$query->withTrashed()
			  ->where('users.idrol','=',2)
			  ->select('users.*');
		return $query;	
	}

	//Query para buscar usuarios por nombre usuario (criterio de busqueda :username)
	public function scopeBuscarUsuarioPorUsername($query,$search_username)
	{
		$query->withTrashed()	
			  ->where('users.username','=',"$search_username")		  
			  ->select('users.*');
		return $query;
	}

	//Query para buscar usuarios por id usuario
	public function scopeBuscarUsuarioPorId($query,$search_criteria)
	{
		$query->withTrashed()
			  ->where('users.id','=',$search_criteria);
		
		return $query;
	}

	//Query para buscar usuarios asignados a un determinado sector
	public function scopeBuscarUsuariosPorIdSector($query,$idsector)
	{
		$query->join('usersxsector','usersxsector.iduser','=','users.id')
			  ->join('sector','sector.idsector','=','usersxsector.idsector');
		$query->where('sector.idsector','=',$idsector);
		$query->select('users.*');
		return $query;
	}

	//Query para buscar usuarios por criterios de busqueda (criterios de busqueda: username, tipo de documento, numero de documento, herramienta)
	public function scopeBuscarUsuariosVistaMantenimiento($query,$search_criteria,$search_tipo_documento,$search_documento_identidad,$search_herramienta)
	{
		$query->withTrashed()
			  ->join('rol','rol.idrol','=','users.idrol')			  
			  ->whereNested(function($query) use($search_criteria,$search_documento_identidad,$search_herramienta){
			  		$query->where('users.username','LIKE',"%$search_criteria%")
			  			  ->orWhere('users.nombre','LIKE',"%$search_criteria%")
			  			  ->orWhere('users.apellido_paterno','LIKE',"%$search_criteria%")
			  			  ->orWhere('users.apellido_materno','LIKE',"%$search_criteria%");
			  });

			  if($search_tipo_documento!=0){
			  	$query->where('users.idtipo_doc_identidad','=',$search_tipo_documento);
			  	$query->where('users.numero_doc_identidad','=',$search_documento_identidad);
			  }

			   if($search_herramienta!=0){
			   	$query->join('herramientaxusers','herramientaxusers.iduser','=','users.id');
			  	$query->where('herramientaxusers.idherramienta','=',$search_herramienta);
			  }
			  $query->select('rol.nombre as nombre_rol','users.*');
		return $query;
	}

	//Query para buscar usuarios por nombre
	public function scopeBuscarPorNombre($query,$search_criteria)
	{
		$query->withTrashed()
			  ->whereNested(function($query) use($search_criteria){
			  		$query->where('users.username','LIKE',"%$search_criteria%")
			  			  ->orWhere('users.nombre','LIKE',"%$search_criteria%")
			  			  ->orWhere('users.apellido_paterno','LIKE',"%$search_criteria%")
			  			  ->orWhere('users.apellido_materno','LIKE',"%$search_criteria%");
			  });
	  	$query->select('users.*');
		return $query;
	}

	//Query para buscar usuarios con su respectiva cantidad de tickets asignados por sector
	public function scopeBuscarUsuariosAsignacionPorSector($query,$idsector)
	{
		
		
		$tempTableSector = DB::select('Select A.id_usuario, A.nombre_usuario, A.apellido_paterno, A.apellido_materno, ISNULL(B.cantidad_solicitudes,0) cantidad_solicitudes
			FROM 
			(select [users].[id] id_usuario, [users].[nombre] nombre_usuario, [users].[apellido_paterno] apellido_paterno, [users].[apellido_materno] apellido_materno
			from [users] 
			inner join [usersxsector] on [usersxsector].[iduser] = [users].[id]
			inner join [sector] on [sector].[idsector] = [usersxsector].[idsector]
			where 
			[sector].[idsector] = ? and [usersxsector].[deleted_at] IS null and [users].[deleted_at] IS null) A
			LEFT JOIN 
			(select [users].[id] id_usuario, count([solicitud].[idsolicitud]) cantidad_solicitudes
			from [users] 
			inner join [usersxsector] on [usersxsector].[iduser] = [users].[id]
			inner join [sector] on [sector].[idsector] = [usersxsector].[idsector]
			inner join [usuariosxasignacion] on [users].[id] = [usuariosxasignacion].[idusuario_asignado] 
			inner join [asignacion] on [asignacion].[idasignacion] = [usuariosxasignacion].[idasignacion] 
			inner join [solicitud] on [solicitud].[idsolicitud] = [asignacion].[idsolicitud] 
			where ([solicitud].[idestado_solicitud] = 3 or [solicitud].[idestado_solicitud] = 4)
			and [sector].[idsector] = ? and [usuariosxasignacion].[estado_usuario_asignado] = 1
			group by [users].[id]) B
			ON (A.id_usuario = B.id_usuario)
			order by B.cantidad_solicitudes',array("$idsector","$idsector"));

		return $tempTableSector;
		
	}

	//Query para buscar usuarios con su respectiva cantidad de tickets asignados por herramienta y accion
	public function scopeBuscarUsuariosAsignacionPorHerramienta($query,$idherramienta,$idaccion)
	{
		return DB::select('Select A.id_usuario, A.nombre_usuario, A.apellido_paterno, A.apellido_materno, ISNULL(B.cantidad_solicitudes,0) cantidad_solicitudes
			FROM
			(
			select [users].[id] id_usuario, [users].[nombre] nombre_usuario, [users].[apellido_paterno] apellido_paterno, [users].[apellido_materno] apellido_materno
			from [users] 
			inner join [herramientaxtipo_solicitudxuser] on [herramientaxtipo_solicitudxuser].[iduser] = [users].[id] 
			inner join [herramientaxtipo_solicitud] on [herramientaxtipo_solicitud].[idherramientaxtipo_solicitud] = [herramientaxtipo_solicitudxuser].[idherramientaxtipo_solicitud] 
			inner join [tipo_solicitud] on [tipo_solicitud].[idtipo_solicitud] = [herramientaxtipo_solicitud].[idtipo_solicitud] 
			inner join [herramienta] on [herramienta].[idherramienta] = [herramientaxtipo_solicitud].[idherramienta] 
			where [users].[deleted_at] is null 
			and [herramienta].[idherramienta] = '.$idherramienta.' 
			and [tipo_solicitud].[idtipo_solicitud] = '.$idaccion.'  
			and [herramientaxtipo_solicitudxuser].[deleted_at] is null) A 
			LEFT JOIN
			(
			select [herramientaxtipo_solicitudxuser].[iduser] id_usuario, count(solicitud.idsolicitud) as cantidad_solicitudes 
			from [users] 
			inner join [herramientaxtipo_solicitudxuser] on [herramientaxtipo_solicitudxuser].[iduser] = [users].[id] 
			inner join [herramientaxtipo_solicitud] on [herramientaxtipo_solicitud].[idherramientaxtipo_solicitud] = [herramientaxtipo_solicitudxuser].[idherramientaxtipo_solicitud] 
			inner join [tipo_solicitud] on [tipo_solicitud].[idtipo_solicitud] = [herramientaxtipo_solicitud].[idtipo_solicitud] 
			inner join [herramienta] on [herramienta].[idherramienta] = [herramientaxtipo_solicitud].[idherramienta] 
			inner join [usuariosxasignacion] on [users].[id]= [usuariosxasignacion].[idusuario_asignado]
			inner join [asignacion] on [asignacion].[idasignacion] = [usuariosxasignacion].[idasignacion] 
			inner join [solicitud] on [solicitud].[idsolicitud] = [asignacion].[idsolicitud] 
			where [users].[deleted_at] is null 
			and [herramienta].[idherramienta] = '.$idherramienta.' 
			and [tipo_solicitud].[idtipo_solicitud] = '.$idaccion.'  
			and [herramientaxtipo_solicitudxuser].[deleted_at] is null 
			and [usuariosxasignacion].[estado_usuario_asignado] = 1 
			and ([solicitud].[idestado_solicitud] = 3 or [solicitud].[idestado_solicitud] = 4)
			group by [herramientaxtipo_solicitudxuser].[iduser] ) B
			ON (A.id_usuario = B.id_usuario)
			order by cantidad_solicitudes');
	}

	//Query para buscar usuarios con su respectiva cantidad de tickets asignados por herramienta y accion
	public function scopeBuscarUsuariosAsignacionPorHerramientaV2($query,$idherramienta,$idaccion,$idsector)
	{
		return DB::select('Select A.id_usuario, A.nombre_usuario, A.apellido_paterno, A.apellido_materno, ISNULL(B.cantidad_solicitudes,0) cantidad_solicitudes
			FROM
			(
			select [users].[id] id_usuario, [users].[nombre] nombre_usuario, [users].[apellido_paterno] apellido_paterno, [users].[apellido_materno] apellido_materno
			from [users] 
			inner join [herramientaxtipo_solicitudxuser] on [herramientaxtipo_solicitudxuser].[iduser] = [users].[id] 
			inner join [herramientaxtipo_solicitud] on [herramientaxtipo_solicitud].[idherramientaxtipo_solicitud] = [herramientaxtipo_solicitudxuser].[idherramientaxtipo_solicitud] 
			inner join [tipo_solicitud] on [tipo_solicitud].[idtipo_solicitud] = [herramientaxtipo_solicitud].[idtipo_solicitud] 
			inner join [herramienta] on [herramienta].[idherramienta] = [herramientaxtipo_solicitud].[idherramienta] 
			inner join [usersxsector] on [usersxsector].[iduser] = [users].[id]
			inner join [sector] on [sector].[idsector] = [usersxsector].[idsector]
			where [users].[deleted_at] is null 
			and [herramienta].[idherramienta] = '.$idherramienta.' 
			and [tipo_solicitud].[idtipo_solicitud] = '.$idaccion.'  
			and [herramientaxtipo_solicitudxuser].[deleted_at] is null and
			[sector].[idsector] = '.$idsector.' and [usersxsector].[deleted_at] IS null) A 
			LEFT JOIN
			(
			select [herramientaxtipo_solicitudxuser].[iduser] id_usuario, count(solicitud.idsolicitud) as cantidad_solicitudes 
			from [users] 
			inner join [herramientaxtipo_solicitudxuser] on [herramientaxtipo_solicitudxuser].[iduser] = [users].[id] 
			inner join [herramientaxtipo_solicitud] on [herramientaxtipo_solicitud].[idherramientaxtipo_solicitud] = [herramientaxtipo_solicitudxuser].[idherramientaxtipo_solicitud] 
			inner join [tipo_solicitud] on [tipo_solicitud].[idtipo_solicitud] = [herramientaxtipo_solicitud].[idtipo_solicitud] 
			inner join [herramienta] on [herramienta].[idherramienta] = [herramientaxtipo_solicitud].[idherramienta] 
			inner join [usuariosxasignacion] on [users].[id]= [usuariosxasignacion].[idusuario_asignado]
			inner join [asignacion] on [asignacion].[idasignacion] = [usuariosxasignacion].[idasignacion] 
			inner join [solicitud] on [solicitud].[idsolicitud] = [asignacion].[idsolicitud] 
			where [users].[deleted_at] is null 
			and [herramienta].[idherramienta] = '.$idherramienta.' 
			and [tipo_solicitud].[idtipo_solicitud] = '.$idaccion.'  
			and [herramientaxtipo_solicitudxuser].[deleted_at] is null 
			and [usuariosxasignacion].[estado_usuario_asignado] = 1 
			and ([solicitud].[idestado_solicitud] = 3 or [solicitud].[idestado_solicitud] = 4)
			group by [herramientaxtipo_solicitudxuser].[iduser] ) B
			ON (A.id_usuario = B.id_usuario)
			order by cantidad_solicitudes');
	}

	/*************************DASHBOARD *****************************************/
	public function scopeBuscarUsuariosConSolicitudSemaforo($query,$anho)
	{
		$query->join('usuariosxasignacion','usuariosxasignacion.idusuario_asignado','=','users.id')
			  ->join('asignacion','asignacion.idasignacion','=','usuariosxasignacion.idusuario_asignado')
			  ->join('solicitud','solicitud.idsolicitud','=','asignacion.idsolicitud');
		$query->where('solicitud.idestado_solicitud','!=',5)
			  ->whereYear('solicitud.fecha_solicitud','=',$anho)
			  ->where('usuariosxasignacion.estado_usuario_asignado','=',1);
		$query->select('users.*');
		$query->distinct();
		return $query;
	}

	/*******************PLANILLA*************************************************/
	public function scopeBuscarGestoresPlanilla($query)
	{
		$query->where('users.idrol','=',6);
		$query->select(DB::Raw('CONCAT(users.nombre,\' \',users.apellido_paterno,\' \',users.apellido_materno) as nombre,users.id as id'));
		return $query;	
	}


}
