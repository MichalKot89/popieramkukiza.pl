<?php

// popieram
require_once dirname(__FILE__).'/../config/config.php';
require_once dirname(__FILE__).'/../libraries/database.php';

error_reporting(-1);
ini_set('display_errors', 1);

if (!isset($_GET['action']) || !in_array($_GET['action'], array('step1', 'step2', 'step3'))) {
    $action = 'step1';
} else {
    $action = $_GET['action'];
}

$database = new Database();

$counts = $database->getCounts();

if ($action === 'step1') {
    require_once dirname(__FILE__).'/../templates/header.php';
    require_once dirname(__FILE__).'/../actions/'.$action.'.php';
    require_once dirname(__FILE__).'/../templates/footer.php';
} else {
    require_once dirname(__FILE__).'/../actions/'.$action.'.php';
}