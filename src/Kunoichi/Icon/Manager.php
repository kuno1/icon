<?php

namespace Kunoichi\Icon;


use Hametuha\SingletonPattern\Singleton;
use Kunoichi\Icon\IconSets\Dashicons;
use Kunoichi\Icon\IconSets\FontAwesomeBrand;
use Kunoichi\Icon\IconSets\FontAwesomeRegular;
use Kunoichi\Icon\IconSets\FontAwesomeSolid;


/**
 * Icon manager.
 *
 * @package Kunoichi\Icon
 */
class Manager extends Singleton {

	protected function init() {
		// Load translations.
		load_textdomain( 'kicon', self::dir() . sprintf( '/languages/%s.mo', get_user_locale() ) );
		// Register all assets.
		add_action( 'init', [ $this, 'register_assets' ] );
		// Register icon parser if kicon is enqueued.
		add_action( 'admin_enqueue_scripts', [ $this, 'register_icons' ], 9999 );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_icons' ], 9999 );
		// Register block if icons exist.
		add_action( 'init', [ $this, 'register_block' ], 11 );
		// Register inline icon if block exists.
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
	}

	/**
	 * Register assets.
	 */
	public function register_assets() {
		// Register Iconset holders.
		list( $url, $version ) = $this->get_path_to_url( 'dist/js/icon-holder.js' );
		wp_register_script( 'kicon', $url, [ 'wp-i18n' ], $version, true );
		wp_set_script_translations( 'kicon', 'kicon', self::dir() . '/languages' );
		// Register Icon search.
		list( $url, $version ) = $this->get_path_to_url( 'dist/js/icon-search.js' );
		wp_register_script( 'kicon-search', $url, [ 'wp-components', 'wp-element', 'kicon' ], $version, true );
		// Register icon block.
		list( $url, $version ) = $this->get_path_to_url( 'dist/js/icon-block.js' );
		wp_register_script( 'kicon-block', $url, [ 'kicon', 'wp-block-editor', 'wp-blocks', 'kicon-search' ], $version, true );
		// Register inline icon.
		list( $url, $version ) = $this->get_path_to_url( 'dist/js/icon-inline.js' );
		wp_register_script( 'kicon-inline', $url, [ 'kicon', 'wp-block-editor', 'wp-rich-text', 'kicon-search' ], $version, true );
		// Register Fontawesome 5
		list( $url, $version ) = $this->get_path_to_url( 'dist/css/all.min.css' );
		wp_register_style( 'fa5-all', $url, [], $version );
		// Register Editor CSS.
		list( $url, $version ) = $this->get_path_to_url( 'dist/css/icons.css' );
		wp_register_style( 'kicon', $url, [], $version );
		list( $url, $version ) = $this->get_path_to_url( 'dist/css/icons-block.css' );
		wp_register_style( 'kicon-block', $url, [ 'kicon' ], $version );
		list( $url, $version ) = $this->get_path_to_url( 'dist/css/icons-block-editor.css' );
		wp_register_style( 'kicon-block-editor', $url, apply_filters( 'kunoichi_icon_dependencies', [ 'kicon' ] ), $version );

	}

	/**
	 * Register icons if enqueued.
	 */
	public function register_icons() {
		if ( wp_script_is( 'kicon' ) ) {
			wp_localize_script( 'kicon', 'Kicon', [
				'icons' => apply_filters( 'kunoichi_icon_list', [] ),
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
	 * Enqueue inline icon assets.
	 */
	public function enqueue_block_editor_assets() {
		if ( ! apply_filters( 'kunoichi_icon_availables', [] ) ) {
			return;
		}
		wp_enqueue_script( 'kicon-inline' );
	}

	/**
	 * Get file url and version.
	 *
	 * @param string $rel_path
	 * @return string[]
	 */
	public function get_path_to_url( $rel_path ) {
		$base_dir = self::dir();
		$abs_path = $base_dir . '/' . ltrim( $rel_path, '/' );
		$url      = str_replace( ABSPATH, home_url( '/' ), $abs_path );
		$version  = null;
		if ( file_exists( $abs_path ) ) {
			$version = filemtime( $abs_path );
		}
		return [ $url, $version ];
	}

	/**
	 * Get root directory.
	 *
	 * @return string
	 */
	public static function dir() {
		return dirname( dirname( dirname( __DIR__ ) ) );
	}

	/**
	 * Get list of available icons.
	 *
	 * @return array
	 */
	public static function availables() {
		return apply_filters( 'kunoichi_icon_availables', [] );
	}

	/**
	 * Get icon list.
	 *
	 * @return array
	 */
	public static function icon_list() {
		return apply_filters( 'kunoichi_icon_list', [] );
	}

	/**
	 * Register instance.
	 *
	 * @param array $default_icons
	 * @apram bool $regiter_comand
	 */
	public static function register( $default_icons = [], $register_command = true ) {
		// Register commands.
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			\WP_CLI::add_command( Command::COMMAND_NAME, Command::class );
		}
		// Register default icons
		$default_icons = wp_parse_args( $default_icons, [
			'dashicons'   => true,
			'fa5-solid'   => true,
			'fa5-regular' => true,
			'fa5-brand'   => true,
		] );
		foreach ( $default_icons as $key => $enabled ) {
			if ( ! $enabled ) {
				continue;
			}
			switch ( $key ) {
				case 'dashicons':
					new Dashicons();
					break;
				case 'fa5-solid':
					new FontAwesomeSolid();
					break;
				case 'fa5-regular':
					new FontAwesomeRegular();
					break;
				case 'fa5-brand':
					new FontAwesomeBrand();
					break;
			}
		}
		$instance = static::get_instance();
	}
}
