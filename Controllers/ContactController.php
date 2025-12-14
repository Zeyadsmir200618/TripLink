<?php
class ContactController {
    public function view() {
        $this->render('contact');
    }

    private function render($view, $data = []) {
        extract($data); 
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "View not found: " . htmlspecialchars($viewPath);
        }
    }
}
?>
