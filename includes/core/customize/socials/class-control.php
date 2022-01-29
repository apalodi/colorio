<?php

namespace Apalodi\Core\Customize\Socials;

use WP_Customize_Control;

class Control extends WP_Customize_Control {
	/**
	 * Type.
	 *
	 * @var string
	 */
	public $type = 'apalodi_socials';

	/**
	 * Labels.
	 *
	 * @var array
	 */
	public $labels = [];

	/**
	 * Get the data to export to the client via JSON.
	 *
	 * @return array Array of parameters passed to the JavaScript.
	 */
	public function json() {
		$json = parent::json();

		$json['id'] = $this->id;
		$json['link'] = $this->get_link();
		$json['choices'] = $this->choices;
		$json['labels'] = $this->labels;
		$json['value'] = $this->value();
		$json['default'] = isset( $this->default ) ? $this->default : $this->setting->default;

		return $json;
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 */
	public function render_content() {}

	/**
	 * Render a JS template for control content.
	 */
	protected function content_template() {
		?>

		<# if ( ! data.choices ) { return; } #>

		<# if ( data.label ) { #><span class="customize-control-title">{{{ data.label }}}</span><# } #>
		<# if ( data.description ) { #><span class="description customize-control-description">{{{ data.description }}}</span><# } #>

		<div class="apalodi-social-icons-wrapper">
		<# _.each( data.value, function( social ) { #>
			<div class="apalodi-social-icons">
				<select value="{{ social.type }}">
					<option value="">{{{ data.labels.select }}}</option>
					<# _.each( data.choices, function( group ) { #>
					<optgroup label="{{ group.group }}">
						<# _.each( group.icons, function( label, choice ) { #>
						<option value="{{ choice }}"<# if ( social.type == choice ) { #> selected="selected"<# } #>>{{{ label }}}</option>
						<# } ) #>
					</optgroup>
					<# } ) #>
				</select>
				<label>
					{{{ data.labels.placeholder }}}
					<input type="text" value="{{ social.url }}" placeholder="https://example.com" />
				</label>
				<button class="button-link button-link-delete apalodi-social-icons-delete">{{{ data.labels.remove }}}</button>
			</div>
		<# } ) #>

		</div>

		<button class="button apalodi-social-icons-add">{{{ data.labels.add }}}</button>

		<?php
	}
}
