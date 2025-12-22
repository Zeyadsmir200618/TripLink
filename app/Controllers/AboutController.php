<?php
class AboutController {
    public function view() {
        // Use a full path to avoid "file not found" issues
        $viewPath = __DIR__ . '/../views/aboutus.php';
        
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "View not found: " . htmlspecialchars($viewPath);
        }
    }
}
?>
