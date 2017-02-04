<?php
/*
* Plugin Name: ShortCodePlugin
* Description: Hasznos, kismétetű shortcode plugin
* Version: 1.2
* Author: Webmaster442
* Author URI: https://webmaster442.hu
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*-----------------------------------------------------------------------------
Short Code functions
-----------------------------------------------------------------------------*/

function SubPages($atts) {
	ob_start();
	$a = shortcode_atts( array('wrap' => ''), $atts );
	echo "<". $a['wrap']. ">\n";
	echo "<ul>\n";
	wp_list_pages(array(
					'title_li'    => '',
					'sort_column' => 'menu_order',
					'child_of' => get_the_ID()
				));
	echo "</ul>\n";
	echo "</". $a['wrap']. ">";
	return ob_get_clean();
}

function GoogleDriveList($atts) {
	$a = shortcode_atts( array('id' => '', 'height' => '600px'), $atts );
	return "<iframe src=\"https://drive.google.com/embeddedfolderview?id=".$a['id']."#list\" style=\"width:100%; height:".$a['height']."; border:0;\"></iframe>";
}

function GoogleDriveGrid($atts) {
	$a = shortcode_atts( array('id' => '', 'height' => '600px'), $atts );
	return "<iframe src=\"https://drive.google.com/embeddedfolderview?id=".$a['id']."#grid\" style=\"width:100%; height:".$a['height']."; border:0;\"></iframe>";
}

function Email( $atts , $content = null ) {
	if ( ! is_email( $content ) ) {
		return "Nem helyes formátumú e-mail cím!";
	}
	return '<a href="mailto:' . antispambot( $content ) . '">' . antispambot( $content ) . '</a>';
}

function ArchiveByYear() {
	ob_start();
	$oldestyear = 1;
	$newestyear = 1;
	$args = array ('orderby' => 'date', 'order' => 'ASC', 'posts_per_page' => '1', 'post_status' => 'publish');
	$the_query = new WP_Query( $args );
	while ( $the_query->have_posts() ) : $the_query->the_post();
		$oldestyear = get_the_time('Y');
		break;
	endwhile;
	
	wp_reset_postdata();
	
	$args = array ('orderby' => 'date', 'order' => 'DESC', 'posts_per_page' => '1', 'post_status' => 'publish');
	$the_query = new WP_Query( $args );
	while ( $the_query->have_posts() ) : $the_query->the_post();
		$newestyear = get_the_time('Y');
		//echo $newestyear;
		break;
	endwhile;
	
	wp_reset_postdata();
	for ($i=$oldestyear; $i<=$newestyear; $i++)
	{
		$args = array ( 'orderby' => 'date', 'order' => 'ASC', 'posts_per_page' => '-1', 'post_status' => 'publish', 'year' => $year);
		$the_query = new WP_Query( $args );
		if ($the_query->have_posts())
		{
			echo ('<h2>'.$i.'</h2>');
			echo('<ul>');
			while ( $the_query->have_posts() ) : $the_query->the_post();
				$date = get_the_time('Y-m-d');
				$perma = '<li><a href="'. get_permalink() .'">'.get_the_title().'</a> ('.$date.')</li>';
				echo $perma;
			endwhile;
			echo('</ul>');	
			wp_reset_postdata();
		}
	}
	return ob_get_clean();
}

function renderSubCat($id) {
	$posts = get_posts(array('category__in' => $id, 'posts_per_page' => '999', 'orderby' => 'date', 'order' => 'asc'));
	echo '<ol>';
	foreach ($posts as $post) {
		echo '<li>';
		echo '<a href="' .get_permalink($post->ID). '">'. $post->post_title .'</a>';
		echo '</li>';
	}
	echo '</ol>';
}


function ArchiveByCategory() {
	ob_start();
	$categories = get_categories(array('orderby' => 'name', 'order' => 'ASC', 'parent' => 0));
	foreach ($categories as $category) {
		$cat_id = $category->term_id;
		echo '<h2>' .$category->name. '</h2>';
		$sub = get_categories(array('orderby' => 'name', 'order' => 'ASC', 'child_of' => $cat_id));
		$haschilds = count($sub) > 0;
		renderSubCat($cat_id);
		if ($haschilds) {
			echo '<div style="padding-left: 30px;">';
			foreach ($sub as $subcat) {
				echo '<h3>' .$subcat->name. '</h3>';
				renderSubCat($subcat->term_id);
			}
			echo '</div>';
		}
	}
	return ob_get_clean();
}

function Archive($atts) {
	$a = shortcode_atts( array('type' => 'year'), $atts );
	if ($a['type'] == 'year')
		return ArchiveByYear();
	else if ($a['type'] == 'category') 
		return ArchiveByCategory();
	else return 'Ismeretlen típus';
}

function MarkDown($atts , $content = null) {
	require_once('Parsedown.php');
	$Parsedown = new Parsedown();
	return $Parsedown->text($content);
}

function LoginLogoutLink() {
	ob_start();
	$login = is_user_logged_in();
	if ($login == true) {
		$user = wp_get_current_user();
		echo '<i class="fa fa-user" aria-hidden="true"></i> ';
		echo '<a href="'. get_edit_user_link().'">'.$user->display_name.'</a> | ';
		echo '<i class="fa fa-sign-out" aria-hidden="true"></i> ';
		echo '<a href="'. wp_logout_url(home_url()) . '" title="Kijelentkezés">Kijelentkezés</a>';
	}
	else {
		echo '<i class="fa fa-sign-in" aria-hidden="true"></i> ';
		echo '<a href="'.wp_login_url( get_permalink() ). '" title="Bejelentkezés">Bejelentkezés</a>';
	}
	return ob_get_clean();
}

/*-----------------------------------------------------------------------------
Registration
-----------------------------------------------------------------------------*/

function my_tinymce_button() {
     if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
          add_filter( 'mce_buttons', 'my_register_tinymce_button' );
          add_filter( 'mce_external_plugins', 'my_add_tinymce_button' );
     }
}

function my_register_tinymce_button( $buttons ) {
     array_push( $buttons, "button_codes" );
     return $buttons;
}

function my_add_tinymce_button( $plugin_array ) {
     $plugin_array['my_button_script'] = plugins_url( '/EditorPlugin.js', __FILE__ ) ;
     return $plugin_array;
}

function my_plugin_menu() {
	add_options_page( 'ShortCode plugin', 'ShortCodePlugin', 'edit_posts', 'ShortCodePluginOptions', 'ShortCodeOptionsMenu' );
}

function ShortCodeOptionsMenu() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include('leiras.html');
}

add_action( 'admin_init', 'my_tinymce_button' );
add_action( 'admin_menu', 'my_plugin_menu' );
add_shortcode('subpages', 'SubPages');
add_shortcode('email', 'Email');
add_shortcode('archive', 'Archive');
add_shortcode('markdown', 'MarkDown');
add_shortcode('loginlogout', 'LoginLogoutLink');
add_shortcode('drivefolder-list', 'GoogleDriveList');
add_shortcode('drivefolder-grid', 'GoogleDriveList');

wp_enqueue_style('prism.css', plugin_dir_url( __FILE__ ) . 'prism.css',false,'1.1','screen');
wp_enqueue_script('prism.js', plugin_dir_url( __FILE__ ) . 'prism.js', false, '1.1', false);
?>