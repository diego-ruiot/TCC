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
            <?php view("includes.navbar", ["page" => "search"]); ?>

            <main class="px-3 h-100 d-inline-flex align-items-center">
                <div class="w-100">
                    <h1>Repositório de TCCs - Campus Hortolândia</h1>
                    <p class="lead">Este projeto é uma ferramenta que armazena arquivos de TCC, e os retorna caso seja pesquisado.</p>
                    <div class="container">
                        <form method="GET" action="/results">
                            <div class="row justify-content-center">
                                <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                                    <input class="form-control form-control-lg mb-3" type="text" id="search" name="q" placeholder="Buscar por TCC, Autor ou Termo" required>
                                </div>
                                <div class="col-12">
                                    <div class="d-inline-flex align-items-center">
                                        <input class="btn btn-primary me-1" type="submit" value="Pesquisar">
                                        <a class="btn btn-primary ms-1" href="/search">Pesquisa Avançada</a>
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