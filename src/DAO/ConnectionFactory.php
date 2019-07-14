<?php 

    namespace src\DAO {
        class ConnectionFactory {
            protected $host;
            protected $port;
            protected $database;
            protected $username;
            protected $password;
            protected $conn;
            
            public function __construct($h, $p, $db, $user, $pass) {
                $this->host = $h;
                $this->port = $p;
                $this->database = $db;
                $this->username = $user;
                $this->password = $pass;
            }

            public function connect() {
                $this->host = "localhost";
                $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->database);
                if (!$this->conn) {
                    die("Conexão falhou: " . mysqli_connect_error());
                }
                return $this->conn;
            }
            
            public function dispose() {
                mysqli_close($this->conn);
            }
        }
    }
    