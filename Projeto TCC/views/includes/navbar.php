<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Repositório de TCCs</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= (isset($page) && $page == "search") ? "active" : ""; ?>" aria-current="page" href="/">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($page) && $page == "advanced_search") ? "active" : ""; ?>" href="/search">Pesquisa Avançada</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (!($_SESSION["is_logged"] ?? false)) { ?>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($page) && $page == "login") ? "active" : ""; ?>" href="/login">Entrar</a>
                </li>
                <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($page) && $page == "upload") ? "active" : ""; ?>" href="/upload">Enviar TCC</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($page) && $page == "my-files") ? "active" : ""; ?>" href="/my-files">Meus Documentos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($page) && $page == "logout") ? "active" : ""; ?>" href="/logout">Sair</a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>