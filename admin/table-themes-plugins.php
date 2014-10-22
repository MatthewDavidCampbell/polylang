<?php

if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ); // since WP 3.1
}

/*
 * a class to create the strings translations table
 * Thanks to Matt Van Andel (http://www.mattvanandel.com) for its plugin "Custom List Table Example" !
 *
 * @since 1.6 - forked
 */
class PLL_Table_Themes_Plugins extends WP_List_Table {

	/*
	 * constructor
	 *
	 * @since 1.6 - forked
	 *
	 * @param array $groups
	 * @param string $group_selected
	 */
	function __construct($type) {
		parent::__construct(array(
			'plural'   => $type, // do not translate (used for css class)
			'ajax'	 => false
		));
	}

	/*
	 * displays the item information in a column (default case)
	 *
	 * @since 1.6 - forked
	 *
	 * @param array $item
	 * @param string $column_name
	 * @return string
	 */
	function column_default($item, $column_name) {
		return $item[$column_name];
	}

	/*
	 * displays the checkbox in first column
	 *
	 * @since 1.6 - forked
	 *
	 * @param array $item
	 * @return string
	 */
	function column_cb($item){
		// print_r($item);
		return sprintf(
			'<input type="checkbox" name="file[]" value="%s" />',
			esc_attr($item['row'])
		);
	}

	/*
	 * displays the string to translate
	 *
	 * @since 1.6 - forked
	 *
	 * @param array $item
	 * @return string
	 */
	function column_file($item) {
		return $item['file']; 
	}

	/*
	 * gets the list of columns
	 *
	 * @since 1.6 - forked
	 *
	 * @return array the list of column titles
	 */
	function get_columns() {
		return array(
			'cb'           => '<input type="checkbox" />', //checkbox
			'file'      => __('Name', 'polylang'),
		);
	}

	/*
	 * gets the list of sortable columns
	 *
	 * @since 1.6 - forked
	 *
	 * @return array
	 */
	function get_sortable_columns() {
		return array(
			'file' => array('file', false),
		);
	}

	/*
	 * prepares the list of items ofr displaying
	 *
	 * @since 1.6 - forked
	 *
	 * @param array $data
	 */
	function prepare_items($data = array()) {
		$per_page = $this->get_items_per_page('pll_strings_per_page');
		$this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());

		if (!function_exists('usort_reorder')) {
			function usort_reorder($a, $b){
				$result = strcmp($a[$_REQUEST['orderby']], $b[$_REQUEST['orderby']]); // determine sort order
				return (empty($_REQUEST['order']) || $_REQUEST['order'] == 'asc') ? $result : -$result; // send final sort direction to usort
			};
		}

		if (!empty($_REQUEST['orderby'])) // no sort by default
			usort($data, 'usort_reorder');

		$total_items = count($data);
		$this->items = array_slice($data, ($this->get_pagenum() - 1) * $per_page, $per_page);

		$this->set_pagination_args(array(
			'total_items' => $total_items,
			'per_page'	=> $per_page,
			'total_pages' => ceil($total_items/$per_page)
		));
	}

	/*
	 * get the list of possible bulk actions
	 *
	 * @since 1.6 - forked
	 *
	 * @return array
	 */
	function get_bulk_actions() {
		return array('load_strings' => __('Load Strings','polylang'));
	}

}
