<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * This is a generic file for admin interfaces.
 *
 * It will load appropriate templates according to the current admin area position from either ./_parts/ folder
 * or from ./admin/ folder.
 */


/**
 * Adding our subpages to Groups
 */
function bpgt_admin_init() {
    add_action( bp_core_admin_hook(), 'bpgt_admin_menus', 99 );
}
add_action( 'bp_init', 'bpgt_admin_init' );

function bpgt_admin_menus() {
    // Duplicating to make it consistent comparing to other top level menus with their children
    add_submenu_page(
        'bp-groups',
        __( 'Groups', 'buddypress' ),
        __( 'Groups', 'buddypress' ),
        'bp_moderate',
        'bp-groups',
        'bp_groups_admin'
    );

    $types_hook = add_submenu_page(
        'bp-groups',
        __( 'Group Types', 'bpgt' ),
        __( 'Types', 'bpgt' ),
        'bp_moderate',
        'bp-groups-types',
        'bpgt_admin_pages'
    );

    $fields_hook = add_submenu_page(
        'bp-groups',
        __( 'Group Fields', 'bpgt' ),
        __( 'Fields', 'bpgt' ),
        'bp_moderate',
        'bp-groups-fields',
        'bpgt_admin_pages'
    );

    if (
        !empty($_POST) &&
        ( isset($_GET['page']) && ( $_GET['page'] == 'bp-groups-fields' || $_GET['page'] == 'bp-groups-types' ) ) &&
        ( isset($_GET['mode']) && ( $_GET['mode'] == 'add_type' || $_GET['mode'] == 'edit_type' ) )
    ) {
        bpgt_admin_save();
    }

    add_action( "load-$types_hook",  'bpgt_admin_types_load_assets' );
    add_action( "load-$fields_hook", 'bpgt_admin_fields_load_assets' );
}

/**
 * Load appropriate code and admin templates according to the page
 */
function bpgt_admin_pages(){
    switch($_GET['page']){
        case 'bp-groups-types':
            include_once( BPGT_PATH . '/admin/types.php' );
            bpgt_admin_page_types();
            break;
        case 'bp-groups-fields':
            include_once( BPGT_PATH . '/admin/fields.php' );
            bpgt_admin_page_fields();
            break;
    }
}

/**
 * Process types and fields save
 */
function bpgt_admin_save(){
    if ( isset($_POST['save_type']) ) {
        $link = bpgt_admin_save_type();
    } elseif ( isset($_POST['save_field']) ) {
        $link = bpgt_admin_save_field();
    } else {
        $link = remove_query_arg( array( 'mode', 'message' ) );
    }

    wp_redirect($link);
    exit;
}

/**
 * Insert / Update type in DB
 *
 * @return string $link Link used to redirect user to empty global $_POST array
 */
function bpgt_admin_save_type(){
    switch ($_POST['mode']) {
        case 'add':
            $type = new BPGT_Type();

            $type->title     = apply_filters( 'bpgt_admin_create_type_title',   wp_strip_all_tags($_POST['type_title']) );
            $type->content   = apply_filters( 'bpgt_admin_create_type_content', wp_strip_all_tags($_POST['type_description']) );
            $type->order     = apply_filters( 'bpgt_admin_create_type_order',   wp_strip_all_tags($_POST['type_order']) );
            $type->avatar_id = apply_filters( 'bpgt_admin_create_type_avatar',  wp_strip_all_tags($_POST['type_avatar']) );
            $type->page      = apply_filters( 'bpgt_admin_create_type_page',    wp_strip_all_tags($_POST['type_page']) );

            if ( $type_id = $type->save() ) {
                $link = add_query_arg( array( 'mode' => 'edit_type', 'type_id' => $type_id, 'message' => 'type_created' ) );
            } else {
                $link = add_query_arg( array( 'mode' => 'add_type', 'message' => 'error' ) );
            }

            break;

        case 'edit':
            $type_id = (int) $_POST['type_id'];
            $type    = new BPGT_Type($type_id);

            $type->title     = apply_filters( 'bpgt_admin_update_type_title',   wp_strip_all_tags($_POST['type_title']), $type_id );
            $type->content   = apply_filters( 'bpgt_admin_update_type_content', wp_strip_all_tags($_POST['type_description']), $type_id );
            $type->order     = apply_filters( 'bpgt_admin_update_type_order',   wp_strip_all_tags($_POST['type_order']), $type_id );
            $type->avatar_id = apply_filters( 'bpgt_admin_update_type_avatar',  wp_strip_all_tags($_POST['type_avatar']), $type_id );
            $type->page      = apply_filters( 'bpgt_admin_update_type_page',    wp_strip_all_tags($_POST['type_page']), $type_id );

            if ( $type->save() ) {
                $link = add_query_arg( array( 'mode' => 'edit_type', 'type_id' => $type_id, 'message' => 'type_updated' ) );
            } else {
                $link = add_query_arg( array( 'mode' => 'edit_type', 'type_id' => $type_id, 'message' => 'error' ) );
            }

            break;

        default:
            $link = add_query_arg( array( 'mode' => 'add_type', 'message' => 'error' ) );
    }

    return $link;
}

function bpgt_admin_save_field(){
    $link = add_query_arg( array( 'mode' => 'add_field', 'message' => 'error' ) );

    return $link;
}

/**
 * Enqueueing CSS / JS
 */
function bpgt_admin_types_load_assets(){
    wp_enqueue_media();
    wp_enqueue_script( 'bpgt_admin_types_js',  BPGT_URL . "/js/admin-types.js", array( 'jquery' ), BPGT_VERSION, true );
    wp_enqueue_style(  'bpgt_admin_types_css', BPGT_URL . "/css/admin-types.css", array(), BPGT_VERSION );

    wp_localize_script( 'bpgt_admin_types_js', 'BPGT_Admin_Types', array(
        'str_ok'     => __('OK', 'bpgt'),
        'str_cancel' => __('Cancel', 'bpgt')
    ) );
}

function bpgt_admin_fields_load_assets(){
    wp_enqueue_script( 'bpgt_admin_fields_js',  BPGT_URL . "/js/admin-fields.js", array( 'jquery' ), BPGT_VERSION, true );
    wp_enqueue_style(  'bpgt_admin_fields_css', BPGT_URL . "/css/admin-fields.css", array(), BPGT_VERSION );

    wp_localize_script( 'bpgt_admin_fields_js', 'BPGT_Admin_Fields', array(
        'test' => 'BPGT_Admin_Fields'
    ) );
}

/**
 * Update messages in $_GET requires ome human-readable description texts
 *
 * @param string $code  Identifier in get
 * @return array
 */
function bpgt_admin_get_messages_data($code){
    switch($code){
        case 'type_created':
            $cls = 'updated';
            $msg = __('Group Type was successfully created.', 'bpgt');
            break;

        case 'type_updated':
            $cls = 'updated';
            $msg = __('Group Type was successfully updated.', 'bpgt');
            break;

        case 'field_created':
            $cls = 'updated';
            $msg = __('Group Field was successfully created.', 'bpgt');
            break;

        case 'field_updated':
            $cls = 'updated';
            $msg = __('Group Field was successfully updated.', 'bpgt');
            break;

        default:
            $cls = 'error';
            $msg = __('Something went wrong, please try again', 'bpgt');
    }

    return array(
        'class'   => $cls,
        'message' => $msg
    );
}