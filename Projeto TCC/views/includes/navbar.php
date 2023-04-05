<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Repositório de TCCs</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <!-- Botão de menu do cabeçalho -->
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent"> <!-- Menu do cabeçalho -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"> <!-- Direciona à página de pesquisa: search.php -->
                    <a class="nav-link <?= (isset($page) && $page == "search") ? "active" : ""; ?>" aria-current="page" href="/">Início</a>
                </li>
                <li class="nav-item"> <!-- Direciona à página de pesquisa avançada: advanced_search.php -->
                    <a class="nav-link <?= (isset($page) && $page == "advanced_search") ? "active" : ""; ?>" href="/search">Pesquisa Avançada</a>
                </li>
                <li class="nav-item"> <!-- Direciona à página de envio de novos TCCs: upload.php -->
                    <a class="nav-link <?= (isset($page) && $page == "upload") ? "active" : ""; ?>" href="/upload">Enviar TCC</a>
                </li>
            </ul>
        </div>
    </div>
</nav>