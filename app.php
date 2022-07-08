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

//&& isset($_POST["task_id"])
if (isset($_POST["edit"])) {
    echo "edit ";

    // on envoie à la page edit.php la variable task_id, en la mettant dans la session ($session)
    $_SESSION["task_id"] = htmlentities($_POST["task_id"]);
    header("Location: edit.php");
    return;
}

if (isset($_POST["delete"])) {
    echo "delete ";
    $task_id = $_POST["task_id"];
    $deleteQuery = "DELETE FROM tasks WHERE task_id = :task_id";
    $query = $pdo->prepare($deleteQuery);
    $query->execute([
        ":task_id" => $task_id
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
    <title>app</title>
</head>

<body>
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
        <a href="logout.php"> se deconnecter</a>
    </nav>
    <h1>Tache a faire</h1>
    <div>
        <h1>Ajouter une nouvelle tache</h1>
        <form method="POST">
            <input type="text" name="task">
        
            <button type="submit" name="ajouter">Ajouter</button>
        </form>
    </div>

    <div>
        <h1>Liste des taches à faire</h1>
        <!-- <?php
                //if (isset($result)) {
                //    echo "<h3>Vous n'avez aucune tache active</h3>";
                /*
                    {
            echo $value["task_id"];
            echo $value["title"];
        }
                */
                //}
                ?> -->
        <?php
        foreach ($result as $value) {
        ?>

            <tr>
                <td><?php $task_id = $value['task_id'] ?></td>
                <td><?php echo $value['title'] ?></td>
                <td colspan="2">
                    <center>
                        <form method="POST">
                            <input type="hidden" name="task_id" value="<?php echo $task_id ?>">
                            <button class="btn btn-success" type="submit" name="edit">edit</button>
                            <button class="btn btn-success" type="submit" name="delete">Delete</button>
                        </form>
                    </center>
                </td>
            </tr>

        <?php
        }
        ?>
        </table>
    </div>
</body>

</html>