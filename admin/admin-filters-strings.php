<?php
/*
 * manages filters and actions related to strings on admin side
 *
 * @since 1.6 - forked
 */
class PLL_Admin_Filters_Strings {
	public $links, $model, $curlang, $current_strings, $new_strings, $settings;

	/*
	 * constructor: setups filters and actions
	 *
	 * @since 1.6 - forked
	 *
	 * @param object $polylang
	 */
	public function __construct(&$polylang) {
		if (!post_type_exists('polylang_strings'))
 			register_post_type('polylang_strings', array('rewrite' => false, 'query_var' => false, '_pll' => true));

		$this->links = &$polylang->links;
		$this->model = &$polylang->model;
		$this->curlang = &$polylang->curlang;

		// ajax response for edit term form
		add_action('wp_ajax_get-strings', array(&$this,'load_strings'));

		// add filter to get all the strings
		add_filter('pll_get_strings', array(&$this,'add_strings_in_admin_panel'));
	}


	/*
	 * ajax response for edit term form
	 *
	 * @since 1.6 - forked
	 */
	public function load_strings($path) {

		require_once('get_strings.php');

		// check activated theme
		$string_manager = new PLL_Get_Strings( $path );

		foreach ($string_manager->files as $file) {
			$string_manager->analyze_files($file);
		}

		$this->save_strings($string_manager->strings);

		// show ajax response after processing if doing ajax (not useful for the moment)
		if ( defined('DOING_AJAX') && DOING_AJAX )
			$this->show_ajax_response();
	}


	/*
	 * save in custom post type
	 *
	 * @since 1.6 - forked
	 */
	private function save_strings($strings) {
		$has_been_added = false;

		foreach ($strings as $string) {
			$post_string = array(
		        'post_title' => sanitize_title( $string['string'] ),
		        'post_content' => $string['domain'],
		        'post_excerpt' => $string['string'],
		        'post_status' => 'publish',
		        'post_type' => 'polylang_strings'
		    );

		    $string_exists = get_page_by_title( sanitize_title( $string['string'] ), 'OBJECT', 'polylang_strings' );

		    if ( $string_exists == null ) {
		        $insert = wp_insert_post( $post_string );

		        if ( $insert ) {		        
			        // add string in new strings
			        $this->new_strings[] = $string;
		        }
		    }				
		}
	}


	/*
	 * display ajax response
	 *
	 * @since 1.6 - forked
	 */
	public function show_ajax_response() {
		echo json_encode($this->new_strings);
	}


	/*
	 * get all strings from custom post type
	 *
	 * @since 1.6 - forked
	 */
	private function get_strings() {
		$args = array(
			'post_type' => 'polylang_strings',
			'post_status' => 'publish',
			'posts_per_page' => -1
		);

		$strings = get_posts($args);

		foreach ($strings as $string) {
			$this->current_strings[] = array(
				'name' => $string->post_title,
				'string' => $string->post_excerpt,
				'domain' => $string->post_content
			);
		}
	}


	/*
	 * register all strings to display in admin panel
	 *
	 * @since 1.6 - forked
	 */
	public function add_strings_in_admin_panel($strings) {
		$this->get_strings();

		if ( !empty( $this->current_strings ) ) {
			foreach ($this->current_strings as $string) {
				if (strlen($string['string']) > 60)
					$strings[] = array( 
						'name' => $string['domain'] . chr(4) . $string['name'], 
						'string' => $string['string'], 
						'context' => $string['domain'], 
						'can_be_removed' => true,
						'multiline' => true
					);
				else
					$strings[] = array( 
						'name' => $string['domain'] . chr(4) . $string['name'], 
						'string' => $string['string'], 
						'context' => $string['domain'], 
						'can_be_removed' => true,
						'multiline' => false
					);
			}
		}
		
		return $strings;
	}

}