<?php
/**
 * Launchpad
 *
 * This file provides helpers that return the appropriate Launchpad
 * checklist and tasks for a given checklist slug.
 *
 * @package A8C\Launchpad
 */

/**
 * Returns the list of tasks by flow or checklist slug.
 *
 * @return array Associative array with checklist task data
 */
function get_checklist_definitions() {
	return array(
		'build'           => array(
			'setup_general',
			'design_selected',
			'first_post_published',
			'design_edited',
			'site_launched',
		),
		'free'            => array(
			'setup_free',
			'design_selected',
			'domain_upsell',
			'first_post_published',
			'design_edited',
			'site_launched',
		),
		'link-in-bio'     => array(
			'design_selected',
			'setup_link_in_bio',
			'plan_selected',
			'links_added',
			'link_in_bio_launched',
		),
		'link-in-bio-tld' => array(
			'design_selected',
			'setup_link_in_bio',
			'plan_selected',
			'links_added',
			'link_in_bio_launched',
		),
		'newsletter'      => array(
			'setup_newsletter',
			'plan_selected',
			'subscribers_added',
			'verify_email',
			'first_post_published_newsletter',
		),
		'videopress'      => array(
			'videopress_setup',
			'plan_selected',
			'videopress_upload',
			'videopress_launched',
		),
		'write'           => array(
			'setup_write',
			'design_selected',
			'first_post_published',
			'site_launched',
		),
	);
}

/**
 * Determines whether or not design selected task is enabled
 *
 * @return boolean True if design selected task is enabled
 */
function can_update_design_selected_task() {
	$site_intent = get_option( 'site_intent' );
	return $site_intent === 'free' || $site_intent === 'build' || $site_intent === 'write';
}

/**
 * Determines whether or not domain upsell task is completed
 *
 * @return boolean True if domain upsell task is completed
 */
function is_domain_upsell_completed() {
	if ( defined( 'IS_WPCOM' ) && IS_WPCOM ) {
		if ( class_exists( '\WPCOM_Store_API' ) ) {
			$plan = \WPCOM_Store_API::get_current_plan( \get_current_blog_id() );
			return ! $plan['is_free'] || get_checklist_task( 'domain_upsell_deferred' );
		}
	}

	return get_checklist_task( 'domain_upsell_deferred' );
}

/**
 * Returns the subtitle for the plan selected task
 *
 * @return string Subtitle text
 */
function get_plan_selected_subtitle() {
	if ( ! function_exists( 'wpcom_global_styles_in_use' ) || ! function_exists( 'wpcom_should_limit_global_styles' ) ) {
		return '';
	}

	return wpcom_global_styles_in_use() && wpcom_should_limit_global_styles()
		? __(
			'Your site contains custom colors that will only be visible once you upgrade to a Premium plan.',
			'jetpack-mu-wpcom'
		) : '';
}

/**
 * Returns the badge text for the plan selected task
 *
 * @return string Badge text
 */
function get_domain_upsell_badge_text() {
	return is_domain_upsell_completed() ? '' : __( 'Upgrade plan', 'jetpack-mu-wpcom' );
}

/**
 * Returns the checklist task definitions.
 *
 * @return array Associative array with checklist task data
 */
