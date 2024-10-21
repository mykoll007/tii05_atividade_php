<?php
require_once 'BaseDAO.php';
require_once 'entity/Aluno.php';
require_once 'entity/Disciplina.php';
require_once 'config/Database.php';

class AlunoDAO implements BaseDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM Aluno WHERE matricula = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Aluno($row['matricula'], $row['nome']);
        }
        return null; // Retorna null se não encontrar
    }

    public function getAll()
    {
        $sql = "SELECT * FROM Aluno";
        $stmt = $this->db->query($sql);
        $alunos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $alunos[] = new Aluno($row['matricula'], $row['nome']);
        }
        return $alunos;
    }

    public function create($aluno)
    {
        $sql = "INSERT INTO Aluno (matricula, nome) VALUES (:matricula, :nome)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':matricula', $aluno->getMatricula());
        $stmt->bindParam(':nome', $aluno->getNome());
        $stmt->execute();
    }

    public function update($aluno)
    {
        $sql = "UPDATE Aluno SET nome = :nome WHERE matricula = :matricula";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome', $aluno->getNome());
        $stmt->bindParam(':matricula', $aluno->getMatricula());
        $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM Aluno WHERE matricula = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
    public function read($id)
    {
        return $this->getById($id);
    }

    public function getAlunoWithDisciplinas($alunoID)
    {
        $sql = "
            SELECT aluno.*, disciplina.*
            FROM aluno
            JOIN disciplina_aluno ON aluno.matricula = disciplina_aluno.aluno_id
            JOIN disciplina ON disciplina_aluno.disciplina_id = disciplina.id
            WHERE aluno.matricula = :alunoID
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':alunoID', $alunoID);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$result) return null;

        $aluno = new Aluno($result[0]['matricula'], $result[0]['nome']);
        $aluno->setDisciplinas([]); // Inicializa o array de disciplinas

        foreach ($result as $row) {
            if (isset($row['id'], $row['nome'])) {
                $disciplina = new Disciplina($row['id'], $row['nome'], $row['carga_horaria'] ?? null);
                $aluno->addDisciplina($disciplina);
            }
        }

        return $aluno;
    }    
}
?>
