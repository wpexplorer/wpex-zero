<?php
/**
 * HR control for the customizer
 *
 * @since Total 1.6.0
 */
class WPEX_HR_Control extends WP_Customize_Control {
	public $type = 'hr';
	public function render_content() {
		echo '<hr />';
	}
}

/**
 * Editor Class for the customizer
 *
 * @since Total 1.6.0
 */
class WPEX_Text_Editor_Control extends WP_Customize_Control {
	/**
	 * Render the content on the theme customizer page
	 */
	public function render_content() { ?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php $settings = array(
				'textarea_name'	=> $this->id,
				'media_buttons'	=> false,
				'teeny'			=> true,
			);
			wp_editor($this->value(), $this->id, $settings ); ?>
		</label>
	<?php
	}
}

/**
 * Multiple select customize control class.
 *
 * @since Total 1.6.0
 */
class WPEX_Customize_Control_Multiple_Select extends WP_Customize_Control {
	/**
	 * The type of customize control being rendered.
	 */
	public $type = 'multiple-select';

	/**
	 * Displays the multiple select on the customize screen.
	 */
	public function render_content() {
	if ( empty( $this->choices ) ) {
		return;
	}
	$this_val = $this->value(); ?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( '' != $this->description ) { ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php } ?>
			<select <?php $this->link(); ?> multiple="multiple" style="height:120px;">
				<?php foreach ( $this->choices as $value => $label ) {
					$selected = ( in_array( $value, $this_val ) ) ? selected( 1, 1, false ) : '';
					echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
				} ?>
			</select>
		</label>
	<?php }
}

/**
 * Slider UI control
 *
 * @since Total 1.6.0
 */
class WPEX_Customize_Sliderui_Control extends WP_Customize_Control {
	public function enqueue() {
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-slider' );
	}
	public function render_content() {
		$this_val = $this->value() ? $this->value() : '0'; ?>
		<label>
			<span class="customize-control-title">
				<?php echo esc_html( $this->label ); ?>
			</span>
			<?php if ( '' != $this->description ) { ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php } ?>
			 <input type="text" id="input_<?php echo $this->id; ?>" value="<?php echo $this_val; ?>" <?php $this->link(); ?>/>
		</label>
		<div id="slider_<?php echo $this->id; ?>" class="wpex-slider-ui"></div>
		<script>
			jQuery(document).ready(function($) {
				$( "#slider_<?php echo $this->id; ?>" ).slider({
					value : <?php echo $this_val; ?>,
					min   : <?php echo $this->choices['min']; ?>,
					max   : <?php echo $this->choices['max']; ?>,
					step  : <?php echo $this->choices['step']; ?>,
					slide : function( event, ui ) { $( "#input_<?php echo $this->id; ?>" ).val(ui.value).keyup(); }
				});
				$( "#input_<?php echo $this->id; ?>" ).val( $( "#slider_<?php echo $this->id; ?>" ).slider( "value" ) );
				$( "#input_<?php echo $this->id; ?>" ).keyup(function() {
					$( "#slider_<?php echo $this->id; ?>" ).slider( "value", $(this).val() );
				});
			});
		</script>
		<?php
	}
}

/**
 * Sorter Control
 *
 * @since Total 1.6.0
 */
class WPEX_Customize_Control_Sorter extends WP_Customize_Control {
public function enqueue() {
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-sortable' );
}
public function render_content() { ?>
	<div class="wpex-sortable">
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( '' != $this->description ) { ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php } ?>
		</label>
		<?php
		// Get values and choices
		$values		= $this->value();
		$choices	= $this->choices;
		// Turn values into array
		if ( ! is_array( $values ) ) {
			$values = explode( ',', $values );
		} ?>
		<ul id="<?php echo $this->id; ?>_sortable">
			<?php
			// Loop through values
			foreach ( $choices as $val => $label ) {
				$classes		= 'wpex-sortable-li';
				$hide_icon		= 'dashicons-no-alt';
				if ( ! in_array( $val, $values ) ) {
					$classes	.= ' wpex-hide';
					$hide_icon	= ' dashicons-yes';
				} ?>
				<li data-value="<?php echo esc_attr( $val ); ?>" class="<?php echo $classes; ?>">
					<?php echo $label; ?>
					<span class="wpex-hide-sortee dashicons <?php echo $hide_icon; ?>"></span>
				</li>
			<?php } ?>
		</ul>
	</div><!-- .wpex-sortable -->
	<div class="clear:both"></div>
	<?php
	// Return values as comma seperated string for input
	if ( is_array( $values ) ) {
		$values = array_keys( $values );
		$values = implode( ',', $values );
	} ?>
	<input id="<?php echo $this->id; ?>_input" type='hidden' name="<?php echo $this->id; ?>" value="<?php echo esc_attr( $values ); ?>" <?php echo $this->get_link(); ?> />
	<script>
	jQuery(document).ready(function($) {
		"use strict";
		// Define variables
		var sortableUl = $('#<?php echo $this->id; ?>_sortable');

		// Create sortable
		sortableUl.sortable()
		sortableUl.disableSelection();

		// Update values on sortstop
		sortableUl.on( "sortstop", function( event, ui ) {
			wpexUpdateSortableVal();
		});

		// Toggle classes
		sortableUl.find('li').each(function() {
			$(this).find('.wpex-hide-sortee').click(function() {
				$(this).toggleClass('dashicons-no-alt dashicons-yes').parents('li:eq(0)').toggleClass('wpex-hide');
			});
		})
		// Update Sortable when hidding/showing items
		$('#<?php echo $this->id; ?>_sortable span.wpex-hide-sortee').click(function() {
			wpexUpdateSortableVal();
		});
		// Used to update the sortable input value
		function wpexUpdateSortableVal() {
			var values = [];
			sortableUl.find('li').each(function() {
				if ( ! $(this).hasClass('wpex-hide') ) {
					values.push( $(this).attr('data-value') );
				}
			});
			$('#<?php echo $this->id; ?>_input').val(values).trigger('change');
		}
	});
	</script>
	<?php
	}
}

/**
 * Google Fonts Control
 *
 * @since Total 1.6.0
 */
