<?php
class NotaRepository extends Repository
{
    public function list(): array
    {
        $models = [];
        foreach ($this->query('SELECT * FROM `notas`')->fetch() as $obj) {
            $model = new NotaEntity();
            $model->id = $obj->id;
            $model->titulo = $obj->titulo;
            $model->mensagem = $obj->mensagem;
            $model->registro = $obj->registro;
            $models[] = $model;
        }
        return $models;
    }

    public function select(int $id): ?NotaEntity
    {
        $obj = $this->query('SELECT * FROM `notas` WHERE `id` = :id')->prepare('id', $id)->row();
        if (is_null($obj)) {
            return null;
        } else {
            $model = new NotaEntity();
            $model->id = $obj->id;
            $model->titulo = $obj->titulo;
            $model->mensagem = $obj->mensagem;
            $model->registro = $obj->registro;
            return $model;
        }
    }

    public function insert(NotaEntity $model): ?int
    {
        $query = $this->query('INSERT INTO `notas` (`titulo`, `mensagem`) VALUES (:titulo, :mensagem)');
        $query->prepare('titulo', $model->titulo);
        $query->prepare('mensagem', $model->mensagem);
        return $query->insert();
    }

    public function update(NotaEntity $model): bool
    {
        $query = $this->query('UPDATE `notas` SET `titulo` = :titulo, `mensagem` = :mensagem, `registro` = :registro WHERE `id` = :id');
        $query->prepare('titulo', $model->titulo);
        $query->prepare('mensagem', $model->mensagem);
        $query->prepare('registro', $model->registro);
        $query->prepare('id', $model->id);
        return $query->execute();
    }

    public function delete(int $id): bool
    {
        return $this->query('DELETE FROM `notas` WHERE `id` = :id')->prepare('id', $id)->execute();
    }
}
