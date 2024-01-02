<?php
$contatos = [];
$caminhoArquivo = 'includes/contatos.txt';

if (file_exists($caminhoArquivo)) {
    $linhas = file($caminhoArquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as $linha) {
        $contatos[] = json_decode($linha, true);
    }
}

function buscarContatosPorNome($contatos, $nomeBusca)
{
    $contatosEncontrados = [];

    foreach ($contatos as $contato) {
        if (stripos($contato['nome'], $nomeBusca) !== false) {
            $contatosEncontrados[] = $contato;
        }
    }

    return $contatosEncontrados;
}

function exibirNome($contato)
{
    if (!empty($contato['nome_social'])) {
        $nomeCompleto = $contato['nome_social'];
    } else {
        $nomeCompleto = $contato['nome'];
    }

    if (!empty($contato['apelido'])) {
        $nomeCompleto .= ' (' . $contato['apelido'] . ')';
    }

    return $nomeCompleto;
}

$contatosFiltrados = [];
$limit = 5;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalContatos = count($contatos);
$totalPages = ceil($totalContatos / $limit);
$offset = ($currentPage - 1) * $limit;

if (isset($_GET['encontrar'])) {
    $busca = $_GET['encontrar'];

    $contatosFiltrados = buscarContatosPorNome($contatos, $busca);
} else {
    $contatosFiltrados = $contatos;
}

$contatosFiltrados = array_slice($contatosFiltrados, $offset, $limit);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Meus Contatos</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/style.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/script.js"></script>

    <!-- Importar font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="container">
        <header onclick="window.location.href='../index.php'">
            <img src="./assets/images/logotipo.png" class="logo" />
            <div class="titulo-header">
                <h1>Contatos Favoritos</h1>
            </div>
        </header>

        <nav class="navbar" id="sidebar">
            <ul>
                <li onclick="window.location.href='index.php'">
                    <i class="fa-solid fa-house"></i>
                    <span class="lbl-menu hide"> Home</span>
                </li>
                <li onclick="window.location.href='pages/register.html'">
                    <i class="fa-regular fa-address-card"></i>
                    <span class="lbl-menu hide"> Novo Contato</span>
                </li>
                <li onclick="window.location.href='pages/contatos.php'">
                    <i class="fa-regular fa-address-book"></i>
                    <span class="lbl-menu hide"> Meus Contatos</span>
                </li>
            </ul>
        </nav>

        <div class="main-register">
            <form method="get" class="form-search">
                <div class="search-box">
                    <div class="search-field">
                        <input placeholder="Filtrar por nome..." class="inputSearch" type="text" name="encontrar" value="<?php echo isset($_GET['encontrar']) ? $_GET['encontrar'] : ''; ?>">
                        <div class="search-box-icon">
                            <button class="btn-icon-content" type="submit">
                                <i class="fa-solid fa-magnifying-glass icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="box-contatos" id='favs'>
                <?php if (!empty($contatosFiltrados) || isset($_GET['encontrar'])) : ?>
                    <?php if (!empty($contatosFiltrados)) : ?>
                        <?php foreach ($contatosFiltrados as $contato) : ?>
                            <?php if ($contato['favorito']) : ?>
                                <div class="card-contato" onclick="abreContato(this);">
                                    <div class="card_load"><i class="fa-solid fa-user"></i></div>
                                    <div class="box-infos">
                                        <div class="card_load_extreme_title"><?php echo exibirNome($contato); ?></div>
                                        <div class="card_load_extreme_descripion">
                                            <p><i class="fa-solid fa-phone"></i> <span><?php echo $contato['telefone']; ?></span></p>
                                            <p class="truncate-email">
                                                <i class="fa-solid fa-envelope"></i>
                                                <span class="email-content"><?php echo $contato['email']; ?></span>
                                            </p>
                                            <?php if ($contato['cpf'] != "") { ?>
                                                <p><i class="fa-solid fa-id-card"></i> <span><?php echo $contato['cpf']; ?></span></p>
                                            <?php } ?>
                                            <?php if ($contato['favorito']) : ?>
                                                <p><i class="fa-solid fa-heart"></i> Favorito</p>
                                            <?php else : ?>
                                                <p><i class="fa-regular fa-heart"></i> Não adicionado aos Favoritos</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="box-sad">
                            <p>Nenhum contato favorito encontrado para a busca: <?php echo $busca; ?></p>
                            <img src="../assets/images/animate-sad.svg" alt="">
                        </div>
                    <?php endif; ?>
                    <div id="modal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="fechaModal()">&times;</span>
                            <div class="card-contato-modal"></div>
                            <div class="btn">
                                <button class="btn-padrao btn-excluir " onclick="excluirContato(this)">Excluir esse contato</button>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="box-sad">
                        <p>Nenhum contato favorito encontrado.</p>
                        <img src="../assets/images/animate-sad.svg" alt="">
                    </div>
                <?php endif; ?>

            </div>

            <div class="pagination">
                <?php if ($totalPages > 1) : ?>
                    <?php if ($currentPage > 1) : ?>
                        <a href="?page=<?php echo $currentPage - 1; ?>">Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <a href="?page=<?php echo $i; ?>" <?php echo ($i == $currentPage) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages) : ?>
                        <a href="?page=<?php echo $currentPage + 1; ?>">Próxima</a>
                    <?php endif; ?>

                <?php endif; ?>
            </div>
            <div class="box-img">
                <img src="assets\images\friends.svg" alt="">
            </div>
        </div>
    </div>
</body>

</html>