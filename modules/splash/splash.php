<?php
if (!defined('INIT_KERNEL')) exit('Access violation [ERR_9]');

# Initiate storage
$stepStore = [];
$index = 0;

# Referrer data
$referrerDat = [
    0 => [
        'img' => '',
        'url' => ''
    ],
    1 => [
        'img' => '',
        'url' => ''
    ]
];

# Loop out the steps
$queryUsr = $db->query("SELECT `id`, `alias`, `sid`, `custom`, `url` FROM `steps_user` WHERE `alias`='$urlAlias' ORDER BY `id` ASC");
while ($rowUsr = $queryUsr->fetch_assoc()) {
    // Fetch user step data
    $stepURL = $rowUsr['url'];
    $stepID = $rowUsr['sid'];

    if ((int)$stepID === 0) {
        $optName = $rowUsr['custom'];
    } else {
        // Fetch base step data
        $queryOpt = $db->query("SELECT `id`, `step_name` FROM `steps` WHERE `id`='$stepID' ORDER BY `id` ASC");
        $rowOpt = $queryOpt->fetch_assoc();
        $optName = $rowOpt['step_name'] ?? 'UNKNOWN';
    }

    // Sanitise
    $optName = htmlentities($optName, ENT_QUOTES);

    // Store URL for each step for JavaScript code later on
    $stepStore[$index]['stepname'] = 'Step ' . ($index + 1);
    $stepStore[$index]['optname'] = $optName;
    $stepStore[$index]['url'] = $security::urlSanitise($stepURL);
    $index++;

    if (isset($queryOpt)) {
        $queryOpt->free();
    }
}
$queryUsr->free();

$stepStore[$index]['stepname'] = 'Steps completed';
$stepStore[$index]['optname'] = 'Continue';
$stepStore[$index]['url'] = $security::urlSanitise($urlTarget);

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
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
        </script>
        <script async src="https://arc.io/widget.min.js#rXUC8drQ"></script>
    </head>

    <body class="mainbody">
    
        <section id="steps">
            <div class="floating_card flex">
                <div class="referral">
                  <iframe data-aa="1530532" src="//acceptable.a-ads.com/1530532" scrolling="no" style="border:0px; padding:0; width:100%; height:100%; overflow:hidden" allowtransparency="true"></iframe>
                </div>
            </div>
            <div class="floating_card flex">
                <div class="maintitle">To unlock content, press the button below to view the steps.</div>
                <div class="stepblock">
                    <div class="fieldtitle">Begin with the button below</div>
                    <button class="stepButton sbs">Unlock...</button>
                </div>
            </div>
            <div class="floating_card flex">
                <div class="referral">
                  <iframe data-aa="1530532" src="//acceptable.a-ads.com/1530532" scrolling="no" style="border:0px; padding:0; width:100%; height:100%; overflow:hidden" allowtransparency="true"></iframe>
                </div> 
                </div>
            </div>
        </section>
        
        <script>
            $(document).ready(function(){
                stepDat = '<?php echo json_encode($stepStore) ?>';

                // Init values
                isInit = true;
                index = 0;
                lock = false;

                // Set button data
                function stepSetData(title, info) {
                    $('.fieldtitle').html(title);
                    $('.stepButton').html(info);
                }
                

                $(document).on('click', '.stepButton', function(event) {
                    event.preventDefault();

                    // Check if invalid
                    if (!checkJSON(stepDat, true)) {
                        stepSetData('Error', 'Invalid step data');
                        return;
                    }

                    // Parse JSON
                    jsonDat = window.JSON.parse(stepDat);

                    // Check if initial press
                    if (isInit) {
                        stepSetData(jsonDat[index]['stepname'], jsonDat[index]['optname']);
                        isInit = false;
                        lock = false;
                        return;
                    }

                    // Launch URL in new tab
                    window.open(jsonDat[index]['url'], '_blank');

                    // Lock handling
                    if (lock) {
                        return;
                    }
                    lock = true;

                    // Check if end or not
                    if (index < (jsonDat.length - 1)) {
                        stepSetData(jsonDat[index]['stepname'], 'Verifying <span class=\'ellipsis-anim\'><span>.</span><span>.</span><span>.</span></span>');

                        setTimeout(function () {
                            index++;
                            stepSetData(jsonDat[index]['stepname'], jsonDat[index]['optname']);
                            lock = false;
                        }, 10000);
                    } else {
                        lock = false;
                    }
                });
            });
        </script>
    </body>
</html>