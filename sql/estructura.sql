-- Base de datos para Municipio Gaspar Marcano
CREATE DATABASE IF NOT EXISTS municipio_gaspar;
USE municipio_gaspar;

-- Tabla de usuarios (CMS)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, -- Hash BCRYPT
    rol ENUM('admin', 'editor', 'lector') DEFAULT 'lector',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de categorias (Noticias, Eventos, Turismo, Historia)
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    tipo VARCHAR(20) DEFAULT 'contenido' -- 'noticia', 'evento', 'turismo'
);

-- Tabla maestra de contenido
CREATE TABLE contenido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL, -- Para URL amigable
    resumen TEXT,
    cuerpo LONGTEXT, -- HTML del contenido
    imagen_destacada VARCHAR(255),
    fecha_evento DATE NULL, -- Si es un evento
    categoria_id INT,
    status TINYINT DEFAULT 1, -- 1: Publicado, 0: Borrador
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Tabla de documentos (Gacetas, Ordenanzas)
CREATE TABLE documentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    tipo VARCHAR(50), -- 'Gaceta', 'Decreto', 'Mapa'
    numero VARCHAR(20),
    fecha DATE,
    archivo_pdf VARCHAR(255) NOT NULL,
    downloads INT DEFAULT 0
);

-- Insertar datos iniciales (Admin y Categorías)
INSERT INTO usuarios (nombre, email, password, rol) VALUES 
('Administrador', 'admin@municipio.gob.ve', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'); -- Password: "password"

INSERT INTO categorias (nombre, tipo) VALUES 
('Noticias Destacadas', 'noticia'),
('Eventos Oficiales', 'evento'),
('Historia de Juan Griego', 'historia'),
('Atractivos Turísticos', 'turismo'),
('Oportunidades de Inversión', 'inversion');