<h2 class="center">Profile page</h2>
<a href="/">Home Page</a>

<div class="form">
    <h2>Modifier les informations</h2>
    <form method="POST" action="/updateUser">
        <input type="email" value="<?= $_SESSION["email"] ?? "" ?>" required name="email" placeholder="Votre email"><br>
        <input type="text" value="<?= $_SESSION["name"] ?? "" ?>" name="name" placeholder="Votre prénom"><br>
        <input class="btn btn_green" type="submit" value="Modifier">
    </form>
    <a href="/resetPassword">Reset le mot de passe</a>
</div>