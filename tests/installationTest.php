<?php

require_once 'src/class/class-ARCW.php';

class installationTest extends WP_UnitTestCase {

	function test_installation() {
		$mock = $this->getMockBuilder( ARCW::class )
			->setMethods( [ 'get_options' ] )
			->getMock();
		$mock->method( 'get_options' )
			->willReturn( [ "hi" => true ] );

		print_r( ARCW::flop() );

		$this->assertSame( [ "hi" => true ], ARCW::flop() );
	}
}
