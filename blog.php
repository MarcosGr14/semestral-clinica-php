<?php
$titulo_pagina = 'Salud y bienestar | Clínica Santa Aurora';
$pagina_activa = 'inicio';
require_once 'includes/articulos.php';
require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <span class="eyebrow">Portal editorial &middot; Salud y bienestar</span>
        <h1>Guías prácticas redactadas por nuestro cuerpo médico</h1>
        <p class="page-lead">Información clara, sin alarmismos y orientada a la prevención cotidiana.</p>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="blog-grid blog-grid-full">
            <?php foreach ($articulos_blog as $art): ?>
                <article class="blog-card blog-card-full reveal" id="<?php echo htmlspecialchars($art['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="blog-thumb">
                        <img
                            src="<?php echo htmlspecialchars($art['imagen'], ENT_QUOTES, 'UTF-8'); ?>"
                            alt="<?php echo htmlspecialchars($art['titulo'], ENT_QUOTES, 'UTF-8'); ?>"
                            loading="lazy"
                        >
                    </div>
                    <div class="blog-body">
                        <div class="blog-meta">
                            <span class="blog-cat"><?php echo htmlspecialchars($art['categoria'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span><?php echo htmlspecialchars($art['fecha'], ENT_QUOTES, 'UTF-8'); ?> &middot; <?php echo htmlspecialchars($art['lectura'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        <h2><?php echo htmlspecialchars($art['titulo'], ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p class="blog-excerpt"><?php echo htmlspecialchars($art['extracto'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php foreach ($art['contenido'] as $parrafo): ?>
                            <p class="blog-paragraph"><?php echo htmlspecialchars($parrafo, ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="section-back">
            <a href="index.php" class="btn btn-outline-teal">Volver al inicio</a>
        </div>
    </div>
</section>

<?php
require_once 'includes/footer.php';
?>
