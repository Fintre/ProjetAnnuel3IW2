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

<div class="form">
    <h2>Reset mot de passe</h2>
    <form method="POST" action="/sendNewPassword">
        <input type="email" value="<?= $_POST["email"] ?? "" ?>" required name="email" placeholder="Votre email"><br>
        <input class="btn btn_green" type="submit">
    </form>
</div>