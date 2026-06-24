<?php
    $args = array(
        'post_type' => array('graduacao', 'posgraduacao'),
        'posts_per_page' => -1, // Get all posts
    );
    
    $query = new WP_Query($args);

    $cursos_data = array();

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $mneumonico = get_post_meta(get_the_ID(), 'mneumonico', true);
            $permalink = get_permalink();

            if (!empty($mneumonico) && !empty($permalink)) {
                $cursos_data[] = array(
                    'mneumonico' => $mneumonico,
                    'permalink' => $permalink,
                    'slug' => get_post_field('post_name', get_the_ID()),
                    'post_type' => get_post_type(get_the_ID())
                );
            }
        endwhile;
        wp_reset_postdata();
    endif;
    // A variável $cursos_data agora está disponível para uso no template que incluiu este arquivo.
    // Nenhum conteúdo é impresso na tela.
?>
