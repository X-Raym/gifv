<?php
/*
Plugin Name: Gifv
Description: Display HTML5 videos in a gif like fashion
Plugin URI: http://github.com/hilja/gifv
Author: Antti Hiljá
Author URI: http://clubmate.fi
Version: 0.1
License: GPL2
Text Domain: gifv
*/

/*

    Copyright (C) Year  Antti Hiljá  Email

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Shortcode to insert gif like videos to page
 *
 * The videos loop and autoplay automatically, and the audio is muted.
 *
 * @param  array $atts The attributes from the shortcode
 * @return string      HTML video element
 */
function cm_gifv($atts)
{
    // Get all the possible video format types, these would be:
    // 'mp4', 'm4v', 'webm', 'ogv', 'wmv', 'flv'
    $default_types = wp_get_video_extensions();

    // The default attributes for the shortcode
    $defaults_atts = array(
        'src'      => '',
        'poster'   => '',
        'loop'     => 'on',
        'autoplay' => 'on',
        'muted'    => 'on',
        'preload'  => 'metadata',
        'width'    => 640,
        'height'   => 360,
    );

    // Add the default types to the mix
    foreach ($default_types as $type) {
        $defaults_atts[$type] = '';
    }

    // Parse the defaults with the atts
    $atts = shortcode_atts($defaults_atts, $atts, 'gifv');

    // HTMLify the attributes
    $html_atts = array(
        'class'    => '',
        'id'       => sprintf('video-%d-%d', $post_id, $instance),
        'width'    => absint($atts['width']),
        'height'   => absint($atts['height']),
        'poster'   => esc_url($atts['poster']),
        'loop'     => wp_validate_boolean($atts['loop']),
        'autoplay' => wp_validate_boolean($atts['autoplay']),
        'autoplay' => wp_validate_boolean($atts['muted']),
        'preload'  => $atts['preload'],
    );

    // Make the attributes to HTML element friendly strings
    $attr_strings = array();
    foreach ($html_atts as $k => $v) {
        $attr_strings[] = $k . '="' . esc_attr($v) . '"';
    }

    // Patterns for checking if YouTube or Vimeo video
    $is_vimeo = $is_youtube = false;
    $yt_pattern = '#^https?://(?:www\.)?(?:youtube\.com/watch|youtu\.be/)#';
    $vimeo_pattern = '#^https?://(.+\.)?vimeo\.com/.*#';

    // Initiate the video element
    $html = '';

    $html .= sprintf('<video %s>', join(' ', $attr_strings));

    // Handle the source element inside the video element
    $fileurl = '';
    $source = '<source type="%s" src="%s" />';
    foreach ($default_types as $fallback) {
        if (!empty( $atts[ $fallback ])) {
            if (empty($fileurl)) {
                $fileurl = $atts[ $fallback ];
            }
            if ('src' === $fallback && $is_youtube) {
                $type = array( 'type' => 'video/youtube' );
            } elseif ('src' === $fallback && $is_vimeo) {
                $type = array( 'type' => 'video/vimeo' );
            } else {
                $type = wp_check_filetype($atts[ $fallback ], wp_get_mime_types());
            }
            $url = add_query_arg('_', $instance, $atts[$fallback ]);
            $html .= sprintf($source, $type['type'], esc_url($url));
        }
    }

    // Close the video element
    $html .= '</video>';

    $width_rule = '';
    if (!empty( $atts['width'])) {
        $width_rule = sprintf('width: %dpx; ', $atts['width']);
    }
    $output = sprintf('<div style="%s" class="gifv">%s</div>', $width_rule, $html);

    // BOOM
    return $output;
}
add_shortcode('gifv', 'cm_gifv');
