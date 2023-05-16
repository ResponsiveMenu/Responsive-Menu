<?php
/**
 * Control_Manager class.
 * This class prepare the input control and it's markup.
 *
 * @version 4.0.0
 * @author  Expresstech System
 *
 * @package responsive-menu
 */

namespace RMP\Features\Inc;

use RMP\Features\Inc\Traits\Singleton;

// Disable the direct access to this class.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Control_Manager
 */
class Control_Manager {

	use Singleton;
	public $pro_plugin_url = 'https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile';
	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @version 4.0.0
	 *
	 * @return void
	 */
	protected function setup_hooks() {
	}

	/**
	 * Add hidden field input control.
	 *
	 * @version 4.0.0
	 *
	 * @param array List of attribute
	 *
	 * @return HTML|string
	 */
	public function add_hidden_control( $param ) {
		?><input type="hidden" name="<?php echo esc_attr( $param['name'] ); ?>" value="<?php echo esc_attr( $param['value'] ); ?>" >
		<?php
	}

	/**
	 * This function prepare the single text input control.
	 *
	 * @version 4.0.0
	 * @param array $param  List of attributes for a input control
	 *
	 * @return HTML
	 */
	public function add_text_input_control( $param ) {
		if ( empty( $param ) ) {
			return;
		}

		/**
		 * Filters the text input control attributes before create.
		 *
		 * @version 4.0.0
		 * @param array $param List of attribute.
		 */
		$param = apply_filters( 'rmp_before_add_text_input_control', $param );

		$is_disabled   = '';
		$group_classes = '';
		if ( ! empty( $param['group_classes'] ) ) {
			$group_classes = $param['group_classes'];
		}
		?>
		<div class="rmp-input-control-wrapper <?php echo esc_attr( $group_classes ); ?>">
		<?php

		// Check label is exist.
		if ( ! empty( $param['label'] ) ) {
			?>
			<div class="rmp-input-control-label">
					<span> <?php echo esc_html( $param['label'] ); ?> </span>
					<span>
				<?php
				// Check tooltip text is added or not.
				if ( ! empty( $param['tool_tip'] ) ) {
					$this->get_tool_tip( $param['tool_tip'] );
				}
				?>
					</span>
					<?php
					// Check feature type.
					if ( ! empty( $param['feature_type'] ) ) {
						$is_disabled = 'disabled';
						?>
						<a target="_blank" rel="noopener" class="upgrade-tooltip" href="<?php echo esc_url( $this->pro_plugin_url ); ?>" >
							<?php echo esc_html( $param['feature_type'] ); ?>
						</a>
						<?php
					}
					?>
				</div>
						<?php
		}
		?>
		<div class="rmp-input-control">
		<?php

		// Check this input has multi device options.
		$has_multi_device = '';
		if ( ! empty( $param['multi_device'] ) ) {
			$has_multi_device = 'multi-device=true';
			$this->get_device_options();
		}

		// Place holder text.
		$placeholder = '';
		if ( ! empty( $param['placeholder'] ) ) {
			$placeholder = $param['placeholder'];
		}

		// Check the input control type that maybe text,number or any other.
		if ( ! empty( $param['type'] ) ) {
			$class = '';
			if ( ! empty( $param['class'] ) ) {
				$class = $param['class'];
			}
			?>
			<input type="<?php echo esc_attr( $param['type'] ); ?>" id="<?php echo esc_attr( $param['id'] ); ?>" name="<?php echo esc_attr( $param['name'] ); ?>" <?php echo esc_attr( $has_multi_device ); ?> class="<?php echo esc_attr( $class ); ?>" value="<?php echo esc_attr( $param['value'] ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php echo esc_attr( $is_disabled ); ?> >
			<?php
		}

		// Check the unit of the this control.
		if ( ! empty( $param['has_unit'] ) ) {
			$unit_type = $param['has_unit']['unit_type'];
			if ( 'all' === $unit_type ) {
				$this->get_input_control_unit( $param['has_unit'] );
			} else {
				?>
				<span class="unit-<?php echo esc_html( $unit_type ); ?>"> <?php echo esc_html( $unit_type ); ?> </span>
				<?php
			}
		}
		?>
		</div></div>
		<?php

		/**
		 * Filters the text input attributes/contents after prepared.
		 *
		 * @version 4.0.0
		 *
		 * @param HTML|string Input control contents.
		 * @param array       $param List of attribute.
		 */
		echo apply_filters( 'rmp_text_control_html', '', $param );
	}

