<? get_header(); ?>
<? the_title() ?>
<?= apply_filters('the_content', wpautop(get_post_field('post_content', $id), true)); ?>
<? get_footer(); ?>