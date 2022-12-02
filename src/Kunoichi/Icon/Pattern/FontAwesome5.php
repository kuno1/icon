<?php

namespace Kunoichi\Icon\Pattern;

use Kunoichi\Icon\Manager;

/**
 * Fontawesome Parser base.
 *
 * @package kicon
 */
abstract class FontAwesome5 extends IconSet {

	protected $font_group = '';

	protected $handle = 'fa5-all';

	protected $default_svg = '';

	/**
	 * Override default SVG.
	 * @param string $target
	 */
	public function __construct( $target = '' ) {
		if ( ! $target ) {
			$target = Manager::dir() . '/dist/webfonts/' . $this->default_svg;
		}
		parent::__construct( $target );
	}


	/**
	 * Parse SVG and
	 *
	 * @return array
	 */
	protected function get_icon_list() {
		$icons = [];
		$file  = file_get_contents( $this->target );
		if ( preg_match_all( '/<glyph glyph-name="([^"]+)" unicode="([^"]+);"/u', $file, $matches, PREG_SET_ORDER ) ) {
			foreach ( $matches as list( $attributes, $name, $glyph ) ) {
				$icons[] = [
					'name'    => $name,
					'classes' => $this->font_group . ' fa-' . $name,
					'glyph'   => str_replace( '&#x', '\\', $glyph ),
				];
			}
		}
		return $icons;
	}


}
