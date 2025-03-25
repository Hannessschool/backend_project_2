<!-- filter_form.php -->
<form id="filterForm" method="get" action="index.php">
    <label for="gender">Könspreferens:</label>
    <select name="gender" id="gender">
        <option value="both" <?= isset($_GET['gender']) && $_GET['gender'] == 'both' ? 'selected' : '' ?>>Alla</option>
        <option value="male" <?= isset($_GET['gender']) && $_GET['gender'] == 'male' ? 'selected' : '' ?>>Man</option>
        <option value="female" <?= isset($_GET['gender']) && $_GET['gender'] == 'female' ? 'selected' : '' ?>>Kvinna</option>
    </select>

    <label for="min_likes">Minsta antal gillningar:</label>
    <input type="number" name="min_likes" id="min_likes" value="<?= isset($_GET['min_likes']) ? htmlspecialchars($_GET['min_likes']) : 0 ?>" min="0">

    <button type="submit">Filtrera</button>
</form>
