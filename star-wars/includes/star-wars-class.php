<?


class Star_Wars_Widget extends WP_Widget {



	function __construct() {
		parent::__construct(
			'starwars_widget', 
			esc_html__( 'Star Wars', 'stw_domain' ), 
			array( 'description' => esc_html__( 'Widget to display Star Wars Ships', 'stw_domain' ), ) 
		);
	}
	
	

	public function widget( $args, $instance ) {
		
		echo $args['before_widget']; 

	

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		

		$url = 'https://swapi.dev/api/starships/';
		$arguments = array (
			'method' => 'GET'
		);
		$response = wp_remote_get($url, $arguments);

		if(is_wp_error($response)){
		$error_message = $response->get_error_message();
		echo "Something went wrong: $error_message";
		}

		 
		$data = json_decode(wp_remote_retrieve_body( $response));

		$shipName = $data->results[0]->name;

		echo '<select name="ship_select">';
		echo '<option value="">Please choose a Starship</option>';
		foreach($data->results[0] as $product): ?>
		<option value="<?php echo $product->name; ?>"></option>
		<?php endforeach; ?>
		</select>
<?
		$shipModel = $data->results[0]->manufacturer;

		
		echo "The Star War Ship Model is $shipModel";

	
		
		echo $args['after_widget']; 
	}
	
	public function form( $instance ) {

		
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
			 foreach( $this->data->results as $product ){
				 esc_url( $product->name);
			}?>
		
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}

} 


