<?php

interface BaseDAO
{
    public function create($entity);
    public function read($id);
    public function update($entity);
    public function delete($id);
    public function getAll();
}

?>

