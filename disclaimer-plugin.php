<?php
/*
* Plugin Name: Custom-Category-Banner
* Description: 在特定类别的分页上方添加自定义横幅。
* Plugin URI:        https://ooize.com
* @package custom_category_banner
*Version: 1.0
* Author: Oak
 * Author URI:        https://ooize.com
*/

// 特定分类链接
function add_custom_div_above_pagination() {
    $category_slug_or_id = get_option('custom_category_banner_category'); // 获取设置的分类别名或ID

    if (is_category($category_slug_or_id)) {
        $category_settings = get_option('custom_category_banner_settings');
        $image_url = isset($category_settings['image_url']) ? esc_url($category_settings['image_url']) : '';
        $link_url = isset($category_settings['link_url']) ? esc_url($category_settings['link_url']) : '';
        $link_text = isset($category_settings['link_text']) ? esc_html($category_settings['link_text']) : '';

        ?>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                var customDiv = document.createElement('div');
                customDiv.className = 'custom-div';
                customDiv.innerHTML = `
                    <div class="custom-div-content">
                        <img src="<?php echo $image_url; ?>" alt="Custom Image">
                        <div class="text-and-link">
                            <a href="<?php echo $link_url; ?>" target="_blank"><?php echo $link_text; ?></a>
                        </div>
                    </div>
                `;

                var paginationElement = document.querySelector('.ct-pagination');
                if (paginationElement) {
                    paginationElement.parentNode.insertBefore(customDiv, paginationElement);
                }
            });
        </script>
        <?php
    }
}

add_action('wp_footer', 'add_custom_div_above_pagination');

// 添加插件设置页面
function custom_category_banner_menu() {
    add_options_page('Custom Category Banner Settings', 'Category Banner Settings', 'manage_options', 'custom_category_banner', 'custom_category_banner_settings_page');
}
add_action('admin_menu', 'custom_category_banner_menu');

// 插件设置页面内容
function custom_category_banner_settings_page() {
    ?>
    <div class="wrap">
        <h2>Custom Category Banner Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('custom_category_banner_settings_group');
            do_settings_sections('custom_category_banner_settings_page');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// 注册插件设置
function custom_category_banner_settings() {
    register_setting('custom_category_banner_settings_group', 'custom_category_banner_settings');
    register_setting('custom_category_banner_settings_group', 'custom_category_banner_category'); // 添加这行，修复了设置名称的问题
    add_settings_section('custom_category_banner_settings_section', 'Category Banner Settings', '', 'custom_category_banner_settings_page');
    add_settings_field('category', 'Category', 'custom_category_banner_category_callback', 'custom_category_banner_settings_page', 'custom_category_banner_settings_section');
    add_settings_field('image_url', 'Image URL', 'custom_category_banner_image_url_callback', 'custom_category_banner_settings_page', 'custom_category_banner_settings_section');
    add_settings_field('link_url', 'Link URL', 'custom_category_banner_link_url_callback', 'custom_category_banner_settings_page', 'custom_category_banner_settings_section');
    add_settings_field('link_text', 'Link Text', 'custom_category_banner_link_text_callback', 'custom_category_banner_settings_page', 'custom_category_banner_settings_section');
}

add_action('admin_init', 'custom_category_banner_settings');

// 设置页面字段回调函数
function custom_category_banner_category_callback() {
    $options = get_option('custom_category_banner_category');
    ?>
    <input type="text" name="custom_category_banner_category" value="<?php echo esc_attr($options); ?>" />
    <?php
}

function custom_category_banner_image_url_callback() {
    $options = get_option('custom_category_banner_settings');
    ?>
    <input type="text" name="custom_category_banner_settings[image_url]" value="<?php echo esc_url($options['image_url']); ?>" />
    <?php
}

function custom_category_banner_link_url_callback() {
    $options = get_option('custom_category_banner_settings');
    ?>
    <input type="text" name="custom_category_banner_settings[link_url]" value="<?php echo esc_url($options['link_url']); ?>" />
    <?php
}

function custom_category_banner_link_text_callback() {
    $options = get_option('custom_category_banner_settings');
    ?>
    <input type="text" name="custom_category_banner_settings[link_text]" value="<?php echo esc_html($options['link_text']); ?>" />
    <?php
}
?>
