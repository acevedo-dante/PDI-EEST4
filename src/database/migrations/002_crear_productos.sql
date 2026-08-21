CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150),
    descripcion VARCHAR(255),
    precio INT,
    stock INT,
    usuario_id INT
);