	/**
	 * This function prepare the group text input control.
	 *
	 * @version 4.0.0
	 * @param array $param  List of attributes for a input control
	 *
	 * @return HTML
	 */
	public function add_group_text_control( $param ) {
		if ( empty( $param ) ) {
			return;
		}

		/**
		 * Filters the text group input control attributes before create.
		 *
		 * @version 4.0.0
		 * @param array $param List of attribute.
		 */
		$param = apply_filters( 'rmp_before_add_group_text_control', $param );

		$group_classes = '';

		if ( ! empty( $param['group_classes'] ) ) {
			$group_classes = $param['group_classes'];
		}
		?>
		<div class="rmp-input-control-wrapper full-size <?php echo esc_attr( $group_classes ); ?>">
		<?php

		// Check label is exist.
		if ( ! empty( $param['label'] ) ) {
			?>
			<div class="rmp-input-control-label"> <span> <?php echo esc_html( $param['label'] ); ?> </span>
			<?php
				// Check tooltip text is added or not.
			if ( ! empty( $param['tool_tip'] ) ) {
				$this->get_tool_tip( $param['tool_tip'] );
			}
			?>
				</div>
				<?php
		}
		?>
		<div class="rmp-input-control rmp-input-group-control">
		<?php

		// Check this input has multi device options.
		$has_multi_device = '';
		if ( ! empty( $param['multi_device'] ) ) {
			$has_multi_device = 'multi-device=true';
			$this->get_device_options();
		}

		// Check the input control type that maybe text,number or any other.
		if ( ! empty( $param['type'] ) ) {
			$class = '';
			if ( ! empty( $param['class'] ) ) {
				$class = $param['class'];
			}

			if ( ! empty( $param['input_options'] ) ) {
				foreach ( $param['input_options'] as $input ) {
					$value = ! empty( $param['value_options'][ $input ] ) ? $param['value_options'][ $input ] : '0px';
					?>
					<div class="rmp-group-input-wrapper">
							<label> <?php echo esc_html( $input ); ?> </label>
							<input type="<?php echo esc_attr( $param['type'] ); ?>" placeholder="0px" data-input="<?php echo esc_attr( $input ); ?>" id="<?php echo esc_attr( $class . '-' . $input ); ?>" name="<?php echo esc_attr( $param['name'] . '[' . $input . ']' ); ?>" <?php echo esc_attr( $has_multi_device ); ?> class="no-updates rmp-group-input <?php echo esc_attr( $class ); ?>" value="<?php echo esc_attr( $value ); ?>">
						</div>
						<?php
				}
				?>
				<div class="rmp-group-input-wrapper">
					<button type="button" class="is-linked rmp-group-input rmp-group-input-linked">
						<span class="dashicons dashicons-admin-links "></span>
					</button></div>
					<?php
			}
		}

		// Check the unit of the this control.
		if ( ! empty( $param['has_unit'] ) ) {
			$unit_type = $param['has_unit']['unit_type'];
			if ( 'all' === $unit_type ) {
				$this->get_input_control_unit( $param['has_unit'] );
			} else {
				?>
				<span class="unit-<?php echo esc_html( $unit_type ); ?>"> <?php echo esc_html( $unit_type ); ?> </span>
				<?php
			}
		}
		?>
		</div></div>
		<?php

		/**
		 * Filters the text input attributes/contents after prepared.
		 *
		 * @version 4.0.0
		 *
		 * @param HTML|string Input control contents.
		 * @param array       $param List of attribute.
		 */
		echo apply_filters( 'rmp_text_control_html', '', $param );
	}

	/**
	 * This function prepare input unit options.
	 *
	 * @version 4.0.0
	 * @param array $param  List of attributes for a input control
	 *
	 * @return HTML
	 */
	protected function get_input_control_unit( $param ) {
		if ( empty( $param['name'] ) ) {
			return;
		}

		$value = '';

		if ( ! empty( $param['value'] ) ) {
			$value = $param['value'];
		}

		$has_multi_device = 'false';
		if ( ! empty( $param['multi_device'] ) ) {
			$has_multi_device = 'true';
		}

		$unit_options = array( 'px', '%', 'em', 'rem', 'vw', 'vh' );

		/**
		 * Filters the input units.
		 *
		 * @param array $unit_options List of units.
		 */
		$unit_options = apply_filters( 'rmp_input_units', $unit_options );
		?>
		<select id="<?php echo esc_attr( $param['id'] ); ?>" name="<?php echo esc_attr( $param['name'] ); ?>" class="<?php echo esc_attr( $param['classes'] ); ?>" multi-device="<?php echo esc_attr( $has_multi_device ); ?>" >
			<?php
			foreach ( $unit_options as $unit ) {
				$is_selected = '';
				if ( $value === $unit ) {
					$is_selected = 'selected';
				}

				$is_disabled = '';
				if ( ! empty( $param['default'] ) && $param['default'] !== $unit ) {
					$is_disabled = 'disabled';
				}
				?>
				<option <?php echo esc_attr( $is_disabled ); ?> value="<?php echo esc_attr( $unit ); ?>" <?php echo esc_attr( $is_selected ); ?> >
					<?php
					echo esc_attr( $unit );
					if ( ! empty( $param['default'] ) && $param['default'] !== $unit ) {
						esc_html_e( ' (PRO)', 'responsive-menu' );
					}
					?>
				</option>
				<?php
			}
			?>
		</select>
		<?php
	}

	/**
	 * This function prepare the color control.
	 *
	 * @version 4.0.0
	 * @param array $param  List of attributes for a input control
	 *
	 * @return HTML
	 */
	public function add_color_control( $param ) {
		if ( empty( $param ) ) {
			return;
		}

		/**
		 * Filters the color input control attribute.
		 *
		 * @version 4.0.0
		 * @param array  $param List of attribute.
		 */
		$param = apply_filters( 'rmp_before_add_color_control', $param );

		$group_classes = '';

		if ( ! empty( $param['group_classes'] ) ) {
			$group_classes = $param['group_classes'];
		}
		?>
		<div class="rmp-input-control-wrapper <?php echo esc_attr( $group_classes ); ?>">
		<?php

		// Check label is exist.
		if ( ! empty( $param['label'] ) ) {
			?>
			<div class="rmp-input-control-label"> <span> <?php echo esc_html( $param['label'] ); ?> </span>
			<?php
				// Check tooltip text is added or not.
			if ( ! empty( $param['tool_tip'] ) ) {
				$this->get_tool_tip( $param['tool_tip'] );
			}
			?>
			</div>
			<?php
		}
		?>
		<div class="rmp-input-control">
		<?php

		// Check this input has multi device options.
		$has_multi_device = '';
		if ( ! empty( $param['multi_device'] ) ) {
			$has_multi_device = 'multi-device=true';
			$this->get_device_options();
		}

		$class = '';
		if ( ! empty( $param['class'] ) ) {
			$class = $param['class'];
		}
		?>
		<input type="text" data-alpha="true" id="<?php echo esc_attr( $param['id'] ); ?>" name="<?php echo esc_attr( $param['name'] ); ?>" <?php echo esc_attr( $has_multi_device ); ?> class="no-updates rmp-color-input <?php echo esc_attr( $class ); ?>" value="<?php echo esc_attr( $param['value'] ); ?>"></div></div>
		<?php

		/**
		 * Filters the color input control html.
		 *
		 * @version 4.0.0
		 * @param HTML|string Input control contents.
		 * @param array       $param List of attribute.
		 */
		echo apply_filters( 'rmp_color_control_html', '', $param );
	}

