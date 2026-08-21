ALTER TABLE productos
ADD FOREIGN KEY (usuario_id) REFERENCES usuarios(id);
