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
class YotuFields{

    public function __construct()
    {

    }

    public function render_field($data ) {

		ob_start();

		$data = apply_filters('yotuwp_before_render_field', $data );

		?>
		<div class="yotu-field yotu-field-type-<?php esc_attr_e( $data['type'] ); echo ( isset($data['pro']) )? ' yotu-field-pro' :''?>" id="yotuwp-field-<?php esc_attr_e( $data['name'] );?>">
			<?php if( isset( $data['label'] ) ):?>
			<label for="yotu-<?php echo esc_attr($data['group']) . '-'. esc_attr($data['name']);?>"><?php esc_attr_e( $data['label'] );?></label>
			<?php endif;?>
			<div class="yotu-field-input">

				<?php call_user_func_array(array($this, $data['type']), array( $data ) );?>
				<?php do_action('yotuwp_after_render_field', $data );?>
				<label class="yotu-field-description" for="yotu-<?php echo esc_html($data['group']) . '-'. esc_attr($data['name']);?>"><?php echo esc_html_e($data['description']);?></label>
			</div>
			<?php
			if (isset($data['extbtn']) && $data['extbtn'] != '') {
				echo wp_kses_post($data['extbtn']);
			}
			?>
		</div>
		<?php

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}



    public function color( $data ) {
		$preview_css = isset($data['preview_css'])? $data['preview_css'] : $data['css'];
		$value = (isset( $data['value'] ) ? $data['value'] : $data['default']);
		?>
			<input type="text" id="yotu-<?php echo esc_attr( $data['group']) . '-'. esc_attr($data['name']);?>" class="yotu-param yotu-colorpicker" name="yotu-<?php echo esc_attr($data['group']);?>[<?php echo esc_attr($data['name']);?>]" data-css="<?php echo esc_attr( $preview_css );?>" value="<?php esc_attr_e( $value ) ?>" />
		<?php
		}

		public function text( $data ) {
		?>
			<input type="text" id="yotu-<?php echo esc_attr( $data['group']) . '-'. esc_attr($data['name']);?>" class="yotu-param" name="yotu-<?php echo esc_attr($data['group']);?>[<?php echo esc_attr($data['name']);?>]" value="<?php echo (isset( $data['value'] ) ? $data['value'] : $data['default']);?>" />
		<?php
	}


	public function select( $data ) {
		$value = (isset($data['value']) && !empty($data['value'])) ? $data['value'] : $data['default'];
		?>
		<select id="yotu-<?php echo esc_attr($data['group']) . '-'. esc_attr($data['name']);?>" class="yotu-param" name="yotu-<?php echo esc_attr($data['group']);?>[<?php echo esc_attr($data['name']);?>]">
			<?php
				foreach ($data['options'] as $key => $val) {
				?>
				<option value="<?php echo esc_attr($key);?>"<?php echo ($value == $key)? ' selected="selected"' : '';?>>
					<?php echo esc_attr($val);?>

				</option>
				<?php
				}
			?>
		</select>
		<?php
		if (isset($data['extbtn']) && $data['extbtn'] != '') {
			echo wp_kses_post($data['extbtn']);
		}

	}

	public function checkbox( $data ) {
		$value = (isset($data['value']) && !empty($data['value'])) ? $data['value'] : $data['default'];
		?>

			<?php
				foreach ($data['options'] as $key => $val) {
					$key_id = $data['group'] . '-'. $data['name'] .'-'. $key;
					$name = $data['name'] .'|'. $key;
				?>
				<div class="yotuwp-field-checkbox-item">
					<input type="checkbox"<?php echo (isset( $value[ $key ] ) && $value[ $key ] == 'on' )? ' checked="checked"' :'' ;?> id="yotuwp-<?php echo esc_attr( $key_id );?>" class="yotu-param" name="yotu-<?php echo esc_attr($data['group']);?>[<?php echo esc_attr( $name );?>]">
					<label for="yotuwp-<?php echo esc_attr( $key_id );?>"><?php echo esc_attr($val);?></label>
				</div>
				<?php
				}
			?>
		</select>
		<?php
	}

