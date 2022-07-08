<?php
require_once("./pdo.php");
session_start();
$name = $_POST["name"] ?? "";
$password = $_POST["password"] ?? "";
$confirmPassword = $_POST["confirmPassword"] ?? "";
$salt = 'ZDCngr*&22/';


if (isset($_POST["register"])) {
    if ($name && $password && $confirmPassword) {
        echo "je suis là 1er IF";
        $sql = "SELECT name FROM users WHERE name = :name";
        $query = $pdo->prepare($sql);
        $query->execute([":name" => $name]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $username = $result["name"];

        if ($name !== $username) {
            $password = hash('md5', $salt . htmlentities($password));
            $confirmPassword = hash('md5', $salt . htmlentities($confirmPassword));

            if ($password === $confirmPassword) {
                $sql = "INSERT INTO users(name,password) VALUES(:name, :password)";
                $query = $pdo->prepare($sql);
                $query->execute([":name" => $name, ":password" => $password]);
                $_SESSION["success"] = "utilisateur crée";
                header("Location: index.php");
                return;
            } else {
                $_SESSION["error"] = "les mots de passes ne correspondent pas";
                header("Location: register.php");
                return;
            }
        } else {
            $_SESSION["error"] = "ce pseudo existe déja";
            header("Location: register.php");
            return;
        }
    } else {
        $_SESSION["error"] = "Tous les champs sont requis";
        header("Location: register.php");
        return;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>s'enregistrer</title>
</head>

<body>


    <div class="container">

        <form method="POST">

            <?php
            // pour afficher les messages d'erreur dans la page, sinon on ne voit pas les erreurs.
            if (isset($_SESSION["error"])) {
                echo "<small style='color: red'>{$_SESSION["error"]}</small>";
                unset($_SESSION["error"]);
            }
            ?>
            
    <form method="POST">
        <div>
            <label for="name">nom d'utilisateur :</label>
            <input type="text" name="name" id="name">
        </div>
        <div>
            <label for="password">mot de passe :</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="confirmPassword">confirmer le mot de passe :</label>
            <input type="password" name="confirmPassword" id="confirmPassword">
        </div>
        <input type="submit" name="register" value="s'enregister">
        <a href="./index.php">annuler</a>
    </form>
</body>

</html>