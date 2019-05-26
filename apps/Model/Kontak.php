<?php
class Kontak{
    protected $config;
    protected $session;
    protected $database;
    protected $query;
    protected $table;
    
    public function __construct() {
        $this->session = new \NG\Session;
        $this->config = \NG\Registry::get('config');
        $this->database = \NG\Registry::get('database');
        $this->query = new \NG\Query();
        $this->table = 'tblkontak';
    }
    
    public function insertKontak($data){
        $database = $this->database;
        $query = $this->query;
        $table = $this->table;
        $sql = $query->insert($table, $data);
        $result = $database->query($sql);
        return $result;
    }
    
    public function deleteKontak($id){
        $database = $this->database;
        $query = $this->query;
        $table = $this->table;
        $sql = $query->delete()->from($table)->where("id = ?", $id);
        /*
        OR:
        $sql = $query->delete()->from($table)->where("id = $id");
        */
        $result = $database->query($sql);
        return $result;
    }
    
    public function fetchKontak(){
        $database = $this->database;
        $query = $this->query;
        $table = $this->table;
        $sql = $query->select()->from($table);
        $result = $database->fetchAll($sql);
        
        return $result;
    }
    
    public function getKontak($id){
        $database = $this->database;
        $query = $this->query;
        $table = $this->table;
        $sql = $query->select()->from($table)->where("id = ?", $id);
        $result = $database->fetchRow($sql);
        
        return $result;
    }
}
?>