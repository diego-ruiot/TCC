<!DOCTYPE html>
<html lang="en" class="h-100">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Repositório - Pesquisa Avançada</title>
        <?php view("includes.header_includes"); ?>
    </head>
    <body class="d-flex h-100 text-center text-bg-dark">
        <span id="warning-container"><i data-reactroot=""></i></span>
    
        <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
            <?php view("includes.navbar", ["page" => "advanced_search"]); ?>

            <main class="px-3 h-100 d-inline-flex align-items-center">
                <div class="w-100">
                    <h1>Pesquisa Avançada</h1>
                    <div class="container">
                        <form method="GET" action="/results">
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-6 col-lg-4 text-start">
                                    <label class="form-label" for="autor">Autor</label>
                                    <input class="form-control form-control-lg mb-3" type="text" id="autor" name="a" placeholder="Buscar por Autor">
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 text-start">
                                    <label class="form-label" for="orientador">Orientador</label>
                                    <input class="form-control form-control-lg mb-3" type="text" id="orientador" name="o" placeholder="Buscar por Orientador">
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-6 col-lg-4 text-start">
                                    <label class="form-label" for="termo">Termo</label>
                                    <input class="form-control form-control-lg mb-3" type="text" id="termo" name="p" placeholder="Buscar por Termo">
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 text-start">
                                    <label class="form-label" for="outro">Outro</label>
                                    <input class="form-control form-control-lg mb-3" type="text" id="outro" name="q" placeholder="Buscar por Outra coisa">
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="d-inline-flex align-items-center">
                                        <input class="btn btn-primary" type="submit" value="Pesquisar">
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