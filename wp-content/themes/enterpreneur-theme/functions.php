<?php
	
	DEFINE( 'ENTERPRENEUR_URI', get_stylesheet_directory_uri() );
	DEFINE( 'ENTERPRENEUR_PATH', get_stylesheet_directory() );

	add_action ('wp_enqueue_scripts', 'add_theme_style');

	

	function add_theme_style()
	{

		wp_enqueue_style( 'bootstrap.min', get_template_directory_uri(). '/assets/vendor/bootstrap/css/bootstrap.min.css', array(), '4.1.3' );
		wp_enqueue_style( 'all.min', get_template_directory_uri(). '/assets/vendor/fontawesome-free/css/all.min.css', array(), '5.3.1' );
		wp_enqueue_style( 'font', 'https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800');
		wp_enqueue_style ( 'magnific-popup', get_template_directory_uri(). 'assets/vendor/magnific-popup/magnific-popup.css');
		wp_enqueue_style ( 'creative.min' , get_template_directory_uri(). '/assets/css/creative.min.css' );
		wp_enqueue_style( 'style', get_stylesheet_uri() ); 
		wp_enqueue_script( 'jquery', get_template_directory_uri() . '/assets/js/jquery-3.1.1.min.js', array(), '3.1.1 ' );

	}


	// TIGA ROUTER

	require (ENTERPRENEUR_PATH.'/inc/flash-message.php');
	
	require (ENTERPRENEUR_PATH.'/inc/extras.php'); 
	


class Demo_Routes {

		private $session;
		private $flash;
		private $drive;

		public function __construct() {
			add_action( 'tiga_route', array( $this, 'register_routes' ) );
			$this->session = new \Tiga\Session();
			$this->flash = new Demo_Flash();
		}

		public function register_routes() {
			TigaRoute::get( '/join', array( $this, 'index' ) );
			TigaRoute::post( '/join', array( $this, 'join_enterpreneur' ) );
		}

		public function index() {

			$data = array(
				'repopulate' => $this->session->pull( 'input','select','option','textarea' ),
				'flash' => $this->flash,
			);
			set_tiga_template( 'page_form.php', $data );
		}

