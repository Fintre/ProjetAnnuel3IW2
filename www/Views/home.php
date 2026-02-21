<h2 class="center">Home page</h2>
<?php if(empty($_SESSION['is_active'])): ; ?>
<ul class="home">
    <a href="/signup">S'inscrire</a>
    <a href="/login">Se connecter</a>
    <a href="/admin/pages">Voir toutes les pages</a>
</ul>
<?php endif; ?>
<?php include("isActiveNav.php"); ?>