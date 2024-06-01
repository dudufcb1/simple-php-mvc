<?php

function conectarDB(): mysqli
{
  $db = new mysqli('localhost', 'root', 'root', 'molino');
  if (!$db) {
    echo 'Error al conectar a la base de datos: ';
    exit;
  }
  return $db;
}
