<?php


namespace App\Http\InterFaces;


interface RepositoryInterFace
{
    public function all();

    public function create(array  $data);


    public function update($id,array $data);

    public function delete($id);

    public function show($id);


}
