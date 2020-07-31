<?php

session_start();

//l'exercice est: "Ouvre une connexion à un serveur MySQL"


class lpdo{
    
    private $bdd;
    private $lastquery;
    private $lastresult;


    public function __construct($host=null, $username=null, $password=null , $db=null){

        $this->bdd= new mysqli($host, $username, $password, $db);
    }


//Ferme la connexion au serveur SQL en cours s’il y en a une et en ouvre une nouvelle.
    public function connect($host, $username, $password, $db){

        if($this->bdd){

            $this->bdd->close();
            $this->bdd= new mysqli($host, $username, $password, $db);
        }

        else{
            $this->bdd= new mysqli($host, $username, $password, $db);
        }

    }


//Ferme la connexion au serveur MySQL.
    public function __destruct(){
        $this->bdd= null;
    }


//Ferme la connexion au serveur MySQL.
    public function close(){
        $this->bdd->close();
    }


//Exécute la requête $query et retourne un tableau contenant la réponse du serveur SQL.
    public function execute($query){

        $data=$this->bdd->query($query);
        $result= $data-> fetch_all();
        $this->lastquery=$query;
        $this->lastresult=$result;
        return $result;

    }

//Retourne la dernière requête SQL ayant été exécutée, false si aucune requête n’a été exécutée.
    public function getLastQuery(){
        return $this->lastquery;
    }


//Retourne le résultat de la dernière requête SQL exécutée, false si aucune requête n’a été exécutée.
    public function getLastResult(){
        return $this->lastresult;
    }


//Retourne un tableau contenant la liste des tables présentes dans la base de données.
    public function getTables(){
        $tables = $this->bdd->query(" SHOW TABLES ;");
        $result= $tables->fetch_all();
        return $result;
    }



//Retourne un tableau contenant la liste des champs présents dans la table passée en paramètre, false en cas d’erreur.
    public function getFields($table){
        $fields= $this->bdd->query(" SHOW COLUMNS FROM $table ; ");
        $result= $fields->fetch_all();
        return $result ;
    }

}


$user= new lpdo("localhost","root","","classes");
$user->connect("localhost","root","","classes");
$query= "SELECT * FROM utilisateurs";
$result= $user->execute($query);

$lastquery= $user->getLastQuery();
var_dump($lastquery);
$lastresult= $user->getLastResult();
var_dump($lastresult);
$tab= $user->getTables();
var_dump($tab);

$table= 'utilisateurs';

$field= $user->getFields($table);
var_dump($field);
}

?>