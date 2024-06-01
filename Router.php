<?php

namespace MVC;

/**
 * Clase Router
 * 
 * Maneja el enrutamiento y el manejo de solicitudes para el framework MVC.
 */
class Router
{
  /**
   * @var array $rutasGet Un array asociativo para almacenar rutas GET.
   */
  public $rutasGet = [];

  /**
   * @var array $rutasPost Un array asociativo para almacenar rutas POST.
   */
  public $rutasPost = [];

  /**
   * Agrega una ruta GET al enrutador.
   *
   * @param string $url El patrón de URL para la ruta.
   * @param callable $fn La función de devolución de llamada para manejar la ruta.
   * @return void
   */
  public function get(string $url, callable $fn): void
  {
    $this->rutasGet[$url] = $fn;
  }

  /**
   * Agrega una ruta POST al enrutador.
   *
   * @param string $url El patrón de URL para la ruta.
   * @param callable $fn La función de devolución de llamada para manejar la ruta.
   * @return void
   */
  public function post(string $url, callable $fn): void
  {
    $this->rutasPost[$url] = $fn;
  }

  /**
   * Verifica la URL y el método solicitados, y llama al controlador de ruta apropiado.
   *
   * @return void
   */
  public function comprobarRutas(): void
  {
    if (!isset($_SESSION)) {
      session_start();
    }
    $auth = $_SESSION['login'] ?? false;

    $rutasProtegidas = ['/admin', '/molinos/crear', '/molinos/actualizar', '/admin/eliminar', '/logout', '/registro'];

    $urlActual = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


    debug($urlActual);
    $metodo = $_SERVER['REQUEST_METHOD'];

    if ($metodo === 'GET') {
      $fn = $this->rutasGet[$urlActual] ?? null;
    } else {
      $fn = $this->rutasPost[$urlActual] ?? null;
    }
    if (in_array($urlActual, $rutasProtegidas) && !$auth) {
      header('Location: /login');
    }

    if ($fn) {
      call_user_func($fn, $this);
    } else {
      header('Location: /404');
    }
  }



  /**
   * Renderiza una vista con los datos proporcionados.
   *
   * @param string $view El nombre del archivo de vista para renderizar.
   * @param array $datos Un array asociativo de datos para pasar a la vista.
   * @return void
   */
  public function render(string $view, array $datos = []): void
  {
    ob_start();
    foreach ($datos as $key => $value) {
      $$key = $value;
    }
    include __DIR__ . "/views/$view.php";
    $contenido = ob_get_clean();
    include __DIR__ . "/views/layout.php";
  }
}
