<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Image;
use File;
use Config;
use Mail;
use Log;
use Str;
use Carbon\Carbon;

class Helper extends Eloquent
{

    public function ImageUpload($file, $id, $type)
    {
        try {
            if ($file != "") {

                ini_set('memory_limit', '-1');
                if ($type == "company") {
                    $destinationPath = Config::get('Constants.COMPANY_IMAGE_PATH') . $id . '/';
                } else if ($type == "article") {
                    $destinationPath = Config::get('Constants.ARTICLE_IMAGE_PATH') . $id . '/';
                }
                $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.webp';


                if (File::exists($destinationPath)) {
                    File::deleteDirectory($destinationPath);
                }

                File::makeDirectory($destinationPath, 0775, true);
                File::makeDirectory($destinationPath . Config::get('Constants.MEDIUM'), 0775);
                File::makeDirectory($destinationPath . Config::get('Constants.SMALL'), 0775);
                File::makeDirectory($destinationPath . Config::get('Constants.THUMB'), 0775);
                File::makeDirectory($destinationPath . Config::get('Constants.LARGE'), 0775);

                $file->move($destinationPath, $filename); // uploading file to given path

                $destinationPathFile = $destinationPath . $filename;
                // root path and original size
                $img = Image::make(file_get_contents($destinationPathFile));
                $img->encode('webp')->save($destinationPath . $filename);


                $img = Image::make(file_get_contents($destinationPathFile))->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->encode('webp', 90)->save($destinationPath . Config::get('Constants.LARGE') . $filename);


                $img = Image::make(file_get_contents($destinationPathFile))->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->encode('webp', 80)->save($destinationPath . Config::get('Constants.MEDIUM') . $filename);


                $img = Image::make(file_get_contents($destinationPathFile))->resize(150, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->encode('webp', 60)->save($destinationPath . Config::get('Constants.SMALL') . $filename);


                $img = Image::make(file_get_contents($destinationPathFile))->resize(75, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->encode('webp', 50)->save($destinationPath . Config::get('Constants.THUMB') . $filename);
                
                return $destinationPathFile;
            } else {
                $filename = "";
                return $filename;
            }

        } catch (\Throwable $th) {
            $thMessage = $th->getMessage();
            Log::debug(print_r('upload err: ', true));
            Log::debug(print_r($thMessage, true));
            return "";
        }
    }

}