	/**
	 * This function prepare the button input control.
	 *
	 * @version 4.0.0
	 * @param array $param  List of attributes for a input control
	 *
	 * @return HTML
	 */
	public function add_button_control( $param ) {
		if ( empty( $param ) ) {
			return;
		}

		/**
		 * Filters the button input control attribute.
		 *
		 * @version 4.0.0
		 * @param array  $param List of attribute.
		 */
		$param = apply_filters( 'rmp_before_add_button_control', $param );

		$group_classes = '';
		if ( ! empty( $param['group_classes'] ) ) {
			$group_classes = $param['group_classes'];
		}
		$class = '';
		if ( ! empty( $param['class'] ) ) {
			$class = $param['class'];
		}
		?>
		<div class="rmp-input-control-wrapper <?php echo esc_attr( $group_classes ); ?>">
			<div class="rmp-input-control">
				<button type="button" id="<?php echo esc_attr( $param['id'] ); ?>" class="button button-primary button-large <?php echo esc_attr( $class ); ?>" ><?php echo esc_html( $param['label'] ); ?></button>
			</div>
		</div>
		<?php

		/**
		 * Filters the button input control html.
		 *
		 * @version 4.0.0
		 *
		 * @param HTML|string Input control contents.
		 * @param array       $param List of attribute.
		 */
		echo apply_filters( 'rmp_button_control_html', '', $param );
	}

	/**
	 * This function prepare the checkbox as switcher input control.
	 *
	 * @version 4.0.0
	 * @param array $param  List of attributes for a input control
	 *
	 * @return HTML
	 */
	public function add_switcher_control( $param ) {
		if ( empty( $param ) ) {
			return;
		}

		/**
		 * Filters the switcher input control attributes.
		 *
		 * @version 4.0.0
		 * @param array  $param List of attribute.
		 */
		$param = apply_filters( 'rmp_before_add_switcher_control', $param );

		$group_classes = '';

		if ( ! empty( $param['group_classes'] ) ) {
			$group_classes = $param['group_classes'];
		}
		?>
		<div class="rmp-input-control-wrapper rmp-input-control-switcher <?php echo esc_attr( $group_classes ); ?>" >
		<?php

		// Check label is exist.
		if ( ! empty( $param['label'] ) ) {
			?>
			<div class="rmp-input-control-label">
					<span> <?php echo esc_html( $param['label'] ); ?> </span>
			<?php
			// Check tooltip text is added or not.
			if ( ! empty( $param['tool_tip'] ) ) {
				$this->get_tool_tip( $param['tool_tip'] );
			}
			?>
				</div>
				<?php
		}

		if ( ! empty( $param['name'] ) ) {
			?>
			<div class="rmp-input-control">
			<?php
				$is_disabled = '';
				// Check feature type.
			if ( ! empty( $param['feature_type'] ) ) {
				$is_disabled = 'disabled';
				?>
					<a target="_blank" rel="noopener" class="upgrade-tooltip" href="<?php echo esc_url( $this->pro_plugin_url ); ?>" > <?php echo esc_html( $param['feature_type'] ); ?> </a>
				<?php
			}

			// Check multi device options is enabled.
			$has_multi_device = '';
			if ( ! empty( $param['multi_device'] ) ) {
				$has_multi_device = 'multi-device=true';
				$this->get_device_options();
			}

			$class = '';
			if ( ! empty( $param['class'] ) ) {
				$class = $param['class'];
			}
			?>
			<input <?php echo esc_attr( $is_disabled ); ?> type="hidden" value="off" name="<?php echo esc_attr( $param['name'] ); ?>"/><input <?php echo esc_attr( $is_disabled ); ?> type="checkbox" id="<?php echo esc_attr( $param['id'] ); ?>" name="<?php echo esc_attr( $param['name'] ); ?>" <?php echo esc_attr( $has_multi_device ); ?> class="toggle <?php echo esc_attr( $class ); ?>" value="on" <?php echo esc_attr( $param['is_checked'] ); ?> ></div>
			<?php
		}
		?>
		</div>
		<?php

		/**
		 * Filters the switcher input control html.
		 *
		 * @version 4.0.0
		 *
		 * @param HTML|string Input control contents.
		 * @param array       $param List of attribute.
		 */
		echo apply_filters( 'rmp_switcher_control_html', '', $param );
	}

