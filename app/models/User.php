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

	public function scopeListarUsuarios($query)
	{
		$query->withTrashed()
			  ->join('rol','rol.idrol','=','users.idrol')
			  ->select('rol.nombre as nombre_rol','users.*');
		return $query;
	}

	public function scopeBuscarUsuarioPorUsername($query,$search_username)
	{
		$query->withTrashed()	
			  ->where('users.username','=',$search_username)		  
			  ->select('users.*');
		return $query;
	}

	public function scopeBuscarUsuarioPorId($query,$search_criteria)
	{
		$query->withTrashed()
			  ->where('users.id','=',$search_criteria);
		
		return $query;
	}

	public function scopeBuscarUsuariosPorIdSector($query,$idsector)
	{
		$query->join('usersxsector','usersxsector.iduser','=','users.id')
			  ->join('sector','sector.idsector','=','usersxsector.idsector');
		$query->where('sector.idsector','=',$idsector);
		$query->select('users.*');
		return $query;
	}

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

	public function scopeBuscarUsuariosAsignacionPorSector($query,$idsector)
	{
		
		
		$tempTableSector = DB::select('Select A.id_usuario, A.nombre_usuario, A.apellido_paterno, A.apellido_materno, ISNULL(B.cantidad_solicitudes,0) cantidad_solicitudes
			FROM 
			(select [users].[id] id_usuario, [users].[nombre] nombre_usuario, [users].[apellido_paterno] apellido_paterno, [users].[apellido_materno] apellido_materno
			from [users] 
			inner join [usersxsector] on [usersxsector].[iduser] = [users].[id]
			inner join [sector] on [sector].[idsector] = [usersxsector].[idsector]
			where 
			[sector].[idsector] = ? and [users].[deleted_at] IS null) A
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

}
