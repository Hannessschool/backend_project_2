<?php 
//index.php
include "handy_methods.php";
include "db_config.php";
?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save The Dayte</title>
    <link rel="stylesheet" href="./style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="./script.js" defer></script>
</head>
<body>
    <div id="container">
    <?php include "header.php";?>
        <section>
            <article>
            <?php include "view_profiles.php";?>
            </article>
            <article>
            <?php include "load_ads.php";?>
            </article>
              <article>
                <?php include "filter_form.php"; ?>
            </article>
            <article>
                <?php include "fetch_filtered_profiles.php"; ?>
            </article>
        <footer>
            Made by Hannes och Katinka<sup>&#169;</sup>
        </footer>
        </div>
    </body>
</html>
