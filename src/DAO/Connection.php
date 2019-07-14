<?php 

    namespace src\DAO {

        use src\Util\Bind;

        class Connection {
            protected $conn;
            
            public function connect() {
                $this->conn = mysqli_connect(Bind::get('host'), Bind::get('db_user'), Bind::get('db_senha'), Bind::get('db'));
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
    