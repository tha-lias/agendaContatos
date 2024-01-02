<?php


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $novoContato = [
        'id' => uniqid(),
        'nome' => $_POST['nome'],
        'nomeSocial' => $_POST['nomeSocial'],
        'apelido' => $_POST['apelido'],
        'cpf' => $_POST['cpf'],
        'telefone' => $_POST['tel'],
        'email' => $_POST['email'],
        'favorito' => isset($_POST['fav']) ? $_POST['fav'] : '',
    ];

    $caminhoArquivo = './contatos.txt';

    $contatos = [];
    if (file_exists($caminhoArquivo)) {
        $linhas = file($caminhoArquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($linhas as $linha) {
            $contatos[] = json_decode($linha, true);
        }
    }

    $contatoExistente = false;
    foreach ($contatos as $contato) {
        if ($contato['nome'] === $novoContato['nome'] && $contato['telefone'] === $novoContato['telefone']) {
            $contatoExistente = true;
            break;
        }
    }

    if (!$contatoExistente) {
        $conteudoAtual = file_get_contents($caminhoArquivo);
        $conteudoAtual .= json_encode($novoContato) . PHP_EOL;
        file_put_contents($caminhoArquivo, $conteudoAtual);
    } else {
        echo '<script>alert("Contato já existe!");</script>';
    }

    header('Location: ../pages/contatos.php?sucesso=true');
    exit;
}
