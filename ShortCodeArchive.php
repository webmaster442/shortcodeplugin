<?php
class ArchiveGenerator
{
    public static function ArchiveByYear() {
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

    public static function renderSubCat($id) {
        $posts = get_posts(array('category__in' => $id, 'posts_per_page' => '999', 'orderby' => 'date', 'order' => 'asc'));
        echo '<ol>';
        foreach ($posts as $post) {
            echo '<li>';
            echo '<a href="' .get_permalink($post->ID). '">'. $post->post_title .'</a>';
            echo '</li>';
        }
        echo '</ol>';
    }

    public static function ArchiveByCategory() {
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
	
	public static function Generate($type) {
		if ($type === 'year') return ArchiveByYear();
		else if ($type === 'category') return ArchiveByCategory();
		else return "";
	}
}
?>