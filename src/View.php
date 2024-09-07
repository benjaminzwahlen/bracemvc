<?php

namespace benjaminzwahlen\bracemvc;

use benjaminzwahlen\bracemvc\common\exceptions\ViewNotFoundException;
use benjaminzwahlen\bracemvc\common\storage\session\AbstractSessionManager;

use benjaminzwahlen\bracemvc\common\storage\session\User;

class View
{


    public static function renderView(array &$_CONFIG, string $layoutName, string $viewName, array &$params)
    {
        $filename = "../app/views/" . $viewName . ".view.php";

        $template = "../app/views/layouts/" . $layoutName . ".view.php";

        if (!file_exists($template)) {
            throw new ViewNotFoundException("Unable to find template file: " . $template);
        }

        if (!file_exists($filename)) {
            throw new ViewNotFoundException("Unable to find view file: " . $filename);
        }

        //TODO
        $params['_CONFIG'] = $_CONFIG;
        extract(["_" => $params], EXTR_OVERWRITE);
        unset($params);

        ob_start();
        require_once $filename;
        $body = ob_get_clean();


        extract(["page_body_contents" => $body], EXTR_OVERWRITE);

        ob_start();
        require_once $template;
        $page = ob_get_clean();


        return $page;
    }
}
