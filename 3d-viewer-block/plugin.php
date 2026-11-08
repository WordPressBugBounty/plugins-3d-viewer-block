<?php
/**
 * Plugin Name: 3D Viewer Block
 * Description: Display interactive 3D models on the web
 * Version: 1.2.0
 * Author: bPlugins
 * Author URI: https://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: 3d-viewer-block
 * Domain Path: /languages
 * Requires at least: 6.5
 * Tested up to: 7.1
 * Requires PHP: 7.4
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constants
if ( !defined( 'TDVB_VERSION' ) ) {
	define( 'TDVB_VERSION', ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : '1.2.0' );
}
if ( !defined( 'TDVB_DIR_URL' ) ) {
	define( 'TDVB_DIR_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'TDVB_DIR_PATH' ) ) {
	define( 'TDVB_DIR_PATH', plugin_dir_path( __FILE__ ) );
}

if ( !class_exists( 'TDVB3DPlugin' ) ) {
	include_once TDVB_DIR_PATH . 'includes/UploadMimes.php';

	class TDVB3DPlugin {
		/**
		 * Script module handle for the model-viewer library.
		 *
		 * Shared with the 3D Viewer and 3D Viewer Premium plugins on purpose. WordPress keeps the
		 * first registration of a handle, and enqueueBlockAssets() mirrors the host's library URL
		 * when a host is active, so the browser never evaluates model-viewer twice.
		 */
		const VIEWER_HANDLE = 'bp3d-lib-model-viewer';

		const BLOCK_NAME = 'tdvb/td-viewer';

		function __construct() {
			add_action( 'init', [ $this, 'onInit' ] );
			add_action( 'enqueue_block_assets', [ $this, 'enqueueBlockAssets' ] );
			add_filter( 'script_loader_tag', [ $this, 'moduleScriptTag' ], 10, 2 );
		}

		function onInit() {
			// A host plugin may have registered its embedded copy of this block already.
			if ( WP_Block_Type_Registry::get_instance()->is_registered( self::BLOCK_NAME ) ) {
				return;
			}
			register_block_type( TDVB_DIR_PATH . 'build' );

			// Translations shipped in the plugin (Freemius builds); wordpress.org installs are covered by WP itself.
			load_plugin_textdomain( '3d-viewer-block', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
			wp_set_script_translations( 'tdvb-td-viewer-editor-script', '3d-viewer-block', TDVB_DIR_PATH . 'languages' );
		}

		function enqueueBlockAssets() {
			$src = TDVB_DIR_URL . 'public/js/model-viewer.min.js';
			$ver = TDVB_VERSION;

			// A host plugin (3D Viewer / 3D Viewer Premium) also prints the library through a classic
			// <script type="module"> tag in the editor. Browsers only evaluate a module once per exact
			// URL, so when a host is present we point the shared handle at the host's file with the
			// host's version string: whichever plugin registers first, the page carries one URL.
			if ( defined( 'BP3D_DIR' ) && defined( 'BP3D_PATH' ) && defined( 'BP3D_VERSION' ) && file_exists( BP3D_PATH . 'public/js/model-viewer.latest.min.js' ) ) {
				$src = BP3D_DIR . 'public/js/model-viewer.latest.min.js';
				$ver = BP3D_VERSION;
			}

			wp_register_script_module( self::VIEWER_HANDLE, $src, [], $ver );

			// In the editor the library must reach the canvas iframe, which only receives classic
			// scripts enqueued on this hook, and WordPress prints script modules in the admin only
			// from 6.6. So the editor gets a classic tag, turned into type="module" by
			// moduleScriptTag(), under the same handle the hosts use. A host registers it first.
			if ( is_admin() ) {
				wp_register_script( self::VIEWER_HANDLE, $src, [], $ver, true );
				wp_enqueue_script( self::VIEWER_HANDLE );
			}
		}

		/**
		 * model-viewer is an ES module; the classic tag printed for the editor needs type="module".
		 * A host plugin filters the same handle and may already have rewritten the tag.
		 */
		function moduleScriptTag( $tag, $handle ) {
			if ( self::VIEWER_HANDLE !== $handle || false !== strpos( $tag, 'type="module"' ) ) {
				return $tag;
			}
			return str_replace( '<script ', '<script type="module" ', $tag );
		}
	}
	new TDVB3DPlugin;
}
