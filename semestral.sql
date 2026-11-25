-- Base de datos para el sistema de gestión de clínica
-- Proyecto Semestral - Ingeniería Web (UTP)

CREATE DATABASE IF NOT EXISTS semestral
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE semestral;

DROP TABLE IF EXISTS pacientes;
DROP TABLE IF EXISTS doctor;

CREATE TABLE doctor (
    id_dr INT NOT NULL,
    nombre_dr VARCHAR(100) NOT NULL,
    PRIMARY KEY (id_dr)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pacientes (
    id INT NOT NULL AUTO_INCREMENT,
    nombre_paciente VARCHAR(120) NOT NULL,
    direccion VARCHAR(200) NOT NULL,
    categoria ENUM('asegurado', 'no asegurado') NOT NULL,
    diagnostico TEXT NOT NULL,
    id_dr INT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_pacientes_doctor
        FOREIGN KEY (id_dr) REFERENCES doctor(id_dr)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3 doctores de ejemplo
INSERT INTO doctor (id_dr, nombre_dr) VALUES
(101, 'Dra. Elena Mendoza'),
(102, 'Dr. Carlos Quintero'),
(103, 'Dra. Sofía Castillo');

-- 5 pacientes de ejemplo (mezcla de asegurados y no asegurados)
INSERT INTO pacientes (nombre_paciente, direccion, categoria, diagnostico, id_dr) VALUES
('Luis Morales', 'Av. Balboa, Edif. Mar Azul, Piso 4, Ciudad de Panamá', 'asegurado', 'Hipertensión arterial controlada. Control mensual y dieta baja en sodio.', 101),
('María González', 'Calle 50, Bella Vista, Ciudad de Panamá', 'no asegurado', 'Gastritis aguda. Tratamiento con protector gástrico por 14 días.', 102),
('José Batista', 'Brisas del Golf, Calle 8 Norte, Casa 24, San Miguelito', 'asegurado', 'Faringitis bacteriana. Reposo relativo y esquema antibiótico completo.', 101),
('Carmen Rodríguez', 'Villa Lucre, Calle Principal, Casa 12, Panamá', 'no asegurado', 'Migraña tensional recurrente. Evaluación neurológica de seguimiento.', 103),
('Andrés Pitti', 'Condado del Rey, PH Altos del Bosque, Apto 7B, Panamá', 'asegurado', 'Control preventivo anual y perfil lipídico dentro de parámetros normales.', 102);
