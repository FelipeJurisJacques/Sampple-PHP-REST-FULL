<?php
require_once '../Main.php';

$main = new Main(__DIR__);
$rest = new Rest(__DIR__);

$rest->filter(
    'NotasResource',
    'notas',
    'notas/',
    'notas/<i>',
    'notas/<i>/'
);

$rest();