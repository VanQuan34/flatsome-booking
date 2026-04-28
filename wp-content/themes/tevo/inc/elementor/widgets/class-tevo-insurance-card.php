<?php
/**
 * Tevo Insurance Card Elementor Widget.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Tevo_Insurance_Card_Widget
 */
class Tevo_Insurance_Card_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'tevo-insurance-card';
	}

	public function get_title() {
		return esc_html__( 'Insurance Card', 'tevo' );
	}

	public function get_icon() {
		return 'eicon-info-box';
	}

	public function get_categories() {
		return array( 'tevo-insurance' );
	}

	public function get_keywords() {
		return array( 'insurance', 'card', 'service', 'tevo' );
	}

	protected function register_controls() {
		// Content Section.
		$this->start_controls_section( 'section_content', array(
			'label' => esc_html__( 'Content', 'tevo' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'icon', array(
			'label'   => esc_html__( 'Icon', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::ICONS,
			'default' => array(
				'value'   => 'fas fa-shield-alt',
				'library' => 'fa-solid',
			),
		) );

		$this->add_control( 'title', array(
			'label'       => esc_html__( 'Title', 'tevo' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'default'     => esc_html__( 'Life Insurance', 'tevo' ),
			'label_block' => true,
		) );

		$this->add_control( 'description', array(
			'label'   => esc_html__( 'Description', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::TEXTAREA,
			'default' => esc_html__( 'Protect your family\'s future with our comprehensive life insurance plans.', 'tevo' ),
		) );

		$this->add_control( 'button_text', array(
			'label'   => esc_html__( 'Button Text', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'Learn More', 'tevo' ),
		) );

		$this->add_control( 'button_link', array(
			'label'       => esc_html__( 'Button Link', 'tevo' ),
			'type'        => \Elementor\Controls_Manager::URL,
			'placeholder' => esc_html__( 'https://your-link.com', 'tevo' ),
			'default'     => array( 'url' => '#' ),
		) );

		$this->end_controls_section();

		// Style Section.
		$this->start_controls_section( 'section_style', array(
			'label' => esc_html__( 'Style', 'tevo' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'card_bg_color', array(
			'label'     => esc_html__( 'Background Color', 'tevo' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => array(
				'{{WRAPPER}} .tevo-insurance-card' => 'background-color: {{VALUE}};',
			),
		) );

		$this->add_control( 'icon_color', array(
			'label'     => esc_html__( 'Icon Color', 'tevo' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'default'   => '#1a3a5c',
			'selectors' => array(
				'{{WRAPPER}} .tevo-insurance-card__icon' => 'color: {{VALUE}};',
				'{{WRAPPER}} .tevo-insurance-card__icon svg' => 'fill: {{VALUE}};',
			),
		) );

		$this->add_control( 'title_color', array(
			'label'     => esc_html__( 'Title Color', 'tevo' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .tevo-insurance-card__title' => 'color: {{VALUE}};',
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'tevo-insurance-card' );

		$link_attrs = '';
		if ( ! empty( $settings['button_link']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['button_link'] );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="tevo-insurance-card__icon">
				<?php \Elementor\Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
			</div>
			<h3 class="tevo-insurance-card__title"><?php echo esc_html( $settings['title'] ); ?></h3>
			<p class="tevo-insurance-card__desc"><?php echo esc_html( $settings['description'] ); ?></p>
			<?php if ( ! empty( $settings['button_text'] ) ) : ?>
				<a <?php $this->print_render_attribute_string( 'button' ); ?> class="tevo-insurance-card__btn">
					<?php echo esc_html( $settings['button_text'] ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function content_template() {
		?>
		<div class="tevo-insurance-card">
			<div class="tevo-insurance-card__icon">
				<# var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' ); #>
				<# if ( iconHTML && iconHTML.rendered ) { #>
					{{{ iconHTML.value }}}
				<# } #>
			</div>
			<h3 class="tevo-insurance-card__title">{{{ settings.title }}}</h3>
			<p class="tevo-insurance-card__desc">{{{ settings.description }}}</p>
			<# if ( settings.button_text ) { #>
				<a href="{{ settings.button_link.url }}" class="tevo-insurance-card__btn">
					{{{ settings.button_text }}}
				</a>
			<# } #>
		</div>
		<?php
	}
}
