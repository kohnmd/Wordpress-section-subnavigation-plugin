<?php

add_action( 'widgets_init', create_function( "", 'register_widget( "ss_widget" );' ) );

/**
 * Creates SS_Widget widget so the SS can easily be added to any sidebar.
 */
class SS_Widget extends WP_Widget {
	
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'foo_widget', // Base ID
			'Section Subnavigation', // Name
			array(
				'description' => 'A subnav for each section.'
			) // Args
		);
	}
	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$section_subnavigation = get_section_subnavigation();
		if($section_subnavigation->_menu != false ) {
			extract( $args );
			$title = apply_filters( 'widget_title', $section_subnavigation->_sectionTitle );
			echo $before_widget;
			if ( ! empty( $title ) ) {
				echo $before_title . $title . $after_title;
			}
			// actual widget output goes here
			echo $section_subnavigation->_menu;
			echo $after_widget;
		}
	}
	
 	/**
	 * Back-end widget form.
	 * Simply alerts the user that the widget doesn't require any input.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		?>
		<p>This widget doesn't require any additional configuration beyond the <a href="options-general.php?page=section-subnav">settings menu</a>.</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 * Since no inputs are necessary, this is just a placeholder for any
	 * possible future functionality.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
	}

}