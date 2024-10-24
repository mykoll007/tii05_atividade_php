<?php

class Disciplina {
    private $id;
    private $nome;
    private $cargaHoraria;
    private $alunos; 

    public function __construct($id, $nome, $cargaHoraria) {
        $this->id = $id;
        $this->nome = $nome;
        $this->cargaHoraria = $cargaHoraria;
        $this->alunos = []; 
    }

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getCargaHoraria() {
        return $this->cargaHoraria;
    }

    public function setCargaHoraria($cargaHoraria) {
        $this->cargaHoraria = $cargaHoraria;
    }

    public function getAlunos() {
        return $this->alunos;
    }

    public function addAluno($aluno) {
        $this->alunos[] = $aluno;
    }

    public function removeAluno($matricula) {
        foreach ($this->alunos as $key => $aluno) {
            if ($aluno->getMatricula() === $matricula) {
                unset($this->alunos[$key]);
                return true; 
            }
        }
        return false; 
    }
}
?>
