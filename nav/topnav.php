<div class="top-header">
    
        <p>MASTERING COOKING</p>
    </div>
    <div id="navigation" class="navigation">
        <h3 class="hidden" id="logo">MASTERING COOKING</h3>
        <?php
        if (isset($_SESSION['username'])) {
        ?>
        <ul>
            <li><a href="index.php?category=0">Home</a></li>
        <?php
            if ($_SESSION['role'] == "admin") {
                echo '<li><a href="create.php">Create recipe</a></li>';
                echo '<li><a href="manage.php">Manage</a></li>';
            }
        ?>
            <li><a href="logout.php"><?php echo '<img class="avatar" src="images/user.png">&nbsp;&nbsp;'.$_SESSION['username']; ?> (logout)</a></li>
        </ul>
        
        <?php
        }else{
        ?>
        <ul>
            <li><a href="index.php">Home</a></li>
        
            <li><a href="signin.php">Sign in</a></li>
        </ul>
        <?php
        }
        ?>
        
</div>
