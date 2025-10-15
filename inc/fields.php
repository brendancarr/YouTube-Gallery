<?php
/**
 * Field system class file.
 *
 * @package YouTube_Gallery
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Field system class.
 */
class YotuFields {

	/**
	 * Constructor.
	 */
	public function __construct() {

	}

	/**
	 * Render field.
	 */
	public function render_field( $data ) {

		ob_start();

		$data = apply_filters( 'yotuwp_before_render_field', $data );

		?>
		<div class="yotu-field yotu-field-type-<?php echo esc_attr( $data['type'] ); ?><?php echo ( isset( $data['pro'] ) ) ? ' yotu-field-pro' : ''; ?>" id="yotuwp-field-<?php echo esc_attr( $data['name'] ); ?>">
			<?php if ( isset( $data['label'] ) ) : ?>
				<label for="yotu-<?php echo esc_attr( $data['group'] . '-' . $data['name'] ); ?>"><?php echo esc_attr( $data['label'] ); ?></label>
			<?php endif; ?>
			<div class="yotu-field-input">
				<?php call_user_func_array( array( $this, $data['type'] ), array( $data ) ); ?>
				<?php do_action( 'yotuwp_after_render_field', $data ); ?>
				<label class="yotu-field-description" for="yotu-<?php echo esc_attr( $data['group'] . '-' . $data['name'] ); ?>"><?php echo esc_html( $data['description'] ); ?></label>
			</div>
			<?php

			if ( isset( $data['extbtn'] ) && $data['extbtn'] != '' ) {
				echo wp_kses_post( $data['extbtn'] );
			}

			?>
		</div>
		<?php

		$html = ob_get_contents();
		ob_end_clean();

		return $html;

	}

	/**
	 * Colour field.
	 */
	public function color( $data ) {

		$preview_css = isset( $data['preview_css'] ) ? $data['preview_css'] : $data['css'];
		$value       = ( isset( $data['value'] ) ? $data['value'] : $data['default'] );
		?>
		<input type="text" id="yotu-<?php echo esc_attr( $data['group'] . '-' . $data['name'] ); ?>" class="yotu-param yotu-colorpicker" name="yotu-<?php echo esc_attr( $data['group'] ); ?>[<?php echo esc_attr( $data['name'] ); ?>]" data-css="<?php echo esc_attr( $preview_css ); ?>" value="<?php echo esc_attr( $value ); ?>" />
		<?php

	}

	/**
	 * Text field.
	 */
	public function text( $data ) {

		?>
		<input type="text" id="yotu-<?php echo esc_attr( $data['group'] . '-' . $data['name'] ); ?>" class="yotu-param" name="yotu-<?php echo esc_attr( $data['group'] ); ?>[<?php echo esc_attr( $data['name'] ); ?>]" value="<?php echo esc_attr( ( isset( $data['value'] ) ? $data['value'] : $data['default'] ) ); ?>" />
		<?php

	}

	/**
	 * Select field.
	 */
	public function select( $data ) {

		$value = ( isset( $data['value'] ) && ! empty( $data['value'] ) ) ? $data['value'] : $data['default'];

		?>
		<select id="yotu-<?php echo esc_attr( $data['group'] . '-' . $data['name'] ); ?>" class="yotu-param" name="yotu-<?php echo esc_attr( $data['group'] ); ?>[<?php echo esc_attr( $data['name'] ); ?>]">
			<?php foreach ( $data['options'] as $key => $val ) { ?>
				<option value="<?php echo esc_attr( $key ); ?>"<?php echo ( $value == $key ) ? ' selected="selected"' : ''; ?>>
					<?php echo esc_html( $val ); ?>
				</option>
			<?php } ?>
		</select>
		<?php

		if ( isset( $data['extbtn'] ) && $data['extbtn'] != '' ) {
			echo wp_kses_post( $data['extbtn'] );
		}

	}

