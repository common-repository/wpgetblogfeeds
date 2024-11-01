<?php
/**
 * @author: Arjun Jain ( http://www.arjunjain.info ) 
 * @license: GNU GENERAL PUBLIC LICENSE Version 3
 *
 */

class ManageFeeds{
	
	private $_feedtable='';
	private $_dbobject='';
	
	function __construct(){
		global $wpdb;
		$this->_feedtable='WPGetBlogFeeds';
		$this->_dbobject=$wpdb;
	}
	
	public function IsFeedurlExits(){
		$result=$this->_dbobject->get_var("SELECT id FROM $this->_feedtable LIMIT 0,1");
		if($result != '')
			return true;
		else
			return false;
	}
	public function GetFeedFromId($feedid){
		return $this->_dbobject->get_row($this->_dbobject->prepare("SELECT * FROM $this->_feedtable WHERE id=".$feedid));
	}
	
	public function DisplayAllUrl(){
		$query="SELECT * FROM $this->_feedtable";
		$results=$this->_dbobject->get_results($query);
		$html='';
		if(sizeof($results)==0){
			$html='<div class="wrap">
					  <div style="width:32px; float:left;height:32px; margin:7px 8px 0 0;"><img src="'.plugins_url('images/32_rss.png',dirname(__FILE__)).'"></div>	  
					  <h2>WPGetBlogFeeds<a class="add-new-h2" href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=wp-getblog-feeds-newfeed">Add New Feed URL</a></h2>
				  	  <div class="updated"><p>Please add new feed url</p></div>
					</div>';
		}
		else{
			$html='<div class="wrap">
				  <div style="width:32px; float:left;height:32px; margin:7px 8px 0 0;"><img src="'.plugins_url('images/32_rss.png',dirname(__FILE__)).'"></div>	  
					<h2>WPGetBlogFeeds<a class="add-new-h2" href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=wp-getblog-feeds-newfeed">Add New Feed URL</a></h2>
					<table class="wp-list-table widefat fixed posts" cellspacing="0" style="margin-top:10px;">
						<thead>
							<tr>
								<th class="manage-column column-author" scope="col">S.No</th>
								<th class="manage-column column-title" scope="col">Feed Url</th>
							</tr>
						</thead>
						<tbody>';
			$i=1;
			foreach ($results as $data){
				$html .='<tr><td class="author column-author">'.$i++.'</td>';
				$html .='<td class="post-title column-title"><a href="'.$_SERVER['PHP_SELF'].'?page=wp-getblog-feeds-newfeed">'.$data->FeedURL.'</a>
							<div class="row-actions">
								<span class="edit"><a href="'.$_SERVER['PHP_SELF'].'?page=wp-getblog-feeds-newfeed&edit=1&fid='.$data->id.'">Edit</a> | </span>
								<span class="trash"><a href="'.$_SERVER['PHP_SELF'].'?page=wp-getblog-feeds&edit=1&action=delete&fid='.$data->id.'">Delete</a></span>
							</div>
						</td></tr>';
			}					
			$html .='	</tbody>
						<tfoot>
							<tr>
								<th colspan="2" class="post-title column-title" style="text-align:right !important;" scope="col">Find Bug or suggest new feature please <a target="_blank" href="http://www.arjunjain.info/contact">click here</a></th>
							</tr>
						</tfoot>
					</table>
					</div>
			      </div>';
		}
		return $html;
	}
	
	public function DisplayFeedForm($msg="",$postdata=array()){
		$buttontext="Add Url";
		if(@$postdata['feedid'] !='')
			$buttontext="Update Url";
		
		$html = '<div class="wrap">
					<div style="width:32px; float:left;height:32px; margin:7px 8px 0 0;"><img src="'.plugins_url('images/32_rss.png',dirname(__FILE__)).'"></div>	  
						<h2>WPGetBlogFeeds</h2>'.$msg.'
						<form method="POST" name="feedform" action="">
							<input type="hidden" name="feedid" value="'.@$postdata['feedid'].'" />
							<input type="hidden" name="oldfeedurl" value="'.@$postdata['oldfeedurl'].'" />
							<table class="form-table">
							<tbody>
								<tr valign="top">
									<th scope="row" style="width:20%"><label for="feedurl">Website/Feed URL*</label></th>
									<th><input id="feedurl" class="regular-text" type="text" value="'.@$postdata['feedurl'].'" name="feedurl"></th>
								</tr>
							</tbody>
							</table>
						<p class="submit"><input id="isSubmit" class="button-primary" type="submit" value="'.$buttontext.'" name="isSubmit"></p>
						</form>
			     </div>';
		return $html;
	}
	
