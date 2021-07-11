<?php
if (!defined('INIT_KERNEL')) exit('Access violation [ERR_12]');

# Free query -- not going to be using URL data here
$query->free();

# HTTP 404
header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo SITE_TITLE ?></title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"  integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <meta name="theme-color" content="<?php echo STYLE_THEME_COLOUR ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon-16x16.png">
        <link href="<?php echo STYLE_MAIN_URI ?>" rel="stylesheet">
        <script src="<?php echo SCR_JQUERY_URI ?>" integrity="<?php echo SCR_JQUERY_SRIHASH ?>" crossorigin="anonymous">
        </script>
        <script src="<?php echo SCR_MAIN_URI ?>"></script>
        <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;700;800;900&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
        </script>
        <script async src="https://arc.io/widget.min.js#rXUC8drQ"></script>
    </head>

    <body>
        <div class="head">
            <section id="bar">
                <nav class="navbar navbar-light navbar-expand-lg">
                    <a href="/">
                        <img src="/assets/logo.svg" class="logo" alt="LetsBoost logo">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="/#top"><span style="color:#6c63ff;">HOME</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/#features"><span class="bar-item">ABOUT</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/#howitworks"><span class="bar-item">HOW IT WORKS</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)"><span class="bar-item">ISTEAL.IT</span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </section>
            <br>
            <br>
        </div>
        <div class="body">
            <section id="steps">
                <div class="floating_card">
                <div class="maintitle">404 Not found</div>
                    <br>
                    URL does not exist
                </div>
            </section>
            <div class="contentblk">
                <div class="referral">
                    <a href="<?php echo $finalReferrerURL ?>" target="_blank" rel="noopener">
                        <img class="referralImage" src="<?php echo $finalReferrerIMG ?>">
                    </a>
                </div>
                <div class="contentinfo">
                    <div class="contenttitle">What is LetsBoost?</div>
                    <div class="contentbody">
                        LetsBoost is a free URL shortener. Using this, you can easily make your own subscribe2unlock, sub2unlock or follow2unlock links.
                        This tool is specifically designed for creators and influencers so they can gain subscribers, followers, likes and etc. using LetsBoost.net
                    </div>
                </div>
            </div>
        </div>
        <footer class="white-section" id="footer">
            <center>
                <p style="color:#fff">Design by <a href="https://www.behance.net/davitantadze"><span class="david">David Antadze</span></a></p>
                <p style="color:#fff">© + Brittney🍓 at <a class="f" href="javascript:void(0)"> isteal.it</a></p>
            </center>
        </footer>
    </body>
</html>
