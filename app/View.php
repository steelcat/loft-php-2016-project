<?php
namespace App;

class View
{
    public static function show($pageName, $pageData = [])
    {
        $params = [];
        $pageDataOutput = [];
        if (APP_CACHE) {
            $params += ['cache' => CACHE];
        }
        if (APP_DEBUG) {
            $params += ['debug' => true];
            $pageDataOutput += ['debug' => true];
        }
        $template = $pageName . '.twig';
        $templates = new \Twig_Loader_Filesystem(TEMPLATES);
        $twig = new \Twig_Environment($templates, $params);
        $twig->addExtension(new \Twig_Extension_Debug());
        $pageDataOutput += $pageData + ['error' => App::get('error')];
        echo $twig->render($template, $pageDataOutput);
    }
}
