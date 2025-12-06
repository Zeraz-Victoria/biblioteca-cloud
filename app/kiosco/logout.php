<?php
session_start();

// Solo limpiar las variables de sesión del alumno/usuario del kiosco
unset($_SESSION['kiosco_socio']);
unset($_SESSION['return_socio']);

// NO destruir la sesión completa para mantener la 'id_escuela' activa
// session_destroy(); 

header("Location: index.php");
exit;
?>
