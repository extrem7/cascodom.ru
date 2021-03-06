<!doctype html>
<html lang="ru">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="google-site-verification" content="j6xnvPA-dGeYlSVPTq3C3Z8WDXQMqGVY6B4sg6koWyc"/>
    <!--CSS -->
    <? wp_head() ?>

    <title><?= wp_get_document_title() ?></title>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-128883883-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-128883883-1');
	  setTimeout("ga('send', 'event', '15 seconds', 'read')",15000)
	  gtag('set', {'user_id': 'USER_ID'}); // Задание идентификатора пользователя с помощью параметра user_id (текущий пользователь).
	</script>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function () {
                try {
                    w.yaCounter51034859 = new Ya.Metrika2({
                        id: 51034859,
                        clickmap: true,
                        trackLinks: true,
                        accurateTrackBounce: true
                    });
                } catch (e) {
                }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () {
                    n.parentNode.insertBefore(s, n);
                };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/tag.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else {
                f();
            }
        })(document, window, "yandex_metrika_callbacks2");
    </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/51034859" style="position:absolute; left:-9999px;" alt=""/></div>
    </noscript>
    <!-- /Yandex.Metrika counter -->
</head>
<body <? body_class() ?>>
<style>

    @-webkit-keyframes rotate {

        100% {

            -webkit-transform: rotate3d(0, 0, 1, 360deg);

            transform: rotate3d(0, 0, 1, 360deg);

        }

    }

    @keyframes rotate {

        100% {

            -webkit-transform: rotate3d(0, 0, 1, 360deg);

            transform: rotate3d(0, 0, 1, 360deg);

        }

    }

    .preloader-wrapper {

        display: flex;

        flex-flow: column wrap;

        justify-content: center;

        align-items: center;

        width: 100%;

        min-height: 100vh;

        background-color: #33333a;

        z-index: 100500;

        position: fixed;

        top: 0

    }

    .preloader-wrapper svg {

        -webkit-transform: rotate3d(0, 0, 1, -90deg);

        transform: rotate3d(0, 0, 1, -90deg)

    }

    .preloader-wrapper svg circle {

        fill: none;

        stroke: #444;

        stroke-width: 1px;

        will-change: transform;

        -webkit-transform-origin: center center;

        transform-origin: center center;

        -webkit-animation-name: rotate;

        animation-name: rotate;

        -webkit-animation-timing-function: linear;

        animation-timing-function: linear;

        -webkit-animation-iteration-count: infinite;

        animation-iteration-count: infinite

    }

    .preloader-wrapper svg .inner {

        stroke: #b2d35e;

        stroke-dasharray: 200.96;

        stroke-dashoffset: 160.96;

        -webkit-animation-delay: 200ms;

        animation-delay: 200ms;

        -webkit-animation-duration: 1s;

        animation-duration: 1s

    }

    .preloader-wrapper svg .middle {

        stroke: #ffca00;

        stroke-dasharray: 238.64;

        stroke-dashoffset: 178.64;

        -webkit-animation-delay: 100ms;

        animation-delay: 100ms;

        -webkit-animation-duration: 1.5s;

        animation-duration: 1.5s

    }

    .preloader-wrapper svg .outer {

        stroke: #95c41e;

        stroke-dasharray: 276.32;

        stroke-dashoffset: 176.32;

        -webkit-animation-delay: 300ms;

        animation-delay: 300ms;

        -webkit-animation-duration: 2s;

        animation-duration: 2s

    }

    .modal-vertical-center {

        text-align: center;

        padding: 0 !important

    }

    .modal-vertical-center:before {

        content: '';

        display: inline-block;

        height: 100%;

        vertical-align: middle;

        margin-right: -4px

    }

    .modal-vertical-center .modal-dialog {

        display: inline-block;

        text-align: left;

        vertical-align: middle

    }

</style>
<? if (is_front_page() || is_single()): ?>
<div id="app"><? endif; ?>
    <div class="preloader-wrapper">

        <svg viewBox="0 0 120 120" width="120px" height="120px">

            <circle class="inner" cx="60" cy="60" r="32"/>

            <circle class="middle" cx="60" cy="60" r="38"/>

            <circle class="outer" cx="60" cy="60" r="44"/>

        </svg>

    </div>
    <!--Header-->
    <div class="header">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="logo">
                <a href="<? bloginfo('url') ?>"><img <? the_image('лого', 'option') ?>></a>
                <span><? the_field('лого_текст', 'option') ?></span>
            </div>
            <nav class="menu-container" role="navigation">
                <div class="box-contact mobile-contact">
                    <?
                    global $Casco;
                    $city = $Casco->city;
                    while (have_rows('телефоны', $city)): the_row();
                        $phone = get_sub_field('телефон');
                        $whatsapp = get_sub_field('whatsapp');
                        if (!get_sub_field('отображать_в_хедере')) {

                            continue;

                        }
                        ?>
                        <div>

                            <img src="<?= path() ?>assets/img/icons/<?= $whatsapp ? 'whatsapp' : 'phone' ?>.svg"

                                 width="20" height="20" alt="phone">

                            <? if (!$whatsapp): ?><a href="<?= phoneLink($phone) ?>"><? endif; ?>

                                <?= $phone ?>

                                <? if (!$whatsapp): ?></a><? endif; ?>

                        </div>
                    <? endwhile; ?>
                </div>
                <? wp_nav_menu(array(
                    'location' => 'header_menu',
                    'container' => null,
                    'items_wrap' => '<ul class="menu">%3$s</ul>',
                )); ?>
            </nav>
            <div class="d-flex flex-column align-items-end">
                <div class="box-contact">
                    <?
                    global $Casco;
                    $city = $Casco->city;
                    while (have_rows('телефоны', $city)): the_row();
                        $phone = get_sub_field('телефон');
                        $whatsapp = get_sub_field('whatsapp');
                        if (!get_sub_field('отображать_в_хедере')) {

                            continue;

                        }
                        ?>
                        <div>

                            <img src="<?= path() ?>assets/img/icons/<?= $whatsapp ? 'whatsapp' : 'phone' ?>.svg"

                                 width="20" height="20" alt="phone">

                            <? if (!$whatsapp): ?><a href="<?= phoneLink($phone) ?>"><? endif; ?>

                                <?= $phone ?>

                                <? if (!$whatsapp): ?></a><? endif; ?>

                        </div>
                    <? endwhile; ?>
                </div>
                <div data-toggle="modal" data-target="#geoapi"
                     class="geoapi-link d-inline-block"><?= $city->post_title ?></div>
            </div>
            <button id="mobile-menu" class="mobile-btn"><span></span><span></span><span></span></button>
        </div>
    </div>
    <!--/Header-->
