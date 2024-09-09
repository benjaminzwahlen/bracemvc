<?php

namespace benjaminzwahlen\bracemvc;

use benjaminzwahlen\bracemvc\common\exceptions\ViewNotFoundException;

class View
{


    public static function renderView(string $layoutName, string $viewName, array &$params)
    {
        $filename = "../app/views/" . $viewName . ".view.php";

        $template = "../app/views/layouts/" . $layoutName . ".view.php";

        if (!file_exists($template)) {
            throw new ViewNotFoundException("MVC: Unable to find template file: " . $template);
        }

        if (!file_exists($filename)) {
            throw new ViewNotFoundException("MVC: Unable to find view file: " . $filename);
        }

        extract(["_" => $params], EXTR_OVERWRITE);
        unset($params);

        try {

            ob_start();
            require_once $filename;
            $body = ob_get_contents();

            extract(["page_body_contents" => $body], EXTR_OVERWRITE);

            require_once $template;
            $page = ob_get_clean();

            return $page;
        } catch (\Throwable $e) {
            ob_clean();
            throw new \Exception($e);
        }
    }
}