		public function join_enterpreneur( $request ) {
			
			if ( $request->has( 'full_name|email|phone|industries|contract_type|why_apply' ) && $request->hasFile('file_cv') ) {
				$data = $request->all();
				
				if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}

				$uploadedfile = $_FILES['file_cv'];

				$file = $this->drive->insertFile( $uploadedfile['name'], $uploadedfile['type'], $uploadedfile['tmp_name'], date("Y/m/d").' | '.$data['full_name'], get_field('google_drive_upload_folder', 'option') );

				$upload_overrides = array( 'test_form' => false );
				// var_dump($uploadedfile);
				// var_dump($upload_overrides); die();

				// $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
				// var_dump($movefile);

				$post_id = wp_insert_post( array(
					'post_title'	=> $data['full_name'],
					'post_type'		=> 'participant',
					'post_status'	=> 'publish'
				) );

				update_post_meta( $post_id, 'specific_job', 0 );
				update_post_meta( $post_id, 'full_name', sanitize_text_field( $data['full_name'] ) );
				update_post_meta( $post_id, 'email', sanitize_text_field( $data['email'] ) );
				update_post_meta( $post_id, 'phone', sanitize_text_field( $data['phone'] ) );
				update_post_meta( $post_id, 'contract_type', sanitize_text_field( $data['contract_type'] ) );
				update_post_meta( $post_id, 'industries', sanitize_text_field( $data['industries'] ) );
				update_post_meta( $post_id, 'why_apply', sanitize_text_field( $data['why_apply'] ) );
				update_post_meta( $post_id, 'cv_file_id', $file->getId() );
				update_post_meta( $post_id, 'cv_file_url', $file->getWebViewLink() );
				
				// $attachments = array(WP_CONTENT_DIR . '/uploads/'.$data['file_cv']);
				// $attachments  = array(ABSPATH . 'uploads/2018/10/author.jpg' );				
				
$message = 'Full name : ' .$data['full_name'].'
'.'Email : ' .$data['email'].'
'.'Phone : ' .$data['phone'].'
'.'Contract Type : ' .$data['contract_type'].'
'.'Industries : ' .$data['industries'].'
'.'Why Apply : ' .$data['why_apply'];

				$email = get_field('cf_email','option');
				$subject = date("Y/m/d").' | '.$data['full_name'];

				// send mail
				wp_mail( $email, $subject, $message, 'this is header' );

				// if ( $movefile && ! isset( $movefile['error'] ) ) {
					// echo "File is valid, and was successfully uploaded.\n";					
					// unlink( $movefile['file'] );
					// var_dump( $movefile ); die();
				// } else {
				    /**
				     * Error generated by _wp_handle_upload()
				     * @see _wp_handle_upload() in wp-admin/includes/file.php
				     */
				    // echo $movefile['error'];
				// }

				$this->flash->success( 'Thank you for your message. It has been sent.' );
				
				// insert to table
				$insertId = WP_PX::table('upload_cv')->insert($data);
				
				
				wp_safe_redirect( site_url() . '/upload-cv' );
			} else {
				// error flash message with return data
				$this->flash->error( 'Semua field harus diisi' );
				$this->session->set( 'input', $request->all() );
				wp_safe_redirect( site_url() . '/upload-cv' );
			}
		}

	

	public function send_specific_job( $request ) {


			
			if ( $request->has( 'full_name|email|phone|company|location|job_title' ) && $request->hasFile('file_cv') ) {
				$data = $request->all();
				// echo 'data';
				// var_dump($data);
				
				if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}

				$uploadedfile = $_FILES['file_cv'];

				$upload_overrides = array( 'test_form' => false );
				// var_dump($uploadedfile);
				// var_dump($upload_overrides); die();

				// $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
				// var_dump($movefile);

				$file = $this->drive->insertFile( $uploadedfile['name'], $uploadedfile['type'], $uploadedfile['tmp_name'], date("Y/m/d").' | '.$data['full_name'], get_field('google_drive_upload_folder', 'option') );
				
				// $attachments = array(WP_CONTENT_DIR . '/uploads/'.$data['file_cv']);
				// $attachments  = array(ABSPATH . 'uploads/2018/10/author.jpg' );				

				$post_id = wp_insert_post( array(
					'post_title'	=> $data['full_name'],
					'post_type'		=> 'participant',
					'post_status'	=> 'publish'
				) );

				update_post_meta( $post_id, 'specific_job', 1 );
				update_post_meta( $post_id, 'full_name', sanitize_text_field( $data['full_name'] ) );
				update_post_meta( $post_id, 'email', sanitize_text_field( $data['email'] ) );
				update_post_meta( $post_id, 'phone', sanitize_text_field( $data['phone'] ) );
				update_post_meta( $post_id, 'company', sanitize_text_field( $data['company'] ) );
				update_post_meta( $post_id, 'job_title', sanitize_text_field( $data['job_title'] ) );
				update_post_meta( $post_id, 'contract_type', sanitize_text_field( $data['contract_type'] ) );
				update_post_meta( $post_id, 'location', sanitize_text_field( $data['location'] ) );
				update_post_meta( $post_id, 'why_apply', sanitize_text_field( $data['why_apply'] ) );
				update_post_meta( $post_id, 'cv_file_id', $file->getId() );
				update_post_meta( $post_id, 'cv_file_url', $file->getWebViewLink() );
				
$message = 'Full name : ' .$data['full_name'].'
'.'Email : ' .$data['email'].'
'.'Phone : ' .$data['phone'].'
'.'Company : ' .$data['company'].'
'.'Job Title : ' .$data['job_title'].'
'.'Contract Type : ' .$data['contract_type'].'
'.'Location : ' .$data['location'].'
'.'Why Apply : ' .$data['why_apply'];

				$email = get_field('cf_email','option');
				$subject = date("Y/m/d").' | '.$data['full_name'];

				// send mail
				wp_mail($email,$subject,$message,'this is header');

				// if ( $movefile && ! isset( $movefile['error'] ) ) {
					// echo "File is valid, and was successfully uploaded.\n";					
					// unlink( $movefile['file'] );
					// var_dump( $movefile ); die();
				// } else {
				    /**
				     * Error generated by _wp_handle_upload()
				     * @see _wp_handle_upload() in wp-admin/includes/file.php
				     */
				    // echo $movefile['error'];
				// }
				// success flash message
				

				$this->flash->success( 'Thank you for your message. It has been sent.' );
				$job_title    = isset( $_GET['job_title'] ) ? sanitize_text_field( wp_unslash( $_GET['job_title'] ) ) : '';
				$keyword    = isset( $_GET['contract_type'] ) ? sanitize_text_field( wp_unslash( $_GET['contract_type'] ) ) : '';
				$location    = isset( $_GET['location'] ) ? sanitize_text_field( wp_unslash( $_GET['location'] ) ) : '';
				$company    = isset( $_GET['company'] ) ? sanitize_text_field( wp_unslash( $_GET['company'] ) ) : '';

				$data = array(
					'job_title' => $job_title,
					'company' => $company,
					'location' => $location,
					'repopulate' => $this->session->pull( 'input','select','option','textarea' ),
					'flash' => $this->flash,
				);
				set_tiga_template( 'page-apply.php', $data );
				// wp_safe_redirect( site_url() . '/apply-now' );
			} else {
				// var_dump($request);
				// error flash message with return data
				$this->flash->error( 'Semua field harus diisi');

				$this->session->set( 'input', $request->all() );
				wp_safe_redirect( site_url() . '/apply-now' );
			}
		}

	}


	new Demo_Routes();

