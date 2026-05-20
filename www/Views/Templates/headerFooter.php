<html>
    <head>
        <title>Roarr</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/Public/css/stylefo.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <nav class="header">
                <?php if(empty($_SESSION['is_active'])): ?>
                    <a href="/" class="header-logo">Roar<span class="header-logo-color">r.</span></a>
                    <ul class="header-ul">
                        <li><a href="/abonnement" class="header-ul-link">Abonnement</a></li>

                    </ul>

                    <div class="header-auth">
                        <a href="/login" class="header-auth-link header-auth-link-log">Se connecter</a>
                        <a href="/signup" class="header-auth-link link-button">Commencer</a>
                    </div>
                <?php else: ?>
                    <a href="/" class="header-logo">Roar<span class="header-logo-color">r.</span></a>
                    <ul class="header-ul">
                        <li><a href="/abonnement" class="header-ul-link">Abonnement</a></li>
                        <li><a href="/profil" class="header-ul-link">Profil</a></li>
                    </ul>
                    <form method="POST" action="/logout" class="header-logout">
                        <button type="submit" class="header-logout-btn">
                            <svg class="header-svg" fill="#1a1a17" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><title>logout</title><path d="M0 9.875v12.219c0 1.125 0.469 2.125 1.219 2.906 0.75 0.75 1.719 1.156 2.844 1.156h6.125v-2.531h-6.125c-0.844 0-1.5-0.688-1.5-1.531v-12.219c0-0.844 0.656-1.5 1.5-1.5h6.125v-2.563h-6.125c-1.125 0-2.094 0.438-2.844 1.188-0.75 0.781-1.219 1.75-1.219 2.875zM6.719 13.563v4.875c0 0.563 0.5 1.031 1.063 1.031h5.656v3.844c0 0.344 0.188 0.625 0.5 0.781 0.125 0.031 0.25 0.031 0.313 0.031 0.219 0 0.406-0.063 0.563-0.219l7.344-7.344c0.344-0.281 0.313-0.844 0-1.156l-7.344-7.313c-0.438-0.469-1.375-0.188-1.375 0.563v3.875h-5.656c-0.563 0-1.063 0.469-1.063 1.031z"></path></svg>
                        </button>
                    </form>
                   
                <?php endif; ?>     
            </nav>
        </header>
        <?php include $this->pathView;?>
    </body>
</html>