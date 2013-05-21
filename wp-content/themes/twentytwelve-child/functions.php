<?php
/*
add_action( 'edit_form_advanced', 'my_second_editor' );
function my_second_editor() {
	// get and set $content somehow...
	wp_editor( $content, 'mysecondeditor' );
}
*/


add_action( 'admin_menu', 'my_admin_menu' );
function my_admin_menu() {
    add_menu_page( 'My Theme Options', 'My Theme Options', 'edit_theme_options', 'my-theme-options', 'my_theme_options' );
}

function my_theme_options() {
?>
    <div class="wrap">
        <div><br></div>
        <h2>My Theme Options</h2>

        <form method="post" action="options.php">
            <?php wp_nonce_field( 'update-options' ); ?>
            <?php settings_fields( 'my-options' ); ?>
            <?php do_settings_sections( 'my-options' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}


add_action( 'admin_init', 'my_register_admin_settings' );
function my_register_admin_settings() {
    register_setting( 'my-options', 'my-options' );

    // Settings fields and sections
    add_settings_section( 'section_typography', 'Typography Options', 'my_section_typography', 'my-options' );
    add_settings_field( 'primary-font', 'Primary Font', 'my_field_primary_font', 'my-options', 'section_typography' );
    add_settings_field( 'sub-font', 'Sub Font', 'my_field_sub_font', 'my-options', 'section_typography' );
}
function my_section_typography() {
    echo 'Section description can go here.';
}

function my_field_primary_font() {
    $options = (array) get_option( 'my-options' );
    $fonts = get_my_available_fonts();
    $current_font = 'arial';

    if ( isset( $options['primary-font'] ) )
        $current_font = $options['primary-font'];

    ?>
        <select name="my-options[primary-font]">
        <?php foreach( $fonts as $font_key => $font ): ?>
            <option <?php selected( $font_key == $current_font ); ?> value="<?php echo $font_key; ?>"><?php echo $font['name']; ?></option>
        <?php endforeach; ?>
        </select>
    <?php
}
function my_field_sub_font() {
    $options = (array) get_option( 'my-options' );
    $fonts = get_my_available_fonts();
    $current_font = 'arial';

    if ( isset( $options['sub-font'] ) )
        $current_font = $options['sub-font'];

    ?>
        <select name="my-options[sub-font]">
        <?php foreach( $fonts as $font_key => $font ): ?>
            <option <?php selected( $font_key == $current_font ); ?> value="<?php echo $font_key; ?>"><?php echo $font['name']; ?></option>
        <?php endforeach; ?>
        </select>
    <?php
}
function get_my_available_fonts() {
    $fonts = array(
        'open-sans' => array(
            'name' => 'Open Sans',
            'import' => '@import url(http://fonts.googleapis.com/css?family=Open+Sans);',
            'css' => "font-family: 'Open Sans', sans-serif;"
        ),
        'lato' => array(
            'name' => 'Lato',
            'import' => '@import url(http://fonts.googleapis.com/css?family=Lato);',
            'css' => "font-family: 'Lato', sans-serif;"
        ),
        'arial' => array(
            'name' => 'Arial',
            'import' => '',
            'css' => "font-family: Arial, sans-serif;"
        )
    );

    return apply_filters( 'my_available_fonts', $fonts );
}
?>





<?php

require_once("section-page/section-page.php");

?>

