<?php
// Incluir la clase DAO y la conexión
include('conexion.php');  // Incluye la clase de conexión
include('DAO.php');       // Incluye la clase DAO

// Crear una instancia de la clase Conexion
$conexion = new Conexion();
$dao = new DAO($conexion->getConexion()); // Pasamos la conexión al DAO

// Obtener los cursos disponibles (opcional, si deseas mostrar los cursos)
$cursos = $dao->listarCursos();

// Si el formulario es enviado, manejar la lógica
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $curso = $_POST['curso'];
    $especialidad = $_POST['especialidad'];
    $turno = $_POST['turno'];

    // Validar si los campos están vacíos
    if (empty($curso) || empty($turno) || empty($especialidad)) {
        echo "Por favor, complete todos los campos.";
    } else {
        // Intentar registrar el curso
        try {
            $dao->registrarCurso($curso, $turno, $especialidad);
            echo "Curso registrado con éxito.";
        } catch (Exception $e) {
            echo "Error al registrar curso: " . $e->getMessage();
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
    <title>Cargar Curso</title>
</head>
<body>
    <h2>Cargar Curso</h2>
    <form method="POST">
        <label for="curso">Curso:</label>
        <input type="text" name="curso" id="curso" required pattern="^[0-9][A-Za-z]$" title="El curso debe contener un número seguido de una letra. Ejemplo: 1A, 2B."><br><br>

        <label for="turno">Turno:</label>
        <select id="turno" name="turno" required>
            <option value="mañana">Mañana</option>
            <option value="tarde">Tarde</option>
            <option value="noche">Noche</option>
        </select><br><br>

        <label for="especialidad">Especialidad:</label>
        <select id="especialidad" name="especialidad" required>
            <option value="programacion">Programación</option>
            <option value="desarrollo_de_alimentos">Desarrollo de Alimentos</option>
            <option value="informatica">Informática</option>
            <option value="enfermeria">Enfermería</option>
        </select><br><br>

        <input type="submit" value="Registrar Curso">
    </form>

    <!-- Volver al menú principal -->
    <a href="index.php" class="button">Volver al Menú Principal</a>
</body>
</html>
