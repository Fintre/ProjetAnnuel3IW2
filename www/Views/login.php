<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?> 
            <li><?= $error ; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<h2>Vous n'avez pas encore de compte ? <a href="/signup">S'inscrire</a></h2>
<a href="/">Home Page</a>

<div class="form">
    <h2>Connection</h2>
    <form method="POST" action="/signinUser">
        <input type="email" value="<?= $_POST["email"] ?? "" ?>" required name="email" placeholder="Votre email"><br>
        <input type="password" required name="pwd" placeholder="Votre mot de passe"><br>
        <input class="btn btn_green" type="submit" value="Se connecter">
    </form>
    <a href="/resetPassword">Reset le mot de passe</a>
</div>