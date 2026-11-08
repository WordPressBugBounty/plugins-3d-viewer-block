<?php
/**
 * Front-end markup for the 3D Viewer block.
 *
 * The size, class and <model-viewer> attribute rules mirror src/utils/viewer.js (used by the
 * editor preview). Keep the two in sync.
 *
 * @var array $attributes
 */
if ( !defined( 'ABSPATH' ) ) { exit; }

if ( !function_exists( 'tdvb_controls_config' ) ) {
	/**
	 * Control button definitions from controls.json, read once per request however many blocks render.
	 *
	 * @return array
	 */
	function tdvb_controls_config() {
		static $controls = null;
		if ( null === $controls ) {
			$controls = json_decode( (string) file_get_contents( __DIR__ . '/controls.json' ), true );
			$controls = is_array( $controls ) ? $controls : [];
		}
		return $controls;
	}
}

// Attributes come from post content, which anyone who can edit the post can hand-craft, so every
// value is reduced to the shape the sidebar controls produce before it reaches the markup. Values
// the controls can produce pass through unchanged, so the output matches the editor preview.
$tdvb_str = static function ( $value ) {
	return is_scalar( $value ) ? trim( (string) $value ) : '';
};
// A CSS length as UnitControl writes it ("350px", "60%", "1.5em"); anything else is dropped.
$tdvb_css_size = static function ( $value ) use ( $tdvb_str ) {
	$value = $tdvb_str( $value );
	return preg_match( '/^\d*\.?\d+(px|%|em|rem|vw|vh|vmin|vmax|ch)?$/i', $value ) ? $value : '';
};
// A CSS color as ColorControl writes it (rgba()/hex/hsl(), a named color or a theme preset var()).
$tdvb_css_color = static function ( $value ) use ( $tdvb_str ) {
	$value = $tdvb_str( $value );
	return preg_match( '/^(#[0-9a-f]{3,8}|[a-z]+|(rgb|rgba|hsl|hsla)\([\d\s.,%\/]+\)|var\(--[\w-]+\))$/i', $value ) ? $value : '';
};
$tdvb_choice = static function ( $value, array $allowed, $fallback ) {
	return in_array( $value, $allowed, true ) ? $value : $fallback;
};
$tdvb_number = static function ( $value, $fallback ) {
	return is_numeric( $value ) ? $value + 0 : $fallback;
};
// Width/height fallback rule, same as sizeOrDefault() in viewer.js.
$tdvb_size = static function ( $value, $fallback ) use ( $tdvb_css_size ) {
	$value = $tdvb_css_size( $value );
	return intval( $value ) ? $value : $fallback;
};

$tdvb_src_type    = $attributes['sourceType'] ?? 'upload';
$tdvb_model_url   = $tdvb_str( 'upload' === $tdvb_src_type ? ( $attributes['model']['url'] ?? '' ) : ( $attributes['modelLink'] ?? '' ) );
$tdvb_model_title = 'upload' === $tdvb_src_type ? $tdvb_str( $attributes['model']['title'] ?? '' ) : '';
// model-viewer uses alt as the element's accessible name, so it is never left empty. Same rule as
// modelViewerAttributes()/nameFromUrl() in viewer.js: media title, else the file name, else "3D model".
$tdvb_file_name = trim( preg_replace( '/[-_]+/', ' ', preg_replace( '/\.[^.]+$/', '', rawurldecode( wp_basename( (string) wp_parse_url( $tdvb_model_url, PHP_URL_PATH ) ) ) ) ) );
$tdvb_alt       = $tdvb_model_title ?: ( $tdvb_file_name ?: __( '3D model', '3d-viewer-block' ) );
$tdvb_poster      = $tdvb_str( $attributes['poster']['url'] ?? '' );

// No model, no output: an empty viewer box (and a stuck loading overlay) helps nobody.
if ( '' === $tdvb_model_url ) {
	return;
}

// CSS custom properties on the viewer wrapper (same map as viewerVars() in JS).
$tdvb_vars = [
	'--tdvb-w' => $tdvb_size( $attributes['width'] ?? '100%', '100%' ),
	'--tdvb-h' => $tdvb_size( $attributes['height'] ?? '350px', '350px' ),
];
foreach ( [ 'widthTablet' => '--tdvb-w-tablet', 'heightTablet' => '--tdvb-h-tablet', 'widthMobile' => '--tdvb-w-mobile', 'heightMobile' => '--tdvb-h-mobile' ] as $tdvb_attr => $tdvb_var ) {
	$tdvb_value = $tdvb_css_size( $attributes[ $tdvb_attr ] ?? '' );
	if ( intval( $tdvb_value ) ) {
		$tdvb_vars[ $tdvb_var ] = $tdvb_value;
	}
}
foreach ( [ 'bgColor' => '--tdvb-bg', 'progressBarColor' => '--tdvb-progress' ] as $tdvb_attr => $tdvb_var ) {
	$tdvb_value = $tdvb_css_color( $attributes[ $tdvb_attr ] ?? '' );
	if ( '' !== $tdvb_value ) {
		$tdvb_vars[ $tdvb_var ] = $tdvb_value;
	}
}
$tdvb_style = '';
foreach ( $tdvb_vars as $tdvb_var => $tdvb_value ) {
	$tdvb_style .= $tdvb_var . ':' . $tdvb_value . ';';
}

// Which control buttons are on (same list as viewerClasses() in JS).
$tdvb_buttons = array_filter( [
	'fullscreenBtn' => !empty( $attributes['fullscreenBtn'] ),
	'zoomBtns'      => !empty( $attributes['zoomBtns'] ),
	'screenshotBtn' => !empty( $attributes['screenshotBtn'] ),
	'downloadBtn'   => !empty( $attributes['downloadBtn'] ),
] );

