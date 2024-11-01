<?php
/**
 * @author: Arjun Jain ( http://www.arjunjain.info ) 
 * @license: GNU GENERAL PUBLIC LICENSE Version 3
 *
 */

require_once '../../../../wp-load.php';
$title= $_POST['ptitle'];
$content=$_POST['pcontent'];
$Objpost=array(
	'post_title'=> $title,
	'post_content'=>$content,
);
try{
	wp_insert_post($Objpost,true);
}catch(Exception $e){
	echo "Error ".$e;
}
echo "Save as a post draft successfully";
?>