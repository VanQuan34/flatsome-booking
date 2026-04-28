<?php
/**
 * Tevo Quote Form Widget.
 * @package Tevo
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Tevo_Quote_Form_Widget extends \Elementor\Widget_Base {
	public function get_name() { return 'tevo-quote-form'; }
	public function get_title() { return esc_html__( 'Quote Form', 'tevo' ); }
	public function get_icon() { return 'eicon-form-horizontal'; }
	public function get_categories() { return array( 'tevo-insurance' ); }

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => esc_html__( 'Content', 'tevo' ) ) );
		$this->add_control( 'title', array( 'label' => esc_html__( 'Title', 'tevo' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Get a Free Quote' ) );
		$this->add_control( 'subtitle', array( 'label' => esc_html__( 'Subtitle', 'tevo' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Fill out the form below' ) );
		$this->add_control( 'cf7_shortcode', array(
			'label' => esc_html__( 'Contact Form 7 Shortcode', 'tevo' ),
			'type'  => \Elementor\Controls_Manager::TEXTAREA,
			'default' => '[contact-form-7 id="123" title="Quote Form"]',
			'description' => esc_html__( 'Paste your Contact Form 7 shortcode here.', 'tevo' ),
		) );
		$this->add_control( 'bg_style', array( 'label' => esc_html__( 'Style', 'tevo' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'light', 'options' => array( 'light' => 'Light', 'dark' => 'Dark', 'gradient' => 'Gradient' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<div class="tevo-quote-form tevo-quote-form--<?php echo esc_attr( $s['bg_style'] ); ?>">
			<h3 class="tevo-quote-form__title"><?php echo esc_html( $s['title'] ); ?></h3>
			<p class="tevo-quote-form__subtitle"><?php echo esc_html( $s['subtitle'] ); ?></p>
			<div class="tevo-quote-form__body">
				<?php echo do_shortcode( $s['cf7_shortcode'] ); ?>
			</div>
		</div>
		<?php
	}
}
