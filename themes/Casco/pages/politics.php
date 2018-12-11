<? /* Template Name: Политика */
get_header(); ?>
    <main role="main" class="content">

        <div class="box-title">

            <div class="container">

                <div class="page-title"><?= get_the_title(13) ?></div>

            </div>

        </div>

        <div class="container main-indent">

            <div class="title medium-size extra-bold text-center"><? the_title() ?></div>

            <div class="mt-3 mb-2">

                <?= apply_filters('the_content', wpautop(get_post_field('post_content', $id), true)); ?>

            </div>

        </div>

    </main>
<? get_footer() ?>