<?php
class ArchiveGenerator
{
    private function ArchiveByYear($open) {
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
            $args = array ( 'orderby' => 'date', 'order' => 'ASC', 'posts_per_page' => '-1', 'post_status' => 'publish', 'year' => $i);
            $the_query = new WP_Query( $args );
            if ($open === true)
				echo '<details open>';
			else
				echo '<details>';
            if ($the_query->have_posts())
            {
                echo '<summary>'.$i.'</summary>';
                echo '<div>';
                echo '<ul>';
                while ( $the_query->have_posts() ) : $the_query->the_post();
                $date = get_the_time('Y-m-d');
                $perma = '<li><a href="'. get_permalink() .'">'.get_the_title().'</a> ('.$date.')</li>';
                echo $perma;
                endwhile;
                echo '</ul>';
                echo '</div>';
                wp_reset_postdata();
            }
            echo '</details>';
        }
        return ob_get_clean();
    }

    private function renderSubCat($id) {
        $posts = get_posts(array('category__in' => $id, 'posts_per_page' => '999', 'orderby' => 'date', 'order' => 'asc'));
        echo '<div>';
        echo '<ol>';
        foreach ($posts as $post) {
            echo '<li>';
            echo '<a href="' .get_permalink($post->ID). '">'. $post->post_title .'</a>';
            echo '</li>';
        }
        echo '</ol>';
        echo '</div>';
    }

    private function ArchiveByCategory($open, $exclude) {
        ob_start();
        if (isset($exclude))
            $categories = get_terms('category', array('orderby' => 'name', 'order' => 'ASC', 'parent' => 0, 'exclude_tree' => $exclude));
        else	
            $categories = get_terms('category', array('orderby' => 'name', 'order' => 'ASC', 'parent' => 0));

        foreach ($categories as $category) {
			if ($open === true)
				echo '<details open>';
			else
				echo '<details>';
			
            $cat_id = $category->term_id;
            echo '<summary>' .$category->name. '</summary>';
            $sub = get_terms('category', array('orderby' => 'name', 'order' => 'ASC', 'child_of' => $cat_id));
            $haschilds = count($sub) > 0;
            $this->renderSubCat($cat_id);
            if ($haschilds) {
                foreach ($sub as $subcat) {
					if ($open === true)
						echo '<details open>';
					else
						echo '<details>';
                    echo '<summary>' .$subcat->name. '</summary>';
                    $this->renderSubCat($subcat->term_id);
					echo '</details>';
                }
            }
            echo '</details>';
        }
        return ob_get_clean();
    }
    
    public function Generate($type, $open, $param=null) {
        if ($type === 'year') return $this->ArchiveByYear($open);
        else if ($type === 'category') return $this->ArchiveByCategory($open, $param);
        else return "";
    }
}
?>