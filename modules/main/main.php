<?php
if (!defined('INIT_KERNEL')) exit('Access violation [ERR_6]');
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title><?php echo SITE_TITLE ?></title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
        <meta name="description" content="Boost your social media influence and gain subscribers, followers, likes etc with LetsBoost.">
        <meta name="keywords" content="sub2unlock, subscribe2unlock,  sub4unlock, social media marketing, likes2unlock, follow2unlock, url shortener">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <meta name="theme-color" content="<?php echo STYLE_THEME_COLOUR ?>">
        <link href="<?php echo STYLE_MAIN_URI ?>" rel="stylesheet">
        <script src="<?php echo SCR_JQUERY_URI ?>" integrity="<?php echo SCR_JQUERY_SRIHASH ?>" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;700;800;900&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon-16x16.png">
        <link rel="manifest" href="/assets/site.webmanifest">
        <script async src="https://arc.io/widget.min.js#rXUC8drQ"></script>
    </head>

    <body>
        <div class="head">
            <section id="bar">
                <nav class="navbar navbar-light navbar-expand-lg">
                    <a href="/">
                        <img src="/assets/logo.svg" class="logo" alt="LetsBoost logo">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"  aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#top"><span style="color:#6c63ff;">HOME</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#features"><span class="bar-item">ABOUT</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#howitworks"><span class="bar-item">HOW IT WORKS</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0)"><span class="bar-item">ISTEAL.IT</span></a>
                        </li>
                    </ul>
                </nav>
            </section>
        </div>

        <div class="body">
            <section id="top">
                <div class="row frow2">
                    <div class="col-lg-5 left dyncenter">
                        <h1 class="heading"><span style="color:#6c63ff">Boost</span> Your Social Media Influence</h1>
                        <h5 class="headingdescription">LetsBoost is a friendly social media marketing platform that helps influencers to unlock their true potential.</h5>
                        <button type="button" class="btn-primary btn-outline-dark btn-sm" name="getstarted">Get started</button>
                        <br>
                        <br>
                    </div>
                    <div class="col-lg-7 priimg">
                        <img width="100%" src="/assets/rocket.svg" alt="rocket">
                    </div>
                </div>
            
            </section>
            
            <br>
            <br>
            <center><hr class="hr1 hr3"></center>
            <br>
            <br>

            <section id="shortener">
                
                <div class="row frow2">
                    <div class="col-lg-6">
                        <img width="100%" src="/assets/un.svg" alt="girl">
                        <br>
                        <br>
                        <br>
                        <br>
                    </div>
                    <div class="floating_card" id="shortenerform">
                        <form name="shortener" class="stepDat">
                            <div class="maintitle">Create your link</div>
                            <div class="msg hidden">
                                <div class="txt"></div>
                                <div class="clippy">Copy</div>
                            </div>
                            <div class="fieldtitle">Target URL</div>
                            <input type="url" name="url" placeholder="https://..." required>
                            <input type="hidden" name="token" value="<?php echo $security::fetchToken() ?>">
                            <hr>
                            <div class="fieldblock">
                                <div class="dynamic_field">
                                    <div class="fieldtitle">Step</div>
                                    <select name="optionType[]">
                                        <option value="">--- Select an option ---</option>
                                        <?php
                                            # Populate selection list with existing steps
                                            $query = $db->query("SELECT `id`, `step_name`, `internal_name` FROM `steps` ORDER BY `step_name` ASC");
                                            while ($row = $query->fetch_assoc()) {
                                                echo '<option value="' . $row['internal_name'] . '">' . $row['step_name'] . '</option>';
                                            }
                                            $query->free();
                                        ?>
                                    </select>
                                    <br>
                                    <br>
                                    <div class="advancedblock">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input advancedcheckbox" id="advancedtoggle0">
                                            <label class="custom-control-label advancedtoggle" for="advancedtoggle0">Advanced options...</label>
                                        </div>
                                        <div class="advancedopt">
                                            <div class="fieldtitle">Custom step name</div>
                                            <input type="text" name="optionName[]" maxlength="50" placeholder="Do this action...">
                                        </div>
                                    </div>
                                    <div class="fieldtitle">Step URL</div>
                                    <input type="url" name="optionValue[]" placeholder="https://..." required>
                                </div>
                            </div>
                            <hr>
                            <div class="step_manager">
                                <input type="button" class="remove" value="Remove step" disabled>
                                <input type="button" class="add" value="Add step">
                            </div>
                            <input type="submit" name="submit" class="main" value="Create link">
                        </form>
                    </div>
                </div>
            </section>

            <section id="howitworks">
                <center>
                    <h2>How it works</h2>
                    <br>
                    <br>
                    <hr class="hr1 hr3">
                    <br>
                    <br>
                    <h6>It's simple as 1, 2, 3</h6>
                </center>

                <div class="deck">
                    <div class="row" style="gap: 0px;">
                        <div class="col col-lg-4 col-md-6 col-sm-12">
                            <div class="card">
                                <h5>1.</h5>
                                <img src="/assets/clip.svg" alt="girl">
                                <div class="card-body">
                                <h5 class="card-title">Create Link</h5>
                                <p class="card-text">Create your link via easy to use form by clicking on "get started button above."</p>
                            </div>
                        </div>
                    </div>

                    <div class="col col-lg-4 col-md-6 col-sm-12">
                        <div class="card">
                            <h5>2.</h5>
                            <img src="/assets/social.svg" alt="girl">
                            <div class="card-body">
                                <h5 class="card-title">Share Link</h5>
                                <p class="card-text">Share your link with your audience, without having to worry about clogging up.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col col-lg-4 col-md-12 col-sm-12">
                    <div class="card">
                        <h5>3.</h5>
                        <img src="/assets/girl.svg" alt="girl">
                        <div class="card-body">
                            <h5 class="card-title">Grow Audience</h5>
                            <p class="card-text">Camly watch your audience and social media influence grow.</p>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </section>

            <section id="features">
                <div class="row frow left">
                    <div class="col-lg-7 col-sm-12">
                        <img width="80%" src="/assets/globe.svg" alt="globe">
                    </div>
                    <div class="col-lg-5 col-sm-12">
                        <h3>Works Everywhere</h3>
                        <br>
                        <br>
                        <hr class="hr2">
                        <br>
                        <p>Create responsive landing pages effortlessly, and don't worry it's going to work everywhere</p>
                    </div>
                </div>

                <div class="row frow right">
                    <div class="col-lg-5 col-sm-12">
                        <h3>Target Masses</h3>
                        <br>
                        <br>
                        <hr class="hr2">
                        <br>
                        <p>Target your customer to increase your reach and redirect them to relevant pages. Add a pixel to retarget them in your social media ad    campaign to    capture them.</p>
                    </div>
                    <div class="col-lg-7 col-sm-12">
                        <img width="80%" src="/assets/map.svg" alt="map">
                    </div>
                </div>

                <div class="row frow left">
                    <div class="col-lg-7 col-sm-12">
                        <img width="80%" src="/assets/lock.svg" alt="lock">
                    </div>
                    <div class="col-lg-5 col-sm-12">
                        <h3>Secure</h3>
                        <br>
                        <br>
                        <hr class="hr2">
                        <br>
                        <p>We offer the best encryption once you sign up we ourselves can't able to read your personal details and we offer 2FA for utmost security</p>
                    </div>
                </div>

                <div class="row frow right">
                    <div class="col-lg-5 col-sm-12">
                        <h3>Reach Your Audience</h3>
                        <br>
                        <br>
                        <hr class="hr2">
                        <br>
                        <p>Understanding your users and customers will help you increase your conversions. Our system allow you to track everything. Whether it is  amount of clicks, the country or the referrer, the data is there for you to analyze it.</p>
                    </div>
                    <div class="col-lg-7 col-sm-12">
                        <img width="80%" src="/assets/girls.svg" alt="girl">
                    </div>
                </div>
            </section>

            <!--<section id="bottom">-->
            <!--    <center>-->
            <!--        <div class="row">-->
            <!--            <div class="col-lg-4">-->
            <!--                <img class="i" src="/assets/guage.svg" alt="guage">-->
            <!--                <h5>Fast</h5>-->
            <!--                <p class="P">In under 60 seconds you can create your own landing page design specifically to increase conversion rate of your audience.</p>-->
            <!--            </div>-->
            <!--            <div class="col-lg-4">-->
            <!--                <img class="i" src="/assets/circle.svg" alt="gauge">-->
            <!--                <h5>Fast</h5>-->
            <!--                <p class="P">In under 60 seconds you can create your own landing page design specifically to increase conversion rate of your audience.</p>-->
            <!--            </div>-->
            <!--            <div class="col-lg-4">-->
            <!--                <img class="i" src="/assets/smiling.svg" alt="gauge">-->
            <!--                <h5>Fast</h5>-->
            <!--                <p class="P">In under 60 seconds you can create your own landing page design specifically to increase conversion rate of your audience.</p>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </center>-->
            <!--</section>-->
        </div>

        <footer class="white-section" id="footer">
            <center>
                <p style="color:#fff">Design by <a href="https://www.behance.net/davitantadze"><span class="david">David Antadze</span></a></p>
                <p style="color:#fff">© + Brittney🍓 + Lyca😞 at <a class="f" href="javascript:void(0)"> isteal.it</a></p>
            </center>
        </footer>
        <script src="<?php echo SCR_MAIN_URI ?>"></script>
    </body>
</html>
