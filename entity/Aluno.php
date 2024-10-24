<?php
class Aluno {
    private $matricula;
    private $nome;
    private $disciplinas;
   

    public function __construct($matricula, $nome) {
        $this->matricula = $matricula;
        $this->nome = $nome;
        $this->disciplinas = [];
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }
    public function getDisciplinas() {
        return $this->disciplinas;
    }
    
    public function addDisciplina($disciplina) {
        $this->disciplinas[] = $disciplina;
    }
    
    public function removeDisciplina($disciplinaId) {
        foreach ($this->disciplinas as $key => $disciplina) {
            if ($disciplina->getId() === $disciplinaId) {
                unset($this->disciplinas[$key]);
                return true; 
            }
        }
        return false; 
    }

}
?>
