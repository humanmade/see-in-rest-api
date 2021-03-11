<?php

declare( strict_types=1 );

namespace HM\SeeInRestApi;

/**
 * Return the REST API route for the current resource, if any.
 *
 * @return string REST API route.
 */
function get_current_resource_route(): string {

	if ( ! is_admin() ) {
		// Use Core function for front-end requests.
		return (string) rest_get_queried_resource_route();
	}

	$screen = get_current_screen()->base ?? '';

	switch ( $screen ) {
		case 'post':
			$post_id = get_the_ID();

			return (string) rest_get_route_for_post( $post_id );

		case 'profile':
			$user_id = get_current_user_id();

			return  $user_id ? "/wp/v2/users/{$user_id}" : '';

		case 'term':
			$term_id = filter_input( INPUT_GET, 'tag_ID', FILTER_SANITIZE_NUMBER_INT );

			return (string) rest_get_route_for_term( $term_id );

		case 'user-edit':
			$user_id = filter_input( INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT );

			return  $user_id ? "/wp/v2/users/{$user_id}" : '';

		default:
			return '';
	}
}
