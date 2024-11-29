<?php
// Incluir la conexión a la base de datos
require_once('conexion.php');  // Incluir el archivo de conexión

// Incluir la clase DAO
require_once('DAO.php');

// Verificar si se ha pasado el ID del Curso por URL
if (!isset($_GET['ID_c'])) {
    die('ID de Curso no valido.');
}

// Obtener el ID del alumno desde la URL
$ID_c = $_GET['ID_c'];

// Crear una instancia de la clase Conexion y obtener la conexión
$conexionObj = new Conexion();  // Crear una instancia de la clase Conexion
$conexion = $conexionObj->getConexion();  // Obtener la conexión

// Verificar si la conexión fue exitosa
if (!$conexion) {
    die("Conexión fallida.");
}

// Crear la instancia de DAO pasando la conexión como argumento
try {
    $dao = new DAO($conexion);  // Aquí le pasas la variable $conexion
    $dao->eliminarCurso($ID_c);  // Eliminar al alumno con el ID especificado

    // Redirigir a la lista de alumnos después de eliminar
    header("Location: listarcursos.php");
    exit;  // Asegúrate de usar exit después de redirigir
} catch (Exception $e) {
    // Si ocurre un error, capturamos la excepción y la mostramos
    echo 'Error: ' . $e->getMessage();
}
?>

