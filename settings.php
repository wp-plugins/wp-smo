<div class="wrap">
	
	<div id="icon-options-general" class="icon32"></div>
	<h2><?php _e('WP SMO', 'wp_smo'); ?></h2>
	
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h3><span><?php _e('Settings', 'wp_smo'); ?></span></h3>
						<div class="inside">

	
	<form method="post" action="options.php">
		<?php settings_fields('wp-smo');?>
		<table class="form-table">
			<tbody>
			<tr valign="top">
				<th scope="row">
					<label for="wsp_posts_by_category">
					<?php _e('Twitter site', 'wp_smo');?>
					</label>
				</th>
				<td>
					<?php
					// get the value of the twitter site
					$wpsmo_twitter_site = get_option('wpsmo_twitter_site');
					?>
					<input type="text" name="wpsmo_twitter_site" id="wpsmo_twitter_site" class="large-text code" 
						value="<?php echo $wpsmo_twitter_site; ?>" placeholder="<?php _e('example: @twitter', 'wp_smo'); ?>" />
				</td>
			</tr>
			</tbody>
		</table>
		<?php
		// @TODO idea to evolve : Define the post type to enable the SMO plugin (by default at least page and post, but let the choice for the author types). Use: get_post_types()
		
		// @TODO idea to evolve : button to restaure initial code
		?>
		<?php submit_button();?>
	</form>

						</div><!-- .inside -->
					</div><!-- .postbox -->
				</div><!-- .meta-box-sortables .ui-sortable -->
			</div><!-- post-body-content -->
			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
					<div class="postbox">
					<h3><span><?php _e('About', 'wp_smo'); ?></span></h3>
					<div style="padding:0 5px;">
						<p><?php _e('Plugin developed by <a href="http://en.tonyarchambeau.com/">Tony Archambeau</a>.', 'wp_smo'); ?></p>
						<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=FQKK22PPR3EJE&lc=GB&item_name=WP%20Sitemap%20Page&item_number=wp%2dsitemap%2dpage&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted"><?php _e('Donate', 'wp_smo'); ?></a></p>
					</div>
					</div><!-- .postbox -->
				</div><!-- .meta-box-sortables -->
			</div><!-- #postbox-container-1 .postbox-container -->
		</div><!-- #post-body .metabox-holder .columns-2 -->
		<br class="clear" />
	</div><!-- #poststuff -->
</div><!-- .wrap -->
