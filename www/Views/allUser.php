<h2 class="center">Tous les utilisateurs</h2>
<a href="/">Home Page</a>
<a href="/profil">Profile Page</a>

<div class="form">
    <h2>Modifier les utilisateurs</h2>
    <?php foreach ($users as $user): ?>
        <div class="flex">
            <h2><?= $user['name'] ?></h2>
            <p><?= $user['email'] ?></p>
            <p>id :<?= $user['id'] ?></p>
            <p>admin :<?= $user['is_admin'] ? 'true' : 'false' ?></p>
            <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true && $user['is_admin'] == false): ?>
                <form action="/deleteUser" method="POST">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <input class="btn btn_red" type="submit" value="Supprimer">
            </form>
             <form action="/setAdmin" method="POST">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <input class="btn btn_red" type="submit" value="Mettre Admin">
                </form>
            <?php endif; ?>    
        </div>
    <?php endforeach; ?>
</div>