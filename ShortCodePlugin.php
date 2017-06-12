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
	public $footnotes = array();
	public $footnoteCount = 0;
	public $prevPost;
	
    public function __construct() {
        //kódok regisztrálása
        add_shortcode('subpages', array($this, 'SubPages'));
        add_shortcode('email', array($this, 'Email'));
        add_shortcode('archive', array($this, 'Archive'));
        add_shortcode('markdown', array($this, 'MarkDown'));
        add_shortcode('loginlogout', array($this, 'LoginLogoutLink'));
		add_shortcode('registerlink', array($this, 'RegisterLink'));
        add_shortcode('note', array($this, 'Note'));
        add_shortcode('drivefolder-list', array($this, 'GoogleDriveList'));
        add_shortcode('drivefolder-grid', array($this, 'GoogleDriveList'));
		add_shortcode('logedin', array($this, 'IsLogedInConent'));
		add_shortcode('csvtable', array($this, 'CsvTable'));
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
		wp_register_script( 'qtip', plugins_url( '/assets/jquery.qtip.min.js' , __FILE__ ), false, false, true );
		wp_register_script( 'tablesorter', plugins_url( '/assets/tablesorter.js' , __FILE__ ), false, false, true );
		wp_register_script( 'call', plugins_url( '/assets/caller.js' , __FILE__ ), array('jquery', 'qtip', 'tablesorter'), false, true );
		wp_register_style( 'qtipstyles', plugins_url( '/assets/jquery.qtip.min.css' , __FILE__ ), null, false, false );
		wp_register_style( 'tablesorterstyle', plugins_url( '/assets/tablesorter.css' , __FILE__ ), null, false, false );
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
        $a = shortcode_atts( array('type' => 'year', 'exclude' => null), $atts );
        require_once('ShortCodeArchive.php');
		$archive = new ArchiveGenerator();
        return $archive->Generate($a['type'], $a['exclude']);
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
	
	public function RegisterLink() {
		ob_start();
		$login = is_user_logged_in();
		if ($login == false) {
			echo '<i class="fa fa-user-plus" aria-hidden="true"></i> <a href="'.wp_registration_url().'">Regisztáció</a>';
		}
		return ob_get_clean();
	}
	
	public function IsLogedInConent($atts , $content = null) {
		ob_start();
		$login = is_user_logged_in();
		if ($login) {
			echo $content;
		}
		return ob_get_clean();
	}
	
	public function CsvTable($atts , $content = null) {
		wp_enqueue_style( 'tablesorterstyle' );
		wp_enqueue_script( 'tablesorter' );
		wp_enqueue_script( 'call' );
		$a = shortcode_atts( array('delimiter' => ';'), $atts );
		require_once("CsvGenerator.php");
		$gen = new CsvGenerator();
		return $gen->Generate($content, $a['delimiter']);
	}
	
	public function Note($atts , $content = null) {
		wp_enqueue_style( 'qtipstyles' );
		wp_enqueue_script( 'qtip' );
		wp_enqueue_script( 'call' );
		$this->easy_footnote_count($this->footnoteCount, get_the_ID());
		$this->easy_footnote_content($content);
		
		if (is_singular() && is_main_query()) {
			$footnoteLink = '#easy-footnote-bottom-'.$this->footnoteCount;
		}
		else {
			$footnoteLink = get_permalink(get_the_ID()).'#easy-footnote-bottom-'.$this->footnoteCount;
		}
		$footnoteContent = "<span id='easy-footnote-".$this->footnoteCount."' class='easy-footnote-margin-adjust'></span><span class='easy-footnote'><a href='".$footnoteLink."' title='".htmlspecialchars($content, ENT_QUOTES)."'><sup>$this->footnoteCount</sup></a></span>";
		return $footnoteContent;
	}
	
	private function easy_footnote_content($content) {
		$this->footnotes[$this->footnoteCount] = $content;
		return $this->footnotes;
	}

	private function easy_footnote_count($count, $currentPost) {
		if ($this->prevPost != $currentPost) {
			$count = 0;
		}

		$this->prevPost = $currentPost;
		$count++;
		$this->footnoteCount = $count;

		return $this->footnoteCount;
	}

	private function CoppyRightText() {
		$user = wp_get_current_user();
		return '<div class="copyright" style="display:none;">' . 
		       '© ' . date("Y").' '.get_bloginfo( 'name' ).'<br/>' .
			   'Felhasználó: ' . $user->user_email . ' ' .$user->display_name .'</div>';
	}

	public function NoteAfterContent($content) {
		if (is_singular() && is_main_query()) {
			$footnotesInsert = $this->footnotes;
			global $footnoteCopy;
			
			//$content .= '<style>.admin-bar .easy-footnote-margin-adjust { position: absolute; margin-top: -40px; }</style>';

			foreach ($footnotesInsert as $count => $footnote) {
				$footnoteCopy .= '<li class="easy-footnote-single"><span id="easy-footnote-bottom-'.$count.'" class="easy-footnote-margin-adjust"></span>'.$footnote.' <a href="#easy-footnote-'.$count.'"><i class="fa fa-level-up" aria-hidden="true"></i></a></li>';
			}
			if (!empty($footnotesInsert)) {
					$content .= '<div class="easy-footnote-title"><h4>Lábjegyzetek</h4></div><ol class="easy-footnotes-wrapper">'.$footnoteCopy.'</ol>';
			}
			$content .= $this->CoppyRightText();
		}
		return $content;
	}
}
$shortcodes = new ShortCodePlugin();
?>