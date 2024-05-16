<?php
include_once "./classes/dbHandler.php";
$dbHandler = new dbHandler();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Heroes</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/fontawesome.min.js"></script>
</head>

<body>
    <header class="jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">Words</h1>
        </div>
    </header>

    <?php
    if (isset($_POST['create'])) 
    {
        if ($dbHandler->createWord($_POST['text'], $_POST['category'])) 
        {
            echo '<div class="alert alert-success" role="alert">Word created</div>';
        } 
        else 
        {
            echo '<div class="alert alert-danger" role="alert">Word creation failed</div>';
        }
    } 
    else if (isset($_POST['edit'])) 
    {
        if ($dbHandler->updateWord($_POST['wordId'], $_POST['text'], $_POST['category'])) 
        {
            echo '<div class="alert alert-success" role="alert">Word updated</div>';
        } 
        else 
        {
            echo '<div class="alert alert-danger" role="alert">Word update failed</div>';
        }
    } 
    else if (isset($_POST["delete"])) 
    {
        if ($dbHandler->deleteWord($_POST["wordId"])) 
        {
            echo '<div class="alert alert-success" role="alert">Word deleted</div>';
        } 
        else 
        {
            echo '<div class="alert alert-danger" role="alert">Word deletion failed</div>';
        }
    }

    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>id</th>
                <th>category</th>
                <th>text</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rows = $dbHandler->selectAll();
            foreach ($rows as $row) {
            ?>
                <tr>
                    <td><?= $row['wordId']; ?></td>
                    <td><?= $row['name']; ?></td>
                    <td><?= $row['text']; ?></td>
                    <td>
                        <form method="POST" action="edit.php">
                            <input type="hidden" name="wordId" value="<?= $row['wordId'] ?>" />
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-pen"></i>
                            </button>
                        </form>
                        <form method="POST" action="index.php">
                            <input type="hidden" name="wordId" value="<?= $row['wordId'] ?>" />
                            <button type="submit" name="delete" class="btn btn-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <form method='post' action='create.php'>
        <button type='submit' class="btn btn-success">
            <i class="fa fa-plus"></i> Create
        </button>
    </form>
</body>

</html>