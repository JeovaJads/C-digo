<?php
require_once 'controllers/Controller.php';
$controller = new Controller();

$nomePesquisa = $_GET['nome'] ?? '';
$estudantes = $nomePesquisa ? $controller->buscarPorNome($nomePesquisa) : $controller->listar();

if(isset($_GET['ajax_search'])) {
    try {
        $nomePesquisa = $_POST['nome'] ?? '';
        $estudantes = $controller->buscarPorNome($nomePesquisa);
        
        ob_start();
        if (!empty($estudantes)): ?>
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
                            <a href="router.php?rota=formAtualizar&matricula=<?= $estudante->matricula ?>">
                           Atualizar
                        </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p><?= $nomePesquisa ? 'Nenhum estudante encontrado.' : 'Nenhum estudante cadastrado.' ?></p>
        <?php endif;
        
        echo ob_get_clean();
        exit;
        
    } catch(Exception $e) {
        http_response_code(500);
        echo "Erro no servidor: " . $e->getMessage();
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Lista de Estudantes</title>
</head>
<body>
    <h2>Lista de Estudantes</h2>
    
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Digite para pesquisar..." 
               value="<?= htmlspecialchars($nomePesquisa) ?>" autocomplete="off">
        <span id="loading">Carregando...</span>
        <div id="error"></div>
    </div>
    
    <div id="results">
        <?php if (!empty($estudantes)): ?>
            <table>
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
                            <a href="router.php?rota=formAtualizar&matricula=<?= $estudante->matricula ?>">
                           Atualizar
                        </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p><?= $nomePesquisa ? 'Nenhum estudante encontrado.' : 'Nenhum estudante cadastrado.' ?></p>
        <?php endif; ?>
    </div>
    
    <button onclick="window.location.href='index.php'">Voltar ao Menu</button>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const resultsDiv = document.getElementById('results');
    const errorDiv = document.getElementById('error');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        
        fetch(`router.php?rota=buscar_Atualizar&nome=${encodeURIComponent(searchTerm)}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Erro na rede');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                resultsDiv.innerHTML = data.html;
                errorDiv.style.display = 'none';
            } else {
                throw new Error(data.error || 'Erro desconhecido');
            }
        })
        .catch(error => {
            errorDiv.textContent = error.message;
            errorDiv.style.display = 'block';
            console.error('Erro:', error);
        });
    });
});
</script>

</body>
</html>
