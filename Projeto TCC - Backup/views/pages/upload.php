<!DOCTYPE html>
<html lang="en" class="h-100">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Repositório - Enviar Arquivo</title>
        <?php view("includes.header_includes"); ?>
    </head>
    <body class="d-flex h-100 text-center text-bg-dark">
        <span id="warning-container"><i data-reactroot=""></i></span>
    
        <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
            <?php view("includes.navbar", ["page" => "upload"]); ?>

            <main class="px-3 h-100 d-inline-flex align-items-center">
                <div class="w-100">
                    <div class="container">
                        <?php if (isset($success) || isset($failed)) { ?>
                        <div class="row justify-content-center">
                            <div class="col-12 col-lg-8 mb-3">
                                <?php if (isset($success) && $success) { ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Sucesso!</strong> Seu arquivo de TCC foi enviado para nós e agora faz parte do nosso repositório, muito obrigado por contribuir!
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php 
                                    } 
                                    if (isset($failed) && $failed) {
                                ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Erro!</strong> Por algum motivo desconhecido, seu arquivo não pode ser enviado para nosso sistema, tente novamente em alguns minutos!
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <h1>Enviar Arquivo</h1>
                    <div class="container">
                        <form method="POST" action="/upload" enctype="multipart/form-data">
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-6 col-lg-4 text-start">
                                    <label class="form-label" for="autor">Autor</label>
                                    <input class="form-control form-control-lg mb-3" type="text" id="autor" name="autor" placeholder="Autor" required>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 text-start">
                                    <label class="form-label" for="orientador">Orientador</label>
                                    <input class="form-control form-control-lg mb-3" type="text" id="orientador" name="orientador" placeholder="Orientador" required>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-6 col-lg-4 text-start">
                                    <label class="form-label" for="coorientador">Coorientador</label>
                                    <input class="form-control form-control-lg mb-3" type="text" id="coorientador" name="coorientador" placeholder="Coorientador">
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 text-start">
                                    <label class="form-label" for="titulo">Título</label>
                                    <input class="form-control form-control-lg mb-3" type="text" id="titulo" name="titulo" placeholder="Título" required>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-6 col-lg-4 text-start">
                                    <label class="form-label" for="ano">Ano</label>
                                    <input class="form-control form-control-lg mb-3" type="text" id="ano" name="ano" placeholder="Ano" required>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 text-start">
                                    <label class="form-label" for="campus">Campus</label>
                                    <input class="form-control form-control-lg mb-3" type="text" id="campus" name="campus" placeholder="Campus" required>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-12 col-lg-8 text-start">
                                    <label class="form-label" for="arquivo">Arquivo</label>
                                    <input class="form-control form-control-lg mb-3" type="file" accept="application/pdf" id="arquivo" name="arquivo" placeholder="Arquivo" required>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="d-inline-flex align-items-center">
                                        <input class="btn btn-primary" type="submit" value="Enviar">
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
