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

// Verificar si se recibe el ID del alumno
if (!isset($_GET['id_a'])) {
    die('ID de alumno no válido.');
}

$id_a = $_GET['id_a'];

// Crear la instancia de DAO pasando la conexión como argumento
$dao = new DAO($conexion);

// Obtener los datos del alumno para mostrar en el formulario
$alumno = $dao->obtenerAlumno($id_a);  // Este método debe devolver los datos del alumno

// Procesar el formulario de modificación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $id_curso = $_POST['id_curso'];
    
    // Actualizar el alumno
    $dao->modificarAlumno($id_a, $nombre, $apellido, $id_curso);
    header("Location: listaralumnos.php"); // Redirigir a la lista de alumnos
    exit;
}
?>

<form action="" method="POST">
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" id="nombre" value="<?php echo $alumno['nombre']; ?>" required>
    
    <label for="apellido">Apellido:</label>
    <input type="text" name="apellido" id="apellido" value="<?php echo $alumno['apellido']; ?>" required>
    
    <label for="id_curso">Curso:</label>
    <select name="id_curso" id="id_curso">
        <!-- Aquí deberías cargar los cursos disponibles desde la base de datos -->
        <?php
        $cursos = $dao->listarCursos();
        foreach ($cursos as $curso) {
            echo "<option value='{$curso['ID_c']}'" . ($curso['ID_c'] == $alumno['id_curso'] ? ' selected' : '') . ">{$curso['curso']} - {$curso['especialidad']}</option>";
        }
        ?>
    </select>
    
    <button type="submit">Guardar Cambios</button>
</form>
