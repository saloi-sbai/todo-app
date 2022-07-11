<?php
require_once("./pdo.php");
session_start();
$name = $_POST["name"] ?? "";
$password = $_POST["password"] ?? "";
$salt = 'ZDCngr*&22/';

if (isset($_POST["login"])) {
    unset($_SESSION["user_id"]);
    if ($name && $password) {
        $password = hash('md5', $salt . htmlentities($password));
        $sql = "SELECT user_id, name FROM users WHERE name = :name AND password = :password";
        $query = $pdo->prepare($sql);
        $query->execute([
            ":name" => $name,
            ":password" => $password
        ]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $_SESSION["success"] = "Vous etes connecté";
            $_SESSION["user_id"] = $result["user_id"];
            $_SESSION["name"] = $result["name"];
            header("Location: app.php");
            return;
        } else {
            $_SESSION["error"] = "il y a un probleme soit avec le nom soit avec le mot de passe";
            header("Location: login.php");
            return;
        }
    } else {
        $_SESSION["error"] = "merci de remplir tous les champs";
        header("Location: login.php");
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
    <title>connexion</title>
</head>

<body>

    <div class="container">

        <?php
        if (isset($_SESSION["error"])) {
            echo "<small style='color: red'>{$_SESSION["error"]}</small>";
            unset($_SESSION["error"]);
        }
        ?>
        <form method="post">
            <div>
                <label for="name">nom d'utilisateur :</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div>
                <label for="password">mot de passe :</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="connexion">
                <input type="submit" name="login" value="connecter" required>
                <a href="./index.php">annuler</a>
            </div>

        </form>

</body>

</html>