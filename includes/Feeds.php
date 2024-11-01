<?php
/**
 * @author: Arjun Jain ( http://www.arjunjain.info ) 
 * @license: GNU GENERAL PUBLIC LICENSE Version 3
 *
 */

require_once 'simplepie.inc';
class Feeds{
	private $FeedURL;
	
	function __construct($feedurl){
		$this->FeedURL=$feedurl;
	}
	public function getFeeds(){		
		$lastdate=date('j M Y');
		$feed=new SimplePie();
		$feed->set_feed_url($this->FeedURL);
		$feed->enable_cache(false);
		$success=$feed->init();
		if($success){
			$data ='<table class="wp-list-table widefat fixed posts"  cellspacing="0" style="margin-top:10px;">
						<thead>
							<tr>
								<th class="manage-column column-title" scope="col">Title</th>
								<th class="manage-column column-title" scope="col">Content</th>
							</tr>
						</thead>
					</table>';
			$i=0;
			foreach ($feed->get_items() as $entry){
				$postdate = $entry->get_date('j M Y');
				$diff = abs(strtotime($postdate) - strtotime($lastdate));
				$years = floor($diff / (365*60*60*24));
				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
				$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
				if($days <= 3 && $years==0 && $months==0 ){
					$data .='<form name="feedform_'.$i.'" id="feedform_'.$i.'" method="post" action="" onsubmit="return insertfeed(this,\''.plugins_url('includes/InsertFeed.php',dirname(__FILE__)).'\');"  >'
						   .'<table class="wp-list-table widefat fixed posts" cellspacing="0">
						   		<tbody id="the-list">
						   			<tr valign="top">
						   				<td class="post-title column-title"><input type="text" name="txtTitle_'.$i.'" id="txtTitle_'.$i.'" value="'.$entry->get_title().'" />
					   	   					<br /><a target="_blank" href="'.$entry->get_permalink().'">'.$entry->get_permalink().'</a>
					   	   				</td>
						   				<td class="post-title column-title">
						   					<textarea style="width:100%; height:200px;" id="txtContent_'.$i.'" >'.$entry->get_content().'</textarea>
										</td>
									</tr>
									<tr valign="top">
										<td class="post-title column-title">
											Publish date:&nbsp; <input  type="text" name="txtPubdate_'.$i.'" id="txtPubdate_'.$i.'" value="'.$entry->get_date('j M Y').'" />
										</td>
					  	    			<td class="post-title column-title">
											<input type="submit" name="save_'.$i.'" value="Save as Post draft" class="button-primary" />
										</td>
									</tr>
								</tbody>
							</table>
							</form>';	
					$i++;
				}
			}
			if($i==0)
				$data='<div class="error"><p>No recent Post found</p></div>';
			return $data;
		}
		else
			return '<div class="error"><p>Network Error: Can not get the feeds</p></div>';
	}
}
?>