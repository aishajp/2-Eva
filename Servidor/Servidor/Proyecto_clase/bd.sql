-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS ControlGlucosa;
USE ControlGlucosa;

-- Tabla Usuarios
CREATE TABLE Usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    apodo VARCHAR(50),
    fecha_de_nacimiento DATE NOT NULL,
    contraseña VARCHAR(255) NOT NULL
);

-- Tabla Control_Glucosa
CREATE TABLE Control_Glucosa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    insulina_lenta BOOLEAN,
    deporte BOOLEAN,
    hora TIME NOT NULL,
    usuario_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES Usuarios(id) ON DELETE CASCADE
);

-- Tabla Comida
CREATE TABLE Comida (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_comida ENUM('Desayuno', 'Almuerzo', 'Cena', 'Merienda') NOT NULL,
    glucosa_1 FLOAT NOT NULL,
    glucosa_2 FLOAT,
    insulina FLOAT,
    raciones INT
);

-- Tabla intermedia Control_Comida (M:N entre Control_Glucosa y Comida)
CREATE TABLE Control_Comida (
    id INT AUTO_INCREMENT PRIMARY KEY,
    control_glucosa_id INT NOT NULL,
    comida_id INT NOT NULL,
    FOREIGN KEY (control_glucosa_id) REFERENCES Control_Glucosa(id) ON DELETE CASCADE,
    FOREIGN KEY (comida_id) REFERENCES Comida(id) ON DELETE CASCADE
);

-- Tabla Hipo (Hipoglucemia)
CREATE TABLE Hipo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hora TIME NOT NULL,
    glucosa FLOAT NOT NULL,
    comida_id INT NOT NULL,
    FOREIGN KEY (comida_id) REFERENCES Comida(id) ON DELETE CASCADE
);

-- Tabla Hiper (Hiperglucemia)
CREATE TABLE Hiper (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hora TIME NOT NULL,
    glucosa FLOAT NOT NULL,
    correccion VARCHAR(255),
    comida_id INT NOT NULL,
    FOREIGN KEY (comida_id) REFERENCES Comida(id) ON DELETE CASCADE
);
