<?php
    include 'DB/db.php';
    session_start();

if (isset($_POST['signin'])) {
    $username = $_POST['username'];
    $password = $_POST['p_word'];
    ##
    $role = "admin";

    $checkAccount = "select * from fetchUsers('$username')";

    if ($data = pg_fetch_array(pg_query($conn, $checkAccount))) {
        if ($data['user_password'] == $password) {
            $_SESSION['username'] = $data['username'];
            $_SESSION['userid'] = $data['user_id'];
            $_SESSION['role'] = $data['user_role'];

            switch ($_SESSION['role']) {
                case 'user':
                    header('location: index.php?category=0');
                    exit;
                break;

                case 'admin':
                    header('location: index.php?category=0');
                    exit;
                break;
                
                default:
                    header('location: index.php?category=0');
                    exit;
                break;
            }
            
           
            
            exit();
        } else {
            $form_error = "Incorrect username or password";
        }
    } else {
        $form_error = "Incorrect username or password";
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
    <div class="grid-layout">
        <div style="border:0"></div>
        <div>
            
            <form action="" class="form" method="POST">
                <h2>Sign in</h2>
                <p style="color:red;"><?php
                    if (isset($form_error)) {
                        echo $form_error;
                    } else {
                        echo '';
                    }
                ?></p>
                <p>
                <input class="input-text" type="text" name="username" placeholder="Username" value="<?php if (isset($_POST['username'])) {
                    echo $_POST['username'];
                } ?>" required>
                </p>
                <p>
                <input class="input-text" type="password" name="p_word" placeholder="Password" required>
                </p>
                <p>
                <input class="input-button" type="submit" name="signin" value="Sign in">
                </p>
            </form>
        </div>
        <div style="border:0"></div>
    </div>
</body>
</html>
