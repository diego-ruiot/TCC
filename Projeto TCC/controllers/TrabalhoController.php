<?php

namespace Tcc\Controllers;

use \Tcc\App\Bases\BaseController;
use \Tcc\Models\Trabalho;

class TrabalhoController extends BaseController {
    public static function index() {
        return view("pages.search");
    }

    public static function results() {
        $geral      = $_GET['q'] ?? '';
        $autor      = $_GET['a'] ?? '';
        $orientador = $_GET['o'] ?? '';
        $titulo     = $_GET['t'] ?? '';
        $termo      = $_GET['p'] ?? '';

        $query = "(UPPER(CONCAT('|', CONCAT_WS('|', id, autor, orientador, coorientador, arquivo, titulo, ano, campus), '|')) like UPPER(?)) or
        (UPPER(autor) like UPPER(?)) or
        (UPPER(orientador) like UPPER(?)) or
        (UPPER(titulo) like UPPER(?))";

        $params = [
            '%|%'. $geral .'%|%',
            '%|%'. $autor .'%|%',
            '%|%'. $orientador .'%|%',
            '%|%'. $titulo .'%|%'
        ];
        
        if ($termo !== "") {
            $primeira = true;
            $palavras = explode(' ', $termo);
            $query .= PHP_EOL ." and (";
            foreach($palavras as $palavra) {
                if (!$primeira) {
                    $query .= PHP_EOL ."or ";
                }
                $query .= PHP_EOL ."(UPPER(cast(palavras AS CHAR(99999) CHARACTER SET utf8)) like UPPER(?))";
                $params[] = '%'. $palavra .'%';
                $primeira = false;
            }
            $query .= PHP_EOL .")";
        }
        

        $result = Trabalho::fetchAllWhere($query, $params);
        return view("pages.results", ['results' => $result]);
    }

    public static function advanced_search() {
        return view("pages.advanced_search");
    }

    public static function view_file($file) {
        $root_path = __DIR__ . DIRECTORY_SEPARATOR. "..". DIRECTORY_SEPARATOR;
        $filename = $root_path ."storage". DIRECTORY_SEPARATOR . "pdf" . DIRECTORY_SEPARATOR . $file;

        if(file_exists($filename)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: 0");
            header('Content-Disposition: attachment; filename="'.basename($filename).'"');
            header('Content-Length: ' . filesize($filename));
            header('Pragma: public');

            flush();
            readfile($filename);
            die();
        }
    }

    public static function upload() {
        return view("pages.upload");
    }

    public static function insert() {
        var_dump($_POST);
        die();
        //return view("pages.upload");
    }
}

?>