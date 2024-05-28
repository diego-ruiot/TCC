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

if (!function_exists('getDsnValue')) {
    function getDsnValue($dsnstring, $dsnParameter, $default = NULL) {
        $pattern = sprintf('~%s=([^;]*)(?:;|$)~', preg_quote($dsnParameter, '~'));

        $result = preg_match($pattern, $dsnstring, $matches);
        if ($result === FALSE) {
            throw new RuntimeException('Regular expression matching failed unexpectedly.');
        }

        return $result ? $matches[1] : $default;
    }
}
if (!function_exists('getAllMigration')) {
    function getAllMigration(): array {
        return [
            [
                "table_name" => "trabalhos",
                "columns"    => [
                    "id" => [
                        "type"           => "bigint",
                        "size"           => 20,
                        "auto_increment" => true,
                        "not_null"       => true,
                    ],
                    "autor" => [
                        "type"     => "varchar",
                        "size"     => 255,
                        "not_null" => true,
                    ],
                    "orientador" => [
                        "type"     => "varchar",
                        "size"     => 255,
                        "not_null" => true,
                    ],
                    "coorientador" => [
                        "type"    => "varchar",
                        "size"    => 255,
                        "default" => "NULL",
                    ],
                    "arquivo" => [
                        "type"     => "varchar",
                        "size"     => 255,
                        "not_null" => true,
                    ],
                    "titulo" => [
                        "type"     => "varchar",
                        "size"     => 500,
                        "not_null" => true,
                    ],
                    "ano" => [
                        "type"     => "Int",
                        "size"     => 11,
                        "not_null" => true,
                    ],
                    "campus" => [
                        "type"     => "varchar",
                        "size"     => 500,
                        "not_null" => true,
                    ],
                    "palavras" => [
                        "type"    => "longtext",
                        "default" => "NULL",
                    ],
                ]
            ],
        ];
    }
}

?>