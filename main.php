<?php

use fksTemplate\NavBar\BootstrapNavBar;
use vyfukTemplate\templateFunctions;

if (!defined('DOKU_INC')) die();
global $conf;
global $ID;

require_once(dirname(__FILE__) . '/tpl_functions.php');
require_once(dirname(__FILE__) . '/navBar/BootstrapNavBar.php');
require_once(dirname(__FILE__) . '/navBar/NavBarItem.php');
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $conf['lang']; ?>" lang="<?php echo $conf['lang']; ?>">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Miroslav Jarý, jason@vyfuk.mff.cuni.cz">
    <meta name="description"
          content="Výfuk, jméno našeho korespondenčního semináře, je vlastně zkratka jeho dlouhého názvu – Výpočty fyzikálních úkolů. Touto soutěží se snažíme ukázat, že fyzika je vlastně velmi zábavné a fascinující téma.">
    <meta name="keywords" content="vyfuk, výfuk,  fyzika, fyzikalni, fyzikální, seminar, seminář, soutez, soutěž">
    <meta name="robots" content="index,follow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/lib/tpl/vyfuk/css/bootstrap.min.css">
    <script src="https://use.fontawesome.com/f585fb7cc3.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
            crossorigin="anonymous"></script>
    <title><?php echo templateFunctions::get_title($ID); ?></title>
    <?php
    echo tpl_favicon(['favicon', 'mobile']);
    tpl_metaheaders();
    ?>
</head>
<body data-act="<?php echo $ACT; ?>" data-namespace="<?php echo getNS($ID); ?>" data-page_id="<?php echo $ID; ?>">
<div class="parallax-wrapper">
    <img class="parallax-bg w-100" src="/lib/tpl/vyfuk/images/wallpaper.png?v=3" alt="Výfuk wallpaper">
    <div class="parallax-fg">
        <div class="sticky-top bg-primary">
            <?php
            if (page_exists("system:menu_cs")) {
                $leftMenu = new BootstrapNavBar('full');
                $leftMenu->setClassName('navbar-expand-lg container')
                    ->addMenuText('menu', 'mr-auto')
                    ->addMenuText('login')
                    ->addBrand('', '', '', 50, null)
                    ->addTools('justify-content-end', true)
                    ->render();
            } else {
                echo "<p style='color: #fff'>Please create page <b>system:menu</b> containing menu structure.<br>Založte prosím stránku <b>system:menu</b> obsahující strukturu menu.</p>";
            }
            ?>
        </div>
        <div id="content" class="clearfix">
            <?php if ($ID == "start"): ?>
                <div id="landing-page" class="container p-0">
                    <div class="row justify-content-center justify-content-lg-between no-gutters" id="clouds">
                        <div class="col-lg-6 cloud-wrapper">
                            <div class="cloud-content m-1">
                                <h2>Tady je Výfučí!</h2>
                                <p><b>Výfuk</b>, jméno našeho korespondenčního semináře, je vlastně zkratka jeho
                                    dlouhého názvu – <b>Vý</b>počty <b>f</b>yzikálních <b>úk</b>olů. Touto soutěží se
                                    snažíme ukázat, že fyzika je vlastně velmi zábavné a fascinující téma.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 cloud-wrapper">
                            <div class="cloud-content m-1">
                                <h2>Jak to probíhá?</h2>
                                <p>Během školního roku postupně zveřejňujeme zadání šesti sérií. Každá z nich obsahuje
                                    pět úloh z různých oblastí fyziky, jeden problém týkající se našeho odborného
                                    textu (tzv. Výfučtení) a jeden zábavný experiment. Zapojit se můžete kdykoliv!</p>
                            </div>
                        </div>
                        <div class="col-lg-6 cloud-wrapper">
                            <div class="cloud-content m-1">
                                <h2>Co tím získám?</h2>
                                <p>Kromě mnoha zkušeností, které uplatníte po celý svůj život, můžete vyhrát hmotné
                                    ceny, ale především účast na našich akcích - dvou setkání a letním táboře. Na obojím
                                    můžete zakusit zajímavé exkurze, poutavé přednášky, spoustu her, výletů a přivést si
                                    domů spousty neopakovatelných zážitků.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 cloud-wrapper">
                            <div class="timer-content m-1">
                                <?php templateFunctions::draw_content("system:timer"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center justify-content-lg-between no-gutters" id="quickLinks">
                        <a class="col-lg" href="/ulohy/zadani">Aktuální zadání úloh</a>
                        <a class="col-lg" href="/ulohy/vyfucteni">Výfučtení</a>
                        <a class="col-lg" href="/akce/start">Akce</a>
                    </div>
                </div>
            <?php endif; ?>
            <div class="container dokuwiki clearfix" id="dokuwiki" data-do="<?php echo $ACT;
            tpl_flush(); ?>">
                <?php tpl_content(); ?>
            </div>
        </div>
        <footer>
            <div class="footer-cloud"></div>
            <div class="footer-content">
                <div class="container row m-auto align-items-center justify-content-center justify-content-lg-between">
                    <div class="col-lg order-lg-2">
                        <a href="/">
                            <img id="footerLogo" src="/lib/tpl/vyfuk/images/logo-white.svg" style="width: 100px"
                                 alt="Výfuk">
                        </a><br>
                        <a href="/o_webu">©<?php echo date("Y") ?> Výfuk</a><br>
                        Neváhej nás kontaktovat!<br>
                        <a href="mailto:vyfuk@vyfuk.mff.cuni.cz">vyfuk@vyfuk.mff.cuni.cz</a>
                    </div>
                    <div class="col-lg order-lg-3">
                        Korespondenční seminář Výfuk<br>
                        Matematicko-fyzikální fakulta UK<br>
                        V Holešovičkách 2<br>
                        180 00 Praha 8
                    </div>
                    <div class="social col-lg order-lg-1 row no-gutters">
                        <a class="col-3 px-1" href="https://facebook.com/ksvyfuk">
                            <?php include("icons/vyfuk-fb.svg"); ?>
                        </a>
                        <a class="col-3 px-1" href="https://www.instagram.com/ksvyfuk/">
                            <?php include("icons/vyfuk-ig.svg"); ?>
                        </a>
                        <a class="col-3 px-1" href="https://www.youtube.com/channel/UCUBv3Ydd-laLSY5DMimoE7w">
                            <?php include("icons/vyfuk-yt.svg"); ?>
                        </a>
                        <a class="col-3 px-1" href="https://vyfuk.mff.cuni.cz/discord">
                            <?php include("icons/vyfuk-dc.svg"); ?>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
<div class="loader-wrapper">
    <span class="loader d-flex"></span>
</div>
</body>
</html>
