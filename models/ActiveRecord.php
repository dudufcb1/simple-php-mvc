<?php

namespace Model;

class ActiveRecord
{
  // BASE DE DATOS

  protected static $db;
  protected static $columnasDB = [];

  //Validación
  protected static $errores = [];
  protected static $tabla = '';

  public $id;
  public $imagen;


  /* definir la conexión a la bd */
  public static function setDB($database) //Nota se está seteando desde app.php
  {
    self::$db = $database;
  }

  public static function getErrores(): array
  {
    return static::$errores;
  }
  public function validar()
  {
    static::$errores = [];
    return static::$errores; //NOTA Devuelve los errores IMPORTANTE
  }

  /* Nota Comienza el CRUD*/

  //** Consulta propiedades **//

  //** GET **//
  public static function all(): array
  {
    $query = "SELECT * FROM " . static::$tabla;
    $resultado = static::consultarSQL($query);

    return $resultado;
  }

  //** GET DETERMINADO NUMERO DE REGISTRO **//

  public static function get($cantidad): array
  {
    $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;
    $resultado = static::consultarSQL($query);

    return $resultado;
  }

  public static function find($id)
  {
    $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id}";
    $resultado = self::consultarSQL($query);
    return array_shift($resultado);
  }

  //** Crea propiedades **//

  //** CREATE **//

  public function guardar()
  {
    if (isset($this->id)) {
      return $this->actualizar();
    } else {
      return $this->crear();
    }
  }

  public function crear(): bool
  {
    $atributosSanitizados = $this->santizarDatos();

    /*     $stringColumns = join(', ', array_keys($atributosSanitizados));
    $stringData = join(', ', array_values($atributosSanitizados)); */
    //Insertar
    $query = "INSERT INTO " . static::$tabla . " ( ";
    $query .= join(', ', array_keys($atributosSanitizados));
    $query .= ") VALUES ('";
    $query .= join("', '", array_values($atributosSanitizados));
    $query .= "')";


    $resultado = self::$db->query($query);

    return $resultado;
  }

  //** Update propiedades **//

  //** Update **//

  public function actualizar(): bool
  {
    $atributosSanitizados = $this->santizarDatos();
    $valoresConsulta = [];
    foreach ($atributosSanitizados as $key => $value) {
      $valoresConsulta[] = "$key='{$value}'";
    }
    $query = "UPDATE " . static::$tabla . " SET ";
    // debug($query);
    $query .= join(', ', $valoresConsulta);
    $query .= " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
    // debug($query);
    $resultado = self::$db->query($query);
    return $resultado;
  }

  //** Delete propiedades **//

  //** DELETE **//

  public function delete()
  {
    $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
    $resultado = self::$db->query($query);
    if ($resultado) {
      $this->eliminarImagen();
      return 3; //Devuelvo 3 por que es el indicado para el mensaje se sucess
    }
  }



  public function prepararAtributos(): array
  {
    $atributosPreparados = [];
    foreach (static::$columnasDB as $columna) {
      if ($columna === 'id') continue;
      $atributosPreparados[$columna] = $this->$columna;
    }
    return $atributosPreparados;
  }

  public function santizarDatos(): array
  {
    $atributos = $this->prepararAtributos();
    $atributosSanitizados = [];
    foreach ($atributos as $atributo => $valor) {
      $atributosSanitizados[$atributo] = self::$db->escape_string($valor);
    }
    return $atributosSanitizados;
  }

  public function setImagen($nombreDeLaImagenTexto)
  {
    // Verifica si hay una imagen actual y si se está actualizando el registro
    if (isset($this->id) && !empty($this->imagen)) {
      $this->eliminarImagen();
    }

    // Asigna la nueva imagen solo si se ha proporcionado una
    if ($nombreDeLaImagenTexto) {
      $this->imagen = $nombreDeLaImagenTexto;
    }
  }

  public function eliminarImagen()
  {
    // Verifica si hay una imagen actual
    if (!empty($this->imagen)) {
      // Construye la ruta completa a la imagen
      $rutaImagen = CARPETA_IMAGENES . "/" . $this->imagen;

      // Elimina la imagen si existe en el servidor
      if (file_exists($rutaImagen)) {
        unlink($rutaImagen);
      }

      // Limpia la propiedad de imagen actual
      $this->imagen = null;
    }
  }



  //Validación





  public static function consultarSQL($query): array
  {
    //CONSULTAR LA BASE DE DATOS
    $resultado = self::$db->query($query);
    //ITERAR LA BASE DE DATOS
    $array = [];

    while ($registro = $resultado->fetch_assoc()) {
      # code...
      $array[] = static::crearObjeto($registro);
    }

    //LIBERAR LA MEMORIA
    $resultado->free();

    //RETORNAR LOS RESULTADOS
    return $array;
  }

  protected static function crearObjeto($registro): object
  {
    $objeto = new static; //Nuevo objeto de si mismo

    //**Si recorremos $registro y le extraemos su $key y $value, si dentro de el objeto EXITE la clave, entonces, añadele el contenido que viene desde el registro */
    foreach ($registro as $key => $value) {
      if (property_exists($objeto, $key)) {
        $objeto->$key = $value;
      }
    }
    return $objeto;
  }

  public function sincronizar($args = [])
  {
    foreach ($args as $key => $value) {
      if (property_exists($this, $key) && !is_null($value)) {
        $this->$key = $value;
      }
    }
  }
}
