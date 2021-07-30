<?php
/** Sets clock out and submits time entry for today */

require_once('hibob_API.php');

$api = new HiBob_API();

$api->setClock('out');

$today = date('Y-m-d');

$api -> setTimeEntries($today);
