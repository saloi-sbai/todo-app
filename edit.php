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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css">
    <link rel="stylesheet" href="styles.css">
    <title>Editer</title>
</head>

<body>
    <div class="container">
        <h3>Editer une Tâche</h3>
        <?php
        if (isset($_SESSION["error"])) {
            echo "<small style='color: red'>{$_SESSION["error"]}</small>";
            unset($_SESSION["error"]);
        }
        ?>
        <form method="POST">
            <input type="text" name="title" value="<?= $result["title"] ?>">
            <button class="btn btn-outline-secondary btn-sm" type="submit" name="save">Enregistrer</button>
            <!-- <input type="submit" name="save" value="Enregistrer"> -->
            <a href="./app.php">annuler</a>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>


</html>