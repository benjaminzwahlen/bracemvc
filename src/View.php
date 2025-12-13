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

        //$params['_CONFIG'] = $_CONFIG;
        extract(["_" => $params], EXTR_OVERWRITE);

        unset($params);

        try {

            ob_start();
            require_once $filename;
            $body = ob_get_contents();
            ob_clean();
            extract(["page_body_contents" => $body], EXTR_OVERWRITE);

            require_once $template;
            $page = ob_get_clean();
            return $page;
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
    }

    public static function renderSingleView(string $viewName, array &$params)
    {
        $filename = "../app/views/" . $viewName . ".view.php";
        extract(["_" => $params], EXTR_OVERWRITE);
        unset($params);
        try {
            ob_start();
            require $filename;
            $body = ob_get_clean();
            return $body;
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
    }
}
