<h2>Vous avez déjà un compte ? <a href="/login">Se connecter</a></h2>
<a href="/">Home Page</a>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?> 
            <li><?= $error ; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>


<div class="form">
    <h2>Inscription</h2>
    <form method="POST" action="/addUser">
        <input type="text" value="<?= $_POST["firstname"] ?? "" ?>" name="name" placeholder="Votre prénom"><br>
        <input type="email" value="<?= $_POST["email"] ?? "" ?>" required name="email" placeholder="Votre email"><br>
        <input type="password" required name="pwd" placeholder="Votre mot de passe"><br>
        <input type="password" required name="pwdConfirm" placeholder="Confirmation du mot de passe"><br>
        <input class="btn btn_green" type="submit" value="S'inscrire">
    </form>
</div>