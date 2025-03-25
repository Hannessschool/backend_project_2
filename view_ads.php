<?php
//view_ads.php
session_start();
include 'db_config.php';
?>

<!-- Filtreringsformulär -->
 <article>
 <form id="filterForm" method="get" action="index.php">
    <label for="gender">Könspreferens:</label>
    <select name="gender" id="gender">
        <option value="both" <?php if (isset($_GET['gender']) && $_GET['gender'] == 'both') echo 'selected'; ?>>Alla</option>
        <option value="male" <?php if (isset($_GET['gender']) && $_GET['gender'] == 'male') echo 'selected'; ?>>Man</option>
        <option value="female" <?php if (isset($_GET['gender']) && $_GET['gender'] == 'female') echo 'selected'; ?>>Kvinna</option>
    </select>

    <label for="min_likes">Minsta antal gillningar:</label>
    <input type="number" name="min_likes" id="min_likes" value="<?php echo isset($_GET['min_likes']) ? $_GET['min_likes'] : 0; ?>" min="0">

    <button type="submit">Filtrera</button>  
    <button type="button" id="loadMore">Ladda fler annonser</button>
</form>