	public function toggle($data ) {
        global $yotuwp;
		?>
		<label class="yotu-switch">
			<input type="checkbox" id="yotu-<?php echo esc_attr($data['group']) . '-'. esc_attr($data['name']);?>" class="yotu-param" name="yotu-<?php echo esc_attr($data['group']);?>[<?php echo esc_attr($data['name']);?>]" <?php echo ($data['value'] == 'on' ) ? 'checked="checked"' : '';?> />
			<span class="yotu-slider yotu-round"></span>
		</label>
		<?php
	}

	public function radios( $data ) {
		global $yotuwp;

		$value = (isset($data['value']) && !empty($data['value']) && isset($data['options'][ $data['value'] ])) ? $data['value'] : $data['default'];

		?>
		<div class="yotu-radios-img yotu-radios-img-<?php echo isset($data['class'])? esc_attr( $data['class']):'full';?>">
			<?php

				if ( $value != '' && isset($data['options'][ $value ]) ) {
					$temp = array( $value => $data['options'][ $value ] );
					unset( $data['options'][$value] );
					$data['options'] = $temp + $data['options'];
				}

				foreach ($data['options'] as $key => $val) {
					$id       = 'yotu-' . esc_attr($data['group']) . '-'. esc_attr($data['name']) . '-'. $key;
					$selected = ($value == $key)? ' yotu-field-radios-selected' : '';
				?>
				<label class="yotu-field-radios<?php esc_attr_e( $selected );?>" for="<?php esc_attr_e($id);?>">
					<input class="yotu-param" value="<?php esc_attr_e( $key );?>" type="radio"<?php echo ($value == $key) ? ' checked="checked"' : '';?> id="<?php esc_attr_e( $id );?>" name="yotu-<?php esc_attr_e($data['group']);?>[<?php esc_attr_e($data['name']);?>]" />

					<?php if( !empty($val['img']) ) :
						$img_url = ( strpos($val['img'], 'http') === false )? $yotuwp->assets_url . $val['img'] : $val['img'];
					?>
						<img src="<?php esc_attr_e( $img_url );?>" alt="<?php esc_attr_e( $val['title'] );?>" title="<?php esc_attr_e( $val['title'] );?>"/><br/>
					<?php else:?>
						<div class="yotuwp-field-radios-text-option"><?php echo esc_attr( $val['title']  ). __(' Settings', 'yotuwp-easy-youtube-embed');?></div>
					<?php endif;?>

					<span><?php esc_html_e( $val['title'] );?></span>
				</label>
				<?php
				}
			?>
		</div>
		<?php
		if (isset($data['extbtn']) && $data['extbtn'] != '') {
			echo wp_kses_post($data['extbtn']);
		}

	}

	public function buttons($data ) {
        global $yotuwp;
		$value = (isset($data['value']) && !empty($data['value'])) ? $data['value'] : $data['default'];

		?>
		<div class="yotu-radios-img-buttons yotu-radios-img yotu-radios-img-<?php echo isset($data['class'])? $data['class']:'full';?>">
			<?php
				for ($i=1; $i<=4; $i++) {
					$id = 'yotu-' . esc_attr($data['group']) . '-'. esc_attr($data['name']) . '-'. $i;
					$selected = ($value == $i)? ' yotu-field-radios-selected' : ''
				?>
				<label class="yotu-field-radios<?php esc_attr_e( $selected );?>" for="<?php esc_attr_e( $id );?>">
					<input value="<?php esc_attr_e( $i );?>" type="radio"<?php echo ($value == $i) ? ' checked="checked"' : '';?> id="<?php esc_attr_e( $id );?>" name="yotu-<?php echo esc_attr($data['group']);?>[<?php echo esc_attr($data['name']);?>]" class="yotu-param" />
					<div>
						<a href="#" class="yotu-button-prs yotu-button-prs-<?php esc_attr_e( $i );?>"><?php echo __('Prev', 'yotuwp-easy-youtube-embed');?></a>
						<a href="#" class="yotu-button-prs yotu-button-prs-<?php esc_attr_e( $i );?>"><?php echo __('Next', 'yotuwp-easy-youtube-embed');?></a>
					</div>
					<br/>
					<span><?php echo sprintf( __('Style %s', 'yotuwp-easy-youtube-embed'), esc_html( $i ) );?></span>
				</label>
				<?php
				}
			?>
		</div>
		<?php
	}

