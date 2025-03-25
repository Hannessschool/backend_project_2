<?php
//logout.php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit();
echo "Du har loggats ut. <a href='login.php'>Logga in igen</a>";
?>
