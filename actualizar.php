<?php
require_once 'conexion.php';

$titulo_pagina = 'Actualizar paciente | Clínica Santa Aurora';
$pagina_activa = 'mostrar';

$error = '';
$paciente = null;
$doctores = [];

// Cargar doctores disponibles para el select
$stmt_drs = $conn->prepare('SELECT id_dr, nombre_dr FROM doctor ORDER BY id_dr ASC');
if ($stmt_drs && $stmt_drs->execute()) {
    $res_drs = $stmt_drs->get_result();
    while ($fila_dr = $res_drs->fetch_assoc()) {
        $doctores[] = $fila_dr;
    }
    $stmt_drs->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_raw = isset($_POST['id']) ? trim($_POST['id']) : '';
    $nombre_paciente = isset($_POST['nombre_paciente']) ? trim($_POST['nombre_paciente']) : '';
    $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
    $categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : '';
    $diagnostico = isset($_POST['diagnostico']) ? trim($_POST['diagnostico']) : '';
    $id_dr_raw = isset($_POST['id_dr']) ? trim($_POST['id_dr']) : '';

    $id = filter_var($id_raw, FILTER_VALIDATE_INT);
    $id_dr = filter_var($id_dr_raw, FILTER_VALIDATE_INT);

    if (
        $id === false || $id <= 0 ||
        $nombre_paciente === '' ||
        $direccion === '' ||
        !in_array($categoria, ['asegurado', 'no asegurado'], true) ||
        $diagnostico === '' ||
        $id_dr === false || $id_dr <= 0
    ) {
        $error = 'Todos los campos son obligatorios y deben tener un formato válido.';
        $paciente = [
            'id' => $id_raw,
            'nombre_paciente' => $nombre_paciente,
            'direccion' => $direccion,
            'categoria' => $categoria,
            'diagnostico' => $diagnostico,
            'id_dr' => $id_dr_raw,
        ];
    } else {
        $stmt_chk = $conn->prepare('SELECT id_dr FROM doctor WHERE id_dr = ?');
        $doctor_valido = false;
        if ($stmt_chk) {
            $stmt_chk->bind_param('i', $id_dr);
            $stmt_chk->execute();
            $stmt_chk->store_result();
            $doctor_valido = $stmt_chk->num_rows > 0;
            $stmt_chk->close();
        }

        if (!$doctor_valido) {
            $error = 'El doctor seleccionado no existe en la base de datos.';
            $paciente = [
                'id' => $id,
                'nombre_paciente' => $nombre_paciente,
                'direccion' => $direccion,
                'categoria' => $categoria,
                'diagnostico' => $diagnostico,
                'id_dr' => $id_dr,
            ];
        } else {
            $sql_upd = 'UPDATE pacientes
                        SET nombre_paciente = ?, direccion = ?, categoria = ?, diagnostico = ?, id_dr = ?
                        WHERE id = ?';
            $stmt_upd = $conn->prepare($sql_upd);

            if ($stmt_upd) {
                $stmt_upd->bind_param('ssssii', $nombre_paciente, $direccion, $categoria, $diagnostico, $id_dr, $id);
                if ($stmt_upd->execute()) {
                    $stmt_upd->close();
                    $conn->close();
                    header('Location: mostrar.php?categoria=todos&estado=actualizado');
                    exit;
                }
                $stmt_upd->close();
            }
            $error = 'Ocurrió un error al actualizar el registro del paciente.';
            $paciente = [
                'id' => $id,
                'nombre_paciente' => $nombre_paciente,
                'direccion' => $direccion,
                'categoria' => $categoria,
                'diagnostico' => $diagnostico,
                'id_dr' => $id_dr,
            ];
        }
    }
} else {
    $id_raw = isset($_GET['id']) ? trim($_GET['id']) : '';
    $id = filter_var($id_raw, FILTER_VALIDATE_INT);

    if ($id === false || $id <= 0) {
        $error = 'ID de paciente no válido.';
    } else {
        $stmt_sel = $conn->prepare(
            'SELECT id, nombre_paciente, direccion, categoria, diagnostico, id_dr FROM pacientes WHERE id = ?'
        );
        if ($stmt_sel) {
            $stmt_sel->bind_param('i', $id);
            if ($stmt_sel->execute()) {
                $res = $stmt_sel->get_result();
                $paciente = $res->fetch_assoc();
                if (!$paciente) {
                    $error = 'No se encontró ningún paciente con el ID especificado.';
                }
            } else {
                $error = 'Error al consultar los datos del paciente.';
            }
            $stmt_sel->close();
        }
    }
}

