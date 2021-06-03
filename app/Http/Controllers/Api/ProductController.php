<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @var Product
     */
    private $product;

    /**
     * ProductController constructor.
     */
    public function __construct(Product $product)
    {

        $this->product = $product;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $products = $this->product;


        //Filtragem de campos
        if ($request->has('fields')) {
            $fields = $request->get('fields');
            $expressions = $products->selectRaw($fields);

            foreach ($expressions as $expression){
                $filter = explode(':', $expression);
                $products = $products->where($filter[0], $filter[1], $filter[2]);
            }

        }
        //Condições de filtragem
        if ($request->has("conditions")) {
            $conditions = explode(';', $request->get('conditions'));
        }

//       return response()->json($products);
        return new ProductCollection($products->paginate(10));
    }


    public function save(Request $request)
    {
        $data = $request->all();
        $product = $this->product->create($data);
        return response()->json($product);
    }

    public function show($id)
    {
        $product = $this->product->find($id);

        //return response()->json($product);
        return new ProductResource($product);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $product = $this->product->find($data['id']);
        $product->update($data);

        return response()->json($product);
    }

    public function delete($id)
    {
        $product = $this->product->find($id);
        $product->delete();

        return response()->json(['data' => ['message' => 'Produto removido com sucesso!']]);
    }
}
