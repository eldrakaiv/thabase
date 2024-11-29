<?php
include('DAO.php');  // Incluir la clase DAO
include('conexion.php');  // Incluir la conexión

// Crear una instancia de la clase Conexion y obtener la conexión
$conexionObj = new Conexion();  // Crear una instancia de la clase Conexion
$conexion = $conexionObj->getConexion();  // Obtener la conexión

// Verificar que la conexión sea válida
if (!$conexion) {
    die("Error: No se pudo establecer la conexión con la base de datos.");
}

// Crear la instancia de DAO pasando la conexión como argumento
$dao = new DAO($conexion);

// Obtener los alumnos usando el método listarAlumnos de la clase DAO
$alumnos = $dao->listarAlumnos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alumnos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Lista de Alumnos</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Curso</th>
            <th>Acciones</th> <!-- Nueva columna para los botones -->
        </tr>
        <?php foreach ($alumnos as $alumno): ?>
        <tr>
            <td><?php echo $alumno['nombre']; ?></td>
            <td><?php echo $alumno['apellido']; ?></td>
            <td><?php echo $alumno['curso'] . ' - ' . $alumno['especialidad']; ?></td> <!-- Mostrar curso y especialidad -->
            <td>
                <!-- Botón para Modificar -->
                <a href="modificarAlumno.php?id_a=<?php echo $alumno['id_a']; ?>" class="btn-modificar">Modificar</a>
                <!-- Botón para Eliminar -->
                <a href="eliminarAlumno.php?id_a=<?php echo $alumno['id_a']; ?>" class="btn-eliminar" onclick="return confirm('¿Seguro que quieres eliminar este alumno?');">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
       <form><div>   <a href="index.php" class="button">Volver al Menú Principal</a>       </div></form> 
</body>
</html>

