<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?> 
            <li><?= $error ; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<h2>Reset votre mdp</h2>
<a href="/">Home Page</a>
<a href="/login">Connection Page</a>

<?php
if(isset($_SESSION['error'])) {
    echo '<p style="color:red">'.$_SESSION['error'].'</p>';
    unset($_SESSION['error']);
}
?>

<div class="form">
    <h2>Reset mot de passe</h2>
    <form method="POST" action="/updatePassword">
        <input type="hidden" name="email" value="<?= $_GET["email"]?>">
        <input type="password" required name="pwd" placeholder="Votre mot de passe"><br>
        <input type="password" required name="pwdConfirm" placeholder="Confirmation du mot de passe"><br>
        <input class="btn btn_green" type="submit" value="Modifier le mdp">
    </form>
</div>