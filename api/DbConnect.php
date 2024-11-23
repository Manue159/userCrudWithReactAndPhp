<?php

    class DbConnect {
        private $server = "127.0.0.1"; // Adresse du serveur
        private $username = "root";   // Nom d'utilisateur MariaDB
        private $password = "";       // Mot de passe MariaDB (vide par défaut sur Wamp)
        private $db = "crudUser"; // Remplacez par le nom de votre base de données
        private $port = "3307";       // Spécifiez le port 3307 pour MariaDB
        private $conn;
    
        public function connect() {
            try {
                $dsn = "mysql:host=$this->server;port=$this->port;dbname=$this->db;charset=utf8mb4";
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conn;
            } catch (PDOException $e) {
                echo "Erreur de connexion : " . $e->getMessage();
                exit;
            }
        }
    }
    
?>

