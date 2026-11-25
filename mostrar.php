<?php
require_once 'conexion.php';

$titulo_pagina = 'Mostrar pacientes | Clínica Santa Aurora';
$pagina_activa = 'mostrar';

$categorias_validas = ['todos', 'asegurado', 'no asegurado'];
$categoria = 'todos';
if (isset($_GET['categoria'])) {
    $cat_recibida = trim($_GET['categoria']);
    if (in_array($cat_recibida, $categorias_validas, true)) {
        $categoria = $cat_recibida;
    }
}

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$id_dr_filtro = 0;
if (isset($_GET['id_dr']) && trim($_GET['id_dr']) !== '') {
    $id_dr_val = filter_var(trim($_GET['id_dr']), FILTER_VALIDATE_INT);
    if ($id_dr_val !== false && $id_dr_val > 0) {
        $id_dr_filtro = $id_dr_val;
    }
}

$mensaje = '';
$tipo_mensaje = '';
if (isset($_GET['estado'])) {
    $estado = trim($_GET['estado']);
    if ($estado === 'actualizado') {
        $mensaje = 'Los datos del paciente se actualizaron correctamente.';
        $tipo_mensaje = 'exito';
    } elseif ($estado === 'eliminado') {
        $mensaje = 'El registro del paciente fue eliminado correctamente.';
        $tipo_mensaje = 'exito';
    } elseif ($estado === 'error') {
        $mensaje = 'No se pudo completar la operación solicitada.';
        $tipo_mensaje = 'error';
    }
}

// Cargar doctores para el filtro opcional
$doctores = [];
$stmt_drs = $conn->prepare('SELECT id_dr, nombre_dr FROM doctor ORDER BY id_dr ASC');
if ($stmt_drs && $stmt_drs->execute()) {
    $res_drs = $stmt_drs->get_result();
    while ($fila_dr = $res_drs->fetch_assoc()) {
        $doctores[] = $fila_dr;
    }
    $stmt_drs->close();
}

// Construir consulta combinada con sentencias preparadas
$where = [];
$types = '';
$params = [];

if ($categoria !== 'todos') {
    $where[] = 'p.categoria = ?';
    $types .= 's';
    $params[] = $categoria;
}

if ($id_dr_filtro > 0) {
    $where[] = 'p.id_dr = ?';
    $types .= 'i';
    $params[] = $id_dr_filtro;
}

