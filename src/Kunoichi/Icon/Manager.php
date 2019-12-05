<?php

namespace Kunoichi\Icon;


use Hametuha\SingletonPattern\Singleton;


/**
 * Icon manager.
 *
 * @package Kunoichi\Icon
 */
class Manager extends Singleton {

	protected function init() {
		// Register all assets.
		add_action( 'init', [ $this, 'register_assets' ] );
		// Register icon parser if kicon is enqueued.
		add_action( 'admin_enqueue_scripts', [ $this, 'register_icons' ], 9999 );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_icons' ], 9999 );
		// Register block if icons exist.
		add_action( 'init', [ $this, 'register_block' ], 11 );
	}

	/**
	 * Register assets.
	 */
	public function register_assets() {
		// Register Iconset holders.
		list( $url, $version ) = $this->get_path_to_url( 'dist/js/icon-holder.js' );
		wp_register_script( 'kicon', $url, [ 'wp-i18n' ], $version, true );
		// Register Icon search.
        list( $url, $version ) = $this->get_path_to_url( 'dist/js/icon-search.js' );
        wp_register_script( 'kicon-search', $url, [ 'wp-components', 'wp-element', 'kicon' ], $version, true );
		// Register icon block.
		list( $url, $version ) = $this->get_path_to_url( 'dist/js/icon-block.js' );
		wp_register_script( 'kicon-block', $url, [ 'kicon', 'wp-block-editor', 'wp-blocks', 'kicon-search' ], $version, true );
        // Register Editor CSS.
        list( $url, $version ) = $this->get_path_to_url( 'dist/css/icons.css' );
        wp_register_style( 'kicon', $url, [], $version );
        list( $url, $version ) = $this->get_path_to_url( 'dist/css/icons-block.css' );
        wp_register_style( 'kicon-block', $url, [ 'kicon' ], $version );
        list( $url, $version ) = $this->get_path_to_url( 'dist/css/icons-block-editor.css' );
        wp_register_style( 'kicon-block-editor', $url, [ 'kicon' ], $version );
	}

	/**
	 * Register icons if enqueued.
	 */
	public function register_icons() {
		if ( wp_script_is( 'kicon' ) ) {
			wp_localize_script( 'kicon', 'Kicon', [
				'icons' => apply_filters( 'kunoichi_icon_list', [] )
			] );
		}
	}

	/**
	 * Register block
	 */
	public function register_block() {
		register_block_type( 'kunoichi/icon', [
			'editor_script' => 'kicon-block',
			'editor_style'  => 'kicon-block-editor',
            'style'         => 'kicon-block',
		] );
	}

	/**
	 * Get file url and version.
	 *
	 * @param string $rel_path
	 * @return string[]
	 */
	public function get_path_to_url( $rel_path ) {
		$base_dir = dirname( dirname( dirname( __DIR__ ) ) );
		$abs_path = $base_dir . '/' . ltrim( $rel_path, '/' );
		$url = str_replace( ABSPATH, home_url( '/' ), $abs_path );
		$version = null;
		if ( file_exists( $abs_path ) ) {
			$version = filemtime( $abs_path );
		}
		return [ $url, $version ];
	}

	/**
	 * Register instance.
	 */
	public static function register(  ) {
		$instance = static::get_instance();
	}
}
