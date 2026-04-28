<?php
/**
 * Tevo Elementor Integration.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Tevo_Elementor
 */
class Tevo_Elementor {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_categories' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_widget_styles' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'editor_scripts' ) );

		// Register Elementor locations for Theme Builder.
		add_action( 'elementor/theme/register_locations', array( $this, 'register_locations' ) );
	}

	/**
	 * Register custom widget category.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elements manager instance.
	 * @return void
	 */
	public function register_categories( $elements_manager ) {
		$elements_manager->add_category( 'tevo-insurance', array(
			'title' => esc_html__( 'Tevo Insurance', 'tevo' ),
			'icon'  => 'eicon-shield',
		) );
	}

	/**
	 * Register custom Elementor widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager instance.
	 * @return void
	 */
	public function register_widgets( $widgets_manager ) {
		// Load widget files.
		$widgets = array(
			'insurance-card',
			'pricing-table',
			'quote-form',
			'team-member',
			'testimonial',
			'counter',
			'cta-box',
			'icon-box',
		);

		foreach ( $widgets as $widget ) {
			$file = TEVO_INC_DIR . '/elementor/widgets/class-tevo-' . $widget . '.php';
			if ( file_exists( $file ) ) {
				require_once $file;
			}
		}

		// Register widgets.
		$widget_classes = array(
			'Tevo_Insurance_Card_Widget',
			'Tevo_Pricing_Table_Widget',
			'Tevo_Quote_Form_Widget',
			'Tevo_Team_Member_Widget',
			'Tevo_Testimonial_Widget',
			'Tevo_Counter_Widget',
			'Tevo_CTA_Box_Widget',
			'Tevo_Icon_Box_Widget',
		);

		foreach ( $widget_classes as $class ) {
			if ( class_exists( $class ) ) {
				$widgets_manager->register( new $class() );
			}
		}
	}

	/**
	 * Enqueue widget styles on the frontend.
	 *
	 * @return void
	 */
	public function enqueue_widget_styles() {
		wp_enqueue_style(
			'tevo-elementor-widgets',
			TEVO_ASSETS_URI . '/css/elementor-widgets.css',
			array(),
			TEVO_VERSION
		);
	}

	/**
	 * Enqueue editor scripts.
	 *
	 * @return void
	 */
	public function editor_scripts() {
		wp_enqueue_style(
			'tevo-elementor-editor',
			TEVO_ASSETS_URI . '/css/admin.css',
			array(),
			TEVO_VERSION
		);
	}

	/**
	 * Register Elementor Theme Builder locations.
	 *
	 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $location_manager Location manager.
	 * @return void
	 */
	public function register_locations( $location_manager ) {
		$location_manager->register_all_core_location();
	}
}

new Tevo_Elementor();
