<Footer>
    <div class="footer-container">

        <div class="footer">
            <img src="wp-content/themes/gburst/logo.jpg" alt="logo" id="logo">

            <!-- Menu Footer -->
            <?php
            wp_nav_menu(array(
                "theme_location" => "menu-footer",
                "menu_class" => "custom-footer-menu",
                "container" => false,
                "walker" => new Footer_menu()
            ))
            ?>
        </div>
    </div>

</Footer>


</body>

</html>