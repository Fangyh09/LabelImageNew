<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Image;

class ImageController extends Controller
{
    public function init() {
            $myfile = fopen(public_path("caffe_rtpose" . DIRECTORY_SEPARATOR . "filenames.txt"), "r") or die("Unable to open file!");
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

    public  function test(Request $request) {
        $name = "null";
        $pwd = "null";
        if ($request->has("username")) {
            $name = $request->get("username");
        }
        if ($request->has("password")) {
            $pwd = $request->get("password");
        }
        info($name);
        info($pwd);

//        $str = "1201.json";
//        $path_parts = pathinfo($str);
//        echo $path_parts['filename'];
    }

    public function index() {
        return view('index2');
    }

    public function getData(\Illuminate\Http\Request $request) {
//        if(isEmpty(Image::all())) {
//            alert("没有图片了。");
//            return response()->json([
//                'ok' => 0
//            ]);
//        }
        $imageJson = Image::first();
        if ($imageJson) {}
        else {
            return response()->json([
                'id' => null,
                'imagePath' => null,
                'noteJson' => null,
                'ok' => 0
            ]);
        }

        $str = trim($imageJson->filename);
//        $str = "000199214.jpg";
        $path_parts = pathinfo($str)['filename'];

        $notejson = file_get_contents(public_path('caffe_rtpose' . '/' . 'input_json' . '/' . $path_parts . '.json'));
        $imagepath = 'caffe_rtpose' . '/' . 'input' . '/' . $path_parts . '.jpg';
      return response()->json([
            'id' => $imageJson->id,
            'imagePath' => $imagepath,
            'noteJson' => $notejson,
            'ok' => 1
        ]);
//        return response()->json(['response' => 'This is post method']);
    }

    public function delData(\Illuminate\Http\Request $request) {
        if ($request->has('id')) {
            $id = $request->input('id');
            $image = Image::find($id);
            if ($image) {
                if($image->delete()) {
                    if(Image::count() == 0) {
                        alert("没有图片了。");
                    }
                }

                $str = trim($image->filename);
                $path_parts = pathinfo($str)['filename'];

                if ($request->has('data')) {
                    $data = $request->input('data');
                    $path = "./caffe_rtpose/myoutput_json/";
                    if (!file_exists($path)) {
                        mkdir($path, 0755);
                    }

                    $myfile = fopen($path . $path_parts. ".json", "w") or die("Unable to open file!");

                    fwrite($myfile,$data);
  //                  fwrite($myfile,json_encode($data));
                    fclose($myfile);
                }
            }
        }



    }
}

























































