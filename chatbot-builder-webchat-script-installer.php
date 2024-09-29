<?php
/*
Plugin Name: Chatbot Builder AI Webchat Installer
Description: Integrates the Chatbot Builder AI Webchat with your WordPress site using your script.
Version: 1.3
Author: Chatbot Builder AI Team
*/

// Add settings page
function chatbot_builder_ai_webchat_settings_page() {
    add_options_page(
        'Chatbot Builder AI Webchat Settings',
        'Chatbot Builder AI Webchat',
        'manage_options',
        'chatbot-builder-ai-webchat',
        'chatbot_builder_ai_webchat_settings_page_html'
    );
}
add_action('admin_menu', 'chatbot_builder_ai_webchat_settings_page');

// Display settings page HTML
function chatbot_builder_ai_webchat_settings_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save settings if form is submitted
    if (isset($_POST['default_webchat_script']) || isset($_POST['page_specific_scripts'])) {
        update_option('chatbot_builder_ai_default_webchat_script', wp_unslash($_POST['default_webchat_script']));
        update_option('chatbot_builder_ai_page_specific_scripts', wp_unslash($_POST['page_specific_scripts']));
        echo "<div class='updated'><p>Settings saved.</p></div>";
    }

    // Get existing values
    $default_webchat_script = get_option('chatbot_builder_ai_default_webchat_script', '');
    $page_specific_scripts = get_option('chatbot_builder_ai_page_specific_scripts', '');

    ?>
    <div class="wrap">
        <h1>Chatbot Builder AI Webchat Settings</h1>
        <form method="POST">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Default Webchat Script:</th>
                    <td>
                        <textarea name="default_webchat_script" rows="10" cols="50" class="large-text code"><?php echo esc_textarea($default_webchat_script); ?></textarea>
                        <p class="description">Paste your full default Chatbot Builder AI Webchat script here, including the &lt;script&gt; tags. This script will be used on all pages unless a specific script is defined.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Page Specific Webchat Scripts (JSON):</th>
                    <td>
                        <textarea name="page_specific_scripts" rows="10" cols="50" class="large-text code"><?php echo esc_textarea($page_specific_scripts); ?></textarea>
                        <p class="description">Specify page-specific scripts in JSON format. Example:<br>
                            <code>{"home": "&lt;script&gt;...&lt;/script&gt;", "about": "&lt;script&gt;...&lt;/script&gt;", "/contact": "&lt;script&gt;...&lt;/script&gt;"}</code></p>
                    </td>
                </tr>
            </table>
            <p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
        </form>
    </div>
    <?php
}

// Add Chatbot Builder AI webchat script to the footer
function chatbot_builder_ai_add_webchat_script() {
    $default_webchat_script = get_option('chatbot_builder_ai_default_webchat_script', '');
    $page_specific_scripts = get_option('chatbot_builder_ai_page_specific_scripts', '');

    if ($page_specific_scripts) {
        $page_specific_scripts = json_decode($page_specific_scripts, true);
        if (is_array($page_specific_scripts)) {
            global $wp;
            $current_url_path = add_query_arg(array(), $wp->request);

            // Check if there's a specific script for the current page
            foreach ($page_specific_scripts as $page => $script) {
                if ($page === 'home' && is_front_page()) {
                    echo $script; // Home page
                    return;
                } elseif ($page === 'about' && is_page('about')) {
                    echo $script; // About page (example page name)
                    return;
                } elseif (trim($page, '/') === $current_url_path) {
                    echo $script; // Specific URL path match
                    return;
                }
            }
        }
    }

    // Fallback to default script
    if ($default_webchat_script) {
        echo $default_webchat_script;
    }
}
add_action('wp_footer', 'chatbot_builder_ai_add_webchat_script');
?>
