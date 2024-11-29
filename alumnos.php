<?php
include('DAO.php');  // Incluir la clase DAO
include('conexion.php');  // Incluir la conexión

// Instanciar la clase Conexion y obtener la conexión
$conexionObj = new Conexion();  // Crear una instancia de la clase Conexion
$conexion = $conexionObj->getConexion();  // Obtener la conexión

// Verificar que la conexión sea válida
if (!$conexion) {
    die("Error: No se pudo establecer la conexión con la base de datos.");
}

// Instanciar la clase DAO y pasar la conexión como parámetro
$dao = new DAO($conexion);

$mensaje = '';  // Para mostrar mensajes de éxito o error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $id_curso = $_POST['id_curso'];

    // Validar que todos los campos sean llenados y que el id_curso no esté vacío
    if (empty($nombre) || empty($apellido) || empty($id_curso)) {
        $mensaje = "Por favor, complete todos los campos y seleccione un curso válido.";
    } else {
        try {
            // Registrar al alumno en la base de datos
            $dao->registrarAlumno($nombre, $apellido, $id_curso);
            $mensaje = "¡Alumno registrado exitosamente!";
        } catch (Exception $e) {
            $mensaje = "Error al registrar el alumno: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Cargar Alumno</title>
</head>
<body>
    <h2>Cargar Alumno</h2>

    <!-- Mostrar el mensaje de éxito o error -->
    <?php if (!empty($mensaje)): ?>
        <div class="mensaje">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

   <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required><br><br>
        
        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" id="apellido" required><br><br>

        <label for="id_curso">Curso:</label>
        <select name="id_curso" id="id_curso" required>
            <option value="">Seleccione un curso</option> <!-- Esta opción ahora es vacía -->
            <?php
            // Obtener los cursos disponibles desde la base de datos
            $cursos = $dao->listarCursos();
            foreach ($cursos as $curso) {
                // Mostrar los cursos con el ID, especialidad y turno
                echo '<option value="' . $curso['ID_c'] . '">' . $curso['curso'] . ' - ' . $curso['especialidad'] . ' (' . $curso['turno'] . ')</option>';
            }
            ?>
        </select><br><br>

        <!-- Botón de envío -->
        <input type="submit" value="Registrar Alumno">

        <!-- Enlace para volver al menú principal -->
        <a href="index.php" class="button">Volver al Menú Principal</a>
    </form>
</body>
</html>
