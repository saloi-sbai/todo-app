<?php
session_start();
//connexion à la bdd
require_once("./pdo.php");


//on protege la page, si la personne n'est pas authentifiée, elle sort.
if (!isset($_SESSION["user_id"])) {
    die("utilisateur non authentifié");
}

//on recupere la tache qui a le n° de task id que l'on veut modifier et on met le resultat de la query dans $result.
$sql = "SELECT * FROM tasks WHERE task_id = :task_id";
$query = $pdo->prepare($sql);
$query->execute([
    ":task_id" => $_SESSION["task_id"]
]);
$result = $query->fetch(PDO::FETCH_ASSOC);


//on enregistre la correction lorsque on appuie sur enregistrer.
if (isset($_POST["save"])) {
    echo "save ";

    //on recupere les variables task_id et title
    $task_id = $_SESSION["task_id"];
    $title = $_POST["title"];

    // on fait la requette
    $updateQuery = "UPDATE tasks SET title = :title WHERE task_id = :task_id";
    // on se protege contre les injection sql
    $query = $pdo->prepare($updateQuery);

    // on execute la requete, on affiche un message et on retourne sur la page app.php
    $query->execute([
        ":task_id" => $task_id,
        ":title" => $title
    ]);
    $_SESSION["success"] = "Tache modifiée avec succes";
    header("Location: app.php");
    return;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editer</title>
</head>

<body>
    <h1>Editer une Tâche</h1>
    <?php
    if (isset($_SESSION["error"])) {
        echo "<small style='color: red'>{$_SESSION["error"]}</small>";
        unset($_SESSION["error"]);
    }
    ?>
    <form method="POST">
        <input type="text" name="title" value="<?= $result["title"] ?>">
        <input type="submit" name="save" value="Enregistrer">
        <a href="./app.php">annuler</a>
    </form>

</body>

</html>