SELECT month(fecha_solicitud) from solicitud

DELETE FROM usuariosxasignacion	
DELETE from asignacion
DELETE FROM solicitud
DELETE FROm carga_archivo


SELECT * from users

DELETE FROM users
where id IN (4,5)

select * from herramientaxtipo_solicitudxuser


DELETE FROM herramientaxtipo_solicitudxuser
where iduser IN (4,5)

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


SELECT * FROM herramientaxtipo_solicitudxuser 
inner join herramientaxtipo_solicitud 
on herramientaxtipo_solicitud.idherramientaxtipo_solicitud = herramientaxtipo_solicitudxuser.idherramientaxtipo_solicitud
where herramientaxtipo_solicitud.idherramienta = 6 

/*select [herramientaxtipo_solicitudxuser].[iduser], count(solicitud.idsolicitud) as cantidad_solicitud 
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
group by [herramientaxtipo_solicitudxuser].[iduser] 
order by [cantidad_solicitud] desc*/

select [herramientaxtipo_solicitudxuser].[iduser] 
from [users] 
inner join [herramientaxtipo_solicitudxuser] on [herramientaxtipo_solicitudxuser].[iduser] = [users].[id] 
inner join [herramientaxtipo_solicitud] on [herramientaxtipo_solicitud].[idherramientaxtipo_solicitud] = [herramientaxtipo_solicitudxuser].[idherramientaxtipo_solicitud] 
inner join [tipo_solicitud] on [tipo_solicitud].[idtipo_solicitud] = [herramientaxtipo_solicitud].[idtipo_solicitud] 
inner join [herramienta] on [herramienta].[idherramienta] = [herramientaxtipo_solicitud].[idherramienta] 
where [users].[deleted_at] is null 
and [herramienta].[idherramienta] = 6 
and [tipo_solicitud].[idtipo_solicitud] = 5 
and [herramientaxtipo_solicitudxuser].[deleted_at] is null 
and [herramientaxtipo_solicitudxuser].[iduser] not in 
	(select distinct [users].[id] 
		from [users] 
		inner join [usuariosxasignacion] on [users].[id] = [usuariosxasignacion].[idusuario_asignado] 
		inner join [asignacion] on [asignacion].[idasignacion] = [usuariosxasignacion].[idasignacion] 
		inner join [solicitud] on [solicitud].[idsolicitud] = [asignacion].[idsolicitud] 
		where ([solicitud].[idestado_solicitud] = 3 or [solicitud].[idestado_solicitud] = 4))

SELECT * from herramienta where idherramienta = 6


Select A.id_usuario, A.nombre_usuario, A.apellido_paterno, A.apellido_materno, ISNULL(B.cantidad_solicitudes,0) cantidad_solicitudes
			FROM
			(
			select [users].[id] id_usuario, [users].[nombre] nombre_usuario, [users].[apellido_paterno] apellido_paterno, [users].[apellido_materno] apellido_materno
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
			order by cantidad_solicitudes

SELECT * from solicitud
UPDATE solicitud
set idestado_solicitud = 4
where idsolicitud = 14


-- PARA USuARIOS
SELECT CONCAT(users.nombre,' ',users.apellido_paterno,' ',users.apellido_materno) usuario,count(codigo_solicitud) total, sum(case when solicitud.idestado_solicitud = 3 then 1 else 0 end) pendientes,sum(case when solicitud.idestado_solicitud = 4 then 1 else 0 end) procesando
FROM solicitud
join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
join usuariosxasignacion on (usuariosxasignacion.idasignacion = asignacion.idasignacion)
join users on (users.id = usuariosxasignacion.idusuario_asignado)
where usuariosxasignacion.estado_usuario_asignado = 1
and month(solicitud.fecha_solicitud) = 12
and year(solicitud.fecha_solicitud) = 2017
group by CONCAT(users.nombre,' ',users.apellido_paterno,' ',users.apellido_materno)
order by total DESC

-- PARA SECTORES

SELECT sector.nombre nombre_sector, CONCAT(users.nombre,' ',users.apellido_paterno,' ',users.apellido_materno) nombre_usuario ,count(codigo_solicitud) cantidad_total, sum(case when solicitud.idestado_solicitud = 3 then 1 else 0 end) cantidad_pendientes,sum(case when solicitud.idestado_solicitud = 4 then 1 else 0 end) cantidad_procesando
FROM solicitud
join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
join usuariosxasignacion on (usuariosxasignacion.idasignacion = asignacion.idasignacion)
join users on (users.id = usuariosxasignacion.idusuario_asignado)
join usersxsector on (usersxsector.iduser = users.id)
join sector on (sector.idsector = usersxsector.idsector)
where usuariosxasignacion.estado_usuario_asignado = 1
group by sector.nombre ,CONCAT(users.nombre,' ',users.apellido_paterno,' ',users.apellido_materno)
order by cantidad_total DESC

SELECT * from solicitud;

UPDATE solicitud
set idestado_solicitud = 3
where idsolicitud = 3

truncate table base_recibo_noviembre

BULK
INSERT base_recibo_octubre
FROM 'C:\Users\lmirandadu\Escritorio\base_octubre.csv'
WITH
(
FIELDTERMINATOR = '|',
ROWTERMINATOR = '\n',
FIRSTROW = 2
)

TRUNCATE TABLE base_recibo_octubre
SELECT * INTO base_recibo_octubre FROM base_recibo_noviembre

SELECT FLAG_DEPENDENCIA,ESTADO_DE_GESTION_1,GESTION,AFILIACION_A_FACTURA_DIGITAL FROM base_recibo_noviembre

SELECT NUMERO_DE_DOCUMENTO,CODIGO_PEDIDO INTO base_recibo_octubre_final FROM base_recibo_octubre ORDER BY NUMERO_DE_DOCUMENTO