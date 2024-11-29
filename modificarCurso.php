<?php
// Incluir la clase DAO y la conexión
require_once('conexion.php');
require_once('DAO.php');

// Crear una instancia de Conexion y obtener la conexión
$conexionObj = new Conexion();  // Instanciamos la clase Conexion
$conexion = $conexionObj->getConexion();  // Obtenemos la conexión

// Verificar que la conexión sea válida
if (!$conexion) {
    die("Error: No se pudo establecer la conexión con la base de datos.");
}

// Verificar si se recibe el ID del curso
if (!isset($_GET['ID_c'])) {
    die('ID de curso no válido.');
}

$id_c = $_GET['ID_c'];

// Crear la instancia de DAO pasando la conexión como argumento
$dao = new DAO($conexion);

// Obtener los datos del curso para mostrar en el formulario
$curso = $dao->obtenerCurso($id_c);  // Este método debe devolver los datos del curso

// Procesar el formulario de modificación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $curso_nombre = $_POST['curso_nombre'];
    $turno = $_POST['turno'];
    $especialidad = $_POST['especialidad'];
    
    // Actualizar el curso
    $dao->modificarCurso($id_c, $curso_nombre, $turno, $especialidad);
    header("Location: listarCursos.php"); // Redirigir a la lista de cursos
    exit;
}
?>
<link rel="stylesheet" href="style.css">
<form action="" method="POST">
    <label for="curso_nombre">Nombre del Curso:</label>
    <input type="text" name="curso_nombre" id="curso_nombre" value="<?php echo $curso['curso']; ?>" required pattern="^[0-9][A-Za-z]$" title="El curso debe contener un número seguido de una letra. Ejemplo: 1A, 2B.">
 

    <label for="turno">Turno:</label>
    <select id="turno" name="turno" value="<?php echo $curso['turno']; ?>" required>
            <option value="mañana">Mañana</option>
            <option value="tarde">Tarde</option>
            <option value="noche">Noche</option>
        </select><br><br>
    
    <label for="especialidad">Especialidad:</label>
    <select id="especialidad" name="especialidad" value=<<?php echo $curso['especialidad']; ?> required>
            <option value="programacion">Programación</option>
            <option value="desarrollo_de_alimentos">Desarrollo de Alimentos</option>
            <option value="informatica">Informática</option>
            <option value="enfermeria">Enfermería</option>
        </select><br><br>
    <button type="submit">Guardar Cambios</button>
</form>
