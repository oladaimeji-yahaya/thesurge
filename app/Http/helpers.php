<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';

//Load helper files
$files = scandir(__DIR__ . DS . 'Helpers');
foreach ($files as $file) {
    if ($file === '.' || $file === '..' || !ends_with($file, '.php')) {
        continue;
    }
    require_once __DIR__ . DS . 'Helpers' . DS . $file;
}

/**
 * Don't declare functions here, keep it clean, every helper goes into Helpers
 * Don't know where to classify it? place it in Helpers/sundry.php
 * 
 */