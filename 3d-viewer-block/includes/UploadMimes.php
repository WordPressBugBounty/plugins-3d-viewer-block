<?php
namespace TDVB;

if ( !defined( 'ABSPATH' ) ) { exit; }

if( !class_exists( 'TDVBUploadMimes' ) ){
	class TDVBUploadMimes {
		public function __construct() {
			add_filter( 'upload_mimes', [$this, 'uploadMimes'] );
			add_filter( 'wp_check_filetype_and_ext', [$this, 'wpCheckFiletypeAndExt'], 10, 5 );
		}

	//Allow some additional file types for upload
	public function uploadMimes( $mimes ) {
		// New allowed mime types.
		$mimes['glb'] = 'model/gltf-binary';
		$mimes['gltf'] = 'model/gltf-binary';
		$mimes['bin'] = 'application/octet-stream';
		return $mimes;
	}

	public function wpCheckFiletypeAndExt( $data, $file, $filename, $mimes, $real_mime=null ){
		$ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );

		if( $ext == 'glb' || $ext == 'gltf' || $ext == 'bin' ){
			// Prevent malicious double extensions
			$is_malicious = false;
			foreach ( ['php', 'phtml', 'exe', 'sh', 'cgi', 'pl', 'py'] as $bad_ext ) {
				if ( strpos( strtolower( $filename ), '.' . $bad_ext . '.' ) !== false ) {
					$is_malicious = true;
					break;
				}
			}
			if ( $is_malicious ) {
				return $data;
			}

			// Validate file content to prevent masquerading
			$is_valid = false;
			if ( ! empty( $file ) && file_exists( $file ) ) {
				if ( $ext === 'glb' ) {
					// GLB files start with magic bytes "glTF"
					$magic = file_get_contents( $file, false, null, 0, 4 );
					if ( $magic === 'glTF' ) {
						$is_valid = true;
					}
				} elseif ( $ext === 'gltf' ) {
					// GLTF files are JSON, should start with '{'
					$content = file_get_contents( $file, false, null, 0, 100 );
					if ( $content !== false && strpos( ltrim( $content ), '{' ) === 0 ) {
						$is_valid = true;
					}
				} elseif ( $ext === 'bin' ) {
					$is_valid = true;
				}
			}

			if ( ! $is_valid ) {
				return $data;
			}

			if ( $ext === 'bin' ) {
				$type = 'application/octet-stream';
			} else {
				$type = 'model/gltf-binary';
			}
			$proper_filename = '';
			return compact('ext', 'type', 'proper_filename');
		}else {
			return $data;
		}
	}
	}
	new TDVBUploadMimes();
}
