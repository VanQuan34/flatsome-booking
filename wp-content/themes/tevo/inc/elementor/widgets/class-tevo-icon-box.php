<?php
/**
 * Tevo Icon Box Widget.
 * @package Tevo
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Tevo_Icon_Box_Widget extends \Elementor\Widget_Base {
	public function get_name() { return 'tevo-icon-box'; }
	public function get_title() { return esc_html__( 'Icon Box', 'tevo' ); }
	public function get_icon() { return 'eicon-icon-box'; }
	public function get_categories() { return array( 'tevo-insurance' ); }

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => esc_html__( 'Content', 'tevo' ) ) );
		$this->add_control( 'icon', array( 'label' => esc_html__( 'Icon', 'tevo' ), 'type' => \Elementor\Controls_Manager::ICONS, 'default' => array( 'value' => 'fas fa-shield-alt', 'library' => 'fa-solid' ) ) );
		$this->add_control( 'title', array( 'label' => esc_html__( 'Title', 'tevo' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Our Service' ) );
		$this->add_control( 'description', array( 'label' => esc_html__( 'Description', 'tevo' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'We provide top quality insurance services for your peace of mind.' ) );
		$this->add_control( 'link', array( 'label' => esc_html__( 'Link', 'tevo' ), 'type' => \Elementor\Controls_Manager::URL ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style', array( 'label' => esc_html__( 'Style', 'tevo' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'icon_color', array( 'label' => esc_html__( 'Icon Color', 'tevo' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#1a3a5c', 'selectors' => array( '{{WRAPPER}} .tevo-icon-box__icon' => 'color: {{VALUE}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<div class="tevo-icon-box">
			<div class="tevo-icon-box__icon">
				<?php \Elementor\Icons_Manager::render_icon( $s['icon'], array( 'aria-hidden' => 'true' ) ); ?>
			</div>
			<h4 class="tevo-icon-box__title"><?php echo esc_html( $s['title'] ); ?></h4>
			<p class="tevo-icon-box__desc"><?php echo esc_html( $s['description'] ); ?></p>
			<?php if ( ! empty( $s['link']['url'] ) ) : ?>
				<a href="<?php echo esc_url( $s['link']['url'] ); ?>" class="tevo-icon-box__link"><?php esc_html_e( 'Read More', 'tevo' ); ?> &rarr;</a>
			<?php endif; ?>
		</div>
		<?php
	}
}
