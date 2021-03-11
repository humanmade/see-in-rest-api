<?php

declare( strict_types=1 );

namespace HM\SeeInRestApi;

use WP_Admin_Bar;

/**
 * Initialize the plugin.
 */
function initialize(): void {

	add_action( 'admin_bar_menu', __NAMESPACE__ . '\\maybe_add_admin_bar_node', 100 );
}

/**
 * Add the "See in REST API" admin bar node for the current resource, if any.
 *
 * @param WP_Admin_Bar $wp_admin_bar Admin bar object.
 */
function maybe_add_admin_bar_node( WP_Admin_Bar $wp_admin_bar ): void {

	$route = get_current_resource_route();

	/**
	 * Filter the resource URL.
	 *
	 * @param string $rest_url REST API URL for the current resource.
	 */
	$rest_url = trim( (string) apply_filters( 'see_in_rest_api.rest_url', $route ? rest_url( $route ) : '' ) );
	if ( ! $rest_url ) {
		return;
	}

	$id = 'see-in-rest-api';

	$style = <<<CSS
#wp-admin-bar-{$id} .ab-item::before {
	content: '\\f124';
	top: 2px;
}
CSS;

	$wp_admin_bar->add_node( [
		'href'  => esc_url( $rest_url ),
		'id'    => $id,
		'title' => _x( 'See in REST API', 'admin bar label', 'see-in-rest-api' ),
		'meta'  => [
			'html'   => "<style>{$style}</style>",
			'target' => '_blank',
		],
	] );
}
