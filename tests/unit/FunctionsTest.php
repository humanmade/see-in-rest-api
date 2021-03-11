<?php

namespace HM\SeeInRestApi\Tests\Unit;

use Brain\Monkey\Functions;
use Mockery;

use function HM\SeeInRestApi\get_current_resource_route;

class FunctionsTest extends TestCase {

	public static function setUpBeforeClass(): void {

		parent::loadPluginFile( 'functions.php' );
		parent::setUpBeforeClass();
	}

	public function testRequestCurrentResourceRouteOnFrontEnd(): void {

		Functions\when( 'is_admin' )
			->justReturn( false );

		$queried_resource_route = '/wp/v2/tests';

		Functions\expect( 'rest_get_queried_resource_route' )
			->once()
			->andReturn( $queried_resource_route );

		self::assertSame( $queried_resource_route, get_current_resource_route() );
	}

	public function testRequestCurrentResourceRouteForInvalidResourceType(): void {

		Functions\when( 'is_admin' )
			->justReturn( true );

		Functions\when( 'get_current_screen' )
			->justReturn();

		self::assertSame( '', get_current_resource_route() );
	}

	public function testRequestCurrentPostRoute(): void {

		Functions\when( 'is_admin' )
			->justReturn( true );

		Functions\when( 'get_current_screen' )
			->justReturn( (object) [ 'base' => 'post' ] );

		$post_id = 42;
		Functions\expect( 'get_the_ID' )
			->once()
			->andReturn( $post_id );

		Functions\expect( 'rest_get_route_for_post' )
			->once()
			->with( $post_id )
			->andReturnUsing( function ( int $post_id ): string {

				return "/wp/v2/posts/{$post_id}";
			} );

		self::assertSame( "/wp/v2/posts/{$post_id}", get_current_resource_route() );
	}

	public function testRequestCurrentProfileRoute(): void {

		Functions\when( 'is_admin' )
			->justReturn( true );

		Functions\when( 'get_current_screen' )
			->justReturn( (object) [ 'base' => 'profile' ] );

		$user_id = 42;
		Functions\expect( 'get_current_user_id' )
			->once()
			->andReturn( $user_id );

		self::assertSame( "/wp/v2/users/{$user_id}", get_current_resource_route() );
	}

	public function testRequestCurrentProfileRouteNoUser(): void {

		Functions\when( 'is_admin' )
			->justReturn( true );

		Functions\when( 'get_current_screen' )
			->justReturn( (object) [ 'base' => 'profile' ] );

		Functions\expect( 'get_current_user_id' )
			->once()
			->andReturn( 0 );

		self::assertSame( '', get_current_resource_route() );
	}

	public function testRequestCurrentTermRoute(): void {

		Functions\when( 'is_admin' )
			->justReturn( true );

		Functions\when( 'get_current_screen' )
			->justReturn( (object) [ 'base' => 'term' ] );

		$term_id = 42;
		Functions\expect( 'filter_input' )
			->once()
			->with( INPUT_GET, 'tag_ID', Mockery::type( 'int' ) )
			->andReturn( $term_id );

		Functions\expect( 'rest_get_route_for_term' )
			->once()
			->with( $term_id )
			->andReturnUsing( function ( int $term_id ): string {

				return "/wp/v2/terms/{$term_id}";
			} );

		self::assertSame( "/wp/v2/terms/{$term_id}", get_current_resource_route() );
	}

	public function testRequestCurrentTermRouteNoTerm(): void {

		Functions\when( 'is_admin' )
			->justReturn( true );

		Functions\when( 'get_current_screen' )
			->justReturn( (object) [ 'base' => 'term' ] );

		Functions\expect( 'rest_get_route_for_term' )
			->once()
			->with( 0 )
			->andReturn( '' );

		self::assertSame( '', get_current_resource_route() );
	}

	public function testRequestUserRoute(): void {

		Functions\when( 'is_admin' )
			->justReturn( true );

		Functions\when( 'get_current_screen' )
			->justReturn( (object) [ 'base' => 'user-edit' ] );

		$user_id = 42;
		Functions\expect( 'filter_input' )
			->once()
			->with( INPUT_GET, 'user_id', Mockery::type( 'int' ) )
			->andReturn( $user_id );

		self::assertSame( "/wp/v2/users/{$user_id}", get_current_resource_route() );
	}

	public function testRequestUserRouteNoUser(): void {

		Functions\when( 'is_admin' )
			->justReturn( true );

		Functions\when( 'get_current_screen' )
			->justReturn( (object) [ 'base' => 'user-edit' ] );

		self::assertSame( '', get_current_resource_route() );
	}
}
