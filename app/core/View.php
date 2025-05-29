<?php

require_once __DIR__ . '/../../config.php';

class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data);

        ob_start();
        require VIEW_PATH . "/$view";
        $content = ob_get_clean();

        require VIEW_PATH . "/layouts/layout.php";
    }
}
