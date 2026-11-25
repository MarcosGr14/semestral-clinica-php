(function () {
    'use strict';

    var prefersReducedMotion = window.matchMedia &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* 1. Header fijo + menú hamburguesa */
    var siteHeader = document.getElementById('siteHeader');
    var navToggle = document.getElementById('navToggle');
    var mainNav = document.getElementById('mainNav');

    if (siteHeader) {
        window.addEventListener('scroll', function () {
            siteHeader.classList.toggle('is-scrolled', window.scrollY > 12);
        }, { passive: true });
    }

    if (navToggle && mainNav) {
        navToggle.addEventListener('click', function () {
            var expanded = navToggle.getAttribute('aria-expanded') === 'true';
            navToggle.setAttribute('aria-expanded', String(!expanded));
            mainNav.classList.toggle('is-open', !expanded);
        });
    }

    /* 2. Aparición suave al hacer scroll (IntersectionObserver) */
    var revealItems = document.querySelectorAll('.reveal');
    if (revealItems.length > 0) {
        if (prefersReducedMotion || !('IntersectionObserver' in window)) {
            revealItems.forEach(function (el) {
                el.classList.add('is-visible');
            });
        } else {
            var revealObserver = new IntersectionObserver(function (entries, observer) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });

            revealItems.forEach(function (el) {
                revealObserver.observe(el);
            });
        }
    }

    /* 3. Contadores animados en Acerca de */
    var counters = document.querySelectorAll('[data-counter]');
    if (counters.length > 0) {
        var animateCounter = function (el) {
            var target = parseInt(el.getAttribute('data-counter'), 10);
            var suffix = el.getAttribute('data-suffix') || '';
            if (isNaN(target)) {
                return;
            }
            if (prefersReducedMotion) {
                el.textContent = target.toLocaleString('es-PA') + suffix;
                return;
            }

            var duration = 1100;
            var start = null;
            var step = function (timestamp) {
                if (!start) {
                    start = timestamp;
                }
                var progress = Math.min((timestamp - start) / duration, 1);
                var current = Math.floor(progress * target);
                el.textContent = current.toLocaleString('es-PA') + suffix;
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                } else {
                    el.textContent = target.toLocaleString('es-PA') + suffix;
                }
            };
            window.requestAnimationFrame(step);
        };

        if ('IntersectionObserver' in window && !prefersReducedMotion) {
            var counterObserver = new IntersectionObserver(function (entries, observer) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.35 });

            counters.forEach(function (c) {
                counterObserver.observe(c);
            });
        } else {
            counters.forEach(animateCounter);
        }
    }

    /* 4. Hero con carrusel en index.php */
    var hero = document.getElementById('heroCarousel');
    if (hero) {
        var slides = hero.querySelectorAll('.hero-slide');
        var dots = hero.querySelectorAll('.hero-dot');
        var prevBtn = document.getElementById('heroPrev');
        var nextBtn = document.getElementById('heroNext');
        var activeIndex = 0;
        var timerId = null;

        var goToSlide = function (index) {
            activeIndex = (index + slides.length) % slides.length;
            slides.forEach(function (slide, idx) {
                var active = idx === activeIndex;
                slide.classList.toggle('is-active', active);
                slide.setAttribute('aria-hidden', String(!active));
            });
            dots.forEach(function (dot, idx) {
                var active = idx === activeIndex;
                dot.classList.toggle('is-active', active);
                dot.setAttribute('aria-selected', String(active));
            });
        };

        var startAuto = function () {
            if (prefersReducedMotion || slides.length < 2) {
                return;
            }
            stopAuto();
            timerId = window.setInterval(function () {
                goToSlide(activeIndex + 1);
            }, 5000);
        };

        var stopAuto = function () {
            if (timerId !== null) {
                window.clearInterval(timerId);
                timerId = null;
            }
        };

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                goToSlide(activeIndex - 1);
                startAuto();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                goToSlide(activeIndex + 1);
                startAuto();
            });
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                var idx = parseInt(dot.getAttribute('data-slide'), 10);
                if (!isNaN(idx)) {
                    goToSlide(idx);
                    startAuto();
                }
            });
        });

        hero.addEventListener('mouseenter', stopAuto);
        hero.addEventListener('mouseleave', startAuto);
        hero.addEventListener('focusin', stopAuto);
        hero.addEventListener('focusout', startAuto);

        hero.setAttribute('tabindex', '0');
        hero.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowLeft') {
                goToSlide(activeIndex - 1);
                startAuto();
            } else if (e.key === 'ArrowRight') {
                goToSlide(activeIndex + 1);
                startAuto();
            }
        });

        startAuto();
    }

    /* 5. Selector segmentado de 3 modos en registrar.php */
    var modeTabs = document.querySelectorAll('.mode-tab[data-mode]');
    var modePanels = document.querySelectorAll('.mode-panel[data-mode-panel]');
    if (modeTabs.length > 0 && modePanels.length > 0) {
        var activateMode = function (mode) {
            modeTabs.forEach(function (tab) {
                var match = tab.getAttribute('data-mode') === mode;
                tab.classList.toggle('is-active', match);
                tab.setAttribute('aria-selected', String(match));
            });
            modePanels.forEach(function (panel) {
                var match = panel.getAttribute('data-mode-panel') === mode;
                panel.classList.toggle('is-active', match);
            });
        };

        modeTabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                activateMode(tab.getAttribute('data-mode'));
            });
        });

        document.querySelectorAll('.js-switch-mode').forEach(function (btn) {
            btn.addEventListener('click', function () {
                activateMode(btn.getAttribute('data-target-mode'));
            });
        });
    }

    /* 6. Validación visual en formularios */
    var validatedForms = document.querySelectorAll('.needs-validation');
    validatedForms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var valid = true;
            var groups = form.querySelectorAll('.field-group');

            groups.forEach(function (group) {
                var errorBox = group.querySelector('.field-error');
                var radios = group.querySelectorAll('input[type="radio"]');
                var input = group.querySelector('input:not([type="radio"]), select, textarea');
                var message = '';

                if (radios.length > 0) {
                    var anyChecked = Array.prototype.some.call(radios, function (r) {
                        return r.checked;
                    });
                    if (!anyChecked) {
                        message = 'Seleccione una categoría.';
                    }
                } else if (input && input.hasAttribute('required')) {
                    var val = input.value.trim();
                    if (val === '') {
                        message = 'Este campo es obligatorio.';
                    } else if (input.type === 'number' && (parseInt(val, 10) <= 0 || isNaN(parseInt(val, 10)))) {
                        message = 'Ingrese un número entero mayor que cero.';
                    }
                }

                if (message !== '') {
                    valid = false;
                    group.classList.add('has-error');
                    if (errorBox) {
                        errorBox.textContent = message;
                    }
                } else {
                    group.classList.remove('has-error');
                    if (errorBox) {
                        errorBox.textContent = '';
                    }
                }
            });

            if (!valid) {
                e.preventDefault();
                var firstError = form.querySelector('.has-error input, .has-error select, .has-error textarea');
                if (firstError) {
                    firstError.focus();
                }
            }
        });
    });

    /* 7. Página mostrar.php: filtros automáticos, búsqueda en vivo, drawer y modal eliminar */
    var filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.querySelectorAll('input[name="categoria"], select[name="id_dr"]').forEach(function (ctrl) {
            ctrl.addEventListener('change', function () {
                filterForm.submit();
            });
        });
    }

    var searchInput = document.getElementById('searchInput');
    var patientsTable = document.getElementById('patientsTable');
    var patientsTableWrap = document.getElementById('patientsTableWrap');
    var liveEmptyState = document.getElementById('liveEmptyState');
    var resetLiveSearch = document.getElementById('resetLiveSearch');
    var resultsCount = document.getElementById('resultsCount');

    if (searchInput && patientsTable) {
        var rows = patientsTable.querySelectorAll('tbody tr.patient-row');
        var applyLiveFilter = function () {
            var term = searchInput.value.trim().toLowerCase();
            var visibleCount = 0;

            rows.forEach(function (row) {
                var haystack = (row.getAttribute('data-search') || '').toLowerCase();
                var show = term === '' || haystack.indexOf(term) !== -1;
                row.style.display = show ? '' : 'none';
                if (show) {
                    visibleCount++;
                }
            });

            if (resultsCount) {
                resultsCount.textContent = visibleCount + ' ' + (visibleCount === 1 ? 'paciente encontrado' : 'pacientes encontrados');
            }

            if (patientsTableWrap && liveEmptyState) {
                patientsTableWrap.classList.toggle('is-hidden', visibleCount === 0);
                liveEmptyState.classList.toggle('is-hidden', visibleCount > 0);
            }
        };

        searchInput.addEventListener('input', applyLiveFilter);

        if (resetLiveSearch) {
            resetLiveSearch.addEventListener('click', function () {
                searchInput.value = '';
                applyLiveFilter();
                searchInput.focus();
            });
        }
    }

    /* Drawer ficha de paciente */
    var drawer = document.getElementById('patientDrawer');
    var closeDrawerBtn = document.getElementById('closeDrawer');
    var drawerDeleteBtn = document.getElementById('drawerDeleteBtn');
    var currentDrawerPatient = null;

    var openDrawerFromRow = function (row) {
        if (!drawer) {
            return;
        }
        var id = row.getAttribute('data-id') || '';
        var nombre = row.getAttribute('data-nombre') || '';
        var iniciales = row.getAttribute('data-iniciales') || 'PA';
        var direccion = row.getAttribute('data-direccion') || '';
        var categoria = row.getAttribute('data-categoria') || '';
        var diagnostico = row.getAttribute('data-diagnostico') || '';
        var doctor = row.getAttribute('data-doctor') || '';
        var idDr = row.getAttribute('data-id-dr') || '';

        currentDrawerPatient = { id: id, nombre: nombre };

        document.getElementById('drawerAvatar').textContent = iniciales;
        document.getElementById('drawerPatientName').textContent = nombre;
        document.getElementById('drawerPatientId').textContent = 'Exp. #' + id;
        document.getElementById('drawerDoctor').textContent = doctor + ' (ID ' + idDr + ')';
        document.getElementById('drawerAddress').textContent = direccion;
        document.getElementById('drawerDiagnosis').textContent = diagnostico;

        var badge = document.getElementById('drawerBadge');
        badge.textContent = categoria;
        badge.className = 'badge ' + (categoria === 'asegurado' ? 'badge-asegurado' : 'badge-no-asegurado');

        document.getElementById('drawerEditLink').href = 'actualizar.php?id=' + encodeURIComponent(id);

        drawer.classList.add('is-open');
        drawer.setAttribute('aria-hidden', 'false');
    };

    var closeDrawer = function () {
        if (!drawer) {
            return;
        }
        drawer.classList.remove('is-open');
        drawer.setAttribute('aria-hidden', 'true');
    };

    if (drawer) {
        document.querySelectorAll('.patient-row').forEach(function (row) {
            row.addEventListener('click', function (e) {
                if (e.target.closest('.table-actions')) {
                    return;
                }
                openDrawerFromRow(row);
            });

            row.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.target.closest('.table-actions')) {
                    openDrawerFromRow(row);
                }
            });
        });

        if (closeDrawerBtn) {
            closeDrawerBtn.addEventListener('click', closeDrawer);
        }

        drawer.addEventListener('click', function (e) {
            if (e.target === drawer) {
                closeDrawer();
            }
        });
    }

    /* Modal propio de confirmación para eliminar */
    var deleteModal = document.getElementById('deleteModal');
    var cancelDeleteBtn = document.getElementById('cancelDelete');
    var deletePatientName = document.getElementById('deletePatientName');
    var deletePatientIdInput = document.getElementById('deletePatientId');

    var openDeleteModal = function (id, nombre) {
        if (!deleteModal) {
            return;
        }
        deletePatientIdInput.value = id;
        deletePatientName.textContent = nombre;
        deleteModal.classList.add('is-open');
        deleteModal.setAttribute('aria-hidden', 'false');
    };

    var closeDeleteModal = function () {
        if (!deleteModal) {
            return;
        }
        deleteModal.classList.remove('is-open');
        deleteModal.setAttribute('aria-hidden', 'true');
    };

    document.querySelectorAll('.btn-trigger-delete').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            openDeleteModal(btn.getAttribute('data-id') || '', btn.getAttribute('data-nombre') || '');
        });
    });

    if (drawerDeleteBtn) {
        drawerDeleteBtn.addEventListener('click', function () {
            if (currentDrawerPatient) {
                closeDrawer();
                openDeleteModal(currentDrawerPatient.id, currentDrawerPatient.nombre);
            }
        });
    }

    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', closeDeleteModal);
    }

    if (deleteModal) {
        deleteModal.addEventListener('click', function (e) {
            if (e.target === deleteModal) {
                closeDeleteModal();
            }
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDrawer();
            closeDeleteModal();
        }
    });
})();
