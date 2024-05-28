<!DOCTYPE html>
<html lang="en" class="h-100">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Repositório - Procurar</title>
        <?php view("includes.header_includes"); ?>
    </head>
    <body class="d-flex h-100 text-center text-bg-dark">
        <span id="warning-container"><i data-reactroot=""></i></span>
    
        <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
            <?php view("includes.navbar", ["page" => ""]); ?>

            <main class="px-3 h-100 mt-3">
                <div class="w-100">
                    <h1>Resultados</h1>
                    <div class="container">
                        <form method="GET" action="/results">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <table class="table table-dark table-hover">
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
                                            if (isset($results) && (sizeof($results) > 0)) {
                                                foreach($results as $result) {
                                        ?>
                                            <tr>
                                                <td><?= $result->id; ?></td>
                                                <td><?= $result->autor; ?></td>
                                                <td><?= $result->orientador; ?></td>
                                                <td><?= $result->coorientador; ?></td>
                                                <td><a href="/arquivos/<?= $result->arquivo; ?>">Baixar Arquivo</td>
                                                <td><?= $result->titulo; ?></td>
                                                <td><?= $result->ano; ?></td>
                                                <td><?= $result->campus; ?></td>
                                            </tr>
                                        <?php
                                                }
                                            } else {
                                        ?>
                                            <tr>
                                                <td colspan="8">Não foram encontrados registros</td>
                                            </tr>
                                        <?php
                                            }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12">
                                    <div class="d-inline-flex align-items-center">
                                        <a class="btn btn-primary ms-1" href="/">Nova Pesquisa</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
        
        <?php view("includes.footer_includes"); ?>
    </body>
</html>