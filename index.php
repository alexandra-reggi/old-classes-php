<!doctype html>
 
<?php

session_start();

var_dump($_SESSION['login']);

include("user.php");

$alex= new User();

?>


<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>idex php</title>
    <link rel="stylesheet" href="user.php">
<head>

    <main>

            <h3>Register</h3>

            <form name= "formulaire" method="post">
                <label> Login </label>
                <input type="login" name="login" /><br>
                <label> Email </label>
                <input type="email" name="email" /><br>
                <label> Password </label>
                <input type="password" name="password" /><br>
                <label> Firstname </label>
                <input type="firstname" name="firstname" /><br>
                <label> Lastname </label>
                <input type="lastname" name="lastname" /><br>
                <label> Valider <label>
                <input type="submit" name="reg" value="valider"/>     
            </form><br></br>

            <?php
                if(isset($_POST['reg']))
                {
                    $alex->register($_POST['login'], $_POST['password'], $_POST['email'],$_POST['firstname'],$_POST['lastname']);
                }
            ?>


            <h3>Connect</h3>

            <form name= "formulaire" method="post">

                <label> Login </label>
                <input type="login" name="login" /><br>
                <label> Password </label>
                <input type="password" name="password" /><br>
                <label> Valider <label>
                <input type="submit" name="con" value="valider"/>     
            </form><br></br>

            <?php
                if(isset($_POST['con']))
                {
                    $alex->connect($_POST['login'], $_POST['password']);
                    
                }
            ?>


            <h3>Update</h3>
            <form name= "formulaire" method="post">

                
                <label> Login </label>
                <input type="login" name="login"/><br>
                <label> Email </label>
                <input type="email" name="email" /><br>
                <label> Password </label>
                <input type="password" name="password" /><br>
                <label> Firstname </label>
                <input type="firstname" name="firstname" /><br>
                <label> Lastname </label>
                <input type="lastname" name="lastname" /><br>
                <label> Valider <label>
                <input type="submit" name="valider" value="valider"/>     
                </form>

                <?php
                    if(isset($_POST['valider']))
                    {
                        $alex->update($_POST['login'], $_POST['password']);
                    }
                ?>

                
                
                <?php
                    if($_SESSION['login'])
                    {
                    var_dump($alex->getAllInfos());
                    }     
                ?>
            
    <main>
<html>
