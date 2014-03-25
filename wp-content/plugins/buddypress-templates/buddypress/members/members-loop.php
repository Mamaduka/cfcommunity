<?php
/**
 * Member loop
 *
 * @package BuddyPress
 * @subpackage Templatepack
 */
?>
<?php do_action( 'bp_before_members_loop' ); ?>
<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>
	<div id="pagination-top" class="pagination">

		<div class="pagination-count">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_directory_members_list' ); ?>

	<ul id="members-list" class="item-list">
	<?php while ( bp_members() ) : bp_the_member(); ?>
		<li>
			<div class="item-avatar">
				<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?></a>
			</div>
			<div class="item-details">
				<div class="item-title">
					<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
				</div>
				<?php if ( bp_get_member_latest_update() ) : ?>
					<div class="update"> <?php bp_member_latest_update(); ?></div>
				<?php endif; ?>
			</div>
			<div class="activity">
				<?php bp_member_last_active(); ?>
			</div>
			<div class="action">
				<?php do_action( 'bp_directory_members_actions' ); ?>
			</div>
		</li>
	<?php endwhile; ?>
	</ul><!-- end #members-list -->
	<?php do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

	<div id="buddypress-pagination-bottom" class="buddypress-pagination">

		<div class="buddypress-pagination-count">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="buddypress-pagination-member buddypress-pagination-links">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>
<?php else: ?>
	<div id="message" class="info">
		<p><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
	</div>
<?php endif; ?>
