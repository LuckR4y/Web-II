mysql -u root;

SHOW DATABASES;

CREATE DATABASE hotel;

SHOW DATABASES;

USE hotel;

CREATE TABLE hospedes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    cpf VARCHAR(20),
    rg VARCHAR(20),
    telefone VARCHAR(20)
);

CREATE TABLE aposentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    valor DECIMAL(10,2),
    descricao VARCHAR(100),
    numero INT,
    ocupado TINYINT(1) DEFAULT 0
);

CREATE TABLE contas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    valorTotal DECIMAL(10,2) DEFAULT 0,
    pago TINYINT(1) DEFAULT 0
);

CREATE TABLE hospedagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dataEntrada DATE,
    dataSaida DATE,
    hospede_id INT,
    aposento_id INT,
    conta_id INT,
    FOREIGN KEY (hospede_id) REFERENCES hospedes(id),
    FOREIGN KEY (aposento_id) REFERENCES aposentos(id),
    FOREIGN KEY (conta_id) REFERENCES contas(id)
);

CREATE TABLE consumos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(100),
    quantidade INT,
    valorUnitario DECIMAL(10,2),
    conta_id INT,
    FOREIGN KEY (conta_id) REFERENCES contas(id)
);

INSERT INTO aposentos (numero, descricao, valor) VALUES (101, 'Solteiro', 200);
INSERT INTO aposentos (numero, descricao, valor) VALUES (102, 'Casal', 400);
INSERT INTO aposentos (numero, descricao, valor) VALUES (103, 'Suíte', 600);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    usuario VARCHAR(50) UNIQUE,
    senha VARCHAR(255)
);


INSERT INTO usuarios (nome, usuario, senha)
VALUES ('Teste Usuário', 'teste', 'teste');

