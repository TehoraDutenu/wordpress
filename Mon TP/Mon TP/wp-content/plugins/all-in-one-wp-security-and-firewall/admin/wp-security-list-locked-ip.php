<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class AIOWPSecurity_List_Locked_IP extends AIOWPSecurity_List_Table {
	
	function __construct(){
		global $status, $page;
		
		// Set parent defaults
		parent::__construct( array(
			'singular'  => 'item',     //singular name of the listed records
			'plural'    => 'items',    //plural name of the listed records
			'ajax'      => false        //does this table support ajax?
		) );
		
	}

	function column_default($item, $column_name){
		return $item[$column_name];
	}
	
	/**
	 * Function to populate the locked ip actions column in the table
	 *
	 * @param array $item - Contains the current item data 
	 *
	 * @return string
	 */
	public function column_failed_login_ip($item){
		$actions = array(
			'unlock' => '<a href="" data-id="'.esc_attr($item['id']).'" data-message="'.esc_js(__('Are you sure you want to unlock this address range?', 'all-in-one-wp-security-and-firewall')).'" class="aios-unlock-ip-button">'.__('Unlock', 'all-in-one-wp-security-and-firewall').'</a>',
			'delete' => '<a href="" data-id="'.esc_attr($item['id']).'" data-message="'.esc_js(__('Are you sure you want to delete this item?', 'all-in-one-wp-security-and-firewall')).'"  class="aios-delete-ip-button">'.__('Delete', 'all-in-one-wp-security-and-firewall').'</a>',
		);
		
		//Return the user_login contents
		return sprintf('%1$s <span style="color:silver"></span>%2$s',
			/*$1%s*/ $item['failed_login_ip'],
			/*$2%s*/ $this->row_actions($actions)
		);
	}

	
	function column_cb($item){
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			/*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label
			/*$2%s*/ $item['id']                //The value of the checkbox should be the record's id
	   );
	}

	/**
	 * Returns ip_lookup_result column html to be rendered.
	 *
	 * @param array - data for the columns on the current row
	 *
	 * @return string - the html to be rendered
	 */
	public function column_ip_lookup_result($item) {
		if (empty($item['ip_lookup_result'])) return __('There is no IP lookup result available.', 'all-in-one-wp-security-and-firewall');
		
		$ip_lookup_result = json_decode($item['ip_lookup_result']);
		$ip_lookup_result = print_r($ip_lookup_result, true);

		$output = sprintf('<a href="#TB_inline?&inlineId=trace-%s" title="%s" class="thickbox">%s</a>', esc_attr($item['id']), esc_html__('IP lookup result', 'all-in-one-wp-security-and-firewall'), esc_html__('Show result', 'all-in-one-wp-security-and-firewall'));
		$output .= sprintf('<div id="trace-%s" style="display: none"><pre>%s</pre></div>', esc_attr($item['id']), esc_html($ip_lookup_result));

		return $output;
	}

	/**
	 * Sets the columns for the table
	 *
	 * @return array
	 */
	public function get_columns(){
		$columns = array(
			'cb' => '<input type="checkbox" />', //Render a checkbox
			'failed_login_ip' => __('Locked IP/range', 'all-in-one-wp-security-and-firewall'),
			'user_id' => __('User ID', 'all-in-one-wp-security-and-firewall'),
			'user_login' => __('Username', 'all-in-one-wp-security-and-firewall'),
			'lock_reason' => __('Reason', 'all-in-one-wp-security-and-firewall'),
			'lockdown_date' => __('Date locked', 'all-in-one-wp-security-and-firewall'),
			'release_date' => __('Release date', 'all-in-one-wp-security-and-firewall'),
			'ip_lookup_result' => __('IP lookup result', 'all-in-one-wp-security-and-firewall')
		);
		return $columns;
	}
	
	function get_sortable_columns() {
		$sortable_columns = array(
			'failed_login_ip' => array('failed_login_ip',false),
			'user_id' => array('user_id',false),
			'user_login' => array('user_login',false),
			'lock_reason' => array('lock_reason',false),
			'lockdown_date' => array('lockdown_date',false),
			'release_date' => array('release_date',false)
		);
		return $sortable_columns;
	}
	
	function get_bulk_actions() {
		$actions = array(
			'unlock' => __('Unlock', 'all-in-one-wp-security-and-firewall'),
			'delete' => __('Delete', 'all-in-one-wp-security-and-firewall'),
		);
		return $actions;
	}

	/**
	 * Process bulk actions.
	 *
	 * @return void
	 */
	private function process_bulk_action() {
		if (empty($_REQUEST['_wpnonce']) || !isset($_REQUEST['_wp_http_referer'])) return;
		$result = AIOWPSecurity_Utility_Permissions::check_nonce_and_user_cap($_REQUEST['_wpnonce'], 'bulk-items');
		if (is_wp_error($result)) return;

		if ('delete' == $this->current_action()) { // Process delete bulk actions
			if (!isset($_REQUEST['item'])) {
				AIOWPSecurity_Admin_Menu::show_msg_error_st(__('Please select some records using the checkboxes', 'all-in-one-wp-security-and-firewall'));
			} else {
				$this->delete_lockout_records($_REQUEST['item']);
			}
		}

		if ('unlock' == $this->current_action()) { //Process unlock bulk actions
			if (!isset($_REQUEST['item'])) {
				AIOWPSecurity_Admin_Menu::show_msg_error_st(__('Please select some records using the checkboxes', 'all-in-one-wp-security-and-firewall'));
			} else {
				$this->unlock_ip_range(($_REQUEST['item']));
			}
		}
	}

	/**
	 * Unlocks an IP range by modifying the release_date column of a record in the AIOWPSEC_TBL_LOGIN_LOCKOUT table.
	 *
	 * @param Array|Integer - ids or a single id
	 *
	 * @return Void
	 */
	public function unlock_ip_range($entries) {
		global $wpdb, $aio_wp_security;

		$lockout_table = AIOWPSEC_TBL_LOGIN_LOCKOUT;

		$now = current_time('mysql', true);

		if (is_array($entries)) {
			// Unlock multiple records
			$entries = array_filter($entries, 'is_numeric');  // Discard non-numeric ID values
			$id_list = '(' .implode(',', $entries) .')';  // Create comma separate list for DB operation
			$result = $wpdb->query($wpdb->prepare("UPDATE $lockout_table SET `release_date` = %s WHERE `id` IN $id_list", $now));

			if (NULL != $result) {
				AIOWPSecurity_Admin_Menu::show_msg_updated_st(__('The selected IP entries were unlocked successfully.', 'all-in-one-wp-security-and-firewall'));
			}
		} elseif (NULL != $entries) {
			// Unlock single record
			$result = $wpdb->query($wpdb->prepare("UPDATE $lockout_table SET `release_date` = %s WHERE `id` = %d", $now, absint($entries)));

			if (NULL != $result) {
				return AIOWPSecurity_Admin_Menu::show_msg_updated_st(__('The selected IP entry was unlocked successfully.', 'all-in-one-wp-security-and-firewall'), true);
			}
		}
	}

	/**
	 * Deletes one or more records from the AIOWPSEC_TBL_LOGIN_LOCKOUT table.
	 *
	 * @param Array|String|Integer - ids or a single id
	 *
	 * @return Void
	 */
	public function delete_lockout_records($entries) {
		global $wpdb, $aio_wp_security;
		$lockout_table = AIOWPSEC_TBL_LOGIN_LOCKOUT;
		if (is_array($entries)) {
			// Delete multiple records
			$entries = array_filter($entries, 'is_numeric'); //discard non-numeric ID values
			$id_list = "(" .implode(",", $entries) .")"; //Create comma separate list for DB operation
			$delete_command = "DELETE FROM ".$lockout_table." WHERE id IN ".$id_list;
			$result = $wpdb->query($delete_command);
			if ($result) {
				AIOWPSecurity_Admin_Menu::show_msg_record_deleted_st();
			} else {
				// Error on bulk delete
				$aio_wp_security->debug_logger->log_debug('Database error occurred when deleting rows from login lockout table. Database error: '.$wpdb->last_error, 4);
				AIOWPSecurity_Admin_Menu::show_msg_record_not_deleted_st();
			}
		} elseif (NULL != $entries) {
			// Delete single record
			$delete_command = "DELETE FROM ".$lockout_table." WHERE id = '".absint($entries)."'";
			$result = $wpdb->query($delete_command);
			if ($result) {
				return AIOWPSecurity_Admin_Menu::show_msg_record_deleted_st(true);
			} elseif (false === $result) {
				// Error on single delete
				$aio_wp_security->debug_logger->log_debug('Database error occurred when deleting rows from login lockout table. Database error: '.$wpdb->last_error, 4);
				return AIOWPSecurity_Admin_Menu::show_msg_record_not_deleted_st(true);
			}
		}
	}

	/**
	 * Retrieves all items from AIOWPSEC_TBL_LOGIN_LOCKOUT. It may paginate and then assigns to $this->items.
	 *
	 * @param Boolean $ignore_pagination - whether to not paginate
	 *
	 * @return Void
	 */
	public function prepare_items($ignore_pagination = false) {
		global $wpdb;

		$lockout_table = AIOWPSEC_TBL_LOGIN_LOCKOUT;

		$this->process_bulk_action();

		// How many records per page to show
		$per_page = 100;
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array($columns, $hidden, $sortable);

		// Parameters that are going to be used to order the result
		$orderby = isset($_GET['orderby']) ? sanitize_text_field(wp_unslash($_GET['orderby'])) : '';
		$order = isset($_GET['order']) ? sanitize_text_field(wp_unslash($_GET['order'])) : '';

		$orderby = !empty($orderby) ? esc_sql($orderby) : 'lockdown_date';
		$order = !empty($order) ? esc_sql($order) : 'DESC';

		$orderby = AIOWPSecurity_Utility::sanitize_value_by_array($orderby, $sortable);
		$order = AIOWPSecurity_Utility::sanitize_value_by_array($order, array('DESC' => '1', 'ASC' => '1'));

		$now = current_time('mysql', true);

		$current_page = $this->get_pagenum();
		$offset = ($current_page - 1) * $per_page;

		$total_items = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$lockout_table} WHERE `release_date` > %s",
				$now
			)
		);

		if ($ignore_pagination) {
			$data = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$lockout_table} WHERE `release_date` > %s ORDER BY {$orderby} {$order}",
					$now
				),
				'ARRAY_A'
			);
		} else {
			$data = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$lockout_table} WHERE `release_date` > %s ORDER BY {$orderby} {$order} LIMIT {$per_page} OFFSET {$offset}",
					$now
				),
				'ARRAY_A'
			);
		}

		foreach ($data as $index => $row) {
			$data[$index]['lockdown_date'] = get_date_from_gmt(mysql2date('Y-m-d H:i:s', $row['lockdown_date']), $this->get_wp_date_time_format());
			$data[$index]['release_date'] = get_date_from_gmt(mysql2date('Y-m-d H:i:s', $row['release_date']), $this->get_wp_date_time_format());
		}

		$this->items = $data;

		if ($ignore_pagination) return;

		$this->set_pagination_args(array(
				'total_items' => $total_items,  // WE have to calculate the total number of items
				'per_page'    => $per_page,  // WE have to determine how many items to show on a page
				'total_pages' => ceil($total_items / $per_page)  // WE have to calculate the total number of pages
		));
	}

}
