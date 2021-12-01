<?php
/*
Configuramos la zona horaria correcta para nuestra aplicación
 */
date_default_timezone_set('America/Mexico_City');

/*
Función para retornar datos 
con cabecera JSON
 */
function json_header() {
    header('Content-Type: application/json; charset=utf-8');
}
