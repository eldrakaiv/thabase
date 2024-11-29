<?php
// Incluir la conexión a la base de datos desde otro archivo
require_once('conexion.php');  // Cambié include por require_once para evitar problemas de múltiples inclusiones

class DAO {
    private $conexion;

    // Constructor que recibe la conexión desde 'conexion.php'
    public function __construct($conexion) {
        if (!$conexion) {
            throw new Exception("Error: No se recibió una conexión válida.");
        }
        $this->conexion = $conexion;
    }

    // Método para listar todos los alumnos
    public function listarAlumnos() {
        // Realizamos una consulta JOIN para obtener también el nombre y la especialidad del curso
        $query = "SELECT a.id_a, a.nombre, a.apellido, c.curso, c.especialidad 
                  FROM alumnos a 
                  JOIN cursos c ON a.id_curso = c.ID_c";
        $resultado = $this->conexion->query($query);

        if (!$resultado) {
            throw new Exception("Error al consultar la tabla 'alumnos': " . $this->conexion->error);
        }

        $alumnos = [];
        while ($row = $resultado->fetch_assoc()) {
            $alumnos[] = $row;  // Cada fila es un alumno con su curso y especialidad
        }
        return $alumnos;  // Devolvemos el array de alumnos con la información del curso
    }

    // Método para registrar un nuevo alumno
    public function registrarAlumno($nombre, $apellido, $id_curso) {
        // Verificar que el curso existe en la tabla cursos
        $sql_check = "SELECT COUNT(*) FROM cursos WHERE ID_c = ?";
        $stmt_check = $this->conexion->prepare($sql_check);
        if (!$stmt_check) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }
        $stmt_check->bind_param("i", $id_curso);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count == 0) {
            throw new Exception("El curso con ID '$id_curso' no existe.");
        }

        // Si el curso existe, entonces insertamos el alumno
        $sql = "INSERT INTO alumnos (nombre, apellido, id_curso) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta de inserción: " . $this->conexion->error);
        }
        $stmt->bind_param("ssi", $nombre, $apellido, $id_curso); // Verifica que id_curso sea correcto
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta de inserción: " . $stmt->error);
        }
        $stmt->close();
    }

  public function modificarAlumno($id_a, $nombre, $apellido, $id_curso) {
    $sql = "UPDATE alumnos SET nombre = ?, apellido = ?, id_curso = ? WHERE id_a = ?";
    $stmt = $this->conexion->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
    }
    $stmt->bind_param("ssii", $nombre, $apellido, $id_curso, $id_a);
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }
    $stmt->close();
}
public function obtenerAlumno($id_a) {
    $sql = "SELECT id_a, nombre, apellido, id_curso FROM alumnos WHERE id_a = ?";
    $stmt = $this->conexion->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
    }

    $stmt->bind_param("i", $id_a);
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();  // Retorna los datos del alumno
    } else {
        throw new Exception("Alumno no encontrado.");
    }
    $stmt->close();
}


// Método para eliminar un alumno
    public function eliminarAlumno($id_a) {
        $sql = "DELETE FROM alumnos WHERE id_a = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }
        $stmt->bind_param("i", $id_a);
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        $stmt->close();
    }

    // Método para listar todos los cursos
    public function listarCursos() {
        $query = "SELECT * FROM cursos";
        $resultado = $this->conexion->query($query);
        if (!$resultado) {
            throw new Exception("Error al consultar la tabla 'cursos': " . $this->conexion->error);
        }
        $cursos = [];
        while ($row = $resultado->fetch_assoc()) {
            $cursos[] = $row;
        }
        return $cursos;
    }

    // Método para registrar un nuevo curso
    public function registrarCurso($curso, $turno, $especialidad) {
        // Verificar si el curso con esa especialidad ya existe en la base de datos
        $sql_check = "SELECT COUNT(*) FROM cursos WHERE curso = ? AND especialidad = ?";
        $stmt_check = $this->conexion->prepare($sql_check);
        if (!$stmt_check) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }
        $stmt_check->bind_param("ss", $curso, $especialidad);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {
            throw new Exception("El curso '$curso' con especialidad '$especialidad' ya existe.");
        }

        // Registrar el curso (sin proporcionar un valor para ID_c, ya que es AUTO_INCREMENT)
        $sql = "INSERT INTO cursos (curso, turno, especialidad) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta de inserción: " . $this->conexion->error);
        }
        $stmt->bind_param("sss", $curso, $turno, $especialidad); // No es necesario incluir ID_c
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta de inserción: " . $stmt->error);
        }
        $stmt->close();
    }
    
    public function obtenerCurso($ID_c) {
    $sql = "SELECT ID_c, curso, especialidad , turno FROM cursos WHERE ID_c = ?";
    $stmt = $this->conexion->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
    }

    $stmt->bind_param("i", $ID_c);
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();  // Retorna los datos del curso
    } else {
        throw new Exception("curso no encontrado.");
    }
    $stmt->close();
}
    public function modificarCurso($id_c, $curso_nombre, $turno, $especialidad) {
    $sql = "UPDATE cursos SET curso = ?, turno = ?, especialidad = ? WHERE ID_c = ?";
    $stmt = $this->conexion->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
    }
    $stmt->bind_param("sssi", $curso_nombre, $turno, $especialidad, $id_c);
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }
    $stmt->close();
}
// Método para eliminar un curso y los alumnos asociados
public function eliminarCurso($id_c) {
    // Eliminar primero los alumnos asociados al curso
    $sqlEliminarAlumnos = "DELETE FROM alumnos WHERE id_curso = ?";
    $stmtAlumnos = $this->conexion->prepare($sqlEliminarAlumnos);
    if (!$stmtAlumnos) {
        throw new Exception("Error al preparar la consulta de eliminación de alumnos: " . $this->conexion->error);
    }
    $stmtAlumnos->bind_param("i", $id_c);
    if (!$stmtAlumnos->execute()) {
        throw new Exception("Error al eliminar los alumnos asociados al curso: " . $stmtAlumnos->error);
    }
    $stmtAlumnos->close();

    // Eliminar el curso
    $sqlEliminarCurso = "DELETE FROM cursos WHERE ID_c = ?";
    $stmtCurso = $this->conexion->prepare($sqlEliminarCurso);
    if (!$stmtCurso) {
        throw new Exception("Error al preparar la consulta de eliminación de curso: " . $this->conexion->error);
    }
    $stmtCurso->bind_param("i", $id_c);
    if (!$stmtCurso->execute()) {
        throw new Exception("Error al eliminar el curso: " . $stmtCurso->error);
    }
    $stmtCurso->close();
}

    // Otros métodos (modificarCurso, eliminarCurso, listarProfesores, etc.) se pueden optimizar de forma similar...
}
?>
