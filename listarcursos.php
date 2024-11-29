<?php
// Incluir la conexión a la base de datos
include('conexion.php');

// Crear una instancia de la conexión
$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

// Incluir la clase DAO
include('DAO.php');

// Crear una instancia de la clase DAO y pasar la conexión
$dao = new DAO($conexion);

// Obtener los cursos desde la base de datos
try {
    $cursos = $dao->listarCursos();
} catch (Exception $e) {
    die("Error al listar los cursos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Cursos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Cursos</h1>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Curso</th>
                <th>Especialidad</th>
                <th>Turno</th>
                <th>Acciones</th> <!-- Nueva columna para los botones -->
            </tr>
            <?php foreach ($cursos as $curso): ?>
            <tr>
                <td><?php echo $curso['ID_c']; ?></td>
                <td><?php echo $curso['curso']; ?></td>
                <td><?php echo $curso['especialidad']; ?></td>
                <td><?php echo $curso['turno']; ?></td>
                    <td>
                <!-- Botón para Modificar -->
                <a href="modificarCurso.php?ID_c=<?php echo $curso['ID_c']; ?>" class="btn-modificar">Modificar</a>
                <!-- Botón para Eliminar -->
                <a href="eliminarCurso.php?ID_c=<?php echo $curso['ID_c']; ?>" class="btn-eliminar" onclick="return confirm('¿Seguro que quieres eliminar este curso?');">Eliminar</a>
            </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="index.php" class="button">Volver al Menú Principal</a>
    </div>
</body>
</html>