	public function FetchFeedsOption($feedurl=''){
		$js='<script type="text/javascript" src="'.plugins_url("js/form.js",dirname(__FILE__)).'"></script>';
		$query="SELECT * FROM $this->_feedtable";
		$results=$this->_dbobject->get_results($query);
		$html='';
		if(sizeof($results)==0){
			$html='<div class="wrap">
						<div style="width:32px; float:left;height:32px; margin:7px 8px 0 0;"><img src="'.plugins_url('images/32_rss.png',dirname(__FILE__)).'"></div>
						<h2>WPGetBlogFeeds<a class="add-new-h2" href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=wp-getblog-feeds-newfeed">Add New Feed URL</a></h2>
						<div class="updated"><p>Please add new feed url</p></div>
					</div>';
		}
		else{
			$html='<div class="wrap">
						<div style="width:32px; float:left;height:32px; margin:7px 8px 0 0;"><img src="'.plugins_url('images/32_rss.png',dirname(__FILE__)).'"></div>	  
						<h2>WPGetBlogFeeds</h2>
						<div style="margin-top:10px;" class="actions">
							<form method="POST" name="fetchfeedform" action="" onsubmit="return getfeeds(this,\''.plugins_url('includes/getFeeds.php',dirname(__FILE__)).'\',\''.plugins_url('images/loading.gif',dirname(__FILE__)).'\');">
								Select Url: <select name="feedid">'
								.$this->WPGetOptionsString("SELECT * FROM $this->_feedtable","id", "FeedURL",$feedurl).	
								'</select>
								<input type="submit" name="getfeed" value="Fetch last 3 days Feeds" class="button-primary" />
							</form>
						</div>
						<div id="formdata"></div>
				  </div>';
		}
		return $js.$html;
	}
	
	public function InsertNewURL($postdata){
		if($postdata['feedid']=="")
			$query='INSERT INTO '.$this->_feedtable.'(FeedURL) VALUES("'.$postdata['feedurl'].'")';
		else
			$query="UPDATE $this->_feedtable SET FeedURL='".$postdata['feedurl']."' WHERE id=".$postdata['feedid'];
		$this->_dbobject->query($this->_dbobject->prepare($query));
	}
	
	public function DeleteURL($id){
		$query="DELETE FROM WPGetBlogFeeds WHERE id=".$id;
		$this->_dbobject->query($this->_dbobject->prepare($query));
	}
	
	public function CheckError($postdata){
		if($postdata['feedurl']=="")
			return "Please enter feed URL";
		if($postdata['feedurl']==$postdata['oldfeedurl'])
			return "true";
		else{
			$query="SELECT id FROM WPGetBlogFeeds WHERE FeedURL = '".$postdata['feedurl']."'";
			$id=$this->_dbobject->get_var($query);
			if($id != "")
				return "Feed URL already exists";
			else{	
				require_once 'simplepie.inc';
				$feed=new SimplePie();
				$feed->set_feed_url($postdata['feedurl']);
				$feed->enable_cache(false);
				$success=$feed->init();	
				if(!$success)
					return "Incorrect URL or URL could not be parsed as XML : Please try again";
				return "true";
			}
		}
	}
	
 	public function WPGetOptionsString($query, $keyCol, $valueCol, $selectedKey){
		$results = $this->_dbobject->get_results($query,ARRAY_A);
		$optionsString = "";
		$isArray = is_array($selectedKey);
		foreach($results as $result)
		{
			if($isArray)
				$selected = (array_search($result[$keyCol], $selectedKey) !== false)? " selected" : "";
			else
				$selected = ($result[$keyCol]==$selectedKey)? " selected" : "";
			$optionsString .= "<option value='$result[$keyCol]' title='$result[$valueCol]'" . $selected . ">$result[$valueCol]</option>";
		}
		return $optionsString;
	} 
	
 	public function CreateTable(){
		$sql ="";
		if($this->_dbobject->get_var("SHOW TABLES LIKE '{$this->_feedtable}'") != $this->_feedtable){
			$sql .= "CREATE TABLE $this->_feedtable (
			id INT NOT NULL AUTO_INCREMENT,
			FeedURL VARCHAR(500) NOT NULL,
			PRIMARY KEY (id));";
		}
		if ($sql != ""){
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}	
	}
}
?>