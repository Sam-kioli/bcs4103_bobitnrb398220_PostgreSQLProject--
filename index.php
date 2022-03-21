<?php


session_start();
include 'DB/db.php';

if (isset($_GET['category'])) {
    $category = $_GET['category'];
} elseif(isset($_GET['view'])) {
    $category = "x";
}else{
    $category = 0;
}
if (isset($_GET['c'])) {
    $category_title = $_GET['c'];
} else {
    $category_title = "All Recipes";
}
if (isset($_GET['delete'])) {
    $recipe_id = $_GET['delete'];
    $delete = "call deleteRecipe($recipe_id)";
    $exec = pg_query($conn, $delete);
    if ($exec) {
        $message = "Recipe deleted";
    } else {
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
        <div class="sticky-left">
            <div>
                <h5>Recipe categories</h5>
                    <ol class="categories" type="number">
                    <?php
                        
                        $getCategories = "select * from getCategories()";
                        $exec = pg_query($conn, $getCategories);

                        while ($data = pg_fetch_assoc($exec)):
                            $db_category_id = $data['category_id'];
                            $db_title = $data['title'];
                    ?>
                        <li><a href="?category=<?php echo $db_category_id; ?>&c=<?php echo $db_title; ?>"><?php echo $db_title;?></a></li>
                    <?php
                        endwhile;
                        pg_close($conn);
                    ?>
                    <li><a href="?view=past5">Viewed in the last 5 hours</a></li>
                    </ol>
            </div>
            <p>&copy;2021</p>
        </div>
        <div>
            <?php if ($category != "x"):?>
            <h3>Recipe of the Day!</h3>
            <?php
            
            include 'DB/db.php';
                $fetchRecipes = "select * from getRecipeOfTheDay()";
                $fetch = pg_query($conn, $fetchRecipes);

                if ($data = pg_fetch_assoc($fetch)):
                    $db_dish_id = $data['id'];
                    $db_dish = $data['dish'];
                    $db_description = $data['description'];
                    //$db_lastviewed = $data['last_viewed'];
                    $db_image = 'images/'.$data['image'];
                    $db_rating = number_format((float)$data['avg_rating'], 1, '.', '');
                    //$db_category = $data['category'];
            ?>
            <div class="item" style="border:4px double black">
                <img src="<?php echo $db_image; ?>" alt="itemimage">
                <p class="title"><?php echo $db_dish; ?></p>
                <div class="dscrpt ellipses"><?php echo $db_description; ?>
                </div>
                <div style="background: #134666;color: white;width: fit-content;">⭐&nbsp;<?php echo $db_rating; ?> rating</div>
                <a class="viewbtn" href="viewrecipe.php?d=<?php echo $db_dish_id ?>">view more</a>
            </div>
            <?php endif; ?>
            <h5><a name="1.1"><?php echo $category_title; ?></a></h5>
            <?php
            
            $fetchRecipes = "select * from fetchRecipes($category)";
            $fetch = pg_query($conn, $fetchRecipes);
            $rows_returned = pg_num_rows($fetch);

            if($rows_returned > 0):
            while ($data = pg_fetch_assoc($fetch)):
            $db_dish_id = $data['id'];
            $db_dish = $data['dish'];
            $db_description = $data['description'];
            $db_lastviewed = $data['last_viewed'];
            $db_image = 'images/'.$data['image'];
            $db_category = $data['category']; 
            ?>
                <div class="item">
                    
                    <img src="<?php echo $db_image; ?>" alt="itemimage">
                    <p class="title"><?php echo $db_dish; ?></p>
                    <div class="dscrpt ellipses"><?php echo $db_description; ?>
                    </div>
                    <a class="viewbtn" href="viewrecipe.php?d=<?php echo $db_dish_id ?>">view more</a>
                </div>
            <?php endwhile;
            else:?>
                <div class="item"><h3>No recipes to display under this category</h3></div>
            <?php
            endif;
            else: ?>
            <h3>Recipes viewed in the last 5 hours</h3>
            <?php
            include 'DB/db.php';
                $fetchLastViewedRecipes = "select * from getlastviewed()";
                $fetch = pg_query($conn, $fetchLastViewedRecipes);
                $rows_returned = pg_num_rows($fetch);
                if($rows_returned > 0):
                while ($data = pg_fetch_assoc($fetch)):
                    $db_dish_id = $data['id'];
                $db_dish = $data['dish'];
                $db_description = $data['description'];
                $db_lastviewed = $data['last_viewed'];
                $db_image = 'images/'.$data['image'];
                $db_category = $data['category']; ?>
            <div class="item">
                
                <img src="<?php echo $db_image; ?>" alt="itemimage">
                <p class="title"><?php echo $db_dish; ?></p>
                <div class="dscrpt ellipses"><?php echo $db_description; ?>
                </div>
                <a class="viewbtn" href="viewrecipe.php?d=<?php echo $db_dish_id ?>">view more</a>
            </div>
            <?php endwhile; ?>
            <?php else: ?>
                <div class="item"><h3>No recipes have been viewed in the last 5 hours</h3></div>
            <?php endif; ?>
           <?php endif; ?>
        </div>
        <div style="">   
        </div>
    </div>
    <script src="main.js"></script>
</body>
</html>