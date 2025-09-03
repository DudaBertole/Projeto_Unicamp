CREATE DATABASE unicamp;
USE unicamp;


CREATE TABLE aluno (
	matricula INT PRIMARY KEY,
	nome VARCHAR(100) NOT NULL,
	curso VARCHAR(100) NOT NULL,
	ano_ingresso INT NOT NULL
);
