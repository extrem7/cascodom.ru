<?php

class ThemeWoocommerce
{
    private function ShopSetup()
    {
        add_action('after_setup_theme', function () {
            add_theme_support('woocommerce');
        });
        add_action('init', function () {
            remove_action('wp_footer', array(WC()->structured_data, 'output_structured_data'), 10);
            remove_action('woocommerce_email_order_details', array(WC()->structured_data, 'output_email_structured_data'), 30);
        });
        add_action('wp_enqueue_scripts', function () {
            remove_action('wp_head', array($GLOBALS['woocommerce'], 'generator'));
            if (function_exists('is_woocommerce')) {
                if (!is_woocommerce() && !is_cart() && !is_checkout()) {
                    wp_dequeue_style('woocommerce_frontend_styles');
                    wp_dequeue_style('woocommerce_fancybox_styles');
                    wp_dequeue_style('woocommerce_chosen_styles');
                    wp_dequeue_style('woocommerce_prettyPhoto_css');
                    wp_dequeue_script('wc_price_slider');
                    wp_dequeue_script('wc-single-product');
                    wp_dequeue_script('wc-add-to-cart');
                    wp_dequeue_script('wc-cart-fragments');
                    wp_dequeue_script('wc-checkout');
                    wp_dequeue_script('wc-add-to-cart-variation');
                    wp_dequeue_script('wc-single-product');
                    wp_dequeue_script('wc-cart');
                    wp_dequeue_script('wc-chosen');
                    wp_dequeue_script('woocommerce');
                    wp_dequeue_script('prettyPhoto');
                    wp_dequeue_script('prettyPhoto-init');
                    wp_dequeue_script('jquery-blockui');
                    wp_dequeue_script('jquery-placeholder');
                    wp_dequeue_script('fancybox');
                    wp_dequeue_script('jqueryui');
                }
            }
        }, 99);
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');

        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

        add_filter('woocommerce_add_error', function ($error) {
            if (strpos($error, 'Оплата ') !== false) {
                $error = str_replace("Оплата ", "", $error);
            }
            return $error;
        });
        add_filter('woocommerce_checkout_fields', function ($fields) {
            /*$fields['billing']['billing_address_1']['required'] = false;
            $fields['billing']['billing_country']['required'] = false;
            $fields['billing']['billing_city']['required'] = false;
            $fields['billing']['billing_postcode']['required'] = false;
            $fields['billing']['billing_address_2']['required'] = false;
            $fields['billing']['billing_state']['required'] = false;
            $fields['billing']['billing_email']['required'] = false;
            $fields['order']['order_comments']['type'] = 'text';
            $fields['billing']['billing_postcode']['label'] = 'Квартира';
            $fields['billing']['billing_state']['label'] = 'Корпус';
            //unset($fields['billing']['billing_last_name']);
            //unset($fields['billing']['billing_company']);
            //unset( $fields['billing']['billing_postcode'] );
            //unset( $fields['billing']['billing_state'] );
            //unset( $fields['billing']['billing_email'] );
            //unset($fields['billing']['billing_country']);
            //unset($fields['billing']['billing_address_2']);
            //unset($fields['billing']['billing_state']);*/
            return $fields;
        });
        add_filter('default_checkout_billing_country', function () {
            return 'UA'; // country code
        });

        add_filter('woocommerce_currency_symbol', function ($currency_symbol, $currency) {

            switch ($currency) {
                case 'UAH':
                    $currency_symbol = ' грн.';
                    break;
            }

            return $currency_symbol;
        }, 10, 2);

      //  $this->perPageSorting();
      //  $this->customFields();

        add_action('wp_ajax_ajax_add_to_cart', [$this, 'ajaxAddToCart']);
        add_action('wp_ajax_nopriv_ajax_add_to_cart', [$this, 'ajaxAddToCart']);
    }

    private function perPageSorting()
    {
        if (!isset($_SESSION['perpage'])) {
            $_SESSION['perpage'] = 8;
        }
        if (isset($_POST['perpage'])) {
            $_SESSION['perpage'] = $_POST['perpage'];
            global $paged;
            $paged = 1;
        }
        add_action('pre_get_posts', function ($query) {
            if ($query->is_tax && $query->is_main_query()) {
                $query->set('posts_per_page', $_SESSION['perpage']);
            }
        });
    }

    private function customFields()
    {
        add_action('woocommerce_product_options_pricing', function () {
            woocommerce_wp_text_input([
                'id' => 'field',
                'label' => 'field falbel',
                'class' => 'cfwc-custom-field',
            ]);
        });
        add_action('woocommerce_process_product_meta', function ($post_id) {
            $product = wc_get_product($post_id);
            $fields = ['field'];
            foreach ($fields as $field) {
                $title = isset($_POST[$field]) ? $_POST[$field] : '';
                $product->update_meta_data($field, intval(sanitize_text_field($title)));
                $product->save();
            }
        });
    }

    public function ajaxAddToCart()
    {

        $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
        $response = [];

        if (WC()->cart->add_to_cart($product_id)) {
            do_action('woocommerce_ajax_added_to_cart', $product_id);

            $response['status'] = 'success';


            $response['html'] = wc_add_to_cart_message($product_id, false, true);
            ob_clean();
        } else {
            $response['status'] = 'error';
        }
        echo json_encode($response);
        die();
    }

