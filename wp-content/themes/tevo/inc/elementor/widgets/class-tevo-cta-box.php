<?php
/**
 * Tevo CTA Box Widget.
 * @package Tevo
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Tevo_CTA_Box_Widget extends \Elementor\Widget_Base {
	public function get_name() { return 'tevo-cta-box'; }
	public function get_title() { return esc_html__( 'CTA Box', 'tevo' ); }
	public function get_icon() { return 'eicon-call-to-action'; }
	public function get_categories() { return array( 'tevo-insurance' ); }

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => esc_html__( 'Content', 'tevo' ) ) );
		$this->add_control( 'title', array( 'label' => esc_html__( 'Title', 'tevo' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Ready to Protect Your Future?' ) );
		$this->add_control( 'description', array( 'label' => esc_html__( 'Description', 'tevo' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Contact us today for a free consultation and personalized quote.' ) );
		$this->add_control( 'button_text', array( 'label' => esc_html__( 'Button Text', 'tevo' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Get Started' ) );
		$this->add_control( 'button_link', array( 'label' => esc_html__( 'Button Link', 'tevo' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => array( 'url' => '#' ) ) );
		$this->add_control( 'bg_color', array( 'label' => esc_html__( 'Background', 'tevo' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#1a3a5c', 'selectors' => array( '{{WRAPPER}} .tevo-cta' => 'background-color: {{VALUE}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		if ( ! empty( $s['button_link']['url'] ) ) {
			$this->add_link_attributes( 'btn', $s['button_link'] );
		}
		?>
		<div class="tevo-cta">
			<h3 class="tevo-cta__title"><?php echo esc_html( $s['title'] ); ?></h3>
			<p class="tevo-cta__desc"><?php echo esc_html( $s['description'] ); ?></p>
			<a <?php $this->print_render_attribute_string( 'btn' ); ?> class="tevo-cta__btn tevo-btn tevo-btn--secondary">
				<?php echo esc_html( $s['button_text'] ); ?>
			</a>
		</div>
		<?php
	}
}
