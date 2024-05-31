<?php
/*
Plugin Name: TGI WhatsApp Icon
Plugin URI: https://tabsgi.com
Description: Adds a floating WhatsApp icon to the bottom right of the website.
Version: 1.9
Author: Zeeshan Ahmad
Author URI: https://tabsgi.com
Author Email: ziishanahmad@gmail.com
Author Phone: +923214499532
License: MIT
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Register settings
function tgi_whatsapp_icon_register_settings() {
    register_setting( 'tgi_whatsapp_icon_settings_group', 'tgi_whatsapp_number' );
}
add_action( 'admin_init', 'tgi_whatsapp_icon_register_settings' );

// Add settings page to the main admin menu
function tgi_whatsapp_icon_settings_page() {
    add_menu_page(
        'TGI WhatsApp Icon Settings',
        'TGI WhatsApp Icon',
        'manage_options',
        'tgi-whatsapp-icon',
        'tgi_whatsapp_icon_settings_page_html',
        'dashicons-whatsapp', // You can replace this with a suitable dashicon
        2 // Position in the menu
    );
}
add_action( 'admin_menu', 'tgi_whatsapp_icon_settings_page' );

// Settings page HTML
function tgi_whatsapp_icon_settings_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>TGI WhatsApp Icon Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'tgi_whatsapp_icon_settings_group' );
            do_settings_sections( 'tgi_whatsapp_icon_settings_group' );
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">WhatsApp Number</th>
                    <td><input type="text" name="tgi_whatsapp_number" value="<?php echo esc_attr( get_option('tgi_whatsapp_number') ); ?>" placeholder="e.g., 923214499532" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Function to output the WhatsApp icon
function tgi_whatsapp_icon() {
    $whatsapp_number = get_option( 'tgi_whatsapp_number', '923214499532' ); // Default number if not set
    ?>
    <style>
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .tgi-whatsapp-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 999;
            width: 90px;
            height: 90px;
            animation: bounce 1s ease-in-out;
        }

        .tgi-whatsapp-icon img {
            width: 100%;
            height: 100%;
        }

        .tgi-whatsapp-icon-tooltip {
            position: absolute;
            bottom: 100px; /* Adjusted for increased icon size */
            right: 0;
            background-color: #25D366;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap; /* Keep text in one line */
            width: auto; /* Adjust width automatically */
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
    <div class="tgi-whatsapp-icon">
        <a id="tgi-whatsapp-link" href="#" target="_blank">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
            <div class="tgi-whatsapp-icon-tooltip" id="tgi-tooltip">
                <span>Get a Quick Response<br> on WhatsApp</span>
            </div>
        </a>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var whatsappLink = document.getElementById('tgi-whatsapp-link');
            var currentPageUrl = window.location.href;
            var message = 'Hello, I would like to know more about your services. Here is the page I am looking at: ' + currentPageUrl;
            whatsappLink.href = 'https://wa.me/<?php echo esc_js( $whatsapp_number ); ?>?text=' + encodeURIComponent(message);
            
            // JavaScript to reset the animation every 10 seconds
            setInterval(function() {
                var icon = document.querySelector('.tgi-whatsapp-icon');
                icon.style.animation = 'none';
                icon.offsetHeight; // trigger reflow
                icon.style.animation = 'bounce 1s ease-in-out';
            }, 10000); // 10000ms = 10 seconds
        });
    </script>
    <?php
}

add_action('wp_footer', 'tgi_whatsapp_icon');
?>
