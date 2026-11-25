<?php
$titulo_pagina = 'Acerca de | Clínica Santa Aurora';
$pagina_activa = 'acerca';
require_once 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <span class="eyebrow">Institución &middot; Trayectoria y Vocación</span>
        <h1>Una clínica privada construida sobre el criterio médico y el trato sereno.</h1>
        <p class="page-lead">
            En Clínica Santa Aurora unimos atención primaria, especialistas certificados y registro clínico ordenado
            para acompañar a cada paciente con tiempo y claridad.
        </p>
    </div>
</section>

<!-- Acerca de nosotros: texto editorial a dos columnas + bloque visual -->
<section class="section-pad reveal">
    <div class="container about-split">
        <div class="about-editorial">
            <span class="eyebrow">Acerca de nosotros</span>
            <h2>Medicina cercana con estándares de hospital privado</h2>
            <div class="two-col-text">
                <p>
                    Fundada en la Ciudad de Panamá, Clínica Santa Aurora nació con la convicción de devolverle a la consulta
                    médica su ritmo natural: escuchar con atención los síntomas, revisar el historial completo y explicar
                    cada indicación con transparencia.
                </p>
                <p>
                    Atendemos tanto a pacientes con cobertura de seguro médico como a pacientes particulares bajo un mismo
                    nivel de cuidado, manteniendo continuidad directa entre el médico tratante y la evolución diagnóstica.
                </p>
            </div>
        </div>

        <aside class="about-visual-card" aria-label="Principio institucional">
            <img src="img/banner1.jpg" alt="Recepción luminosa y cálida de una clínica moderna" class="about-visual-img" loading="lazy">
            <div class="about-visual-caption">
                <span class="eyebrow eyebrow-light">Sede Principal</span>
                <p>Av. Balboa &middot; Consultorios diseñados para ofrecer privacidad, silencio y puntualidad.</p>
            </div>
        </aside>
    </div>
</section>

<section class="section-pad about-gallery reveal" aria-label="Instalaciones y equipo médico">
    <div class="container about-gallery-grid">
        <figure><img src="img/banner3.jpg" alt="Consultorio médico moderno y ordenado" loading="lazy"></figure>
        <figure><img src="img/banner4.jpg" alt="Equipo médico profesional trabajando en conjunto" loading="lazy"></figure>
        <figure><img src="img/acerca1.jpg" alt="Personal de clínica atendiendo en recepción" loading="lazy"></figure>
    </div>
</section>

<!-- Misión y Visión -->
<section class="section-pad section-sand reveal">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Propósito</span>
            <h2>Misión y Visión</h2>
        </div>

        <div class="mission-grid">
            <article class="editorial-card">
                <span class="card-top-rule" aria-hidden="true"></span>
                <h3>Nuestra Misión</h3>
                <p>
                    Brindar servicios médicos accesibles, éticos y de alta precisión diagnóstica, enfocados en la prevención,
                    el tratamiento oportuno y el acompañamiento continuo de cada paciente y su familia.
                </p>
            </article>

            <article class="editorial-card">
                <span class="card-top-rule" aria-hidden="true"></span>
                <h3>Nuestra Visión</h3>
                <p>
                    Consolidarnos como un referente regional en medicina ambulatoria e integral, reconocido por la calidez
                    de su cuerpo médico, la claridad de sus procesos clínicos y la confianza a largo plazo de sus pacientes.
                </p>
            </article>
        </div>
    </div>
</section>

<!-- Valores con íconos SVG -->
<section class="section-pad reveal">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Principios</span>
            <h2>Valores que guían cada consulta</h2>
        </div>

        <div class="services-grid">
            <article class="service-card reveal">
                <div class="service-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        <polyline points="9 12 11 14 15 10"></polyline>
                    </svg>
                </div>
                <h3>Ética clínica</h3>
                <p>Indicaciones basadas en evidencia, sin estudios innecesarios ni diagnósticos apresurados.</p>
            </article>

            <article class="service-card reveal">
                <div class="service-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <h3>Rigor diagnóstico</h3>
                <p>Análisis minucioso del contexto de cada paciente antes de definir un esquema terapéutico.</p>
            </article>

            <article class="service-card reveal">
                <div class="service-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <h3>Trato humano</h3>
                <p>Explicaciones claras en lenguaje comprensible, respetando el tiempo y las inquietudes de la familia.</p>
            </article>

            <article class="service-card reveal">
                <div class="service-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <h3>Continuidad</h3>
                <p>Seguimiento real tras la consulta para evaluar evolución y ajustar tratamientos a tiempo.</p>
            </article>
        </div>
    </div>
</section>

<!-- Cifras destacadas con contador animado -->
<section class="stats-band reveal">
    <div class="container stats-grid">
        <div class="stat-item">
            <span class="stat-number" data-counter="18" data-suffix="+">18+</span>
            <span class="stat-label">Años de trayectoria clínica</span>
        </div>
        <div class="stat-item">
            <span class="stat-number" data-counter="14500" data-suffix="+">14,500+</span>
            <span class="stat-label">Pacientes atendidos</span>
        </div>
        <div class="stat-item">
            <span class="stat-number" data-counter="12" data-suffix="">12</span>
            <span class="stat-label">Especialistas certificados</span>
        </div>
        <div class="stat-item">
            <span class="stat-number" data-counter="98" data-suffix="%">98%</span>
            <span class="stat-label">Satisfacción en seguimiento</span>
        </div>
    </div>
