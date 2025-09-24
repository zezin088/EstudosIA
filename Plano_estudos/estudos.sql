CREATE TABLE IF NOT EXISTS plano_estudos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    semana INT NOT NULL,
    conteudo VARCHAR(255) NOT NULL
);
