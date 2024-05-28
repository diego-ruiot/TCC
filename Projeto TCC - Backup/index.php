<?php

namespace Tcc;
include(__DIR__."/app/autoload.php");

$loader = new \Tcc\AutoLoad();
$loader->load(__DIR__);

require_once(__DIR__."/app/routes.php");

?>