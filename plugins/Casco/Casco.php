<?php

class Casco
{

    public $city;

    public function __construct()

    {

        $this->themeSetup();

        $this->enqueueStyles();

        $this->enqueueScripts();

        $this->customHooks();

        //$this->registerWidgets();

        $this->registerNavMenus();

        add_action('init', function () {

            //$this->registerTaxonomies();

            $this->registerPostTypes();

        });

        add_action('plugins_loaded', function () {

            //$this->ShopSetup();

            $this->ACF();

            $this->GPSI();

            $this->setupCity();

        });


        add_action('wp_ajax_casco', [$this, 'ajaxCasco']);

        add_action('wp_ajax_nopriv_casco', [$this, 'ajaxCasco']);

        add_action('wp_ajax_casco_data', [$this, 'calculatorData']);

        add_action('wp_ajax_nopriv_casco_data', [$this, 'calculatorData']);

    }

    private function themeSetup()

    {

        add_theme_support('post-thumbnails');

        add_theme_support('menus');

        add_theme_support('widgets');

        show_admin_bar(false);

        add_option('orders', 0);
    }

    private function enqueueStyles()

    {

        add_action('wp_print_styles', function () {

            wp_register_style('main', get_template_directory_uri() . '/assets/css/main.css');

            wp_enqueue_style('main');

        });

        add_action('admin_enqueue_scripts', function () {

            wp_enqueue_style('admin-styles', get_template_directory_uri() . '/assets/css/admin.css');

        });

    }

    private function enqueueScripts()

    {

        add_action('wp_enqueue_scripts', function () {

            wp_deregister_script('jquery');

            wp_register_script('jquery', path() . 'assets/node_modules/jquery/dist/jquery.min.js');

            wp_enqueue_script('jquery');

            wp_register_script('popper', path() . 'assets/node_modules/popper.js/dist/umd/popper.min.js');

            wp_enqueue_script('popper');

            wp_register_script('bootstrap', path() . 'assets/node_modules/bootstrap/dist/js/bootstrap.min.js');

            wp_enqueue_script('bootstrap');

            wp_register_script('fancybox', path() . 'assets/node_modules/@fancyapps/fancybox/dist/jquery.fancybox.js');

            wp_enqueue_script('fancybox');

            wp_register_script('vue', path() . 'assets/node_modules/vue/dist/vue.min.js');

            wp_enqueue_script('vue');

            wp_register_script('vue-slider-component', path() . 'assets/node_modules/vue-slider-component/dist/index.js');

            wp_enqueue_script('vue-slider-component');

            wp_register_script('axios', path() . 'assets/node_modules/axios/dist/axios.min.js');

            wp_enqueue_script('axios');

            wp_register_script('mask', path() . 'assets/node_modules/jquery.inputmask/dist/jquery.inputmask.bundle.js');

            wp_enqueue_script('mask');

            wp_register_script('main', path() . 'assets/js/main.js');

            wp_enqueue_script('main');

            if (is_front_page() || is_single()) {

                wp_register_script('calculator', path() . 'assets/js/calculator.js');

                wp_enqueue_script('calculator');

            }

        });

    }

    private function customHooks()

    {

        add_action('admin_menu', function () {

            remove_menu_page('edit-comments.php');

        });


        //add_image_size('', 0, 0, ['center', 'center']);

        //add_filter('wpcf7_autop_or_not', '__return_false');

        add_filter('body_class', function ($classes) {

            if (get_page_template_slug() == 'pages/contacts.php') $classes[] = 'decor-content';

            if (is_front_page() || is_single()) $classes[] = 'page-home';

            return $classes;

        });


        add_filter('post_type_link', function ($post_link, $post) {

            if ('car' === $post->post_type && 'publish' === $post->post_status) {

                $post_link = str_replace('/' . $post->post_type . '/', '/', $post_link);

            }

            return $post_link;

        }, 10, 2);


        add_action('pre_get_posts', function ($query) {

            // Bail if this is not the main query.

            if (!$query->is_main_query()) {

                return;

            }

            // Bail if this query doesn't match our very specific rewrite rule.

            if (!isset($query->query['page']) || 2 !== count($query->query)) {

                return;

            }

            // Bail if we're not querying based on the post name.

            if (empty($query->query['name'])) {

                return;

            }

            // Add CPT to the list of post types WP will include when it queries based on the post name.

            $query->set('post_type', array('post', 'page', 'car'));

        });


        add_action('wp_head', function () {

            if (is_front_page()) {

                global $post;

                if (isset($_COOKIE['car'])) {

                    $post = get_post($_COOKIE['car']);

                    setup_postdata($post);

                } else {

                    $post = get_field('калькулятор', 'option')['автомобиль'];

                    setup_postdata($post);

                }

            }

        });


        add_action('template_redirect', function () {

            if (is_single()) {

                setcookie('car', get_the_ID(), time() + (10 * 365 * 24 * 60 * 60), '/');

            }

        });

    }

