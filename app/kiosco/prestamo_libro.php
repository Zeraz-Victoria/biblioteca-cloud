<?php
session_start();
if (!isset($_SESSION['id_escuela'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../conexion.php';
$conexion = new Conexion();
$conn = $conexion->conectar();

// AJAX Request Handler
if (isset($_GET['ajax'])) {
    $q = $_GET['q'] ?? '';
    $cat = $_GET['cat'] ?? '';
    $id_escuela = $_SESSION['id_escuela'];
    
    $sql = "SELECT * FROM LIBRO WHERE Ejemplares_disponibles > 0 AND id_escuela = :escuela";
    $params = [':escuela' => $id_escuela];

    if ($q) {
        $sql .= " AND Titulo_libro ILIKE :q";
        $params[':q'] = "%" . $q . "%";
    }

    if ($cat) {
        $sql .= " AND Id_categoria = :cat";
        $params[':cat'] = $cat;
    }

    $sql .= " ORDER BY Titulo_libro ASC LIMIT 1000";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($libros)) {
        echo '<div style="grid-column: 1/-1; text-align: center; padding: 40px;">
                <i class="fas fa-search fa-3x" style="color: #ccc; margin-bottom: 20px;"></i>
                <p>No encontramos libros con esa búsqueda.</p>
              </div>';
    } else {
        foreach($libros as $libro) {
            // Generar imagen basada en el título usando un servicio de placeholders
            $titulo_url = urlencode(substr($libro['titulo_libro'], 0, 20)); // Cortar para que quepa
            $cover_url = "https://placehold.co/200x300/003366/ffffff?text=" . $titulo_url;
            
            echo '<div class="kiosco-book-card" style="animation: fadeIn 0.3s;">
                    <div class="book-cover">
                        <img src="' . $cover_url . '" alt="Portada" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
                    </div>
                    <div class="book-info">
                        <h3 title="' . htmlspecialchars($libro['titulo_libro']) . '">' . htmlspecialchars($libro['titulo_libro']) . '</h3>
                        <span class="badge ' . ($libro['ejemplares_disponibles'] > 0 ? 'badge-available' : 'badge-out') . '">
                            ' . ($libro['ejemplares_disponibles'] > 0 ? 'Disponible: ' . $libro['ejemplares_disponibles'] : 'Agotado') . '
                        </span>
                    </div>
                    <a href="validar_nfc.php?action=borrow_book&item_id=' . $libro['id_libro'] . '" class="kiosco-btn btn-borrow-book" style="margin-top: 10px; font-size: 0.9rem; padding: 8px;">
                        Pedir
                    </a>
                </div>';
        }
    }
    exit; // Stop execution for AJAX calls
}

// Initial Page Load
$categorias = $conn->query("SELECT * FROM CATEGORIA ORDER BY Nombre_categoria ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Libro - Kiosco</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .cat-btn {
            background: #16213e;
            border: 1px solid #e94560;
            color: #fff;
            padding: 8px 15px;
            border-radius: 20px;
            margin: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .cat-btn:hover, .cat-btn.active {
            background: #e94560;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="kiosco-mode">
    <div class="kiosco-card" style="max-width: 900px;">
        <h2><i class="fas fa-search"></i> ¿Qué quieres leer hoy?</h2>
        
        <!-- Search Bar -->
        <div style="margin-bottom: 20px;">
            <input type="text" id="search" class="nfc-input" placeholder="Escribe el título del libro..." autofocus>
        </div>

        <!-- Categories -->
        <div style="margin-bottom: 20px; display: flex; flex-wrap: wrap; justify-content: center;">
            <button class="cat-btn active" onclick="filterCat('', this)">Todos</button>
            <?php foreach($categorias as $c): ?>
                <button class="cat-btn" onclick="filterCat(<?= $c['id_categoria'] ?>, this)">
                    <?= htmlspecialchars($c['nombre_categoria']) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Results Container -->
        <div id="results" style="text-align: left; max-height: 500px; overflow-y: auto;">
            <!-- Results will be loaded here via AJAX -->
        </div>

        <a href="index.php" class="kiosco-btn" style="background: #555; margin-top: 20px;">Volver</a>
    </div>

    <script>
        let currentCat = '';
        let timer;

        function loadBooks() {
            const query = document.getElementById('search').value;
            const resultsDiv = document.getElementById('results');
            
            resultsDiv.innerHTML = '<p class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</p>';

            fetch(`prestamo_libro.php?ajax=1&q=${encodeURIComponent(query)}&cat=${currentCat}`)
                .then(response => response.text())
                .then(html => {
                    resultsDiv.innerHTML = html;
                });
        }

        // Instant Search
        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(loadBooks, 300); // Debounce 300ms
        });

        // Category Filter
        function filterCat(catId, btn) {
            currentCat = catId;
            
            // Update active button
            document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            loadBooks();
        }

        // Initial Load
        loadBooks();
    </script>
</body>
</html>
