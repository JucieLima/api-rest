<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\Models\RealState;

class RealStateController extends Controller
{
    /**
     * @var RealState
     */
    private $realState;

    /**
     * RealStateController constructor.
     * @param RealState $realState
     */
    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $realState = $this->realState->paginate(10);

        return response()->json($realState);
    }

    public function show($id)
    {
        try {
            $realState = $this->realState->findOrFail($id);

            $realState->update();

            return response()->json([
                'data' => $realState
            ]);
        } catch (\Exception $exception) {
            $message = new ApiMessages($exception->getMessage(), [], $exception->getFile(), $exception->getLine());
            return response()->json(
                [
                    'error' => $message->getMessage()
                ], 500);
        }
    }

    /**
     * @param RealStateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RealStateRequest $request)
    {
        $data = $request->all();

        try {

            $realState = $this->realState->create($data);

            if(isset($data['categories']) && count($data['categories'])){
                $realState->categories()->sync($data['categories']);
            }

            return response()->json([
                'data' => [
                    'message' => 'Imóvel cadastrado com sucesso!'
                ]
            ], 201);
        } catch (\Exception $exception) {
            $message = new ApiMessages($exception->getMessage(), [], $exception->getFile(), $exception->getLine());
            return response()->json(
                [
                    'error' => $message->getMessage()
                ], 500);
        }
    }

    /**
     * @param $id
     * @param RealStateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, RealStateRequest $request)
    {
        $data = $request->all();
        try {
            $realState = $this->realState->findOrFail($id);

            $realState->update($data);

            if(isset($data['categories']) && count($data['categories'])){
                $realState->categories()->sync($data['categories']);
            }

            return response()->json([
                'data' => [
                    'message' => 'Imóvel atualizado com sucesso!'
                ]
            ]);
        } catch (\Exception $exception) {
            $message = new ApiMessages($exception->getMessage(), [], $exception->getFile(), $exception->getLine());
            return response()->json(
                [
                    'error' => $message->getMessage()
                ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $realState = $this->realState->findOrFail($id);

            $realState->delete();

            return response()->json([
                'data' => [
                    'message' => 'Imóvel removido com sucesso!'
                ]
            ]);
        } catch (\Exception $exception) {
            $message = new ApiMessages($exception->getMessage(), [], $exception->getFile(), $exception->getLine());
            return response()->json(
                [
                    'error' => $message->getMessage()
                ], 500);
        }
    }
}
