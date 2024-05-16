<!DOCTYPE html>
<html>

<head>
    <title>Create</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/fontawesome.min.js"></script>
</head>

<body>
<header class="jumbotron-fluid">
    <div class="container">
        <h1 class="display-4">Create Word</h1>
    </div>
</header>
<div class="container-fluid">
    <form method='post' action='index.php'>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="text">Word</label>
                <input id="text" class="form-control" name="text" required/>
            </div>
            <div class="form-group col-md-6">
                <label for="category">Category</label>
                <select id="category" name="category" class="form-control" required>
                    <?php
                    require_once './classes/dbHandler.php';
                    $dbHandler = new dbHandler();
                    $categories = $dbHandler->selectCategories();

                    //Gebruik de array van categories i.c.m. een foreach loop om de select te vullen met opties voor elke categorie
                    //Elke option moet als value de categoryId hebben en als inhoud te naam van de categorie.
                    foreach ($categories as $category)
                    {

                        // Voeg de optie toe aan de select-tag
                        echo "<option value='" . $category['categoryId'] . "' $selected>" . $category['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success col-md-2" name='create' value="create"
                    style="margin-top: 20px;">
                <i class="fa fa-plus"></i> Create
            </button>
        </div>
    </form>
</div>
</body>

</html>