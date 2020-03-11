<html>
    <head>
        <title>Bestel je snacks!</title>
            <link href="/css/all.css" media="all" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="main" class="rounded">
            <?php if (strlen($this->session->flashdata('info')) > 0 ||
                     strlen($this->session->userdata('info')) > 0) : ?>
                <div class="infoMsg rounded">
                   <?php echo $this->session->flashdata('info'); ?>
                     <?php
                        echo $this->session->userdata('info');
                        $this->session->unset_userdata('info');
                     ?>
                </div>
            <?php endif; ?>
            <div id="logo">
                <a href="/">
                    <img src="/images/logo_oxilion.jpg" />
                </a>
                <span class="menu">
                    <?php if ($this->user->isIngelogd()): ?>
                        <h1>Menu</h1>
                        <ul>
                            <li><a href="/">Bestellijst</a></li>
                            <li><a href="/index.php/snack/reorder"><b>RE-ORDER</b></a></li>
                            <li><a href="/index.php/snack/historie">Historie</a></li>
                            <li><a href="/index.php/snack/bestellingen">Bestellingen</a></li>
                        </ul>
                <h1>Beheer</h1>
            <ul>
                <li><a href="/index.php/beheer/snackstats">Snackstats</a></li>
                <li><a href="/index.php/beheer/orders">Orders v/d week</a></li>
                <li><a href="/index.php/beheer/declareren">Declareren</a></li></br>
                <li><a href="/index.php/beheer/nieuwpass">Wachtwoord wijzigen</a></li>
                <li><a href="/index.php/snack/loguit">Loguit</a></li>
            </ul>
            </br>
            Wil je weten wie deze week mag halen?</br>
            Kijk dan bij de <a href="/index.php/beheer/snackstats">Snackstats</a>!</p>
            De mee-snackende collega bovenaan de lijst (met het laagste saldo), is aan de beurt.
                    <?php endif; ?>
                </span>
            </div>
            <div id="inhoud">

