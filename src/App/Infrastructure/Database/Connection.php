<?php

namespace App\Infrastructure\Database;

use PDO;
use PDOException;

class Connection{

    public $dbsistemas_proveedores;

    public function __construct(){
        
        $configPath = __DIR__ . '/../../../../config/database.json';
        $config = json_decode(file_get_contents($configPath),true);

        if (!$config) {
            die("Error al leer la configuración de la base de datos.");
        }

        $dbsistemas_proveedores = "mysql:host={$config['sistema_proveedores']['host']};dbname={$config['sistema_proveedores']['database']};charset=utf8"; 
        try{
            $this->dbsistemas_proveedores = new PDO($dbsistemas_proveedores,$config['sistema_proveedores']['username'],$config['sistema_proveedores']['password']);
            $this->dbsistemas_proveedores->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexioón con la base de datos sistema_proveedores: ".$e->getMessage());
        }
        
    }
}

?>
