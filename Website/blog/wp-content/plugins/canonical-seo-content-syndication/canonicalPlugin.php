<?php
/**
 * Plugin Name: Canonical SEO WordPress Plugin
 * Plugin URI: https://www.shoutmeloud.com/
 * Description: Canonical Plugin for SEO. Provides A Simple Toggle On/Off Option to set Canonical URL of a particular post.
 * Version: 3.0
 * Author: Harsh Agrawal
 * Author URI: https://www.shoutmeloud.com/
 * License: GPL2
 */


//New Fix Dec 2018 To Prevent Duplicate Canonical Tags
add_filter( 'get_canonical_url', 'seo_plugin_bens_override', 99, 2 );
function seo_plugin_bens_override( $can_url, $post ) { 
    $can = get_post_meta($post->ID, '_Seo_plugin_meta_canonical_tag', true);
    if ( $can ) { 
        $can_url = $can;
    }   
    return $can_url;
}


//call the filters

//Here to add metaboxes under post and page
// Create your custom meta box
add_action( 'add_meta_boxes', 'Seo_plugin_add_custom_canonical_url' );
// Save your meta box content

add_action( 'save_post', 'Seo_plugin_save_custom_canonical_tag_box' );
// Add a custom meta box to a post

function Seo_plugin_add_custom_canonical_url( $post ) {

	  
	  add_meta_box(
	  'Meta Box', // ID, should be a string
	  '&nbsp;<img style="margin-bottom:-2px; margin-left:-5px;" width="16" height="16" src="../wp-content/plugins/canonical-seo-content-syndication/logo.png"> SEO Canonical Settings', // Meta Box Title
	  'Seo_plugin_custom_meta_box_content_canonical_url', // Your call back function, this is where your form field will go
	  'post', // The post type you want this to show up on, can be post, page, or custom post type
	  'normal', // The placement of your meta box, can be normal or side
	  'high' // The priority in which this will be displayed
	  );


}


//Custom Document Title Meta Box
// save newsletter content
function Seo_plugin_save_custom_canonical_tag_box(){
global $post;
// Get our form field
if( $_POST ) :

$Seo_plugin_meta_canonical = esc_attr( $_POST['Seo_plugin-meta-canonical-url'] );
// Update post meta canonical url
update_post_meta($post->ID, '_Seo_plugin_meta_canonical_tag', $Seo_plugin_meta_canonical);


endif;
}

// Content for the custom meta box
function Seo_plugin_custom_meta_box_content_canonical_url( $post ) {?>
<script type="text/javascript">
//Count the characters in meta title and meta description
$(document).ready(function(){
    var totalChars      = 150; //Total characters allowed in textarea
    var countTextBox    = $('#counttextarea') // Textarea input box
    var charsCountEl    = $('#countchars'); // Remaining chars count will be displayed here
	
	var totalChars1      = 70; //Total characters allowed in textarea
    var countTextBox1   = $('#counttextarea1') // Textarea input box
    var charsCountEl1    = $('#countchars1'); // Remaining chars count will be displayed here
    
    charsCountEl.text(totalChars); //initial value of countchars element
    countTextBox.keyup(function() { //user releases a key on the keyboard
        var thisChars = this.value.replace(/{.*}/g, '').length; //get chars count in textarea
        if(thisChars > totalChars) //if we have more chars than it should be
        {
            var CharsToDel = (thisChars-totalChars); // total extra chars to delete
            this.value = this.value.substring(0,this.value.length-CharsToDel); //remove excess chars from textarea
        }else{
            charsCountEl.text( totalChars - thisChars ); //count remaining chars
        }
    });
	
	charsCountEl1.text(totalChars1); //initial value of countchars element
    countTextBox1.keyup(function() { //user releases a key on the keyboard
        var thisChars = this.value.replace(/{.*}/g, '').length; //get chars count in textarea
        if(thisChars > totalChars1) //if we have more chars than it should be
        {
            var CharsToDel = (thisChars-totalChars1); // total extra chars to delete
            this.value = this.value.substring(0,this.value.length-CharsToDel); //remove excess chars from textarea
        }else{
            charsCountEl1.text( totalChars1 - thisChars ); //count remaining chars
        }
    });
});
</script>
<?php

	  // Get post Custom Canonical Url
	  $Seo_plugin_meta_canonical = get_post_meta($post->ID, '_Seo_plugin_meta_canonical_tag', true);
	  ?>
<!-- Rounded switch -->
<label class="switch" style="margin-top:5px;">
  <input id="seocan" type="checkbox" onchange="calc();">
  <span class="slider round"></span>
</label>
<script>

function calc()
{
  if (document.getElementById('seocan').checked) 
  {
      document.getElementById('can123').style.display = "block";
  } else {
  	document.getElementById('canurl').value="";
      document.getElementById('can123').style.display = "none";
  }
}


</script>


<style>
/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 28px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 21px;
  width: 21px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}


input:checked + .slider:before {
  -webkit-transform: translateX(20px);
  -ms-transform: translateX(20px);
  transform: translateX(20px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
	  
	  <?php
	  echo '<span id="can123"><p><label>Custom Canonical URL:</label><a style="text-decoration:none; position: absolute; right:15px;" href="https://www.shoutmeloud.com/relcanonical-wordpresss-content-syndication-seo.html" target="_blank"><span class="dashicons dashicons-info"></span></a></p>';
	  echo '<p><input id="canurl" style="width:99%;" class="meta-text" type="text" placeholder="Enter the URL of Orignally Published Content if any, otherwise leave blank" name="Seo_plugin-meta-canonical-url" value="'.$Seo_plugin_meta_canonical.'" /></p></span>';

?>
<script>
<?php
if(strlen($Seo_plugin_meta_canonical)>0){
echo "document.getElementById('seocan').checked = true; "; }
else {
echo "document.getElementById('can123').style.display = 'none';";
}
?>
</script>
<?php
}