if ($q !== '') {
    $like = '%' . $q . '%';
    $where[] = '(p.nombre_paciente LIKE ? OR p.diagnostico LIKE ? OR p.direccion LIKE ? OR CAST(p.id AS CHAR) LIKE ? OR d.nombre_dr LIKE ?)';
    $types .= 'sssss';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

$sql = 'SELECT p.id, p.nombre_paciente, p.direccion, p.categoria, p.diagnostico, p.id_dr, d.nombre_dr
        FROM pacientes p
        INNER JOIN doctor d ON p.id_dr = d.id_dr';
if (count($where) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY p.id ASC';

$pacientes = [];
$error_consulta = '';
$stmt = $conn->prepare($sql);

if ($stmt) {
    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }
    if ($stmt->execute()) {
        $resultado = $stmt->get_result();
        while ($fila = $resultado->fetch_assoc()) {
            $pacientes[] = $fila;
        }
    } else {
        $error_consulta = 'Ocurrió un error al consultar los pacientes.';
    }
    $stmt->close();
} else {
    $error_consulta = 'Ocurrió un error al preparar la consulta.';
}

function obtener_iniciales($nombre) {
    $partes = preg_split('/\s+/u', trim($nombre));
    if (!$partes || count($partes) === 0) {
        return 'PA';
    }
    $primera = mb_substr($partes[0], 0, 1, 'UTF-8');
    $segunda = count($partes) > 1 ? mb_substr($partes[count($partes) - 1], 0, 1, 'UTF-8') : mb_substr($partes[0], 1, 1, 'UTF-8');
    return mb_strtoupper($primera . $segunda, 'UTF-8');
}

$total_resultados = count($pacientes);
$hay_filtros = ($q !== '' || $categoria !== 'todos' || $id_dr_filtro > 0);

require_once 'includes/header.php';
?>

<section class="page-header page-header-compact">
    <div class="container page-header-split">
        <div>
            <span class="eyebrow">Directorio Clínico</span>
            <h1>Pacientes registrados</h1>
            <p class="page-lead">Busque por nombre, diagnóstico, dirección, ID o médico tratante y revise cada expediente al instante.</p>
        </div>
        <a href="registrar.php" class="btn btn-primary">Nuevo registro</a>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <?php if ($mensaje !== ''): ?>
            <div class="alert <?php echo $tipo_mensaje === 'exito' ? 'alert-success' : 'alert-error'; ?>" role="status">
                <span><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($error_consulta !== ''): ?>
            <div class="alert alert-error" role="alert">
                <span><?php echo htmlspecialchars($error_consulta, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        <?php endif; ?>

        <!-- Panel de búsqueda y filtros -->
        <div class="search-panel">
            <form method="GET" action="mostrar.php" id="filterForm" class="search-form">
                <div class="search-bar-wrap">
                    <label for="searchInput" class="sr-only">Buscar pacientes</label>
                    <svg class="search-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input
                        type="search"
                        id="searchInput"
                        name="q"
                        class="search-input"
                        placeholder="Buscar por paciente, diagnóstico, dirección, ID o doctor..."
                        value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>"
                        autocomplete="off"
                    >
                    <button type="submit" class="btn btn-primary search-submit">Buscar</button>
                </div>

                <div class="filters-row">
                    <div class="filter-group">
                        <span class="filter-label">Categoría</span>
                        <div class="segmented-control" role="radiogroup" aria-label="Filtrar por categoría">
                            <label class="segmented-option">
                                <input type="radio" name="categoria" value="todos" <?php echo $categoria === 'todos' ? 'checked' : ''; ?>>
                                <span>Todos</span>
                            </label>
                            <label class="segmented-option">
                                <input type="radio" name="categoria" value="asegurado" <?php echo $categoria === 'asegurado' ? 'checked' : ''; ?>>
                                <span>Asegurado</span>
                            </label>
                            <label class="segmented-option">
                                <input type="radio" name="categoria" value="no asegurado" <?php echo $categoria === 'no asegurado' ? 'checked' : ''; ?>>
                                <span>No asegurado</span>
                            </label>
                        </div>
                    </div>

                    <div class="filter-group filter-doctor">
                        <label for="doctorFilter" class="filter-label">Doctor tratante</label>
                        <select name="id_dr" id="doctorFilter" class="select-clean">
                            <option value="">Todos los doctores</option>
                            <?php foreach ($doctores as $dr): ?>
                                <option
                                    value="<?php echo htmlspecialchars((string) $dr['id_dr'], ENT_QUOTES, 'UTF-8'); ?>"
                                    <?php echo (int) $dr['id_dr'] === $id_dr_filtro ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($dr['nombre_dr'] . ' (ID ' . $dr['id_dr'] . ')', ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php if ($hay_filtros): ?>
                        <a href="mostrar.php" class="btn btn-link-clear">Limpiar filtros</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="results-bar">
            <p class="results-count" id="resultsCount">
                <?php
                echo htmlspecialchars(
                    $total_resultados . ' ' . ($total_resultados === 1 ? 'paciente encontrado' : 'pacientes encontrados'),
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>
            </p>
            <p class="results-hint">Haga clic en una fila para ver la ficha clínica completa.</p>
        </div>

        <?php if ($total_resultados === 0): ?>
            <div class="empty-state">
                <svg viewBox="0 0 64 64" width="52" height="52" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="28" cy="28" r="18"></circle>
                    <line x1="41" y1="41" x2="56" y2="56"></line>
                    <line x1="22" y1="28" x2="34" y2="28"></line>
                </svg>
                <h3>No se encontraron pacientes</h3>
                <p>No hay registros que coincidan con la búsqueda o los filtros aplicados.</p>
                <a href="mostrar.php" class="btn btn-outline-teal">Limpiar filtros</a>
            </div>
        <?php else: ?>
            <div class="table-wrap" id="patientsTableWrap">
                <table class="patients-table" id="patientsTable">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Dirección</th>
                            <th>Categoría</th>
                            <th>Diagnóstico</th>
                            <th>Doctor asignado</th>
                            <th class="col-actions">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pacientes as $p): ?>
                            <?php
                            $iniciales = obtener_iniciales($p['nombre_paciente']);
                            $texto_busqueda = mb_strtolower(
                                $p['id'] . ' ' . $p['nombre_paciente'] . ' ' . $p['direccion'] . ' ' . $p['diagnostico'] . ' ' . $p['nombre_dr'] . ' ' . $p['categoria'],
                                'UTF-8'
                            );
                            $badge_class = $p['categoria'] === 'asegurado' ? 'badge-asegurado' : 'badge-no-asegurado';
                            ?>
                            <tr
                                class="patient-row"
                                tabindex="0"
                                data-id="<?php echo htmlspecialchars((string) $p['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-nombre="<?php echo htmlspecialchars($p['nombre_paciente'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-iniciales="<?php echo htmlspecialchars($iniciales, ENT_QUOTES, 'UTF-8'); ?>"
                                data-direccion="<?php echo htmlspecialchars($p['direccion'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-categoria="<?php echo htmlspecialchars($p['categoria'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-diagnostico="<?php echo htmlspecialchars($p['diagnostico'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-doctor="<?php echo htmlspecialchars($p['nombre_dr'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-id-dr="<?php echo htmlspecialchars((string) $p['id_dr'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-search="<?php echo htmlspecialchars($texto_busqueda, ENT_QUOTES, 'UTF-8'); ?>"
                            >
                                <td>
                                    <div class="patient-cell">
                                        <span class="patient-avatar" aria-hidden="true">
                                            <?php echo htmlspecialchars($iniciales, ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                        <div>
                                            <strong class="patient-name"><?php echo htmlspecialchars($p['nombre_paciente'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                            <span class="patient-id">Exp. #<?php echo htmlspecialchars((string) $p['id'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="cell-address"><?php echo htmlspecialchars($p['direccion'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <span class="badge <?php echo $badge_class; ?>">
                                        <?php echo htmlspecialchars($p['categoria'], ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td class="cell-diagnosis"><?php echo htmlspecialchars($p['diagnostico'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <div class="doctor-cell">
                                        <span><?php echo htmlspecialchars($p['nombre_dr'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <small>ID <?php echo htmlspecialchars((string) $p['id_dr'], ENT_QUOTES, 'UTF-8'); ?></small>
                                    </div>
                                </td>
                                <td class="col-actions">
                                    <div class="table-actions">
                                        <a
                                            href="actualizar.php?id=<?php echo urlencode((string) $p['id']); ?>"
                                            class="btn-table-action"
                                            title="Editar paciente"
                                        >
                                            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                            </svg>
                                            <span>Editar</span>
                                        </a>
                                        <button
                                            type="button"
                                            class="btn-table-action btn-trigger-delete"
                                            data-id="<?php echo htmlspecialchars((string) $p['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-nombre="<?php echo htmlspecialchars($p['nombre_paciente'], ENT_QUOTES, 'UTF-8'); ?>"
                                            title="Eliminar paciente"
                                        >
                                            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                                <path d="M10 11v6"></path>
                                                <path d="M14 11v6"></path>
                                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                            </svg>
                                            <span>Eliminar</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Estado vacío dinámico cuando el filtro en vivo en JS no encuentra coincidencias -->
            <div class="empty-state is-hidden" id="liveEmptyState">
                <svg viewBox="0 0 64 64" width="52" height="52" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="28" cy="28" r="18"></circle>
                    <line x1="41" y1="41" x2="56" y2="56"></line>
                    <line x1="22" y1="28" x2="34" y2="28"></line>
                </svg>
                <h3>Sin coincidencias en pantalla</h3>
                <p>Pruebe otra palabra clave o presione Buscar para consultar en la base de datos.</p>
                <button type="button" class="btn btn-outline-teal" id="resetLiveSearch">Mostrar filas cargadas</button>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Panel lateral (drawer) con ficha completa del paciente -->
<div class="drawer-backdrop" id="patientDrawer" aria-hidden="true">
    <aside class="drawer-panel" role="dialog" aria-modal="true" aria-labelledby="drawerPatientName">
        <div class="drawer-header">
            <span class="eyebrow">Ficha clínica</span>
            <button type="button" class="modal-close" id="closeDrawer" aria-label="Cerrar ficha">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="drawer-body">
            <div class="drawer-identity">
                <span class="patient-avatar patient-avatar-lg" id="drawerAvatar" aria-hidden="true">PA</span>
                <div>
                    <h2 id="drawerPatientName">Nombre del paciente</h2>
                    <div class="drawer-meta">
                        <span id="drawerPatientId">Exp. #0</span>
                        <span class="badge" id="drawerBadge">asegurado</span>
                    </div>
                </div>
            </div>

            <dl class="drawer-details">
                <div>
                    <dt>Doctor asignado</dt>
                    <dd id="drawerDoctor">&mdash;</dd>
                </div>
                <div>
                    <dt>Dirección</dt>
                    <dd id="drawerAddress">&mdash;</dd>
                </div>
                <div>
                    <dt>Diagnóstico y observaciones</dt>
                    <dd id="drawerDiagnosis">&mdash;</dd>
                </div>
            </dl>
        </div>

        <div class="drawer-footer">
            <a href="#" class="btn-table-action" id="drawerEditLink">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 20h9"></path>
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                </svg>
                <span>Editar</span>
            </a>
            <button type="button" class="btn-table-action" id="drawerDeleteBtn">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                    <path d="M10 11v6"></path>
                    <path d="M14 11v6"></path>
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                </svg>
                <span>Eliminar</span>
            </button>
        </div>
    </aside>
</div>

<!-- Modal propio de confirmación para eliminar -->
<div class="modal-backdrop" id="deleteModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
        <div class="modal-head">
            <span class="eyebrow">Confirmar acción</span>
            <h3 id="deleteModalTitle">Eliminar registro de paciente</h3>
        </div>
        <p class="modal-text">
            ¿Está seguro de que desea eliminar el expediente de <strong id="deletePatientName">este paciente</strong>?
            Esta acción no se puede deshacer.
        </p>
        <form action="eliminar.php" method="POST" id="deleteConfirmForm" class="modal-actions">
            <input type="hidden" name="id" id="deletePatientId" value="">
            <input type="hidden" name="categoria" value="<?php echo htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="q" value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="id_dr" value="<?php echo htmlspecialchars($id_dr_filtro > 0 ? (string) $id_dr_filtro : '', ENT_QUOTES, 'UTF-8'); ?>">
            <button type="button" class="btn btn-outline-teal" id="cancelDelete">Cancelar</button>
            <button type="submit" class="btn btn-primary">Confirmar eliminación</button>
        </form>
    </div>
</div>

<?php
$conn->close();
require_once 'includes/footer.php';
?>
