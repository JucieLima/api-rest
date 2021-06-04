<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * @var Category
     */
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $category = $this->category->paginate('10');

        return response()->json($category, 200);
    }

    /**
     * @param CategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->all();

        try{

            $category = $this->category->create($data);

            return response()->json([
                'data' => [
                    'msg' => 'Categoria cadastrada com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try{

            $category = $this->category->findOrFail($id);

            return response()->json([
                'data' => $category
            ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * @param CategoryRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $request, $id)
    {
        $data = $request->all();

        try{

            $category = $this->category->findOrFail($id);
            $category->update($data);

            return response()->json([
                'data' => [
                    'msg' => 'Categoria atualizada com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try{

            $category = $this->category->findOrFail($id);
            $category->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Categoria removida com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
