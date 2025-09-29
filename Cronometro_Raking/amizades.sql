-- Tabela de amizades
-- Cada amizade é registrada apenas uma vez com status 'pendente' ou 'aceito'
CREATE TABLE IF NOT EXISTS amizades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario1 INT NOT NULL,
    id_usuario2 INT NOT NULL,
    status ENUM('pendente','aceito') DEFAULT 'pendente',
    CONSTRAINT fk_usuario1 FOREIGN KEY (id_usuario1) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_usuario2 FOREIGN KEY (id_usuario2) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de sugestões de amizade
-- Sugestões são criadas automaticamente pelo sistema ou manualmente
CREATE TABLE IF NOT EXISTS sugestoes_amizade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_sugerido INT NOT NULL,
    CONSTRAINT fk_usuario_sugestao FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_sugerido FOREIGN KEY (id_sugerido) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Inserindo alguns usuários de exemplo
INSERT INTO usuarios (nome, foto) VALUES
('Alice', '/videos/alice.gif'),
('Bob', '/videos/bob.gif'),
('Carol', '/videos/carol.gif'),
('David', '/videos/david.gif');

-- Inserindo algumas amizades aceitas
INSERT INTO amizades (id_usuario1, id_usuario2, status) VALUES
(1, 2, 'aceito'), 
(1, 3, 'aceito');

-- Inserindo sugestões de amizade
INSERT INTO sugestoes_amizade (id_usuario, id_sugerido) VALUES
(1, 4),
(2, 3);