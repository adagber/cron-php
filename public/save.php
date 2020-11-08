<?php

use Vitrac\PhpCron\CrontabParser;

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/app/files.php';

//Obtenemos la reglas del formulario
$rules = $_REQUEST['rules'] ?? '';

$rules = trim($rules);

//Validamos los datos
if(!CrontabParser::checkMultiLines($rules)){
  http_response_code(400);
  die('KO');
}

//Convertimos la lineas en un array
$content = @file_put_contents($cronFilename, $rules);
die('OK');