<? get_header(); ?>
<?= wp_get_document_title() ?>
<?php

if (have_posts()) :
    while (have_posts()) : the_post();
        ?>
    <?
    endwhile;
endif;
?>
<? get_footer(); ?>