	/**
	 * Checkbox field.
	 */
	public function checkbox( $data ) {

		$value = ( isset( $data['value'] ) && ! empty( $data['value'] ) ) ? $data['value'] : $data['default'];

		foreach ( $data['options'] as $key => $val ) {

			$key_id = $data['group'] . '-' . $data['name'] . '-' . $key;
			$name   = $data['name'] . '|' . $key;

			?>
			<div class="yotuwp-field-checkbox-item">
				<input type="checkbox"<?php echo ( isset( $value[ $key ] ) && $value[ $key ] == 'on' ) ? ' checked="checked"' : ''; ?> id="yotuwp-<?php echo esc_attr( $key_id ); ?>" class="yotu-param" name="yotu-<?php echo esc_attr( $data['group'] ); ?>[<?php echo esc_attr( $name ); ?>]">
				<label for="yotuwp-<?php echo esc_attr( $key_id ); ?>"><?php echo esc_html( $val ); ?></label>
			</div>
			<?php

		}

	}

	/**
	 * Toggle field.
	 */
	public function toggle( $data ) {

		global $yotuwp;

		?>
		<label class="yotu-switch">
			<input type="checkbox" id="yotu-<?php echo esc_attr( $data['group'] . '-' . $data['name'] ); ?>" class="yotu-param" name="yotu-<?php echo esc_attr( $data['group'] ); ?>[<?php echo esc_attr( $data['name'] ); ?>]" <?php echo ( $data['value'] == 'on' ) ? 'checked="checked"' : ''; ?> />
			<span class="yotu-slider yotu-round"></span>
		</label>
		<?php

	}

	/**
	 * Radio button field.
	 */
	public function radios( $data ) {

		global $yotuwp;

		$value = ( isset( $data['value'] ) && ! empty( $data['value'] ) && isset( $data['options'][ $data['value'] ] ) ) ? $data['value'] : $data['default'];

		?>
		<div class="yotu-radios-img yotu-radios-img-<?php echo isset( $data['class'] ) ? esc_attr( $data['class'] ) : 'full'; ?>">
			<?php

			if ( $value != '' && isset( $data['options'][ $value ] ) ) {
				$temp = array( $value => $data['options'][ $value ] );
				unset( $data['options'][ $value ] );
				$data['options'] = $temp + $data['options'];
			}

			foreach ( $data['options'] as $key => $val ) {

				$id       = 'yotu-' . $data['group'] . '-' . $data['name'] . '-' . $key;
				$selected = ( $value == $key ) ? ' yotu-field-radios-selected' : '';

				?>
				<label class="yotu-field-radios<?php echo esc_attr( $selected ); ?>" for="<?php echo esc_attr( $id ); ?>">
					<input class="yotu-param" value="<?php echo esc_attr( $key ); ?>" type="radio"<?php echo ( $value == $key ) ? ' checked="checked"' : ''; ?> id="<?php echo esc_attr( $id ); ?>" name="yotu-<?php echo esc_attr( $data['group'] ); ?>[<?php echo esc_attr( $data['name'] ); ?>]" />

					<?php if ( ! empty( $val['img'] ) ) : ?>
						<?php $img_url = ( strpos( $val['img'], 'http' ) === false ) ? $yotuwp->assets_url . $val['img'] : $val['img']; ?>
						<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $val['title'] ); ?>" title="<?php echo esc_attr( $val['title'] ); ?>"/><br/>
					<?php else : ?>
						<div class="yotuwp-field-radios-text-option"><?php printf( esc_html__( '%s Settings', 'yotuwp-easy-youtube-embed' ), esc_html( $val['title'] ) ); ?></div>
					<?php endif; ?>

					<span><?php echo esc_html( $val['title'] ); ?></span>
				</label>
				<?php

			}

			?>
		</div>
		<?php

