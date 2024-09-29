<?php
/*
Plugin Name: Chatbot Builder AI Webchat Installer
Description: Integrates the Chatbot Builder AI Webchat with your WordPress site using your script.
Version: 1.1
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
    if (isset($_POST['webchat_script'])) {
        update_option('chatbot_builder_ai_webchat_script', wp_kses_post($_POST['webchat_script']));
        echo "<div class='updated'><p>Settings saved.</p></div>";
    }

    // Get existing values
    $webchat_script = get_option('chatbot_builder_ai_webchat_script', '');

    ?>
    <div class="wrap">
        <h1>Chatbot Builder AI Webchat Settings</h1>
        <form method="POST">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Webchat Script:</th>
                    <td>
                        <textarea name="webchat_script" rows="10" cols="50" class="large-text code" required><?php echo esc_textarea($webchat_script); ?></textarea>
                        <p class="description">Paste your full Chatbot Builder AI Webchat script here.</p>
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
    $webchat_script = get_option('chatbot_builder_ai_webchat_script', '');

    if ($webchat_script) {
        echo $webchat_script;
    }
}
add_action('wp_footer', 'chatbot_builder_ai_add_webchat_script');
?>
