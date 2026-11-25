<?php
require_once 'conexion.php';

$titulo_pagina = 'Registrar | Clínica Santa Aurora';
$pagina_activa = 'registrar';

$modos_validos = ['doctor', 'paciente', 'completo'];
$modo_activo = 'completo';

if (isset($_GET['modo'])) {
    $m_get = trim($_GET['modo']);
    if (in_array($m_get, $modos_validos, true)) {
        $modo_activo = $m_get;
    }
}

$mensaje = '';
$tipo_mensaje = '';

// Valores por modo para repoblar en caso de error
$dr_id = '';
$dr_nombre = '';

$pac_nombre = '';
$pac_direccion = '';
$pac_categoria = '';
$pac_diagnostico = '';
$pac_id_dr = '';

$comp_id_dr = '';
$comp_nombre_dr = '';
$comp_nombre_pac = '';
$comp_direccion = '';
$comp_categoria = '';
$comp_diagnostico = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modo_post = isset($_POST['modo']) ? trim($_POST['modo']) : 'completo';
    if (in_array($modo_post, $modos_validos, true)) {
        $modo_activo = $modo_post;
    }

    if ($modo_activo === 'doctor') {
        $dr_id_raw = isset($_POST['id_dr']) ? trim($_POST['id_dr']) : '';
        $dr_nombre = isset($_POST['nombre_dr']) ? trim($_POST['nombre_dr']) : '';
        $dr_id = $dr_id_raw;
        $id_dr_int = filter_var($dr_id_raw, FILTER_VALIDATE_INT);

        if ($id_dr_int === false || $id_dr_int <= 0 || $dr_nombre === '') {
            $mensaje = 'Ingrese un ID numérico positivo y el nombre completo del doctor.';
            $tipo_mensaje = 'error';
        } else {
            $stmt_chk = $conn->prepare('SELECT id_dr FROM doctor WHERE id_dr = ?');
            $existe = false;
            if ($stmt_chk) {
                $stmt_chk->bind_param('i', $id_dr_int);
                $stmt_chk->execute();
                $stmt_chk->store_result();
                $existe = $stmt_chk->num_rows > 0;
                $stmt_chk->close();
            }

            if ($existe) {
                $mensaje = 'El ID del doctor (' . $id_dr_int . ') ya se encuentra registrado. Elija otro ID.';
                $tipo_mensaje = 'error';
            } else {
                $stmt_ins = $conn->prepare('INSERT INTO doctor (id_dr, nombre_dr) VALUES (?, ?)');
                if ($stmt_ins) {
                    $stmt_ins->bind_param('is', $id_dr_int, $dr_nombre);
                    if ($stmt_ins->execute()) {
                        $mensaje = 'Doctor "' . $dr_nombre . '" (ID ' . $id_dr_int . ') registrado correctamente.';
                        $tipo_mensaje = 'exito';
                        $dr_id = '';
                        $dr_nombre = '';
                    } else {
                        $mensaje = 'No se pudo registrar el doctor.';
                        $tipo_mensaje = 'error';
                    }
                    $stmt_ins->close();
                } else {
                    $mensaje = 'Error al preparar el registro del doctor.';
                    $tipo_mensaje = 'error';
                }
            }
        }
    } elseif ($modo_activo === 'paciente') {
        $pac_nombre = isset($_POST['nombre_paciente']) ? trim($_POST['nombre_paciente']) : '';
        $pac_direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
        $pac_categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : '';
        $pac_diagnostico = isset($_POST['diagnostico']) ? trim($_POST['diagnostico']) : '';
        $pac_id_dr_raw = isset($_POST['id_dr']) ? trim($_POST['id_dr']) : '';
        $pac_id_dr = $pac_id_dr_raw;
        $id_dr_int = filter_var($pac_id_dr_raw, FILTER_VALIDATE_INT);

        if (
            $pac_nombre === '' ||
            $pac_direccion === '' ||
            !in_array($pac_categoria, ['asegurado', 'no asegurado'], true) ||
            $pac_diagnostico === '' ||
            $id_dr_int === false || $id_dr_int <= 0
        ) {
            $mensaje = 'Complete todos los campos del paciente y seleccione un doctor válido.';
            $tipo_mensaje = 'error';
        } else {
            $stmt_chk = $conn->prepare('SELECT id_dr FROM doctor WHERE id_dr = ?');
            $doctor_valido = false;
            if ($stmt_chk) {
                $stmt_chk->bind_param('i', $id_dr_int);
                $stmt_chk->execute();
                $stmt_chk->store_result();
                $doctor_valido = $stmt_chk->num_rows > 0;
                $stmt_chk->close();
            }

            if (!$doctor_valido) {
                $mensaje = 'El doctor seleccionado no existe en el sistema.';
                $tipo_mensaje = 'error';
            } else {
                $stmt_pac = $conn->prepare(
                    'INSERT INTO pacientes (nombre_paciente, direccion, categoria, diagnostico, id_dr)
                     VALUES (?, ?, ?, ?, ?)'
                );
                if ($stmt_pac) {
                    $stmt_pac->bind_param('ssssi', $pac_nombre, $pac_direccion, $pac_categoria, $pac_diagnostico, $id_dr_int);
                    if ($stmt_pac->execute()) {
                        $mensaje = 'Paciente "' . $pac_nombre . '" registrado correctamente.';
                        $tipo_mensaje = 'exito';
                        $pac_nombre = '';
                        $pac_direccion = '';
                        $pac_categoria = '';
                        $pac_diagnostico = '';
                        $pac_id_dr = '';
                    } else {
                        $mensaje = 'No se pudo guardar el registro del paciente.';
                        $tipo_mensaje = 'error';
                    }
                    $stmt_pac->close();
                } else {
                    $mensaje = 'Error al preparar el registro del paciente.';
                    $tipo_mensaje = 'error';
                }
            }
        }
    } else {
        // Modo 3: Formulario único (doctor + paciente)
        $comp_id_dr_raw = isset($_POST['id_dr']) ? trim($_POST['id_dr']) : '';
        $comp_nombre_dr = isset($_POST['nombre_dr']) ? trim($_POST['nombre_dr']) : '';
        $comp_nombre_pac = isset($_POST['nombre_paciente']) ? trim($_POST['nombre_paciente']) : '';
        $comp_direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
        $comp_categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : '';
        $comp_diagnostico = isset($_POST['diagnostico']) ? trim($_POST['diagnostico']) : '';

        $comp_id_dr = $comp_id_dr_raw;
        $id_dr_int = filter_var($comp_id_dr_raw, FILTER_VALIDATE_INT);

        if (
            $id_dr_int === false || $id_dr_int <= 0 ||
            $comp_nombre_dr === '' ||
            $comp_nombre_pac === '' ||
            $comp_direccion === '' ||
            !in_array($comp_categoria, ['asegurado', 'no asegurado'], true) ||
            $comp_diagnostico === ''
        ) {
            $mensaje = 'Por favor complete todos los campos correctamente. El ID del doctor debe ser un número entero positivo.';
            $tipo_mensaje = 'error';
        } else {
            $conn->begin_transaction();

            try {
                $stmt_buscar = $conn->prepare('SELECT id_dr, nombre_dr FROM doctor WHERE id_dr = ?');
                if (!$stmt_buscar) {
                    throw new Exception('Error al preparar la verificación del doctor.');
                }
                $stmt_buscar->bind_param('i', $id_dr_int);
                if (!$stmt_buscar->execute()) {
                    throw new Exception('Error al verificar el doctor.');
                }
                $res_doctor = $stmt_buscar->get_result();
                $doctor_existente = $res_doctor->fetch_assoc();
                $stmt_buscar->close();

                if (!$doctor_existente) {
                    $stmt_dr = $conn->prepare('INSERT INTO doctor (id_dr, nombre_dr) VALUES (?, ?)');
                    if (!$stmt_dr) {
                        throw new Exception('Error al preparar el registro del doctor.');
                    }
                    $stmt_dr->bind_param('is', $id_dr_int, $comp_nombre_dr);
                    if (!$stmt_dr->execute()) {
                        throw new Exception('No se pudo registrar el doctor.');
                    }
                    $stmt_dr->close();
                    $detalle_dr = 'Se registró el nuevo doctor (ID: ' . $id_dr_int . ').';
                } else {
                    $detalle_dr = 'El doctor con ID ' . $id_dr_int . ' (' . $doctor_existente['nombre_dr'] . ') ya existía y fue reutilizado sin duplicarse.';
                }

                $stmt_pac = $conn->prepare(
                    'INSERT INTO pacientes (nombre_paciente, direccion, categoria, diagnostico, id_dr)
                     VALUES (?, ?, ?, ?, ?)'
                );
                if (!$stmt_pac) {
                    throw new Exception('Error al preparar el registro del paciente.');
                }
                $stmt_pac->bind_param('ssssi', $comp_nombre_pac, $comp_direccion, $comp_categoria, $comp_diagnostico, $id_dr_int);
                if (!$stmt_pac->execute()) {
                    throw new Exception('No se pudo registrar el paciente.');
                }
                $stmt_pac->close();

                $conn->commit();

                $mensaje = 'Paciente guardado correctamente. ' . $detalle_dr;
                $tipo_mensaje = 'exito';

                $comp_id_dr = '';
                $comp_nombre_dr = '';
                $comp_nombre_pac = '';
                $comp_direccion = '';
                $comp_categoria = '';
                $comp_diagnostico = '';
            } catch (Exception $e) {
                $conn->rollback();
                $mensaje = $e->getMessage();
                $tipo_mensaje = 'error';
            }
        }
    }
}

