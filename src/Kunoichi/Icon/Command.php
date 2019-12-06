<?php

namespace Kunoichi\Icon;

use cli\Table;

/**
 * Command Line Interface for icons.
 *
 * @package kicon
 */
class Command extends \WP_CLI_Command {

	const COMMAND_NAME = 'icons';

	/**
	 * List available command names.
	 *
	 * @param array $args
	 */
	public function availables( $args ) {
		$icons = Manager::icon_list();
		if ( ! $icons ) {
			\WP_CLI::error( __( 'No icon set found.', 'kicon' ) );
		}
		$table = new Table();
		$table->setHeaders( [ __( 'Icon Name', 'kicon' ), __( 'Font Family', 'kicon' ), __( 'Count', 'kicon' ) ] );
		foreach ( $icons as $name => $icon ) {
			$table->addRow( [ $name, $icon['font_family'], count( $icon['icons'] ) ] );
		}
		$table->display();
	}

	/**
	 * Display all icons.
	 *
	 * @synopsis <icon> [--term=<term>]
	 * @param array $args
	 * @param array $assoc
	 */
	public function detail( $args, $assoc ) {
		list( $icon_name ) = $args;
		$term = empty( $assoc['term'] ) ? '' : $assoc['term'];
		$icons = apply_filters( 'kunoichi_icon_list', [] );
		if ( ! $icons || ! isset( $icons[ $icon_name ] ) ) {
			\WP_CLI::error( __( 'No icon set found.', 'kicon' ) );
		}
		$icon_set = $icons[ $icon_name ];
		$count    = count( $icon_set['icons'] );
		\WP_CLI::line( sprintf( __( '%s has %s.', 'kicon' ), $icon_name, sprintf( _n( '%d icon', '%d icons', $count, 'kicon' ), $count ) ) );
		$table = new Table();
		$table->setHeaders( [ __( 'Name', 'kicon' ), __( 'Class Name', 'kicon' ), __( 'Glyph', 'kicon' ) ] );
		foreach ( $icon_set['icons'] as $icon ) {
			if ( $term && false === strpos( $icon['name'], $term ) ) {
				continue;
			}
			$table->addRow( [ $icon['name'], $icon['classes'], $icon['glyph'] ] );
		}
		$table->display();
	}

}
