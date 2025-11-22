CREATE DATABASE unicamp;
USE unicamp;


CREATE TABLE aluno (
	matricula INT PRIMARY KEY,
	nome VARCHAR(100) NOT NULL,
	curso VARCHAR(100) NOT NULL,
	ano_ingresso INT NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    birth_date DATE NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    phone VARCHAR(20),
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    board_size INT NOT NULL,
    moves_count INT NOT NULL,
    mode CHAR(20) NOT NULL,
    duration_seconds INT NOT NULL,
    result CHAR(1) NOT NULL,
    play_datetime DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);