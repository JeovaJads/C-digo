<?php
require_once 'controllers/Controller.php';
$controller = new Controller();

// Verifica se há um parâmetro de pesquisa
$nomePesquisa = $_GET['nome'] ?? '';
$estudantes = $nomePesquisa ? $controller->buscarPorNome($nomePesquisa) : $controller->listar();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Lista de Estudantes</title>
    <style>
        .search-form {
            margin-bottom: 20px;
        }
        .search-form input {
            padding: 5px;
            width: 300px;
        }
        .search-form button {
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <h2>Lista de Estudantes</h2>

    <!-- Formulário de pesquisa -->
    <form class="search-form" method="get" action="">
        <input type="hidden" name="rota" value="deletar">
        <input type="text" name="nome" placeholder="Pesquisar por nome..." value="<?= htmlspecialchars($nomePesquisa) ?>">
        <button type="submit">Pesquisar</button>
        <?php if ($nomePesquisa): ?>
            <a href="router.php?rota=deletar" style="margin-left: 10px;">Limpar pesquisa</a>
        <?php endif; ?>
    </form>

    <?php if (!empty($estudantes)): ?>
        <table border="1">
            <tr>
                <th>Matrícula</th>
                <th>Nome</th>
                <th>Curso</th>
                <th>Ano de Ingresso</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($estudantes as $estudante): ?>
                <tr>
                    <td><?= htmlspecialchars($estudante->matricula) ?></td>
                    <td><?= htmlspecialchars($estudante->nome) ?></td>
                    <td><?= htmlspecialchars($estudante->curso) ?></td>
                    <td><?= htmlspecialchars($estudante->ano_ingresso) ?></td>
                    <td>
                        <a href="router.php?rota=confirmarDeletar&matricula=<?= $estudante->matricula ?>" 
                           onclick="return confirm('Tem certeza que deseja excluir este estudante?');">
                           Excluir
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p><?= $nomePesquisa ? 'Nenhum estudante encontrado.' : 'Nenhum estudante cadastrado.' ?></p>
    <?php endif; ?>

    <br>
    <button onclick="window.location.href='index.php'">Voltar ao Menu</button>
</body>
</html>