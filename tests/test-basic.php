<?php
/**
 * Class SampleTest
 *
 * @package pr
 */

use Kunoichi\Icon\IconSets\Dashicons;
use Kunoichi\Icon\Manager;

/**
 * Sample test case.
 */
class BasicTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	public function test_css_properties() {
		new Dashicons();
		$this->assertNotEmpty( Manager::availables() );
	}
}
