<header>
    <!-- header.php -->
    <img src="./logo_again.png" alt="Website logo" />
    <div id="logo">Save The Dayte</div>
    <nav>
        <ul>
            <li><a href="./commercial_ads.php">Sponsorer</a></li>
            <li><a href="./about.php">Om Save The Dayte</a></li>
            <li><a href="./index.php">Annonstorget</a></li>
            <li><a href="./rapport.php">Rapport</a></li>

            <?php
            if(isset($_SESSION['username']) && !!empty($_SESSION['username']))
            {
                echo "<li><a href='./logout.php'>Logga ut</a></li>";
                echo "<li><a href='./profile.php'>". $_SESSION['username']."'s profil</a></li>";
            }
            else
            {
                echo "<li><a href='./login.php'>Logga in</a></li>";
                echo "<li><a href='./register.php'>Registrera mig</a></li>";
            }
            ?>
        </ul>
    </nav>
</header>