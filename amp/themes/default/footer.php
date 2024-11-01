<?php 

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) { 
    exit; 
}

?>

</main>

    <footer class="container">
        <div id="footer">
            <p>Copyright &copy; <?php echo date("Y"); ?> </p>
        </div>
        <?php do_action( 'sovrn_tools_post_template_footer' ); ?>
    </footer>

    </body>

</html>