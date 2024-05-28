<?php

if (!function_exists('view')) {
    function view(string $path, array $vars = null) {
        $root_path = __DIR__ . DIRECTORY_SEPARATOR. "..". DIRECTORY_SEPARATOR;
        $filename = $root_path ."views". DIRECTORY_SEPARATOR . str_replace(".", DIRECTORY_SEPARATOR, $path) .".php";
        
        if (file_exists($filename)) {
            if (is_array($vars) && !empty($vars)) {
                extract($vars);
            }
            ob_start();
            include($filename);
        }
    }
}

?>