    private function registerNavMenus()

    {

        add_action('after_setup_theme', function () {

            register_nav_menus(array(

                'header_menu' => 'Меню в шапке',

            ));

        });


        add_filter('nav_menu_link_attributes', function ($atts, $item, $args) {

            $atts['itemprop'] = 'url';

            return $atts;

        }, 10, 3);


        if (!file_exists(plugin_dir_path(__FILE__) . 'includes/wp-bootstrap-navwalker.php')) {

            // return new WP_Error('wp-bootstrap-navwalker-missing', __('It appears the wp-bootstrap-navwalker.php file may be missing.', 'wp-bootstrap-navwalker'));

        } else {

            //  require_once plugin_dir_path(__FILE__) . 'includes/wp-bootstrap-navwalker.php';

        }


    }

    private function registerPostTypes()
    {
        register_post_type('car', [

            'label' => null,

            'labels' => [

                'name' => 'Автомобиль', // основное название для типа записи

                'singular_name' => 'Автомобиль', // название для одной записи этого типа

                'add_new' => 'Добавить автомобиль', // для добавления новой записи

                'add_new_item' => 'Добавление автомобиля', // заголовка у вновь создаваемой записи в админ-панели.

                'edit_item' => 'Редактирование автомобиля', // для редактирования типа записи

                'new_item' => '', // текст новой записи

                'view_item' => 'Смотреть автомобиль', // для просмотра записи этого типа.

                'search_items' => 'Искать автомобиль', // для поиска по этим типам записи

                'not_found' => 'Не найдено', // если в результате поиска ничего не было найдено

                'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине

                'menu_name' => 'Автомобили', // название меню

            ],

            'public' => true,

            'menu_position' => 5,

            'menu_icon' => 'dashicons-admin-network',

            'supports' => array('title', 'editor', 'custom-fields', 'thumbnail'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'

            'has_archive' => false,

            // 'rewrite' => ['slug' => '/'],

        ]);
        register_post_type('city', [

            'label' => null,

            'labels' => [

                'name' => 'Город', // основное название для типа записи

                'singular_name' => 'Город', // название для одной записи этого типа

                'add_new' => 'Добавить город', // для добавления новой записи

                'add_new_item' => 'Добавление города', // заголовка у вновь создаваемой записи в админ-панели.

                'edit_item' => 'Редактирование города', // для редактирования типа записи

                'new_item' => '', // текст новой записи

                'view_item' => 'Смотреть город', // для просмотра записи этого типа.

                'search_items' => 'Искать город', // для поиска по этим типам записи

                'not_found' => 'Не найдено', // если в результате поиска ничего не было найдено

                'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине

                'menu_name' => 'Города', // название меню

            ],

            'public' => true,

            'publicly_queryable' => false,

            'menu_position' => 6,

            'menu_icon' => 'dashicons-admin-multisite',

            'supports' => array('title', 'custom-fields',), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'

            'has_archive' => false,

        ]);

    }

    private function ACF()

    {

        if (function_exists('acf_add_options_page')) {

            $main = acf_add_options_page([

                'page_title' => 'Настройки темы',

                'menu_title' => 'Настройки темы',

                'menu_slug' => 'theme-general-settings',

                'capability' => 'edit_posts',

                'redirect' => false,

                'position' => 2,

                'icon_url' => 'dashicons-admin-customizer',

            ]);

        }

    }

    private function GPSI()

    {

        require_once "includes/GPSI.php";

    }

    public function setupCity()

    {


        $city_name = null;

        if (isset($_POST["city-name"]) && !empty($_POST["city-name"])) {

            setcookie('city', $_POST["city-name"], time() + (10 * 365 * 24 * 60 * 60), '/');
            $city_name = $_POST["city-name"];

        } else if (isset($_COOKIE['city']) && !empty($_COOKIE['city'])) {

            $city_name = $_COOKIE['city'];

        } else {


            $client = @$_SERVER['HTTP_CLIENT_IP'];

            $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];

            $remote = @$_SERVER['REMOTE_ADDR'];

            $result = array('country' => '', 'city' => '');


            if (filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;

            elseif (filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;

            else $ip = $remote;


            ///$ip = '194.44.36.117';


            $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));


            if ($ip_data && $ip_data->geoplugin_countryName != null) {

                $result = $ip_data->geoplugin_city;

            }


            $city_name = $result;

            //   pre($city_name);

        }


        $city = get_posts([

            'post_type' => 'city',

            'post_per_page' => 1,

            'meta_key' => 'слаг',

            'meta_value' => $city_name

        ]);

        if (!empty($city)) {

            $this->city = $city[0];

        } else {

            $this->city = get_field('город_по_умолчанию', 'option');

        }

    }

