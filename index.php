<?php

# KERNEL
define('INIT_KERNEL', true);
if (@!require('include/kernel.php')) exit('Error communicating with the backend [ERR_1]');

# TOKEN CHECK
if ($core->realCount($security->fetchToken()) !== $security::COOKIE_TOKEN_LENGTH) {
    $security->tokenRegen(); // Re-create token if not using the correct length or doesn't exist
}

# MODULE ROUTER
if (@!require('include/router.php')) $core::exiti('Error communicating with the backend [ERR_2]');
$db->close();
