CREATE DATABASE IF NOT EXISTS api_terramedical;

USE api_terramedical;

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

CREATE TABLE productos(
    id int(255) auto_increment not null,
    codigo varchar(50) NOT NULL,
    nombre varchar(255) NOT NULL,
    descripcion text NOT NULL,
    imagen varchar(255) NOT NULL,
    tipo varchar(255) NOT NULL,
    precio int(255) NOT NULL,
    created_at datetime DEFAULT NULL,
    updated_at datetime DEFAULT NULL,
    CONSTRAINT pk_productos PRIMARY KEY(id)
) ENGINE = InnoDb;