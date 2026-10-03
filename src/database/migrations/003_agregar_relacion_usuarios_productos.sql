ALTER TABLE productos
ADD CONSTRAINT fk_productos_usuarios
FOREIGN KEY (usuario_id) REFERENCES usuarios(id);
