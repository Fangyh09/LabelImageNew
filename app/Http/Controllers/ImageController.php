<?php

namespace App\Http\Controllers;

use App\Image;

class ImageController extends Controller
{
    public function init() {
        echo ("11111111111");
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
}










































