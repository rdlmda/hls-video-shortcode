<?php
/**
 * @package HLS Video Shortcode
 * @version 0.1
 */
/*
Plugin Name: HLS Video Shortcode
Plugin URI: http://github.com/ruda-almeida/hls-video-shortcode
Description: HLS Video Shortcode - Offers a shortcode for HLS video
Author: Rudá Almeida
Version: 0.1
Author URI: http://www.rudaalmeida.com.br
License: GPL v3
*/
function use_hls_video_shortcode($atts) {
	// extract shortcode informations
	extract(shortcode_atts(array(
		'src' => '',
		'width' => '',
		'height' => '',
	), $atts));

	$playerid = uniqid('hlsvideo');

	// video source
	if($src) {
		$src = '<source src="'.$src.'" type="application/x-mpegURL" />';
	}

	// Set a default height if not available
	if(!$height) {
		$height = 320;
	}
	// Set a default width if not available
	if(!$width) {
		$width = 568;
	}

	// Create Player content for video
	$hlsvideoplayer .= <<<_end_

	<div class="hls-video-player">
		<video id="{$playerid}" class="video-js vjs-default-skin" width="{$width}" height="{$height}" controls>
			{$src}
		</video>
	</div>

<script>var player = videojs('{$playerid}');</script>
_end_;

	// Return content
	return $hlsvideoplayer;
}

add_shortcode('hls_video', 'use_hls_video_shortcode');

function add_hls_video_header() {
	global $posts;
	$isUsed = false;

	// check if shortcode was found in the content of one of all posts to add sources to the header
	for($i = 0; $i<count($posts); $i++) {
		if(strstr($posts[$i]->post_content, '[hls_video') ) {
			$isUsed = true;
		}
	}

	// if shortcode is used in content add sources to the header
	if($isUsed) {
		$dir = plugins_url( '', __FILE__ );
		echo <<<_end_
	<link rel="stylesheet" href="{$dir}/video-js.css" type="text/css" media="screen">
	<script src="{$dir}/video.js" type="text/javascript" charset="utf-8"></script>
	<script src="{$dir}/videojs.hls.min.js" type="text/javascript" charset="utf-8"></script>
_end_;
	}
}
add_action('wp_head','add_hls_video_header');
?>
