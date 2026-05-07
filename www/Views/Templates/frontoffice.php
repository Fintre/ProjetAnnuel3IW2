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
                <a href="/" class="header-logo">Roar<span class="header-logo-color">r.</span></a>
                <ul class="header-ul">
                    <li><a href="" class="header-ul-link">Tarifs</a></li>
                    <li><a href="" class="header-ul-link">Test</a></li>
                </ul>
                <div class="header-auth">
                    <a href="/login" class="header-auth-link header-auth-link-log">Se connecter</a>
                    <a href="/signup" class="header-auth-link link-button">Commencer</a>
                </div>
            </nav>
        </header>
        <?php include $this->pathView;?>
    </body>
</html>