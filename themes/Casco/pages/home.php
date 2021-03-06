<? /* Template Name: Главная */
get_header();
global $post, $Casco;
?>
    <main role="main" class="content">
        <div class="container main-indent">
            <div class="row home-page">
                <div class="col-lg-5">
                    <div class="vue-app">
                        <div class="casco-list">
                            <div data-id="1" class="casco-item d-flex align-items-center justify-content-between"
                                 data-toggle="modal" data-target="#cascocompare">
                                <div class="casco-name">Для автокредита</div>
                                <div class="casco-price">{{CascoMinimalRender | priceFormat }} <span>₽</span></div>
                            </div>
                            <div data-id="2" class="casco-item d-flex align-items-center justify-content-between"
                                 data-toggle="modal" data-target="#cascocompare">
                                <div class="casco-name">с франшизой</div>
                                <div class="casco-price">{{CascoOptimalRender | priceFormat }} <span>₽</span></div>
                            </div>
                            <div data-id="3" class="casco-item d-flex align-items-center justify-content-between"
                                 data-toggle="modal" data-target="#cascocompare">
                                <div class="casco-name">без франшизы</div>
                                <div class="casco-price">{{CascoFullRender | priceFormat }} <span>₽</span></div>
                            </div>
                        </div>
                        <div class="calculator">
                            <div class="form-group">
                                <div class="title">цена вашего авто</div>
                                <vue-slider class="price-slider" v-model="price" ref="slider2" :min="Data.prices.min"
                                            :max="Data.prices.max" interval="1000">
                                    <div class="diy-tooltip" slot="tooltip" slot-scope="{ value }">{{value | price}}
                                    </div>
                                </vue-slider>
                            </div>
                            <div class="form-group">
                                <div class="title">возраст вашего авто</div>
                                <vue-slider class="year-slider" tooltip="false" v-model="carYear" ref="slider"
                                            piecewise="true" piecewise-label="true" :data="yearsCar">
                                </vue-slider>
                                <div class="old-car"><span>б/y</span></div>
                            </div>
                            <div class="form-group d-flex justify-content-between align-items-center select-group">
                                <div class="d-flex align-items-center mt-3">
                                    <div class="title">возраст водителя</div>
                                    <input type="number" v-model.number="ageRaw" id="age-raw" :min="Data.age.min"
                                           :min="Data.age.max"
                                           step="1" class="custom-select control-form">
                                </div>
                                <div class="d-flex align-items-center mt-3">
                                    <div class="title">стаж водителя</div>
                                    <input type="number" v-model.number="experienceRaw" :min="Data.experience.min"
                                           :min="Data.experience.max"
                                           step="1" class="custom-select control-form">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="title">Ваше каско</div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="cascoPlan-1" v-model="cascoPlan" value="Для автокредита"
                                           class="custom-control-input">
                                    <label class="custom-control-label" for="cascoPlan-1">Для автокредита</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="cascoPlan-2" v-model="cascoPlan" value="C франшизой"
                                           class="custom-control-input">
                                    <label class="custom-control-label" for="cascoPlan-2">C франшизой</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="cascoPlan-3" v-model="cascoPlan" value="Без франшизы"
                                           class="custom-control-input">
                                    <label class="custom-control-label" for="cascoPlan-3">Без франшизы</label>
                                </div>
                            </div>
                            <br>
                            <div class="form-group text-center text-md-left">
                                <button class="button btn-yellow" data-backdrop="static" data-keyboard="false"
                                        data-toggle="modal" data-target="#order">получить полис
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="car-selected">
                        <div class="car-title"><? the_field('главная_заголовок', 'option') ?></div>
                        <div class="car-model">специально для <span><? the_title() ?></span></div>
                        <div class="car-img">
                            <? $img = wp_prepare_attachment_for_js(get_post_thumbnail_id()) ?>
                            <img src="<?= $img['url'] ?>" class="img-fluid" alt="<?= $img['alt'] ?>">
                        </div>
                        <div class="car-link">
                            <a class="custom-link" href="<?= get_permalink(107) ?>">у меня другой автомобиль
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M21 11H6.83l3.58-3.59L9 6l-6 6 6 6 1.41-1.41L6.83 13H21z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="modal fade" id="order">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="post" id="cascoForm" class="modal-content" @submit="sendForm" onsubmit="gtag('event', 'send', { 'event_category': 'push', 'event_action': 'order', }); yaCounter51034859.reachGoal('order'); return true;">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="#95c41e" width="24" height="24"

                         viewBox="0 0 24 24">


                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>


                        <path d="M0 0h24v24H0z" fill="none"/>


                    </svg>

                </button>

                <div class="order-header">

                    <div class="title medium-size extra-bold text-center mt-md-5 mt-2">Заявка на

                        полис

                    </div>

                    <div class="row wrap-modal align-items-center">

                        <div class="col-md-12 col-lg-6 form-group">

                            <div class="d-flex align-items-center">

                                <label class="label-form">ваше имя</label>

                                <input type="text" name="name" class="control-form"

                                       placeholder="Введите ваше имя">

                            </div>

                        </div>

                        <div class="col-md-12 col-lg-6 form-group">

                            <div class="d-flex align-items-center">

                                <label class="label-form">ваш email</label>

                                <input type="text" name="email" class="control-form"

                                       placeholder="email@domen.ru">

                            </div>

                        </div>

                        <div class="col-md-12 col-lg-6 form-group">

                            <div class="d-flex align-items-center">

                                <label class="label-form">ваш номер</label>

                                <input type="text" name="phone" class="control-form phone"

                                       placeholder="+7 (___) ___-__-__" required>

                            </div>

                        </div>

                        <div class="col-md-12 col-lg-6 form-group">

                            <div class="custom-control custom-checkbox">

                                <input type="checkbox" class="custom-control-input"

                                       id="customCheck1" checked required>

                                <label class="custom-control-label accept" for="customCheck1">Даю <a

                                            href="<? the_permalink(30) ?>" target="_blank">согласие

                                        на обработку моих песональных данных</a></label>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="order-info">

                    <div class="title medium-size extra-bold text-center">условия расчета</div>

                    <div class="row no-gutters wrap-modal">

                        <div class="col-xl-9 col-lg-9">

                            <div class="d-flex align-items-center flex-sm-column-center">

                                <div class="d-flex wrap-casco-info flex-column-sm">

                                    <div class="bold text-uppercase w90px">каско на</div>

                                    <? the_title() ?> ({{newCar|carStatus}})

                                </div>

                                <div class="d-flex flex-column-sm">

                                    <div class="bold text-uppercase w170px">возраст водителя</div>

                                    {{age | yearsRu}}

                                </div>

                            </div>

                            <div class="d-flex align-items-center flex-sm-column-center">

                                <div class="d-flex wrap-casco-info flex-column-sm">

                                    <div class="bold text-uppercase w90px">ценой</div>

                                    {{price | price}} руб.

                                </div>

                                <div class="d-flex flex-column-sm">

                                    <div class="bold text-uppercase w170px">стаж водителя</div>

                                    {{experience | yearsRu}}

                                </div>

                            </div>

                        </div>

                        <div class="col-xl-3 col-lg-3 d-flex align-items-center">

                            <div class="d-flex justify-content-center w-100 flex-column-sm">

                                <div>

                                    <a href="#" data-dismiss="modal"

                                       class="custom-link ml-lg-5 ml-0">изменить

                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"

                                             height="24" viewBox="0 0 24 24">

                                            <path d="M0 0h24v24H0z" fill="none"/>

                                            <path d="M21 11H6.83l3.58-3.59L9 6l-6 6 6 6 1.41-1.41L6.83 13H21z"/>

                                        </svg>

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="text-center">

                        <button class="button btn-yellow">Отправить</button>

                    </div>

                    <div class="separate"></div>

                    <div class="write-us text-center bold text-uppercase">или напишите нам:</div>

                    <div class="d-flex justify-content-center address-block mt-3 flex-sm-column-center">

                        <? $mail = get_field('почта', $Casco->city) ?>

                        <div><a href="mailto:<?= $mail ?>"><?= $mail ?></a></div>

                        <?
                        $phone = null;
                        while (have_rows('телефоны', $Casco->city)) {
                            the_row();
                            if (get_sub_field('обображать_в_модалке')) {
                                $phone = get_sub_field('телефон');
                            } else {
                                break;
                            }
                        }
                        if ($phone): ?>
                            <div><img src="<?= path() ?>assets/img/icons/phone.svg" width="20" height="20" alt="phone"
                                      class="mr-2"><a href="tel:<?= $phone ?>"><?= $phone ?></a></div>
                        <? endif; ?>

                    </div>

                </div>

            </form>

        </div>
    </div>
    <div class="modal fade" id="cascocompare">
        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content modal-compare">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="#95c41e" width="24" height="24"
                         viewBox="0 0 24 24">


                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>


                        <path d="M0 0h24v24H0z" fill="none"/>


                    </svg>

                </button>

                <div class="casco-compare">

                    <div class="row">

                        <?

                        $active = 'active';
                        $cascoArray = [
                            '{{CascoMinimalRender | priceFormat }}',
                            '{{CascoOptimalRender | priceFormat }}',
                            '{{CascoFullRender | priceFormat }}'
                        ];
                        $i = 0;
                        while (have_rows('виды_каско', 'option')):
                            the_row() ?>

                            <div class="col-lg-4 col-md-12 compare-item <?= $active ?>">

                                <div class="compare-header">

                                    <div><? the_sub_field('название') ?></div>

                                    <div class="price"><?= $cascoArray[$i] ?> <span>₽</span></div>

                                </div>

                                <div class="compare-content">

                                    <? while (have_rows('список')):

                                        the_row() ?>

                                        <div class="compare-content-item d-flex align-items-start">

                                            <div class="compare-ind <?= !get_sub_field('иконка') ? 'del' : '' ?>"></div>

                                            <div><? the_sub_field('значение') ?></div>

                                        </div>

                                    <? endwhile; ?>

                                </div>

                            </div>

                            <?

                            $active = '';
                            $i++;
                        endwhile; ?>

                        <div class="pl-3 pt-1 detail"><? the_field('каско_виды_примечание', 'option') ?></div>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <? get_template_part('views/thanks') ?>
<? get_footer() ?>
