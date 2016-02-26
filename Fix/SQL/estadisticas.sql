-- la cantidad de nodos reportados por guion SECTOR - NODO 
SELECT
	count(n.ID) CANTIDAD, u.NOMBRE
FROM
	GUIONES_REGISTRO_UBICACION_NODO n
INNER JOIN GUIONES_REGISTRO r  
	ON 	n.REGISTRO = r.ID
INNER JOIN GUIONES_REGISTRO_UBICACION u
	ON r.UBICACION = u.ID
GROUP BY r.UBICACION;


-- la cantidad de guiones reportados SECTOR - NODO 
SELECT 
	COUNT(r.UBICACION) CANTIDAD, u.NOMBRE
FROM 
	GUIONES_REGISTRO r
INNER JOIN 
	GUIONES_REGISTRO_UBICACION u 
ON 
	r.UBICACION = u.ID
WHERE 
	UBICACION BETWEEN 1 AND 2
GROUP BY UBICACION;




-- cantidad de nodos por Regional reportados SECTOR - NODO - GUION 

SELECT
	 r.NODO, count(r.NOMBRE_COMUNIDAD) AS CANTIDAD_CIUDAD, r.NOMBRE_COMUNIDAD AS CIUDAD
FROM
	GUIONES_REGISTRO_UBICACION_NODO n
INNER JOIN 
	RADIOGRAFIA_NODOS r
ON n.NODO = r.ID
GROUP BY r.NOMBRE_COMUNIDAD
ORDER BY CANTIDAD_CIUDAD 
