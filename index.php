<?php
$titulo_pagina = 'Inicio | Clínica Santa Aurora';
$pagina_activa = 'inicio';
require_once 'includes/articulos.php';
require_once 'includes/header.php';

$articulos_destacados = array_slice($articulos_blog, 0, 3);
?>

<!-- 1. Hero con carrusel de banners -->
<section class="hero-carousel" id="heroCarousel" aria-roledescription="carrusel" aria-label="Destacados de Clínica Santa Aurora">
    <div class="hero-slides">
        <article class="hero-slide is-active" aria-hidden="false">
            <img src="img/banner1.jpg" alt="Recepción luminosa de una clínica moderna con personal de atención" class="hero-bg">
            <div class="hero-overlay"></div>
            <div class="container hero-content">
                <span class="eyebrow eyebrow-light">Medicina Integral &middot; Ciudad de Panamá</span>
                <h1>Atención médica pensada para escuchar, diagnosticar y acompañar.</h1>
                <p>Consultas sin prisa, criterio clínico riguroso y seguimiento continuo para pacientes asegurados y particulares.</p>
                <div class="hero-actions">
                    <a href="registrar.php" class="btn btn-gold">Registrar paciente</a>
                    <a href="acerca.php" class="btn btn-ghost-light">Conocer la clínica</a>
                </div>
            </div>
        </article>

        <article class="hero-slide" aria-hidden="true">
            <img src="img/banner2.jpg" alt="Médico conversando con una paciente durante una consulta" class="hero-bg" loading="lazy">
            <div class="hero-overlay"></div>
            <div class="container hero-content">
                <span class="eyebrow eyebrow-light">Diagnóstico &amp; Seguimiento</span>
                <h2>Cada historia clínica merece claridad y continuidad.</h2>
                <p>Un equipo multidisciplinario conectado bajo un mismo expediente para evitar duplicidades y ganar precisión.</p>
                <div class="hero-actions">
                    <a href="mostrar.php" class="btn btn-gold">Consultar pacientes</a>
                    <a href="acerca.php#equipo" class="btn btn-ghost-light">Ver cuerpo médico</a>
                </div>
            </div>
        </article>

        <article class="hero-slide" aria-hidden="true">
            <img src="img/banner3.jpg" alt="Consultorio médico limpio y moderno, preparado para atender pacientes" class="hero-bg" loading="lazy">
            <div class="hero-overlay"></div>
            <div class="container hero-content">
                <span class="eyebrow eyebrow-light">Prevención &amp; Bienestar</span>
                <h2>Prevenir a tiempo es el acto más sereno de cuidado.</h2>
                <p>Programas de control cardiovascular, pediatría y medicina interna diseñados alrededor de su ritmo de vida.</p>
                <div class="hero-actions">
                    <a href="blog.php" class="btn btn-gold">Leer salud y bienestar</a>
                    <a href="registrar.php" class="btn btn-ghost-light">Nuevo registro</a>
                </div>
            </div>
        </article>

        <article class="hero-slide" aria-hidden="true">
            <img src="img/banner4.jpg" alt="Equipo médico profesional reunido en un entorno clínico" class="hero-bg" loading="lazy">
            <div class="hero-overlay"></div>
            <div class="container hero-content">
                <span class="eyebrow eyebrow-light">Cuerpo Médico</span>
                <h2>Experiencia clínica y trabajo en equipo para cuidar cada historia.</h2>
                <p>Especialistas que comparten conocimiento y acompañan a cada paciente con criterio y cercanía.</p>
                <div class="hero-actions">
                    <a href="acerca.php#equipo" class="btn btn-gold">Conocer al equipo</a>
                    <a href="acerca.php" class="btn btn-ghost-light">Nuestra clínica</a>
                </div>
            </div>
        </article>
    </div>

    <div class="hero-controls container">
        <div class="hero-dots" role="tablist" aria-label="Seleccionar diapositiva">
            <button type="button" class="hero-dot is-active" data-slide="0" aria-label="Diapositiva 1" aria-selected="true"></button>
            <button type="button" class="hero-dot" data-slide="1" aria-label="Diapositiva 2" aria-selected="false"></button>
            <button type="button" class="hero-dot" data-slide="2" aria-label="Diapositiva 3" aria-selected="false"></button>
            <button type="button" class="hero-dot" data-slide="3" aria-label="Diapositiva 4" aria-selected="false"></button>
        </div>
        <div class="hero-arrows">
            <button type="button" class="hero-arrow" id="heroPrev" aria-label="Diapositiva anterior">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <button type="button" class="hero-arrow" id="heroNext" aria-label="Siguiente diapositiva">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
    </div>
