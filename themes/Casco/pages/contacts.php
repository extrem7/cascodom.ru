<? /* Template Name: Контакты */
get_header();
$cities = get_posts([
    'post_type' => 'city',
    'posts_per_page' => -1
]);
?>
    <main role="main" class="content">
        <div class="box-title">
            <div class="container">
                <div class="page-title"><? the_title() ?></div>
            </div>
        </div>
        <div class="container">
            <div class="row about-page">
                <div class="col-xl-6 col-lg-6 col-md-6 main-indent">
                    <div class="title medium-size extra-bold second-indent">Список городов</div>
                    <div class="about-content">
                        <ul class="list-town">
                            <? foreach ($cities as $city): ?>
                                <li data-town="<?= $city->post_name ?>"><?= $city->post_title ?></li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                    <div class="mt-5 mb-5">
                        <a href="<? bloginfo('url') ?>" class="button btn-yellow">рассчитать полис</a>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 text-center">
                    <div class="address-info d-flex h-100 align-items-center justify-content-center">
                        <? foreach ($cities as $city):
                            $active = $Casco->city == $city ? ' class="active" ' : 'style="display: none"';
                            ?>
                            <div data-town="<?= $city->post_name ?>" <?= $active ?>>
                                <? if (get_field('адрес', $city)): ?>
                                    <address>
                                        <div class="title mb-1">Адрес:</div>
                                        <div><? the_field('адрес', $city) ?></div>
                                    </address>
                                <? endif; ?>
                                <div class="phones">
                                    <div class="title mb-1">телефон:</div>
                                    <? while (have_rows('телефоны', $city)): the_row();
                                        $phone = get_sub_field('телефон');
                                        $whatsapp = get_sub_field('whatsapp');
                                        ?>
                                        <div class="phone-item">
                                            <img class="d-inline-block d-md-none" src="<?= path() ?>assets/img/icons/<?= $whatsapp ? 'whatsapp' : 'phone' ?>.svg"
                                                 width="20" height="20" alt="phone">												 											<img class="d-none d-md-inline-block" src="<?= path() ?>assets/img/icons/<?= $whatsapp ? 'whatsapp' : 'phone-light' ?>.svg"                                                 width="20" height="20" alt="phone">
                                            <? if (!$whatsapp): ?><a href="<?= phoneLink($phone) ?>"><? endif; ?>
                                                <?= $phone ?>
                                                <? if (!$whatsapp): ?></a><? endif; ?>
                                        </div>
                                    <? endwhile; ?>
                                </div>
                                <? if (get_field('почта', $city)): ?>
                                    <div class="emails">
                                        <div class="title">E-MAIL</div>
                                        <div><? the_field('почта', $city) ?></div>
                                    </div>
                                <? endif; ?>
                            </div>
                        <? endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
<? get_footer() ?>