<?php


class User
{
    // ATTRIBUTS
    private $id;
    public $login;
    public $password;
    public $email;
    public $firstname;
    public $lastname;


    // FONCTIONS
    public function register($login, $password, $email, $firstname, $lastname)
    {

        $connexion= mysqli_connect("localhost", "root", "", "classes");
        $password = password_hash($password,PASSWORD_DEFAULT);
        var_dump($password);
        $requet= "SELECT * FROM user WHERE login = '".$login."'";
        $sql = mysqli_query($connexion, $requet);
        $resultat = mysqli_fetch_all($sql);

        if (!empty($resultat[0]) && $resultat[0] != "") 
        {
            return "<p>login déja existant!!</p>";
        }
        else
        {
            $sql = "INSERT INTO user VALUES (NULL,'".$login."', '".$email."', '".$password."', '".$firstname."', '".$lastname."')";
            mysqli_query($connexion, $sql);
            return "<p>vous êtes bien enregistré!!</p>"; 
        }
    }




    public function connect($login, $password)
    {
            $connexion=mysqli_connect("localhost","root","","classes"); 
            $requete= "SELECT * FROM user WHERE login='".$login."'";
            $query=mysqli_query($connexion, $requete);
            $resultat= mysqli_fetch_assoc($query);
echo "connecté";
                if(!empty($resultat))
                {
                    $passhash=$resultat['password'];
                    var_dump($passhash);
                    if(password_verify($password, $passhash))
                    {
                        $_SESSION['login'] = $resultat['login'];
                        var_dump($_SESSION);
                        $this->id = $resultat['id'];
                        $this->login = $resultat['login'];
                        $this->password = $resultat['password'];
                        $this->email = $resultat['email'];
                        $this->firstname = $resultat['firstname'];
                        $this->lastname = $resultat['lastname'];

                        return "<p><STRONG>BIENVENUE</STRONG></p>";
                        header("refresh:2;url=index.php");
                    }

                    else
                    {
                        return "<p><STRONG>Désolé nous n'avons pas pu vous identifier</STRONG></p>";  
                    }
                
            
                } 

    } 
    


    public function disconnect()
        {
            unset($_SESSION);
            session_destroy(); 
        }



    public function delete()
    {
        $connexion =  mysqli_connect("localhost","root","","classes");
		$requete = "DELETE FROM utilisateurs WHERE id = ".$this->id.";";
		$query=mysqli_query($connexion,$requete);
        session_destroy();
    }



    public function update()
    {
        $connexion =  mysqli_connect("localhost","root","","classes");

            if(isset($_SESSION['login']))
            {
                if(isset($_POST['valider']))
                {
                    if(!empty($_POST['login']) && !empty($_POST['password'])) //post remplis//
                        {
                            $login=$_POST['login']; //le post devient cette variable//
                            $password=password_hash($_POST['password'] , PASSWORD_BCRYPT , array('cost'=>12)); //on hache le password du post et devient cette variable//

                            if($login != $this->login)// ducoup le new login est comparé à celui dans le bdd $this...
                            {
                                $nouveau_login= "SELECT id FROM user WHERE login='".$login."'";// login bdd//
                                $resultat3= mysqli_query($connexion, $nouveau_login);
                                $nombre_login=mysqli_num_rows($resultat3);
                                var_dump($nouveau_login);

                                $log = $_SESSION['login'];//la session devient cette variable//

                                $nouveau_login2= "SELECT id FROM user WHERE login= '$log'";//donc on select le nouveau login//
                                $resultat2= mysqli_query($connexion, $nouveau_login2);
                                $nombre_login2=mysqli_fetch_all($resultat2);
                                var_dump($nombre_login2);

                                if($nombre_login < 1)//si le nouveau login est inferieur à 1...//
                                {
                                    $requete= "UPDATE user SET login= '$login' WHERE login= '".SESSION['login']."'";//on enregistre//
                                    mysqli_query($connexion, $requete);
                                    $_SESSION['login'] = $_POST['login'];

                                    echo "<><strong> votre login a bien été modifié </strong></p>";
                                    header('Location: ');
                                    session_destroy();
                                }
                                else
                                {
                                    echo "<p><strong> Ce login est dejà pris </strong></p>"; 
                                }
    
                            }
                                else
                                {
                                    echo "<p><strong> Veuillez remplir touds les champs </strong></p>";
                                }
                                
                            
                        }
                    }
                }

    }




    public function isConnected()
    {
        if (isset($_SESSION['login']))
        {
            return true;
        }

    }




    public function getAllInfos()
    {
        $infos= array(
            "id"=> $this->id,
            "login"=> $this->login,
            "password"=>$this->password,
            "email"=>$this->email,
            "firstname"=>$this->firstname,
            "lastname"=>$this->lastname,
        );

        return $infos;
    }




    public function getLogin()
    {
        return $this->login;
    }



    public function getEmail()
    {
        return $this->email;
    }


    public function getFirstname()
    {
        return $this->firstname;
    }



    public function getLastname()
    {
        return $this->lastname;

    }


    public function refresh()
    {
        $_SESSION['test'] = "ruben";
        var_dump($_SESSION);
        $login= $_SESSION['login'];
        $connexion=mysqli_connect("localhost","root","","classes"); 
        $requete= "SELECT * FROM user WHERE login='".$login."'";
        $query=mysqli_query($connexion, $requete);
        $resultat= mysqli_fetch_array($query);

        if (isset($_SESSION['login']))
        {
            $this->id= $resultat[0];
            $this->login= $resultat[1];
            $this->password= $resultat[2];
            $this->email= $resultat[3];
            $this->firstname= $resultat[4];
            $this->lastname= $resultat[5];
            var_dump($resultat);

            return($this);
        }
    }

   

}


/*________________________________APPEL DES FUNCTIONS________________________________*/


$alex= new User;

$alex->register("alex","secret","alexandra.reggi@laplateforme","alexandra", "reggi");

$alex->connect("alex","secret");

var_dump($alex->getAllInfos());

echo $alex->getLogin(); //ou var_dump($alex->getLogin());//

var_dump($alex->getEmail());

var_dump($alex->getFirstname());

var_dump($alex->getLastname());

var_dump($alex->refresh());


?>