    public function breadcrumb()

    {

        //  require_once "includes/breadcrumb.php";

        //breadcrumbs();

    }

    public function calculatorData()

    {

        global $post;
        $setting = get_field('калькулятор', 'option');

        $car = get_field('название_из_таблицы', $setting['автомобиль']->ID);
        $price = $setting['цена'];
        $experience = $setting['стаж'];
        $age = $setting['возраст'];
        $carYear = $setting['год'];
        $newCar = 0;
        $regionalFactor = get_field('региональный_коэффициент', $this->city);

        $q4 = [];
        if ($this->city == get_field('город_по_умолчанию', 'option')) {

            $q4[] = $setting['кфб_москвы'];

        } else {

            $q4[] = $setting['кфб_другие'];

        }
        $q4[] = $setting['кфб'];

        $q3 = [];
        $q3Table = $setting['коэффициент_возраста']['body'];

        for ($i = 1; $i <= count($q3Table) - 1; $i++) {

            for ($j = 1; $j <= count($q3Table[0]) - 1; $j++) {

                $q3[$i - 1][$j - 1] = $q3Table[$i][$j]['c'];

            }

        }

        $ages = [];
        for ($i = 1; $i <= count($q3Table[0]) - 1; $i++) {

            $value = $q3Table[0][$i]['c'];

            if (strpos($value, ',')) $value = explode(',', $value);

            $ages[$i - 1] = $value;

        }

        $experiences = [];
        for ($i = 1; $i <= count($q3Table) - 1; $i++) {

            $value = $q3Table[$i][0]['c'];

            if (strpos($value, ',')) $value = explode(',', $value);

            $experiences[$i - 1] = $value;

        }

        $q5 = [];
        $q5Table = $setting['коэффициент_франшизы']['body'];
        for ($i = 0; $i <= count($q5Table[0]) - 1; $i++) {

            $q5[] = [$q5Table[0][$i]['c'], $q5Table[1][$i]['c']];

        }

        $cars = [];
        $carsTable = $setting['автомобили']['body'];

        foreach ($carsTable as $tr) {

            $cars[$tr[0]['c']] = [];

            for ($i = 1; $i < count($tr) - 1; $i++) {

                $cars[$tr[0]['c']][] = $tr[$i]['c'];

            }

        }

        $tables = ['q3' => $q3, 'ages' => $ages, 'experiences' => $experiences, 'q5' => $q5, 'cars' => $cars];

        if (get_field('название_из_таблицы')) $car = get_field('название_из_таблицы');
        if (get_field('цена')) $price = get_field('цена');
        if (get_field('стаж')) $experience = get_field('стаж');
        if (get_field('возраст')) $age = get_field('возраст');
        if (get_field('новый')) {

            $newCar = get_field('новый');

            $carYear = 'новый';

        } else if (get_field('год_выпуска')) $carYear = get_field('год_выпуска');

        $prices = [];
        if (get_field('новый')) {
            $prices['min'] = get_field('цена') * $setting['цена_минимальная'];
            $prices['max'] = get_field('цена') * $setting['цена_максимальная'];
        } else {
            $new = get_posts([
                'post_type' => 'car',
                'posts_per_page' => 1,
                'meta_query' => [
                    [
                        'key' => 'название_из_таблицы',
                        'value' => get_field('название_из_таблицы')
                    ],
                    [
                        'key' => 'новый',
                        'value' => 1
                    ]
                ]
            ])[0];
            $prices['min'] = get_field('цена', $new) * $setting['цена_минимальная'];
            $prices['max'] = get_field('цена', $new) * $setting['цена_максимальная'];
        }

        $calculator = [
            'name' => get_the_title(),
            'years' => [

                'min' => $setting['минимальный_год'],

                'max' => $setting['максимальный_год']

            ],
            'year' => $setting['год'],
            'newCar' => $newCar,
            'prices' => $prices,
            'price' => $price,
            'car' => $car,
            'cars' => $cars,
            'experience' => [

                'min' => 0,

                'max' => 80

            ],
            'currentExperience' => $experience,
            'age' => [

                'min' => 18,

                'max' => 100

            ],
            'currentAge' => $age,
            'regionalFactor' => $regionalFactor,
            'ages' => $ages,
            'experiences' => $experiences,
            'carYear' => $carYear,
            'q3' => $q3,
            'q4' => $q4,
            'q5' => $q5,
            'q6' => $setting['легкое_каско'],
            'q7' => $setting['скидка-1'],
            'q8' => $setting['скидка-2']
        ];
       // header('Content-Type: application/json');
       return json_encode($calculator, JSON_NUMERIC_CHECK);
      //  exit;

    }

