<?php

/*
 * Create symlink for shared hosting
 */

$project_dir = $_GET['project'] ?? 'project';
$target = realpath("../$project_dir/storage/app/public");
$link = 'storage';
symlink($target, $link);
die($link.' ==> '.$target);
