<?php if(isset($_SESSION["is_active"]) && $_SESSION["is_active"] === true): ; ?>
<nav class="isActiveNav">
    <a href="/profil">Profile Page</a>
    <a href="/dashboard">Dashboard Page</a>
    <a href="/users">Tous les utilisateurs</a>
    <a href="/admin/pages/create">Créer une page</a>
    <a href="/admin/pages">Voir toutes les pages</a>
</nav>
<?php endif; ?>