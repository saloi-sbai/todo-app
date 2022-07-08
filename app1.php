<?php
session_start();
$task = $_POST["task"] ?? "";
echo $_SESSION["user_id"];


if (!isset($_SESSION["user_id"])) {
    die("utilisateur non authentifié");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1" />
</head>

<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <a class="navbar-brand" href="https://sourcecodester.com">Sourcecodester</a>
        </div>
    </nav>
    <div class="col-md-3"></div>
    <div class="col-md-6 well">
        <h3 class="text-primary">PHP - Simple To Do List App</h3>
        <hr style="border-top:1px dotted #ccc;" />
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <center>
                <form method="POST" class="form-inline" action="add_query.php">
                    <input type="text" class="form-control" name="task" required />
                    <button class="btn btn-primary form-control" name="add">Add Task</button>
                </form>
            </center>
        </div>
        <br /><br /><br />
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Task</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once("./pdo.php");
                $query = $pdo->query("SELECT * FROM tasks WHERE user_id = :user_id");
                $query = $pdo->prepare($query);
                $query->execute([
                    ":user_id" => $_SESSION["user_id"]
                ]);
                $count = 1;
                while ($fetch = $query->fetchAll(PDO::FETCH_ASSOC)) {
                    echo "here  :" . $count;
                ?>
                    <tr>
                        <td><?php echo $count++ ?></td>
                        <td><?php echo $fetch['task_id'] ?></td>
                        <td><?php echo $fetch['title'] ?></td>
                        <td colspan="2">
                            <center>
                                <?php
                                if ($fetch['status'] != "Done") {
                                    echo
                                    '<a href="update_task.php?task_id=' . $fetch['task_id'] . '" class="btn btn-success"><span class="glyphicon glyphicon-check"></span></a> |';
                                }
                                ?>
                                <a href="delete_query.php?task_id=<?php echo $fetch['task_id'] ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
                            </center>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>