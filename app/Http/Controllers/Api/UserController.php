<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

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
     * @return \Illuminate\Http\JsonResponse
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

        Validator::make($data,[
            'mobile_phone' => 'required',
            'phone' => 'required'
        ])->validate();

        try {
            $data['password'] = bcrypt($data['password']);
            $user = $this->user->create($data);
            $user->profile()->create(
                [
                    'phone' => $data['phone'],
                    'mobile_phone' => $data['mobile_phone']
                ]
            );

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
            $user = $this->user->with('profile')->findOrFail($id);
            $user->profile->social_networks = unserialize($user->profile->social_networks);

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

        Validator::make($data,[
            'profile.mobile_phone' => 'required',
            'profile.phone' => 'required',
        ])->validate();

        try {
            $profile = $data['profile'];
            $profile['social_networks'] = serialize($profile['social_networks']);

            $user = $this->user->findOrFail($id);
            $user->update($data);

            $user->profile()->update($profile);

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
