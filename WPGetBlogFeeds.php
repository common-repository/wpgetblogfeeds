<?php
/*
Plugin Name: WPGetBlogFeeds
Plugin URI: http://wordpress.org/extend/plugins/wpgetblogfeeds/
Description: Provide functionality to get the feeds from blogs and save as post draft .
Author: Arjun Jain
Author URI: http://www.arjunjain.info
Version: 2.4
*/	

global $wpgetblogfeeds_db_version;
$wpgetblogfeeds_db_version="1.0";

add_action('admin_menu', 'WPGetBlogFeeds');
function WPGetBlogFeeds() {
	add_menu_page('WPGetBlogFeeds - Get the feeds from blogs','GetBlogFeeds', 'administrator', 'wp-getblog-feeds', 'AdminWPGetBlogFeeds',plugins_url('images/16_rss.png',__FILE__));
	add_submenu_page( 'wp-getblog-feeds','Add New Feed','Add New Feed URL', 'administrator', 'wp-getblog-feeds-newfeed', 'AdminWPGetBlogFeedsNewFeed' );
	add_submenu_page( 'wp-getblog-feeds','Fetch Feeds','Fetch Feeds', 'administrator', 'wpgetblogfeeds-fetchfeeds', 'AdminWPGetBlogFeedsFetchFeeds' );
}

function AdminWPGetBlogFeedsFetchFeeds(){
	require_once 'includes/ManageFeeds.php';
	$mf=new ManageFeeds();
	echo $mf->FetchFeedsOption();
}

function AdminWPGetBlogFeeds(){
	require_once 'includes/ManageFeeds.php';
	$mf=new ManageFeeds();
	if(isset($_GET['action']) && isset($_GET['edit']) && isset($_GET['fid'])){
		$mf->DeleteURL($_GET['fid']);
	}
	echo $mf->DisplayAllUrl();
}

function AdminWPGetBlogFeedsNewFeed(){
	require_once 'includes/ManageFeeds.php';
	$mf=new ManageFeeds();
	$postdata=array();
	if(isset($_GET['edit'])&&isset($_GET['fid'])){
		$feeddata=$mf->GetFeedFromId($_GET['fid']);
		$postdata=array(
					"feedid"=>$feeddata->id,
					"feedurl"=>$feeddata->FeedURL,
					"oldfeedurl"=>$feeddata->FeedURL
				);
	}
	if(isset($_POST['isSubmit'])){
		$feedurl=strtolower(trim($_POST['feedurl']));
		$postdata=array(
				"feedid"=>$_POST['feedid'],
				"feedurl"=>trim($_POST['feedurl']),
				"oldfeedurl"=>$_POST['oldfeedurl']
				);
		$msg=$mf->CheckError($postdata);
		if($msg=="true"){
			if($postdata != "")
				$success='<div class="updated"><p>Url Updated successfully</p></div>';
			else
				$success='<div class="updated"><p>Url Added successfully</p></div>';
			$mf->InsertNewURL($postdata);
			echo $mf->DisplayFeedForm($success);	
		}
		else{
			$error='<div class="error"><p>'.$msg.'</p></div>';
			echo $mf->DisplayFeedForm($error,$postdata);
		}
	}
	else{
		echo $mf->DisplayFeedForm('',$postdata);
	}
}

function AdminWPGetBlogFeeds1(){	
	require_once 'includes/ManageFeeds.php';
	$mf=new ManageFeeds();
	if(isset($_POST['selFeedURL']))
		echo $mf->DisplayOption($_POST['selFeedURL']);
	else 
		echo $mf->DisplayOption();
	$results=WPGetResultsArray("SELECT * FROM WPGetBlogFeeds LIMIT 1");
	if(count($results)==0){
		echo '<span style="color:red; font-size:14px;">Please Go to&nbsp;<b><a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=wp-getblog-feeds-option">Settings</a></b>&nbsp;to add new URL </span>';
	}
	else	
		echo '<div id="formdata"> </div>';
}

register_activation_hook( __FILE__, "WPGetBlogFeeds_activate" );
function WPGetBlogFeeds_activate(){
	require_once 'includes/ManageFeeds.php';
	$mf=new ManageFeeds();
	$mf->CreateTable();
	add_option("wpgetblogfeeds_db_version", $wpgetblogfeeds_db_version);
}
?>