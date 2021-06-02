<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
    public function index(){
       $products =  $this->product->all();

       return response()->json($products);
    }
}
