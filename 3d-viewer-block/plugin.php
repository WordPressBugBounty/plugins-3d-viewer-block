<?php
/**
 * Plugin Name: 3D Viewer Block
 * Description: Display interactive 3D models on the web
 * Version: 1.1.0
 * Author: bPlugins
 * Author URI: https://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: 3d-viewer
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
if ( !defined( 'BP3D_VERSION' ) ) {
	define( 'BP3D_VERSION', ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : '1.1.0' );
}
define( 'TDVB_DIR_URL', plugin_dir_url( __FILE__ ) );

if( !class_exists( 'TDVB3DPlugin' ) ){
	include_once 'includes/UploadMimes.php';

	class TDVB3DPlugin{
		function __construct(){
			add_action( 'init', [ $this, 'onInit' ] );
			add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
			add_action( 'enqueue_block_editor_assets', [$this, 'enqueueBlockEditorAssets'] );
		}

		function onInit(){
			register_block_type( __DIR__ . '/build' );
		}

		function enqueueBlockAssets(){
			wp_register_script_module( 'bp3d-model-viewer', TDVB_DIR_URL . 'public/js/model-viewer.min.js', [], BP3D_VERSION );
		}

		function enqueueBlockEditorAssets(){
			wp_enqueue_script_module( 'bp3d-model-viewer' );
		}
	}
	new TDVB3DPlugin;
}