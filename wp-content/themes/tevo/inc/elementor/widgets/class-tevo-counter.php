<?php
/**
 * Tevo Counter Widget.
 * @package Tevo
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Tevo_Counter_Widget extends \Elementor\Widget_Base {
	public function get_name() { return 'tevo-counter'; }
	public function get_title() { return esc_html__( 'Counter', 'tevo' ); }
	public function get_icon() { return 'eicon-counter'; }
	public function get_categories() { return array( 'tevo-insurance' ); }

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => esc_html__( 'Content', 'tevo' ) ) );
		$this->add_control( 'icon', array( 'label' => esc_html__( 'Icon', 'tevo' ), 'type' => \Elementor\Controls_Manager::ICONS, 'default' => array( 'value' => 'fas fa-users', 'library' => 'fa-solid' ) ) );
		$this->add_control( 'number', array( 'label' => esc_html__( 'Number', 'tevo' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 5000 ) );
		$this->add_control( 'suffix', array( 'label' => esc_html__( 'Suffix', 'tevo' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '+' ) );
		$this->add_control( 'title', array( 'label' => esc_html__( 'Title', 'tevo' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Happy Clients' ) );
		$this->add_control( 'duration', array( 'label' => esc_html__( 'Animation Duration (ms)', 'tevo' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 2000 ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<div class="tevo-counter" data-target="<?php echo esc_attr( $s['number'] ); ?>" data-duration="<?php echo esc_attr( $s['duration'] ); ?>">
			<div class="tevo-counter__icon">
				<?php \Elementor\Icons_Manager::render_icon( $s['icon'], array( 'aria-hidden' => 'true' ) ); ?>
			</div>
			<div class="tevo-counter__number">
				<span class="tevo-counter__value">0</span><span class="tevo-counter__suffix"><?php echo esc_html( $s['suffix'] ); ?></span>
			</div>
			<h4 class="tevo-counter__title"><?php echo esc_html( $s['title'] ); ?></h4>
		</div>
		<?php
	}
}