require_once 'includes/header.php';
?>

<section class="page-header page-header-compact">
    <div class="container">
        <span class="eyebrow">Edición de Expediente</span>
        <h1>Actualizar paciente</h1>
        <p class="page-lead">Modifique la información clínica o el médico asignado y guarde los cambios.</p>
    </div>
</section>

<section class="section-pad">
    <div class="container form-container">
        <?php if ($error !== ''): ?>
            <div class="alert alert-error" role="alert">
                <span><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($paciente): ?>
            <form method="POST" action="actualizar.php" class="editorial-form needs-validation" novalidate>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $paciente['id'], ENT_QUOTES, 'UTF-8'); ?>">

                <div class="form-panel-head">
                    <h2>Expediente #<?php echo htmlspecialchars((string) $paciente['id'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p>Todos los campos son obligatorios para conservar la integridad de la historia clínica.</p>
                </div>

                <div class="form-grid">
                    <div class="field-group">
                        <label for="nombre_paciente">Nombre del paciente</label>
                        <input
                            type="text"
                            id="nombre_paciente"
                            name="nombre_paciente"
                            maxlength="120"
                            required
                            value="<?php echo htmlspecialchars($paciente['nombre_paciente'], ENT_QUOTES, 'UTF-8'); ?>"
                        >
                        <span class="field-error" aria-live="polite"></span>
                    </div>

                    <div class="field-group">
                        <label for="id_dr">Doctor asignado</label>
                        <select id="id_dr" name="id_dr" required>
                            <?php foreach ($doctores as $dr): ?>
                                <option
                                    value="<?php echo htmlspecialchars((string) $dr['id_dr'], ENT_QUOTES, 'UTF-8'); ?>"
                                    <?php echo (string) $dr['id_dr'] === (string) $paciente['id_dr'] ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($dr['nombre_dr'] . ' (ID ' . $dr['id_dr'] . ')', ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-error" aria-live="polite"></span>
                    </div>

                    <div class="field-group field-full">
                        <label for="direccion">Dirección</label>
                        <input
                            type="text"
                            id="direccion"
                            name="direccion"
                            maxlength="200"
                            required
                            value="<?php echo htmlspecialchars($paciente['direccion'], ENT_QUOTES, 'UTF-8'); ?>"
                        >
                        <span class="field-error" aria-live="polite"></span>
                    </div>

                    <div class="field-group field-full">
                        <span class="field-label-text">Categoría de cobertura</span>
                        <div class="radio-cards">
                            <label class="radio-card">
                                <input
                                    type="radio"
                                    name="categoria"
                                    value="asegurado"
                                    required
                                    <?php echo $paciente['categoria'] === 'asegurado' ? 'checked' : ''; ?>
                                >
                                <span class="radio-card-body">
                                    <strong>Asegurado</strong>
                                    <small>Paciente con póliza o cobertura activa</small>
                                </span>
                            </label>
                            <label class="radio-card">
                                <input
                                    type="radio"
                                    name="categoria"
                                    value="no asegurado"
                                    required
                                    <?php echo $paciente['categoria'] === 'no asegurado' ? 'checked' : ''; ?>
                                >
                                <span class="radio-card-body">
                                    <strong>No asegurado</strong>
                                    <small>Atención particular o consulta directa</small>
                                </span>
                            </label>
                        </div>
                        <span class="field-error" aria-live="polite"></span>
                    </div>

                    <div class="field-group field-full">
                        <label for="diagnostico">Diagnóstico</label>
                        <textarea id="diagnostico" name="diagnostico" required><?php echo htmlspecialchars($paciente['diagnostico'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                        <span class="field-error" aria-live="polite"></span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="mostrar.php" class="btn btn-outline-teal">Cancelar</a>
                </div>
            </form>
        <?php else: ?>
            <div class="empty-state">
                <h3>Registro no disponible</h3>
                <p>No fue posible cargar el expediente solicitado.</p>
                <a href="mostrar.php" class="btn btn-outline-teal">Volver al listado</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
$conn->close();
require_once 'includes/footer.php';
?>
