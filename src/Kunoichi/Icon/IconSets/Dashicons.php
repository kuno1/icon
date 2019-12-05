<?php

namespace Kunoichi\Icon\IconSets;


use Kunoichi\Icon\Pattern\IconSet;

/**
 * Dashicons.
 *
 * @package kicons
 */
class Dashicons extends IconSet {
	
	protected $name = 'Dashicons';
	
	protected $font_family = 'Dashicons';
	
	protected $handle = 'dashicons';
	
	public function __construct( $target = '' ) {
		if ( ! $target ) {
			$target = ABSPATH . 'wp-includes/css/dashicons.min.css';
		}
		parent::__construct( $target );
	}
	
	/**
	 * Parse dashicons
	 *
	 * @return array
	 */
	protected function get_icon_list() {
		$icons = [];
		$file  = file_get_contents( $this->target );
		if ( preg_match_all( '/\.dashicons-([a-z\-0-9]+):before{content:"([^"]*)"}/u', $file, $matches, PREG_SET_ORDER ) ) {
			foreach ( $matches as list( $selector, $class, $gryph ) ) {
				if ( 'before' === $class ) {
					continue;
				}
				$icons[] = [
					'name'    => $class,
					'classes' => 'dashicons dashicons-' . $class,
					'gryph'   => $gryph,
				];
			}
		}
		return $icons;
	}
}
