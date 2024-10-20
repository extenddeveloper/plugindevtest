<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class TF_Services_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'tf_services_widget';
    }

    public function get_title()
    {
        return esc_html__('TF Services', 'tf-services');
    }

    public function get_icon()
    {
        return 'fa fa-cogs';
    }

    public function get_categories()
    {
        return ['tf-services-category'];
    }

    protected function _register_controls()
    {
        // General content settings
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'tf-services'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Number of Services', 'tf-services'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => esc_html__('Items per Column', 'tf-services'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
            ]
        );

        $this->end_controls_section();

        // Style section for the image
        $this->start_controls_section(
            'image_style_section',
            [
                'label' => esc_html__('Image', 'tf-services'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_width',
            [
                'label' => esc_html__('Width', 'tf-services'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tf-service-thumbnail img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style section for the title
        $this->start_controls_section(
            'title_style_section',
            [
                'label' => esc_html__('Title', 'tf-services'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Typography', 'tf-services'),
                'selector' => '{{WRAPPER}} .tf-service-title a',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'tf-services'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tf-service-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style section for the excerpt
        $this->start_controls_section(
            'excerpt_style_section',
            [
                'label' => esc_html__('Excerpt', 'tf-services'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'label' => esc_html__('Typography', 'tf-services'),
                'selector' => '{{WRAPPER}} .tf-service-excerpt',
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => esc_html__('Color', 'tf-services'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tf-service-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style section for the price and Add to Cart button
        $this->start_controls_section(
            'pricing_style_section',
            [
                'label' => esc_html__('Pricing & Add to Cart', 'tf-services'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => esc_html__('Button Background Color', 'tf-services'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tf-service-pricing .button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Button Text Color', 'tf-services'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tf-service-pricing .button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'label' => esc_html__('Price Typography', 'tf-services'),
                'selector' => '{{WRAPPER}} .tf-service-pricing p',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $args = [
            'post_type'      => 'tfservices',
            'posts_per_page' => $settings['posts_per_page'],
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            echo '<div class="tf-service-list">';
            while ($query->have_posts()) {
                $query->the_post();
                $regular_price = get_post_meta(get_the_ID(), '_tf_service_regular_price', true);
                $sale_price = get_post_meta(get_the_ID(), '_tf_service_sale_price', true);

                echo '<div class="tf-service">';
                echo '<div class="tf-service-thumbnail">';
                if (has_post_thumbnail()) {
                    echo '<a href="' . get_permalink() . '">';
                    the_post_thumbnail('medium');
                    echo '</a>';
                }
                echo '</div>'; // .tf-service-thumbnail

                echo '<div class="tf-service-details">';
                echo '<h2 class="tf-service-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
                echo '<div class="tf-service-excerpt">' . wp_trim_words(get_the_excerpt(), 20, '...') . '</div>';

                echo '<div class="tf-service-pricing">';
                if (!empty($regular_price)) {
                    if ($sale_price) {
                        echo '<p><del>' . wc_price($regular_price) . '</del> <strong>' . wc_price($sale_price) . '</strong></p>';
                    } else {
                        echo '<p>' . wc_price($regular_price) . '</p>';
                    }

                    // Add to Cart Form
                    echo '<form class="cart" action="' . esc_url(wc_get_cart_url()) . '" method="post">';
                    echo '<input type="hidden" name="add-to-cart" value="' . esc_attr(get_the_ID()) . '">';
                    echo '<input type="number" name="quantity" value="1" min="1" class="input-text qty text" size="4" />';
                    echo '<button type="submit" class="button alt">' . __('Add to Cart', 'tf_services') . '</button>';
                    echo '</form>';
                } else {
                    echo '<a href="' . get_permalink() . '" class="tf-service-read-more">' . __('Read More', 'tf_services') . '</a>';
                }
                echo '</div>'; // .tf-service-pricing

                echo '</div>'; // .tf-service-details
                echo '</div>'; // .tf-service
            }
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo esc_html__('No services found.', 'tf-services');
        }
    }
}
