<?php

namespace Tcc\Models;

use \Tcc\App\Bases\BaseModel; // Inclui classe BaseModel

/* Define 'Trabalho' como a classe filha da classe BaseModel, e define a variavel estática "tableName" com o valor 'trabalhos' */
class Trabalho extends BaseModel {
    static protected $_tableName = 'trabalhos'; 
}

?>