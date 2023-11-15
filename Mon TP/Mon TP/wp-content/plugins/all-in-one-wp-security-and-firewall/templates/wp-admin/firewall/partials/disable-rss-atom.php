<?php if (!defined('ABSPATH')) die('Access denied.'); ?>
<div class="postbox">
			<h3 class="hndle"><?php _e('Disable WordPress RSS and ATOM feeds', 'all-in-one-wp-security-and-firewall'); ?></h3>
			<div class="inside">
				<?php
				//Display security info badge
				$aiowps_feature_mgr->output_feature_details_badge("firewall-disable-rss-and-atom");
				?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php _e('Disable RSS and ATOM feeds:', 'all-in-one-wp-security-and-firewall'); ?></th>
						<td>
							<div class="aiowps_switch_container">
								<?php AIOWPSecurity_Utility_UI::setting_checkbox(__('Check this if you do not want users using feeds.', 'all-in-one-wp-security-and-firewall') . ' ' .  __(' RSS and ATOM feeds are used to read content from your site.', 'all-in-one-wp-security-and-firewall'), 'aiowps_disable_rss_and_atom_feeds', '1' == $aio_wp_security->configs->get_value('aiowps_disable_rss_and_atom_feeds')); ?>`
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
