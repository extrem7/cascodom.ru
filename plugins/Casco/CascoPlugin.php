<?php
/**
 * Plugin Name: Casco
 * Version: 1.0
 * Author: Raxkor
 * Author uri: https://raxkor.com
 */
require_once "includes/functions.php";
require_once "Casco.php";

function ThemeActivation()
{
    global $Casco;
    $Casco = new Casco();
}

ThemeActivation();