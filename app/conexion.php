<?php
class Conexion {
    private $host = "db_final"; // Updated to match docker service name
    private $dbname = "db_biblioteca";
    private $username = "user_final";
    private $password = "pass_final";
    private $port = "5432"; 

    public function conectar(){
        $host = getenv('DB_HOST') ?: 'localhost';
        $dbname = getenv('DB_NAME') ?: 'db_biblioteca';
        $username = getenv('DB_USER') ?: 'user_final';
        $password = getenv('DB_PASS') ?: 'pass_final';
        $port = getenv('DB_PORT') ?: '5433'; // 5433 si es local, 5432 si es Docker (pasado por env)

        try{
            $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
            $conn = new PDO($dsn, $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;    
        } catch (PDOException $e){
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>