		if ( isset( $data['extbtn'] ) && $data['extbtn'] != '' ) {
			echo wp_kses_post( $data['extbtn'] );
		}

	}

	/**
	 * Buttons field.
	 */
	public function buttons( $data ) {

		global $yotuwp;

		$value = ( isset( $data['value'] ) && ! empty( $data['value'] ) ) ? $data['value'] : $data['default'];

		?>
		<div class="yotu-radios-img-buttons yotu-radios-img yotu-radios-img-<?php echo esc_attr( ( isset( $data['class'] ) ? $data['class'] : 'full' ) ); ?>">
			<?php

			for ( $i = 1; $i <= 4; $i++ ) {

				$id       = 'yotu-' . $data['group'] . '-' . $data['name'] . '-' . $i;
				$selected = ( $value == $i ) ? ' yotu-field-radios-selected' : '';

				?>
				<label class="yotu-field-radios<?php echo esc_attr( $selected ); ?>" for="<?php echo esc_attr( $id ); ?>">
					<input value="<?php echo esc_attr( $i ); ?>" type="radio"<?php echo ( $value == $i ) ? ' checked="checked"' : ''; ?> id="<?php echo esc_attr( $id ); ?>" name="yotu-<?php echo esc_attr( $data['group'] ); ?>[<?php echo esc_attr( $data['name'] ); ?>]" class="yotu-param" />
					<div>
						<a href="#" class="yotu-button-prs yotu-button-prs-<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Prev', 'yotuwp-easy-youtube-embed' ); ?></a>
						<a href="#" class="yotu-button-prs yotu-button-prs-<?php echo esc_attr( $i ); ?>"><?php esc_html_e( 'Next', 'yotuwp-easy-youtube-embed' ); ?></a>
					</div>
					<br/>
					<span><?php echo sprintf( esc_html__( 'Style %s', 'yotuwp-easy-youtube-embed' ), esc_html( $i ) ); ?></span>
				</label>
				<?php

			}

			?>
		</div>
		<?php

	}

	/**
	 * Icons field.
	 */
	public function icons( $data ) {

		global $yotuwp;

		$value = ( isset( $data['value'] ) && ! empty( $data['value'] ) ) ? $data['value'] : $data['default'];

		?>
		<div class="yotu-radios-img-buttons yotu-radios-img yotu-radios-img-<?php echo isset( $data['class'] ) ? esc_attr( $data['class'] ) : 'full'; ?>">
			<?php

			foreach ( $data['options'] as $key => $val ) {

				$id       = 'yotu-' . $data['group'] . '-' . $data['name'] . '-' . $key;
				$selected = ( $value == $key ) ? ' yotu-field-radios-selected' : '';

				?>
				<label class="yotu-field-radios<?php echo esc_attr( $selected ); ?>" for="<?php echo esc_attr( $id ); ?>">
					<input value="<?php echo esc_attr( $key ); ?>" type="radio"<?php echo ( $value == $key ) ? ' checked="checked"' : ''; ?> id="<?php echo esc_attr( $id ); ?>" name="yotu-<?php echo esc_attr( $data['group'] ); ?>[<?php echo esc_attr( $data['name'] ); ?>]" class="yotu-param" />
					<div>
						<i class="yotu-video-thumb-wrp yotuicon-<?php echo esc_attr( $key ); ?>"></i>
					</div>
					<br/>
					<span><?php echo esc_html( $val ); ?></span>
				</label>
				<?php

			}

			?>
		</div>
		<?php

	}

	/**
	 * Button field.
	 */
	public function button( $data ) {

		?>
		<a href="#" class="yotu-button yotu-button-s" data-func="<?php echo esc_attr( $data['func'] ); ?>"><?php echo esc_html( $data['btn-label'] ); ?></a>
		<?php

	}

	/**
	 * Effects field.
	 */
	public function effects( $data ) {

		$value   = ! empty( $data['value'] ) ? $data['value'] : $data['default'];
		$effects = array(
			array( '', 'None' ),
			array( 'ytef-grow', 'grow' ),
			array( 'ytef-float', 'float' ),
			array( 'ytef-rotate', 'Rotate' ),
			array( 'ytef-shadow-radial', 'shadow radial' ),
		);

		?>
		<div class="yotu-effects">
			<?php

			foreach ( $effects as $eff ) {

				$selected       = ( $eff[0] == $value ) ? true : false;
				$id             = 'yotu-' . $data['group'] . '-' . $data['name'] . '-' . $eff[0];
				$selected_class = $selected ? ' yotu-field-effects-selected' : '';
				$selected_attr  = ( $selected ) ? ' checked="checked"' : '';

				?>
				<label class="yotu-field-effects<?php echo esc_attr( $selected_class ); ?>" for="<?php echo esc_attr( $id ); ?>">
					<span class="<?php echo esc_attr( $eff[0] ); ?>"><?php echo esc_html( $eff[1] ); ?></span>
					<input class="yotu-param" value="<?php echo esc_attr( $eff[0] ); ?>" type="radio"<?php echo esc_attr( $selected_attr ); ?> id="<?php echo esc_attr( $id ); ?>" name="yotu-<?php echo esc_attr( $data['group'] ); ?>[<?php echo esc_attr( $data['name'] ); ?>]" />

				</label>
				<?php

			}

			?>
		</div>
		<?php

	}

}
