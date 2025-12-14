<?php
class AboutController {
    public function view() {
        $viewPath = __DIR__ . '/../views/aboutus.php';
        
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "View not found: " . htmlspecialchars($viewPath);
        }
    }
}
?>
