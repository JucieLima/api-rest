<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private User $user;

    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->user->paginate(10);

        return response()->json($user);
    }

    /**
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();

        if(!$request->has('password') || !$request->get('password')){
            $message = new ApiMessages('É nesessário informar uma senha!');
            return response()->json(
                [
                    'error' => $message->getMessage()
                ], 401);
        }

        try {
            $data['password'] = bcrypt($data['password']);
            $this->user->create($data);

            return response()->json([
                'data' => [
                    'message' => 'Usuário cadastrado com sucesso!'
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $user = $this->user->findOrFail($id);

            $user->update();

            return response()->json([
                'data' => $user
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
     * @param UserRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, $id)
    {
        $data = $request->all();
        if(!$request->has('password') && !$request->get('password')){
            $data['password'] = bcrypt($data['password']);
        }else{
            unset($data['password']);
        }

        try {
            $user = $this->user->findOrFail($id);

            $user->update($data);

            return response()->json([
                'data' => [
                    'message' => 'Usuário atualizado com sucesso!'
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

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $user = $this->user->findOrFail($id);

            $user->delete();

            return response()->json([
                'data' => [
                    'message' => 'Usuário removido com sucesso!'
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
