create database proyecto
use proyecto;

SHOW VARIABLES LIKE 'auto_increment_increment';

CREATE TABLE IF NOT EXISTS proyectos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_proyecto VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    numero_estudiantes INT NOT NULL,
    fecha DATE NOT NULL
    
);
ALTER TABLE proyectos ADD COLUMN email_profesor VARCHAR(255);


ALTER TABLE proyectos ADD COLUMN disponible BOOLEAN DEFAULT TRUE;


select*from proyectos
DELETE FROM proyectos WHERE id = 24;

ALTER TABLE proyectos DROP COLUMN email;