<?php
/**
 * @author: Arjun Jain ( http://www.arjunjain.info ) 
 * @license: GNU GENERAL PUBLIC LICENSE Version 3
 *
 */

require_once '../../../../wp-load.php';
require_once 'ManageFeeds.php';
require_once 'Feeds.php';
$mf=new ManageFeeds();
$feedurl=$mf->GetFeedFromId($_POST['feedid']);
$feeds=new Feeds($feedurl->FeedURL);
echo $feeds->getFeeds();
?>