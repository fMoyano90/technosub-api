CREATE DATABASE IF NOT EXISTS api_technosub;

USE api_technosub;

CREATE TABLE usuarios(
    id int(255) auto_increment not null,
    nombre varchar(50) NOT NULL,
    role varchar(20),
    email varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    created_at datetime DEFAULT NULL,
    updated_at datetime DEFAULT NULL,
    CONSTRAINT pk_usuarios PRIMARY KEY(id)
) ENGINE = InnoDb;

CREATE TABLE noticias(
    id int(255) auto_increment not null,
    titulo varchar(50) NOT NULL,
    t_breve varchar(50) NOT NULL,
    cuerpo text NOT NULL,
    autor varchar(255) NOT NULL,
    categoria varchar(255) NOT NULL,
    imagen varchar(255) NOT NULL,
    created_at datetime DEFAULT NULL,
    updated_at datetime DEFAULT NULL,
    CONSTRAINT pk_noticias PRIMARY KEY(id)
) ENGINE = InnoDb;

CREATE TABLE productos(
    id int(255) auto_increment not null,
    codigo varchar(50) NOT NULL,
    nombre varchar(255) NOT NULL,
    d_breve varchar(50) NOT NULL,
    descripcion text NOT NULL,
    imagen varchar(255) NOT NULL,
    categoria varchar(255) NOT NULL,
    prioridad varchar(255) NOT NULL,
    created_at datetime DEFAULT NULL,
    updated_at datetime DEFAULT NULL,
    CONSTRAINT pk_productos PRIMARY KEY(id)
) ENGINE = InnoDb;

CREATE TABLE socios(
    id int(255) auto_increment not null,
    descripcion int(255) not null,
    imagen datetime DEFAULT NULL,
    created_at datetime DEFAULT NULL,
    updated_at datetime DEFAULT NULL,
    CONSTRAINT pk_socios PRIMARY KEY(id)
) ENGINE = InnoDb;