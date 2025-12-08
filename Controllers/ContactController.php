<?php
class ContactController {
    public function view() {
        $this->render('contact');
    }

    private function render($view, $data = []) {
        extract($data); // optional: pass data to views
        include "app/views/{$view}.php";
    }
}
?>
