<?php

if (!defined('INIT_KERNEL')) exit('Access violation [ERR_4]');

# Initiate GET parameter
$data = $_GET[SHORTENER_PARAM] ?? '';

# Check if ID is specified
if (trim($_GET[SHORTENER_PARAM]) !== '') {
    // Sanitise data
    $data = $db->real_escape_string($data);
    $query = $db->query("SELECT `id`, `alias`, `url` FROM `urls` WHERE `alias`='$data'");

    # Check if ID exists
    if ($query->num_rows === 1) {
        // Exists
        $queryDat = $query->fetch_assoc();
        $urlTarget = $queryDat['url'];
        $urlAlias = $queryDat['alias'];
        if (@!include('modules/splash/splash.php')) $core::exiti('Error accessing module [ERR_10]');
    } else {
        // Does not exist
        if (@!include('modules/error/error.php')) $core::exiti('Error accessing module [ERR_11]');
    }
} else {
    // Default page
    if (@!include('modules/main/main.php')) $core::exiti('Error accessing module [ERR_5]');
}
