<?php


namespace App\Repositores;
use Illuminate\Database\Eloquent\Model;


use App\Http\InterFaces\RepositoryInterFace as RepositoryInterFaceAlias;

class Repository implements RepositoryInterFaceAlias
{

    protected $model;

    // Constructor to bind model to repo
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();

    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update( $id ,array $data)
    {
        $record = $this->find($id);
        return $record->update($data);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function show($id)
    {
        return $this->model-findOrFail($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->model->with($relations);
    }


}
