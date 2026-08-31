// Toast Notification Helper
window.showCustomToast = function(type, message, duration = 3000) {
    try {
        // Create a simple colored div that shows in bottom-right
        var toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: ${type === 'success' ? '#28a745' : '#dc3545'};
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            z-index: 9999;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            max-width: 300px;
            word-wrap: break-word;
        `;
        
        toast.innerHTML = message;
        document.body.appendChild(toast);
        
        // Auto-remove after duration
        setTimeout(function() {
            if (toast && toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, duration);
        
    } catch (error) {
        alert(message);
    }
};

var base_url = $("#base_url").val();

$("input[name='theme[admin_side_bar_color]']").on("focusout", function(){
    $(".sidebar-offcanvas").css('background-color', $(this).val());
    $(".sidebar-offcanvas .navbar-nav").css('background-color', $(this).val());
    $(".sidebar-light").css('background-color', $(this).val());
    $(".dashboard-wrap").css('background-color', $(this).val());
});

$("input[name='theme[admin_side_scroll_bar_color]']").on("focusout", function(){
    $(".scroll-bar").css({'-webkit-scrollbar-thumb':'background-color: '+$(this).val()});
});

$("input[name='theme[admin_side_bar_text_color]']").on("focusout", function(){
    $(".nav-link .menu-title").css('color', $(this).val());
    $(".dropdown-menu .dropdown-item").css('color', $(this).val());
    $(".admin-balance .name").css('color', $(this).val());
    $(".admin-balance .designation").css('color', $(this).val());
});

$("input[name='theme[admin_top_bar_color]']").on("focusout", function(){
    $(".navbar-menu-wrapper .header-right").css('background-color', $(this).val());
    $(".navbar .navbar-menu-wrapper").css('background-color', $(this).val());
});

$("input[name='theme[admin_footer_color]']").on("focusout", function(){
    $(".footer-bg .dashboard-footer").css('background-color', $(this).val());
});

$("input[name='theme[admin_logo_color]']").on("focusout", function(){
    $(".navbar .navbar-brand-wrapper").css('background-color', $(this).val());
});

$("input[name='theme[admin_login_box_background_color]']").on("focusout", function(){
    $(".navbar .navbar-brand-wrapper").css('background-color', $(this).val());
});

$("input[name='theme[admin_login_background_color]']").on("focusout", function(){
    $(".login-main").css('background-color', $(this).val());
});

// New admin theme colors - Real-time color changes (direct application)
$("input[name='theme[admin_topbar_bg]']").on("focusout", function(){
    $(".admin-topbar").css('background-color', $(this).val());
});

$("input[name='theme[admin_topbar_text]']").on("focusout", function(){
    $(".admin-topbar").css('color', $(this).val());
    $(".admin-topbar .topbar-btn").css('color', $(this).val());
    $(".admin-topbar h4").css('color', $(this).val());
});

$("input[name='theme[admin_dropdown_bg]']").on("focusout", function(){
    $(".admin-topbar .dropdown-menu").css('background-color', $(this).val());
    $(".horizontal-nav .dropdown-menu").css('background-color', $(this).val());
});

$("input[name='theme[admin_dropdown_text]']").on("focusout", function(){
    var c = $(this).val();
    $(':root').get(0).style.setProperty('--admin-dropdown-text', c);
    $(".admin-topbar .dropdown-item").css('color', c);
});

$("input[name='theme[admin_dropdown_hover_bg]']").on("focusout", function(){
    $(':root').get(0).style.setProperty('--admin-dropdown-hover-bg', $(this).val());
});

$("input[name='theme[admin_dropdown_hover_text]']").on("focusout", function(){
    $(':root').get(0).style.setProperty('--admin-dropdown-hover-text', $(this).val());
});

$("input[name='theme[admin_menu_bg]']").on("focusout", function(){
    $(".horizontal-nav").css('background-color', $(this).val());
});

$("input[name='theme[admin_menu_text]']").on("focusout", function(){
    $(".horizontal-nav .nav-item").css('color', $(this).val());
});

$("input[name='theme[admin_menu_active]']").on("focusout", function(){
    $(".horizontal-nav .nav-item.active").css('color', $(this).val());
});

$("input[name='theme[admin_menu_hover]']").on("focusout", function(){
    $(".horizontal-nav .nav-item:hover").css('color', $(this).val());
});

$("input[name='theme[admin_footer_bg]']").on("focusout", function(){
    $(".admin-footer").css('background-color', $(this).val());
});

$("input[name='theme[admin_footer_text]']").on("focusout", function(){
    $(".admin-footer").css('color', $(this).val());
    $(".admin-footer a").css('color', $(this).val());
    $(".admin-footer span").css('color', $(this).val());
});

$("input[name='theme[admin_dropdown_scrollbar]']").on("focusout", function(){
    var color = $(this).val();
    // Apply scrollbar colors using CSS
    var style = `.nav-dropdown .dropdown-menu::-webkit-scrollbar-track { background: ${color}33 !important; } .nav-dropdown .dropdown-menu::-webkit-scrollbar-thumb { background: ${color} !important; } .nav-dropdown .dropdown-menu::-webkit-scrollbar-thumb:hover { background: ${color}DD !important; } .nav-dropdown .dropdown-menu { scrollbar-color: ${color} ${color}33 !important; }`;
    
    // Remove existing scrollbar style if it exists
    $('#dynamic-scrollbar-style').remove();
    
    // Add new style
    $('head').append('<style id="dynamic-scrollbar-style">' + style + '</style>');
});

$(".default-theme-setting").on("click", function(){
    var setting = $(this).val();
    var color = '';

    if (setting == "user_side_bar_color") {
        color = "#FFFFFF";
        $("input[name='theme[user_side_bar_color]']").val(color);
    }else if (setting == 'user_side_bar_text_color') {
        color = "#3F567A";
        $("input[name='theme[user_side_bar_text_color]']").val(color);
    }else if (setting == 'user_side_bar_clock_text_color') {
        color = "#085445";
        $("input[name='theme[user_side_bar_clock_text_color]']").val(color);
    }else if (setting == 'user_side_bar_text_hover_color') {
        color = "#5EC394";
        $("input[name='theme[user_side_bar_text_hover_color]']").val(color);
    }else if (setting == 'user_top_bar_color') {
        color = "#FFFFFF";
        $("input[name='theme[user_top_bar_color]']").val(color);
    }else if (setting == 'user_footer_color') {
        color = "#FFFFFF";
        $("input[name='theme[user_footer_color]']").val(color);
    }
    else if (setting == 'user_button_color') {
        color = "#3d5674";
        $("input[name='theme[user_button_color]']").val(color);
    }
    else if (setting == 'user_button_hover_color') {
        color = "#085445";
        $("input[name='theme[user_button_hover_color]']").val(color);
    }
    
    else if (setting == 'admin_side_bar_color') {
        color = "#FFFFFF";
        $("input[name='theme[admin_side_bar_color]']").val(color);
        $(".sidebar-offcanvas").css('background-color', color);
        $(".sidebar-offcanvas .navbar-nav").css('background-color', color);
        $(".sidebar-light").css('background-color', color);
        $(".dashboard-wrap").css('background-color', color);
    }else if (setting == 'admin_side_bar_scroll_color') {
        color = "#007BFF";
        $("input[name='theme[admin_side_bar_scroll_color]']").val(color);
    }else if (setting == 'admin_side_bar_text_color') {
        color = "#686868";
        $("input[name='theme[admin_side_bar_text_color]']").val(color);
        $(".nav-link .menu-title").css('color', color);
        $(".dropdown-menu .dropdown-item").css('color', color);
        $(".admin-balance .name").css('color', color);
        $(".admin-balance .designation").css('color', color);
    }else if (setting == 'admin_side_bar_text_hover_color') {
        color = "#007BFF";
        $("input[name='theme[admin_side_bar_text_hover_color]']").val(color);
    }else if (setting == 'admin_top_bar_color') {
        color = "#FFFFFF";
        $("input[name='theme[admin_top_bar_color]']").val(color);
        $(".navbar-menu-wrapper .header-right").css('background-color', color);
        $(".navbar .navbar-menu-wrapper").css('background-color', color);
    }else if (setting == 'admin_footer_color') {
        color = "#F2F3F5";
        $("input[name='theme[admin_footer_color]']").val(color);
        $(".footer-bg .dashboard-footer").css('background-color', color);
    }else if (setting == 'admin_logo_color') {
        color = "#007BFF";
        $("input[name='theme[admin_logo_color]']").val(color);
        $(".navbar .navbar-brand-wrapper").css('background-color', color);
    }
    else if (setting == 'admin_button_color') {
        color = "#3d5674";
        $("input[name='theme[admin_button_color]']").val(color);
        $(".navbar .navbar-brand-wrapper").css('background-color', color);
    }
    else if (setting == 'admin_button_hover_color') {
        color = "#007BFF";
        $("input[name='theme[admin_button_hover_color]']").val(color);
        $(".navbar .navbar-brand-wrapper").css('background-color', color);
    }
     else if (setting == 'admin_login_box_background_color') {
        color = "#7a90a8";
        $("input[name='theme[admin_login_box_background_color]']").val(color);
        $(".navbar .navbar-brand-wrapper").css('background-color', color);
    }
    else if (setting == 'admin_login_background_color') {
        color = "#5e7590";
        $("input[name='theme[admin_login_background_color]']").val(color);
        $(".login-main").css('background-color', color);
    }
    // New admin theme colors
    else if (setting == 'admin_topbar_bg') {
        color = "#34495e";
        $("input[name='theme[admin_topbar_bg]']").val(color);
        $(".admin-topbar").css('background-color', color);
    }
    else if (setting == 'admin_topbar_text') {
        color = "#ffffff";
        $("input[name='theme[admin_topbar_text]']").val(color);
        $(".admin-topbar").css('color', color);
        $(".admin-topbar .topbar-btn").css('color', color);
        $(".admin-topbar h4").css('color', color);
    }
    else if (setting == 'admin_dropdown_bg') {
        color = "#ffffff";
        $("input[name='theme[admin_dropdown_bg]']").val(color);
        $(".admin-topbar .dropdown-menu").css('background-color', color);
        $(".horizontal-nav .dropdown-menu").css('background-color', color);
        $(':root').get(0).style.setProperty('--admin-dropdown-text', '#212529');
        $(':root').get(0).style.setProperty('--admin-dropdown-hover-bg', '#e3f2fd');
        $(':root').get(0).style.setProperty('--admin-dropdown-hover-text', '#1976d2');
        $("input[name='theme[admin_dropdown_text]']").val('#212529');
        $("input[name='theme[admin_dropdown_hover_bg]']").val('#e3f2fd');
        $("input[name='theme[admin_dropdown_hover_text]']").val('#1976d2');
    }
    else if (setting == 'admin_menu_bg') {
        color = "#f8f9fa";
        $("input[name='theme[admin_menu_bg]']").val(color);
        $(".horizontal-nav").css('background-color', color);
    }
    else if (setting == 'admin_menu_text') {
        color = "#ffffff";
        $("input[name='theme[admin_menu_text]']").val(color);
        $(".horizontal-nav .nav-item").css('color', color);
    }
    else if (setting == 'admin_menu_active') {
        color = "#ffffff";
        $("input[name='theme[admin_menu_active]']").val(color);
        $(".horizontal-nav .nav-item.active").css('color', color);
    }
    else if (setting == 'admin_menu_hover') {
        color = "#ffffff";
        $("input[name='theme[admin_menu_hover]']").val(color);
        $(".horizontal-nav .nav-item:hover").css('color', color);
    }
    else if (setting == 'admin_footer_bg') {
        color = "#1a252f";
        $("input[name='theme[admin_footer_bg]']").val(color);
        $(".admin-footer").css('background-color', color);
    }
    else if (setting == 'admin_footer_text') {
        color = "#ffffff";
        $("input[name='theme[admin_footer_text]']").val(color);
        $(".admin-footer").css('color', color);
        $(".admin-footer a").css('color', color);
        $(".admin-footer span").css('color', color);
    }
    else if (setting == 'admin_dropdown_scrollbar') {
        color = "#666666";
        $("input[name='theme[admin_dropdown_scrollbar]']").val(color);
        
        // Apply scrollbar colors using CSS
        var style = `.nav-dropdown .dropdown-menu::-webkit-scrollbar-track { background: ${color}33 !important; } .nav-dropdown .dropdown-menu::-webkit-scrollbar-thumb { background: ${color} !important; } .nav-dropdown .dropdown-menu::-webkit-scrollbar-thumb:hover { background: ${color}DD !important; } .nav-dropdown .dropdown-menu { scrollbar-color: ${color} ${color}33 !important; }`;
        
        // Remove existing scrollbar style if it exists
        $('#dynamic-scrollbar-style').remove();
        
        // Add new style
        $('head').append('<style id="dynamic-scrollbar-style">' + style + '</style>');
    }

    if(color != '') {
        $.ajax({
            url:base_url+'admincontrol/default_theme_settings',
            type:'POST',
            dataType:'json',
            data:{'action':'default_theme_settings', setting:setting, color:color},
            success:function(json){
            },
        });
    }
});

$(".default-font-setting").on("click", function(){
    var setting = $(this).val();
    var font = '';

    if (setting == "admin_side_font") {
        font = "PT Sans";
        $(".class_admin_side_font").val(font).trigger("change");
    }else if (setting == 'user_side_font') {
        font = "sans-serif";
        $(".class_user_side_font").val(font).trigger("change");
    }else if (setting == 'front_side_font') {
        font = "sans-serif";
        $(".class_front_side_font").val(font).trigger("change");
    }else if (setting == 'cart_store_side_font') {
        font = "Jost";
        $(".class_cart_store_side_font").val(font).trigger("change");
    }else if (setting == 'sales_store_side_font') {
        font = "Roboto";
        $(".class_sales_store_side_font").val(font).trigger("change");
    }

    if(font != '') {
        $.ajax({
            url:base_url+'admincontrol/default_font_settings',
            type:'POST',
            dataType:'json',
            data:{'action':'default_font_settings', setting:setting, font:font},
            success:function(json){
            },
        });
    }
});

// Reset to Default Colors functionality
$("#reset-default-colors").on("click", function(){
    var btn = $(this);
    var originalText = btn.html();
    
    // Show loading state
    btn.prop('disabled', true).html('<i class="bi bi-arrow-repeat me-1"></i>Resetting...');
    
    $.ajax({
        url: base_url + 'admincontrol/reset_default_colors_ajax',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                // Update all color inputs with default values
                $("input[name='theme[admin_topbar_bg]']").val(response.colors.admin_topbar_bg);
                $("input[name='theme[admin_topbar_text]']").val(response.colors.admin_topbar_text);
                $("input[name='theme[admin_dropdown_bg]']").val(response.colors.admin_dropdown_bg);
                if (response.colors.admin_dropdown_text) $("input[name='theme[admin_dropdown_text]']").val(response.colors.admin_dropdown_text);
                if (response.colors.admin_dropdown_hover_bg) $("input[name='theme[admin_dropdown_hover_bg]']").val(response.colors.admin_dropdown_hover_bg);
                if (response.colors.admin_dropdown_hover_text) $("input[name='theme[admin_dropdown_hover_text]']").val(response.colors.admin_dropdown_hover_text);
                $("input[name='theme[admin_menu_bg]']").val(response.colors.admin_menu_bg);
                $("input[name='theme[admin_menu_text]']").val(response.colors.admin_menu_text);
                $("input[name='theme[admin_menu_active]']").val(response.colors.admin_menu_active);
                $("input[name='theme[admin_menu_hover]']").val(response.colors.admin_menu_hover);
                $("input[name='theme[admin_dropdown_scrollbar]']").val(response.colors.admin_dropdown_scrollbar);
                $("input[name='theme[admin_footer_bg]']").val(response.colors.admin_footer_bg);
                $("input[name='theme[admin_footer_text]']").val(response.colors.admin_footer_text);
                
                // CLEAR all existing inline styles first (to allow CSS variables to take over)
                $(".admin-topbar").removeAttr('style');
                $(".admin-topbar .topbar-btn, .admin-topbar h4").removeAttr('style');
                $(".admin-topbar .dropdown-menu, .horizontal-nav .dropdown-menu").removeAttr('style');
                $(".horizontal-nav").removeAttr('style');
                $(".horizontal-nav .nav-item").removeAttr('style');
                $(".horizontal-nav .nav-item.active").removeAttr('style');
                $(".admin-footer").removeAttr('style');
                $(".admin-footer a, .admin-footer span").removeAttr('style');
                
                // Remove all dynamic styles to reset to CSS variables
                $('#dynamic-hover-style').remove();
                $('#dynamic-scrollbar-style').remove();
                
                // Update CSS variables directly in the DOM
                $(':root').get(0).style.setProperty('--admin-topbar-bg', response.colors.admin_topbar_bg);
                $(':root').get(0).style.setProperty('--admin-topbar-text', response.colors.admin_topbar_text);
                $(':root').get(0).style.setProperty('--admin-dropdown-bg', response.colors.admin_dropdown_bg);
                if (response.colors.admin_dropdown_text) $(':root').get(0).style.setProperty('--admin-dropdown-text', response.colors.admin_dropdown_text);
                if (response.colors.admin_dropdown_hover_bg) $(':root').get(0).style.setProperty('--admin-dropdown-hover-bg', response.colors.admin_dropdown_hover_bg);
                if (response.colors.admin_dropdown_hover_text) $(':root').get(0).style.setProperty('--admin-dropdown-hover-text', response.colors.admin_dropdown_hover_text);
                $(':root').get(0).style.setProperty('--admin-menu-bg', response.colors.admin_menu_bg);
                $(':root').get(0).style.setProperty('--admin-menu-text', response.colors.admin_menu_text);
                $(':root').get(0).style.setProperty('--admin-menu-active', response.colors.admin_menu_active);
                $(':root').get(0).style.setProperty('--admin-menu-hover', response.colors.admin_menu_hover);
                $(':root').get(0).style.setProperty('--admin-dropdown-scrollbar', response.colors.admin_dropdown_scrollbar);
                $(':root').get(0).style.setProperty('--admin-footer-bg', response.colors.admin_footer_bg);
                $(':root').get(0).style.setProperty('--admin-footer-text', response.colors.admin_footer_text);
                
                // Update font if provided
                if (response.font) {
                    $(':root').get(0).style.setProperty('--admin-font-family', response.font);
                    // Update font input if it exists
                    $("select[name='site[admin_side_font]']").val(response.font);
                }
                
                // Show success message
                window.showCustomToast('success', response.message);
                
            } else {
                window.showCustomToast('error', response.message || 'Failed to reset colors');
            }
        },
        error: function() {
            window.showCustomToast('error', 'Failed to connect to server');
        },
        complete: function() {
            // Restore button state
            btn.prop('disabled', false).html(originalText);
        }
    });
});

// Auto Style Generator functionality
$("#auto-style-generator").on("click", function(){
    var btn = $(this);
    var originalText = btn.html();
    
    // Show loading state
    btn.prop('disabled', true).html('<i class="bi bi-arrow-repeat me-1"></i>Generating...');
    
    $.ajax({
        url: base_url + 'admincontrol/auto_style_generator_ajax',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                // Update all color inputs with new values
                $("input[name='theme[admin_topbar_bg]']").val(response.colors.admin_topbar_bg);
                $("input[name='theme[admin_topbar_text]']").val(response.colors.admin_topbar_text);
                $("input[name='theme[admin_dropdown_bg]']").val(response.colors.admin_dropdown_bg);
                if (response.colors.admin_dropdown_text) $("input[name='theme[admin_dropdown_text]']").val(response.colors.admin_dropdown_text);
                if (response.colors.admin_dropdown_hover_bg) $("input[name='theme[admin_dropdown_hover_bg]']").val(response.colors.admin_dropdown_hover_bg);
                if (response.colors.admin_dropdown_hover_text) $("input[name='theme[admin_dropdown_hover_text]']").val(response.colors.admin_dropdown_hover_text);
                $("input[name='theme[admin_menu_bg]']").val(response.colors.admin_menu_bg);
                $("input[name='theme[admin_menu_text]']").val(response.colors.admin_menu_text);
                $("input[name='theme[admin_menu_active]']").val(response.colors.admin_menu_active);
                $("input[name='theme[admin_menu_hover]']").val(response.colors.admin_menu_hover);
                $("input[name='theme[admin_dropdown_scrollbar]']").val(response.colors.admin_dropdown_scrollbar);
                $("input[name='theme[admin_footer_bg]']").val(response.colors.admin_footer_bg);
                $("input[name='theme[admin_footer_text]']").val(response.colors.admin_footer_text);
                
                // Apply colors immediately for real-time preview
                $(".admin-topbar").css('background-color', response.colors.admin_topbar_bg);
                $(".admin-topbar .topbar-btn, .admin-topbar h4").css('color', response.colors.admin_topbar_text);
                $(".admin-topbar .dropdown-menu, .horizontal-nav .dropdown-menu").css('background-color', response.colors.admin_dropdown_bg);
                if (response.colors.admin_dropdown_text) {
                    $(':root').get(0).style.setProperty('--admin-dropdown-text', response.colors.admin_dropdown_text);
                    $(':root').get(0).style.setProperty('--admin-dropdown-hover-bg', response.colors.admin_dropdown_hover_bg);
                    $(':root').get(0).style.setProperty('--admin-dropdown-hover-text', response.colors.admin_dropdown_hover_text);
                }
                $(".horizontal-nav").css('background-color', response.colors.admin_menu_bg);
                $(".horizontal-nav .nav-item").css('color', response.colors.admin_menu_text);
                $(".horizontal-nav .nav-item.active").css('color', response.colors.admin_menu_active);
                $(".admin-footer").css({
                    'background-color': response.colors.admin_footer_bg,
                    'color': response.colors.admin_footer_text
                });
                $(".admin-footer a, .admin-footer span").css('color', response.colors.admin_footer_text);
                
                // Apply hover styles using CSS injection (since :hover can't be set via .css())
                var hoverStyle = `.horizontal-nav .nav-item:hover { color: ${response.colors.admin_menu_hover} !important; }`;
                $('#dynamic-hover-style').remove();
                $('head').append('<style id="dynamic-hover-style">' + hoverStyle + '</style>');
                
                // Update scrollbar colors
                var scrollbarColor = response.colors.admin_dropdown_scrollbar;
                var style = `.nav-dropdown .dropdown-menu::-webkit-scrollbar-track { background: ${scrollbarColor}33 !important; } .nav-dropdown .dropdown-menu::-webkit-scrollbar-thumb { background: ${scrollbarColor} !important; } .nav-dropdown .dropdown-menu::-webkit-scrollbar-thumb:hover { background: ${scrollbarColor}DD !important; } .nav-dropdown .dropdown-menu { scrollbar-color: ${scrollbarColor} ${scrollbarColor}33 !important; }`;
                $('#dynamic-scrollbar-style').remove();
                $('head').append('<style id="dynamic-scrollbar-style">' + style + '</style>');
                
                // Show custom toast message
                window.showCustomToast('success', response.message);
                
            } else {
                window.showCustomToast('error', response.message || 'Failed to generate auto style');
            }
        },
        error: function() {
            window.showCustomToast('error', 'Failed to connect to server');
        },
        complete: function() {
            // Restore button state
            btn.prop('disabled', false).html(originalText);
        }
    });
});