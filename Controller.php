<?php

require_once __DIR__ . '/../models/Model.php'; // Caminho absoluto, evita erros com níveis de diretórios

class Controller {
private $model;

public function __construct(){
    $this->model = new Model(); 
}

public function create()
{
    return view('estudante.form');
}

public function cadastrar($data) {
    // Validação simples
    if (empty($data['matricula']) || empty($data['nome']) || 
        empty($data['curso']) || empty($data['ano_ingresso']) ||
        empty($data['responsaveis'][0]['nome']) || 
        empty($data['responsaveis'][0]['contato'])
    ) {
        die("Todos os campos obrigatórios devem ser preenchidos.");
    }

    try {
        // Use o método do Model em vez de acessar $connect diretamente
        $this->model->salvarComResponsavel($data);

        header("Location: router.php?rota=cadastrar&sucesso=1");
        exit;
    } catch (Exception $e) {
        die("Erro ao cadastrar: " . $e->getMessage());
    }
}

public function listar() {
    return $this->model->buscarEstudantes();
}

public function deletar() {
    if (isset($_GET['matricula'])) {
        $matricula = $_GET['matricula'];
        $this->model->deletarEstudante($matricula);
    }
    header("Location:router.php?rota=listar"); // Redireciona para a lista de estudantes
    exit;
}

public function buscarPorMatricula($matricula) {
    return $this->model->buscarPorMatricula($matricula);
}

public function atualizar() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $matricula = $_POST["matricula"];
        $nome = $_POST["nome"];
        $curso = $_POST["curso"];
        $ano_ingresso = $_POST["ano_ingresso"];

        $this->model->atualizarEstudante($matricula, $nome, $curso, $ano_ingresso);

        header("Location: router.php?rota=listar&atualizado=1");
        exit;
    }
}
public function buscarPorNome($nome) {
    return $this->model->buscarEstudantesPorNome($nome);
}

}
?>
