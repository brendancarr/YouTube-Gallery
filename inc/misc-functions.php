<?php
/**
 * Miscellaneous functions file.
 *
 * @package YouTube_Gallery
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Checks if cron is running.
 */
function yotuwp_doing_cron() {

	if ( function_exists( 'wp_doing_cron' ) && wp_doing_cron() ) {
		// Bail if not doing WordPress cron (>4.8.0).
		return true;

	} elseif ( defined( 'DOING_CRON' ) && ( true === DOING_CRON ) ) {
		// Bail if not doing WordPress cron (<4.8.0).
		return true;
	}

	// Default to false.
	return false;
}

/**
 * Gets the video title.
 */
function yotuwp_video_title( $video ) {
	return apply_filters( 'yotuwp_video_title', $video->snippet->title, $video );
}

/**
 * Gets the video description.
 */
function yotuwp_video_description( $video ) {
	$desc = apply_filters( 'yotuwp_video_description', nl2br( wp_strip_all_tags( $video->snippet->description ) ), $video );
	return wp_kses_post( $desc );
}

/**
 * Gets the video thumbnail.
 */
function yotuwp_video_thumb( $video ) {
	$url = ( isset( $video->snippet->thumbnails ) && isset( $video->snippet->thumbnails->standard ) ) ? $video->snippet->thumbnails->standard->url : $video->snippet->thumbnails->high->url;
	return apply_filters( 'yotuwp_video_thumbnail', $url, $video );
}

/**
 * Applieed KSES to content.
 */
function yotuwp_kses( $content ) {

	$allowed_html = wp_kses_allowed_html( 'post' );

	// Iframe.
	$allowed_html['iframe'] = array(
		'src'             => array(),
		'height'          => array(),
		'width'           => array(),
		'frameborder'     => array(),
		'allowfullscreen' => array(),
	);

	// Form fields - input.
	$allowed_html['input'] = array(
		'class'    => array(),
		'data-*'   => 1,
		'id'       => array(),
		'name'     => array(),
		'value'    => array(),
		'type'     => array(),
		'selected' => array(),
		'checked'  => array(),
	);

	// Select.
	$allowed_html['select'] = array(
		'class'  => array(),
		'data-*' => 1,
		'id'     => array(),
		'name'   => array(),
		'value'  => array(),
		'type'   => array(),
	);

	// Select options.
	$allowed_html['option'] = array(
		'selected' => array(),
		'value'    => array(),
	);

	// Style.
	$allowed_html['style'] = array(
		'types' => array(),
	);

	// Script.
	$allowed_html['script'] = array(
		'src'  => array(),
		'type' => array(),
	);

	return wp_kses( $content, $allowed_html );
}

