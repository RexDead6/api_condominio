<?php 
class DBHelper{
    private $connection;

    // PROD
    
    /*
    private $host = 'mysql-condominio.alwaysdata.net';
    private $user = '328326';
    private $pass = 'Rex?4200';
    private $db = 'condominio_db';
    private $charset = 'utf8mb4';
    */
    
    // LOCAL
    
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $db = 'condominio';
    private $charset = 'utf8mb4';
    
    public function connect(){
        try {
                 
            $connection = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            return new PDO($connection, $this->user, $this->pass, $options);

        } catch(PDOException $e) {
            
            //echo 'Error en la conexión: ' . $e->getMessage();
        }
    }
}
?>