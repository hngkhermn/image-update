<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Upload;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class UploadService
{

    public function postData($request)
    {
        try {
            if ($request->hasFile('file')) {
                $uploadedFiles = $request->file('file');
                $dataList = [];
                // $imageFile = $imageFile ?? $request->file('file');

                foreach ($uploadedFiles as $imageFile) {

                $alternativeName = Str::random(10);

                $encryptedName = encrypt($alternativeName);

                $extension = $imageFile->getClientOriginalExtension();

                $filename = $alternativeName . '.' . $extension;

                $mime = $imageFile->getClientMimeType();

                $size = $imageFile->getSize();

                // Check if the image size is less than 1.5MB (1.5 * 1024 * 1024 bytes)
                // if ($size > 5 * 1024 * 1024) {
                //     throw new Exception('File to big, please use image size < 5MB', 400);
                // }

                $currentTimestamp = now()->toDateTimeString();
                $data = [
                    'name' => $imageFile->getClientOriginalName(),
                    'alternative_name' => $alternativeName,
                    'hash' => $encryptedName,
                    'extension' => $extension,
                    'path' => 'storage/uploads/' . $filename,
                    'mime' => $mime,
                    'url' => url('storage/uploads/' . $filename),
                    'size' => $size,
                    'created_at' => $currentTimestamp,
                    'updated_at' => $currentTimestamp
                ];

                $allowedImageExtensions = ['png', 'jpg', 'jpeg'];
                $allowedOtherFileExtensions = ['pdf', 'docs', 'xlsx', 'mkv'];

                if (in_array($extension, $allowedImageExtensions)) {
                    // Get width and height of the image
                    list($width, $height) = getimagesize($imageFile);
                    $data['width'] = $width;
                    $data['height'] = $height;
                } else if (in_array($extension, $allowedOtherFileExtensions)) {
                    $data['width'] = null;
                    $data['height'] = null;
                } else {
                    return redirect()->back()->with('failed', 'File Not Supported.');
                }

                $dataList[] = $data;

                $imageFile->storeAs('public/uploads', $filename);
            }
            
                // dd($data);
                // $data = Upload::create($data);

                // return $data;
                Upload::insert($dataList);

                return $dataList;
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}