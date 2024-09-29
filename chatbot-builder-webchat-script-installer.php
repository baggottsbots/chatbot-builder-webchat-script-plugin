<?php
/*
Plugin Name: Chatbot Builder AI Webchat Script Installer
Description: Adds the webchat script to your WordPress site with user-supplied account and webchat ID.
Version: 1.0
Author: Chatbot Builder AI
*/

// Add settings page
function webchat_settings_page() {
    add_options_page(
        'Webchat Script Settings',
        'Chatbot Builder Webchat Script',
        'manage_options',
        'webchat-script',
        'webchat_settings_page_html'
    );
}
add_action('admin_menu', 'webchat_settings_page');

// Display settings page HTML
function webchat_settings_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save settings if form is submitted
    if (isset($_POST['webchat_id']) && isset($_POST['account_id'])) {
        update_option('webchat_id', sanitize_text_field($_POST['webchat_id']));
        update_option('account_id', sanitize_text_field($_POST['account_id']));
        echo "<div class='updated'><p>Settings saved.</p></div>";
    }

    // Get existing values
    $webchat_id = get_option('webchat_id', '');
    $account_id = get_option('account_id', '');

    ?>
    <div class="wrap">
        <h1>Chatbot Builder AI Webchat Script Settings</h1>
        <form method="POST">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Webchat ID:</th>
                    <td><input type="text" name="webchat_id" value="<?php echo esc_attr($webchat_id); ?>" required /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Account ID:</th>
                    <td><input type="text" name="account_id" value="<?php echo esc_attr($account_id); ?>" required /></td>
                </tr>
            </table>
            <p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
        </form>
    </div>
    <?php
}

// Add webchat script to the footer
function add_webchat_script() {
    $webchat_id = get_option('webchat_id', '');
    $account_id = get_option('account_id', '');

    if ($webchat_id && $account_id) {
        echo "
            <script src=\"https://app.chatgptbuilder.io/webchat/plugin.js?v=5\"></script>
            <script>ktt10.setup({id:\"$webchat_id\",accountId:\"$account_id\",color:\"#010101\"})</script>
        ";
    }
}
add_action('wp_footer', 'add_webchat_script');