$tdvb_classes = [ 'tdvb3DViewerBlock' ];
if ( isset( $attributes['progressBar'] ) && !$attributes['progressBar'] ) {
	$tdvb_classes[] = 'tdvbNoProgress';
}
if ( $tdvb_buttons ) {
	$tdvb_classes[] = 'tdvbHasControls';
}

// <model-viewer> attributes (same map as modelViewerAttributes() in JS).
$tdvb_mv_attrs = [
	'src'              => $tdvb_model_url,
	'alt'              => $tdvb_alt,
	'poster'           => $tdvb_poster ?: null,
	'camera-controls'  => ( $attributes['isTouchMove'] ?? true ) ? '' : null,
	'disable-zoom'     => ( $attributes['isZoom'] ?? true ) ? null : '',
	'loading'          => $tdvb_choice( $attributes['loadingType'] ?? 'auto', [ 'auto', 'lazy', 'eager' ], 'auto' ),
	'auto-rotate'      => ( $attributes['autoRotate'] ?? true ) ? '' : null,
	'exposure'         => $tdvb_number( $attributes['exposure'] ?? 1, 1 ),
	'shadow-intensity' => $tdvb_number( $attributes['shadowIntensity'] ?? 0, 0 ),
];
$tdvb_mv_html = '';
foreach ( $tdvb_mv_attrs as $tdvb_name => $tdvb_value ) {
	if ( null === $tdvb_value ) {
		continue;
	}
	$tdvb_escaped = ( 'src' === $tdvb_name || 'poster' === $tdvb_name ) ? esc_url( $tdvb_value ) : esc_attr( $tdvb_value );
	$tdvb_mv_html .= '' === $tdvb_value ? ' ' . $tdvb_name : ' ' . $tdvb_name . '="' . $tdvb_escaped . '"';
}

$tdvb_labels = [
	'zoomIn'     => __( 'Zoom in', '3d-viewer-block' ),
	'zoomOut'    => __( 'Zoom out', '3d-viewer-block' ),
	'screenshot' => __( 'Take screenshot', '3d-viewer-block' ),
	'download'   => __( 'Download 3D model', '3d-viewer-block' ),
	'fullscreen' => __( 'Fullscreen', '3d-viewer-block' ),
];
$tdvb_svg_kses = [
	'svg'    => [ 'viewbox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'aria-hidden' => true, 'class' => true ],
	'path'   => [ 'd' => true ],
	'circle' => [ 'cx' => true, 'cy' => true, 'r' => true ],
];
$tdvb_controls = $tdvb_buttons ? tdvb_controls_config() : [];
// Same class list as controlsClasses() in JS.
$tdvb_controls_class = 'tdvbControls tdvbControls--' . $tdvb_choice( $attributes['controlsPosition'] ?? 'bottom-right', [ 'bottom-right', 'bottom-left', 'top-right', 'top-left' ], 'bottom-right' );

$tdvb_wrapper_attributes = get_block_wrapper_attributes( [
	'style' => 'text-align:' . $tdvb_choice( $attributes['alignment'] ?? 'center', [ 'left', 'center', 'right' ], 'center' ) . ';',
] );
?>
<div <?php echo $tdvb_wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="<?php echo esc_attr( implode( ' ', $tdvb_classes ) ); ?>" style="<?php echo esc_attr( $tdvb_style ); ?>">
		<model-viewer<?php echo $tdvb_mv_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped per attribute above ?>>
			<?php if ( !empty( $attributes['loadingPercentage'] ) ) : ?>
				<div slot="progress-bar" class="tdvbProgress" role="progressbar" aria-label="<?php esc_attr_e( 'Loading 3D model', '3d-viewer-block' ); ?>" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><span class="tdvbProgress__pct" aria-hidden="true">0%</span></div>
			<?php endif; ?>
		</model-viewer>

		<?php if ( $tdvb_controls ) : ?>
			<div class="<?php echo esc_attr( $tdvb_controls_class ); ?>" role="group" aria-label="<?php esc_attr_e( '3D viewer controls', '3d-viewer-block' ); ?>">
				<?php foreach ( $tdvb_controls as $tdvb_control ) :
					if ( empty( $tdvb_buttons[ $tdvb_control['attribute'] ] ) ) {
						continue;
					}
					$tdvb_label = $tdvb_labels[ $tdvb_control['key'] ] ?? $tdvb_control['key'];
					?>
					<button type="button" class="tdvbControls__btn" data-tdvb-action="<?php echo esc_attr( $tdvb_control['key'] ); ?>" aria-label="<?php echo esc_attr( $tdvb_label ); ?>" title="<?php echo esc_attr( $tdvb_label ); ?>"<?php echo empty( $tdvb_control['iconActive'] ) ? '' : ' aria-pressed="false"'; ?>>
						<span class="tdvbIcon"><?php echo wp_kses( $tdvb_control['icon'], $tdvb_svg_kses ); ?></span>
						<?php if ( !empty( $tdvb_control['iconActive'] ) ) : ?>
							<span class="tdvbIconActive"><?php echo wp_kses( $tdvb_control['iconActive'], $tdvb_svg_kses ); ?></span>
						<?php endif; ?>
					</button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<p class="tdvbLoadError" role="alert" hidden><?php esc_html_e( 'The 3D model could not be loaded.', '3d-viewer-block' ); ?></p>
	</div>
</div>
