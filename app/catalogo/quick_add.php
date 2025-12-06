<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id_escuela'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

require_once '../conexion.php';

try {
    $conexion = new Conexion();
    $conn = $conexion->conectar();
    $id_escuela = $_SESSION['id_escuela'];
    
    // Leer JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $type = $input['type'] ?? '';
    $name = trim($input['name'] ?? '');

    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio']);
        exit;
    }

    if ($type === 'author') {
        // Verificar duplicado
        $check = $conn->prepare("SELECT Id_autor FROM AUTOR WHERE LOWER(Nombre_autor) = LOWER(?) AND id_escuela = ?");
        $check->execute([$name, $id_escuela]);
        if ($check->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'El autor ya existe.']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO AUTOR (id_escuela, Nombre_autor) VALUES (?, ?) RETURNING Id_autor");
        $stmt->execute([$id_escuela, $name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true, 
            'id' => $result['id_autor'], 
            'name' => $name,
            'message' => 'Autor agregado correctamente'
        ]);

    } elseif ($type === 'category') {
        // Verificar duplicado
        $check = $conn->prepare("SELECT Id_categoria FROM CATEGORIA WHERE LOWER(Nombre_categoria) = LOWER(?) AND id_escuela = ?");
        $check->execute([$name, $id_escuela]);
        if ($check->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'La categoría ya existe.']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO CATEGORIA (id_escuela, Nombre_categoria) VALUES (?, ?) RETURNING Id_categoria");
        $stmt->execute([$id_escuela, $name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true, 
            'id' => $result['id_categoria'], 
            'name' => $name,
            'message' => 'Categoría agregada correctamente'
        ]);

    } else {
        echo json_encode(['success' => false, 'message' => 'Acción inválida']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de BD: ' . $e->getMessage()]);
}
?>
