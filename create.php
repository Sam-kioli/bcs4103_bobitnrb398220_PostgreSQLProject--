<?php
session_start();
include 'DB/db.php';
if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
} elseif ($_SESSION['role'] == 'user') {
    header('Location: index.php?unauthorized');
}
    $edit_recipe_id = "";
    $edit_dish_name = "";
    $edit_category ="";
    $edit_image = "";
    $edit_description = "";

    // ****************************

if (isset($_POST['create'])) {
    $countfiles = count(array($_FILES['image']['name']));

    if ($countfiles > 0) {
        $dish_image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $dish_image);
    } else {
        $dish_image = "default.jpg";
    }

    $dish_name = $_POST['dish_name'];
    $dish_description = $_POST['dish_description'];
    $status = 0;
    $category = $_POST['category'];

    echo $query = "call createNewRecipe('$dish_name', $category, '$dish_image', '$dish_description', $status)";

    $result = pg_query($query);
    
    if (!$result) {
        $form_error = "An error occured";
    } else {
        $submit_status = 1;
        $getMaxId = "select * from getMaxId()";

        if ($data = pg_fetch_array(pg_query($conn, $getMaxId))) {
            $db_id = $data['id'];
            $db_dish = $data['dish'];
            $db_description = $data['description'];
            $db_image = $data['image'];
            $db_status = $data['status'];
        }
    }
}elseif (isset($_POST['update'])) {

    if(!file_exists($_FILES['image']['tmp_name']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
        $dish_image = "default.jpg";
    }else{
        $dish_image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $dish_image);
    }

    $recipe_id = $_GET['edit'];
    $dish_name = $_POST['dish_name'];
    $dish_description = $_POST['dish_description'];
    $status = 0;
    $category = $_POST['category'];

    $query = "call updaterecipe($recipe_id::integer, '$dish_name'::character varying,'$dish_image'::character varying, $category, '$dish_description'::text, $status::integer)";

    $result = pg_query($query);
    
    if (!$result) {
        $form_error = "An error occured";
    } else {
        // $submit_status = 1;
        $form_error = "UPDATE SUCCESSFUL";
    }
}
if (isset($_GET['edit'])) {
    $edit_recipe_id = $_GET['edit'];

    include 'DB/db.php';
    $fetchSelectedRecipe = "select * from fetchSelectedRecipe($edit_recipe_id)";
    $fetch = pg_query($conn, $fetchSelectedRecipe);

    if ($data = pg_fetch_assoc($fetch)) {
        $edit_recipe_id = $data['id'];
        $edit_dish_name = $data['dish'];
        $edit_description = $data['description'];
        $edit_lastviewed = $data['last_viewed'];
        $edit_image = 'images/'.$data['image'];
        $edit_category = $data['category'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tiny.cloud/1/spd1mi6471tur9krw7147q1874eisyty8nf8egg854eps7q0/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>tinymce.init({ selector:'textarea',
    menubar: false,
    plugins: 'lists',
    toolbar: 'bold italic underline numlist bullist'
     });</script>
    <title>recipes</title>
</head>
<body>
    <?php include 'nav/topnav.php' ?>
    <div style="grid-template-columns: 1fr 1fr;" class="grid-layout">
        <!-- <div style="border:0"></div> -->
        <div>
            <?php
            if (isset($_GET['edit'])):?>
                <h1>Edit recipe</h1>
            <?php else: ?>
                <h1>Create a new recipe</h1>
            <?php endif ?>
            <p style="color:red;"><?php
                    if (isset($form_error)) {
                        echo $form_error;
                    } else {
                        echo '';
                    }
                ?></p>
            <form action="" class="form" method="POST" enctype="multipart/form-data">
                <p>
                    Dish name <br>
                <input class="input-text" type="text" name="dish_name" value="<?php echo $edit_dish_name; ?>" placeholder="Dish name" required>
                </p>
                <p>
                    <select class="input-text" name="category" required>
                        <option value="" selected disabled>Category</option>
                    <?php
                    $getCategories = "select * from public.getCategories()";
                    $exec = pg_query($conn, $getCategories);
                    while ($data = pg_fetch_assoc($exec)):
                        $db_category_id = $data['category_id'];
                        $db_title = $data['title'];
                    ?>
                        <option value="<?php echo $db_category_id; ?>"><?php echo $db_title; ?></option>
                    <?php
                        endwhile;
                    ?>
                    </select>
                </p>
                <p>
                    Image <br>
                <input class="input-text" type="file" name="image" accept="image/png, image/jpeg" / id="image">
                </p>
                <p>
                    Description
                    <textarea name="dish_description"><?php echo $edit_description; ?></textarea>
                </p>
                <?php if(isset($_GET['edit'])): ?>
                    <p>
                    <input class="input-button" type="submit" name="update" value="Update recipe">
                    </p>
                <?php else: ?>
                    <p>
                    <input class="input-button" type="submit" name="create" value="Add recipe">
                    </p>
                <?php endif; ?>
            </form>
        </div>
        <div>
        <p><?php
            if (isset($submit_status)) {
                echo '<br><p style="text-transform: capitalize;font-size: 22px;font-weight:bold;text-decoration: underline;">'.$db_dish.'</p>';

                echo '<br><img src="images/'.$db_image.'" width="300px" height="auto"">';
                
                echo '<br>'.$db_description;

                $db_status;
            } else {
                echo 'Nothing to diplay';
            }
        ?></p>
        </div>
    </div>
</body>
<script src="main.js"></script>
</html>