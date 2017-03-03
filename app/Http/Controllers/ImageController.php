<?php

namespace App\Http\Controllers;

use App\Image;

class ImageController extends Controller
{
    public function init() {
        $myfile = fopen(asset("caffe_rtpose" . DIRECTORY_SEPARATOR . "filenames.txt"), "r") or die("Unable to open file!");
        // 输出单行直到 end-of-file

        while(!feof($myfile)) {
             $image = new Image();
             $line = fgets($myfile);
             echo($line);
             echo("\n");
             $image->filename = $line;
             if (!$image->save()) {
                 alert('loading error');
             }
//             $image->delete();
        }
        fclose($myfile);
    }

    public  function test() {
        $str = "1201.json";
        $path_parts = pathinfo($str);
        echo $path_parts['filename'];
    }

    public function index() {
        return view('index2');
    }

    public function getData(\Illuminate\Http\Request $request) {

        $imageJson = Image::first();
        $str = trim($imageJson->filename);
        $path_parts = pathinfo($str)['filename'];

        $notejson = file_get_contents(asset('caffe_rtpose' . '/' . 'input_json' . '/' . $path_parts . '.json'));
        $imagepath = 'caffe_rtpose' . '/' . 'input' . '/' . $path_parts . '.jpg';

      return response()->json([
            'id' => $imageJson->id,
            'imagePath' => $imagepath,
            'noteJson' => $notejson
        ]);
//        return response()->json(['response' => 'This is post method']);
    }

    public function delData(\Illuminate\Http\Request $request) {
        if ($request->has('id')) {
            $id = $request->input('id');
            $image = Image::find($id);
            if ($image) {
                if($image->delete()) {

                }

                $str = trim($image->filename);
                $path_parts = pathinfo($str)['filename'];

                if ($request->has('data')) {
                    $data = $request->input('data');
                    $myfile = fopen($path_parts. ".json", "w") or die("Unable to open file!");

                    fwrite($myfile,json_encode($data));
                    fclose($myfile);
                }
            }
        }



    }
}

























































