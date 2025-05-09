<?php
class Model {
    private $connect;
    
    public function __construct() {
        try {
            $this->connect = new PDO("mysql:host=localhost;dbname=identificar_responsavel", 'root', 'mysql2024');
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }



    protected $fillable = ['matricula', 'nome', 'curso', 'ano_ingresso'];

    public function responsavel()
    {
        return $this->hasOne(Estudante::class, 'id_estudante'); // simula relação com responsável
    }

    public function salvarComResponsavel($data)
{
    try {
        // Inicia transação
        $this->connect->beginTransaction();

        // Inserir estudante
        $stmt = $this->connect->prepare("
            INSERT INTO estudantes (matricula, nome, curso, ano_ingresso)
            VALUES (:matricula, :nome, :curso, :ano_ingresso)
        ");
        $stmt->execute([
            ':matricula' => $data['matricula'],
            ':nome' => $data['nome'],
            ':curso' => $data['curso'],
            ':ano_ingresso' => $data['ano_ingresso']
        ]);

        $estudanteId = $this->connect->lastInsertId();

        // Inserir responsável
        $stmt2 = $this->connect->prepare("
    INSERT INTO responsaveis (id_estudante, nome, contato, parentesco)
    VALUES (:id_estudante, :nome, :contato, :parentesco)
");

$stmt2->execute([
    ':id_estudante' => $estudanteId,
    ':nome' => $data['responsavel_nome'],
    ':contato' => $data['responsavel_contato'],
    ':parentesco' => $data['responsavel_parentesco']
]);

        // Commit
        $this->connect->commit();
        return true;

    } catch (PDOException $e) {
        $this->connect->rollBack();
        die("Erro ao salvar dados: " . $e->getMessage());
    }}

    

    public function estudante()
    {
        return $this->belongsTo(Estudante::class);
    }

    public function buscarEstudantes() {
        try {
            $stmt = $this->connect->prepare("SELECT id, matricula, nome, curso, ano_ingresso FROM estudantes");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die("Erro ao buscar estudantes: " . $e->getMessage());
        }
    }

    // Adicionar função de deletar no Model.php
    public function deletarEstudante($matricula) {
        try {
            $stmt = $this->connect->prepare("DELETE FROM estudantes WHERE matricula = :matricula");
            $stmt->bindParam(':matricula', $matricula, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao excluir estudante: " . $e->getMessage());
        }
    }

    public function buscarPorMatricula($matricula) {
        try {
            $stmt = $this->connect->prepare("SELECT * FROM estudantes WHERE matricula = :matricula");
            $stmt->bindParam(':matricula', $matricula, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die("Erro ao buscar estudante: " . $e->getMessage());
        }
    }

    public function atualizarEstudante($matricula, $nome, $curso, $ano_ingresso) {
        try {
            $stmt = $this->connect->prepare("
                UPDATE estudantes 
                SET nome = :nome, curso = :curso, ano_ingresso = :ano_ingresso 
                WHERE matricula = :matricula
            ");
            $stmt->bindParam(':matricula', $matricula, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':curso', $curso, PDO::PARAM_STR);
            $stmt->bindParam(':ano_ingresso', $ano_ingresso, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao atualizar estudante: " . $e->getMessage());
        }
    }

    public function buscarEstudantesPorNome($nome) {
        try {
            $stmt = $this->connect->prepare("SELECT * FROM estudantes WHERE nome LIKE :nome");
            $stmt->bindValue(':nome', '%' . $nome . '%', PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die("Erro ao buscar estudantes: " . $e->getMessage());
        }
    }
}

?>