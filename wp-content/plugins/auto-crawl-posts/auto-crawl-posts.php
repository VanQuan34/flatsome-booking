<?php
/*
Plugin Name: Auto Crawl Posts Pro
Description: Crawl bài viết từ API và đăng vào WordPress
Version: 1.0
Author: Quân TV
*/

if (!defined('ABSPATH')) exit;

class AutoCrawlPosts {

    public function __construct() {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'register_settings']);

        add_action('admin_post_run_crawl', [$this, 'handle_manual_crawl']);

        add_action('auto_crawl_cron', [$this, 'run_crawl']);
        add_action('auto_crawl_cron', [$this, 'run_rss_crawl']);

        // Schedule cron
        if (!wp_next_scheduled('auto_crawl_cron')) {
            wp_schedule_event(time(), 'hourly', 'auto_crawl_cron');
        }
    }

    /* =========================
       ADMIN UI
    ==========================*/
    public function menu() {
        add_menu_page(
            'Auto Crawl',
            'Auto Crawl',
            'manage_options',
            'auto-crawl',
            [$this, 'settings_page']
        );
    }

    public function register_settings() {
        register_setting('auto_crawl_group', 'auto_crawl_api_url');
        register_setting('auto_crawl_group', 'auto_crawl_rss_url');
        register_setting('auto_crawl_group', 'auto_crawl_rss_content_selector');
        register_setting('auto_crawl_group', 'auto_crawl_limit');
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1>Auto Crawl Settings</h1>

            <form method="post" action="options.php">
                <?php settings_fields('auto_crawl_group'); ?>
                <?php do_settings_sections('auto_crawl_group'); ?>

                <table class="form-table">
                    <tr>
                        <th>API URL</th>
                        <td>
                            <input type="text" name="auto_crawl_api_url" value="<?php echo esc_attr(get_option('auto_crawl_api_url')); ?>" style="width:100%">
                        </td>
                    </tr>

                    <tr>
                        <th>RSS URL</th>
                        <td>
                            <input type="text" name="auto_crawl_rss_url" value="<?php echo esc_attr(get_option('auto_crawl_rss_url')); ?>" style="width:100%">
                        </td>
                    </tr>

                    <tr>
                        <th>RSS Content Selector</th>
                        <td>
                            <input type="text" name="auto_crawl_rss_content_selector" value="<?php echo esc_attr(get_option('auto_crawl_rss_content_selector')); ?>" style="width:100%" placeholder="Ví dụ: .fck_detail hoặc #article-body">
                            <p class="description">Nhập CSS Selector của vùng chứa nội dung bài viết trên trang gốc (Ví dụ: <code>.fck_detail</code> cho VnExpress).</p>
                        </td>
                    </tr>

                    <tr>
                        <th>Limit Posts / Run</th>
                        <td>
                            <input type="number" name="auto_crawl_limit" value="<?php echo esc_attr(get_option('auto_crawl_limit', 5)); ?>">
                        </td>
                    </tr>
                </table>

                <?php submit_button(); ?>
            </form>

            <hr>

            <h2>Manual Crawl</h2>
            <div style="display: flex; gap: 10px;">
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                    <input type="hidden" name="action" value="run_crawl">
                    <input type="hidden" name="crawl_type" value="api">
                    <?php submit_button('Run API Crawl Now'); ?>
                </form>

                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                    <input type="hidden" name="action" value="run_crawl">
                    <input type="hidden" name="crawl_type" value="rss">
                    <?php submit_button('Run RSS Crawl Now'); ?>
                </form>
            </div>

            <hr>

            <h2>Log</h2>
            <pre style="background:#000;color:#0f0;padding:10px;height:200px;overflow:auto;">
<?php echo esc_html(get_option('auto_crawl_log')); ?>
            </pre>
        </div>
        <?php
    }

    /* =========================
       HANDLE MANUAL
    ==========================*/
    public function handle_manual_crawl() {
        if (!current_user_can('manage_options')) return;

        $type = $_POST['crawl_type'] ?? 'api';

        if ($type === 'rss') {
            $this->run_rss_crawl();
        } else {
            $this->run_crawl();
        }

        wp_redirect(admin_url('admin.php?page=auto-crawl'));
        exit;
    }

    /* =========================
       CORE LOGIC
    ==========================*/
    public function run_crawl() {
        $api = get_option('auto_crawl_api_url');
        $limit = intval(get_option('auto_crawl_limit', 5));

        if (!$api) {
            $this->log("❌ API URL empty");
            return;
        }

        $response = wp_remote_get($api, [
    'timeout' => 20,
    'sslverify' => false,
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        'Accept' => 'application/json'
    ]
]);

        if (is_wp_error($response)) {
            $this->log("❌ Fetch error");
            return;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!$data) {
            $this->log("❌ Invalid JSON");
            return;
        }

        $count = 0;

        foreach ($data as $item) {

            if ($count >= $limit) break;

            $external_id = $item['id'] ?? null;

            if (!$external_id) continue;

            $title = $item['title']['rendered'] ?? '';

            if ($this->is_duplicate($external_id) || $this->is_duplicate_title($title)) {
                $this->log("⚠️ Duplicate (API): " . $external_id . " / Title: " . $title);
                continue;
            }

            $post_id = wp_insert_post([
                'post_title'   => $item['title']['rendered'] ?? 'No title',
                'post_content' => $item['content']['rendered'] ?? '',
                'post_status'  => 'publish'
            ]);

            if ($post_id) {
                update_post_meta($post_id, 'external_id', $external_id);

                // xử lý ảnh
                if (!empty($item['featured_media_url'])) {
                    media_sideload_image($item['featured_media_url'], $post_id);
                }

                $this->log("✅ Inserted: " . $post_id);
                $count++;
            }
        }

        $this->log("🎯 Done. Total: " . $count);
    }

    public function run_rss_crawl() {
        $rss_url = get_option('auto_crawl_rss_url');
        $limit = intval(get_option('auto_crawl_limit', 5));
        $selector = get_option('auto_crawl_rss_content_selector');

        if (!$rss_url) {
            $this->log("❌ RSS URL empty");
            return;
        }

        $response = wp_remote_get($rss_url, [
            'timeout' => 20,
            'sslverify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
            ]
        ]);

        if (is_wp_error($response)) {
            $this->log("❌ RSS Fetch error");
            return;
        }

        $body = wp_remote_retrieve_body($response);
        $xml = @simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA);

        if (!$xml || !isset($xml->channel->item)) {
            $this->log("❌ Invalid RSS XML");
            return;
        }

        $count = 0;

        foreach ($xml->channel->item as $item) {
            if ($count >= $limit) break;

            $link = (string) $item->link;
            $title = (string) $item->title;
            
            // Content
            $content = '';

            // 1. Crawl từ Link nếu có Selector
            if (!empty($selector) && !empty($link)) {
                $page_response = wp_remote_get($link, [
                    'timeout' => 20,
                    'sslverify' => false,
                    'headers' => ['User-Agent' => 'Mozilla/5.0']
                ]);

                if (!is_wp_error($page_response)) {
                    $page_html = wp_remote_retrieve_body($page_response);
                    
                    if (!empty($page_html)) {
                        $dom = new DOMDocument();
                        // Thêm meta tag để xử lý bảng mã UTF-8 chính xác
                        @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $page_html);
                        $xpath = new DOMXPath($dom);
                        
                        $xpath_expression = $this->convert_css_to_xpath($selector);
                        
                        if (!empty($xpath_expression)) {
                            $nodes = $xpath->query($xpath_expression);
                            
                            if ($nodes->length > 0) {
                                $content_node = $nodes->item(0);
                                $content = $dom->saveHTML($content_node);
                            }
                        }
                    }
                }
            }

            // Fallback 1: content:encoded
            if (empty($content)) {
                $namespaces = $item->getNamespaces(true);
                if (isset($namespaces['content'])) {
                    $content = (string) $item->children($namespaces['content'])->encoded;
                }
            }

            // Fallback 2: description
            if (empty($content)) {
                $content = (string) $item->description;
            }

            if (!$link) continue;

            if ($this->is_duplicate($link) || $this->is_duplicate_title($title)) {
                $this->log("⚠️ Duplicate (RSS): " . $link . " / Title: " . $title);
                continue;
            }

            $post_id = wp_insert_post([
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'publish'
            ]);

            if ($post_id) {
                update_post_meta($post_id, 'external_id', $link);

                // Featured Image
                $image_url = '';
                if (isset($item->enclosure) && isset($item->enclosure['url'])) {
                    $image_url = (string) $item->enclosure['url'];
                }
                
                if (empty($image_url)) {
                     preg_match('/<img[^>]+src="([^">]+)"/i', (string)$item->description, $matches);
                     if (!empty($matches[1])) {
                         $image_url = $matches[1];
                     }
                }

                if (!empty($image_url)) {
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    require_once(ABSPATH . 'wp-admin/includes/media.php');
                    media_sideload_image($image_url, $post_id);
                }

                $this->log("✅ Inserted (RSS): " . $post_id);
                $count++;
            }
        }

        $this->log("🎯 RSS Done. Total: " . $count);
    }

    /* =========================
       CSS TO XPATH HELPER
    ==========================*/
    private function convert_css_to_xpath($selector) {
        $selector = trim($selector);
        if (empty($selector)) return '';

        // Class
        if (strpos($selector, '.') === 0) {
            $class = substr($selector, 1);
            return "//*[contains(concat(' ', normalize-space(@class), ' '), ' $class ')]";
        }
        
        // ID
        if (strpos($selector, '#') === 0) {
            $id = substr($selector, 1);
            return "//*[@id='$id']";
        }

        // Tag.class
        if (preg_match('/^([a-zA-Z0-9]+)\.([a-zA-Z0-9\-_]+)$/', $selector, $matches)) {
            $tag = $matches[1];
            $class = $matches[2];
            return "//{$tag}[contains(concat(' ', normalize-space(@class), ' '), ' $class ')]";
        }

        return '';
    }

    /* =========================
       DUPLICATE CHECK
    ==========================*/
    private function is_duplicate($external_id) {
        $query = new WP_Query([
            'meta_key' => 'external_id',
            'meta_value' => $external_id,
            'post_type' => 'post',
            'fields' => 'ids'
        ]);

        return !empty($query->posts);
    }

    /* =========================
       DUPLICATE TITLE CHECK
    ==========================*/
    private function is_duplicate_title($title) {
        if (empty($title)) return false;
        global $wpdb;
        $post_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'post' AND post_status != 'trash' LIMIT 1",
            $title
        ));
        return !empty($post_id);
    }

    /* =========================
       LOG
    ==========================*/
    private function log($message) {
        $log = get_option('auto_crawl_log', '');
        $log .= "[" . date('Y-m-d H:i:s') . "] " . $message . "\n";

        update_option('auto_crawl_log', $log);
    }

}

new AutoCrawlPosts();