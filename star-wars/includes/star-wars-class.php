<?php

/**
 * Adds Star War widget.
 */


class Star_Wars_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'starwars_widget', // Base ID
			esc_html__( 'Star Wars', 'stw_domain' ), // Name
			array( 'description' => esc_html__( 'Widget to display Star Wars Ships', 'stw_domain' ), ) // Args
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
		
		echo $args['before_widget']; //Display before the widget (<div>)


		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		if ( ! empty( $instance['starships'] ) ) {
			echo $args['starship-name'] . apply_filters( 'starship-name', $instance['starships'] ) . $args['starship-name'];
		}


        //widget content output

		
		echo $args['after_widget']; //Display after the widget (</div>)
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	
	public function form( $instance ) {

		$url = 'https://swapi.dev/api/starships/1/';
		$arguments = array (
			'method' => 'GET'
		);
		$response = wp_remote_get($url, $arguments);

		if(is_wp_error($response)){
		$error_message = $response->get_error_message();
		echo "Something went wrong: $error_message";
		}
		$body = wp_remote_retrieve_body( $response );

		$data = json_decode( $body );
		
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Star Wars', 'stw_domain' );

		$ships = ! empty( $instance['ships'] ) ? $instance['ships'] : esc_html__( 'Star Wars', 'stw_domain' );
		?>
	   
            <!-- Title-->
		<p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php esc_attr_e( 'Title:', 'stw_domain' ); ?>
            </label> 

		    <input
             class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
             name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
             type="text" 
             value="<?php echo esc_attr( $title ); ?>">
		</p>
		
          <!-- DROPDOWN MENU-->
		  <p>
		    <label for="<?php echo esc_attr( $this->get_field_id( 'ships' ) ); ?>">
                <?php esc_attr_e( 'Ships:', 'stw_domain' ); ?>
            </label> 

		<select name="ship_select">
  			<option value="">Please choose a Starship</option>
			 <?php
			 foreach( $data->results as $product ){
				 esc_url( $product->name);
			}?>

		</select>
		</p>
		<?php 
	
	}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}

} // class Foo_Widget



