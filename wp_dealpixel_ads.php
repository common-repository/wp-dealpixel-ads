<?php
/*
Plugin Name: WP DealPixel Ads
Plugin URI: http://dealpixel.com/wp-dealpixel-ads-wordpress-plugin/
Description: Integrate DealPixel ads on to your sidebar and make money with the DealPixel affiliate network.
Version: 1.0
Author: jw
Author URI: http://dealpixel.com
License: GPL
*/

class WPDealPixelAdsWidget extends WP_Widget
{
 /**
  * Declares the WPDealPixelAdsWidget class.
  *
  */
    function WPDealPixelAdsWidget(){
    $widget_ops = array('classname' => 'widget_wp_dealpixel_deals', 'description' => __( "List DealPixel deals in your sidebar and make money with the DealPixel affiliate network.") );
    $control_ops = array('width' => 550, 'height' => 650);
    $this->WP_Widget('wpdealpixelads', __('WP DealPixel Deals'), $widget_ops, $control_ops);
    }

  /**
    * Displays the Widget
    *
    */
    function widget($args, $instance){
      extract($args);
      $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
      $lineOne = empty($instance['lineOne']) ? 'Hello' : $instance['lineOne'];
      $lineTwo = empty($instance['lineTwo']) ? 'World' : $instance['lineTwo'];
	  $campid = empty($instance['campid']) ? '240882' : $instance['campid'];
	  $totaldeals = empty($instance['totaldeals']) ? '' : $instance['totaldeals'];
	  $catagory = empty($instance['catagory']) ? 'all' : $instance['catagory'];
	  $logowidth = empty($instance['logowidth']) ? '200' : $instance['logowidth'];
	  $dis_icon = empty($instance['dis_icon']) ? 'yes' : $instance['dis_icon'];
	  $contribute = empty($instance['contribute']) ? '' : $instance['contribute'];
	  $linkback = empty($instance['linkback']) ? 'no' : $instance['linkback'];
	  $textfont = empty($instance['textfont']) ? 'Verdana' : $instance['textfont'];
	  $textsize = empty($instance['textsize']) ? '10' : $instance['textsize'];

		if($textfont=="Verdana"){
		$userfont = "font-family: Verdana;";
		}

		# Before the widget
      echo $before_widget;

      # The title
      if ( $title )
      echo $before_title . $title . $after_title;

      # Make the Hello World Example widget
      //echo '<div style="text-align:center;padding:10px;">' . $lineOne . '<br />' . $lineTwo . "</div>";
		$xmlurl = "http://feeds.feedburner.com/dealpixel";
		  
if (ini_get('allow_url_fopen')) {
$resp = simplexml_load_file($xmlurl);
} else {
// Setup a cURL request
$curl_request = curl_init($xmlurl);
curl_setopt($curl_request, CURLOPT_HEADER, false);
curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
// Execute the cURL request
$raw_xml = curl_exec($curl_request);
// ...Check for errors from cURL...
$resp = simplexml_load_string($raw_xml);
}
		
		$imgurl = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		
		$storeurl = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__))."store.php?";
		
		//set donation amount
		if($contribute>0){
			$temp_per2 = rand(1,100);
			if($contribute>$temp_per2){
				$campid = "240882";
			}
		}
		if($campid==""){
		$campid = "240882";
		}
		if ($resp) {

				$n=0;
				$results[$n][SectionTitle]= "DealPixel Deals";	
				foreach($resp->channel->item as $item) {
					$results[$n][title]= $item->title;
					$results[$n][link]= $item->link;
					$results[$n][ConvertedCurrentPrice]= $item->ourprice;
					$results[$n][MSRP]= $item->cprice;;
					$results[$n][PictureURL]= $item->imgsrc;
					$results[$n][SmallPictureURL]= $item->imgsrcsmall;
					if($results[$n][SmallPictureURL]==""){
					$results[$n][SmallPictureURL]=$results[$n][PictureURL];
					}
					$results[$n][DealURL]= "http://dealpixel.com";
					if($results[$n][MSRP]>0){
					$results[$n][SavingsRate]= (($results[$n][MSRP]-$results[$n][ConvertedCurrentPrice])/$results[$n][MSRP])*100;
					$results[$n][SavingsRate] = round($results[$n][SavingsRate],0);
					} else {
					$results[$n][SavingsRate] = 0;
					}
					$tempurl = urlencode($results[$n][DealURL]);
					//$results[$n][pURL]= "http://rover.ebay.com/rover/1/711-53200-19255-0/1?ff3=4&pub=5574642961&toolid=10001&campid=".$campid."&customid=&mpre=".$tempurl;
					$results[$n][pURL]=  "https://www.e-junkie.com/ecom/gb.php?cl=240876&c=ib&aff=".$campid;
					//$results[$n][pURL]=  "https://www.e-junkie.com/ecom/gb.php?cl=240876&c=ib&aff=".$campid."&mpre=".$tempurl;
					//reduce fp for seo--------------------------------------------------------------------------------
					$rover = "buystuff"; // This is the word 'rover' will be replaced with in the link, 
					$ebay = "buycheap"; // Ditto but for the word 'ebay' i.e. with this example you 
					$newterms = array($rover,$ebay);
					$oldterms = array("e-junkie","ecom");
					$linka = str_replace ( $oldterms, $newterms, $results[$n][pURL]);
					$linka = base64_encode ( $linka );
					$results[$n][pURLhide]= $storeurl."&buy=".$rover."&cheap=".$ebay."&buyurl=".$linka;

				$n++;
				}

		} else {
		// If there was no response, print an error
			$results = "Oops! Must not have gotten the response!";
		}
		?>
		<table style="text-align: left; width: 100%;" border="0" cellpadding="0" cellspacing="0">
		  <tbody>
		  <?php
		  if($dis_icon=="yes"){
		  ?>
			<tr align="middle">
			  <td colspan="2" rowspan="1">
			  <img alt="" src="<?php echo $imgurl; ?>dp-logo.png" style="border: 0px solid; float: middle;margin-bottom: 2px; margin-top: 0px;"></td>
			</tr>
		  <?php
		  }
		  ?>
				<tr align="left">
			  <td colspan="2" rowspan="1" align="left">
		<?php
			if($totaldeals<$n && $totaldeals>0){
			$temploop = $totaldeals;
			$tempusing = "totaldeals";
			} else {
			$temploop = $n;
			$tempusing = "n";
			}

		for ($j=0; $j<$temploop; $j++){
		?>
		<div id="divid<?php echo $j;?>">
		<?php
			if($results[$j][SectionTitle]!=""){
			echo "<div id='wpedd-sec".$j."' style='border-top: 2px solid rgb(102, 102, 102); text-align: left; ".$userfont." width: 100%; background-color: rgb(230, 230, 230); font-size: ".$textsize."px; color: rgb(102, 102, 102); padding-top: 0px; padding-bottom: 0px;'>&nbsp;".$results[$j][SectionTitle]."</div>";
			//echo "<div style='font-size: 2px;'>&nbsp;</div>";
			}
		?>
		  <table style="text-align: left; <?php echo $userfont; ?> font-size: <?php echo $textsize;?>px; width: 100%;" border="0" cellpadding="0" cellspacing="0">
			<tr>

			  <td>
			  <div style="font-weight: bold; padding-bottom: 2px; line-height: 14px;">
			  <a style="color: rgb(130, 130, 130); text-decoration: none;" href="<?php echo $results[$j][pURLhide];?>" target="_blank" rel="nofollow">
<img alt="<?php echo $results[$j][title];?>" style="border: 0px solid; float: left;margin-right: 3px; margin-top: 2px;" src="<?php echo $results[$j][SmallPictureURL];?>" width="110px" >
		<?php echo $results[$j][title];?></a></div>
			  <div style="padding-bottom: 0px;"><span
		 style="text-decoration: line-through;">$<?php echo $results[$j][MSRP];?></span>
			  <span style="font-weight: bold;">$<?php echo $results[$j][ConvertedCurrentPrice];?></span>
			  <span style="color: rgb(51, 204, 0);">(<?php echo $results[$j][SavingsRate];?>% off)</span></div>
			  </td>
			</tr>
			
			<?php
			if($results[$j+1][SectionTitle]=="" || $tempusing != "totaldeals"){
			echo "<tr><td colspan='2' rowspan='1'><hr style='width: 100%; height: 1px;'></td></tr>";
			}
			?>
			</table>
			
				
				</div>
		<?php
		}
		?>
			</td>
			</tr>
			<tr>
			   <td><span>
			   <a style="color: rgb(102, 102, 102); text-decoration: none; font-size: <?php echo $textsize-1;?>px;" href="<?php echo $results[0][pURLhide];?>" target="_blank" rel="nofollow"><b>View More Deals</b></a></td>
			  <td align="right">
			  <?php
			  if($linkback=="yes"){
			  ?>
			  <a style="color: rgb(102, 102, 102); text-decoration: none; font-size: <?php echo $textsize-2;?>px;" href="http://dealpixel.com/wp-dealpixel-ads-wordpress-plugin/" target="_blank">
			  DealPixel</a>
			  <?php
			  }
			  ?>
			  </td>
			</tr>
		  </tbody>
		</table>

		<?php

	  

      # After the widget
      echo $after_widget;
  }

  /**
    * Saves the widgets settings.
    *
    */
    function update($new_instance, $old_instance){
      $instance = $old_instance;
      $instance['title'] = strip_tags(stripslashes($new_instance['title']));
      $instance['lineOne'] = strip_tags(stripslashes($new_instance['lineOne']));
      $instance['lineTwo'] = strip_tags(stripslashes($new_instance['lineTwo']));
	  
	  $instance['campid'] = strip_tags(stripslashes($new_instance['campid']));
	  $instance['totaldeals'] = strip_tags(stripslashes($new_instance['totaldeals']));
	  $instance['catagory'] = strip_tags(stripslashes($new_instance['catagory']));
	  $instance['logowidth'] = strip_tags(stripslashes($new_instance['logowidth']));
	  $instance['dis_icon'] = strip_tags(stripslashes($new_instance['dis_icon']));
	  $instance['contribute'] = strip_tags(stripslashes($new_instance['contribute']));
	  $instance['linkback'] = strip_tags(stripslashes($new_instance['linkback']));
	  $instance['textfont'] = strip_tags(stripslashes($new_instance['textfont']));
	  $instance['textsize'] = strip_tags(stripslashes($new_instance['textsize']));
	  

    return $instance;
  }

  /**
    * Creates the edit form for the widget.
    *
    */
    function form($instance){
      //Defaults
      $instance = wp_parse_args( (array) $instance, array('title'=>'', 'lineOne'=>'Hello', 'lineTwo'=>'World', 'campid'=>'240882', 'totaldeals'=>'4', 'catagory'=>'all', 'logowidth'=>'200', 'dis_icon'=>'yes', 'contribute'=>'0', 'linkback'=>'no', 'textfont'=>'Verdana', 'textsize'=>'10') );

      $title = htmlspecialchars($instance['title']);
      $lineOne = htmlspecialchars($instance['lineOne']);
      $lineTwo = htmlspecialchars($instance['lineTwo']);
	  $campid = htmlspecialchars($instance['campid']);
	  $totaldeals = htmlspecialchars($instance['totaldeals']);
	  $catagory = htmlspecialchars($instance['catagory']);
	  $logowidth = htmlspecialchars($instance['logowidth']);
	  $dis_icon = htmlspecialchars($instance['dis_icon']);
	  $contribute = htmlspecialchars($instance['contribute']);
	  $linkback = htmlspecialchars($instance['linkback']);
	  $textfont = htmlspecialchars($instance['textfont']);
	  $textsize = htmlspecialchars($instance['textsize']);

		echo '<table width="600">
		<tr valign="top">
		 <td><b>Title:</b>
		 <input name="'.$this->get_field_name('title').'" type="text" id="'.$this->get_field_id('title').'"
		 value="'.$title.'" /><br></td>
		</tr>
		<tr valign="top">
		 <td><b>Enter Your Dealpixel Affiliate ID:</b>
		 <input name="'.$this->get_field_name('campid').'" type="text" id="'.$this->get_field_id('campid').'"
		 value="'.$campid.'" /><br></td>
		</tr></table>';
		
		echo '<table width="600"><tr valign="top"><th colspan="2" scope="row" align="left"><br><br><big><u>Display Options</u></big><br><br></th></tr>
		<tr valign="top"><th scope="row">Display Dealpixel Logo:</th>
		 <td>
		  <select name="'.$this->get_field_name('dis_icon').'" id="'.$this->get_field_id('dis_icon').'">
		  <option value="'.$dis_icon.'">'.$dis_icon.'</option>
		  <option value="yes">yes</option>
		  <option value="no">no</option>
		  </select>
		 </td>
		</tr>';
	
		
		echo '<tr valign="top"><th scope="row">Choose Text Font</th>
			 <td><select name="'.$this->get_field_name('textfont').'" id="'.$this->get_field_id('textfont').'">
			  <option value="'.$textfont.'">'.$textfont.'</option>
			  <option value="Verdana">Verdana</option>
			  <option value="Inherit Template Style">Inherit Template Style</option>
			  </select></td></tr>';
		
		echo '<tr valign="top"><th scope="row">Choose Text Size:</th>
			 <td><select name="'.$this->get_field_name('textsize').'" id="'.$this->get_field_id('textsize').'">
			  <option value="'.$textsize.'">'.$textsize.'px</option>
			  <option value="8">8px</option>
			  <option value="9">9px</option>
			  <option value="10">10px</option>
			  <option value="11">11px</option>
			  <option value="12">12px</option>
			  </select></td></tr>';
			  			  
		
		echo '</select></td></tr>
		<tr valign="top">
		 <th scope="row">How Many Deals to Display:</th>
		 <td>
		 <input class="color" name="'.$this->get_field_name('totaldeals').'" type="text" id="'.$this->get_field_id('totaldeals').'"
		 value="'.$totaldeals.'" /> </td></tr></table>';
		 
		 echo '<table width="600"><tr valign="top">
		 <td><br><br><b><u>Please Help Support This Plugin:</u></b><br><br></td></tr>
		<tr valign="top"><td><b>Enter the percentage you would like to donate to this plugin:</b>
		 <input size="3" name="'.$this->get_field_name('contribute').'" type="text" id="'.$this->get_field_id('contribute').'"
		 value="'.$contribute.'" />%
		 <br><small>(This will replace your Affiliate ID with a donation one. If you leave at 5% then only 5 out of 100 times my Affiliate<br>ID will be used.
		 Setting it to anything lower than 5% will make me sad.)</small><br><br>
		</td>
		</tr>
		<tr valign="top">
		 <td scope="row"><b>Keep "Powered By" link active:</b>
		  <select name="'.$this->get_field_name('linkback').'" id="'.$this->get_field_id('linkback').'">
		  <option value="'.$linkback.'">'.$linkback.'</option>
		  <option value="yes">yes</option>
		  <option value="no">no</option>
		  </select>
		 <br><small>(Please consider leaving this set to yes and show some love for this plug-in. Especially if you enjoy using it.)</small><br><br>
		</td>
		</tr>
		</table>';
	}

}// END class

/**
  * Register Hello World widget.
  *
  * Calls 'widgets_init' action after the Hello World widget has been registered.
  */
  function WPDealPixelAdsInit() {
  register_widget('WPDealPixelAdsWidget');
  }
  add_action('widgets_init', 'WPDealPixelAdsInit');