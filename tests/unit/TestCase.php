<?php

namespace HM\SeeInRestApi\Tests\Unit;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Abstract base class for all unit test case implementations.
 */
abstract class TestCase extends PHPUnitTestCase {

	use MockeryPHPUnitIntegration;

	/**
	 * Ensure the plugin file with the given (relative) path is loaded.
	 *
	 * @param string $file Plugin file path (relative).
	 */
	protected static function loadPluginFile( string $file ): void {

		require_once dirname( __DIR__, 2 ) . "/inc/{$file}";
	}

	/**
	 * Prepare the test environment before each test.
	 *
	 * @return void
	 */
	protected function setUp(): void {

		parent::setUp();
		Monkey\setUp();
	}

	/**
	 * Clean up the test environment after each test.
	 *
	 * @return void
	 */
	protected function tearDown(): void {

		Monkey\tearDown();
		parent::tearDown();
	}
}
