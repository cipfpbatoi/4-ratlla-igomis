CREATE TABLE usuaris (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         nom_usuari VARCHAR(50) UNIQUE NOT NULL,
                         contrasenya VARCHAR(255) NOT NULL
);

CREATE TABLE partides (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          usuari_id INT NOT NULL,
                          graella TEXT,
                          torn_actual INT,
                          estat_partida ENUM('en_curs', 'guanyada', 'perduda') NOT NULL,
                          FOREIGN KEY (usuari_id) REFERENCES usuaris(id) ON DELETE CASCADE
);