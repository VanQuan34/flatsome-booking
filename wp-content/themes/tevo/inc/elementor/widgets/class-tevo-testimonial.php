<?php
/**
 * Tevo Testimonial Widget.
 * @package Tevo
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Tevo_Testimonial_Widget extends \Elementor\Widget_Base {
	public function get_name() { return 'tevo-testimonial'; }
	public function get_title() { return esc_html__( 'Testimonial', 'tevo' ); }
	public function get_icon() { return 'eicon-testimonial'; }
	public function get_categories() { return array( 'tevo-insurance' ); }

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => esc_html__( 'Content', 'tevo' ) ) );
		$this->add_control( 'avatar', array( 'label' => esc_html__( 'Avatar', 'tevo' ), 'type' => \Elementor\Controls_Manager::MEDIA ) );
		$this->add_control( 'name', array( 'label' => esc_html__( 'Name', 'tevo' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Jane Doe' ) );
		$this->add_control( 'company', array( 'label' => esc_html__( 'Company', 'tevo' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'CEO, Company' ) );
		$this->add_control( 'rating', array( 'label' => esc_html__( 'Rating', 'tevo' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'min' => 1, 'max' => 5, 'default' => 5 ) );
		$this->add_control( 'review', array( 'label' => esc_html__( 'Review', 'tevo' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Excellent insurance service! They helped us find the perfect coverage for our business.' ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<div class="tevo-testimonial">
			<div class="tevo-testimonial__stars">
				<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
					<span class="tevo-testimonial__star <?php echo $i <= absint( $s['rating'] ) ? 'tevo-testimonial__star--filled' : ''; ?>">&#9733;</span>
				<?php endfor; ?>
			</div>
			<blockquote class="tevo-testimonial__text"><?php echo esc_html( $s['review'] ); ?></blockquote>
			<div class="tevo-testimonial__author">
				<?php if ( ! empty( $s['avatar']['id'] ) ) : ?>
					<?php echo wp_get_attachment_image( $s['avatar']['id'], 'thumbnail', false, array( 'class' => 'tevo-testimonial__avatar' ) ); ?>
				<?php endif; ?>
				<div>
					<strong class="tevo-testimonial__name"><?php echo esc_html( $s['name'] ); ?></strong>
					<span class="tevo-testimonial__company"><?php echo esc_html( $s['company'] ); ?></span>
				</div>
			</div>
		</div>
		<?php
	}
}