function get_task_definitions() {
	return array(
		'setup_newsletter'
			=> array(
				'id'        => 'setup_newsletter',
				'title'     => __( 'Personalize Newsletter', 'jetpack-mu-wpcom' ),
				'completed' => true,
				'disabled'  => false,
			),
		'plan_selected'
			=> array(
				'id'        => 'plan_selected',
				'title'     => __( 'Choose a Plan', 'jetpack-mu-wpcom' ),
				'subtitle'  => get_plan_selected_subtitle(),
				'completed' => true,
				'disabled'  => false,
			),
		'subscribers_added'
			=> array(
				'id'        => 'subscribers_added',
				'title'     => __( 'Add Subscribers', 'jetpack-mu-wpcom' ),
				'completed' => true,
				'disabled'  => false,
			),
		'first_post_published'
			=> array(
				'id'        => 'first_post_published',
				'title'     => __( 'Write your first post', 'jetpack-mu-wpcom' ),
				'completed' => get_checklist_task( 'first_post_published' ),
				'disabled'  => false,
			),
		'first_post_published_newsletter'
			=> array(
				'id'        => 'first_post_published_newsletter',
				'title'     => __( 'Start writing', 'jetpack-mu-wpcom' ),
				'completed' => get_checklist_task( 'first_post_published' ),
				'disabled'  => false,
			),
		'design_selected'
			=> array(
				'id'        => 'design_selected',
				'title'     => __( 'Select a design', 'jetpack-mu-wpcom' ),
				'completed' => true,
				'disabled'  => ! can_update_design_selected_task(),
			),
		'setup_link_in_bio'
			=> array(
				'id'        => 'setup_link_in_bio',
				'title'     => __( 'Personalize Link in Bio', 'jetpack-mu-wpcom' ),
				'completed' => true,
				'disabled'  => false,
			),
		'links_added'
			=> array(
				'id'        => 'links_added',
				'title'     => __( 'Add links', 'jetpack-mu-wpcom' ),
				'completed' => get_checklist_task( 'links_edited' ),
				'disabled'  => false,
			),
		'link_in_bio_launched'
			=> array(
				'id'        => 'link_in_bio_launched',
				'title'     => __( 'Launch your site', 'jetpack-mu-wpcom' ),
				'completed' => get_checklist_task( 'site_launched' ),
				'disabled'  => ! get_checklist_task( 'links_edited' ),
			),
		'videopress_setup'
			=> array(
				'id'        => 'videopress_setup',
				'title'     => __( 'Set up your video site', 'jetpack-mu-wpcom' ),
				'completed' => true,
				'disabled'  => false,
			),
		'videopress_upload'
			=> array(
				'id'        => 'videopress_upload',
				'title'     => __( 'Upload your first video', 'jetpack-mu-wpcom' ),
				'completed' => get_checklist_task( 'video_uploaded' ),
				'disabled'  => get_checklist_task( 'video_uploaded' ),
			),
		'videopress_launched'
			=> array(
				'id'        => 'videopress_launched',
				'title'     => __( 'Launch site', 'jetpack-mu-wpcom' ),
				'completed' => get_checklist_task( 'site_launched' ),
				'disabled'  => ! get_checklist_task( 'video_uploaded' ),
			),
		'setup_free'
			=> array(
				'id'        => 'setup_free',
				'title'     => __( 'Personalize your site', 'jetpack-mu-wpcom' ),
				'completed' => true,
				'disabled'  => false,
			),
		'setup_general'
			=> array(
				'id'        => 'setup_general',
				'title'     => __( 'Set up your site', 'jetpack-mu-wpcom' ),
				'completed' => true,
				'disabled'  => true,
			),
		'design_edited'
			=> array(
				'id'        => 'design_edited',
				'title'     => __( 'Edit site design', 'jetpack-mu-wpcom' ),
				'completed' => get_checklist_task( 'site_edited' ),
				'disabled'  => false,
			),
		'site_launched'
			=> array(
				'id'           => 'site_launched',
				'title'        => __( 'Launch your site', 'jetpack-mu-wpcom' ),
				'completed'    => get_checklist_task( 'site_launched' ),
				'disabled'     => false,
				'isLaunchTask' => true,
			),
		'setup_write'
			=> array(
				'id'        => 'setup_write',
				'title'     => __( 'Set up your site', 'jetpack-mu-wpcom' ),
				'completed' => true,
				'disabled'  => true,
			),
		'domain_upsell'
			=> array(
				'id'         => 'domain_upsell',
				'title'      => __( 'Choose a domain', 'jetpack-mu-wpcom' ),
				'completed'  => is_domain_upsell_completed(),
				'disabled'   => false,
				'badge_text' => get_domain_upsell_badge_text(),
			),
		'verify_email'
			=> array(
				'id'       => 'verify_email',
				'title'    => __( 'Confirm Email (Check Your Inbox)', 'jetpack-mu-wpcom' ),
				'complete' => false,
				'disabled' => true,
			),
	);
}

/**
 * Returns launchpad checklist task by task id.
 *
 * @param string $task Task id.
 *
 * @return array Associative array with task data
 *               or false if task id is not found.
 */
function get_checklist_task( $task ) {
	$launchpad_checklist_tasks_statuses_option = get_option( 'launchpad_checklist_tasks_statuses' );
	if ( is_array( $launchpad_checklist_tasks_statuses_option ) && isset( $launchpad_checklist_tasks_statuses_option[ $task ] ) ) {
			return $launchpad_checklist_tasks_statuses_option[ $task ];
	}

	return false;
}

/**
 * Returns launchpad checklist by checklist slug.
 *
 * @param string $checklist_slug Checklist slug.
 *
 * @return array Associative array with checklist task
 *               or empty array if checklist slug is not found.
 */
function build_checklist( $checklist_slug ) {
	$checklist = array();
	if ( null === ( get_checklist_definitions()[ $checklist_slug ] ) ) {
		return $checklist;
	}
	foreach ( get_checklist_definitions()[ $checklist_slug ] as $task_id ) {
		$checklist[] = get_task_definitions()[ $task_id ];
	}
	return $checklist;
}

/**
 * Returns launchpad checklist by checklist slug.
 *
 * @param string $checklist_slug Checklist slug.
 *
 * @return array Associative array with checklist task data
 */
function get_launchpad_checklist_by_checklist_slug( $checklist_slug ) {
	if ( ! $checklist_slug ) {
		return array();
	}
	return build_checklist( $checklist_slug );
}
