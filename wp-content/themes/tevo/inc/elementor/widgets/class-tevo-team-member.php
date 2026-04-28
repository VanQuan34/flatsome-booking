<?php
/**
 * Tevo Team Member Elementor Widget.
 *
 * @package Tevo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Tevo_Team_Member_Widget extends \Elementor\Widget_Base {

	public function get_name() { return 'tevo-team-member'; }
	public function get_title() { return esc_html__( 'Team Member', 'tevo' ); }
	public function get_icon() { return 'eicon-person'; }
	public function get_categories() { return array( 'tevo-insurance' ); }

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array(
			'label' => esc_html__( 'Content', 'tevo' ),
		) );
		$this->add_control( 'photo', array(
			'label' => esc_html__( 'Photo', 'tevo' ),
			'type'  => \Elementor\Controls_Manager::MEDIA,
			'default' => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
		) );
		$this->add_control( 'name', array(
			'label'   => esc_html__( 'Name', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => 'John Smith',
		) );
		$this->add_control( 'position', array(
			'label'   => esc_html__( 'Position', 'tevo' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => 'Insurance Agent',
		) );
		$this->add_control( 'bio', array(
			'label' => esc_html__( 'Bio', 'tevo' ),
			'type'  => \Elementor\Controls_Manager::TEXTAREA,
			'default' => 'Experienced insurance professional.',
		) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<div class="tevo-team-member">
			<div class="tevo-team-member__photo">
				<?php echo wp_get_attachment_image( $s['photo']['id'], 'tevo-team', false, array( 'class' => 'tevo-team-member__img' ) ); ?>
			</div>
			<div class="tevo-team-member__info">
				<h4 class="tevo-team-member__name"><?php echo esc_html( $s['name'] ); ?></h4>
				<span class="tevo-team-member__position"><?php echo esc_html( $s['position'] ); ?></span>
				<p class="tevo-team-member__bio"><?php echo esc_html( $s['bio'] ); ?></p>
			</div>
		</div>
		<?php
	}
}
