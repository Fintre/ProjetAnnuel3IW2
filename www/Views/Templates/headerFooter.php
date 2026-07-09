<html>
    <head>
        <title>Roarr</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script>
        (function(){var s=localStorage.getItem('theme');var t=s||'light';document.documentElement.setAttribute('data-theme',t);})();
        function toggleTheme(){var c=document.documentElement.getAttribute('data-theme');var n=c==='dark'?'light':'dark';document.documentElement.setAttribute('data-theme',n);localStorage.setItem('theme',n);}
        function toggleBurger(){document.body.classList.toggle('header-mobile-open');}
        </script>
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

                    <div class="header-auth">
                        <button type="button" class="header-theme" onclick="toggleTheme()" aria-label="Basculer thème">
                            <svg class="header-theme-icon header-theme-moon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" fill="currentColor"/></svg>
                            <svg class="header-theme-icon header-theme-sun" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="4" fill="currentColor"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </button>
                        <a href="/abonnement" class="header-ul-link">Abonnement</a>
                        <a href="/login" class="header-auth-link header-auth-link-log">Se connecter</a>
                        <a href="/signup" class="header-auth-link link-button">S'inscrire</a>
                    </div>
                    <button type="button" class="header-burger" onclick="toggleBurger()" aria-label="Menu">
                        <span class="header-burger-line"></span>
                        <span class="header-burger-line"></span>
                        <span class="header-burger-line"></span>
                    </button>
                <?php else: ?>
                    <a href="/accounts" class="header-logo">Roar<span class="header-logo-color">r.</span></a>
                    <ul class="header-ul">
                        <li><a href="/abonnement" class="header-ul-link">Abonnement</a></li>
                        <li><a href="/accounts" class="header-ul-link">Mes comptes</a></li>
                        <li><a href="/profil" class="header-ul-link">Profil</a></li>
                    </ul>
                    <div class="header-actions">
                        <button type="button" class="header-theme" onclick="toggleTheme()" aria-label="Basculer thème">
                            <svg class="header-theme-icon header-theme-moon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" fill="currentColor"/></svg>
                            <svg class="header-theme-icon header-theme-sun" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="4" fill="currentColor"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </button>
                        <form method="POST" action="/logout" class="header-logout">
                            <button type="submit" class="header-logout-btn">
                                <svg class="header-svg" fill="#1a1a17" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><title>logout</title><path d="M0 9.875v12.219c0 1.125 0.469 2.125 1.219 2.906 0.75 0.75 1.719 1.156 2.844 1.156h6.125v-2.531h-6.125c-0.844 0-1.5-0.688-1.5-1.531v-12.219c0-0.844 0.656-1.5 1.5-1.5h6.125v-2.563h-6.125c-1.125 0-2.094 0.438-2.844 1.188-0.75 0.781-1.219 1.75-1.219 2.875zM6.719 13.563v4.875c0 0.563 0.5 1.031 1.063 1.031h5.656v3.844c0 0.344 0.188 0.625 0.5 0.781 0.125 0.031 0.25 0.031 0.313 0.031 0.219 0 0.406-0.063 0.563-0.219l7.344-7.344c0.344-0.281 0.313-0.844 0-1.156l-7.344-7.313c-0.438-0.469-1.375-0.188-1.375 0.563v3.875h-5.656c-0.563 0-1.063 0.469-1.063 1.031z"></path></svg>
                            </button>
                        </form>
                    </div>
                    <button type="button" class="header-burger" onclick="toggleBurger()" aria-label="Menu">
                        <span class="header-burger-line"></span>
                        <span class="header-burger-line"></span>
                        <span class="header-burger-line"></span>
                    </button>
                <?php endif; ?>
            </nav>
        </header>

        <div class="header-mobile-backdrop" onclick="toggleBurger()"></div>
        <aside class="header-mobile">
            <button type="button" class="header-mobile-close" onclick="toggleBurger()" aria-label="Fermer">×</button>
            <?php if(empty($_SESSION['is_active'])): ?>
                <div class="header-mobile-head">
                    <button type="button" class="header-mobile-cta" onclick="toggleTheme()">Changer de thème</button>
                    <a href="/login" class="header-mobile-cta">Se connecter</a>
                    <a href="/signup" class="header-mobile-cta header-mobile-cta-primary">S'inscrire</a>
                </div>
                <a href="/abonnement" class="header-mobile-cta">Abonnement</a>
            <?php else: ?>
                <ul class="header-mobile-nav">
                    <li><a href="/abonnement" class="header-mobile-link">Abonnement</a></li>
                    <li><a href="/accounts" class="header-mobile-link">Mes comptes</a></li>
                    <li><a href="/profil" class="header-mobile-link">Profil</a></li>
                </ul>
                <div class="header-mobile-foot">
                    <button type="button" class="header-mobile-cta" onclick="toggleTheme()">Changer de thème</button>
                    <form method="POST" action="/logout" class="header-mobile-logout">
                        <button type="submit" class="header-mobile-logout-btn">Déconnexion</button>
                    </form>
                </div>
            <?php endif; ?>
        </aside>

        <?php include $this->pathView;?>
    </body>
</html>