<?php

define('INIT_KERNEL', true);
if (@!require('../include/kernel.php')) exit('Error communicating with the backend [ERR_7]');

# Request source check
if (!$security::sourceVerifier()) {
    $core::exiti('Request source mismatch [ERR_8]');
}

# CSRF check
$security::chkToken();

# User input data
$usrURL = $_POST['url'] ?? '';
$usrStepType = $_POST['optionType'] ?? [];
$usrStepName = $_POST['optionName'] ?? [];
$usrStepValue = $_POST['optionValue'] ?? [];

# Validate main URL
if (!$security::urlValidate($usrURL)) {
    $core::jsonRes('error', 'Valid main URL is required');
}

# Init
$index = 0;
$trustedSteps = [];
$trustedSelection = [];

# Build list of trusted steps
$query = $db->query("SELECT `id`, `step_name`, `internal_name` FROM `steps` ORDER BY `step_name` ASC");
while($row = $query->fetch_assoc()) {
    // Only store first occurrence of internal name
    if (!array_key_exists($row['internal_name'], $trustedSteps)) {
        $trustedSteps[$row['internal_name']] = $row['id'];
    }
}
$query->free();

# Compile list of input data
foreach ($usrStepType as $type) {
    // Check if both input methods for each step is present
    if (isset($usrStepType[$index]) && isset($usrStepName[$index]) && isset($usrStepValue[$index])) {

        // Determine what step ID to use
        if (trim($usrStepName[$index]) !== '') {
            $stepId = 0;
        } elseif ($usrStepType[$index] !== '' && array_key_exists($usrStepType[$index], $trustedSteps)) {
            $stepId = $usrStepType[$index];
        }

        // Essentially skip step if no step selected or named
        if (!isset($stepId)) {
            $core::jsonRes('error', 'Ensure all steps have a type or custom name set');
        }

        if ($core::realCount($usrStepName[$index]) > 50) {
            $core::jsonRes('error', '50 characters maximum allowed for custom step names');
        }

        // Check if provided URL is in a valid format
        if ($security::urlValidate($usrStepValue[$index])) {
            // Success -- store valid data into array
            $trustedSelection[] = ['type' => $stepId, 'custom' => $usrStepName[$index], 'url' => $usrStepValue[$index]];
        } else {
            $core::jsonRes('error', 'One or more steps has an invalid URL');
        }

    } else {
        $core::jsonRes('error', 'Input out of sync');
    }
    $index++;
}

# Check if at least one step is selected
if (count($trustedSelection) === 0) {
    $core::jsonRes('error', 'At least one step is required');
} else if (count($trustedSelection) > 5) {
    // 5 overall (valid) steps allowed only
    $core::jsonRes('error', 'Maximum amount of steps exceeded');
}

# Create generated ID
$shortID = $security::strGen(6);

# Sanitise URL
$usrURL = $db->real_escape_string($usrURL);

# Create URL
$query = $db->query("INSERT INTO `urls` (`alias`, `url`, `time`) VALUES ('$shortID', '$usrURL', NOW())");
if (!$query) {
    $core::jsonRes('error', 'Failed to create URL');
}

# Assign steps
foreach ($trustedSelection as $data) {

    // Set up mapping
    $selType = $data['type'];
    $selCustom = $data['custom'];
    $selURL = $data['url'];

    // Detect step mode
    if ($selType === 0) {
        $customType = true;
    } else {
        $customType = false;
    }

    // Obtain step ID
    if ($customType) {
        $sid = $selType;
    } else {
        $sid = $trustedSteps[$selType];
    }

    // Sanitise custom type
    $custom = $db->real_escape_string($selCustom);

    // Sanitise URL
    $url = $db->real_escape_string($selURL);

    // Create step
    $query = $db->query("INSERT INTO `steps_user` (`alias`, `sid`, `custom`, `url`, `time`) VALUES ('$shortID', '$sid', '$custom', '$url', NOW())");
    if (!$query) {
        $core::jsonRes('error', 'Failed to create step');
    }
}

# Construct URL
$finalURL = '';
// Check if HTTPS present
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $finalURL .= 'https://';
} else {
    $finalURL .= 'http://';
}
// Add domain
$finalURL .= $security::SITE_DOMAIN;
// Add path
$finalURL .= '/';
// Add unique ID
$finalURL .= $shortID;

# Return result
$core::jsonRes('success', $finalURL);

$db->close();
