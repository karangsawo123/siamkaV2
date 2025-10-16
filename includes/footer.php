<?php
if (!defined("SECURE")) {
    die("Direct access not allowed");
}
?>
            </div> <!-- .content-wrapper -->
        </main> <!-- .main-content -->
    </div> <!-- .wrapper -->
    
    <footer class="main-footer">
        <div class="footer-content">
            <p>&copy; <?= date("Y") ?> <?= SITE_NAME ?> - All Rights Reserved</p>
            <p>Developed by [Your Team Name]</p>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
    
    <!-- Additional JS -->
    <?php if(isset($additional_js)): ?>
        <?php foreach($additional_js as $js): ?>
            <script src="<?= BASE_URL . $js ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>