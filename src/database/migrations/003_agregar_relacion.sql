ALTER TABLE productos
ADD COLUMN categoria_id INT,
ADD CONSTRAINT fk_productos_categorias
FOREIGN KEY (categoria_id) REFERENCES categorias(id);