class WPEX_Fonts_Dropdown_Control extends WP_Customize_Control {
	public function render_content() {
		$this_val = $this->value(); ?>
	<label>
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<select <?php $this->link(); ?>>
			<option value="" <?php if ( ! $this_val ) echo 'selected="selected"'; ?>><?php _e( 'Default', 'wpex-zero' ); ?></option>
			<?php
			// Add custom fonts from child themes
			if ( function_exists( 'wpex_add_custom_fonts' ) && wpex_add_custom_fonts() ) {
				$fonts = wpex_add_custom_fonts();
				if ( is_array( $fonts ) ) {
					foreach ( $fonts as $font ) { ?>
						<option value="<?php echo $font; ?>" <?php if ( $font == $this_val ) echo 'selected="selected"'; ?>><?php echo $font; ?></option>
					<?php }
				}
			} ?>
			<option value="Arial, Helvetica, sans-serif" <?php if ( "Arial, Helvetica, sans-serif" == $this_val ) echo 'selected="selected"'; ?>>Arial, Helvetica, sans-serif</option>
			<option value="Arial Black, Gadget, sans-serif" <?php if ( "Arial Black, Gadget, sans-serif" == $this_val ) echo 'selected="selected"'; ?>>Arial Black, Gadget, sans-serif</option>
			<option value="Bookman Old Style, serif" <?php if ( "Bookman Old Style, serif" == $this_val ) echo 'selected="selected"'; ?>>Bookman Old Style, serif</option>
			<option value="Comic Sans MS, cursive" <?php if ( "Comic Sans MS, cursive" == $this_val ) echo 'selected="selected"'; ?>>Comic Sans MS, cursive</option>
			<option value="Courier, monospace" <?php if ( "Courier, monospace" == $this_val ) echo 'selected="selected"'; ?>>Courier, monospace</option>
			<option value="Garamond, serif" <?php if ( "Garamond, serif" == $this_val ) echo 'selected="selected"'; ?>>Garamond, serif</option>
			<option value="Georgia, serif" <?php if ( "Georgia, serif" == $this_val ) echo 'selected="selected"'; ?>>Georgia, serif</option>
			<option value="Impact, Charcoal, sans-serif" <?php if ( "Impact, Charcoal, sans-serif" == $this_val ) echo 'selected="selected"'; ?>>Impact, Charcoal, sans-serif</option>
			<option value="Lucida Console, Monaco, monospace" <?php if ( "Lucida Console, Monaco, monospace" == $this_val ) echo 'selected="selected"'; ?>>Lucida Console, Monaco, monospace</option>
			<option value="Lucida Sans Unicode, Lucida Grande, sans-serif" <?php if ( "Lucida Sans Unicode, Lucida Grande, sans-serif" == $this_val ) echo 'selected="selected"'; ?>>Lucida Sans Unicode, Lucida Grande, sans-serif</option>
			<option value="MS Sans Serif, Geneva, sans-serif" <?php if ( "MS Sans Serif, Geneva, sans-serif" == $this_val ) echo 'selected="selected"'; ?>>MS Sans Serif, Geneva, sans-serif</option>
			<option value="MS Serif, New York, sans-serif" <?php if ( "MS Serif, New York, sans-serif" == $this_val ) echo 'selected="selected"'; ?>>MS Serif, New York, sans-serif</option>
			<option value="Palatino Linotype, 'Book Antiqua, Palatino, serif" <?php if ( "Palatino Linotype, 'Book Antiqua, Palatino, serif" == $this_val ) echo 'selected="selected"'; ?>>Palatino Linotype, 'Book Antiqua, Palatino, serif</option>
			<option value="Tahoma, Geneva, sans-serif" <?php if ( "Tahoma, Geneva, sans-serif" == $this_val ) echo 'selected="selected"'; ?>>Tahoma, Geneva, sans-serif</option>
			<option value="Times New Roman, Times, serif" <?php if ( "Times New Roman, Times, serif" == $this_val ) echo 'selected="selected"'; ?>>Times New Roman, Times, serif</option>
			<option value="Trebuchet MS, Helvetica, sans-serif" <?php if ( "Trebuchet MS, Helvetica, sans-serif" == $this_val ) echo 'selected="selected"'; ?>>Trebuchet MS, Helvetica, sans-serif</option>
			<option value="Verdana, Geneva, sans-serif" <?php if ( "Verdana, Geneva, sans-serif" == $this_val ) echo 'selected="selected"'; ?>>Verdana, Geneva, sans-serif</option>
			<option value="Garamond, serif" <?php if ( "Garamond, serif" == $this_val ) echo 'selected="selected"'; ?>>Garamond, serif</option>
			<option value="Bookman Old Style" <?php if ( "Bookman Old Style" == $this_val ) echo 'selected="selected"'; ?>>Bookman Old Style</option>
			<option value="Tahoma, Geneva" <?php if ( "Tahoma, Geneva" == $this_val ) echo 'selected="selected"'; ?>>Tahoma, Geneva</option>
			<option value="Verdana" <?php if ( "Verdana" == $this_val ) echo 'selected="selected"'; ?>>Verdana</option>
			<option value="Comic Sans" <?php if ( "Comic Sans" == $this_val ) echo 'selected="selected"'; ?>>Comic Sans</option>
			<option value="Courier, monospace" <?php if ( "Courier, monospace" == $this_val ) echo 'selected="selected"'; ?>>Courier, monospace</option>
			<option value="Comic Sans MS" <?php if ( "Comic Sans MS" == $this_val ) echo 'selected="selected"'; ?>>Comic Sans MS</option>
			<option value="Courier" <?php if ( "Courier" == $this_val ) echo 'selected="selected"'; ?>>Courier</option>
			<option value="Georgia" <?php if ( "Georgia" == $this_val ) echo 'selected="selected"'; ?>>Georgia</option>
			<option value="Paratina Linotype" <?php if ( "Paratina Linotype" == $this_val ) echo 'selected="selected"'; ?>>Paratina Linotype</option>
			<option value="Trebuchet MS" <?php if ( "Trebuchet MS" == $this_val ) echo 'selected="selected"'; ?>>Trebuchet MS</option>
			<option value="ABeeZee" <?php if ( "ABeeZee" == $this_val ) echo 'selected="selected"'; ?>>ABeeZee</option>
			<option value="Abel" <?php if ( "Abel" == $this_val ) echo 'selected="selected"'; ?>>Abel</option>
			<option value="Abril Fatface" <?php if ( "Abril Fatface" == $this_val ) echo 'selected="selected"'; ?>>Abril Fatface</option>
			<option value="Aclonica" <?php if ( "Aclonica" == $this_val ) echo 'selected="selected"'; ?>>Aclonica</option>
			<option value="Acme" <?php if ( "Acme" == $this_val ) echo 'selected="selected"'; ?>>Acme</option>
			<option value="Actor" <?php if ( "Actor" == $this_val ) echo 'selected="selected"'; ?>>Actor</option>
			<option value="Adamina" <?php if ( "Adamina" == $this_val ) echo 'selected="selected"'; ?>>Adamina</option>
			<option value="Advent Pro" <?php if ( "Advent Pro" == $this_val ) echo 'selected="selected"'; ?>>Advent Pro</option>
			<option value="Aguafina Script" <?php if ( "Aguafina Script" == $this_val ) echo 'selected="selected"'; ?>>Aguafina Script</option>
			<option value="Akronim" <?php if ( "Akronim" == $this_val ) echo 'selected="selected"'; ?>>Akronim</option>
			<option value="Aladin" <?php if ( "Aladin" == $this_val ) echo 'selected="selected"'; ?>>Aladin</option>
			<option value="Aldrich" <?php if ( "Aldrich" == $this_val ) echo 'selected="selected"'; ?>>Aldrich</option>
			<option value="Alef" <?php if ( "Alef" == $this_val ) echo 'selected="selected"'; ?>>Alef</option>
			<option value="Alegreya" <?php if ( "Alegreya" == $this_val ) echo 'selected="selected"'; ?>>Alegreya</option>
			<option value="Alegreya SC" <?php if ( "Alegreya SC" == $this_val ) echo 'selected="selected"'; ?>>Alegreya SC</option>
			<option value="Alegreya Sans" <?php if ( "Alegreya Sans" == $this_val ) echo 'selected="selected"'; ?>>Alegreya Sans</option>
			<option value="Alegreya Sans SC" <?php if ( "Alegreya Sans SC" == $this_val ) echo 'selected="selected"'; ?>>Alegreya Sans SC</option>
			<option value="Alex Brush" <?php if ( "Alex Brush" == $this_val ) echo 'selected="selected"'; ?>>Alex Brush</option>
			<option value="Alfa Slab One" <?php if ( "Alfa Slab One" == $this_val ) echo 'selected="selected"'; ?>>Alfa Slab One</option>
			<option value="Alice" <?php if ( "Alice" == $this_val ) echo 'selected="selected"'; ?>>Alice</option>
			<option value="Alike" <?php if ( "Alike" == $this_val ) echo 'selected="selected"'; ?>>Alike</option>
			<option value="Alike Angular" <?php if ( "Alike Angular" == $this_val ) echo 'selected="selected"'; ?>>Alike Angular</option>
			<option value="Allan" <?php if ( "Allan" == $this_val ) echo 'selected="selected"'; ?>>Allan</option>
			<option value="Allerta" <?php if ( "Allerta" == $this_val ) echo 'selected="selected"'; ?>>Allerta</option>
			<option value="Allerta Stencil" <?php if ( "Allerta Stencil" == $this_val ) echo 'selected="selected"'; ?>>Allerta Stencil</option>
			<option value="Allura" <?php if ( "Allura" == $this_val ) echo 'selected="selected"'; ?>>Allura</option>
			<option value="Almendra" <?php if ( "Almendra" == $this_val ) echo 'selected="selected"'; ?>>Almendra</option>
			<option value="Almendra Display" <?php if ( "Almendra Display" == $this_val ) echo 'selected="selected"'; ?>>Almendra Display</option>
			<option value="Almendra SC" <?php if ( "Almendra SC" == $this_val ) echo 'selected="selected"'; ?>>Almendra SC</option>
			<option value="Amarante" <?php if ( "Amarante" == $this_val ) echo 'selected="selected"'; ?>>Amarante</option>
			<option value="Amaranth" <?php if ( "Amaranth" == $this_val ) echo 'selected="selected"'; ?>>Amaranth</option>
			<option value="Amatic SC" <?php if ( "Amatic SC" == $this_val ) echo 'selected="selected"'; ?>>Amatic SC</option>
			<option value="Amethysta" <?php if ( "Amethysta" == $this_val ) echo 'selected="selected"'; ?>>Amethysta</option>
			<option value="Anaheim" <?php if ( "Anaheim" == $this_val ) echo 'selected="selected"'; ?>>Anaheim</option>
			<option value="Andada" <?php if ( "Andada" == $this_val ) echo 'selected="selected"'; ?>>Andada</option>
			<option value="Andika" <?php if ( "Andika" == $this_val ) echo 'selected="selected"'; ?>>Andika</option>
			<option value="Angkor" <?php if ( "Angkor" == $this_val ) echo 'selected="selected"'; ?>>Angkor</option>
			<option value="Annie Use Your Telescope" <?php if ( "Annie Use Your Telescope" == $this_val ) echo 'selected="selected"'; ?>>Annie Use Your Telescope</option>
			<option value="Anonymous Pro" <?php if ( "Anonymous Pro" == $this_val ) echo 'selected="selected"'; ?>>Anonymous Pro</option>
			<option value="Antic" <?php if ( "Antic" == $this_val ) echo 'selected="selected"'; ?>>Antic</option>
			<option value="Antic Didone" <?php if ( "Antic Didone" == $this_val ) echo 'selected="selected"'; ?>>Antic Didone</option>
			<option value="Antic Slab" <?php if ( "Antic Slab" == $this_val ) echo 'selected="selected"'; ?>>Antic Slab</option>
			<option value="Anton" <?php if ( "Anton" == $this_val ) echo 'selected="selected"'; ?>>Anton</option>
			<option value="Arapey" <?php if ( "Arapey" == $this_val ) echo 'selected="selected"'; ?>>Arapey</option>
			<option value="Arbutus" <?php if ( "Arbutus" == $this_val ) echo 'selected="selected"'; ?>>Arbutus</option>
			<option value="Arbutus Slab" <?php if ( "Arbutus Slab" == $this_val ) echo 'selected="selected"'; ?>>Arbutus Slab</option>
			<option value="Architects Daughter" <?php if ( "Architects Daughter" == $this_val ) echo 'selected="selected"'; ?>>Architects Daughter</option>
			<option value="Archivo Black" <?php if ( "Archivo Black" == $this_val ) echo 'selected="selected"'; ?>>Archivo Black</option>
			<option value="Archivo Narrow" <?php if ( "Archivo Narrow" == $this_val ) echo 'selected="selected"'; ?>>Archivo Narrow</option>
			<option value="Arimo" <?php if ( "Arimo" == $this_val ) echo 'selected="selected"'; ?>>Arimo</option>
			<option value="Arizonia" <?php if ( "Arizonia" == $this_val ) echo 'selected="selected"'; ?>>Arizonia</option>
			<option value="Armata" <?php if ( "Armata" == $this_val ) echo 'selected="selected"'; ?>>Armata</option>
			<option value="Artifika" <?php if ( "Artifika" == $this_val ) echo 'selected="selected"'; ?>>Artifika</option>
			<option value="Arvo" <?php if ( "Arvo" == $this_val ) echo 'selected="selected"'; ?>>Arvo</option>
			<option value="Asap" <?php if ( "Asap" == $this_val ) echo 'selected="selected"'; ?>>Asap</option>
			<option value="Asset" <?php if ( "Asset" == $this_val ) echo 'selected="selected"'; ?>>Asset</option>
			<option value="Astloch" <?php if ( "Astloch" == $this_val ) echo 'selected="selected"'; ?>>Astloch</option>
			<option value="Asul" <?php if ( "Asul" == $this_val ) echo 'selected="selected"'; ?>>Asul</option>
			<option value="Atomic Age" <?php if ( "Atomic Age" == $this_val ) echo 'selected="selected"'; ?>>Atomic Age</option>
			<option value="Aubrey" <?php if ( "Aubrey" == $this_val ) echo 'selected="selected"'; ?>>Aubrey</option>
			<option value="Audiowide" <?php if ( "Audiowide" == $this_val ) echo 'selected="selected"'; ?>>Audiowide</option>
			<option value="Autour One" <?php if ( "Autour One" == $this_val ) echo 'selected="selected"'; ?>>Autour One</option>
			<option value="Average" <?php if ( "Average" == $this_val ) echo 'selected="selected"'; ?>>Average</option>
			<option value="Average Sans" <?php if ( "Average Sans" == $this_val ) echo 'selected="selected"'; ?>>Average Sans</option>
			<option value="Averia Gruesa Libre" <?php if ( "Averia Gruesa Libre" == $this_val ) echo 'selected="selected"'; ?>>Averia Gruesa Libre</option>
			<option value="Averia Libre" <?php if ( "Averia Libre" == $this_val ) echo 'selected="selected"'; ?>>Averia Libre</option>
			<option value="Averia Sans Libre" <?php if ( "Averia Sans Libre" == $this_val ) echo 'selected="selected"'; ?>>Averia Sans Libre</option>
			<option value="Averia Serif Libre" <?php if ( "Averia Serif Libre" == $this_val ) echo 'selected="selected"'; ?>>Averia Serif Libre</option>
			<option value="Bad Script" <?php if ( "Bad Script" == $this_val ) echo 'selected="selected"'; ?>>Bad Script</option>
			<option value="Balthazar" <?php if ( "Balthazar" == $this_val ) echo 'selected="selected"'; ?>>Balthazar</option>
			<option value="Bangers" <?php if ( "Bangers" == $this_val ) echo 'selected="selected"'; ?>>Bangers</option>
			<option value="Basic" <?php if ( "Basic" == $this_val ) echo 'selected="selected"'; ?>>Basic</option>
			<option value="Battambang" <?php if ( "Battambang" == $this_val ) echo 'selected="selected"'; ?>>Battambang</option>
			<option value="Baumans" <?php if ( "Baumans" == $this_val ) echo 'selected="selected"'; ?>>Baumans</option>
			<option value="Bayon" <?php if ( "Bayon" == $this_val ) echo 'selected="selected"'; ?>>Bayon</option>
			<option value="Belgrano" <?php if ( "Belgrano" == $this_val ) echo 'selected="selected"'; ?>>Belgrano</option>
			<option value="Belleza" <?php if ( "Belleza" == $this_val ) echo 'selected="selected"'; ?>>Belleza</option>
			<option value="BenchNine" <?php if ( "BenchNine" == $this_val ) echo 'selected="selected"'; ?>>BenchNine</option>
			<option value="Bentham" <?php if ( "Bentham" == $this_val ) echo 'selected="selected"'; ?>>Bentham</option>
			<option value="Berkshire Swash" <?php if ( "Berkshire Swash" == $this_val ) echo 'selected="selected"'; ?>>Berkshire Swash</option>
			<option value="Bevan" <?php if ( "Bevan" == $this_val ) echo 'selected="selected"'; ?>>Bevan</option>
			<option value="Bigelow Rules" <?php if ( "Bigelow Rules" == $this_val ) echo 'selected="selected"'; ?>>Bigelow Rules</option>
			<option value="Bigshot One" <?php if ( "Bigshot One" == $this_val ) echo 'selected="selected"'; ?>>Bigshot One</option>
			<option value="Bilbo" <?php if ( "Bilbo" == $this_val ) echo 'selected="selected"'; ?>>Bilbo</option>
			<option value="Bilbo Swash Caps" <?php if ( "Bilbo Swash Caps" == $this_val ) echo 'selected="selected"'; ?>>Bilbo Swash Caps</option>
			<option value="Bitter" <?php if ( "Bitter" == $this_val ) echo 'selected="selected"'; ?>>Bitter</option>
			<option value="Black Ops One" <?php if ( "Black Ops One" == $this_val ) echo 'selected="selected"'; ?>>Black Ops One</option>
			<option value="Bokor" <?php if ( "Bokor" == $this_val ) echo 'selected="selected"'; ?>>Bokor</option>
			<option value="Bonbon" <?php if ( "Bonbon" == $this_val ) echo 'selected="selected"'; ?>>Bonbon</option>
			<option value="Boogaloo" <?php if ( "Boogaloo" == $this_val ) echo 'selected="selected"'; ?>>Boogaloo</option>
			<option value="Bowlby One" <?php if ( "Bowlby One" == $this_val ) echo 'selected="selected"'; ?>>Bowlby One</option>
			<option value="Bowlby One SC" <?php if ( "Bowlby One SC" == $this_val ) echo 'selected="selected"'; ?>>Bowlby One SC</option>
			<option value="Brawler" <?php if ( "Brawler" == $this_val ) echo 'selected="selected"'; ?>>Brawler</option>
			<option value="Bree Serif" <?php if ( "Bree Serif" == $this_val ) echo 'selected="selected"'; ?>>Bree Serif</option>
			<option value="Bubblegum Sans" <?php if ( "Bubblegum Sans" == $this_val ) echo 'selected="selected"'; ?>>Bubblegum Sans</option>
			<option value="Bubbler One" <?php if ( "Bubbler One" == $this_val ) echo 'selected="selected"'; ?>>Bubbler One</option>
			<option value="Buda" <?php if ( "Buda" == $this_val ) echo 'selected="selected"'; ?>>Buda</option>
			<option value="Buenard" <?php if ( "Buenard" == $this_val ) echo 'selected="selected"'; ?>>Buenard</option>
			<option value="Butcherman" <?php if ( "Butcherman" == $this_val ) echo 'selected="selected"'; ?>>Butcherman</option>
			<option value="Butterfly Kids" <?php if ( "Butterfly Kids" == $this_val ) echo 'selected="selected"'; ?>>Butterfly Kids</option>
			<option value="Cabin" <?php if ( "Cabin" == $this_val ) echo 'selected="selected"'; ?>>Cabin</option>
			<option value="Cabin Condensed" <?php if ( "Cabin Condensed" == $this_val ) echo 'selected="selected"'; ?>>Cabin Condensed</option>
			<option value="Cabin Sketch" <?php if ( "Cabin Sketch" == $this_val ) echo 'selected="selected"'; ?>>Cabin Sketch</option>
			<option value="Caesar Dressing" <?php if ( "Caesar Dressing" == $this_val ) echo 'selected="selected"'; ?>>Caesar Dressing</option>
			<option value="Cagliostro" <?php if ( "Cagliostro" == $this_val ) echo 'selected="selected"'; ?>>Cagliostro</option>
			<option value="Calligraffitti" <?php if ( "Calligraffitti" == $this_val ) echo 'selected="selected"'; ?>>Calligraffitti</option>
			<option value="Cambo" <?php if ( "Cambo" == $this_val ) echo 'selected="selected"'; ?>>Cambo</option>
			<option value="Candal" <?php if ( "Candal" == $this_val ) echo 'selected="selected"'; ?>>Candal</option>
			<option value="Cantarell" <?php if ( "Cantarell" == $this_val ) echo 'selected="selected"'; ?>>Cantarell</option>
			<option value="Cantata One" <?php if ( "Cantata One" == $this_val ) echo 'selected="selected"'; ?>>Cantata One</option>
			<option value="Cantora One" <?php if ( "Cantora One" == $this_val ) echo 'selected="selected"'; ?>>Cantora One</option>
			<option value="Capriola" <?php if ( "Capriola" == $this_val ) echo 'selected="selected"'; ?>>Capriola</option>
			<option value="Cardo" <?php if ( "Cardo" == $this_val ) echo 'selected="selected"'; ?>>Cardo</option>
			<option value="Carme" <?php if ( "Carme" == $this_val ) echo 'selected="selected"'; ?>>Carme</option>
			<option value="Carrois Gothic" <?php if ( "Carrois Gothic" == $this_val ) echo 'selected="selected"'; ?>>Carrois Gothic</option>
			<option value="Carrois Gothic SC" <?php if ( "Carrois Gothic SC" == $this_val ) echo 'selected="selected"'; ?>>Carrois Gothic SC</option>
			<option value="Carter One" <?php if ( "Carter One" == $this_val ) echo 'selected="selected"'; ?>>Carter One</option>
			<option value="Caudex" <?php if ( "Caudex" == $this_val ) echo 'selected="selected"'; ?>>Caudex</option>
			<option value="Cedarville Cursive" <?php if ( "Cedarville Cursive" == $this_val ) echo 'selected="selected"'; ?>>Cedarville Cursive</option>
			<option value="Ceviche One" <?php if ( "Ceviche One" == $this_val ) echo 'selected="selected"'; ?>>Ceviche One</option>
			<option value="Changa One" <?php if ( "Changa One" == $this_val ) echo 'selected="selected"'; ?>>Changa One</option>
			<option value="Chango" <?php if ( "Chango" == $this_val ) echo 'selected="selected"'; ?>>Chango</option>
			<option value="Chau Philomene One" <?php if ( "Chau Philomene One" == $this_val ) echo 'selected="selected"'; ?>>Chau Philomene One</option>
			<option value="Chela One" <?php if ( "Chela One" == $this_val ) echo 'selected="selected"'; ?>>Chela One</option>
			<option value="Chelsea Market" <?php if ( "Chelsea Market" == $this_val ) echo 'selected="selected"'; ?>>Chelsea Market</option>
			<option value="Chenla" <?php if ( "Chenla" == $this_val ) echo 'selected="selected"'; ?>>Chenla</option>
			<option value="Cherry Cream Soda" <?php if ( "Cherry Cream Soda" == $this_val ) echo 'selected="selected"'; ?>>Cherry Cream Soda</option>
			<option value="Cherry Swash" <?php if ( "Cherry Swash" == $this_val ) echo 'selected="selected"'; ?>>Cherry Swash</option>
			<option value="Chewy" <?php if ( "Chewy" == $this_val ) echo 'selected="selected"'; ?>>Chewy</option>
			<option value="Chicle" <?php if ( "Chicle" == $this_val ) echo 'selected="selected"'; ?>>Chicle</option>
			<option value="Chivo" <?php if ( "Chivo" == $this_val ) echo 'selected="selected"'; ?>>Chivo</option>
			<option value="Cinzel" <?php if ( "Cinzel" == $this_val ) echo 'selected="selected"'; ?>>Cinzel</option>
			<option value="Cinzel Decorative" <?php if ( "Cinzel Decorative" == $this_val ) echo 'selected="selected"'; ?>>Cinzel Decorative</option>
			<option value="Clicker Script" <?php if ( "Clicker Script" == $this_val ) echo 'selected="selected"'; ?>>Clicker Script</option>
			<option value="Coda" <?php if ( "Coda" == $this_val ) echo 'selected="selected"'; ?>>Coda</option>
			<option value="Coda Caption" <?php if ( "Coda Caption" == $this_val ) echo 'selected="selected"'; ?>>Coda Caption</option>
			<option value="Codystar" <?php if ( "Codystar" == $this_val ) echo 'selected="selected"'; ?>>Codystar</option>
			<option value="Combo" <?php if ( "Combo" == $this_val ) echo 'selected="selected"'; ?>>Combo</option>
			<option value="Comfortaa" <?php if ( "Comfortaa" == $this_val ) echo 'selected="selected"'; ?>>Comfortaa</option>
			<option value="Coming Soon" <?php if ( "Coming Soon" == $this_val ) echo 'selected="selected"'; ?>>Coming Soon</option>
			<option value="Concert One" <?php if ( "Concert One" == $this_val ) echo 'selected="selected"'; ?>>Concert One</option>
			<option value="Condiment" <?php if ( "Condiment" == $this_val ) echo 'selected="selected"'; ?>>Condiment</option>
			<option value="Content" <?php if ( "Content" == $this_val ) echo 'selected="selected"'; ?>>Content</option>
			<option value="Contrail One" <?php if ( "Contrail One" == $this_val ) echo 'selected="selected"'; ?>>Contrail One</option>
			<option value="Convergence" <?php if ( "Convergence" == $this_val ) echo 'selected="selected"'; ?>>Convergence</option>
			<option value="Cookie" <?php if ( "Cookie" == $this_val ) echo 'selected="selected"'; ?>>Cookie</option>
			<option value="Copse" <?php if ( "Copse" == $this_val ) echo 'selected="selected"'; ?>>Copse</option>
			<option value="Corben" <?php if ( "Corben" == $this_val ) echo 'selected="selected"'; ?>>Corben</option>
			<option value="Courgette" <?php if ( "Courgette" == $this_val ) echo 'selected="selected"'; ?>>Courgette</option>
			<option value="Cousine" <?php if ( "Cousine" == $this_val ) echo 'selected="selected"'; ?>>Cousine</option>
			<option value="Coustard" <?php if ( "Coustard" == $this_val ) echo 'selected="selected"'; ?>>Coustard</option>
			<option value="Covered By Your Grace" <?php if ( "Covered By Your Grace" == $this_val ) echo 'selected="selected"'; ?>>Covered By Your Grace</option>
			<option value="Crafty Girls" <?php if ( "Crafty Girls" == $this_val ) echo 'selected="selected"'; ?>>Crafty Girls</option>
			<option value="Creepster" <?php if ( "Creepster" == $this_val ) echo 'selected="selected"'; ?>>Creepster</option>
			<option value="Crete Round" <?php if ( "Crete Round" == $this_val ) echo 'selected="selected"'; ?>>Crete Round</option>
			<option value="Crimson Text" <?php if ( "Crimson Text" == $this_val ) echo 'selected="selected"'; ?>>Crimson Text</option>
			<option value="Croissant One" <?php if ( "Croissant One" == $this_val ) echo 'selected="selected"'; ?>>Croissant One</option>
			<option value="Crushed" <?php if ( "Crushed" == $this_val ) echo 'selected="selected"'; ?>>Crushed</option>
			<option value="Cuprum" <?php if ( "Cuprum" == $this_val ) echo 'selected="selected"'; ?>>Cuprum</option>
			<option value="Cutive" <?php if ( "Cutive" == $this_val ) echo 'selected="selected"'; ?>>Cutive</option>
			<option value="Cutive Mono" <?php if ( "Cutive Mono" == $this_val ) echo 'selected="selected"'; ?>>Cutive Mono</option>
			<option value="Damion" <?php if ( "Damion" == $this_val ) echo 'selected="selected"'; ?>>Damion</option>
			<option value="Dancing Script" <?php if ( "Dancing Script" == $this_val ) echo 'selected="selected"'; ?>>Dancing Script</option>
			<option value="Dangrek" <?php if ( "Dangrek" == $this_val ) echo 'selected="selected"'; ?>>Dangrek</option>
			<option value="Dawning of a New Day" <?php if ( "Dawning of a New Day" == $this_val ) echo 'selected="selected"'; ?>>Dawning of a New Day</option>
			<option value="Days One" <?php if ( "Days One" == $this_val ) echo 'selected="selected"'; ?>>Days One</option>
			<option value="Delius" <?php if ( "Delius" == $this_val ) echo 'selected="selected"'; ?>>Delius</option>
			<option value="Delius Swash Caps" <?php if ( "Delius Swash Caps" == $this_val ) echo 'selected="selected"'; ?>>Delius Swash Caps</option>
			<option value="Delius Unicase" <?php if ( "Delius Unicase" == $this_val ) echo 'selected="selected"'; ?>>Delius Unicase</option>
			<option value="Della Respira" <?php if ( "Della Respira" == $this_val ) echo 'selected="selected"'; ?>>Della Respira</option>
			<option value="Denk One" <?php if ( "Denk One" == $this_val ) echo 'selected="selected"'; ?>>Denk One</option>
			<option value="Devonshire" <?php if ( "Devonshire" == $this_val ) echo 'selected="selected"'; ?>>Devonshire</option>
			<option value="Didact Gothic" <?php if ( "Didact Gothic" == $this_val ) echo 'selected="selected"'; ?>>Didact Gothic</option>
			<option value="Diplomata" <?php if ( "Diplomata" == $this_val ) echo 'selected="selected"'; ?>>Diplomata</option>
			<option value="Diplomata SC" <?php if ( "Diplomata SC" == $this_val ) echo 'selected="selected"'; ?>>Diplomata SC</option>
			<option value="Domine" <?php if ( "Domine" == $this_val ) echo 'selected="selected"'; ?>>Domine</option>
			<option value="Donegal One" <?php if ( "Donegal One" == $this_val ) echo 'selected="selected"'; ?>>Donegal One</option>
			<option value="Doppio One" <?php if ( "Doppio One" == $this_val ) echo 'selected="selected"'; ?>>Doppio One</option>
			<option value="Dorsa" <?php if ( "Dorsa" == $this_val ) echo 'selected="selected"'; ?>>Dorsa</option>
			<option value="Dosis" <?php if ( "Dosis" == $this_val ) echo 'selected="selected"'; ?>>Dosis</option>
			<option value="Dr Sugiyama" <?php if ( "Dr Sugiyama" == $this_val ) echo 'selected="selected"'; ?>>Dr Sugiyama</option>
			<option value="Droid Sans" <?php if ( "Droid Sans" == $this_val ) echo 'selected="selected"'; ?>>Droid Sans</option>
			<option value="Droid Sans Mono" <?php if ( "Droid Sans Mono" == $this_val ) echo 'selected="selected"'; ?>>Droid Sans Mono</option>
			<option value="Droid Serif" <?php if ( "Droid Serif" == $this_val ) echo 'selected="selected"'; ?>>Droid Serif</option>
			<option value="Duru Sans" <?php if ( "Duru Sans" == $this_val ) echo 'selected="selected"'; ?>>Duru Sans</option>
			<option value="Dynalight" <?php if ( "Dynalight" == $this_val ) echo 'selected="selected"'; ?>>Dynalight</option>
			<option value="EB Garamond" <?php if ( "EB Garamond" == $this_val ) echo 'selected="selected"'; ?>>EB Garamond</option>
			<option value="Eagle Lake" <?php if ( "Eagle Lake" == $this_val ) echo 'selected="selected"'; ?>>Eagle Lake</option>
			<option value="Eater" <?php if ( "Eater" == $this_val ) echo 'selected="selected"'; ?>>Eater</option>
			<option value="Economica" <?php if ( "Economica" == $this_val ) echo 'selected="selected"'; ?>>Economica</option>
			<option value="Ek Mukta" <?php if ( "Ek Mukta" == $this_val ) echo 'selected="selected"'; ?>>Ek Mukta</option>
			<option value="Electrolize" <?php if ( "Electrolize" == $this_val ) echo 'selected="selected"'; ?>>Electrolize</option>
			<option value="Elsie" <?php if ( "Elsie" == $this_val ) echo 'selected="selected"'; ?>>Elsie</option>
			<option value="Elsie Swash Caps" <?php if ( "Elsie Swash Caps" == $this_val ) echo 'selected="selected"'; ?>>Elsie Swash Caps</option>
			<option value="Emblema One" <?php if ( "Emblema One" == $this_val ) echo 'selected="selected"'; ?>>Emblema One</option>
			<option value="Emilys Candy" <?php if ( "Emilys Candy" == $this_val ) echo 'selected="selected"'; ?>>Emilys Candy</option>
			<option value="Engagement" <?php if ( "Engagement" == $this_val ) echo 'selected="selected"'; ?>>Engagement</option>
			<option value="Englebert" <?php if ( "Englebert" == $this_val ) echo 'selected="selected"'; ?>>Englebert</option>
			<option value="Enriqueta" <?php if ( "Enriqueta" == $this_val ) echo 'selected="selected"'; ?>>Enriqueta</option>
			<option value="Erica One" <?php if ( "Erica One" == $this_val ) echo 'selected="selected"'; ?>>Erica One</option>
			<option value="Esteban" <?php if ( "Esteban" == $this_val ) echo 'selected="selected"'; ?>>Esteban</option>
			<option value="Euphoria Script" <?php if ( "Euphoria Script" == $this_val ) echo 'selected="selected"'; ?>>Euphoria Script</option>
			<option value="Ewert" <?php if ( "Ewert" == $this_val ) echo 'selected="selected"'; ?>>Ewert</option>
			<option value="Exo" <?php if ( "Exo" == $this_val ) echo 'selected="selected"'; ?>>Exo</option>
			<option value="Exo 2" <?php if ( "Exo 2" == $this_val ) echo 'selected="selected"'; ?>>Exo 2</option>
			<option value="Expletus Sans" <?php if ( "Expletus Sans" == $this_val ) echo 'selected="selected"'; ?>>Expletus Sans</option>
			<option value="Fanwood Text" <?php if ( "Fanwood Text" == $this_val ) echo 'selected="selected"'; ?>>Fanwood Text</option>
			<option value="Fascinate" <?php if ( "Fascinate" == $this_val ) echo 'selected="selected"'; ?>>Fascinate</option>
			<option value="Fascinate Inline" <?php if ( "Fascinate Inline" == $this_val ) echo 'selected="selected"'; ?>>Fascinate Inline</option>
			<option value="Faster One" <?php if ( "Faster One" == $this_val ) echo 'selected="selected"'; ?>>Faster One</option>
			<option value="Fasthand" <?php if ( "Fasthand" == $this_val ) echo 'selected="selected"'; ?>>Fasthand</option>
			<option value="Fauna One" <?php if ( "Fauna One" == $this_val ) echo 'selected="selected"'; ?>>Fauna One</option>
			<option value="Federant" <?php if ( "Federant" == $this_val ) echo 'selected="selected"'; ?>>Federant</option>
			<option value="Federo" <?php if ( "Federo" == $this_val ) echo 'selected="selected"'; ?>>Federo</option>
			<option value="Felipa" <?php if ( "Felipa" == $this_val ) echo 'selected="selected"'; ?>>Felipa</option>
			<option value="Fenix" <?php if ( "Fenix" == $this_val ) echo 'selected="selected"'; ?>>Fenix</option>
			<option value="Finger Paint" <?php if ( "Finger Paint" == $this_val ) echo 'selected="selected"'; ?>>Finger Paint</option>
			<option value="Fira Mono" <?php if ( "Fira Mono" == $this_val ) echo 'selected="selected"'; ?>>Fira Mono</option>
			<option value="Fira Sans" <?php if ( "Fira Sans" == $this_val ) echo 'selected="selected"'; ?>>Fira Sans</option>
			<option value="Fjalla One" <?php if ( "Fjalla One" == $this_val ) echo 'selected="selected"'; ?>>Fjalla One</option>
			<option value="Fjord One" <?php if ( "Fjord One" == $this_val ) echo 'selected="selected"'; ?>>Fjord One</option>
			<option value="Flamenco" <?php if ( "Flamenco" == $this_val ) echo 'selected="selected"'; ?>>Flamenco</option>
			<option value="Flavors" <?php if ( "Flavors" == $this_val ) echo 'selected="selected"'; ?>>Flavors</option>
			<option value="Fondamento" <?php if ( "Fondamento" == $this_val ) echo 'selected="selected"'; ?>>Fondamento</option>
			<option value="Fontdiner Swanky" <?php if ( "Fontdiner Swanky" == $this_val ) echo 'selected="selected"'; ?>>Fontdiner Swanky</option>
			<option value="Forum" <?php if ( "Forum" == $this_val ) echo 'selected="selected"'; ?>>Forum</option>
			<option value="Francois One" <?php if ( "Francois One" == $this_val ) echo 'selected="selected"'; ?>>Francois One</option>
			<option value="Freckle Face" <?php if ( "Freckle Face" == $this_val ) echo 'selected="selected"'; ?>>Freckle Face</option>
			<option value="Fredericka the Great" <?php if ( "Fredericka the Great" == $this_val ) echo 'selected="selected"'; ?>>Fredericka the Great</option>
			<option value="Fredoka One" <?php if ( "Fredoka One" == $this_val ) echo 'selected="selected"'; ?>>Fredoka One</option>
			<option value="Freehand" <?php if ( "Freehand" == $this_val ) echo 'selected="selected"'; ?>>Freehand</option>
			<option value="Fresca" <?php if ( "Fresca" == $this_val ) echo 'selected="selected"'; ?>>Fresca</option>
			<option value="Frijole" <?php if ( "Frijole" == $this_val ) echo 'selected="selected"'; ?>>Frijole</option>
			<option value="Fruktur" <?php if ( "Fruktur" == $this_val ) echo 'selected="selected"'; ?>>Fruktur</option>
			<option value="Fugaz One" <?php if ( "Fugaz One" == $this_val ) echo 'selected="selected"'; ?>>Fugaz One</option>
			<option value="GFS Didot" <?php if ( "GFS Didot" == $this_val ) echo 'selected="selected"'; ?>>GFS Didot</option>
			<option value="GFS Neohellenic" <?php if ( "GFS Neohellenic" == $this_val ) echo 'selected="selected"'; ?>>GFS Neohellenic</option>
			<option value="Gabriela" <?php if ( "Gabriela" == $this_val ) echo 'selected="selected"'; ?>>Gabriela</option>
			<option value="Gafata" <?php if ( "Gafata" == $this_val ) echo 'selected="selected"'; ?>>Gafata</option>
			<option value="Galdeano" <?php if ( "Galdeano" == $this_val ) echo 'selected="selected"'; ?>>Galdeano</option>
			<option value="Galindo" <?php if ( "Galindo" == $this_val ) echo 'selected="selected"'; ?>>Galindo</option>
			<option value="Gentium Basic" <?php if ( "Gentium Basic" == $this_val ) echo 'selected="selected"'; ?>>Gentium Basic</option>
			<option value="Gentium Book Basic" <?php if ( "Gentium Book Basic" == $this_val ) echo 'selected="selected"'; ?>>Gentium Book Basic</option>
			<option value="Geo" <?php if ( "Geo" == $this_val ) echo 'selected="selected"'; ?>>Geo</option>
			<option value="Geostar" <?php if ( "Geostar" == $this_val ) echo 'selected="selected"'; ?>>Geostar</option>
			<option value="Geostar Fill" <?php if ( "Geostar Fill" == $this_val ) echo 'selected="selected"'; ?>>Geostar Fill</option>
			<option value="Germania One" <?php if ( "Germania One" == $this_val ) echo 'selected="selected"'; ?>>Germania One</option>
			<option value="Gilda Display" <?php if ( "Gilda Display" == $this_val ) echo 'selected="selected"'; ?>>Gilda Display</option>
			<option value="Give You Glory" <?php if ( "Give You Glory" == $this_val ) echo 'selected="selected"'; ?>>Give You Glory</option>
			<option value="Glass Antiqua" <?php if ( "Glass Antiqua" == $this_val ) echo 'selected="selected"'; ?>>Glass Antiqua</option>
			<option value="Glegoo" <?php if ( "Glegoo" == $this_val ) echo 'selected="selected"'; ?>>Glegoo</option>
			<option value="Gloria Hallelujah" <?php if ( "Gloria Hallelujah" == $this_val ) echo 'selected="selected"'; ?>>Gloria Hallelujah</option>
			<option value="Goblin One" <?php if ( "Goblin One" == $this_val ) echo 'selected="selected"'; ?>>Goblin One</option>
			<option value="Gochi Hand" <?php if ( "Gochi Hand" == $this_val ) echo 'selected="selected"'; ?>>Gochi Hand</option>
			<option value="Gorditas" <?php if ( "Gorditas" == $this_val ) echo 'selected="selected"'; ?>>Gorditas</option>
			<option value="Goudy Bookletter 1911" <?php if ( "Goudy Bookletter 1911" == $this_val ) echo 'selected="selected"'; ?>>Goudy Bookletter 1911</option>
			<option value="Graduate" <?php if ( "Graduate" == $this_val ) echo 'selected="selected"'; ?>>Graduate</option>
			<option value="Grand Hotel" <?php if ( "Grand Hotel" == $this_val ) echo 'selected="selected"'; ?>>Grand Hotel</option>
			<option value="Gravitas One" <?php if ( "Gravitas One" == $this_val ) echo 'selected="selected"'; ?>>Gravitas One</option>
			<option value="Great Vibes" <?php if ( "Great Vibes" == $this_val ) echo 'selected="selected"'; ?>>Great Vibes</option>
			<option value="Griffy" <?php if ( "Griffy" == $this_val ) echo 'selected="selected"'; ?>>Griffy</option>
			<option value="Gruppo" <?php if ( "Gruppo" == $this_val ) echo 'selected="selected"'; ?>>Gruppo</option>
			<option value="Gudea" <?php if ( "Gudea" == $this_val ) echo 'selected="selected"'; ?>>Gudea</option>
			<option value="Habibi" <?php if ( "Habibi" == $this_val ) echo 'selected="selected"'; ?>>Habibi</option>
			<option value="Halant" <?php if ( "Halant" == $this_val ) echo 'selected="selected"'; ?>>Halant</option>
			<option value="Hammersmith One" <?php if ( "Hammersmith One" == $this_val ) echo 'selected="selected"'; ?>>Hammersmith One</option>
			<option value="Hanalei" <?php if ( "Hanalei" == $this_val ) echo 'selected="selected"'; ?>>Hanalei</option>
			<option value="Hanalei Fill" <?php if ( "Hanalei Fill" == $this_val ) echo 'selected="selected"'; ?>>Hanalei Fill</option>
			<option value="Handlee" <?php if ( "Handlee" == $this_val ) echo 'selected="selected"'; ?>>Handlee</option>
			<option value="Hanuman" <?php if ( "Hanuman" == $this_val ) echo 'selected="selected"'; ?>>Hanuman</option>
			<option value="Happy Monkey" <?php if ( "Happy Monkey" == $this_val ) echo 'selected="selected"'; ?>>Happy Monkey</option>
			<option value="Headland One" <?php if ( "Headland One" == $this_val ) echo 'selected="selected"'; ?>>Headland One</option>
			<option value="Henny Penny" <?php if ( "Henny Penny" == $this_val ) echo 'selected="selected"'; ?>>Henny Penny</option>
			<option value="Herr Von Muellerhoff" <?php if ( "Herr Von Muellerhoff" == $this_val ) echo 'selected="selected"'; ?>>Herr Von Muellerhoff</option>
			<option value="Hind" <?php if ( "Hind" == $this_val ) echo 'selected="selected"'; ?>>Hind</option>
			<option value="Holtwood One SC" <?php if ( "Holtwood One SC" == $this_val ) echo 'selected="selected"'; ?>>Holtwood One SC</option>
			<option value="Homemade Apple" <?php if ( "Homemade Apple" == $this_val ) echo 'selected="selected"'; ?>>Homemade Apple</option>
			<option value="Homenaje" <?php if ( "Homenaje" == $this_val ) echo 'selected="selected"'; ?>>Homenaje</option>
			<option value="IM Fell DW Pica" <?php if ( "IM Fell DW Pica" == $this_val ) echo 'selected="selected"'; ?>>IM Fell DW Pica</option>
			<option value="IM Fell DW Pica SC" <?php if ( "IM Fell DW Pica SC" == $this_val ) echo 'selected="selected"'; ?>>IM Fell DW Pica SC</option>
			<option value="IM Fell Double Pica" <?php if ( "IM Fell Double Pica" == $this_val ) echo 'selected="selected"'; ?>>IM Fell Double Pica</option>
			<option value="IM Fell Double Pica SC" <?php if ( "IM Fell Double Pica SC" == $this_val ) echo 'selected="selected"'; ?>>IM Fell Double Pica SC</option>
			<option value="IM Fell English" <?php if ( "IM Fell English" == $this_val ) echo 'selected="selected"'; ?>>IM Fell English</option>
			<option value="IM Fell English SC" <?php if ( "IM Fell English SC" == $this_val ) echo 'selected="selected"'; ?>>IM Fell English SC</option>
			<option value="IM Fell French Canon" <?php if ( "IM Fell French Canon" == $this_val ) echo 'selected="selected"'; ?>>IM Fell French Canon</option>
			<option value="IM Fell French Canon SC" <?php if ( "IM Fell French Canon SC" == $this_val ) echo 'selected="selected"'; ?>>IM Fell French Canon SC</option>
			<option value="IM Fell Great Primer" <?php if ( "IM Fell Great Primer" == $this_val ) echo 'selected="selected"'; ?>>IM Fell Great Primer</option>
			<option value="IM Fell Great Primer SC" <?php if ( "IM Fell Great Primer SC" == $this_val ) echo 'selected="selected"'; ?>>IM Fell Great Primer SC</option>
			<option value="Iceberg" <?php if ( "Iceberg" == $this_val ) echo 'selected="selected"'; ?>>Iceberg</option>
			<option value="Iceland" <?php if ( "Iceland" == $this_val ) echo 'selected="selected"'; ?>>Iceland</option>
			<option value="Imprima" <?php if ( "Imprima" == $this_val ) echo 'selected="selected"'; ?>>Imprima</option>
			<option value="Inconsolata" <?php if ( "Inconsolata" == $this_val ) echo 'selected="selected"'; ?>>Inconsolata</option>
			<option value="Inder" <?php if ( "Inder" == $this_val ) echo 'selected="selected"'; ?>>Inder</option>
			<option value="Indie Flower" <?php if ( "Indie Flower" == $this_val ) echo 'selected="selected"'; ?>>Indie Flower</option>
			<option value="Inika" <?php if ( "Inika" == $this_val ) echo 'selected="selected"'; ?>>Inika</option>
			<option value="Irish Grover" <?php if ( "Irish Grover" == $this_val ) echo 'selected="selected"'; ?>>Irish Grover</option>
			<option value="Istok Web" <?php if ( "Istok Web" == $this_val ) echo 'selected="selected"'; ?>>Istok Web</option>
			<option value="Italiana" <?php if ( "Italiana" == $this_val ) echo 'selected="selected"'; ?>>Italiana</option>
			<option value="Italianno" <?php if ( "Italianno" == $this_val ) echo 'selected="selected"'; ?>>Italianno</option>
			<option value="Jacques Francois" <?php if ( "Jacques Francois" == $this_val ) echo 'selected="selected"'; ?>>Jacques Francois</option>
			<option value="Jacques Francois Shadow" <?php if ( "Jacques Francois Shadow" == $this_val ) echo 'selected="selected"'; ?>>Jacques Francois Shadow</option>
			<option value="Jim Nightshade" <?php if ( "Jim Nightshade" == $this_val ) echo 'selected="selected"'; ?>>Jim Nightshade</option>
			<option value="Jockey One" <?php if ( "Jockey One" == $this_val ) echo 'selected="selected"'; ?>>Jockey One</option>
			<option value="Jolly Lodger" <?php if ( "Jolly Lodger" == $this_val ) echo 'selected="selected"'; ?>>Jolly Lodger</option>
			<option value="Josefin Sans" <?php if ( "Josefin Sans" == $this_val ) echo 'selected="selected"'; ?>>Josefin Sans</option>
			<option value="Josefin Slab" <?php if ( "Josefin Slab" == $this_val ) echo 'selected="selected"'; ?>>Josefin Slab</option>
			<option value="Joti One" <?php if ( "Joti One" == $this_val ) echo 'selected="selected"'; ?>>Joti One</option>
			<option value="Judson" <?php if ( "Judson" == $this_val ) echo 'selected="selected"'; ?>>Judson</option>
			<option value="Julee" <?php if ( "Julee" == $this_val ) echo 'selected="selected"'; ?>>Julee</option>
			<option value="Julius Sans One" <?php if ( "Julius Sans One" == $this_val ) echo 'selected="selected"'; ?>>Julius Sans One</option>
			<option value="Junge" <?php if ( "Junge" == $this_val ) echo 'selected="selected"'; ?>>Junge</option>
			<option value="Jura" <?php if ( "Jura" == $this_val ) echo 'selected="selected"'; ?>>Jura</option>
			<option value="Just Another Hand" <?php if ( "Just Another Hand" == $this_val ) echo 'selected="selected"'; ?>>Just Another Hand</option>
			<option value="Just Me Again Down Here" <?php if ( "Just Me Again Down Here" == $this_val ) echo 'selected="selected"'; ?>>Just Me Again Down Here</option>
			<option value="Kalam" <?php if ( "Kalam" == $this_val ) echo 'selected="selected"'; ?>>Kalam</option>
			<option value="Kameron" <?php if ( "Kameron" == $this_val ) echo 'selected="selected"'; ?>>Kameron</option>
			<option value="Kantumruy" <?php if ( "Kantumruy" == $this_val ) echo 'selected="selected"'; ?>>Kantumruy</option>
			<option value="Karla" <?php if ( "Karla" == $this_val ) echo 'selected="selected"'; ?>>Karla</option>
			<option value="Karma" <?php if ( "Karma" == $this_val ) echo 'selected="selected"'; ?>>Karma</option>
			<option value="Kaushan Script" <?php if ( "Kaushan Script" == $this_val ) echo 'selected="selected"'; ?>>Kaushan Script</option>
			<option value="Kavoon" <?php if ( "Kavoon" == $this_val ) echo 'selected="selected"'; ?>>Kavoon</option>
			<option value="Kdam Thmor" <?php if ( "Kdam Thmor" == $this_val ) echo 'selected="selected"'; ?>>Kdam Thmor</option>
			<option value="Keania One" <?php if ( "Keania One" == $this_val ) echo 'selected="selected"'; ?>>Keania One</option>
			<option value="Kelly Slab" <?php if ( "Kelly Slab" == $this_val ) echo 'selected="selected"'; ?>>Kelly Slab</option>
			<option value="Kenia" <?php if ( "Kenia" == $this_val ) echo 'selected="selected"'; ?>>Kenia</option>
			<option value="Khand" <?php if ( "Khand" == $this_val ) echo 'selected="selected"'; ?>>Khand</option>
			<option value="Khmer" <?php if ( "Khmer" == $this_val ) echo 'selected="selected"'; ?>>Khmer</option>
			<option value="Kite One" <?php if ( "Kite One" == $this_val ) echo 'selected="selected"'; ?>>Kite One</option>
			<option value="Knewave" <?php if ( "Knewave" == $this_val ) echo 'selected="selected"'; ?>>Knewave</option>
			<option value="Kotta One" <?php if ( "Kotta One" == $this_val ) echo 'selected="selected"'; ?>>Kotta One</option>
			<option value="Koulen" <?php if ( "Koulen" == $this_val ) echo 'selected="selected"'; ?>>Koulen</option>
			<option value="Kranky" <?php if ( "Kranky" == $this_val ) echo 'selected="selected"'; ?>>Kranky</option>
			<option value="Kreon" <?php if ( "Kreon" == $this_val ) echo 'selected="selected"'; ?>>Kreon</option>
			<option value="Kristi" <?php if ( "Kristi" == $this_val ) echo 'selected="selected"'; ?>>Kristi</option>
			<option value="Krona One" <?php if ( "Krona One" == $this_val ) echo 'selected="selected"'; ?>>Krona One</option>
			<option value="La Belle Aurore" <?php if ( "La Belle Aurore" == $this_val ) echo 'selected="selected"'; ?>>La Belle Aurore</option>
			<option value="Laila" <?php if ( "Laila" == $this_val ) echo 'selected="selected"'; ?>>Laila</option>
			<option value="Lancelot" <?php if ( "Lancelot" == $this_val ) echo 'selected="selected"'; ?>>Lancelot</option>
			<option value="Lato" <?php if ( "Lato" == $this_val ) echo 'selected="selected"'; ?>>Lato</option>
			<option value="League Script" <?php if ( "League Script" == $this_val ) echo 'selected="selected"'; ?>>League Script</option>
			<option value="Leckerli One" <?php if ( "Leckerli One" == $this_val ) echo 'selected="selected"'; ?>>Leckerli One</option>
			<option value="Ledger" <?php if ( "Ledger" == $this_val ) echo 'selected="selected"'; ?>>Ledger</option>
			<option value="Lekton" <?php if ( "Lekton" == $this_val ) echo 'selected="selected"'; ?>>Lekton</option>
			<option value="Lemon" <?php if ( "Lemon" == $this_val ) echo 'selected="selected"'; ?>>Lemon</option>
			<option value="Libre Baskerville" <?php if ( "Libre Baskerville" == $this_val ) echo 'selected="selected"'; ?>>Libre Baskerville</option>
			<option value="Life Savers" <?php if ( "Life Savers" == $this_val ) echo 'selected="selected"'; ?>>Life Savers</option>
			<option value="Lilita One" <?php if ( "Lilita One" == $this_val ) echo 'selected="selected"'; ?>>Lilita One</option>
			<option value="Lily Script One" <?php if ( "Lily Script One" == $this_val ) echo 'selected="selected"'; ?>>Lily Script One</option>
			<option value="Limelight" <?php if ( "Limelight" == $this_val ) echo 'selected="selected"'; ?>>Limelight</option>
			<option value="Linden Hill" <?php if ( "Linden Hill" == $this_val ) echo 'selected="selected"'; ?>>Linden Hill</option>
			<option value="Lobster" <?php if ( "Lobster" == $this_val ) echo 'selected="selected"'; ?>>Lobster</option>
			<option value="Lobster Two" <?php if ( "Lobster Two" == $this_val ) echo 'selected="selected"'; ?>>Lobster Two</option>
			<option value="Londrina Outline" <?php if ( "Londrina Outline" == $this_val ) echo 'selected="selected"'; ?>>Londrina Outline</option>
			<option value="Londrina Shadow" <?php if ( "Londrina Shadow" == $this_val ) echo 'selected="selected"'; ?>>Londrina Shadow</option>
			<option value="Londrina Sketch" <?php if ( "Londrina Sketch" == $this_val ) echo 'selected="selected"'; ?>>Londrina Sketch</option>
			<option value="Londrina Solid" <?php if ( "Londrina Solid" == $this_val ) echo 'selected="selected"'; ?>>Londrina Solid</option>
			<option value="Lora" <?php if ( "Lora" == $this_val ) echo 'selected="selected"'; ?>>Lora</option>
			<option value="Love Ya Like A Sister" <?php if ( "Love Ya Like A Sister" == $this_val ) echo 'selected="selected"'; ?>>Love Ya Like A Sister</option>
			<option value="Loved by the King" <?php if ( "Loved by the King" == $this_val ) echo 'selected="selected"'; ?>>Loved by the King</option>
			<option value="Lovers Quarrel" <?php if ( "Lovers Quarrel" == $this_val ) echo 'selected="selected"'; ?>>Lovers Quarrel</option>
			<option value="Luckiest Guy" <?php if ( "Luckiest Guy" == $this_val ) echo 'selected="selected"'; ?>>Luckiest Guy</option>
			<option value="Lusitana" <?php if ( "Lusitana" == $this_val ) echo 'selected="selected"'; ?>>Lusitana</option>
			<option value="Lustria" <?php if ( "Lustria" == $this_val ) echo 'selected="selected"'; ?>>Lustria</option>
			<option value="Macondo" <?php if ( "Macondo" == $this_val ) echo 'selected="selected"'; ?>>Macondo</option>
			<option value="Macondo Swash Caps" <?php if ( "Macondo Swash Caps" == $this_val ) echo 'selected="selected"'; ?>>Macondo Swash Caps</option>
			<option value="Magra" <?php if ( "Magra" == $this_val ) echo 'selected="selected"'; ?>>Magra</option>
			<option value="Maiden Orange" <?php if ( "Maiden Orange" == $this_val ) echo 'selected="selected"'; ?>>Maiden Orange</option>
			<option value="Mako" <?php if ( "Mako" == $this_val ) echo 'selected="selected"'; ?>>Mako</option>
			<option value="Marcellus" <?php if ( "Marcellus" == $this_val ) echo 'selected="selected"'; ?>>Marcellus</option>
			<option value="Marcellus SC" <?php if ( "Marcellus SC" == $this_val ) echo 'selected="selected"'; ?>>Marcellus SC</option>
			<option value="Marck Script" <?php if ( "Marck Script" == $this_val ) echo 'selected="selected"'; ?>>Marck Script</option>
			<option value="Margarine" <?php if ( "Margarine" == $this_val ) echo 'selected="selected"'; ?>>Margarine</option>
			<option value="Marko One" <?php if ( "Marko One" == $this_val ) echo 'selected="selected"'; ?>>Marko One</option>
			<option value="Marmelad" <?php if ( "Marmelad" == $this_val ) echo 'selected="selected"'; ?>>Marmelad</option>
			<option value="Marvel" <?php if ( "Marvel" == $this_val ) echo 'selected="selected"'; ?>>Marvel</option>
			<option value="Mate" <?php if ( "Mate" == $this_val ) echo 'selected="selected"'; ?>>Mate</option>
			<option value="Mate SC" <?php if ( "Mate SC" == $this_val ) echo 'selected="selected"'; ?>>Mate SC</option>
			<option value="Maven Pro" <?php if ( "Maven Pro" == $this_val ) echo 'selected="selected"'; ?>>Maven Pro</option>
			<option value="McLaren" <?php if ( "McLaren" == $this_val ) echo 'selected="selected"'; ?>>McLaren</option>
			<option value="Meddon" <?php if ( "Meddon" == $this_val ) echo 'selected="selected"'; ?>>Meddon</option>
			<option value="MedievalSharp" <?php if ( "MedievalSharp" == $this_val ) echo 'selected="selected"'; ?>>MedievalSharp</option>
			<option value="Medula One" <?php if ( "Medula One" == $this_val ) echo 'selected="selected"'; ?>>Medula One</option>
			<option value="Megrim" <?php if ( "Megrim" == $this_val ) echo 'selected="selected"'; ?>>Megrim</option>
			<option value="Meie Script" <?php if ( "Meie Script" == $this_val ) echo 'selected="selected"'; ?>>Meie Script</option>
			<option value="Merienda" <?php if ( "Merienda" == $this_val ) echo 'selected="selected"'; ?>>Merienda</option>
			<option value="Merienda One" <?php if ( "Merienda One" == $this_val ) echo 'selected="selected"'; ?>>Merienda One</option>
			<option value="Merriweather" <?php if ( "Merriweather" == $this_val ) echo 'selected="selected"'; ?>>Merriweather</option>
			<option value="Merriweather Sans" <?php if ( "Merriweather Sans" == $this_val ) echo 'selected="selected"'; ?>>Merriweather Sans</option>
			<option value="Metal" <?php if ( "Metal" == $this_val ) echo 'selected="selected"'; ?>>Metal</option>
			<option value="Metal Mania" <?php if ( "Metal Mania" == $this_val ) echo 'selected="selected"'; ?>>Metal Mania</option>
			<option value="Metamorphous" <?php if ( "Metamorphous" == $this_val ) echo 'selected="selected"'; ?>>Metamorphous</option>
			<option value="Metrophobic" <?php if ( "Metrophobic" == $this_val ) echo 'selected="selected"'; ?>>Metrophobic</option>
			<option value="Michroma" <?php if ( "Michroma" == $this_val ) echo 'selected="selected"'; ?>>Michroma</option>
			<option value="Milonga" <?php if ( "Milonga" == $this_val ) echo 'selected="selected"'; ?>>Milonga</option>
			<option value="Miltonian" <?php if ( "Miltonian" == $this_val ) echo 'selected="selected"'; ?>>Miltonian</option>
			<option value="Miltonian Tattoo" <?php if ( "Miltonian Tattoo" == $this_val ) echo 'selected="selected"'; ?>>Miltonian Tattoo</option>
			<option value="Miniver" <?php if ( "Miniver" == $this_val ) echo 'selected="selected"'; ?>>Miniver</option>
			<option value="Miss Fajardose" <?php if ( "Miss Fajardose" == $this_val ) echo 'selected="selected"'; ?>>Miss Fajardose</option>
			<option value="Modern Antiqua" <?php if ( "Modern Antiqua" == $this_val ) echo 'selected="selected"'; ?>>Modern Antiqua</option>
			<option value="Molengo" <?php if ( "Molengo" == $this_val ) echo 'selected="selected"'; ?>>Molengo</option>
			<option value="Molle" <?php if ( "Molle" == $this_val ) echo 'selected="selected"'; ?>>Molle</option>
			<option value="Monda" <?php if ( "Monda" == $this_val ) echo 'selected="selected"'; ?>>Monda</option>
			<option value="Monofett" <?php if ( "Monofett" == $this_val ) echo 'selected="selected"'; ?>>Monofett</option>
			<option value="Monoton" <?php if ( "Monoton" == $this_val ) echo 'selected="selected"'; ?>>Monoton</option>
			<option value="Monsieur La Doulaise" <?php if ( "Monsieur La Doulaise" == $this_val ) echo 'selected="selected"'; ?>>Monsieur La Doulaise</option>
			<option value="Montaga" <?php if ( "Montaga" == $this_val ) echo 'selected="selected"'; ?>>Montaga</option>
			<option value="Montez" <?php if ( "Montez" == $this_val ) echo 'selected="selected"'; ?>>Montez</option>
			<option value="Montserrat" <?php if ( "Montserrat" == $this_val ) echo 'selected="selected"'; ?>>Montserrat</option>
			<option value="Montserrat Alternates" <?php if ( "Montserrat Alternates" == $this_val ) echo 'selected="selected"'; ?>>Montserrat Alternates</option>
			<option value="Montserrat Subrayada" <?php if ( "Montserrat Subrayada" == $this_val ) echo 'selected="selected"'; ?>>Montserrat Subrayada</option>
			<option value="Moul" <?php if ( "Moul" == $this_val ) echo 'selected="selected"'; ?>>Moul</option>
			<option value="Moulpali" <?php if ( "Moulpali" == $this_val ) echo 'selected="selected"'; ?>>Moulpali</option>
			<option value="Mountains of Christmas" <?php if ( "Mountains of Christmas" == $this_val ) echo 'selected="selected"'; ?>>Mountains of Christmas</option>
			<option value="Mouse Memoirs" <?php if ( "Mouse Memoirs" == $this_val ) echo 'selected="selected"'; ?>>Mouse Memoirs</option>
			<option value="Mr Bedfort" <?php if ( "Mr Bedfort" == $this_val ) echo 'selected="selected"'; ?>>Mr Bedfort</option>
			<option value="Mr Dafoe" <?php if ( "Mr Dafoe" == $this_val ) echo 'selected="selected"'; ?>>Mr Dafoe</option>
			<option value="Mr De Haviland" <?php if ( "Mr De Haviland" == $this_val ) echo 'selected="selected"'; ?>>Mr De Haviland</option>
			<option value="Mrs Saint Delafield" <?php if ( "Mrs Saint Delafield" == $this_val ) echo 'selected="selected"'; ?>>Mrs Saint Delafield</option>
			<option value="Mrs Sheppards" <?php if ( "Mrs Sheppards" == $this_val ) echo 'selected="selected"'; ?>>Mrs Sheppards</option>
			<option value="Muli" <?php if ( "Muli" == $this_val ) echo 'selected="selected"'; ?>>Muli</option>
			<option value="Mystery Quest" <?php if ( "Mystery Quest" == $this_val ) echo 'selected="selected"'; ?>>Mystery Quest</option>
			<option value="Neucha" <?php if ( "Neucha" == $this_val ) echo 'selected="selected"'; ?>>Neucha</option>
			<option value="Neuton" <?php if ( "Neuton" == $this_val ) echo 'selected="selected"'; ?>>Neuton</option>
			<option value="New Rocker" <?php if ( "New Rocker" == $this_val ) echo 'selected="selected"'; ?>>New Rocker</option>
			<option value="News Cycle" <?php if ( "News Cycle" == $this_val ) echo 'selected="selected"'; ?>>News Cycle</option>
			<option value="Niconne" <?php if ( "Niconne" == $this_val ) echo 'selected="selected"'; ?>>Niconne</option>
			<option value="Nixie One" <?php if ( "Nixie One" == $this_val ) echo 'selected="selected"'; ?>>Nixie One</option>
			<option value="Nobile" <?php if ( "Nobile" == $this_val ) echo 'selected="selected"'; ?>>Nobile</option>
			<option value="Nokora" <?php if ( "Nokora" == $this_val ) echo 'selected="selected"'; ?>>Nokora</option>
			<option value="Norican" <?php if ( "Norican" == $this_val ) echo 'selected="selected"'; ?>>Norican</option>
			<option value="Nosifer" <?php if ( "Nosifer" == $this_val ) echo 'selected="selected"'; ?>>Nosifer</option>
			<option value="Nothing You Could Do" <?php if ( "Nothing You Could Do" == $this_val ) echo 'selected="selected"'; ?>>Nothing You Could Do</option>
			<option value="Noticia Text" <?php if ( "Noticia Text" == $this_val ) echo 'selected="selected"'; ?>>Noticia Text</option>
			<option value="Noto Sans" <?php if ( "Noto Sans" == $this_val ) echo 'selected="selected"'; ?>>Noto Sans</option>
			<option value="Noto Serif" <?php if ( "Noto Serif" == $this_val ) echo 'selected="selected"'; ?>>Noto Serif</option>
			<option value="Nova Cut" <?php if ( "Nova Cut" == $this_val ) echo 'selected="selected"'; ?>>Nova Cut</option>
			<option value="Nova Flat" <?php if ( "Nova Flat" == $this_val ) echo 'selected="selected"'; ?>>Nova Flat</option>
			<option value="Nova Mono" <?php if ( "Nova Mono" == $this_val ) echo 'selected="selected"'; ?>>Nova Mono</option>
			<option value="Nova Oval" <?php if ( "Nova Oval" == $this_val ) echo 'selected="selected"'; ?>>Nova Oval</option>
			<option value="Nova Round" <?php if ( "Nova Round" == $this_val ) echo 'selected="selected"'; ?>>Nova Round</option>
			<option value="Nova Script" <?php if ( "Nova Script" == $this_val ) echo 'selected="selected"'; ?>>Nova Script</option>
			<option value="Nova Slim" <?php if ( "Nova Slim" == $this_val ) echo 'selected="selected"'; ?>>Nova Slim</option>
			<option value="Nova Square" <?php if ( "Nova Square" == $this_val ) echo 'selected="selected"'; ?>>Nova Square</option>
			<option value="Numans" <?php if ( "Numans" == $this_val ) echo 'selected="selected"'; ?>>Numans</option>
			<option value="Nunito" <?php if ( "Nunito" == $this_val ) echo 'selected="selected"'; ?>>Nunito</option>
			<option value="Odor Mean Chey" <?php if ( "Odor Mean Chey" == $this_val ) echo 'selected="selected"'; ?>>Odor Mean Chey</option>
			<option value="Offside" <?php if ( "Offside" == $this_val ) echo 'selected="selected"'; ?>>Offside</option>
			<option value="Old Standard TT" <?php if ( "Old Standard TT" == $this_val ) echo 'selected="selected"'; ?>>Old Standard TT</option>
			<option value="Oldenburg" <?php if ( "Oldenburg" == $this_val ) echo 'selected="selected"'; ?>>Oldenburg</option>
			<option value="Oleo Script" <?php if ( "Oleo Script" == $this_val ) echo 'selected="selected"'; ?>>Oleo Script</option>
			<option value="Oleo Script Swash Caps" <?php if ( "Oleo Script Swash Caps" == $this_val ) echo 'selected="selected"'; ?>>Oleo Script Swash Caps</option>
			<option value="Open Sans" <?php if ( "Open Sans" == $this_val ) echo 'selected="selected"'; ?>>Open Sans</option>
			<option value="Open Sans Condensed" <?php if ( "Open Sans Condensed" == $this_val ) echo 'selected="selected"'; ?>>Open Sans Condensed</option>
			<option value="Oranienbaum" <?php if ( "Oranienbaum" == $this_val ) echo 'selected="selected"'; ?>>Oranienbaum</option>
			<option value="Orbitron" <?php if ( "Orbitron" == $this_val ) echo 'selected="selected"'; ?>>Orbitron</option>
			<option value="Oregano" <?php if ( "Oregano" == $this_val ) echo 'selected="selected"'; ?>>Oregano</option>
			<option value="Orienta" <?php if ( "Orienta" == $this_val ) echo 'selected="selected"'; ?>>Orienta</option>
			<option value="Original Surfer" <?php if ( "Original Surfer" == $this_val ) echo 'selected="selected"'; ?>>Original Surfer</option>
			<option value="Oswald" <?php if ( "Oswald" == $this_val ) echo 'selected="selected"'; ?>>Oswald</option>
			<option value="Over the Rainbow" <?php if ( "Over the Rainbow" == $this_val ) echo 'selected="selected"'; ?>>Over the Rainbow</option>
			<option value="Overlock" <?php if ( "Overlock" == $this_val ) echo 'selected="selected"'; ?>>Overlock</option>
			<option value="Overlock SC" <?php if ( "Overlock SC" == $this_val ) echo 'selected="selected"'; ?>>Overlock SC</option>
			<option value="Ovo" <?php if ( "Ovo" == $this_val ) echo 'selected="selected"'; ?>>Ovo</option>
			<option value="Oxygen" <?php if ( "Oxygen" == $this_val ) echo 'selected="selected"'; ?>>Oxygen</option>
			<option value="Oxygen Mono" <?php if ( "Oxygen Mono" == $this_val ) echo 'selected="selected"'; ?>>Oxygen Mono</option>
			<option value="PT Mono" <?php if ( "PT Mono" == $this_val ) echo 'selected="selected"'; ?>>PT Mono</option>
			<option value="PT Sans" <?php if ( "PT Sans" == $this_val ) echo 'selected="selected"'; ?>>PT Sans</option>
			<option value="PT Sans Caption" <?php if ( "PT Sans Caption" == $this_val ) echo 'selected="selected"'; ?>>PT Sans Caption</option>
			<option value="PT Sans Narrow" <?php if ( "PT Sans Narrow" == $this_val ) echo 'selected="selected"'; ?>>PT Sans Narrow</option>
			<option value="PT Serif" <?php if ( "PT Serif" == $this_val ) echo 'selected="selected"'; ?>>PT Serif</option>
			<option value="PT Serif Caption" <?php if ( "PT Serif Caption" == $this_val ) echo 'selected="selected"'; ?>>PT Serif Caption</option>
			<option value="Pacifico" <?php if ( "Pacifico" == $this_val ) echo 'selected="selected"'; ?>>Pacifico</option>
			<option value="Paprika" <?php if ( "Paprika" == $this_val ) echo 'selected="selected"'; ?>>Paprika</option>
			<option value="Parisienne" <?php if ( "Parisienne" == $this_val ) echo 'selected="selected"'; ?>>Parisienne</option>
			<option value="Passero One" <?php if ( "Passero One" == $this_val ) echo 'selected="selected"'; ?>>Passero One</option>
			<option value="Passion One" <?php if ( "Passion One" == $this_val ) echo 'selected="selected"'; ?>>Passion One</option>
			<option value="Pathway Gothic One" <?php if ( "Pathway Gothic One" == $this_val ) echo 'selected="selected"'; ?>>Pathway Gothic One</option>
			<option value="Patrick Hand" <?php if ( "Patrick Hand" == $this_val ) echo 'selected="selected"'; ?>>Patrick Hand</option>
			<option value="Patrick Hand SC" <?php if ( "Patrick Hand SC" == $this_val ) echo 'selected="selected"'; ?>>Patrick Hand SC</option>
			<option value="Patua One" <?php if ( "Patua One" == $this_val ) echo 'selected="selected"'; ?>>Patua One</option>
			<option value="Paytone One" <?php if ( "Paytone One" == $this_val ) echo 'selected="selected"'; ?>>Paytone One</option>
			<option value="Peralta" <?php if ( "Peralta" == $this_val ) echo 'selected="selected"'; ?>>Peralta</option>
			<option value="Permanent Marker" <?php if ( "Permanent Marker" == $this_val ) echo 'selected="selected"'; ?>>Permanent Marker</option>
			<option value="Petit Formal Script" <?php if ( "Petit Formal Script" == $this_val ) echo 'selected="selected"'; ?>>Petit Formal Script</option>
			<option value="Petrona" <?php if ( "Petrona" == $this_val ) echo 'selected="selected"'; ?>>Petrona</option>
			<option value="Philosopher" <?php if ( "Philosopher" == $this_val ) echo 'selected="selected"'; ?>>Philosopher</option>
			<option value="Piedra" <?php if ( "Piedra" == $this_val ) echo 'selected="selected"'; ?>>Piedra</option>
			<option value="Pinyon Script" <?php if ( "Pinyon Script" == $this_val ) echo 'selected="selected"'; ?>>Pinyon Script</option>
			<option value="Pirata One" <?php if ( "Pirata One" == $this_val ) echo 'selected="selected"'; ?>>Pirata One</option>
			<option value="Plaster" <?php if ( "Plaster" == $this_val ) echo 'selected="selected"'; ?>>Plaster</option>
			<option value="Play" <?php if ( "Play" == $this_val ) echo 'selected="selected"'; ?>>Play</option>
			<option value="Playball" <?php if ( "Playball" == $this_val ) echo 'selected="selected"'; ?>>Playball</option>
			<option value="Playfair Display" <?php if ( "Playfair Display" == $this_val ) echo 'selected="selected"'; ?>>Playfair Display</option>
			<option value="Playfair Display SC" <?php if ( "Playfair Display SC" == $this_val ) echo 'selected="selected"'; ?>>Playfair Display SC</option>
			<option value="Podkova" <?php if ( "Podkova" == $this_val ) echo 'selected="selected"'; ?>>Podkova</option>
			<option value="Poiret One" <?php if ( "Poiret One" == $this_val ) echo 'selected="selected"'; ?>>Poiret One</option>
			<option value="Poller One" <?php if ( "Poller One" == $this_val ) echo 'selected="selected"'; ?>>Poller One</option>
			<option value="Poly" <?php if ( "Poly" == $this_val ) echo 'selected="selected"'; ?>>Poly</option>
			<option value="Pompiere" <?php if ( "Pompiere" == $this_val ) echo 'selected="selected"'; ?>>Pompiere</option>
			<option value="Pontano Sans" <?php if ( "Pontano Sans" == $this_val ) echo 'selected="selected"'; ?>>Pontano Sans</option>
			<option value="Port Lligat Sans" <?php if ( "Port Lligat Sans" == $this_val ) echo 'selected="selected"'; ?>>Port Lligat Sans</option>
			<option value="Port Lligat Slab" <?php if ( "Port Lligat Slab" == $this_val ) echo 'selected="selected"'; ?>>Port Lligat Slab</option>
			<option value="Prata" <?php if ( "Prata" == $this_val ) echo 'selected="selected"'; ?>>Prata</option>
			<option value="Preahvihear" <?php if ( "Preahvihear" == $this_val ) echo 'selected="selected"'; ?>>Preahvihear</option>
			<option value="Press Start 2P" <?php if ( "Press Start 2P" == $this_val ) echo 'selected="selected"'; ?>>Press Start 2P</option>
			<option value="Princess Sofia" <?php if ( "Princess Sofia" == $this_val ) echo 'selected="selected"'; ?>>Princess Sofia</option>
			<option value="Prociono" <?php if ( "Prociono" == $this_val ) echo 'selected="selected"'; ?>>Prociono</option>
			<option value="Prosto One" <?php if ( "Prosto One" == $this_val ) echo 'selected="selected"'; ?>>Prosto One</option>
			<option value="Puritan" <?php if ( "Puritan" == $this_val ) echo 'selected="selected"'; ?>>Puritan</option>
			<option value="Purple Purse" <?php if ( "Purple Purse" == $this_val ) echo 'selected="selected"'; ?>>Purple Purse</option>
			<option value="Quando" <?php if ( "Quando" == $this_val ) echo 'selected="selected"'; ?>>Quando</option>
			<option value="Quantico" <?php if ( "Quantico" == $this_val ) echo 'selected="selected"'; ?>>Quantico</option>
			<option value="Quattrocento" <?php if ( "Quattrocento" == $this_val ) echo 'selected="selected"'; ?>>Quattrocento</option>
			<option value="Quattrocento Sans" <?php if ( "Quattrocento Sans" == $this_val ) echo 'selected="selected"'; ?>>Quattrocento Sans</option>
			<option value="Questrial" <?php if ( "Questrial" == $this_val ) echo 'selected="selected"'; ?>>Questrial</option>
			<option value="Quicksand" <?php if ( "Quicksand" == $this_val ) echo 'selected="selected"'; ?>>Quicksand</option>
			<option value="Quintessential" <?php if ( "Quintessential" == $this_val ) echo 'selected="selected"'; ?>>Quintessential</option>
			<option value="Qwigley" <?php if ( "Qwigley" == $this_val ) echo 'selected="selected"'; ?>>Qwigley</option>
			<option value="Racing Sans One" <?php if ( "Racing Sans One" == $this_val ) echo 'selected="selected"'; ?>>Racing Sans One</option>
			<option value="Radley" <?php if ( "Radley" == $this_val ) echo 'selected="selected"'; ?>>Radley</option>
			<option value="Rajdhani" <?php if ( "Rajdhani" == $this_val ) echo 'selected="selected"'; ?>>Rajdhani</option>
			<option value="Raleway" <?php if ( "Raleway" == $this_val ) echo 'selected="selected"'; ?>>Raleway</option>
			<option value="Raleway Dots" <?php if ( "Raleway Dots" == $this_val ) echo 'selected="selected"'; ?>>Raleway Dots</option>
			<option value="Rambla" <?php if ( "Rambla" == $this_val ) echo 'selected="selected"'; ?>>Rambla</option>
			<option value="Rammetto One" <?php if ( "Rammetto One" == $this_val ) echo 'selected="selected"'; ?>>Rammetto One</option>
			<option value="Ranchers" <?php if ( "Ranchers" == $this_val ) echo 'selected="selected"'; ?>>Ranchers</option>
			<option value="Rancho" <?php if ( "Rancho" == $this_val ) echo 'selected="selected"'; ?>>Rancho</option>
			<option value="Rationale" <?php if ( "Rationale" == $this_val ) echo 'selected="selected"'; ?>>Rationale</option>
			<option value="Redressed" <?php if ( "Redressed" == $this_val ) echo 'selected="selected"'; ?>>Redressed</option>
			<option value="Reenie Beanie" <?php if ( "Reenie Beanie" == $this_val ) echo 'selected="selected"'; ?>>Reenie Beanie</option>
			<option value="Revalia" <?php if ( "Revalia" == $this_val ) echo 'selected="selected"'; ?>>Revalia</option>
			<option value="Ribeye" <?php if ( "Ribeye" == $this_val ) echo 'selected="selected"'; ?>>Ribeye</option>
			<option value="Ribeye Marrow" <?php if ( "Ribeye Marrow" == $this_val ) echo 'selected="selected"'; ?>>Ribeye Marrow</option>
			<option value="Righteous" <?php if ( "Righteous" == $this_val ) echo 'selected="selected"'; ?>>Righteous</option>
			<option value="Risque" <?php if ( "Risque" == $this_val ) echo 'selected="selected"'; ?>>Risque</option>
			<option value="Roboto" <?php if ( "Roboto" == $this_val ) echo 'selected="selected"'; ?>>Roboto</option>
			<option value="Roboto Condensed" <?php if ( "Roboto Condensed" == $this_val ) echo 'selected="selected"'; ?>>Roboto Condensed</option>
			<option value="Roboto Slab" <?php if ( "Roboto Slab" == $this_val ) echo 'selected="selected"'; ?>>Roboto Slab</option>
			<option value="Rochester" <?php if ( "Rochester" == $this_val ) echo 'selected="selected"'; ?>>Rochester</option>
			<option value="Rock Salt" <?php if ( "Rock Salt" == $this_val ) echo 'selected="selected"'; ?>>Rock Salt</option>
			<option value="Rokkitt" <?php if ( "Rokkitt" == $this_val ) echo 'selected="selected"'; ?>>Rokkitt</option>
			<option value="Romanesco" <?php if ( "Romanesco" == $this_val ) echo 'selected="selected"'; ?>>Romanesco</option>
			<option value="Ropa Sans" <?php if ( "Ropa Sans" == $this_val ) echo 'selected="selected"'; ?>>Ropa Sans</option>
			<option value="Rosario" <?php if ( "Rosario" == $this_val ) echo 'selected="selected"'; ?>>Rosario</option>
			<option value="Rosarivo" <?php if ( "Rosarivo" == $this_val ) echo 'selected="selected"'; ?>>Rosarivo</option>
			<option value="Rouge Script" <?php if ( "Rouge Script" == $this_val ) echo 'selected="selected"'; ?>>Rouge Script</option>
			<option value="Rozha One" <?php if ( "Rozha One" == $this_val ) echo 'selected="selected"'; ?>>Rozha One</option>
			<option value="Rubik Mono One" <?php if ( "Rubik Mono One" == $this_val ) echo 'selected="selected"'; ?>>Rubik Mono One</option>
			<option value="Rubik One" <?php if ( "Rubik One" == $this_val ) echo 'selected="selected"'; ?>>Rubik One</option>
			<option value="Ruda" <?php if ( "Ruda" == $this_val ) echo 'selected="selected"'; ?>>Ruda</option>
			<option value="Rufina" <?php if ( "Rufina" == $this_val ) echo 'selected="selected"'; ?>>Rufina</option>
			<option value="Ruge Boogie" <?php if ( "Ruge Boogie" == $this_val ) echo 'selected="selected"'; ?>>Ruge Boogie</option>
			<option value="Ruluko" <?php if ( "Ruluko" == $this_val ) echo 'selected="selected"'; ?>>Ruluko</option>
			<option value="Rum Raisin" <?php if ( "Rum Raisin" == $this_val ) echo 'selected="selected"'; ?>>Rum Raisin</option>
			<option value="Ruslan Display" <?php if ( "Ruslan Display" == $this_val ) echo 'selected="selected"'; ?>>Ruslan Display</option>
			<option value="Russo One" <?php if ( "Russo One" == $this_val ) echo 'selected="selected"'; ?>>Russo One</option>
			<option value="Ruthie" <?php if ( "Ruthie" == $this_val ) echo 'selected="selected"'; ?>>Ruthie</option>
			<option value="Rye" <?php if ( "Rye" == $this_val ) echo 'selected="selected"'; ?>>Rye</option>
			<option value="Sacramento" <?php if ( "Sacramento" == $this_val ) echo 'selected="selected"'; ?>>Sacramento</option>
			<option value="Sail" <?php if ( "Sail" == $this_val ) echo 'selected="selected"'; ?>>Sail</option>
			<option value="Salsa" <?php if ( "Salsa" == $this_val ) echo 'selected="selected"'; ?>>Salsa</option>
			<option value="Sanchez" <?php if ( "Sanchez" == $this_val ) echo 'selected="selected"'; ?>>Sanchez</option>
			<option value="Sancreek" <?php if ( "Sancreek" == $this_val ) echo 'selected="selected"'; ?>>Sancreek</option>
			<option value="Sansita One" <?php if ( "Sansita One" == $this_val ) echo 'selected="selected"'; ?>>Sansita One</option>
			<option value="Sarina" <?php if ( "Sarina" == $this_val ) echo 'selected="selected"'; ?>>Sarina</option>
			<option value="Sarpanch" <?php if ( "Sarpanch" == $this_val ) echo 'selected="selected"'; ?>>Sarpanch</option>
			<option value="Satisfy" <?php if ( "Satisfy" == $this_val ) echo 'selected="selected"'; ?>>Satisfy</option>
			<option value="Scada" <?php if ( "Scada" == $this_val ) echo 'selected="selected"'; ?>>Scada</option>
			<option value="Schoolbell" <?php if ( "Schoolbell" == $this_val ) echo 'selected="selected"'; ?>>Schoolbell</option>
			<option value="Seaweed Script" <?php if ( "Seaweed Script" == $this_val ) echo 'selected="selected"'; ?>>Seaweed Script</option>
			<option value="Sevillana" <?php if ( "Sevillana" == $this_val ) echo 'selected="selected"'; ?>>Sevillana</option>
			<option value="Seymour One" <?php if ( "Seymour One" == $this_val ) echo 'selected="selected"'; ?>>Seymour One</option>
			<option value="Shadows Into Light" <?php if ( "Shadows Into Light" == $this_val ) echo 'selected="selected"'; ?>>Shadows Into Light</option>
			<option value="Shadows Into Light Two" <?php if ( "Shadows Into Light Two" == $this_val ) echo 'selected="selected"'; ?>>Shadows Into Light Two</option>
			<option value="Shanti" <?php if ( "Shanti" == $this_val ) echo 'selected="selected"'; ?>>Shanti</option>
			<option value="Share" <?php if ( "Share" == $this_val ) echo 'selected="selected"'; ?>>Share</option>
			<option value="Share Tech" <?php if ( "Share Tech" == $this_val ) echo 'selected="selected"'; ?>>Share Tech</option>
			<option value="Share Tech Mono" <?php if ( "Share Tech Mono" == $this_val ) echo 'selected="selected"'; ?>>Share Tech Mono</option>
			<option value="Shojumaru" <?php if ( "Shojumaru" == $this_val ) echo 'selected="selected"'; ?>>Shojumaru</option>
			<option value="Short Stack" <?php if ( "Short Stack" == $this_val ) echo 'selected="selected"'; ?>>Short Stack</option>
			<option value="Siemreap" <?php if ( "Siemreap" == $this_val ) echo 'selected="selected"'; ?>>Siemreap</option>
			<option value="Sigmar One" <?php if ( "Sigmar One" == $this_val ) echo 'selected="selected"'; ?>>Sigmar One</option>
			<option value="Signika" <?php if ( "Signika" == $this_val ) echo 'selected="selected"'; ?>>Signika</option>
			<option value="Signika Negative" <?php if ( "Signika Negative" == $this_val ) echo 'selected="selected"'; ?>>Signika Negative</option>
			<option value="Simonetta" <?php if ( "Simonetta" == $this_val ) echo 'selected="selected"'; ?>>Simonetta</option>
			<option value="Sintony" <?php if ( "Sintony" == $this_val ) echo 'selected="selected"'; ?>>Sintony</option>
			<option value="Sirin Stencil" <?php if ( "Sirin Stencil" == $this_val ) echo 'selected="selected"'; ?>>Sirin Stencil</option>
			<option value="Six Caps" <?php if ( "Six Caps" == $this_val ) echo 'selected="selected"'; ?>>Six Caps</option>
			<option value="Skranji" <?php if ( "Skranji" == $this_val ) echo 'selected="selected"'; ?>>Skranji</option>
			<option value="Slabo 13px" <?php if ( "Slabo 13px" == $this_val ) echo 'selected="selected"'; ?>>Slabo 13px</option>
			<option value="Slabo 27px" <?php if ( "Slabo 27px" == $this_val ) echo 'selected="selected"'; ?>>Slabo 27px</option>
			<option value="Slackey" <?php if ( "Slackey" == $this_val ) echo 'selected="selected"'; ?>>Slackey</option>
			<option value="Smokum" <?php if ( "Smokum" == $this_val ) echo 'selected="selected"'; ?>>Smokum</option>
			<option value="Smythe" <?php if ( "Smythe" == $this_val ) echo 'selected="selected"'; ?>>Smythe</option>
			<option value="Sniglet" <?php if ( "Sniglet" == $this_val ) echo 'selected="selected"'; ?>>Sniglet</option>
			<option value="Snippet" <?php if ( "Snippet" == $this_val ) echo 'selected="selected"'; ?>>Snippet</option>
			<option value="Snowburst One" <?php if ( "Snowburst One" == $this_val ) echo 'selected="selected"'; ?>>Snowburst One</option>
			<option value="Sofadi One" <?php if ( "Sofadi One" == $this_val ) echo 'selected="selected"'; ?>>Sofadi One</option>
			<option value="Sofia" <?php if ( "Sofia" == $this_val ) echo 'selected="selected"'; ?>>Sofia</option>
			<option value="Sonsie One" <?php if ( "Sonsie One" == $this_val ) echo 'selected="selected"'; ?>>Sonsie One</option>
			<option value="Sorts Mill Goudy" <?php if ( "Sorts Mill Goudy" == $this_val ) echo 'selected="selected"'; ?>>Sorts Mill Goudy</option>
			<option value="Source Code Pro" <?php if ( "Source Code Pro" == $this_val ) echo 'selected="selected"'; ?>>Source Code Pro</option>
			<option value="Source Sans Pro" <?php if ( "Source Sans Pro" == $this_val ) echo 'selected="selected"'; ?>>Source Sans Pro</option>
			<option value="Source Serif Pro" <?php if ( "Source Serif Pro" == $this_val ) echo 'selected="selected"'; ?>>Source Serif Pro</option>
			<option value="Special Elite" <?php if ( "Special Elite" == $this_val ) echo 'selected="selected"'; ?>>Special Elite</option>
			<option value="Spicy Rice" <?php if ( "Spicy Rice" == $this_val ) echo 'selected="selected"'; ?>>Spicy Rice</option>
			<option value="Spinnaker" <?php if ( "Spinnaker" == $this_val ) echo 'selected="selected"'; ?>>Spinnaker</option>
			<option value="Spirax" <?php if ( "Spirax" == $this_val ) echo 'selected="selected"'; ?>>Spirax</option>
			<option value="Squada One" <?php if ( "Squada One" == $this_val ) echo 'selected="selected"'; ?>>Squada One</option>
			<option value="Stalemate" <?php if ( "Stalemate" == $this_val ) echo 'selected="selected"'; ?>>Stalemate</option>
			<option value="Stalinist One" <?php if ( "Stalinist One" == $this_val ) echo 'selected="selected"'; ?>>Stalinist One</option>
			<option value="Stardos Stencil" <?php if ( "Stardos Stencil" == $this_val ) echo 'selected="selected"'; ?>>Stardos Stencil</option>
			<option value="Stint Ultra Condensed" <?php if ( "Stint Ultra Condensed" == $this_val ) echo 'selected="selected"'; ?>>Stint Ultra Condensed</option>
			<option value="Stint Ultra Expanded" <?php if ( "Stint Ultra Expanded" == $this_val ) echo 'selected="selected"'; ?>>Stint Ultra Expanded</option>
			<option value="Stoke" <?php if ( "Stoke" == $this_val ) echo 'selected="selected"'; ?>>Stoke</option>
			<option value="Strait" <?php if ( "Strait" == $this_val ) echo 'selected="selected"'; ?>>Strait</option>
			<option value="Sue Ellen Francisco" <?php if ( "Sue Ellen Francisco" == $this_val ) echo 'selected="selected"'; ?>>Sue Ellen Francisco</option>
			<option value="Sunshiney" <?php if ( "Sunshiney" == $this_val ) echo 'selected="selected"'; ?>>Sunshiney</option>
			<option value="Supermercado One" <?php if ( "Supermercado One" == $this_val ) echo 'selected="selected"'; ?>>Supermercado One</option>
			<option value="Suwannaphum" <?php if ( "Suwannaphum" == $this_val ) echo 'selected="selected"'; ?>>Suwannaphum</option>
			<option value="Swanky and Moo Moo" <?php if ( "Swanky and Moo Moo" == $this_val ) echo 'selected="selected"'; ?>>Swanky and Moo Moo</option>
			<option value="Syncopate" <?php if ( "Syncopate" == $this_val ) echo 'selected="selected"'; ?>>Syncopate</option>
			<option value="Tangerine" <?php if ( "Tangerine" == $this_val ) echo 'selected="selected"'; ?>>Tangerine</option>
			<option value="Taprom" <?php if ( "Taprom" == $this_val ) echo 'selected="selected"'; ?>>Taprom</option>
			<option value="Tauri" <?php if ( "Tauri" == $this_val ) echo 'selected="selected"'; ?>>Tauri</option>
			<option value="Teko" <?php if ( "Teko" == $this_val ) echo 'selected="selected"'; ?>>Teko</option>
			<option value="Telex" <?php if ( "Telex" == $this_val ) echo 'selected="selected"'; ?>>Telex</option>
			<option value="Tenor Sans" <?php if ( "Tenor Sans" == $this_val ) echo 'selected="selected"'; ?>>Tenor Sans</option>
			<option value="Text Me One" <?php if ( "Text Me One" == $this_val ) echo 'selected="selected"'; ?>>Text Me One</option>
			<option value="The Girl Next Door" <?php if ( "The Girl Next Door" == $this_val ) echo 'selected="selected"'; ?>>The Girl Next Door</option>
			<option value="Tienne" <?php if ( "Tienne" == $this_val ) echo 'selected="selected"'; ?>>Tienne</option>
			<option value="Tinos" <?php if ( "Tinos" == $this_val ) echo 'selected="selected"'; ?>>Tinos</option>
			<option value="Titan One" <?php if ( "Titan One" == $this_val ) echo 'selected="selected"'; ?>>Titan One</option>
			<option value="Titillium Web" <?php if ( "Titillium Web" == $this_val ) echo 'selected="selected"'; ?>>Titillium Web</option>
			<option value="Trade Winds" <?php if ( "Trade Winds" == $this_val ) echo 'selected="selected"'; ?>>Trade Winds</option>
			<option value="Trocchi" <?php if ( "Trocchi" == $this_val ) echo 'selected="selected"'; ?>>Trocchi</option>
			<option value="Trochut" <?php if ( "Trochut" == $this_val ) echo 'selected="selected"'; ?>>Trochut</option>
			<option value="Trykker" <?php if ( "Trykker" == $this_val ) echo 'selected="selected"'; ?>>Trykker</option>
			<option value="Tulpen One" <?php if ( "Tulpen One" == $this_val ) echo 'selected="selected"'; ?>>Tulpen One</option>
			<option value="Ubuntu" <?php if ( "Ubuntu" == $this_val ) echo 'selected="selected"'; ?>>Ubuntu</option>
			<option value="Ubuntu Condensed" <?php if ( "Ubuntu Condensed" == $this_val ) echo 'selected="selected"'; ?>>Ubuntu Condensed</option>
			<option value="Ubuntu Mono" <?php if ( "Ubuntu Mono" == $this_val ) echo 'selected="selected"'; ?>>Ubuntu Mono</option>
			<option value="Ultra" <?php if ( "Ultra" == $this_val ) echo 'selected="selected"'; ?>>Ultra</option>
			<option value="Uncial Antiqua" <?php if ( "Uncial Antiqua" == $this_val ) echo 'selected="selected"'; ?>>Uncial Antiqua</option>
			<option value="Underdog" <?php if ( "Underdog" == $this_val ) echo 'selected="selected"'; ?>>Underdog</option>
			<option value="Unica One" <?php if ( "Unica One" == $this_val ) echo 'selected="selected"'; ?>>Unica One</option>
			<option value="UnifrakturCook" <?php if ( "UnifrakturCook" == $this_val ) echo 'selected="selected"'; ?>>UnifrakturCook</option>
			<option value="UnifrakturMaguntia" <?php if ( "UnifrakturMaguntia" == $this_val ) echo 'selected="selected"'; ?>>UnifrakturMaguntia</option>
			<option value="Unkempt" <?php if ( "Unkempt" == $this_val ) echo 'selected="selected"'; ?>>Unkempt</option>
			<option value="Unlock" <?php if ( "Unlock" == $this_val ) echo 'selected="selected"'; ?>>Unlock</option>
			<option value="Unna" <?php if ( "Unna" == $this_val ) echo 'selected="selected"'; ?>>Unna</option>
			<option value="VT323" <?php if ( "VT323" == $this_val ) echo 'selected="selected"'; ?>>VT323</option>
			<option value="Vampiro One" <?php if ( "Vampiro One" == $this_val ) echo 'selected="selected"'; ?>>Vampiro One</option>
			<option value="Varela" <?php if ( "Varela" == $this_val ) echo 'selected="selected"'; ?>>Varela</option>
			<option value="Varela Round" <?php if ( "Varela Round" == $this_val ) echo 'selected="selected"'; ?>>Varela Round</option>
			<option value="Vast Shadow" <?php if ( "Vast Shadow" == $this_val ) echo 'selected="selected"'; ?>>Vast Shadow</option>
			<option value="Vesper Libre" <?php if ( "Vesper Libre" == $this_val ) echo 'selected="selected"'; ?>>Vesper Libre</option>
			<option value="Vibur" <?php if ( "Vibur" == $this_val ) echo 'selected="selected"'; ?>>Vibur</option>
			<option value="Vidaloka" <?php if ( "Vidaloka" == $this_val ) echo 'selected="selected"'; ?>>Vidaloka</option>
			<option value="Viga" <?php if ( "Viga" == $this_val ) echo 'selected="selected"'; ?>>Viga</option>
			<option value="Voces" <?php if ( "Voces" == $this_val ) echo 'selected="selected"'; ?>>Voces</option>
			<option value="Volkhov" <?php if ( "Volkhov" == $this_val ) echo 'selected="selected"'; ?>>Volkhov</option>
			<option value="Vollkorn" <?php if ( "Vollkorn" == $this_val ) echo 'selected="selected"'; ?>>Vollkorn</option>
			<option value="Voltaire" <?php if ( "Voltaire" == $this_val ) echo 'selected="selected"'; ?>>Voltaire</option>
			<option value="Waiting for the Sunrise" <?php if ( "Waiting for the Sunrise" == $this_val ) echo 'selected="selected"'; ?>>Waiting for the Sunrise</option>
			<option value="Wallpoet" <?php if ( "Wallpoet" == $this_val ) echo 'selected="selected"'; ?>>Wallpoet</option>
			<option value="Walter Turncoat" <?php if ( "Walter Turncoat" == $this_val ) echo 'selected="selected"'; ?>>Walter Turncoat</option>
			<option value="Warnes" <?php if ( "Warnes" == $this_val ) echo 'selected="selected"'; ?>>Warnes</option>
			<option value="Wellfleet" <?php if ( "Wellfleet" == $this_val ) echo 'selected="selected"'; ?>>Wellfleet</option>
			<option value="Wendy One" <?php if ( "Wendy One" == $this_val ) echo 'selected="selected"'; ?>>Wendy One</option>
			<option value="Wire One" <?php if ( "Wire One" == $this_val ) echo 'selected="selected"'; ?>>Wire One</option>
			<option value="Yanone Kaffeesatz" <?php if ( "Yanone Kaffeesatz" == $this_val ) echo 'selected="selected"'; ?>>Yanone Kaffeesatz</option>
			<option value="Yellowtail" <?php if ( "Yellowtail" == $this_val ) echo 'selected="selected"'; ?>>Yellowtail</option>
			<option value="Yeseva One" <?php if ( "Yeseva One" == $this_val ) echo 'selected="selected"'; ?>>Yeseva One</option>
			<option value="Yesteryear" <?php if ( "Yesteryear" == $this_val ) echo 'selected="selected"'; ?>>Yesteryear</option>
			<option value="Zeyada" <?php if ( "Zeyada" == $this_val ) echo 'selected="selected"'; ?>>Zeyada</option>
		</select>
	</label>
	<?php }
}