    public function ajaxCasco()
    {
        date_default_timezone_set('Europe/Moscow');

        $headers = "From: Casco <admin@" . $_SERVER['SERVER_NAME'] . ">\r\n";
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-Type: text/html; charset=utf-8' . "\r\n";

        $subject = 'Заявка на КАСКО № ' . get_option('orders') . ' (autofinex)';

        $fields = [];

        $phone = $_POST['phone'];
        $name = null;
        $email = null;
        $casco = null;

        if (isset($_POST['name']) && $_POST['name']) {

            $name = $_POST['name'];

        }
        if (isset($_POST['email']) && $_POST['email']) {

            $email = $_POST['email'];

        }
        if (isset($_POST['casco']) && $_POST['casco']) {
            mb_parse_str($_POST['casco'], $casco);
        }

        $fields['Источник'] = 'AutoFinEx';
        $fields['Номер заявки'] = get_option('orders');
        $fields['Дата заполнения'] = date('d.m.Y');
        $fields['Время заполнения'] = date('G:i');
        $fields['Согласие на обработку ПД'] = $_POST['subject'] ? 'Заявка не завершена' : 'Получено';
        $fields['Имя клиента'] = $name ? $name : '-';
        $fields['Телефон клиента'] = $phone ? $phone : '-';
        $fields['Email клиента'] = $email ? $email : '-';
        if (!empty($casco)) {
            $fields['Марка, модель автомобиля'] = $casco['car'] ? $casco['car'] : '-';
            $fields['Цена автомобиля'] = $casco['price'] ? $casco['price'] : '-';
            $fields['Возраст автомобиля'] = $casco['car_year'] ? $casco['car_year'] : '-';
            $fields['Возраст водителя'] = $casco['age'] ? $casco['age'] : '-';
            $fields['Стаж водителя'] = $casco['experience'] ? $casco['experience'] : '-';
            $fields['Выбранный план'] = $casco['plan'] ? $casco['plan'] : '-';
        }

        $message = "<html><head></head><body><table border=\"1\" cellpadding=\"7\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">";

        foreach ($fields as $key => $field) {
            $message .= "<tr><td>$key</td><td>$field</td></tr>";
        }
        $message .= "</table><br><p>Данное сообщение сгенерировано автоматически. Пожалуйста, не отвечайте на него.</p>";
        $message .= "</body></html>";

        if (mail(get_field('почта_заявки', $this->city), $subject, $message, $headers)) {
            update_option('orders', get_option('orders') + 1);
        }

    }

    private function registerWidgets()

    {

        add_action('widgets_init', function () {

            register_sidebar([

                'name' => "Правая боковая панель сайта",

                'id' => 'right-sidebar',

                'description' => 'Эти виджеты будут показаны в правой колонке сайта',

                'before_title' => '<h1>',

                'after_title' => '</h1>'

            ]);

        });

    }

    private function registerTaxonomies()

    {

        register_taxonomy('gallery_cat', ['room'], [

            'label' => '', // определяется параметром $labels->name

            'labels' => [

                'name' => 'Категории фото',

                'singular_name' => 'Категории фото',

                'search_items' => 'Искать Категорию фото',

                'all_items' => 'Новая Категория фото',

                'view_item ' => 'Смотреть Категорию фото',

                'parent_item' => 'Родитель Категории фото',

                'parent_item_colon' => 'Родитель Категории фото:',

                'edit_item' => 'Редактировать Категорию фото',

                'update_item' => 'Обновить Категорию фото',

                'add_new_item' => 'Добавить новую Категорию фото',

                'new_item_name' => 'Категории фото',

                'menu_name' => 'Категории фото',

            ],

            'public' => true,

            'meta_box_cb' => false,

        ]);


    }
}