</section>

<!-- Hitos de la clínica -->
<section class="section-pad reveal">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Historia</span>
            <h2>Hitos de nuestra clínica</h2>
        </div>

        <ol class="timeline">
            <li class="timeline-item">
                <span class="timeline-year">2008</span>
                <div class="timeline-body">
                    <h3>Apertura del primer consultorio</h3>
                    <p>Inicio de operaciones en Bella Vista con tres consultorios de medicina general y pediatría.</p>
                </div>
            </li>
            <li class="timeline-item">
                <span class="timeline-year">2014</span>
                <div class="timeline-body">
                    <h3>Unidad de Medicina Preventiva</h3>
                    <p>Incorporación de programas estructurados para control hipertensivo, metabólico y chequeos ejecutivos.</p>
                </div>
            </li>
            <li class="timeline-item">
                <span class="timeline-year">2020</span>
                <div class="timeline-body">
                    <h3>Digitalización de expedientes</h3>
                    <p>Implementación del sistema unificado de doctores y pacientes para agilizar el seguimiento clínico.</p>
                </div>
            </li>
            <li class="timeline-item">
                <span class="timeline-year">2025</span>
                <div class="timeline-body">
                    <h3>Nueva sede en Av. Balboa</h3>
                    <p>Inauguración de la Torre Santa Aurora con doce especialidades y atención ampliada de lunes a domingo.</p>
                </div>
            </li>
        </ol>
    </div>
</section>

<!-- Equipo médico -->
<section class="section-pad section-sand reveal" id="equipo">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Cuerpo Médico</span>
            <h2>Especialistas a cargo</h2>
        </div>

        <div class="team-grid">
            <article class="team-card reveal">
                <div class="avatar-circle" aria-hidden="true">EM</div>
                <div class="team-info">
                    <span class="team-id">ID Médico 101</span>
                    <h3>Dra. Elena Mendoza</h3>
                    <p class="team-role">Medicina Interna y Riesgo Cardiovascular</p>
                    <p class="team-bio">Especialista en control de hipertensión arterial, medicina preventiva del adulto y seguimiento metabólico.</p>
                </div>
            </article>

            <article class="team-card reveal">
                <div class="avatar-circle" aria-hidden="true">CQ</div>
                <div class="team-info">
                    <span class="team-id">ID Médico 102</span>
                    <h3>Dr. Carlos Quintero</h3>
                    <p class="team-role">Medicina General y Salud Digestiva</p>
                    <p class="team-bio">Enfocado en atención primaria familiar, manejo integral de gastritis y evaluación clínica preventiva.</p>
                </div>
            </article>

            <article class="team-card reveal">
                <div class="avatar-circle" aria-hidden="true">SC</div>
                <div class="team-info">
                    <span class="team-id">ID Médico 103</span>
                    <h3>Dra. Sofía Castillo</h3>
                    <p class="team-role">Neurología Clínica y Bienestar</p>
                    <p class="team-bio">Dedicada al estudio de cefaleas tensionales, trastornos del sueño y manejo integral del dolor.</p>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- Servicios, Horarios y Contacto -->
<section class="section-pad reveal" id="contacto">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Información práctica</span>
            <h2>Servicios, horarios y contacto</h2>
        </div>

        <div class="info-triad">
            <article class="editorial-card">
                <h3>Servicios Médicos</h3>
                <ul class="clean-list">
                    <li>Medicina General y Familiar</li>
                    <li>Control de Enfermedades Crónicas</li>
                    <li>Pediatría y Control de Crecimiento</li>
                    <li>Evaluaciones Preventivas y Certificados</li>
                    <li>Orientación Nutricional y Seguimiento</li>
                </ul>
            </article>

            <article class="editorial-card">
                <h3>Horarios de Atención</h3>
                <ul class="clean-list">
                    <li><strong>Lunes a Viernes:</strong> 7:00 a.m. &ndash; 7:00 p.m.</li>
                    <li><strong>Sábados:</strong> 8:00 a.m. &ndash; 2:00 p.m.</li>
                    <li><strong>Domingos y Feriados:</strong> Atención prioritaria (8:00 a.m. &ndash; 12:00 m.d.)</li>
                </ul>
            </article>

            <article class="editorial-card">
                <h3>Contacto y Ubicación</h3>
                <ul class="clean-list">
                    <li><strong>Dirección:</strong> Av. Balboa y Calle 42 Este, Torre Santa Aurora, Piso 3, Ciudad de Panamá</li>
                    <li><strong>Teléfono:</strong> +507 264-8890 / +507 6412-3300</li>
                    <li><strong>Correo:</strong> citas@clinicasantaaurora.pa</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<?php
require_once 'includes/footer.php';
?>
