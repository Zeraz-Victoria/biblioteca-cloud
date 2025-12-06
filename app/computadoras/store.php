session_start();
if (!isset($_SESSION['id_escuela'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_escuela = $_SESSION['id_escuela'];
    $nombre = $_POST['nombre'];
    $marca = $_POST['marca'];
    $estado = true; // Disponible

    try {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = "INSERT INTO COMPUTADORA (id_escuela, nombre, marca, estado) VALUES (:id_escuela, :nombre, :marca, :estado)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_escuela', $id_escuela);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':marca', $marca);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_BOOL);

        if ($stmt->execute()) {
            header("Location: index.php?msg=registrado");
        } else {
            echo "Error al registrar.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
