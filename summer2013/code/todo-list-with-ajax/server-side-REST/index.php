<?php
require_once '../restler/vendor/restler.php';
use Luracast\Restler\Restler;
$r = new Restler();
$r->addAPIClass('Todo');
$r->handle();
?>