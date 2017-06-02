<?php

namespace App\Http\Controllers;

use App\Image;

class ImageController extends Controller
{
    public function getIP() {
        dd($_SERVER['SERVER_ADDR']);
    }

    public function copyfiles($file1,$file2){
        $contentx = file_get_contents($file1);
        $openedfile = fopen($file2, "w");
        fwrite($openedfile, $contentx);
        fclose($openedfile);
        if ($contentx === FALSE) {
            $status=false;
        }else $status=true;
        return $status;
    }
    public function alarm(\Illuminate\Http\Request $request) {
        if ($request->has('id')) {
            $id = $request->input('id');
            $image = Image::find($id);
            if ($image) {
                if($image->delete()) {
//                    if(Image::count() == 0) {
//                        alert("没有图片了。");
//                    }
                }

                $str = trim($image->filename);
                $path_parts = pathinfo($str)['filename'];

                //if ($request->has('data')) {
                  //  $data = $request->input('data');
                    $alarmJsonPath = "./caffe_rtpose/alarm_json/";
                    $alarmPicPath = "./caffe_rtpose/alarm_pic/";
                    $inputPath = "./caffe_rtpose/input/";
                    $inputJsonPath = "./caffe_rtpose/input_json/";
                    if (!file_exists($alarmJsonPath)) {
                        mkdir($alarmJsonPath, 0755);
                    }

                    if (!file_exists($alarmPicPath)) {
                        mkdir($alarmPicPath, 0755);
                    }
                     $this->copyfiles($inputJsonPath . $path_parts. ".json",$alarmJsonPath . $path_parts. ".json");
                     $this->copyfiles($inputPath . $path_parts. ".jpg",$alarmPicPath . $path_parts. ".jpg");
//                    $myfileJson = fopen($alarmJsonPath . $path_parts. ".json", "w") or die("Unable to open file!");
//                    $myfilePic = fopen($alarmPicPath . $path_parts. ".jpg", "w") or die("Unable to open file!");
//
//                    $data = fread($inputPath . $path_parts. ".jpg", "r");
//                    fwrite($myfilePic,$data);
//
//                    $data = fread($inputJsonPath . $path_parts. ".json", "r");
//                    fwrite($myfileJson,$data);
//
//                    //                  fwrite($myfile,json_encode($data));
//                    fclose($myfileJson);
//                    fclose($myfilePic);
                //}
            }
        }
    }


    public function init() {
        echo ("111");
        var_dump("111");
           $myfile = fopen(public_path("caffe_rtpose" . DIRECTORY_SEPARATOR . "filenames.txt"), "r") ; //or die("Unable to open file!");
           var_dump($myfile);

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

























































