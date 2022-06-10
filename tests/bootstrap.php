<?php

use Dotenv\Dotenv;

require_once(dirname(__FILE__) . '/../vendor/autoload.php');

$dotenv = Dotenv::createImmutable(dirname(__FILE__) . '/../');
$dotenv->safeLoad();

// clear snapshots if tests are running in recording mode
if (env('RECORD_SNAPSHOTS', false)) {
    $recordingFilePath = 'tests/__snapshots__/snapshot';
    if (file_exists($recordingFilePath)) {
        file_put_contents($recordingFilePath, '');
    }
}
