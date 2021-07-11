<?php

if (!defined('INIT_KERNEL')) exit('Access violation [ERR_3]');

# CONFIGURATION
define('SHORTENER_PARAM', 'id');
// Database
define('DB_HOST', 'localhost');
define('DB_USER', 'u666582916_letsboost');
define('DB_PASS', '#nY$r94Ky&aqft%GK8%x');
define('DB_DBNAME', 'u666582916_letsboost');
define('DB_CHARSET', 'utf8mb4');
// Scripts
define('SCR_JQUERY_URI', 'https://code.jquery.com/jquery-3.5.1.min.js');
define('SCR_JQUERY_SRIHASH', 'sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2'); // SRI hash for jQuery resource https://www.srihash.org/
define('SCR_MAIN_URI', '/assets/main.js');
// Styling
define('STYLE_THEME_COLOUR', '#000000');
define('STYLE_MAIN_URI', '/assets/main.css');
// Static configuration
define('SITE_TITLE', 'LetsBoost | Advance Social Media Marketing Tool');
define('SITE_LOGO', 'LetsBoost');

# MESSAGES
define('MSG_ERR_DB_CONN', 'Failed to connect to the database');

# MAIN CLASS
class core {
    # Basic functions

    /**
    * Process killer
    *
    * @param string $msg Message to safely halt with if a database connection was already made
    * 
    * @return void
    */
    public function exiti($msg = 'error') {
        global $db;
        $db->close();
        exit($msg);
    }

    /**
    * Real character counter.
    *
    * @param string $str String to count characters of.
    * 
    * @return integer
    */
    public function realCount($str = '') {
        $str = utf8_decode($str); // Decode it (will get more real visual looking count when comes to MB chars)
        $str = strlen($str); // Get string length

        return (int)$str;
    }

    /**
    * JSON response formatter
    * @param string $type Message type
    * @param string $msg Actual message
    * 
    * @return void
    */
    public function jsonRes($type = 'info', $msg = 'null'): void {
        header('Content-Type: application/json; charset=utf-8'); // Set appropriate mime type for content-type header

        $rmsg = [
            'type' => $type,
            'message' => $msg
        ]; // Store response

        $rmsg = json_encode($rmsg); // Encode to JSON

        self::exiti($rmsg); // Output response and halt script
    }
}

# SECURITY CLASS
class security extends core {
    # Configuration
    // Site information
    public const SITE_DOMAIN = 'letsboost.net'; // Generic cookie domain
    // Cookies
    private const COOKIE_TOKEN_NAME = 'token'; // Token cookie name
    private const COOKIE_TOKEN_EXP = 1210000; // Token cookie expiration - 2 weeks
    public const COOKIE_TOKEN_LENGTH = 50; // Length of token

    # Functions

    /**
    * URL format validator
    * @param string $url URL
    * 
    * @return boolean
    */
    public function urlValidate($url = '') {
        $validate = preg_match('%\b(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z0-9\x{00a1}-\x{ffff}][a-z0-9\x{00a1}-\x{ffff}_-]{0,62})?[a-z0-9\x{00a1}-\x{ffff}]\.)+(?:[a-z\x{00a1}-\x{ffff}]{2,}\.?))(?::\d{2,5})?(?:[/?#]\S*)?\b%iuS', $url);
        if ($validate) return true;
        return false;
    }

    /**
    * URL sanitiser
    * @param string $url URL
    * 
    * @return boolean
    */
    public function urlSanitise($url = '') {

        // Set index
        $index = 0;

        // Set character mapping
        $fromMap = [
            '%3B',
            '%2C',
            '%3F',
            '%3A',
            '%40',
            '%26',
            '%3D',
            '%2B',
            '%24',
            '%21',
            '%2A',
            '%28',
            '%29',
            '%23'
        ];

        $toMap = [
            ';',
            ',',
            '?',
            ':',
            '@',
            '&',
            '=',
            '+',
            '$',
            '!',
            '*',
            '(',
            ')',
            '#'
        ];

        // Separate data before path
        $splHost = explode('/', $url);
        $splHost = array_slice($splHost, 0, 3);
        $splHost = implode('/', $splHost);

        // Fetch path
        $splPath = explode('/', $url);
        $splPath = array_slice($splPath, 3, count($splPath));
        // Encode each segment of the path
        foreach ($splPath as $split) {
            $split = rawurlencode($split);
            $split = str_replace($fromMap, $toMap, $split);
            $splPath[$index] = $split;
            $index++;
        }
        // Build path
        $splPath = implode('/', $splPath);

        // Put data together
        $url = $splHost . '/' . $splPath;

        return $url;
    }

    /**
    * String generator
    *
    * @param integer $length Length of generated string
    * @param string $characters Characters to use in generated string
    * 
    * @return string
    */
    public function strGen($length = 6, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        $gene = '';
        for ($i = 0; $i < $length; $i++) $gene .= $characters[random_int(0, strlen($characters) - 1)];
        return $gene;
    }

    /**
    * Verify request source
    *
    * @return boolean
    */
    public function sourceVerifier() {
        if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/x-www-form-urlencoded; charset=UTF-8') {
            // Fetch-issued request detected, pass true
            return true;
        }
        return false;
    }

    # Fetch token
    public function fetchToken() {
        return $_COOKIE[self::COOKIE_TOKEN_NAME] ?? '';
    }

    /**
    * Token regeneration.
    *
    * @return void
    */
    public function tokenRegen(): void {
        $genToken = self::strGen(self::COOKIE_TOKEN_LENGTH);
        setcookie(self::COOKIE_TOKEN_NAME, $genToken, [
            'expires' => time() + self::COOKIE_TOKEN_EXP,
            'path' => '/',
            'domain' => self::SITE_DOMAIN,
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax']);
        // Override token value so user is not required to reload
        $_COOKIE[self::COOKIE_TOKEN_NAME] = $genToken;
    }

    /**
    * Token check.
    *
    * @param boolean $errMsg Show response.
    * @param boolean $enfGen Determine usage of JSON.
    * 
    * @return boolean
    */
    public function chkToken($errMsg = true, $enfGen = false) {
        $tokenVal = $_POST['token'] ?? '';
        // Check if token mismatches
        if ($tokenVal != self::fetchToken()) {
            if ($errMsg) {
                if (!$enfGen && self::sourceVerifier()) {
                    self::jsonRes('error', 'Token mismatch -- try refreshing');
                } else {
                    self::exiti('Token mismatch - try refreshing');
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}

# Create instances
$core = new core;
$security = new security;

# DATABASE CONNECTION
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DBNAME);
if ($db->connect_errno || !$db->set_charset(DB_CHARSET)) {
    // If connection fails or bad charset
    if ($security::sourceVerifier()) {
        $core::jsonRes('error', MSG_ERR_DB_CONN);
    } else {
        exit(MSG_ERR_DB_CONN);
    }
}