</section>

<!-- 2. Franja de frase motivacional / de bienestar -->
<section class="quote-band reveal">
    <div class="container quote-inner">
        <span class="gold-rule" aria-hidden="true"></span>
        <blockquote class="editorial-quote">
            &ldquo;Cuidar la salud no es un acto aislado frente a la enfermedad, sino la disciplina serena de escuchar al cuerpo a tiempo.&rdquo;
        </blockquote>
        <p class="quote-caption">Dirección Médica &middot; Clínica Santa Aurora</p>
    </div>
</section>

<!-- 3. Servicios / especialidades destacadas -->
<section class="section-pad reveal">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Especialidades</span>
            <h2>Áreas de atención clínica</h2>
            <p class="section-lead">Cuatro pilares médicos orientados al diagnóstico temprano, el control de enfermedades crónicas y la medicina familiar.</p>
        </div>

        <div class="services-grid">
            <article class="service-card reveal">
                <div class="service-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"></path>
                        <path d="M8 15v1a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6v-4"></path>
                        <circle cx="20" cy="10" r="2"></circle>
                    </svg>
                </div>
                <h3>Medicina Interna y Familiar</h3>
                <p>Valoración integral del adulto, chequeos preventivos y coordinación de estudios diagnósticos.</p>
            </article>

            <article class="service-card reveal">
                <div class="service-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                </div>
                <h3>Control Cardiovascular</h3>
                <p>Seguimiento de hipertensión arterial, riesgo metabólico y planes de prevención a largo plazo.</p>
            </article>

            <article class="service-card reveal">
                <div class="service-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M6 20v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"></path>
                    </svg>
                </div>
                <h3>Pediatría y Crecimiento</h3>
                <p>Control de hitos del desarrollo, esquemas de vacunación y orientación cercana para padres.</p>
            </article>

            <article class="service-card reveal">
                <div class="service-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <h3>Nutrición Clínica</h3>
                <p>Acompañamiento terapéutico para gastritis, síndrome metabólico y hábitos sostenibles.</p>
            </article>
        </div>
    </div>
</section>

<!-- 4. Portal de blog "Salud y bienestar" -->
<section class="section-pad section-sand reveal">
    <div class="container">
        <div class="section-head-split">
            <div>
                <span class="eyebrow">Salud y bienestar</span>
                <h2>Lecturas clínicas para el cuidado diario</h2>
            </div>
            <a href="blog.php" class="btn btn-outline-teal">Ver todos los artículos</a>
        </div>

        <div class="blog-grid">
            <?php foreach ($articulos_destacados as $art): ?>
                <article class="blog-card reveal">
                    <a href="blog.php#<?php echo htmlspecialchars($art['id'], ENT_QUOTES, 'UTF-8'); ?>" class="blog-thumb">
                        <img
                            src="<?php echo htmlspecialchars($art['imagen'], ENT_QUOTES, 'UTF-8'); ?>"
                            alt="<?php echo htmlspecialchars($art['titulo'], ENT_QUOTES, 'UTF-8'); ?>"
                            loading="lazy"
                        >
                    </a>
                    <div class="blog-body">
                        <div class="blog-meta">
                            <span class="blog-cat"><?php echo htmlspecialchars($art['categoria'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span><?php echo htmlspecialchars($art['fecha'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        <h3>
                            <a href="blog.php#<?php echo htmlspecialchars($art['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($art['titulo'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h3>
                        <p><?php echo htmlspecialchars($art['extracto'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 5. Llamado a la acción final -->
<section class="section-pad reveal">
    <div class="container">
        <div class="cta-banner">
            <div class="cta-copy">
                <span class="eyebrow">Gestión Clínica</span>
                <h2>Administre expedientes de doctores y pacientes en un solo lugar.</h2>
                <p>Consulte el directorio actualizado por categoría o registre una nueva consulta médica con validación inmediata.</p>
            </div>
            <div class="cta-actions">
                <a href="registrar.php" class="btn btn-primary">Registrar paciente</a>
                <a href="mostrar.php" class="btn btn-outline-teal">Mostrar pacientes</a>
            </div>
        </div>
    </div>
</section>

<?php
require_once 'includes/footer.php';
?>
