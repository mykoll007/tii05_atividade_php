<?php
require_once 'BaseDAO.php';
require_once 'entity/Disciplina.php';
require_once 'entity/Aluno.php';
require_once 'entity/Professor.php';
require_once 'config/Database.php';

class DisciplinaDAO implements BaseDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM Disciplina WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Disciplina($row['id'], $row['nome'], $row['carga_horaria']);
        }
        return null; 
    }

    public function getAll()
    {
        $sql = "SELECT * FROM Disciplina";
        $stmt = $this->db->query($sql);
        $disciplinas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $disciplinas[] = new Disciplina($row['id'], $row['nome'], $row['carga_horaria']);
        }
        return $disciplinas;
    }

    public function create($disciplina)
    {
        $sql = "INSERT INTO Disciplina (nome, carga_horaria) VALUES (:nome, :carga_horaria)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome', $disciplina->getNome());
        $stmt->bindParam(':carga_horaria', $disciplina->getCargaHoraria());
        $stmt->execute();
    }

    public function update($disciplina)
    {
        $sql = "UPDATE Disciplina SET nome = :nome, carga_horaria = :carga_horaria WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome', $disciplina->getNome());
        $stmt->bindParam(':carga_horaria', $disciplina->getCargaHoraria());
        $stmt->bindParam(':id', $disciplina->getId());
        $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM Disciplina WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function getDisciplinaWithAlunos($disciplinaID)
    {
        $sql = "
            SELECT disciplina.*, aluno.*
            FROM disciplina
            JOIN disciplina_aluno ON disciplina.id = disciplina_aluno.disciplina_id
            JOIN aluno ON disciplina_aluno.aluno_id = aluno.matricula
            WHERE disciplina.id = :disciplinaID
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':disciplinaID', $disciplinaID);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$result) return null;

        $disciplina = new Disciplina($result[0]['id'], $result[0]['nome'], $result[0]['carga_horaria']);
        $disciplina->setAlunos([]); 

        foreach ($result as $row) {
            $aluno = new Aluno($row['matricula'], $row['nome']);
            $disciplina->addAluno($aluno);
        }

        return $disciplina;
    }    

    public function getProfessoresForDisciplina($disciplinaID)
    {
        $sql = "SELECT p.id, p.nome FROM professor p
            WHERE p.disciplina_id = :disciplinaID";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':disciplinaID', $disciplinaID);
        $stmt->execute();

        $professores = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $professores[] = new Professor($row['id'], $row['nome'], $disciplinaID);
        }

        return $professores;
    }

   
    public function read($id) {
        return $this->getById($id);
    }
}
?>
