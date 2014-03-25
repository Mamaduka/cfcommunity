<?php
/**
 * Member profile
 *
 * @package BuddyPress
 * @subpackage Templatepack
 */
?>

<nav id="subnav" class="item-list-tabs no-ajax" role="navigation">
	<ul>
		<?php bp_get_options_nav(); ?>
	</ul>
</nav><!-- .item-list-tabs -->

<?php do_action( 'bp_before_profile_content' ); ?>

<div id="profile">

<?php switch ( bp_current_action() ) :

	// Edit
	case 'edit'   :
		bp_get_template_part( 'members/single/profile/edit' );
		break;

	// Change Avatar
	case 'change-avatar' :
		bp_get_template_part( 'members/single/profile/change-avatar' );
		break;

	// Compose
	case 'public' :

		// Display XProfile
		if ( bp_is_active( 'xprofile' ) )
			bp_get_template_part( 'members/single/profile/profile-loop' );

		// Display WordPress profile (fallback)
		else
			bp_get_template_part( 'members/single/profile/profile-wp' );

		break;

	// Any other
	default :
		bp_get_template_part( 'members/single/plugins' );
		break;
endswitch; ?>
</div><!-- .profile -->

<?php do_action( 'bp_after_profile_content' ); ?>
