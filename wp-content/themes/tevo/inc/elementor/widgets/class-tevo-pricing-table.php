<?php
/**
 * Tevo Pricing Table Elementor Widget.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Tevo_Pricing_Table_Widget extends \Elementor\Widget_Base {

	public function get_name() { return 'tevo-pricing-table'; }
	public function get_title() { return esc_html__( 'Pricing Table', 'tevo' ); }
	public function get_icon() { return 'eicon-price-table'; }
	public function get_categories() { return array( 'tevo-insurance' ); }
	public function get_keywords() { return array( 'pricing', 'table', 'plan', 'insurance', 'tevo' ); }

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array(
			'label' => esc_html__( 'Content', 'tevo' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'is_featured', array(
			'label'        => esc_html__( 'Featured Plan', 'tevo' ),
			'type'         => \Elementor\Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Yes', 'tevo' ),
			'label_off'    => esc_html__( 'No', 'tevo' ),
			'return_value' => 'yes',
			'default'      => '',
		) );

		$this->add_control( 'badge_text', array(
			'label'     => esc_html__( 'Badge Text', 'tevo' ),
			'type'      => \Elementor\Controls_Manager::TEXT,
			'default'   => esc_html__( 'Popular', 'tevo' ),
			'condition' => array( 'is_featured' => 'yes' ),
		) );

		$this->add_control( 'plan_name', array(
			'label'   => esc_html__( 'Plan Name', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'Standard Plan', 'tevo' ),
		) );

		$this->add_control( 'price', array(
			'label'   => esc_html__( 'Price', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => '49',
		) );

		$this->add_control( 'currency', array(
			'label'   => esc_html__( 'Currency', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => '$',
		) );

		$this->add_control( 'period', array(
			'label'   => esc_html__( 'Period', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( '/month', 'tevo' ),
		) );

		$this->add_control( 'description', array(
			'label'   => esc_html__( 'Description', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::TEXTAREA,
			'default' => esc_html__( 'Perfect for individuals and families', 'tevo' ),
		) );

		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'feature_text', array(
			'label'   => esc_html__( 'Feature', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'Feature item', 'tevo' ),
		) );
		$repeater->add_control( 'is_included', array(
			'label'        => esc_html__( 'Included', 'tevo' ),
			'type'         => \Elementor\Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
		) );

		$this->add_control( 'features', array(
			'label'   => esc_html__( 'Features', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::REPEATER,
			'fields'  => $repeater->get_controls(),
			'default' => array(
				array( 'feature_text' => esc_html__( 'Personal Accident Cover', 'tevo' ), 'is_included' => 'yes' ),
				array( 'feature_text' => esc_html__( 'Health Insurance', 'tevo' ), 'is_included' => 'yes' ),
				array( 'feature_text' => esc_html__( '24/7 Support', 'tevo' ), 'is_included' => 'yes' ),
				array( 'feature_text' => esc_html__( 'Family Coverage', 'tevo' ), 'is_included' => '' ),
			),
			'title_field' => '{{{ feature_text }}}',
		) );

		$this->add_control( 'button_text', array(
			'label'   => esc_html__( 'Button Text', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'Choose Plan', 'tevo' ),
		) );

		$this->add_control( 'button_link', array(
			'label'   => esc_html__( 'Button Link', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::URL,
			'default' => array( 'url' => '#' ),
		) );

		$this->end_controls_section();

		// Style Section.
		$this->start_controls_section( 'section_style', array(
			'label' => esc_html__( 'Style', 'tevo' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'primary_color', array(
			'label'   => esc_html__( 'Primary Color', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::COLOR,
			'default' => '#1a3a5c',
			'selectors' => array(
				'{{WRAPPER}} .tevo-pricing__btn' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .tevo-pricing__badge' => 'background-color: {{VALUE}};',
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$is_featured = 'yes' === $settings['is_featured'];

		if ( ! empty( $settings['button_link']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['button_link'] );
		}
		?>
		<div class="tevo-pricing <?php echo $is_featured ? 'tevo-pricing--featured' : ''; ?>">
			<?php if ( $is_featured && ! empty( $settings['badge_text'] ) ) : ?>
				<span class="tevo-pricing__badge"><?php echo esc_html( $settings['badge_text'] ); ?></span>
			<?php endif; ?>

			<div class="tevo-pricing__header">
				<h3 class="tevo-pricing__name"><?php echo esc_html( $settings['plan_name'] ); ?></h3>
				<div class="tevo-pricing__price">
					<span class="tevo-pricing__currency"><?php echo esc_html( $settings['currency'] ); ?></span>
					<span class="tevo-pricing__amount"><?php echo esc_html( $settings['price'] ); ?></span>
					<span class="tevo-pricing__period"><?php echo esc_html( $settings['period'] ); ?></span>
				</div>
				<?php if ( ! empty( $settings['description'] ) ) : ?>
					<p class="tevo-pricing__desc"><?php echo esc_html( $settings['description'] ); ?></p>
				<?php endif; ?>
			</div>

			<ul class="tevo-pricing__features">
				<?php foreach ( $settings['features'] as $feature ) : ?>
					<li class="tevo-pricing__feature <?php echo 'yes' !== $feature['is_included'] ? 'tevo-pricing__feature--excluded' : ''; ?>">
						<?php if ( 'yes' === $feature['is_included'] ) : ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
						<?php else : ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
						<?php endif; ?>
						<?php echo esc_html( $feature['feature_text'] ); ?>
					</li>
				<?php endforeach; ?>
			</ul>

			<a <?php $this->print_render_attribute_string( 'button' ); ?> class="tevo-pricing__btn tevo-btn">
				<?php echo esc_html( $settings['button_text'] ); ?>
			</a>
		</div>
		<?php
	}

	protected function content_template() {
		?>
		<div class="tevo-pricing <# if ( settings.is_featured === 'yes' ) { #>tevo-pricing--featured<# } #>">
			<# if ( settings.is_featured === 'yes' && settings.badge_text ) { #>
				<span class="tevo-pricing__badge">{{{ settings.badge_text }}}</span>
			<# } #>
			<div class="tevo-pricing__header">
				<h3 class="tevo-pricing__name">{{{ settings.plan_name }}}</h3>
				<div class="tevo-pricing__price">
					<span class="tevo-pricing__currency">{{{ settings.currency }}}</span>
					<span class="tevo-pricing__amount">{{{ settings.price }}}</span>
					<span class="tevo-pricing__period">{{{ settings.period }}}</span>
				</div>
				<# if ( settings.description ) { #>
					<p class="tevo-pricing__desc">{{{ settings.description }}}</p>
				<# } #>
			</div>
			<ul class="tevo-pricing__features">
				<# _.each( settings.features, function( feature ) { #>
					<li class="tevo-pricing__feature <# if ( feature.is_included !== 'yes' ) { #>tevo-pricing__feature--excluded<# } #>">
						{{{ feature.feature_text }}}
					</li>
				<# }); #>
			</ul>
			<a href="{{ settings.button_link.url }}" class="tevo-pricing__btn tevo-btn">{{{ settings.button_text }}}</a>
		</div>
		<?php
	}
}
