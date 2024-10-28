<?php

namespace Joc4enRatlla\Services;

use Joc4enRatlla\Exceptions\NoViewException;

class Service
{
    public static function loadView($view, $data = [])
    {
        $viewPath = str_replace('.', '/', $view);
        extract($data);

        $file =  $_SERVER['DOCUMENT_ROOT'] . "/../Views/$viewPath.view.php";

        if (!file_exists($file)){
            throw new NoViewException();
        }
        include $file;

    }


}