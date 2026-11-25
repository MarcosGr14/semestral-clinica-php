<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $conn->close();
    header('Location: mostrar.php?estado=error');
    exit;
}

$id_raw = isset($_POST['id']) ? trim($_POST['id']) : '';
$categoria_raw = isset($_POST['categoria']) ? trim($_POST['categoria']) : 'todos';
$categorias_validas = ['todos', 'asegurado', 'no asegurado'];
$categoria = in_array($categoria_raw, $categorias_validas, true) ? $categoria_raw : 'todos';
$q = isset($_POST['q']) ? trim($_POST['q']) : '';
$id_dr_raw = isset($_POST['id_dr']) ? trim($_POST['id_dr']) : '';

$id = filter_var($id_raw, FILTER_VALIDATE_INT);

$query_base = 'categoria=' . urlencode($categoria);
if ($q !== '') {
    $query_base .= '&q=' . urlencode($q);
}
if ($id_dr_raw !== '' && filter_var($id_dr_raw, FILTER_VALIDATE_INT) !== false) {
    $query_base .= '&id_dr=' . urlencode($id_dr_raw);
}

if ($id === false || $id <= 0) {
    $conn->close();
    header('Location: mostrar.php?' . $query_base . '&estado=error');
    exit;
}

$stmt = $conn->prepare('DELETE FROM pacientes WHERE id = ?');
$eliminado = false;

if ($stmt) {
    $stmt->bind_param('i', $id);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $eliminado = true;
    }
    $stmt->close();
}

$conn->close();
$estado = $eliminado ? 'eliminado' : 'error';
header('Location: mostrar.php?' . $query_base . '&estado=' . $estado);
exit;
?>