	/**
	 * This function prepare the select/dropdown input control.
	 *
	 * @version 4.0.0
	 * @param array $param  List of attributes for a input control
	 *
	 * @return HTML
	 */
	public function add_select_control( $param ) {
		if ( empty( $param ) ) {
			return;
		}

		/**
		 * Filters the select input control attributes.
		 *
		 * @version 4.0.0
		 * @param array  $param List of attribute.
		 */
		$param = apply_filters( 'rmp_before_add_select_control', $param );

		$group_classes = '';

		if ( ! empty( $param['group_classes'] ) ) {
			$group_classes = $param['group_classes'];
		}

		?>
		<div class="rmp-input-control-wrapper <?php echo esc_attr( $group_classes ); ?> ">
		<?php

		// Check label is exist.
		if ( ! empty( $param['label'] ) ) {
			?>
			<div class="rmp-input-control-label">
					<span> <?php echo esc_html( $param['label'] ); ?> </span>
					<span>
					<?php
					// Check tooltip text is added or not.
					if ( ! empty( $param['tool_tip'] ) ) {
						$this->get_tool_tip( $param['tool_tip'] );
					}
					?>
					</span>
					<?php
					// Check feature type.
					if ( ! empty( $param['feature_type'] ) ) {
						?>
					<a target="_blank" rel="noopener" class="upgrade-tooltip" href="<?php echo esc_url( $this->pro_plugin_url ); ?>" > <?php echo esc_html( $param['feature_type'] ); ?> </a>
					<?php } ?>
				</div>
					<?php
		}
		?>
		<div class="rmp-input-control">
		<?php

		// Check multi device options is enabled.
		$has_multi_device = '';
		if ( ! empty( $param['multi_device'] ) ) {
			$has_multi_device = 'multi-device=true';
			$this->get_device_options();
		}

		$class = '';
		if ( ! empty( $param['class'] ) ) {
			$class = $param['class'];
		}

		// Check multiple value is allowed.
		$is_multiple_value_allow = '';
		if ( ! empty( $param['multiple'] ) ) {
			$is_multiple_value_allow = 'multiple';
		}
		?>
		<select id="<?php echo esc_attr( $param['id'] ); ?>" name="<?php echo esc_attr( $param['name'] ); ?>" <?php echo esc_attr( $has_multi_device ); ?> class="<?php echo esc_attr( $class ); ?>" <?php echo esc_attr( $is_multiple_value_allow ); ?>>
			<?php
			if ( ! empty( $param['options'] ) ) {
				foreach ( $param['options'] as $key => $value ) {
					$is_select = '';
					if ( ! empty( $param['value'] ) ) {
						if ( ! empty( $param['multiple'] ) && is_array( $param['value'] ) && in_array( $key, $param['value'], true ) ) {
							$is_select = 'selected';
						} elseif ( $key == $param['value'] ) {
							$is_select = 'selected';
						}
					}

					// Check options is pro.
					$disabled = '';
					if ( strpos( strtolower( $value ), 'pro' ) ) {
						$disabled = 'disabled';
					}
					?>
					<option <?php echo esc_attr( $disabled ); ?> value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $is_select ); ?>> <?php echo esc_html( $value ); ?> </option>
					<?php
				}
			}
			?>
		</select>
		</div></div>
		<?php

