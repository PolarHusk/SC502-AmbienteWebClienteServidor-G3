CREATE DATABASE IF NOT EXISTS sidegeek
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE sidegeek;


CREATE TABLE roles (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL,
  descripcion VARCHAR(150) NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_roles_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE permisos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  codigo VARCHAR(80) NOT NULL,
  nombre VARCHAR(120) NOT NULL,
  descripcion VARCHAR(200) NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uk_permisos_codigo (codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE rol_permisos (
  rol_id INT UNSIGNED NOT NULL,
  permiso_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (rol_id, permiso_id),
  CONSTRAINT fk_rol_permisos_rol
    FOREIGN KEY (rol_id) REFERENCES roles(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_rol_permisos_permiso
    FOREIGN KEY (permiso_id) REFERENCES permisos(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE categorias (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(80) NOT NULL,
  slug VARCHAR(100) NOT NULL,
  estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_categorias_nombre (nombre),
  UNIQUE KEY uk_categorias_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE usuarios (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  rol_id INT UNSIGNED NOT NULL,
  nombre VARCHAR(120) NOT NULL,
  correo VARCHAR(150) NOT NULL,
  contrasena_hash VARCHAR(255) NOT NULL,
  telefono VARCHAR(20) NULL,
  estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_usuarios_correo (correo),
  KEY idx_usuarios_rol (rol_id),
  CONSTRAINT fk_usuarios_rol
    FOREIGN KEY (rol_id) REFERENCES roles(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE productos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  categoria_id INT UNSIGNED NOT NULL,
  nombre VARCHAR(150) NOT NULL,
  descripcion VARCHAR(255) NOT NULL,
  imagen_principal_url VARCHAR(255) NULL,
  precio DECIMAL(10,2) NOT NULL,
  stock INT UNSIGNED NOT NULL DEFAULT 0,
  descuento_porcentaje TINYINT UNSIGNED NOT NULL DEFAULT 0,
  es_nuevo_lanzamiento TINYINT(1) NOT NULL DEFAULT 0,
  estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_productos_categoria (categoria_id),
  KEY idx_productos_descuento (descuento_porcentaje),
  KEY idx_productos_nuevo (es_nuevo_lanzamiento),
  CONSTRAINT fk_productos_categoria
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE producto_imagenes (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  producto_id INT UNSIGNED NOT NULL,
  url_imagen VARCHAR(255) NOT NULL,
  es_principal TINYINT(1) NOT NULL DEFAULT 0,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_producto_imagenes_producto (producto_id),
  CONSTRAINT fk_producto_imagenes_producto
    FOREIGN KEY (producto_id) REFERENCES productos(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE carritos (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id INT UNSIGNED NOT NULL,
  estado ENUM('activo', 'cerrado') NOT NULL DEFAULT 'activo',
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_carritos_usuario (usuario_id),
  CONSTRAINT fk_carritos_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE carrito_detalles (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  carrito_id BIGINT UNSIGNED NOT NULL,
  producto_id INT UNSIGNED NOT NULL,
  cantidad INT UNSIGNED NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uk_carrito_producto (carrito_id, producto_id),
  KEY idx_carrito_detalles_producto (producto_id),
  CONSTRAINT fk_carrito_detalles_carrito
    FOREIGN KEY (carrito_id) REFERENCES carritos(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_carrito_detalles_producto
    FOREIGN KEY (producto_id) REFERENCES productos(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pedidos (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id INT UNSIGNED NOT NULL,
  carrito_id BIGINT UNSIGNED NULL,
  numero_pedido VARCHAR(25) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  descuento_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  impuesto_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  estado ENUM('pendiente', 'pagado', 'en_preparacion', 'enviado', 'completado', 'cancelado') NOT NULL DEFAULT 'pendiente',
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_pedidos_numero (numero_pedido),
  KEY idx_pedidos_usuario (usuario_id),
  KEY idx_pedidos_estado (estado),
  CONSTRAINT fk_pedidos_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_pedidos_carrito
    FOREIGN KEY (carrito_id) REFERENCES carritos(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pedido_detalles (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  pedido_id BIGINT UNSIGNED NOT NULL,
  producto_id INT UNSIGNED NULL,
  categoria_id INT UNSIGNED NULL,
  nombre_producto VARCHAR(150) NOT NULL,
  cantidad INT UNSIGNED NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  descuento_porcentaje TINYINT UNSIGNED NOT NULL DEFAULT 0,
  subtotal DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (id),
  KEY idx_pedido_detalles_pedido (pedido_id),
  KEY idx_pedido_detalles_producto (producto_id),
  KEY idx_pedido_detalles_categoria (categoria_id),
  CONSTRAINT fk_pedido_detalles_pedido
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_pedido_detalles_producto
    FOREIGN KEY (producto_id) REFERENCES productos(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL,
  CONSTRAINT fk_pedido_detalles_categoria
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE facturas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  pedido_id BIGINT UNSIGNED NOT NULL,
  usuario_id INT UNSIGNED NOT NULL,
  numero_factura VARCHAR(25) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  descuento_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  impuesto_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  total DECIMAL(10,2) NOT NULL,
  fecha_emision DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_facturas_pedido (pedido_id),
  UNIQUE KEY uk_facturas_numero (numero_factura),
  KEY idx_facturas_usuario (usuario_id),
  CONSTRAINT fk_facturas_pedido
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_facturas_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE factura_detalles (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  factura_id BIGINT UNSIGNED NOT NULL,
  producto_id INT UNSIGNED NULL,
  descripcion VARCHAR(255) NOT NULL,
  cantidad INT UNSIGNED NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (id),
  KEY idx_factura_detalles_factura (factura_id),
  KEY idx_factura_detalles_producto (producto_id),
  CONSTRAINT fk_factura_detalles_factura
    FOREIGN KEY (factura_id) REFERENCES facturas(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_factura_detalles_producto
    FOREIGN KEY (producto_id) REFERENCES productos(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE inventario_movimientos (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  producto_id INT UNSIGNED NOT NULL,
  usuario_id INT UNSIGNED NULL,
  tipo_movimiento ENUM('entrada', 'salida', 'ajuste') NOT NULL,
  cantidad INT UNSIGNED NOT NULL,
  motivo VARCHAR(255) NULL,
  referencia_tipo VARCHAR(50) NULL,
  referencia_id BIGINT UNSIGNED NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_inventario_producto (producto_id),
  KEY idx_inventario_usuario (usuario_id),
  CONSTRAINT fk_inventario_producto
    FOREIGN KEY (producto_id) REFERENCES productos(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_inventario_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO roles (nombre, descripcion) VALUES
  ('admin', 'Administrador del sistema'),
  ('cliente', 'Cliente registrado');

INSERT INTO permisos (codigo, nombre, descripcion) VALUES
  ('catalogo.ver', 'Ver catálogo', 'Permite visualizar productos del catálogo'),
  ('catalogo.filtrar', 'Buscar y filtrar', 'Permite buscar y filtrar productos'),
  ('auth.login', 'Iniciar sesión', 'Permite autenticarse en el sistema'),
  ('carrito.usar', 'Usar carrito', 'Permite agregar, editar y eliminar productos del carrito'),
  ('facturas.generar', 'Generar facturas', 'Permite emitir facturas de compra'),
  ('pedidos.ver_historial', 'Ver historial de pedidos', 'Permite consultar pedidos completados'),
  ('productos.gestionar', 'Gestionar productos', 'Permite crear, editar y eliminar productos'),
  ('pedidos.reporte', 'Consultar pedidos', 'Permite generar reportes de pedidos e inventario');

INSERT INTO rol_permisos (rol_id, permiso_id)
SELECT r.id, p.id
FROM roles r
JOIN permisos p
WHERE
  (r.nombre = 'admin' AND p.codigo IN (
    'catalogo.ver', 'catalogo.filtrar', 'auth.login', 'carrito.usar',
    'facturas.generar', 'pedidos.ver_historial', 'productos.gestionar', 'pedidos.reporte'
  ))
  OR
  (r.nombre = 'cliente' AND p.codigo IN (
    'catalogo.ver', 'catalogo.filtrar', 'auth.login', 'carrito.usar',
    'facturas.generar', 'pedidos.ver_historial'
  ));

INSERT INTO categorias (nombre, slug) VALUES
  ('Videojuegos', 'videojuegos'),
  ('Mangas', 'mangas'),
  ('Comics', 'comics'),
  ('Figuras coleccionables', 'figuras-coleccionables');

INSERT INTO productos
  (categoria_id, nombre, descripcion, imagen_principal_url, precio, stock, descuento_porcentaje, es_nuevo_lanzamiento)
SELECT
  c.id,
  'The Legend of Zelda',
  'Aventura épica con exploración, acertijos y combates.',
  'https://upload.wikimedia.org/wikipedia/en/thumb/c/c6/The_Legend_of_Zelda_Breath_of_the_Wild.jpg/250px-The_Legend_of_Zelda_Breath_of_the_Wild.jpg?utm_source=en.wikipedia.org&utm_campaign=parser&utm_content=thumbnail',
  28500.00,
  8,
  0,
  0
FROM categorias c
WHERE c.slug = 'videojuegos'
UNION ALL
SELECT
  c.id,
  'Spider-Man: Miles Morales',
  'Recorre la ciudad y conviértete en un nuevo Spider-Man.',
  'https://image.api.playstation.com/vulcan/ap/rnd/202008/1020/T45iRN1bhiWcJUzST6UFGBvO.png',
  24000.00,
  5,
  10,
  0
FROM categorias c
WHERE c.slug = 'videojuegos'
UNION ALL
SELECT
  c.id,
  'One Piece Vol. 1',
  'El inicio de la gran aventura de Monkey D. Luffy.',
  'https://m.media-amazon.com/images/I/91NxYvUNf6L._SL1500_.jpg',
  7500.00,
  12,
  0,
  1
FROM categorias c
WHERE c.slug = 'mangas'
UNION ALL
SELECT
  c.id,
  'Jujutsu Kaisen Vol. 1',
  'Acción sobrenatural, maldiciones y hechiceros.',
  'https://m.media-amazon.com/images/I/81jxwTCbzTL._UF1000,1000_QL80_.jpg',
  8000.00,
  3,
  5,
  0
FROM categorias c
WHERE c.slug = 'mangas'
UNION ALL
SELECT
  c.id,
  'Batman: Año Uno',
  'Una historia esencial sobre los primeros pasos de Batman.',
  'https://m.media-amazon.com/images/I/61PmYEhb60L._AC_UF1000,1000_QL80_.jpg',
  11500.00,
  7,
  0,
  0
FROM categorias c
WHERE c.slug = 'comics'
UNION ALL
SELECT
  c.id,
  'Avengers: Infinity',
  'Los héroes más poderosos enfrentan una amenaza cósmica.',
  'https://m.media-amazon.com/images/M/MV5BOGVkODYxMDEtODczZC00MjRiLTg3ZWYtZjgzN2QyMDBjZTUzXkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg',
  13500.00,
  2,
  15,
  1
FROM categorias c
WHERE c.slug = 'comics'
UNION ALL
SELECT
  c.id,
  'Figura de colección - Spider-Man',
  'Figura coleccionable para exhibición.',
  'https://www.milcomics.com/1259897-home_default/spider-man-no-way-home-figura-deluxe-spider-man-integrated-suit-hot-toys-29-cm.jpg',
  32000.00,
  4,
  0,
  1
FROM categorias c
WHERE c.slug = 'figuras-coleccionables';

INSERT INTO producto_imagenes (producto_id, url_imagen, es_principal)
SELECT
  p.id,
  p.imagen_principal_url,
  1
FROM productos p
WHERE p.nombre IN (
  'The Legend of Zelda',
  'Spider-Man: Miles Morales',
  'One Piece Vol. 1',
  'Jujutsu Kaisen Vol. 1',
  'Batman: Año Uno',
  'Avengers: Infinity',
  'Figura de colección - Spider-Man'
);


CREATE VIEW vw_pedidos_completados AS
SELECT
  p.id AS pedido_id,
  p.numero_pedido,
  u.nombre AS cliente,
  p.estado,
  p.subtotal,
  p.descuento_total,
  p.impuesto_total,
  p.total,
  p.creado_en
FROM pedidos p
INNER JOIN usuarios u ON u.id = p.usuario_id
WHERE p.estado = 'completado';

CREATE VIEW vw_productos_mas_vendidos AS
SELECT
  c.nombre AS categoria,
  pr.nombre AS producto,
  SUM(pd.cantidad) AS unidades_vendidas,
  SUM(pd.subtotal) AS total_facturado
FROM pedido_detalles pd
INNER JOIN pedidos pe ON pe.id = pd.pedido_id
INNER JOIN productos pr ON pr.id = pd.producto_id
INNER JOIN categorias c ON c.id = pr.categoria_id
WHERE pe.estado = 'completado'
GROUP BY c.nombre, pr.nombre
ORDER BY unidades_vendidas DESC;


INSERT INTO usuarios (rol_id, nombre, correo, contrasena_hash) VALUES 
(
  (SELECT id FROM roles WHERE nombre = 'admin'),
  'Administrador',
  'admin@example.com',
  '$2y$10$RU1apI4ISeOiEmJsjeDTr.QXGIZ3X6r3Azg/bT/YHvqtd.0gfUxky'
);
