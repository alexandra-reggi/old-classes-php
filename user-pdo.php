<?php

session_start();

//Exercice: Classe user comme celle de (user.php) mais avec les requêtes en langage pdo et plus sql


//Tentative de connexion à la base de données
try{
//Chaine de connexion, serveur, base, encodage, port, user, pw
    $db = new PDO('mysql:host= localhost; dbname= classes; charset= utf8', 'root', '');
////Active la gestion des erreurs
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
////Cacher les messages d'erreur en production.
    //Envoyer email ou fichier de log à la place
    echo 'Échec lors de la connexion : ' . $e->getMessage();
}


//ATTRIBUTS
class userpdo{

    private $id;
    public $login;
    public $email;
    public $firstname;
    public $lastname;


//FONCTIONS
//Crée l’utilisateur en base de données. Retourne un tableau contenant l’ensemble des informations concernant l’utilisateur créé.
    public function register($login, $password, $email, $firstname, $lastname){

        $password = password_hash($password,PASSWORD_DEFAULT);

        $password_confirm =  $password;

        $req_login= $db-> prepare( "SELECT * FROM `utilisateurs` WHERE `login` = ? " );
        $req_login->execute(array($login));
        $resultat= $req_login->fetchall();
        var_dump($resultat);

        if(count($resultat) != 0){
            echo "Désolé ce login est déjà utilisé";
        }

        else{
            if($password == $password_confirm){
                $req_register= $db->prepare("INSERT INTO `utilisateurs`(`login`, `password`, `email`, `firstname`, `lastname`) VALUES (?, ?, ?, ?, ?)");
                $req_register->execute(array($login, $pwd_hash, $email, $firstname, $lastname));

                echo "utilisateur enregistré";

                return $user_array= array($login, $pwd_hash, $email, $firstname, $lastname);
            }

            else{
                echo "Votre mot de passe est différent";
            }
        }

    }


//Connecte l’utilisateur, modifie les attributs présents dans la classe et retourne un tableau contenant l’ensemble de ses informations.
    public function connect($login, $password){


        if(!empty($login) && !empty($password)){

            $req_connect= $db-> prepare( "SELECT * FROM `utilisateurs` WHERE `login` = ? " );
            $req_connect->execute(array($login));
            $resultat= $req_connect->fetch();


            if(count($resultat) == 0)
            {
                echo "Login ou mot de passe incorrect";
            }
            elseif(password_verify($password, $resultat['password']))
            {
                $this->id= $resltat['id'];
                $this->login= $resltat['login'];
                $this->email= $resltat['email'];
                $this->firstname= $resltat['firstname'];
                $this->lastname= $resltat['lastname'];

                $req_id= $db-> prepare("SELECT id FROM `utilisateurs` WHERE `login` = ? ");
                $req_id->execute(array($login));
                $resultat= $req_id->fetch();

                $_SESSION["login"]= $login;
                $_SESSION["id"]=$resultat['id'];
                $_SESSION['connected']=1;
                echo "Vous êtes connecté";
            }
            else
            {
                echo "Login ou mot de passe incorrect";
            }
        }
        else{
            echo "Veuillez remplir tous les champs";
        }

    }

//Déconnecte l’utilisateur.
    public function disconnect(){

        unset($_SESSION);
        session_destroy();
        return "Vous êtes déconnecté";

    }


//Supprime et déconnecte l’utilisateur.
    public function delete(){

        $req_delete= $db->prepare(" DELETE FROM `utilisateurs` WHERE login = '$this->login' ");
        $req_delete->execute();
        unset($_SESSION);
        session_destroy();
        echo "Utilisateur supprimé";
    }

//Modifie les informations de l’utilisateur en base de données.
    public function update($login, $password, $email, $firstname, $lastname){

        $password_confirm= $password;
        $pwd_hash=password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        if(!empty($login) && !empty($password) && !empty($password_confirm) )
        {

            $req_login= $db->prepare("SELECT * FROM `utilisateurs` WHERE `login` = ? ");
            $req_login-> execute([$login]);
            $compare_login= $req_login->fetchall();
            var_dump($compare_login);


            if(count($compare_login) != 0)
            {
                echo "Désolé ce login est déjà utilsé";
            }

            elseif(!empty($password) && !empty($password_confirm))
            {
                if($password === $password_confirm )
                {

                    $req_update= $db-> prepare("UPDATE `utilisateurs` SET `login`=  ? ,`password`= ? , email= ? , firstname= ? , lastname = ? WHERE id = $this->id") ;

                    $req_update->execute(array($login, $pwd_hash, $email, $firstname, $lastname ));

                    $_SESSION["login"]=$login;
                }

                else
                {

                    echo "Mot de passe différent";
                }
            }
        }
    }



//Retourne un booléen permettant de savoir si un utilisateur est connecté ou non.
    public function isConnected(){

        if(isset($_SESSION['connected']))
        {
            return true;
        }

        else
        {
            return false;
        }
    }


//Retourne un tableau contenant l’ensemble des informations de l’utilisateur.
    public function getAllInfos(){

        $req_all_info= $db-> prepare("SELECT * FROM utilisateurs WHERE login= '$this->login'");
        $req_all_info->execute();
        $resultat= $req_all_info->fetchAll();

        return $resultat;
    }


//Retourne le login de l’utilisateur connecté.
    public function getLogin(){

         return $this->login;
    }

//Retourne l’adresse email de l’utilisateur connecté.  
    public function getEmail(){

        return $this->email;
    }


//Retourne le firstname de l’utilisateur connecté.
    public function getFirstname(){

        return $this->firstname;
    }


//Retourne le lastname de l’utilisateur connecté.
    public function getLastname(){

        return $this->lastname;
    }


//Met à jour les attributs de la classe à partir de la base de données.
    public function refresh(){

        $req= $db-> prepare(" SELECT * FROM utilisateurs WHERE id = '$this->id' ");
        $req->execute();
        $resultat= $req->fetchAll();

         $this->login =$resultat[0]['login'];
         $this->email =$resultat[0]['email'] ;
         $this->firstname =$resultat[0]['firstname'] ;
         $this->lastname =$resultat[0]['lastname'] ;

         return $array =[ $this->login, $this->email, $this->firstname, $this->lastname];
    }
}

/*________________________________APPEL DES FUNCTIONS________________________________*/

$user= new userpdo;

 $user->register("alex","secret","alexandra.reggi@laplateforme","alexandra", "reggi");
 $user->connect("alex","secret");
 $user->update("alex","secret","alexandra.reggi@laplateforme","alexandra", "reggi")
 var_dump($user->getAllInfos());
 $user->delete();
 var_dump($user->refresh());
$state= $user->disconnect();
var_dump($state);

?>