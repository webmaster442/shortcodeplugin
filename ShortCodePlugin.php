<?php
/**
 * Plugin Name: ShortCode Plugin
 * Description: Hasznos shortcode-ok
 * Version: 2.0
 * Author: Ruzsinszki Gábor
 * Author URI: https://webmaster442.hu
 * License: GPL2
 */

class ShortCodePlugin
{
	public $Notes = array();
	public $Counter = 0;
	
    public function __construct() {
        //kódok regisztrálása
        add_shortcode('subpages', array($this, 'SubPages'));
        add_shortcode('email', array($this, 'Email'));
        add_shortcode('archive', array($this, 'Archive'));
        add_shortcode('markdown', array($this, 'MarkDown'));
        add_shortcode('loginlogout', array($this, 'LoginLogoutLink'));
        add_shortcode('note', array($this, 'Note'));
        add_shortcode('drivefolder-list', array($this, 'GoogleDriveList'));
        add_shortcode('drivefolder-grid', array($this, 'GoogleDriveList'));
        //editor menü
        add_action( 'admin_init', array($this, 'RegisterMenu'));
		//admin menü
		add_action('admin_menu', array($this, 'RegisterAdminMenu'));
		//Regisztráljuk a js & css fájlokat amik kód függőek
		add_action('wp_enqueue_scripts', array($this, 'RegisterJSCSS'));
		//a többit meg hozzáadjuk
		wp_enqueue_style('prism.css', plugin_dir_url( __FILE__ ) . '/assets/prism.css',false,'1.1','screen');
		wp_enqueue_script('prism.js', plugin_dir_url( __FILE__ ) . '/assets/prism.js', false, '1.1', false);
		//note megjelenítés content után
		add_filter('the_content', array($this, 'NoteAfterContent'), 20);
    }
	
	public function RegisterJSCSS() {
		wp_register_style( 'TooltipCSS', plugins_url( '/assets/tooltip.css' , __FILE__ ), null, false, false );
	}
	
	public function RegisterAdminMenu() {
		add_options_page( 'ShortCode plugin', 'ShortCodePlugin', 'edit_posts', 'ShortCodePluginOptions', array($this, 'ShortCodeOptionsMenu') );
	}
	
	public function ShortCodeOptionsMenu() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		include('leiras.html');
	}

    public function RegisterMenu() {
        if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
            add_filter( 'mce_buttons', array($this, 'RegisterButton') );
            add_filter( 'mce_external_plugins', array($this, 'AddButton') );
        }
    }

    public function RegisterButton( $buttons ) {
        array_push( $buttons, "button_codes" );
        return $buttons;
    }
	
    public function AddButton( $plugin_array ) {
        $plugin_array['my_button_script'] = plugins_url( '/EditorPlugin.js', __FILE__ ) ;
        return $plugin_array;
    }
    //--------------------------------------------------------------------------------------------------------------------------------
    //Kódok implementációja
    //--------------------------------------------------------------------------------------------------------------------------------
    public function SubPages($atts) {
        ob_start();
        $a = shortcode_atts( array('wrap' => ''), $atts );
        echo "<". $a['wrap']. ">\n";
        echo "<ul>\n";
        wp_list_pages(array(
                          'title_li'    => '',
                          'sort_column' => 'menu_order',
                          'child_of' => get_the_ID()));
        echo "</ul>\n";
        echo "</". $a['wrap']. ">";
        return ob_get_clean();
    }

    public function GoogleDriveList($atts) {
        $a = shortcode_atts( array('id' => '', 'height' => '600px'), $atts );
        return "<iframe src=\"https://drive.google.com/embeddedfolderview?id=".$a['id']."#list\" style=\"width:100%; height:".$a['height']."; border:0;\"></iframe>";
    }

    public function GoogleDriveGrid($atts) {
        $a = shortcode_atts( array('id' => '', 'height' => '600px'), $atts );
        return "<iframe src=\"https://drive.google.com/embeddedfolderview?id=".$a['id']."#grid\" style=\"width:100%; height:".$a['height']."; border:0;\"></iframe>";
    }

    public function Email( $atts , $content = null ) {
        if ( ! is_email( $content ) ) {
            return "Nem helyes formátumú e-mail cím!";
        }
        return '<a href="mailto:' . antispambot( $content ) . '">' . antispambot( $content ) . '</a>';
    }

    public function Archive($atts) {
        $a = shortcode_atts( array('type' => 'year'), $atts );
        require_once('ShortCodeArchive.php');
        return ArchiveGenerator::Generate($a['type']);
    }

    public function MarkDown($atts , $content = null) {
        require_once('Parsedown.php');
        $Parsedown = new Parsedown();
        return $Parsedown->text($content);
    }

    public function LoginLogoutLink() {
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
	
	public function Note($atts , $content = null) {
		//http://www.webdesignerdepot.com/2012/11/how-to-create-a-simple-css3-tooltip/
		ob_start();
		wp_enqueue_style('TooltipCSS');
		$count = $this->Counter;
		$this->Notes[$count] = $content;
		$count++;
		$this->Counter = $count;
		
		echo '<a href="#" title="'.$content.'" class="tooltip">';
		echo '<sup>".$count."</sup></a>';
		return ob_get_clean();
	}
	
	public function NoteAfterContent($content) {
		if (is_singular() && is_main_query()) {
			global $notes;
			$notes .= '<div><h4>Lábjegyzetek</h4>';
			$notes .= '<ol>';
			foreach ($this->Note as $note) {
				$notes.='<li>'.$note.'</li>';
			}
			$notes .= '</ol>';
			$content .= $notes;
		}
		return $content;
	}
}

$shortcodes = new ShortCodePlugin();
?>