<?php

namespace Kunoichi\Icon\Pattern;


/**
 * Icon base.
 *
 * @package kicons
 */
abstract class IconSet {

	protected $name = '';

	protected $parsed = false;

	protected $target = '';

	protected $icons = [];

	protected $font_family = '';

	protected $handle = '';

	/**
	 * IconSet constructor.
	 *
	 * @param string $target Target CSS file to extract.
	 */
	public function __construct( $target = '' ) {
		if ( ! file_exists( $target ) ) {
			return;
		}
		$this->target = $target;
		if ( ! $this->font_family ) {
		    $this->font_family = $this->name;
        }
		// Register itself.
		add_filter( 'kunoichi_icon_availables', [ $this, 'available_icons' ] );
		// Extract all icons.
		add_filter( 'kunoichi_icon_list', [ $this, 'icon_list' ] );
		// Enqueue CSS for Admin.
		if ( $this->handle ) {
			add_filter( 'kunoichi_icon_dependencies', [ $this, 'css_dependency_handle' ] );
		}
	}

	/**
	 * Register available icons.
	 *
	 * @param array $icons
	 */
	public function available_icons( $icons ) {
		$icons[ $this->name ] = $this->font_family;
		return $icons;
	}

	/**
	 * Get icon name and gryph.
	 *
	 * @return array Array of [ 'name' => 'icon-name', 'gryph' => '\f23', 'classes' => 'dashicons dashicons-no' ]
	 */
	abstract protected function get_icon_list();

	/**
	 * Get icons list.
	 *
	 * @param array $icons
	 * @return array
	 */
	public function icon_list( $icons ) {
		if ( ! $this->parsed ) {
			$this->icons = $this->get_icon_list();
		}
		$icons[ $this->name ] = [
			'font_family' => $this->font_family,
			'icons'       => $this->icons,
		];
		return $icons;
	}

	/**
	 * Add CSS dependencies.
	 *
	 * @param array $handles
	 * @return string[]
	 */
	public function css_dependency_handle( $handles = [] ) {
		$handles[] = $this->handle;
		return $handles;
	}
}
