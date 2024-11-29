<?php
if (!class_exists('Conexion')) {
    class Conexion {
        private $conexion;

        public function __construct() {
            $host = 'localhost';
            $usuario = 'root';
            $contraseña = '';
            $base_de_datos = 'thabase';

            $this->conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

            if ($this->conexion->connect_error) {
                die("Conexión fallida: " . $this->conexion->connect_error);
            } else {
                ;
            }
        }

        public function getConexion() {
            return $this->conexion;
        }

        public function cerrarConexion() {
            $this->conexion->close();
        }
    }
}
?>
