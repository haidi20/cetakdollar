<?php

namespace App\Http\Controllers;

use App\Models\ImageHasParent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**

     *Store the image for a user.
     *@param \App\Models\User $user - The user model.
     *@param string $image - The base64 encoded image.
     *@param \App\Models\Anywhat $parent - The parent model.
     *@param string $nameModel - The name of the model.
     *@param string $nameTable - The name of the table.
     *@param string|null $addNameFolder - The additional name for the folder.
     *@return object Returns true if the image was successfully stored, false otherwise.
     */
    public function storeSingle($user, $image, $parent, $nameModel, $nameTable, $addNameFolder = null)
    {
        try {
            $pos  = strpos($image, ';');
            $image_extension = explode(':', substr($image, 0, $pos))[1];

            preg_match("/data:image\/(.*?);/", $image, $image_extension); // extract the image extension
            $image = preg_replace('/data:image\/(.*?);base64,/', '', $image); // remove the type part
            $image = str_replace(' ', '+', $image);

            $format = Str::lower($image_extension[1]);

            if (!in_array($format, ['png', 'jpg', 'jpeg'])) {
                return (object) [
                    "success" => false,
                    "message" => 'Gagal upload foto, format Tidak Sesuai',
                    "code" => 500,
                ];
            }

            // if (!Storage::exists('public/images/' . $nameTable . '/cover')) {
            //     Storage::makeDirectory('public/images/' . $nameTable . '/cover');
            // }

            // y_s_d_m
            $imageName = $user->id . Carbon::now()->format('s_i_h_d_m_y') . '.' . $image_extension[1];
            Storage::disk('public')->put('images/' . $nameTable . $addNameFolder . '/' . $imageName, base64_decode($image));
            // Storage::disk('public')->put($imageName, base64_decode($image));

            // proses kompresi
            // $syntax = [
            //     "python3",
            //     "/www/wwwroot/shipyard.aplikasipelayaran.com/storage/app/png_jpg.py",
            //     "/www/wwwroot/shipyard.aplikasipelayaran.com/storage/app/public/" . $imageName
            // ];

            // $process = new Process($syntax);
            // $process->run();

            // if (!$process) {
            //     return (object) [
            //         "success" => false,
            //         "message" => 'Gagal jalankan kompres',
            //         "code" => 500,
            //     ];
            // }

            $path = "app/public/images/{$nameTable}{$addNameFolder}";
            $pathName = $path . "/" . $imageName;

            $this->storeData($user, $parent, $nameModel, $path, $imageName, $pathName, $format);

            return (object) [
                "success" => true,
                "message" => 'Berhasil upload gambar',
                "code" => 200,
            ];
        } catch (\Exception $e) {
            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);


            return (object) [
                "success" => false,
                "message" => 'Gagal upload gambar',
                "code" => 500,
            ];
        }
    }

    private function storeData($user, $parent, $nameModel, $path, $name, $pathName, $format)
    {
        $this->destroy($parent, $nameModel, $pathName);

        $imageHasParent = new ImageHasParent;
        $imageHasParent->parent_id = $parent->id;
        $imageHasParent->parent_model = $nameModel;
        $imageHasParent->path = $path;
        $imageHasParent->name = $name;
        $imageHasParent->path_name = $pathName;
        $imageHasParent->format = $format;
        $imageHasParent->created_by = $user->id;
        $imageHasParent->save();
    }

    private function destroy($parent, $nameModel, $pathName)
    {
        $image = ImageHasParent::where([
            "parent_id" => $parent->id,
            "parent_model" => $nameModel,
            "path_name" => $pathName,
        ])->first();

        if ($image) {
            @unlink(storage_path($image->path_name));

            $image->delete();
        }
    }
}
