<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\RealStatePhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RealStatePhotoController extends Controller
{
    private $realStatePhoto;

    /**
     * RealStatePhotoController constructor.
     * @param RealStatePhoto $realStatePhoto
     */
    public function __construct(RealStatePhoto $realStatePhoto)
    {
        $this->realStatePhoto = $realStatePhoto;
    }

    /**
     * @param $photoId
     * @param $realStateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function setThumb($photoId, $realStateId): JsonResponse
    {
        try{
            $photo = $this->realStatePhoto
                ->where('real_state_id', $realStateId)
                ->where('is_thumb', true)->first();

            if($photo) {
                $photo->is_thumb = false;
                $photo->save();
            }

            $photo = $this->realStatePhoto->find($photoId);
            $photo->is_thumb = true;
            $photo->save();

            return response()->json([
                'data' => 'Thumb atualizada com sucesso!'
            ]);
        }catch (\Exception $exception){
            $message = new ApiMessages($exception->getMessage());
            return response()->json($message->getMessage(), 500);
        }
    }

    public function remove(int $photoId)
    {
        try{

            $photo = $this->realStatePhoto->find($photoId);

            if($photo->is_thumb){
                $newThumb = $this->realStatePhoto
                    ->where('id', '!=', $photo->id)
                    ->where('real_state_id', $photo->real_state_id)
                    ->first();
                if($newThumb){
                    $newThumb->is_thumb = true;
                    $newThumb->save();
                }

            }

            if($photo){
                Storage::disk('public')->delete($photo->photo);
                $photo->delete();
            }

            return response()->json([
                'data' => 'Foto removida com sucesso!'
            ]);
        }catch (\Exception $exception){
            $message = new ApiMessages($exception->getMessage());
            return response()->json($message->getMessage(), 500);
        }
    }

}