		/**
		 * Filters the select input control html.
		 *
		 * @version 4.0.0
		 *
		 * @param HTML|string Input control contents.
		 * @param array       $param List of attribute.
		 */
		echo apply_filters( 'rmp_select_control_html', '', $param );
	}

	/**
	 * This function prepare the tooltip.
	 *
	 * @version 4.0.0
	 * @param array $param  List of attributes for a input control
	 *
	 * @return HTML
	 */
	public function get_tool_tip( $param ) {
		if ( empty( $param['text'] ) ) {
			return;
		}
		?>
		<div class="rmp-tooltip-icon dashicons dashicons-editor-help">
			<span class="rmp-tooltip-content">
				<?php
				echo wp_kses(
					$param['text'],
					array(
						'a' => array(
							'href'  => array(),
							'title' => array(),
						),
					)
				);
				?>
			</span>
		</div>
		<?php
	}

	/**
	 * This function prepare the shortcut.
	 *
	 * @version 4.0.0
	 * @param array $param  List of attributes for a input control
	 *
	 * @return HTML
	 */
	public function add_shortcut_link( $param ) {
		if ( empty( $param ) ) {
			return;
		}

		// Accordion id.
		$accordion_id = '';
		if ( ! empty( $param['accordion_id'] ) ) {
			$accordion_id = $param['accordion_id'];
		}

		// Sub accordion id.
		$sub_accordion_id = '';
		if ( ! empty( $param['sub_accordion_id'] ) ) {
			$sub_accordion_id = $param['sub_accordion_id'];
		}

		// Sub tab id.
		$sub_tab_id = '';
		if ( ! empty( $param['sub_tab_id'] ) ) {
			$sub_tab_id = $param['sub_tab_id'];
		}

		if ( ! empty( $param['label'] ) && ! empty( $param['target'] ) ) {
			?>
			<div class="rmp-quick-edit-link rmp-input-control-wrapper" aria-owns="<?php echo esc_attr( $param['target'] ); ?>" accordion-id="<?php echo esc_attr( $accordion_id ); ?>" sub-accordion-id="<?php echo esc_attr( $sub_accordion_id ); ?>" sub-tab-id="<?php echo esc_attr( $sub_tab_id ); ?>">
				<a href="javascript:void(0)"><?php echo esc_html( $param['label'] ); ?> <i class="fas fa-share"></i></a>
			</div>
			<?php
		}
	}

	/**
	 * This function prepare the icon picker control.
	 *
	 * @version 4.0.0
	 * @param array $param  List of attributes for a input control
	 *
	 * @return HTML
	 */
	public function add_icon_picker_control( $param ) {
		if ( empty( $param ) ) {
			return;
		}

		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();

		$group_classes = '';

		if ( ! empty( $param['group_classes'] ) ) {
			$group_classes = $param['group_classes'];
		}
		$is_disabled = '';
		?>
		<div class="rmp-input-control-wrapper <?php echo esc_attr( $group_classes ); ?>">
		<?php

		// Check label is exist.
		if ( ! empty( $param['label'] ) ) {
			?>
			<div class="rmp-input-control-label">
				<span> <?php echo esc_html( $param['label'] ); ?> </span>
				<span>
			<?php
			// Check tooltip text is added or not.
			if ( ! empty( $param['tool_tip'] ) ) {
				$this->get_tool_tip( $param['tool_tip'] );
			}
			?>
				</span>
					<?php
					// Check feature type.
					if ( ! empty( $param['feature_type'] ) ) {
						$is_disabled = 'disabled';
						?>
						<a target="_blank" rel="noopener" class="upgrade-tooltip" href="<?php echo esc_url( $this->pro_plugin_url ); ?>" > <?php echo esc_html( $param['feature_type'] ); ?></a>
						<?php
					}
					?>
			</div>
					<?php
		}
		?>
		<div class="rmp-input-control rmp-icon-picker-container">
		<?php

		// Check multiple device option enabled.
		$has_multi_device = '';
		if ( ! empty( $param['multi_device'] ) ) {
			$has_multi_device = 'multi-device=true';
			$this->get_device_options();
		}

		$class = '';
		if ( ! empty( $param['class'] ) ) {
			$class = $param['class'];
		}

		$icon_value  = '';
		$is_icon_set = 'false';
		if ( ! empty( $param['value'] ) ) {
			$icon_value  = $param['value'];
			$is_icon_set = 'true';
		}
		?>
		<input type="hidden" placeholder="fa fa-icon" id="<?php echo esc_attr( $param['id'] ); ?>" name="<?php echo esc_attr( $param['name'] ); ?>" <?php echo esc_attr( $has_multi_device ); ?> class="rmp-icon-hidden-input <?php echo esc_attr( $class ); ?>" value="<?php echo esc_attr( $icon_value ); ?>"><div class="rmp-icon-picker <?php echo esc_attr( $param['picker_class'] . $is_disabled ); ?>" for="<?php echo esc_attr( $param['id'] ); ?>" id="<?php echo esc_attr( $param['picker_id'] ); ?>" data-icon="<?php echo esc_attr( $is_icon_set ); ?>">
				<div class="rmp-icon-picker-placeholder">
					<span>
						<?php
						$svg_placeholder = $wp_filesystem->get_contents( RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/image-placeholder.svg' );
						if ( $svg_placeholder ) {
							echo wp_kses( $svg_placeholder, rmp_allow_svg_html_tags() );
						}
						?>
					</span>
					<label> Choose Icon</label>
				</div>
				<?php
				echo wp_kses( $icon_value, rmp_allow_svg_html_tags() );
				if ( ! empty( $param['value'] ) ) {
					?>
			<i class="rmp-icon-picker-trash dashicons dashicons-trash" aria-hidden="true"></i>
					<?php
				}
				?>
			</div>
		</div></div>
		<?php

		/**
		 * Filters the icon picker control html.
		 *
		 * @version 4.0.0
		 *
		 * @param HTML|string Input control contents.
		 * @param array       $param List of attribute.
		 */
		echo apply_filters( 'rmp_icon_picker_control_html', '', $param );
	}

	/**
	 * This function prepare the image input control.
	 *
	 * @version 4.0.0
	 * @param array $param  List of attributes for a input control
	 *
	 * @return HTML
	 */
	public function add_image_control( $param ) {
		if ( empty( $param ) ) {
			return;
		}
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();
		$group_classes = '';

		if ( ! empty( $param['group_classes'] ) ) {
			$group_classes = $param['group_classes'];
		}
		?>
		<div class="rmp-input-control-wrapper <?php echo esc_attr( $group_classes ); ?>">
		<?php

		// Check label is exist.
		if ( ! empty( $param['label'] ) ) {
			?>
			<div class="rmp-input-control-label"> <span> <?php echo esc_html( $param['label'] ); ?> </span>
			<?php
			// Check tooltip text is added or not.
			if ( ! empty( $param['tool_tip'] ) ) {
					$this->get_tool_tip( $param['tool_tip'] );
			}
			?>
			</div>
			<?php
		}
		?>
		<div class="rmp-input-control rmp-image-picker-container">
		<?php

		// Check multi device option is enabled.
		$has_multi_device = '';
		if ( ! empty( $param['multi_device'] ) ) {
			$has_multi_device = 'multi-device=true';
			$this->get_device_options();
		}

		$class = '';
		if ( ! empty( $param['class'] ) ) {
			$class = $param['class'];
		}

		$image_url = '';
		if ( ! empty( $param['value'] ) ) {
			$image_url = $param['value'];
		}
		?>
		<input type="hidden" id="<?php echo esc_attr( $param['id'] ); ?>" name="<?php echo esc_attr( $param['name'] ); ?>" <?php echo esc_attr( $has_multi_device ); ?> class="rmp-image-url-input <?php echo esc_attr( $class ); ?>" value="<?php echo esc_url( $image_url ); ?>"><div class="rmp-image-picker <?php echo esc_attr( $param['picker_class'] ); ?>" for="<?php echo esc_attr( $param['id'] ); ?>" id="<?php echo esc_attr( $param['picker_id'] ); ?>"
			<?php
			if ( ! empty( $param['value'] ) ) {
				echo 'style="background-image: url(' . esc_url( $image_url ) . ');"';
			}
			?>
			>
				<div class="rmp-image-picker-placeholder">
					<span>
						<?php
						$svg_placeholder = $wp_filesystem->get_contents( RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/image-placeholder.svg' );
						if ( $svg_placeholder ) {
							echo wp_kses( $svg_placeholder, rmp_allow_svg_html_tags() );
						}
						?>
					</span>
					<label> Choose Image</label>
				</div>
				<?php
				if ( ! empty( $param['value'] ) ) {
					?>
					<i class="rmp-image-picker-trash dashicons dashicons-trash" aria-hidden="true"></i>
					<?php
				}
				?>
			</div>
		</div></div>
		<?php

		/**
		 * Filters the icon picker control html.
		 *
		 * @version 4.0.0
		 *
		 * @param HTML|string Input control contents.
		 * @param array       $param List of attribute.
		 */
		echo apply_filters( 'rmp_add_image_control_html', '', $param );
	}

	/**
	 * This function prepare the alignment control.
	 *
	 * @version 4.0.0
	 * @param array $param  List of attributes for a input control
	 *
	 * @return HTML
	 */
	public function add_text_alignment_control( $param ) {
		if ( empty( $param ) ) {
			return;
		}

		$group_classes = '';

		if ( ! empty( $param['group_classes'] ) ) {
			$group_classes = $param['group_classes'];
		}
		?>
		<div class="rmp-input-control-wrapper <?php echo esc_attr( $group_classes ); ?>">
		<?php

		// Check label is exist.
		if ( ! empty( $param['label'] ) ) {
			?>
			<div class="rmp-input-control-label"> <span> <?php echo esc_html( $param['label'] ); ?> </span>
			<?php
			// Check tooltip text is added or not.
			if ( ! empty( $param['tool_tip'] ) ) {
				$this->get_tool_tip( $param['tool_tip'] );
			}
			?>
			</div>
			<?php
		}
		?>
		<div class="rmp-input-control">
		<?php

		// Check multiple device options is enabled.
		$has_multi_device = '';
		if ( ! empty( $param['multi_device'] ) ) {
			$has_multi_device = 'multi-device=true';
			$this->get_device_options();
		}

		$class = '';
		if ( ! empty( $param['class'] ) ) {
			$class = $param['class'];
		}
		?>
		<div class="align-icons-group">
			<?php
			foreach ( $param['options'] as $value ) {
				$is_checked = '';
				if ( $param['value'] == $value ) {
					$is_checked = 'checked';
				}
				?>
					<input id="<?php echo esc_attr( $class . '-' . $value ); ?>" type="radio" name="<?php echo esc_attr( $param['name'] ); ?>" <?php echo esc_attr( $has_multi_device ); ?> class="no-updates <?php echo esc_attr( $class ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo esc_attr( $is_checked ); ?> >
						<label for="<?php echo esc_attr( $class . '-' . $value ); ?>">
						<?php
						if ( 'left' == $value ) {
							?>
							<span class="dashicons dashicons-editor-alignleft"></span>
							<?php
						} elseif ( 'justify' == $value ) {
							?>
							<span class="dashicons dashicons-editor-justify"></span>
							<?php
						} elseif ( 'right' == $value ) {
							?>
							<span class="dashicons dashicons-editor-alignright "></span>
							<?php
						} elseif ( 'center' == $value ) {
							?>
							<span class="dashicons dashicons-editor-aligncenter "></span>
							<?php
						}
						?>
						</label>
						<?php
			}
			?>
		</div>
		</div></div>
		<?php

		/**
		 * Filters the icon picker control html.
		 *
		 * @version 4.0.0
		 *
		 * @param HTML|string Input control contents.
		 * @param array       $param List of attribute.
		 */
		echo apply_filters( 'rmp_add_text_alignment_control_html', '', $param );
	}

	/**
	 * Function to prepare the device visibility control,
	 * those are mobile, tablet and desktop as options.
	 *
	 * @version 4.0.0
	 *
	 * @param array $options list of values.
	 *
	 * @return HTML
	 */
	public function add_device_visibility_control( $options ) {
		?>
		<div class="rmp-input-control-wrapper full-size">
				<label class="rmp-input-control-label">
					<?php esc_html_e( 'Device Visibility', 'responsive-menu' ); ?>
					<a target="_blank" rel="noopener" class="upgrade-tooltip" href="<?php echo esc_url( $this->pro_plugin_url ); ?>" > <?php esc_html_e( 'PRO', 'responsive-menu' ); ?> </a>
				</label>
				<div class="rmp-input-control">
					<div class="device-icons-group">
						<div class="device-icon">
							<input type="hidden" name="menu[use_mobile_menu]" value="on"/>
							<input disabled checked class="rmp-menu-display-device checkbox mobile" type="checkbox"/>
							<label for="rmp-menu-display-device-mobile" title="mobile" >
								<span class="corner-icon">
									<i class="fas fa-check-circle" aria-hidden="true"></i>
								</span>
								<span class="device">
									<svg width="15" height="20" viewBox="0 0 15 20" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M7.5 5.625C7.5 5.625 7.49251 5.625 7.47754 5.625C7.28288 5.625 7.10319 5.57259 6.93848 5.46777C6.78874 5.34798 6.68392 5.20573 6.62402 5.04102C6.59408 4.98112 6.57161 4.92122 6.55664 4.86133C6.54167 4.80143 6.53418 4.74154 6.53418 4.68164C6.53418 4.68164 6.53418 4.67415 6.53418 4.65918C6.53418 4.62923 6.53418 4.59928 6.53418 4.56934C6.54915 4.53939 6.55664 4.50195 6.55664 4.45703V4.47949C6.57161 4.44954 6.5791 4.4196 6.5791 4.38965C6.59408 4.3597 6.60905 4.32975 6.62402 4.2998C6.639 4.26986 6.64648 4.23991 6.64648 4.20996C6.66146 4.18001 6.68392 4.15007 6.71387 4.12012C6.72884 4.10514 6.74382 4.08268 6.75879 4.05273C6.77376 4.02279 6.79622 4.00033 6.82617 3.98535C6.90104 3.89551 6.99837 3.82812 7.11816 3.7832C7.23796 3.73828 7.36523 3.71582 7.5 3.71582C7.52995 3.71582 7.5599 3.71582 7.58984 3.71582C7.61979 3.71582 7.64974 3.72331 7.67969 3.73828C7.70964 3.73828 7.73958 3.74577 7.76953 3.76074C7.81445 3.76074 7.8444 3.76823 7.85938 3.7832C7.88932 3.79818 7.91927 3.81315 7.94922 3.82812C7.97917 3.8431 8.00911 3.85807 8.03906 3.87305C8.06901 3.88802 8.09147 3.91048 8.10645 3.94043C8.13639 3.9554 8.15885 3.97038 8.17383 3.98535C8.20378 4.00033 8.22624 4.02279 8.24121 4.05273C8.25618 4.08268 8.27116 4.10514 8.28613 4.12012C8.31608 4.15007 8.33105 4.18001 8.33105 4.20996C8.34603 4.23991 8.361 4.26986 8.37598 4.2998C8.39095 4.32975 8.39844 4.3597 8.39844 4.38965C8.41341 4.4196 8.42839 4.44954 8.44336 4.47949C8.44336 4.50944 8.44336 4.53939 8.44336 4.56934C8.45833 4.59928 8.46582 4.62923 8.46582 4.65918C8.46582 4.73405 8.45833 4.80143 8.44336 4.86133C8.42839 4.92122 8.40592 4.98112 8.37598 5.04102C8.361 5.10091 8.33105 5.16081 8.28613 5.2207C8.25618 5.26562 8.21875 5.31055 8.17383 5.35547C8.08398 5.43034 7.97917 5.49772 7.85938 5.55762C7.75456 5.60254 7.63477 5.625 7.5 5.625ZM9.40918 16.1592C9.40918 15.9046 9.31185 15.6875 9.11719 15.5078C8.9375 15.3132 8.72038 15.2158 8.46582 15.2158H6.53418C6.27962 15.2158 6.05501 15.3132 5.86035 15.5078C5.68066 15.6875 5.59082 15.9046 5.59082 16.1592C5.59082 16.4287 5.68066 16.6608 5.86035 16.8555C6.05501 17.0352 6.27962 17.125 6.53418 17.125H8.46582C8.72038 17.125 8.9375 17.0352 9.11719 16.8555C9.31185 16.6608 9.40918 16.4287 9.40918 16.1592ZM14.2158 16.6533V4.1875C14.2158 3.25911 13.8864 2.47298 13.2275 1.8291C12.5687 1.17025 11.7751 0.84082 10.8467 0.84082H4.15332C3.22493 0.84082 2.43132 1.17025 1.77246 1.8291C1.11361 2.47298 0.78418 3.25911 0.78418 4.1875V16.6533C0.78418 17.5667 1.11361 18.3529 1.77246 19.0117C2.43132 19.6706 3.22493 20 4.15332 20H10.8467C11.7751 20 12.5687 19.6706 13.2275 19.0117C13.8864 18.3529 14.2158 17.5667 14.2158 16.6533ZM10.8467 2.75C11.251 2.75 11.5879 2.89225 11.8574 3.17676C12.1419 3.46126 12.2842 3.79818 12.2842 4.1875V16.6533C12.2842 17.0426 12.1419 17.3796 11.8574 17.6641C11.5879 17.9486 11.251 18.0908 10.8467 18.0908H4.15332C3.74902 18.0908 3.40462 17.9486 3.12012 17.6641C2.85059 17.3796 2.71582 17.0426 2.71582 16.6533V4.1875C2.71582 3.79818 2.85059 3.46126 3.12012 3.17676C3.40462 2.89225 3.74902 2.75 4.15332 2.75H10.8467Z" fill="#56606D"/>
									</svg>
								</span>
							</label>
							<span class="rmp-input-control-label device-title"> <?php esc_html_e( 'Mobile', 'responsive-menu' ); ?> </span>
						</div>
						<div class="device-icon">
							<input type="hidden" name="menu[use_tablet_menu]" value="on"/>
							<input type="hidden" name="menu[use_tablet_menu]" value="on"/>
							<input disabled checked class="rmp-menu-display-device checkbox tablet"  type="checkbox"/>
							<label for="rmp-menu-display-device-tablet" title="tablet" >
								<span class="corner-icon">
									<i class="fas fa-check-circle" aria-hidden="true"></i>
								</span>
								<span class="device">
									<svg width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M12.125 19H3.875C2.98698 19 2.22786 18.6849 1.59766 18.0547C0.981771 17.4245 0.673828 16.6725 0.673828 15.7988V3.875C0.673828 2.98698 0.981771 2.23503 1.59766 1.61914C2.22786 0.988932 2.98698 0.673828 3.875 0.673828H12.125C13.013 0.673828 13.765 0.988932 14.3809 1.61914C15.0111 2.23503 15.3262 2.98698 15.3262 3.875V15.7988C15.3262 16.6725 15.0111 17.4245 14.3809 18.0547C13.765 18.6849 13.013 19 12.125 19ZM3.875 2.5C3.5026 2.5 3.18034 2.63607 2.9082 2.9082C2.63607 3.18034 2.5 3.5026 2.5 3.875V15.7988C2.5 16.1712 2.63607 16.4935 2.9082 16.7656C3.18034 17.0378 3.5026 17.1738 3.875 17.1738H12.125C12.4974 17.1738 12.8197 17.0378 13.0918 16.7656C13.3639 16.4935 13.5 16.1712 13.5 15.7988V3.875C13.5 3.5026 13.3639 3.18034 13.0918 2.9082C12.8197 2.63607 12.4974 2.5 12.125 2.5H3.875ZM8.64453 15.9922C8.73047 15.9062 8.79492 15.806 8.83789 15.6914C8.89518 15.5768 8.92383 15.4622 8.92383 15.3477C8.92383 15.3333 8.92383 15.3262 8.92383 15.3262C8.92383 15.0827 8.83073 14.875 8.64453 14.7031C8.47266 14.5169 8.25781 14.4238 8 14.4238C7.74219 14.4238 7.52018 14.5169 7.33398 14.7031C7.16211 14.875 7.07617 15.0827 7.07617 15.3262C7.07617 15.584 7.16211 15.806 7.33398 15.9922C7.52018 16.1641 7.74219 16.25 8 16.25C8.12891 16.25 8.24349 16.2285 8.34375 16.1855C8.45833 16.1283 8.55859 16.0638 8.64453 15.9922Z" fill="#56606D"/>
									</svg>
								</span>
							</label>
							<span class="rmp-input-control-label device-title"> <?php esc_html_e( 'Tablet', 'responsive-menu' ); ?> </span>
						</div>
						<div class="device-icon">
							<input type="hidden" name="menu[use_desktop_menu]" value="off"/>
							<input disabled class="rmp-menu-display-device checkbox desktop"  type="checkbox" />
							<label for="rmp-menu-display-device-desktop" title="desktop" >
								<span class="corner-icon">
									<i class="fas fa-check-circle" aria-hidden="true"></i>
								</span>
								<span class="device">
									<svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M15.9512 0.673828H4.04883C3.16081 0.673828 2.40169 0.988932 1.77148 1.61914C1.14128 2.23503 0.826172 2.98698 0.826172 3.875V12.125C0.826172 13.013 1.14128 13.7721 1.77148 14.4023C2.40169 15.0182 3.16081 15.3262 4.04883 15.3262H9.07617V17.1738H6.32617C6.08268 17.1738 5.86784 17.2669 5.68164 17.4531C5.50977 17.625 5.42383 17.8327 5.42383 18.0762C5.42383 18.334 5.50977 18.556 5.68164 18.7422C5.86784 18.9141 6.08268 19 6.32617 19H13.6738C13.9173 19 14.125 18.9141 14.2969 18.7422C14.4831 18.556 14.5762 18.334 14.5762 18.0762C14.5762 17.8327 14.4831 17.625 14.2969 17.4531C14.125 17.2669 13.9173 17.1738 13.6738 17.1738H10.9238V15.3262H15.9512C16.8392 15.3262 17.5983 15.0182 18.2285 14.4023C18.8587 13.7721 19.1738 13.013 19.1738 12.125V3.875C19.1738 2.98698 18.8587 2.23503 18.2285 1.61914C17.5983 0.988932 16.8392 0.673828 15.9512 0.673828ZM17.3262 12.125C17.3262 12.4974 17.1901 12.8197 16.918 13.0918C16.6602 13.3639 16.3379 13.5 15.9512 13.5H4.04883C3.66211 13.5 3.33268 13.3639 3.06055 13.0918C2.80273 12.8197 2.67383 12.4974 2.67383 12.125V3.875C2.67383 3.5026 2.80273 3.18034 3.06055 2.9082C3.33268 2.63607 3.66211 2.5 4.04883 2.5H15.9512C16.3379 2.5 16.6602 2.63607 16.918 2.9082C17.1901 3.18034 17.3262 3.5026 17.3262 3.875V12.125ZM7.76562 3.83203C7.83724 3.90365 7.88737 3.98242 7.91602 4.06836C7.95898 4.13997 7.98047 4.22591 7.98047 4.32617C7.98047 4.42643 7.95898 4.51953 7.91602 4.60547C7.88737 4.67708 7.83724 4.7487 7.76562 4.82031L5.01562 7.57031C4.95833 7.6276 4.88672 7.67057 4.80078 7.69922C4.72917 7.72786 4.64323 7.74219 4.54297 7.74219C4.35677 7.74219 4.19206 7.67773 4.04883 7.54883C3.91992 7.41992 3.85547 7.25521 3.85547 7.05469C3.85547 6.96875 3.86979 6.88997 3.89844 6.81836C3.94141 6.73242 3.99154 6.65365 4.04883 6.58203L6.79883 3.83203C6.85612 3.77474 6.92773 3.73177 7.01367 3.70312C7.09961 3.66016 7.19271 3.63867 7.29297 3.63867C7.37891 3.63867 7.46484 3.66016 7.55078 3.70312C7.63672 3.73177 7.70833 3.77474 7.76562 3.83203Z" fill="white"/>
									</svg>
								</span>
							</label>
							<span class="rmp-input-control-label device-title"> <?php esc_html_e( 'Desktop', 'responsive-menu' ); ?> </span>
						</div>
					</div>
				</div>
			</div>
			<?php
	}

	/**
	 * Function to return the device options markup.
	 *
	 * @version 4.0.0
	 *
	 * @return HTML
	 */
	protected function get_device_options() {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();
		?>
		<div class="rmp-device-switcher-holder">
			<a target="_blank" rel="noopener" class="upgrade-tooltip" href="<?php echo esc_url( $this->pro_plugin_url ); ?>" > <?php esc_html_e( 'PRO', 'responsive-menu' ); ?> </a>
			<ul class="select rmp-device-switcher" >
				<li data-device="mobile">
					<?php
					$svg_mobile = $wp_filesystem->get_contents( RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/mobile.svg' );
					if ( $svg_mobile ) {
						echo wp_kses( $svg_mobile, rmp_allow_svg_html_tags() );
					}
					?>
				</li>
				<li data-device="tablet">
					<?php
					$svg_tablet = $wp_filesystem->get_contents( RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/tablet.svg' );
					if ( $svg_tablet ) {
						echo wp_kses( $svg_tablet, rmp_allow_svg_html_tags() );
					}
					?>
				</li>
				<li data-device="desktop">
					<?php
					$svg_desktop = $wp_filesystem->get_contents( RMP_PLUGIN_PATH_V4 . '/assets/admin/icons/svg/desktop.svg' );
					if ( $svg_desktop ) {
						echo wp_kses( $svg_desktop, rmp_allow_svg_html_tags() );
					}
					?>
				</li>
			</ul>
		</div>
		<?php
	}

	public function add_sub_heading( $param ) {
		if ( empty( $param['text'] ) ) {
			return;
		}
		?>
		<div class="rmp-accordion-sub-heading">
			<?php
			echo esc_html( $param['text'] );
			// Check tooltip text is added or not.
			if ( ! empty( $param['tool_tip'] ) ) {
				$this->get_tool_tip( $param['tool_tip'] );
			}
			?>
		</div>
		<?php
	}

	public function upgrade_notice() {
		?>
		<div class="upgrade-options">
			<div class="upgrade-notes">
				<p><?php echo esc_html_e( 'This feature is not available in free version. Upgrade now to use', 'responsive-menu' ); ?> </p>
				<a target="_blank" rel="noopener" href="<?php echo esc_attr( $this->pro_plugin_url ); ?>" class="button"> <?php esc_html_e( 'Upgrade to Pro', 'responsive-menu' ); ?> </a>
			</div>
		</div>
		<?php
	}
}
