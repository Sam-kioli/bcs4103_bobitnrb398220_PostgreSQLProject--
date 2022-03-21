<?php
session_start();
include 'DB/db.php';

if (isset($_GET['category'])) {
    $category = $_GET['category'];
}else{
    $category = 0;
}
if (isset($_GET['delete'])) {
    $recipe_id = $_GET['delete'];
    $delete = "call deleteRecipe($recipe_id)";
    $exec = pg_query($conn, $delete);
    if ($exec) {
        $message = "Recipe deleted";
    }else{
        $message = "An error occured";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>recipes</title>
</head>
<body>
    <?php include 'nav/topnav.php' ?>
    <?php if (isset($message)):?>
        <div><p><?php echo $message; ?></p></div>
    <?php else: ?>
    <?php endif; ?>
    <div class="grid-layout">
        <div style="border:0;"></div>
        <div>
            <h5><a>MANAGE RECIPES</a></h5>
            <table border="1">
                <tr>
                    <th>Image</th>
                    <th>Recipe</th>
                    <th>Description</th>
                    <th>Last viewed</th>
                    <th colspan=3>Actions</th>
                </tr>
            <?php 
            include 'DB/db.php';
                $fetchRecipes = "select * from fetchRecipes($category)";
                $fetch = pg_query($conn, $fetchRecipes); 

                while ($data = pg_fetch_assoc($fetch)):
                    $db_dish_id = $data['id'];
                    $db_dish = $data['dish'];
                    $db_description = $data['description'];
                    $db_lastviewed = $data['last_viewed'];
                    $db_image = 'images/'.$data['image'];
                    $db_category = $data['category'];
            ?>
                <tr>
                    <td><img src="<?php echo $db_image; ?>" alt="Image"></td>
                    <td><?php echo $db_dish; ?></td>
                    <td class="ellipses"><?php echo $db_description; ?>
                    
                </td>
                    <td><?php echo $db_lastviewed; ?></td>
                    <td colspan="3">
                        <a href="viewrecipe.php?d=<?php echo $db_dish_id ?>">View</a>&nbsp;
                        <a href="create.php?edit=<?php echo $db_dish_id; ?>">Edit</a>&nbsp;
                        <a href="?delete=<?php echo $db_dish_id; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        </div>
        <div style="border:0;"></div>
    </div>
    <script src="main.js"></script>
</body>
</html>