/**
 * Flash Class
 */
class Demo_Flash {

	/**
	 * Session
	 *
	 * @var object Session Object
	 */
	private $session;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->session = new \Tiga\Session();
	}

	/**
	 * Add error notice
	 *
	 * @param string $message Message text.
	 */
	public function error( $message ) {
		$this->add( 'danger', $message );
	}

	/**
	 * Add success notice
	 *
	 * @param string $message Message text.
	 */
	public function success( $message ) {
		$this->add( 'success', $message );
	}

	/**
	 * Add info notice
	 *
	 * @param string $message Message text.
	 */
	public function info( $message ) {
		$this->add( 'info', $message );
	}

	/**
	 * Add warning notice
	 *
	 * @param string $message Message text.
	 */
	public function warning( $message ) {
		$this->add( 'warning', $message );
	}

	/**
	 * Add message to session
	 *
	 * @param string $type Notice type.
	 * @param string $message Message text.
	 */
	public function add( $type, $message ) {
		$old = $this->session->get( 'demo_flash_' . $type );
		$this->session->set( 'demo_flash_' . $type, $old . '<div>' . $message . '</div>' );
	}

	/**
	 * Displaying notice
	 */
	public function display() {
		if ( $this->session->has( 'demo_flash_danger' ) ) {
			echo '<div class="wpcf7-response-output wpcf7-display-none wpcf7-mail-sent-ng" style="display: block;" role="alert">' . $this->session->pull( 'demo_flash_danger' ) . '</div>';
			// echo '<div class="alert dismissable alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' . $this->session->pull( 'demo_flash_danger' ) . '</div>';
		}
		if ( $this->session->has( 'demo_flash_info' ) ) {
			// echo '<div class="alert dismissable alert-info"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' . $this->session->pull( 'demo_flash_info' ) . '</div>';
			echo '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;" role="alert">' . $this->session->pull( 'demo_flash_info' ) . '</div>';
		}
		if ( $this->session->has( 'demo_flash_success' ) ) {
			// echo '<div class="alert dismissable alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' . $this->session->pull( 'demo_flash_success' ) . '</div>';
			echo '<div class="wpcf7-response-output wpcf7-display-none wpcf7-mail-sent-ok" style="display: block;" role="alert">' . $this->session->pull( 'demo_flash_success' ) . '</div>';
		}
		if ( $this->session->has( 'demo_flash_warning' ) ) {
			// echo '<div class="alert dismissable alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' . $this->session->pull( 'demo_flash_warning' ) . '</div>';

			echo '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;" role="alert">' . $this->session->pull( 'demo_flash_warning' ) . '</div>';

		}
	}

}

	/**
	 * Create DB table for demo purpose
	 */
	function create_demo_db_table() {
		global $wpdb;
		$db_name = 'upload_cv';
		$charset_collate = $wpdb->get_charset_collate();

		if ( $wpdb->get_var( "SHOW TABLES LIKE '$db_name'") !== $db_name ) {
			$sql = 'CREATE TABLE ' . $db_name . " 
					( `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, 
					  `full_name` varchar(255) NOT NULL, 
					  `phone` varchar(20) NOT NULL, 
					  `email` varchar(255) NOT NULL,
					  `industries` varchar(255) NOT NULL,
					  `contract_type` varchar(255) NOT NULL,
					  `file_cv` varchar(255) NOT NULL,
					  `why_apply` varchar(255) NOT NULL, 
					  PRIMARY KEY  (`id`) 
				  ) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		TigaPixie::get('WP_PX');
	}
	add_action( 'init', 'create_demo_db_table', 1);