	public function icons($data ) {
        global $yotuwp;
		$value = (isset($data['value']) && !empty($data['value'])) ? $data['value'] : $data['default'];
	?>
	<div class="yotu-radios-img-buttons yotu-radios-img yotu-radios-img-<?php echo isset($data['class'])? esc_attr( $data['class'] ):'full';?>">
		<?php
            foreach ( $data['options'] as $key => $val ) {
            	$id = 'yotu-' . esc_attr($data['group']) . '-'. esc_attr($data['name']) . '-'. $key;
            	$selected = ($value == $key)? ' yotu-field-radios-selected' : ''
            ?>
            <label class="yotu-field-radios<?php esc_attr_e( $selected ) ;?>" for="<?php esc_attr_e( $id );?>">
				<input value="<?php esc_attr_e( $key );?>" type="radio"<?php echo ($value == $key) ? ' checked="checked"' : '';?> id="<?php esc_attr_e( $id );?>" name="yotu-<?php echo esc_attr($data['group']);?>[<?php echo esc_attr($data['name']);?>]" class="yotu-param" />
				<div>
            		<i class="yotu-video-thumb-wrp yotuicon-<?php esc_attr_e( $key );?>"></i>
				</div>
                <br/>
                <span><?php echo sprintf( __('%s', 'yotuwp-easy-youtube-embed'),esc_html( $val ) );?></span>
            </label>
            <?php
            }
        ?>
	</div>
	<?php
	}

	public function button($data) {
		?>
		<a href="#" class="yotu-button yotu-button-s" data-func="<?php esc_attr_e( $data['func'] );?>"><?php esc_html_e(  $data['btn-label'] );?></a>
		<?php
	}

	public function effects($data ) {
		$value = (isset($data['value']) && !empty($data['value'])) ? $data['value'] : $data['default'];
		$effects = array(
			array('', 'None'),
			array('ytef-grow', 'grow'),
			array('ytef-float', 'float'),
			array('ytef-rotate', 'Rotate'),
			array('ytef-shadow-radial', 'shadow radial')
		);
		?>
		<div class="yotu-effects">
			<?php
				foreach ($effects as $eff) {
					$selected = ($eff[0] == $value)? true : false;
					$id       = 'yotu-' . esc_attr($data['group']) . '-'. esc_attr($data['name']) . '-'. $eff[0];
					$selected_class = $selected? ' yotu-field-effects-selected' :'';
					$selected_attr = ($selected) ? ' checked="checked"' : '';
				?>
					<label class="yotu-field-effects<?php echo esc_attr($selected_class) ;?>" for="<?php esc_attr_e( $id );?>">
						<span class="<?php esc_attr_e( $eff[0] );?>"><?php esc_html_e( $eff[1] );?></span>
						<input class="yotu-param" value="<?php esc_attr_e( $eff[0] );?>" type="radio"<?php esc_attr_e( $selected_attr ) ;?> id="<?php esc_attr_e( $id );?>" name="yotu-<?php echo esc_attr($data['group']);?>[<?php echo esc_attr($data['name']);?>]" />

					</label>
				<?php
				}
			?>
		</div>

		<?php
	}
}
