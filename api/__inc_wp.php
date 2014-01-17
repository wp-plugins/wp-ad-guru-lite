<?php
/*
 * This file tries to include the Wordpress header by searching UP the directory structure
 http://onetarek.com/
 */
$searchFile = 'wp-config.php';
for($i = 0; $i < 10; $i++)
{
    if( file_exists($searchFile) )
    {
        require_once($searchFile);
        break;    
    }
    $searchFile = "../" . $searchFile;
}

//Make sure we got it
if( !defined('WPINC') )
{
   die(  "Failed to locate wp-blog-header.php.");
}


?>