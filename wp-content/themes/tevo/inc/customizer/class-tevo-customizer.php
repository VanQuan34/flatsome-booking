<?php
/**
 * Tevo Customizer.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Tevo_Customizer
 */
class Tevo_Customizer {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register' ) );
		add_action( 'customize_preview_init', array( $this, 'preview_scripts' ) );
	}

	/**
	 * Register Customizer settings and controls.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	public function register( $wp_customize ) {
		// --- Panel: Tevo Theme Options ---
		$wp_customize->add_panel( 'tevo_panel', array(
			'title'    => esc_html__( 'Tevo Theme Options', 'tevo' ),
			'priority' => 30,
		) );

		$this->register_general_section( $wp_customize );
		$this->register_header_section( $wp_customize );
		$this->register_colors_section( $wp_customize );
		$this->register_typography_section( $wp_customize );
		$this->register_footer_section( $wp_customize );
		$this->register_blog_section( $wp_customize );
	}

	/**
	 * General section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 */
	private function register_general_section( $wp_customize ) {
		$wp_customize->add_section( 'tevo_general', array(
			'title' => esc_html__( 'General', 'tevo' ),
			'panel' => 'tevo_panel',
		) );

		// Preloader.
		$wp_customize->add_setting( 'tevo_preloader', array(
			'default'           => true,
			'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'tevo_preloader', array(
			'label'   => esc_html__( 'Enable Preloader', 'tevo' ),
			'section' => 'tevo_general',
			'type'    => 'checkbox',
		) );

		// Scroll to top.
		$wp_customize->add_setting( 'tevo_scroll_to_top', array(
			'default'           => true,
			'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'tevo_scroll_to_top', array(
			'label'   => esc_html__( 'Enable Scroll to Top Button', 'tevo' ),
			'section' => 'tevo_general',
			'type'    => 'checkbox',
		) );
	}

	/**
	 * Header section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 */
	private function register_header_section( $wp_customize ) {
		$wp_customize->add_section( 'tevo_header', array(
			'title' => esc_html__( 'Header', 'tevo' ),
			'panel' => 'tevo_panel',
		) );

		// Header style.
		$wp_customize->add_setting( 'tevo_header_style', array(
			'default'           => 'default',
			'sanitize_callback' => array( $this, 'sanitize_select' ),
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'tevo_header_style', array(
			'label'   => esc_html__( 'Header Style', 'tevo' ),
			'section' => 'tevo_header',
			'type'    => 'select',
			'choices' => array(
				'default'     => esc_html__( 'Default', 'tevo' ),
				'transparent' => esc_html__( 'Transparent', 'tevo' ),
				'centered'    => esc_html__( 'Centered', 'tevo' ),
			),
		) );

		// Sticky header.
		$wp_customize->add_setting( 'tevo_sticky_header', array(
			'default'           => true,
			'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
		) );
		$wp_customize->add_control( 'tevo_sticky_header', array(
			'label'   => esc_html__( 'Enable Sticky Header', 'tevo' ),
			'section' => 'tevo_header',
			'type'    => 'checkbox',
		) );

		// Top bar enable.
		$wp_customize->add_setting( 'tevo_topbar_enable', array(
			'default'           => true,
			'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
		) );
		$wp_customize->add_control( 'tevo_topbar_enable', array(
			'label'   => esc_html__( 'Enable Top Bar', 'tevo' ),
			'section' => 'tevo_header',
			'type'    => 'checkbox',
		) );

		// Top bar phone.
		$wp_customize->add_setting( 'tevo_topbar_phone', array(
			'default'           => '+1 (800) 123-4567',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'tevo_topbar_phone', array(
			'label'   => esc_html__( 'Phone Number', 'tevo' ),
			'section' => 'tevo_header',
			'type'    => 'text',
		) );

		// Top bar email.
		$wp_customize->add_setting( 'tevo_topbar_email', array(
			'default'           => 'info@tevo-insurance.com',
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'tevo_topbar_email', array(
			'label'   => esc_html__( 'Email Address', 'tevo' ),
			'section' => 'tevo_header',
			'type'    => 'email',
		) );

		// Top bar hours.
		$wp_customize->add_setting( 'tevo_topbar_hours', array(
			'default'           => 'Mon - Fri: 9:00 AM - 6:00 PM',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'tevo_topbar_hours', array(
			'label'   => esc_html__( 'Working Hours', 'tevo' ),
			'section' => 'tevo_header',
			'type'    => 'text',
		) );

		// Header CTA text.
		$wp_customize->add_setting( 'tevo_header_cta_text', array(
			'default'           => 'Get a Quote',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'tevo_header_cta_text', array(
			'label'   => esc_html__( 'CTA Button Text', 'tevo' ),
			'section' => 'tevo_header',
			'type'    => 'text',
		) );

		// Header CTA link.
		$wp_customize->add_setting( 'tevo_header_cta_link', array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'tevo_header_cta_link', array(
			'label'   => esc_html__( 'CTA Button Link', 'tevo' ),
			'section' => 'tevo_header',
			'type'    => 'url',
		) );
	}

	/**
	 * Colors section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 */
	private function register_colors_section( $wp_customize ) {
		$wp_customize->add_section( 'tevo_colors', array(
			'title' => esc_html__( 'Colors', 'tevo' ),
			'panel' => 'tevo_panel',
		) );

		$colors = array(
			'tevo_primary_color'   => array( esc_html__( 'Primary Color', 'tevo' ), '#1a3a5c' ),
			'tevo_secondary_color' => array( esc_html__( 'Secondary Color', 'tevo' ), '#f0a030' ),
			'tevo_accent_color'    => array( esc_html__( 'Accent Color', 'tevo' ), '#2ecc71' ),
			'tevo_text_color'      => array( esc_html__( 'Text Color', 'tevo' ), '#4a5568' ),
			'tevo_heading_color'   => array( esc_html__( 'Heading Color', 'tevo' ), '#1a202c' ),
		);

		foreach ( $colors as $key => $data ) {
			$wp_customize->add_setting( $key, array(
				'default'           => $data[1],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array(
				'label'   => $data[0],
				'section' => 'tevo_colors',
			) ) );
		}
	}

	/**
	 * Typography section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 */
	private function register_typography_section( $wp_customize ) {
		$wp_customize->add_section( 'tevo_typography', array(
			'title' => esc_html__( 'Typography', 'tevo' ),
			'panel' => 'tevo_panel',
		) );

		$fonts = array(
			'Inter'      => 'Inter',
			'Outfit'     => 'Outfit',
			'Roboto'     => 'Roboto',
			'Poppins'    => 'Poppins',
			'Open Sans'  => 'Open Sans',
			'Montserrat' => 'Montserrat',
			'Lato'       => 'Lato',
			'Nunito'     => 'Nunito',
		);

		// Heading font.
		$wp_customize->add_setting( 'tevo_heading_font', array(
			'default'           => 'Outfit',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'tevo_heading_font', array(
			'label'   => esc_html__( 'Heading Font Family', 'tevo' ),
			'section' => 'tevo_typography',
			'type'    => 'select',
			'choices' => $fonts,
		) );

		// Body font.
		$wp_customize->add_setting( 'tevo_body_font', array(
			'default'           => 'Inter',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'tevo_body_font', array(
			'label'   => esc_html__( 'Body Font Family', 'tevo' ),
			'section' => 'tevo_typography',
			'type'    => 'select',
			'choices' => $fonts,
		) );
	}

	/**
	 * Footer section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 */
	private function register_footer_section( $wp_customize ) {
		$wp_customize->add_section( 'tevo_footer', array(
			'title' => esc_html__( 'Footer', 'tevo' ),
			'panel' => 'tevo_panel',
		) );

		// Footer columns.
		$wp_customize->add_setting( 'tevo_footer_columns', array(
			'default'           => 4,
			'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'tevo_footer_columns', array(
			'label'   => esc_html__( 'Footer Columns', 'tevo' ),
			'section' => 'tevo_footer',
			'type'    => 'select',
			'choices' => array(
				2 => '2',
				3 => '3',
				4 => '4',
			),
		) );

		// Footer copyright.
		$wp_customize->add_setting( 'tevo_footer_copyright', array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'tevo_footer_copyright', array(
			'label'   => esc_html__( 'Copyright Text', 'tevo' ),
			'section' => 'tevo_footer',
			'type'    => 'textarea',
		) );
	}

	/**
	 * Blog section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 */
	private function register_blog_section( $wp_customize ) {
		$wp_customize->add_section( 'tevo_blog', array(
			'title' => esc_html__( 'Blog', 'tevo' ),
			'panel' => 'tevo_panel',
		) );

		// Sidebar position.
		$wp_customize->add_setting( 'tevo_sidebar_position', array(
			'default'           => 'right',
			'sanitize_callback' => array( $this, 'sanitize_select' ),
		) );
		$wp_customize->add_control( 'tevo_sidebar_position', array(
			'label'   => esc_html__( 'Sidebar Position', 'tevo' ),
			'section' => 'tevo_blog',
			'type'    => 'select',
			'choices' => array(
				'left'  => esc_html__( 'Left', 'tevo' ),
				'right' => esc_html__( 'Right', 'tevo' ),
				'none'  => esc_html__( 'No Sidebar', 'tevo' ),
			),
		) );

		// Excerpt length.
		$wp_customize->add_setting( 'tevo_excerpt_length', array(
			'default'           => 25,
			'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'tevo_excerpt_length', array(
			'label'       => esc_html__( 'Excerpt Length (words)', 'tevo' ),
			'section'     => 'tevo_blog',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 10,
				'max'  => 100,
				'step' => 5,
			),
		) );
	}

	/**
	 * Enqueue Customizer preview scripts.
	 *
	 * @return void
	 */
	public function preview_scripts() {
		wp_enqueue_script(
			'tevo-customizer-preview',
			TEVO_ASSETS_URI . '/js/customizer-preview.js',
			array( 'customize-preview', 'jquery' ),
			TEVO_VERSION,
			true
		);
	}

	/**
	 * Sanitize checkbox.
	 *
	 * @param mixed $checked Value to sanitize.
	 * @return bool
	 */
	public function sanitize_checkbox( $checked ) {
		return ( ( isset( $checked ) && true === $checked ) ? true : false );
	}

	/**
	 * Sanitize select.
	 *
	 * @param string               $input   Value to sanitize.
	 * @param WP_Customize_Setting $setting Setting instance.
	 * @return string
	 */
	public function sanitize_select( $input, $setting ) {
		$input   = sanitize_key( $input );
		$choices = $setting->manager->get_control( $setting->id )->choices;
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
}

new Tevo_Customizer();
