ALTER TABLE usuarios
ADD CONSTRAINT uq_usuarios_email UNIQUE (email);
