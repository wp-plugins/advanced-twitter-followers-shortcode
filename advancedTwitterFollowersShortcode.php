<?php
/**
 * @package advanced-twitter-followers-shortcode
*/
/*
Plugin Name: Advanced Twitter Followers Shortcode
Plugin URI: http://www.sparxseo.com
Description: Advanced Twitter Followers Shortcode for Wordpress.Advanced Twitter Followers Shortcode is an advanced Wordpress Twitter Followers Display widget which allow to customized in lots of way. You can add or remove options as well change color of background. No of Twitter followers to display and lots of. So Hope you will enjoy our this free wordpress shortcode :) .
Version: 1.1
Author: Alan Ferdinand
Author URI: http://www.sparxseo.com
*/
// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_plugin_styles_adv_twitter' );
add_shortcode('advancedTwitterFollowers', 'advancedTwitterFollowersShortcode');
 function advancedTwitterFollowersShortcode($atts){
 	$atts = shortcode_atts(array(
 		'suffix' => '',
 		'username' => '',
 		'width' => '500',
		'key' => '',
		'keysecret' => '',
 		'token' => '',
		'tokensecret' => '',
 		'background' => '#fff',
        'border' => 'true',
 		'border_size' => '1',
 		'border_color' => '#ccc',
        'module_padding' => '10',
 		'border_radius' => '10',
        'header' => 'true',
 		'header_text' => 'Follow us on Twitter',
 		'header_background_color' => '#ccc',
        'header_font_color' => '#fff',
 		'follow_button_text' => 'follow',
        'connections' => '15',
 		'image_padding' => '0',
 		'image_border_radius' => '7',
        'image_border' => '2',
 		'image_border_color' => '#ccc',
 		'footer' => '#true',
		'author' => 'true'
 	), $atts);
	print_r($atts);
 	extract($atts);
        //including the api
        $dir = plugin_dir_path( __FILE__ )."TwitterAPIExchange.php";
        require_once($dir);
        $settings = array(
        'oauth_access_token' => trim($token),
        'oauth_access_token_secret' => trim($tokensecret),
        'consumer_key' => trim($key),
        'consumer_secret' => trim($keysecret)
        );
        $urlUserInformation = "https://api.twitter.com/1.1/users/show.json";
        $url = "https://api.twitter.com/1.1/followers/list.json";
        $requestMethod = "GET";
        $getFollowers = "?cursor=-1&screen_name=$username&skip_status=true&include_user_entities=false";
        $twitter = new TwitterAPIExchange($settings);
        $stringUserInfo = json_decode($twitter->setGetfield($getFollowers)
        ->buildOauth($urlUserInformation, $requestMethod)
        ->performRequest(),$assoc = TRUE);
        $string = json_decode($twitter->setGetfield($getFollowers)
        ->buildOauth($url, $requestMethod)
        ->performRequest(),$assoc = TRUE);
        $followers = $stringUserInfo['followers_count'];
        if($username == "" || $key == "" || $keysecret == "" || $token == "" || $tokensecret == ""){
            $data = "Please check our documentation. Some fields are required in our advanced twitter followers shortcode." . $tUsername;
        }
        else{
 	$data = "";
 	$data .= "
<div id='advanced_twitter_followers_shortcode' style='max-width: $width";
        $data .= "px; background: $background; padding: $module_padding";
        $data .= "px; border-radius: $border_radius";
        $data .= "px;'>
		<div id='twitterWidget' class='twitterFollowers'>
			<div class='likebox-border'>
				<div id='likebox' style='color: #000; font-size: 14px; ";
        if($border== "true"){
            $data .= "border: $border_size";
            $data .= "px solid $border_color; ";
            }
        $data .= "'>";
        if($header == "true"){
        $data .= "<div class='findus' style='background: $header_background_color; color: $header_font_color; '>$header_text</div>";
        }
        $userScreenName = $stringUserInfo['screen_name'];
        $userProfileImage = $stringUserInfo['profile_image_url'];
        $userNameInfo = $stringUserInfo['name'];
	$data .= "<div class='floatelement'>
            <div class='thumb-img'><a href='//twitter.com/$userScreenName' target='_blank'><img src='$userProfileImage'></a></div>
		<div class='right-text'><p class='title'><a href='//twitter.com/$userScreenName' target='_blank'>$userNameInfo</a></p>
                    <a class='follow-btn' href='//twitter.com/$userScreenName' target='_blank'><span></span> $follow_button_text</a></div>						
			<div class='clr'></div>
		</div>
			<div class='imagelisting'>
                            <p>$followers peoples are following <strong><a href='//twitter.com/$userScreenName' target='_blank'>$userScreenName</a></strong> @twitter</p>
    <ul>";
foreach($string as $items){						
$length = count($items);
if($length<$connections){
$t = $length;}
else{$t = $connections+1;
}
for($i=0;$i<$t-1;$i++){
$followImg = $items[$i]['profile_image_url'];
$followURL = $items[$i]['screen_name'];
$followTitle = $items[$i]['name'];
$data .= "<li style='margin: 5px 5px 0 0;'><a href='//twitter.com/$followURL' target='_blank'><img src='$followImg' title='$followTitle' style='border: $image_border";
$data .= "px  solid $image_border_color; border-radius: $image_border_radius";
$data .= "px;margin: $image_padding";
$data .= "px;'></a></li>";
}
}
$data .= "<div class='clr'></div>
    </ul>";
if($footer=="true"){
    $data .= "<p class='icon'>follow us on twitter</p>";
        }
$data .= "</div>
				</div>
			</div>
		</div>
	</div>";
	if($author == "true"){
	$data .= "<div style='font-size: 9px; color: #808080; font-weight: normal; font-family: tahoma,verdana,arial,sans-serif; line-height: 1.28; text-align: right; direction: ltr;'><a href='http://woodplanktile.com' target='_blank' style='color: #808080;' title='click here'>woodplanktile.com</a></div>";}
        }
 	return $data;
 }
 
 /**
  * Register style sheet.
  */
 function register_plugin_styles_adv_twitter() {
 	wp_register_style( 'advancedTwitterFollowersShortcode', plugins_url( 'assets/style.css' , __FILE__ ) );
 	wp_enqueue_style( 'advancedTwitterFollowersShortcode' );
 }