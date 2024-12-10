USE mscode_estoque;

-- Inserindo categorias fictícias
INSERT INTO categoria (nome, criado_em, atualizado_em) VALUES
('Eletrônicos', NOW(), NULL),
('Roupas', NOW(), NULL),
('Livros', NOW(), NULL),
('Móveis', NOW(), NULL),
('Esportes', NOW(), NULL);

-- Inserindo produtos fictícios relacionados às categorias
INSERT INTO produto (nome, descricao, quantidade, valor_unitario, criado_em, atualizado_em, categoria_id, ativo) VALUES
('Smartphone XYZ', 'Smartphone de última geração com 128GB de armazenamento.', 50, 399900, NOW(), NULL, 1, TRUE), -- R$ 3999,00
('Notebook ABC', 'Notebook com processador Intel i5, 8GB de RAM e SSD de 256GB.', 30, 259990, NOW(), NULL, 1, TRUE), -- R$ 2599,90
('Camiseta Básica', 'Camiseta de algodão em cores variadas.', 200, 4999, NOW(), NULL, 2, TRUE), -- R$ 49,99
('Calça Jeans', 'Calça jeans azul de alta qualidade.', 100, 11990, NOW(), NULL, 2, TRUE), -- R$ 119,90
('Livro de Ficção', 'Livro de ficção científica de autor renomado.', 80, 3999, NOW(), NULL, 3, TRUE), -- R$ 39,99
('Estante Modular', 'Estante modular para sala ou escritório.', 15, 499990, NOW(), NULL, 4, TRUE), -- R$ 4999,90
('Bola de Futebol', 'Bola oficial de futebol tamanho padrão.', 100, 12990, NOW(), NULL, 5, TRUE); -- R$ 129,90


INSERT INTO cliente (nome, cpf, ativo) VALUES
('Gabriel Silva', '12345678901', TRUE),
('Mariana Oliveira', '98765432100', TRUE),
('Lucas Santos', '45678912300', FALSE),
('Ana Clara Costa', '32165498711', TRUE),
('João Pedro Lima', '78912345699', TRUE),
('Beatriz Almeida', '65498732188', TRUE),
('Rafael Monteiro', '15935785244', FALSE),
('Carolina Nunes', '25896314733', TRUE),
('Rodrigo Teixeira', '36985214722', TRUE),
('Larissa Ferreira', '14725836911', TRUE);
