<?php

namespace Tcc;

include(__DIR__."/app/autoload.php"); /*Carrega classes à aplicação */

/* Cria o objeto loader, instância da classe autoLoad, após isso, chama o diretório através da função load do objeto.*/
$loader = new \Tcc\AutoLoad();
$loader->load(__DIR__);


require_once(__DIR__."/app/routes.php"); /* inclui as rotas da aplicação */

?>