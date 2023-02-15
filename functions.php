<?php
defined('ABSPATH') or die();
const CHILD_ADMIN_MENU_AKTIV = false;
require_once 'inc/class_register_child_hooks.php';


$childHooks = Register_Child_Hooks::instance();
$childHooks->run();
