<?php


define('BASE_FOLDER', __DIR__ . '/../../src');
define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . 'funciones.php');
define('CARPETA_IMAGENES', $_SERVER['DOCUMENT_ROOT'] . '/imagenes/');

function incluirTemplate(string $nombre, bool $inicio = false, $titulo = ''): void
{
  if ($titulo) {
    $titulo = 'Bienes Raices - ' . $titulo;
  } else {
    $titulo = 'Bienes Raices';
  }

  include TEMPLATES_URL . "/{$nombre}.php";
}

function estaAutenticado(): void
{
  session_start();
  if (!$_SESSION['login']) {
    header('Location: /bienesraices/src');
  }
}
function debug($var)
{
  echo '<pre>';
  var_dump($var);
  echo '</pre>';
  exit;
}


function s($html): string
{
  $s = htmlspecialchars($html);
  return $s;
}


function generarNombreArchivo($imagen)
{
  // Generar un nombre único para la imagen
  $nombreImagen = md5(uniqid(rand(), true));
  // Obtener la extensión del archivo subido
  $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);
  $nombreArchivo = $nombreImagen . "." . $extension;

  return $nombreArchivo;
}


function vaciarCarpeta($carpeta)
{

  $directorio = opendir($carpeta);

  while ($archivo = readdir($directorio)) {

    if ($archivo != "." && $archivo != "..") {

      $ruta_completa = $carpeta . "/" . $archivo;

      if (is_file($ruta_completa)) {
        unlink($ruta_completa);
      }
    }
  }

  closedir($directorio);
  exit;
}

function tipoDeContenido($tipo): bool
{
  $contenidos = ['vendedor', 'propiedad'];
  return in_array($tipo, $contenidos);
}

function notificaciones($tipo)
{
  $mensaje = '';
  switch ($tipo) {
    case 1:
      $mensaje = 'Registro agregado correctamente a la base de datos';
      break;
    case 2:
      $mensaje = 'Registro actualizado correctamente en la base de datos';
      break;
    case 3:
      $mensaje = 'Registro eliminado correctamente de la base de datos';
      break;
    default:
      $mensaje = false;
      break;
  }
  return $mensaje;
}

function validarExistencia($clase, $parametro = 'id', $redirectUrl = '../')
{
  $datos = $_GET; // Conseguimos los datos actuales del formulario.
  $id = isset($datos[$parametro]) ? $datos[$parametro] : null;
  $id = filter_var($id, FILTER_VALIDATE_INT); // No solo valida, lo convierte a entero también.


  if (!$id) {
    header('Location: ' . $redirectUrl); // Redirige si el ID no es válido
    exit();
  }

  $registro = $clase::find($id);
  if (!$registro) {
    header('Location: ' . $redirectUrl); // Redirige si el registro no existe
    exit();
  }

  return $registro;
}
