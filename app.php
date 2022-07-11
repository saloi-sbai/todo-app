<?php
require_once("./pdo.php");
session_start();
$task = $_POST["task"] ?? "";

if (!isset($_SESSION["user_id"])) {
    die("utilisateur non authentifié");
}

//recuperer la liste des taches pour l'utilisateur connecté
$sql = "SELECT * FROM tasks WHERE user_id = :user_id";
$query = $pdo->prepare($sql);
$query->execute([
    ":user_id" => $_SESSION["user_id"]
]);
//fetchAll pour bien récuperer tous les resulats dans la base et les mettre dans la variable $result.
$result = $query->fetchAll(PDO::FETCH_ASSOC);

//des que l'utilisateur appuie sur Ajouter 
if (isset($_POST["ajouter"])) {
    //on verifie si le champs tache n'est pas vide (!empty).
    if (!empty($task)) {
        //Ajout d'une nouvelle tache dans la BDD
        $insert = "INSERT INTO `tasks`(`title`, `user_id`) VALUES (:task,:user_id)";
        $query = $pdo->prepare($insert);
        $query->execute([
            ":task" => $task,
            ":user_id" => $_SESSION["user_id"]
        ]);
        $_SESSION["success"] = "tache ajoutée";
        header("Location: app.php");
        return;
    } else {
        //si le champs tache est vide alors on affiche un message d'erreur.
        $_SESSION["error"] = "Vous devez mettre tache à ajouter";
        header("Location: app.php");
        return;
    }
}

if (isset($_POST["edit"])) {
    echo "edit ";
    // on envoie à la page edit.php la variable task_id, en la mettant dans la session ($session)
    $_SESSION["task_id"] = $_POST["task_id"];
    header("Location: edit.php");
    return;
}

if (isset($_POST["delete"])) {
    echo "delete ";

    $deleteQuery = "DELETE FROM tasks WHERE task_id = :task_id";
    $query = $pdo->prepare($deleteQuery);
    $query->execute([
        ":task_id" => $_POST["task_id"]
    ]);
    $_SESSION["success"] = "Tache supprimée avec succes";
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

    <title>app</title>
</head>

<body>
    <div class="container">
        <?php
        if (isset($_SESSION["error"])) {
            echo "<small style='color: red'>{$_SESSION["error"]}</small>";
            unset($_SESSION["error"]);
        }
        if (isset($_SESSION["success"])) {
            echo "<small style='color: green'>{$_SESSION["success"]}</small>";
            unset($_SESSION["success"]);
        }
        ?>
        <nav>
            <button class="btn btn-outline-secondary btn-sm">
                <a href="logout.php"> se deconnecter</a>

            </button>

        </nav>
        <h1>Mes tache a faire</h1>
        <div class="new-task">
            <form method="POST">
                <input type="text" name="task" placeholder="Ajouter une nouvelle tache" required>
                <button class="btn btn-outline-success btn-sm" type="submit" name="ajouter">Ajouter</button>
            </form>
        </div>

        <div class="task-list">
            <h3>Liste des taches à faire</h3>
            <hr>
            <?php
            foreach ($result as $value) {
            ?>
                <div class="rows">
                    <form method="POST">
                        <input type="hidden" name="task_id" value="<?php echo $value['task_id'] ?>">
                        <input type="text" name="title" value="<?php echo $value['title'] ?>">

                        <button class="btn btn-outline-secondary btn-sm" type="submit" name="edit">edit</button>
                        <button class="btn btn-outline-danger btn-sm" type="submit" name="delete">Delete</button>
                    </form>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>


</html>