// Cargar doctores disponibles para el modo 2 (registrar paciente)
$doctores_disponibles = [];
$stmt_list_dr = $conn->prepare('SELECT id_dr, nombre_dr FROM doctor ORDER BY id_dr ASC');
if ($stmt_list_dr && $stmt_list_dr->execute()) {
    $res_list_dr = $stmt_list_dr->get_result();
    while ($fila_dr = $res_list_dr->fetch_assoc()) {
        $doctores_disponibles[] = $fila_dr;
    }
    $stmt_list_dr->close();
}

require_once 'includes/header.php';
?>

<section class="page-header page-header-compact">
    <div class="container">
        <span class="eyebrow">Admisión &amp; Expedientes</span>
        <h1>Registrar</h1>
        <p class="page-lead">Seleccione el tipo de ingreso clínico: registrar un médico, asociar un paciente a un doctor existente o crear ambos en un solo formulario.</p>
    </div>
</section>

<section class="section-pad">
    <div class="container form-container">
        <?php if ($mensaje !== ''): ?>
            <div class="alert <?php echo $tipo_mensaje === 'exito' ? 'alert-success' : 'alert-error'; ?>" role="status">
                <span><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></span>
                <?php if ($tipo_mensaje === 'exito'): ?>
                    <a href="mostrar.php" class="alert-link">Ver en Mostrar pacientes</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Selector segmentado de 3 modos -->
        <div class="mode-switcher" role="tablist" aria-label="Modo de registro">
            <button
                type="button"
                class="mode-tab <?php echo $modo_activo === 'doctor' ? 'is-active' : ''; ?>"
                role="tab"
                data-mode="doctor"
                aria-selected="<?php echo $modo_activo === 'doctor' ? 'true' : 'false'; ?>"
            >
                Registrar doctor
            </button>
            <button
                type="button"
                class="mode-tab <?php echo $modo_activo === 'paciente' ? 'is-active' : ''; ?>"
                role="tab"
                data-mode="paciente"
                aria-selected="<?php echo $modo_activo === 'paciente' ? 'true' : 'false'; ?>"
            >
                Registrar paciente
            </button>
            <button
                type="button"
                class="mode-tab <?php echo $modo_activo === 'completo' ? 'is-active' : ''; ?>"
                role="tab"
                data-mode="completo"
                aria-selected="<?php echo $modo_activo === 'completo' ? 'true' : 'false'; ?>"
            >
                Paciente + doctor nuevo
            </button>
        </div>

        <!-- MODO 1: Registrar doctor -->
        <div class="mode-panel <?php echo $modo_activo === 'doctor' ? 'is-active' : ''; ?>" data-mode-panel="doctor">
            <form method="POST" action="registrar.php" class="editorial-form needs-validation" novalidate>
                <input type="hidden" name="modo" value="doctor">

                <div class="form-panel-head">
                    <h2>Nuevo médico en el directorio</h2>
                    <p>Asigne un identificador único y el nombre del especialista. No se permiten IDs duplicados.</p>
                </div>

                <div class="form-grid">
                    <div class="field-group">
                        <label for="dr_id_dr">ID del Doctor</label>
                        <input
                            type="number"
                            id="dr_id_dr"
                            name="id_dr"
                            min="1"
                            required
                            placeholder="Ej. 104"
                            value="<?php echo htmlspecialchars($dr_id, ENT_QUOTES, 'UTF-8'); ?>"
                        >
                        <span class="field-error" aria-live="polite"></span>
                    </div>

                    <div class="field-group">
                        <label for="dr_nombre_dr">Nombre del Doctor</label>
                        <input
                            type="text"
                            id="dr_nombre_dr"
                            name="nombre_dr"
                            maxlength="100"
                            required
                            placeholder="Ej. Dr. Mateo Villarreal"
                            value="<?php echo htmlspecialchars($dr_nombre, ENT_QUOTES, 'UTF-8'); ?>"
                        >
                        <span class="field-error" aria-live="polite"></span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar doctor</button>
                    <a href="mostrar.php" class="btn btn-outline-teal">Mostrar pacientes</a>
                </div>
            </form>
        </div>

        <!-- MODO 2: Registrar paciente (doctor existente) -->
        <div class="mode-panel <?php echo $modo_activo === 'paciente' ? 'is-active' : ''; ?>" data-mode-panel="paciente">
            <?php if (count($doctores_disponibles) === 0): ?>
                <div class="empty-state">
                    <h3>Aún no hay doctores registrados</h3>
                    <p>Para asociar un paciente de forma individual, registre primero al médico tratante.</p>
                    <button type="button" class="btn btn-primary js-switch-mode" data-target-mode="doctor">Ir a Registrar doctor</button>
                </div>
            <?php else: ?>
                <form method="POST" action="registrar.php" class="editorial-form needs-validation" novalidate>
                    <input type="hidden" name="modo" value="paciente">

                    <div class="form-panel-head">
                        <h2>Nuevo paciente</h2>
                        <p>Seleccione el médico asignado del directorio actual e ingrese los datos clínicos.</p>
                    </div>

                    <div class="form-grid">
                        <div class="field-group">
                            <label for="pac_nombre">Nombre del Paciente</label>
                            <input
                                type="text"
                                id="pac_nombre"
                                name="nombre_paciente"
                                maxlength="120"
                                required
                                placeholder="Ej. Lucía Herrera"
                                value="<?php echo htmlspecialchars($pac_nombre, ENT_QUOTES, 'UTF-8'); ?>"
                            >
                            <span class="field-error" aria-live="polite"></span>
                        </div>

                        <div class="field-group">
                            <label for="pac_id_dr">Doctor asignado</label>
                            <select id="pac_id_dr" name="id_dr" required>
                                <option value="">-- Seleccione un doctor --</option>
                                <?php foreach ($doctores_disponibles as $dr): ?>
                                    <option
                                        value="<?php echo htmlspecialchars((string) $dr['id_dr'], ENT_QUOTES, 'UTF-8'); ?>"
                                        <?php echo (string) $dr['id_dr'] === (string) $pac_id_dr ? 'selected' : ''; ?>
                                    >
                                        <?php echo htmlspecialchars($dr['nombre_dr'] . ' (ID ' . $dr['id_dr'] . ')', ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-error" aria-live="polite"></span>
                        </div>

                        <div class="field-group field-full">
                            <label for="pac_direccion">Dirección</label>
                            <input
                                type="text"
                                id="pac_direccion"
                                name="direccion"
                                maxlength="200"
                                required
                                placeholder="Ej. San Francisco, Calle 73, Casa 18"
                                value="<?php echo htmlspecialchars($pac_direccion, ENT_QUOTES, 'UTF-8'); ?>"
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
                                        <?php echo $pac_categoria === 'asegurado' ? 'checked' : ''; ?>
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
                                        <?php echo $pac_categoria === 'no asegurado' ? 'checked' : ''; ?>
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
                            <label for="pac_diagnostico">Diagnóstico</label>
                            <textarea
                                id="pac_diagnostico"
                                name="diagnostico"
                                required
                                placeholder="Describa el diagnóstico e indicaciones médicas..."
                            ><?php echo htmlspecialchars($pac_diagnostico, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <span class="field-error" aria-live="polite"></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Guardar paciente</button>
                        <a href="mostrar.php" class="btn btn-outline-teal">Mostrar pacientes</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <!-- MODO 3: Formulario único (Paciente + Doctor) -->
        <div class="mode-panel <?php echo $modo_activo === 'completo' ? 'is-active' : ''; ?>" data-mode-panel="completo">
            <form method="POST" action="registrar.php" class="editorial-form needs-validation" novalidate>
                <input type="hidden" name="modo" value="completo">

                <div class="form-panel-head">
                    <h2>Registro unificado de doctor y paciente</h2>
                    <p>Si el ID del doctor ya existe en la base de datos, se asociará el paciente sin duplicar el registro médico.</p>
                </div>

                <fieldset class="form-fieldset">
                    <legend>Datos del Doctor</legend>
                    <div class="form-grid">
                        <div class="field-group">
                            <label for="comp_id_dr">ID del Doctor</label>
                            <input
                                type="number"
                                id="comp_id_dr"
                                name="id_dr"
                                min="1"
                                required
                                placeholder="Ej. 101"
                                value="<?php echo htmlspecialchars($comp_id_dr, ENT_QUOTES, 'UTF-8'); ?>"
                            >
                            <span class="field-hint">Si el ID ya existe, se reutilizará automáticamente.</span>
                            <span class="field-error" aria-live="polite"></span>
                        </div>

                        <div class="field-group">
                            <label for="comp_nombre_dr">Nombre del Doctor</label>
                            <input
                                type="text"
                                id="comp_nombre_dr"
                                name="nombre_dr"
                                maxlength="100"
                                required
                                placeholder="Ej. Dra. Elena Mendoza"
                                value="<?php echo htmlspecialchars($comp_nombre_dr, ENT_QUOTES, 'UTF-8'); ?>"
                            >
                            <span class="field-error" aria-live="polite"></span>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="form-fieldset">
                    <legend>Datos del Paciente</legend>
                    <div class="form-grid">
                        <div class="field-group field-full">
                            <label for="comp_nombre_pac">Nombre del Paciente</label>
                            <input
                                type="text"
                                id="comp_nombre_pac"
                                name="nombre_paciente"
                                maxlength="120"
                                required
                                placeholder="Ej. Pedro Sánchez"
                                value="<?php echo htmlspecialchars($comp_nombre_pac, ENT_QUOTES, 'UTF-8'); ?>"
                            >
                            <span class="field-error" aria-live="polite"></span>
                        </div>

                        <div class="field-group field-full">
                            <label for="comp_direccion">Dirección</label>
                            <input
                                type="text"
                                id="comp_direccion"
                                name="direccion"
                                maxlength="200"
                                required
                                placeholder="Ej. Calle 50, Edif. Sol, Apto 3"
                                value="<?php echo htmlspecialchars($comp_direccion, ENT_QUOTES, 'UTF-8'); ?>"
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
                                        <?php echo $comp_categoria === 'asegurado' ? 'checked' : ''; ?>
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
                                        <?php echo $comp_categoria === 'no asegurado' ? 'checked' : ''; ?>
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
                            <label for="comp_diagnostico">Diagnóstico</label>
                            <textarea
                                id="comp_diagnostico"
                                name="diagnostico"
                                required
                                placeholder="Describa el diagnóstico y las indicaciones médicas..."
                            ><?php echo htmlspecialchars($comp_diagnostico, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <span class="field-error" aria-live="polite"></span>
                        </div>
                    </div>
                </fieldset>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar registro</button>
                    <a href="mostrar.php" class="btn btn-outline-teal">Mostrar pacientes</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php
$conn->close();
require_once 'includes/footer.php';
?>