    public function getHighestCategory($currentCategory)
    {
        if (!in_array($currentCategory->term_id, [$this->stihlID(), $this->vikingID()])) {
            return array_reverse(get_ancestors($currentCategory->term_id, 'product_cat'))[0];
        } else {
            return $currentCategory->term_id;
        }
    }

    public function printAttributes($product)
    {
        $attributes = $product->get_attributes();
        global $additional;

        foreach ($attributes as $attribute):
            $terms = get_terms($attribute->get_name(), ['hide_empty' => false]);
            $sup = '';
            foreach ($terms as $option) {
                if ($option->description) {
                    $additional[] = $option->description;
                    $sup = count($additional) . ')';
                }
            }
            if ($attribute->get_visible()) :
                ?>
                <tr>
                    <td><?= wc_attribute_label($attribute->get_name()) ?><sup> <?= $sup ?></sup></td>
                    <td>
                        <?
                        $values = [];
                        if ($attribute->is_taxonomy()) {
                            $attribute_taxonomy = $attribute->get_taxonomy_object();
                            $attribute_values = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'all'));
                            foreach ($attribute_values as $attribute_value) {
                                $value_name = esc_html($attribute_value->name);
                                if ($attribute_taxonomy->attribute_public) {
                                    $values[] = '<a href="' . esc_url(get_term_link($attribute_value->term_id, $attribute->get_name())) . '" rel="tag">' . $value_name . '</a>';
                                } else {
                                    $values[] = $value_name;
                                }
                            }
                        } else {
                            $values = $attribute->get_options();
                            foreach ($values as &$value) {
                                $value = make_clickable(esc_html($value));
                            }
                        }

                        echo implode(', ', $values) ?>
                    </td>
                </tr>
            <? endif;
        endforeach;
    }

    public function minMaxPrice()
    {
        global $wpdb;
        $category = get_queried_object();

        $categoryQuery = new WP_Query([
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $category->name,
                    'operator' => 'IN'
                ]
            ],
            'meta_query' => [
                [
                    'key' => '_stock_status',
                    'value' => 'instock'
                ]
            ]
        ]);
        $args = $categoryQuery->query_vars;
        $tax_query = isset($args['tax_query']) ? $args['tax_query'] : array();
        $meta_query = isset($args['meta_query']) ? $args['meta_query'] : array();

        if (!is_post_type_archive('product') && !empty($args['taxonomy']) && !empty($args['term'])) {
            $tax_query[] = array(
                'taxonomy' => $args['taxonomy'],
                'terms' => array($args['term']),
                'field' => 'slug',
            );
        }

        foreach ($meta_query + $tax_query as $key => $query) {
            if (!empty($query['price_filter']) || !empty($query['rating_filter'])) {
                unset($meta_query[$key]);
            }
        }

        $meta_query = new WP_Meta_Query($meta_query);
        $tax_query = new WP_Tax_Query($tax_query);

        $meta_query_sql = $meta_query->get_sql('post', $wpdb->posts, 'ID');
        $tax_query_sql = $tax_query->get_sql($wpdb->posts, 'ID');

        $sql = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
        $sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
        $sql .= " 	WHERE {$wpdb->posts}.post_type IN ('" . implode("','", array_map('esc_sql', apply_filters('woocommerce_price_filter_post_type', array('product')))) . "')
			AND {$wpdb->posts}.post_status = 'publish'
			AND price_meta.meta_key IN ('" . implode("','", array_map('esc_sql', apply_filters('woocommerce_price_filter_meta_keys', array('_price')))) . "')
			AND price_meta.meta_value > '' ";
        $sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

        $search = WC_Query::get_main_search_query_sql();
        if ($search) {
            $sql .= ' AND ' . $search;
        }

        return $wpdb->get_row($sql); // WPCS: unprepared SQL ok.
    }

    public function latestProducts($limit)
    {
        $query = new WP_Query([
            'post_type' => 'product',
            'post_per_page' => $limit,
            'post_status' => 'publish',
            'orderby' => 'date',
            'tax_query' => [
                [
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'featured',
                    'operator' => 'IN',
                ]
            ]

        ]);
        return $query;
    }

    public function popularProducts($limit)
    {
        $query = new WP_Query([
            'post_type' => 'product',
            'post_per_page' => $limit,
            'post_status' => 'publish',
            'meta_key' => 'total_sales',
            'orderby' => [
                'meta_value_num' => 'DESC'
            ],
        ]);
        return $query;
    }

    public function saleProducts($limit)
    {
        $query = new WP_Query([
            'post_type' => 'product',
            'post_per_page' => $limit,
            'post_status' => 'publish',
            'orderby' => 'meta_value_num',
            'meta_key' => '_price',
            'order' => 'asc',
            'tax_query', [
                [
                    'taxonomy' => 'product_cat',
                    'terms' => 20,
                    'field' => 'id',
                    'include_children' => true,
                    'operator' => 'IN'
                ]
            ]
        ]);
        return $query;
    }
}