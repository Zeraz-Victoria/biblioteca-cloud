<?php
class Conexion {
    private $host = "127.0.0.1"; 
    private $dbname = "db_biblioteca";
    private $username = "user_final";
    private $password = "pass_final";
    private $port = "5432"; 

    public function conectar(){
        try{
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            $conn = new PDO($dsn, $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;    
        } catch (PDOException $e){
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>
