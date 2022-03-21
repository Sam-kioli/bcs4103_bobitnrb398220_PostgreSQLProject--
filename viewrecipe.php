<?php
session_start();
include 'DB/db.php';
if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
}
if (isset($_GET['d'])) {
    $recipe_id = $_GET['d'];
    $updateViewTime = "call updateViewTime($recipe_id)";
    pg_query($conn, $updateViewTime);
} else {
    header('Location: index.php');
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
    <div class="grid-layout">
        <div style="border: none;">
        <a class="viewbtn" href="javascript:history.back()"></a>
        <script>
            document.write('<a class="viewbtn" href="' + document.referrer + '">← Back</a>');
        </script>
        </div>
        <div>
            <?php
            include 'DB/db.php';
                $fetchSelectedRecipe = "select * from fetchSelectedRecipe($recipe_id)";
                $fetch = pg_query($conn, $fetchSelectedRecipe);

                if ($data = pg_fetch_assoc($fetch)):
                    $db_dish_id = $data['id'];
                    $db_dish = $data['dish'];
                    $db_description = $data['description'];
                    $db_lastviewed = $data['last_viewed'];
                    $db_image = 'images/'.$data['image'];
                    $db_category = $data['category'];
            ?>
            <h3><a name="1.1"><?php echo $db_dish; ?></a></h3>
            <div class="item">
                <img src="<?php echo $db_image; ?>" alt="itemimage">
                <p class="title"><?php echo $db_dish; ?></p>
                <div class="dscrpt"><?php echo $db_description; ?>
                </div>
            <?php  
            if (isset($_SESSION['role'])): 
                if($_SESSION['role'] == "admin"):?>
            
                <a href="create.php?edit=<?php echo $db_dish_id; ?>">Edit this recipe</a>
            <?php endif; ?>
            <?php endif; ?>
            </div>
            <?php endif; ?>
            
        </div>
        <div style="border: none;">   
        <style>
            label {
                float: left;
                padding: 0 .2em;
                text-align: center;
            }
            .widthmax{
                width:100%;
                display:inline-block;
            }
        </style>
        <?php if (isset($user_id)): ?>
        <h3>Rate this recipe</h3>
        <form action="" method="POST">
            <?php if (isset($form_error)): ?>
            <p class=""><?php echo $form_error; ?></p>
            <?php endif; ?>
            <input type="number" name="recipeid" value="<?php echo $db_dish_id; ?>" hidden>
            <input type="number" name="userid" value="<?php echo $user_id; ?>" hidden>
        <div class="widthmax">
            <label>1<br>
            <input type="radio" name=rating value="1"></label>
            <label>2<br>
            <input type="radio" name=rating value="2"></label>
            <label>3<br>
            <input type="radio" name=rating value="3"></label>
            <label>4<br>
            <input type="radio" name=rating value="4"></label>
            <label>5<br>
            <input type="radio" name=rating value="5"></label>
        </div>
        <div>
            Comment
            <textarea name="comment" cols="30" rows="6" style="resize:none;"></textarea>
        </div>
        <div>
            <input type="submit" name="submit_rating" value="submit">
        </div>
        </form>
        <?php else: ?>
        <?php endif; ?>
        </div>
    </div>
    <script src="main.js"></script>
</body>
</html>
<?php
    if (isset($_POST['submit_rating'])) {
        $var_recipe_id = $_POST['recipeid'];
        $var_user_id = $_POST['userid'];
        $var_rating = $_POST['rating'];
        $var_user_comment = $_POST['comment'];

        echo $query = "call rateRecipe($var_recipe_id, $var_user_id, $var_rating, '$var_user_comment')";

        $result = pg_query($query);
    
        if (!$result) {
            $form_error = "An error occured";
        } else {
            $form_error = "Thank you for your submission";
        }
    }
?>