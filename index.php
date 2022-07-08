<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>bienvenue dans la base de données des tâches</h1>
    
    <?php
    // pour afficher les messages dans la page,
    if (isset($_SESSION["success"])) {
        echo "<small style='color: green'>{$_SESSION["success"]}</small>";
        unset($_SESSION["success"]);
    }
    ?>

    <a href="./register.php">enregistrez-vous</a>
    <a href="./login.php">connectez-vous</a>
    <p>essayez d'<a href="./app.php">ajouter des données</a> sans vous connecter</p>

</body>

</html>