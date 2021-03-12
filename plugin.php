<?php
/**
 * See in REST API
 *
 * @package           HumanMade\SeeInRestApi
 * @author            Human Made
 * @copyright         2021 Human Made
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       See in REST API
 * Plugin URI:        https://github.com/humanmade/see-in-rest-api
 * Description:       Quickly request the current resource off the WordPress REST API via the WordPress admin bar.
 * Version:           1.0.0
 * Requires at least: 5.5
 * Requires PHP:      7.2
 * Author:            Human Made
 * Author URI:        https://humanmade.com/
 * Text Domain:       see-in-rest-api
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace HM\SeeInRestApi;

defined( 'ABSPATH' ) || die;

require __DIR__ . '/inc/functions.php';
require __DIR__ . '/inc/namespace.php';

add_action( 'plugins_loaded', __NAMESPACE__ . '\\initialize' );
