SELECT month(fecha_solicitud) from solicitud

DELETE FROM usuariosxasignacion	
DELETE from asignacion
DELETE FROM solicitud
DELETE FROm carga_archivo


SELECT * from solicitud

select [users].[id], count([solicitud].[idsolicitud]) cantidad_solicitudes
from [users] 
inner join [usersxsector] on [usersxsector].[iduser] = [users].[id]
inner join [sector] on [sector].[idsector] = [usersxsector].[idsector]
inner join [usuariosxasignacion] on [users].[id] = [usuariosxasignacion].[idusuario_asignado] 
inner join [asignacion] on [asignacion].[idasignacion] = [usuariosxasignacion].[idasignacion] 
inner join [solicitud] on [solicitud].[idsolicitud] = [asignacion].[idsolicitud] 
where ([solicitud].[idestado_solicitud] = 3 or [solicitud].[idestado_solicitud] = 4)
and [sector].[idsector] = 2
group by [users].[id]
order by count([solicitud].[idsolicitud]) desc

select [users].[id] usuarios_con_solicitudes
from [users] 
inner join [usersxsector] on [usersxsector].[iduser] = [users].[id]
inner join [sector] on [sector].[idsector] = [usersxsector].[idsector]
where 
[sector].[idsector] = 2;


SELECT A.id_usuario, A.nombre_usuario, A.apellido_pat, A.apellido_materno, ISNULL(B.cantidad_solicitudes,0) cantidad_solicitudes
FROM 
(select [users].[id] id_usuario, [users].[nombre] nombre_usuario, [users].[apellido_paterno] apellido_pat, [users].[apellido_materno] apellido_materno
from [users] 
inner join [usersxsector] on [usersxsector].[iduser] = [users].[id]
inner join [sector] on [sector].[idsector] = [usersxsector].[idsector]
where 
[sector].[idsector] = 2) A
LEFT JOIN 
(select [users].[id] id_usuario, count([solicitud].[idsolicitud]) cantidad_solicitudes
from [users] 
inner join [usersxsector] on [usersxsector].[iduser] = [users].[id]
inner join [sector] on [sector].[idsector] = [usersxsector].[idsector]
inner join [usuariosxasignacion] on [users].[id] = [usuariosxasignacion].[idusuario_asignado] 
inner join [asignacion] on [asignacion].[idasignacion] = [usuariosxasignacion].[idasignacion] 
inner join [solicitud] on [solicitud].[idsolicitud] = [asignacion].[idsolicitud] 
where ([solicitud].[idestado_solicitud] = 3 or [solicitud].[idestado_solicitud] = 4)
and [sector].[idsector] = 2
group by [users].[id]) B
ON (A.idusuario = B.id_usuario)
order by B.cantidad_solicitudes


SELECT A.id_usuario, A.nombre_usuario, A.apellido_pat, A.apellido_materno, ISNULL(B.cantidad_solicitudes,0) cantidad_solicitudes
FROM
(
select [users].[id] id_usuario, [users].[nombre] nombre_usuario, [users].[apellido_paterno] apellido_pat, [users].[apellido_materno] apellido_materno
from [users] 
inner join [herramientaxtipo_solicitudxuser] on [herramientaxtipo_solicitudxuser].[iduser] = [users].[id] 
inner join [herramientaxtipo_solicitud] on [herramientaxtipo_solicitud].[idherramientaxtipo_solicitud] = [herramientaxtipo_solicitudxuser].[idherramientaxtipo_solicitud] 
inner join [tipo_solicitud] on [tipo_solicitud].[idtipo_solicitud] = [herramientaxtipo_solicitud].[idtipo_solicitud] 
inner join [herramienta] on [herramienta].[idherramienta] = [herramientaxtipo_solicitud].[idherramienta] 
where [users].[deleted_at] is null 
and [herramienta].[idherramienta] = 6
and [tipo_solicitud].[idtipo_solicitud] = 5 
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
and [herramienta].[idherramienta] = 6
and [tipo_solicitud].[idtipo_solicitud] = 5 
and [herramientaxtipo_solicitudxuser].[deleted_at] is null 
and [usuariosxasignacion].[estado_usuario_asignado] = 1 
and ([solicitud].[idestado_solicitud] = 3 or [solicitud].[idestado_solicitud] = 4)
group by [herramientaxtipo_solicitudxuser].[iduser] ) B
ON (A.id_usuario = B.id_usuario)
order by cantidad_solicitudes ;



