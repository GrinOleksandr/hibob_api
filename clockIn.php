<?php
/** Sets clock in */

require_once('hibob_API.php');

$api = new HiBob_API();
$api->setClock('in');
