<!DOCTYPE html>
<html lang="en" class="h-100">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Repositório - Procurar</title>
        <?php view("includes.header_includes"); ?>
        <style>
            .form-vertical .form-floating > input:focus-within {
                z-index: 2 !important;
            }

            .form-vertical .middle-input > input  {
                margin-bottom: -1px !important;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
                border-bottom-right-radius: 0;
                border-bottom-left-radius: 0;
            }

            .form-vertical .first-input > input {
                margin-bottom: -1px !important;
                border-bottom-right-radius: 0;
                border-bottom-left-radius: 0;
            }

            .form-vertical .last-input > input {
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }
        </style>
    </head>
    <body class="d-flex h-100 text-center text-bg-dark">
        <span id="warning-container"><i data-reactroot=""></i></span>

        <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
            <?php view("includes.navbar", ["page" => ""]); ?>

            <main class="d-flex align-items-center py-4 flex-grow-1">
                <div class="col-12 col-sm-8 col-md-6 col-lg-4 m-auto" style="max-width: 430px;">
                    <form class="form-vertical" method="POST">
                        <img class="mb-4" src="/storage/assets/img/tcc_logo.png" alt="" width="150" height="150">
                        <h1 class="h3 mb-3 fw-normal">Crie a sua conta!</h1>

                        <div class="form-floating first-input text-dark">
                            <input type="email" name="email" class="form-control <?= ($data['email']['valid'] ?? true) ? '' : 'is-invalid' ?>" id="floatingInput" value="<?= $data['email']['value'] ?? '' ?>" placeholder="jhon@whick.com" <?php if (!($data['email']['valid'] ?? true)) { ?>data-bs-toggle="tooltip" data-bs-title="<?= $data['email']['valid_message'] ?? "" ?>"<?php } ?>>
                            <label for="floatingInput">E-mail</label>
                        </div>
                        <div class="form-floating middle-input text-dark">
                            <input type="text" name="name" class="form-control <?= ($data['name']['valid'] ?? true) ? '' : 'is-invalid' ?>" id="floatingInput" value="<?= $data['name']['value'] ?? '' ?>" placeholder="John Whick" <?php if (!($data['name']['valid'] ?? true)) { ?>data-bs-toggle="tooltip" data-bs-title="<?= $data['name']['valid_message'] ?? "" ?>"<?php } ?>>
                            <label for="floatingInput">Nome e Sobrenome</label>
                        </div>
                        <div class="form-floating middle-input text-dark">
                            <input type="text" name="campus_id" class="form-control <?= ($data['campus_id']['valid'] ?? true) ? '' : 'is-invalid' ?>" id="floatingInput" value="<?= $data['campus_id']['value'] ?? '' ?>" placeholder="Matrícula" <?php if (!($data['campus_id']['valid'] ?? true)) { ?>data-bs-toggle="tooltip" data-bs-title="<?= $data['campus_id']['valid_message'] ?? "" ?>"<?php } ?>>
                            <label for="floatingInput">Matrícula</label>
                        </div>
                        <div class="form-floating last-input text-dark mb-3">
                            <input type="password" name="password" class="form-control <?= ($data['password']['valid'] ?? true) ? '' : 'is-invalid' ?>" id="floatingPassword" placeholder="Senha (Dog mal)" <?php if (!($data['password']['valid'] ?? true)) { ?>data-bs-toggle="tooltip" data-bs-title="<?= $data['password']['valid_message'] ?? "" ?>"<?php } ?>>
                            <label for="floatingPassword">Senha</label>
                        </div>

                        <div class="form-check text-start my-3">
                            <input class="form-check-input <?= ($data['terms']['valid'] ?? true) ? '' : 'is-invalid' ?>" name="terms" type="checkbox" value="on" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Aceito os Termos de Uso
                            </label>
                            <?php if (!($data['terms']['valid'] ?? true)) { ?>
                            <div id="invalidTermsFeedback" class="invalid-feedback">
                                <?= $data['name']['valid_message'] ?? "" ?>
                            </div>
                            <?php } ?>
                        </div>
                        <button class="btn btn-primary w-100 py-2" type="submit">Registrar</button>
                    </form>
                </div>
            </main>
        </div>

        <?php view("includes.footer_includes"); ?>
    </body>
</html>