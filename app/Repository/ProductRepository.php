<?php


namespace App\Repository;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductRepository
{
    /**
     * @var Model
     */
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectFilter($filters)
    {
        $this->model = $this->model->selectRaw($filters);
    }

    public function selectConditions($conditions){
        $expressions = explode(';', $conditions);

        foreach ($expressions as $expression){
            $filter = explode(':', $expression);
            $this->model = $this->model->where($filter[0], $filter[1], $filter[2]);
        }
    }

    public function getResult(){
        return $this->model;
    }
}
