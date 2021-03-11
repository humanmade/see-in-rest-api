<?php

namespace HM\SeeInRestApi\Tests\Unit;

use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use Mockery;
use WP_Admin_Bar;

use function HM\SeeInRestApi\initialize;
use function HM\SeeInRestApi\maybe_add_admin_bar_node;

class NamespaceTest extends TestCase {

	public static function setUpBeforeClass(): void {

		parent::loadPluginFile( 'namespace.php' );
		parent::setUpBeforeClass();
	}

	public function testRegisterAdminBarCallback(): void {

		initialize();

		self::assertNotFalse( has_action( 'admin_bar_menu', 'HM\\SeeInRestApi\\maybe_add_admin_bar_node' ) );
	}

	public function testAddingAdminBarNode(): void {

		$route = '/wp/v2/tests/42';
		Functions\when( 'HM\\SeeInRestApi\\get_current_resource_route' )
			->justReturn( $route );

		$rest_url = "https://example.com/rest-base{$route}";
		Functions\when( 'rest_url' )
			->justReturn( $rest_url );

		Filters\expectApplied( 'see_in_rest_api.rest_url' )
			->with( $rest_url );

		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();

		$wp_admin_bar = Mockery::mock( WP_Admin_Bar::class );
		$wp_admin_bar->shouldReceive( 'add_node' )
			->once()
			->with( Mockery::on( function ( array $args ) use ( $rest_url ): bool {

				return (
					$args['href'] === $rest_url
					&& $args['id'] === 'see-in-rest-api'
					&& $args['meta']['target'] === '_blank'
				);
			} ) );

		maybe_add_admin_bar_node( $wp_admin_bar );
	}

	public function testAddingAdminBarNodeNoRoute(): void {

		Functions\when( 'HM\\SeeInRestApi\\get_current_resource_route' )
			->justReturn( '' );

		$wp_admin_bar = Mockery::mock( WP_Admin_Bar::class );
		$wp_admin_bar->shouldNotReceive( 'add_node' );

		maybe_add_admin_bar_node( $wp_admin_bar );
	}

	public function testAddingAdminBarNodeNoRestUrl(): void {

		$route = '/wp/v2/tests/42';
		Functions\when( 'HM\\SeeInRestApi\\get_current_resource_route' )
			->justReturn( $route );

		$rest_url = "/rest-base{$route}";
		Functions\when( 'rest_url' )
			->justReturn( $rest_url );

		// Fake a filter callback changing the URL to an empty string.
		Filters\expectApplied( 'see_in_rest_api.rest_url' )
			->with( $rest_url )
			->andReturn( '' );

		$wp_admin_bar = Mockery::mock( WP_Admin_Bar::class );
		$wp_admin_bar->shouldNotReceive( 'add_node' );

		maybe_add_admin_bar_node( $wp_admin_bar );
	}
}
