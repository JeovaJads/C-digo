<?php
require_once 'controllers/Controller.php';

$controller = new Controller();

$rota = $_GET['rota'] ?? 'listar'; // Se não tiver definida, define com a rota padrão

// Processa requisições POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($rota == 'cadastrar') {
        $controller->cadastrar($_POST);
    }elseif($rota == 'atualizar'){
        $controller->atualizar();
    }
    exit;
}

// Processa requisições GET
switch($rota) {
    case 'cadastrar':
        require_once 'views/estudanteCadastro.php';
        break;
    case 'deletar':
        require_once 'views/listarDeletar.php';
        break;
    case 'atualizar':
        require_once 'views/listarAtualizar.php';
        break; 
    case 'formAtualizar': 
        require_once 'views/formAtualizar.php';
        break; 
    case 'confirmarDeletar':
        $controller->deletar(); // Aqui deletamos o estudante
        exit; // Evita que carregue outros arquivos após a execução
    case 'listar':
    default:
        require_once 'views/listarEstudantes.php';
        break;
    // No switch case, modifique o caso 'deletar':
    case 'deletar':
        require_once 'views/listarDeletar.php';
        break;   
    case 'buscarSugestoes':
        header('Content-Type: application/json');
        echo json_encode($controller->buscarSugestoesNome($_GET['term']));
        exit;
        break;
}
?>