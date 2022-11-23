<?php
//Variaveis de configurações globais do arquivo
$host = "localhost";
$db   = "repositorio_tcc";
$user = "root";
$pass = "";
$url  = "http://localhost";

// conecta ao banco de dados
$con = mysqli_connect($host, $user, $pass, $db) or die('ERRO ao conectar com o Banco de Dados');

//pegar os parametros e salva em variaveis
$busca_geral = $_GET['q'] ?? '';
$busca_autor = $_GET['a'] ?? '';
$busca_orientador = $_GET['o'] ?? '';
$busca_titulo = $_GET['t'] ?? '';
$busca_termo = $_GET['p'] ?? '';

//monta a consulta base
$query = "
SELECT t.id,
       t.autor,
       t.orientador,
       t.coorientador,
       t.arquivo,
       t.titulo,
       t.ano,
       t.campus
FROM trabalhos t
WHERE UPPER(CONCAT('|', CONCAT_WS('|', t.id, t.autor, t.orientador, t.coorientador, t.arquivo, t.titulo, t.ano, t.campus), '|')) like UPPER('%|%". $busca_geral ."%|%')";

//Adiciona as condições extras caso os parametros enviados não estejam vazios
if ($busca_autor !== "") {
    $query .= PHP_EOL ."and (UPPER(t.autor) like UPPER('%". $busca_autor ."%'))";
}

if ($busca_orientador !== "") {
    $query .= PHP_EOL ."and (UPPER(t.orientador) like UPPER('%". $busca_orientador ."%'))";
}

if ($busca_titulo !== "") {
    $query .= PHP_EOL ."and (UPPER(t.titulo) like UPPER('%". $busca_titulo ."%'))";
}

//Inclusive busca termo a termo no parametro p
if ($busca_termo !== "") {
    $primeira = true;
    $palavras = explode(' ', $busca_termo);
    $query .= PHP_EOL ."and (";
    foreach($palavras as $palavra) {
        if (!$primeira) {
            $query .= PHP_EOL ."or ";
        }
        $query .= PHP_EOL ."(UPPER(cast(t.palavras AS CHAR(99999) CHARACTER SET utf8)) like UPPER('%". $palavra ."%'))";
        $primeira = false;
    }
    $query .= PHP_EOL .")";
}

//Executa a consulta e armazena os resultados numa variável
$results = mysqli_query($con, $query);

//Exibe os dados em uma tabela
?>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Autor</th>
            <th>Orientador</th>
            <th>Coorientador</th>
            <th>Arquivo</th>
            <th>Título</th>
            <th>Ano</th>
            <th>Campus</th>
        </tr>
    </thead>
    <tbody>
<?php
foreach($results as $result) {
?>
        <tr>
            <td><?= $result['id']; ?></td>
            <td><?= $result['autor']; ?></td>
            <td><?= $result['orientador']; ?></td>
            <td><?= $result['coorientador']; ?></td>
            <td><a target="_blank" href="<?= $url; ?>/arquivos/<?= $result['arquivo']; ?>"><?= $result['arquivo']; ?></td>
            <td><?= $result['titulo']; ?></td>
            <td><?= $result['ano']; ?></td>
            <td><?= $result['campus']; ?></td>
        </tr>
<?php

}

?>

    </tbody>

</table>