<? /* Template Name: Как работает */
get_header(); ?>
    <main role="main" class="content">

        <div class="box-title">

            <div class="container">

                <div class="page-title"><? the_title() ?></div>

            </div>

        </div>

        <div class="container main-indent">

            <div class="row how-it-work second-indent">
                <?
                $i = 1;
                while (have_rows('шаги')): the_row() ?>
                    <div class="col-lg-4 col-md-6">

                        <div class="how-it-work-item">

                            <img <? repeater_image('иконка') ?>>

                            <div class="how-it-title"><? the_sub_field('заголовок') ?></div>

                            <p><? the_sub_field('текст') ?></p>

                        </div>
                        <? if (count(get_field('шаги')) <= $i): ?>
                            <div class="pay-method">

                                <div class="text-uppercase mb-2">доступные способы оплаты:</div>
                                <? while (have_rows('способы_оплаты')): the_row() ?>
                                    <div class="d-flex align-items-center">
                                        <img width="18" <? repeater_image('иконка') ?>
                                             class="mr-1"><? the_sub_field('название') ?>
                                    </div>
                                <? endwhile; ?>
                            </div>
                        <? endif; ?>
                    </div>
                    <?
                    $i++;
                endwhile; ?>
            </div>

            <div class="text-center mt-2">

                <a href="<? bloginfo('url') ?>" class="button btn-yellow">рассчитать полис</a>

            </div>

        </div>

    </main>
<? get_footer() ?>