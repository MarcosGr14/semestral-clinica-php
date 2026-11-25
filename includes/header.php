<?php
$titulo_pagina = isset($titulo_pagina) ? $titulo_pagina : 'Clínica Santa Aurora';
$pagina_activa = isset($pagina_activa) ? $pagina_activa : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo_pagina, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header class="site-header" id="siteHeader">
        <div class="container header-inner">
            <a href="index.php" class="brand-link" aria-label="Inicio Clínica Santa Aurora">
                <img src="img/logo.svg" alt="Clínica Santa Aurora" class="brand-logo" width="230" height="50">
            </a>

            <button
                type="button"
                class="nav-toggle"
                id="navToggle"
                aria-label="Abrir menú de navegación"
                aria-expanded="false"
                aria-controls="mainNav"
            >
                <svg class="icon-menu" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="3" y1="7" x2="21" y2="7"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="17" x2="21" y2="17"></line>
                </svg>
                <svg class="icon-close" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <nav class="main-nav" id="mainNav" aria-label="Navegación principal">
                <ul>
                    <li>
                        <a href="index.php" class="<?php echo $pagina_activa === 'inicio' ? 'activo' : ''; ?>">
                            Inicio
                        </a>
                    </li>
                    <li>
                        <a href="acerca.php" class="<?php echo $pagina_activa === 'acerca' ? 'activo' : ''; ?>">
                            Acerca de
                        </a>
                    </li>
                    <li>
                        <a href="mostrar.php" class="<?php echo $pagina_activa === 'mostrar' ? 'activo' : ''; ?>">
                            Mostrar pacientes
                        </a>
                    </li>
                    <li>
                        <a href="registrar.php" class="<?php echo $pagina_activa === 'registrar' ? 'activo' : ''; ?>">
                            Agregar
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="site-main">
