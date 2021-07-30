<?php
/** Sets clock out */

require_once('hibob_API.php');

$api = new HiBob_API();
$api->setClock('out');
