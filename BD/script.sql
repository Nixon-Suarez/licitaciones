CREATE TABLE actividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_segmento INT NOT NULL,
    segmento VARCHAR(200) NOT NULL,
    codigo_familia INT NOT NULL,
    familia VARCHAR(200) NOT NULL,
    codigo_clase INT NOT NULL,
    clase VARCHAR(200) NOT NULL,
    codigo_producto INT NOT NULL,
    producto VARCHAR(200) NOT NULL
);

CREATE TABLE ofertas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consecutivo VARCHAR(50) NOT NULL UNIQUE,
    objeto VARCHAR(150) NOT NULL,
    descripcion VARCHAR(400),
    moneda VARCHAR(3) NOT NULL,
    presupuesto DECIMAL(15, 2) NOT NULL,
    actividad_id INT NOT NULL,
    fecha_inicio DATE,
    hora_inicio TIME,
    fecha_cierre DATE,
    hora_cierre TIME,
    estado VARCHAR(20),
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_ofertas_actividad FOREIGN KEY (actividad_id) REFERENCES actividades (id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE ofertas_documentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    licitacion_id INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descripcion VARCHAR(200),
    archivo VARCHAR(255) NOT NULL,
    ruta_archivo VARCHAR(255) NOT NULL,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_documentos_oferta FOREIGN KEY (licitacion_id) REFERENCES ofertas (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_nombre VARCHAR(70) NOT NULL,
    usuario_apellido VARCHAR(70) NOT NULL,
    usuario_usuario VARCHAR(30) NOT NULL UNIQUE,
    usuario_clave VARCHAR(200) NOT NULL,
    usuario_creado TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    usuario_actualizado TIMESTAMP NULL DEFAULT NULL
);

-- Cargue de actividades
LOAD DATA INFILE 'data2.csv' --direccion del archivo
INTO TABLE actividades
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(codigo_segmento, segmento, codigo_familia, familia, codigo_clase, clase, codigo_producto, producto);