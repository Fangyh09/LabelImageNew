<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Image Annotations</title>
    <link href="{{asset('static/css/bootstrap.min.css')}}" rel="stylesheet">
    <style type="text/css" media="all">
        @import "{{asset('static/css/annotation.css')}}";
    </style>
    <style type="text/css">
        #canvas-wrap { position:relative } /* Make this a positioned parent */
        #overlay     { position:absolute; top:0px; left:0px; }

    </style>
    <script type="text/javascript" src="{{asset('static/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/js/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/js/jquery.annotate.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/js/bootstrap.min.js')}}"></script>
    <script language="javascript">
        var simple = '<?php echo $_SERVER['SERVER_ADDR']; ?>';

        var basePath = "http://202.121.182.216:20013/";
        filterId = 0;
        //offset changed, so temp solution.
        var outerTop = 0;
        var outerLeft = 0;
        var outerWidth = 0;
        var outerHeight = 0;
        var imageDbId = 0;
         originNotes = null;
//        var myPicNames = new Array("images/pic1.jpg", "images/pic2.png", "images/pic3.png");
        //        var tmpNote1 = JSON.parse('[{"top":22,"left":22,"text":"a","groupId":0,"partId":0},{"top":22,"left":28,"text":"b","groupId":0,"partId":1},{"top":18,"left":22,"text":"c","groupId":1,"partId":0},{"top":67,"left":16,"text":"d","groupId":1,"partId":5}]');
        //        var tmpNote2 = JSON.parse('[{"top":82,"left":22,"text":"a","groupId":0,"partId":0},{"top":22,"left":68,"text":"b","groupId":0,"partId":1},{"top":58,"left":22,"text":"c","groupId":1,"partId":0},{"top":67,"left":76,"text":"d","groupId":1,"partId":1}]');
        //        var tmpNote3 = JSON.parse('[{"top":42,"left":22,"text":"a","groupId":0,"partId":0},{"top":52,"left":28,"text":"b","groupId":0,"partId":1},{"top":78,"left":22,"text":"c","groupId":1,"partId":0},{"top":67,"left":36,"text":"d","groupId":1,"partId":5}]');
        //        var myNotes = new Array(tmpNote1, tmpNote2, tmpNote3);
        //        var arrIdx = 0;
//        var readFileName = "D:/software/xampp/htdocs/caffe_rtpose/input_json/filenames.txt";

        initOuterTop_Left = function () {
            outerTop = $("#toAnnotate").offset().top;
            outerLeft = $("#toAnnotate").offset().left;
//            alert(outerHeight);
        }

        nextPerson = function () {
            //to show next person
            filterId = parseInt(filterId) + 1;
            //reload annotations
            var result = $("#toAnnotate").annotateImage.reload();
            if (!result) {
                $('#nextPerson_btn').prop('disabled', true);
                $('#ok_btn').prop('disabled', false);
            }
            $.fn.firstDrawLines(filterId);
//            $.fn.firstDrawLines(filterId,originNotes);
        };

        print = function () {
            var annotations = $.fn.annotateImage.getAnnotations();
            annotations = JSON.parse(annotations);
            //console.log(annotations.toString());
//            alert((jsonRevWrapper(annotations)));
            return jsonRevWrapper(annotations);
//            return annotations;
//            console.log(jsonRevWrapper(annotations));


        }

        do_finish_once = function (noteJson) {
            filterId = 0;
            $('#nextPerson_btn').prop('disabled', false);
            $('#ok_btn').prop('disabled', true);
            $.fn.annotateImage.removeCanvas();


            $("#toAnnotate").annotateImage({
                notes: noteJson,
                top: outerTop,
                left: outerLeft,
                filterId: filterId

            });
            $.fn.firstDrawLines(filterId);
//             $.fn.firstDrawLines(filterId,originNotes);
//            console.log($.fn.annotateImage.getAnnotations());
        }

        alarm = function() {

            $.ajax({
                url: basePath + "alarm",
                type: "post",
                data: {
                    id: imageDbId
                }
            }).then(function() {
                $.getJSON( basePath + "getData", function( data ) {
                    var ok = data['ok'];
                    if (parseInt(ok) == 0) {
                        alert("图片加载完毕。");
                        return;
                    }
                    var imagePath = data['imagePath'];
                    console.log(data['noteJson']);
                    originNotes = data['noteJson'];
                    imageDbId = data['id'];
                    var noteJson = jsonWrapper(data['noteJson']);
//                var modifiedNoteJson = jsonWrapper(noteJson);
//                console.log(modifiedNoteJson);
//                    console.log(noteJson);
                    nextPictureWithPara(imagePath, noteJson);
                });

            });

        }


        nextPicture = function () {

            $.ajax({
                url: basePath + "delData",
                type: "post",
                data: {
                    id: imageDbId,
                    data: print()
                }
            }).then(function() {
                $.getJSON( basePath + "getData", function( data ) {
                    var ok = data['ok'];
                    if (parseInt(ok) == 0) {
                        alert("图片加载完毕。");
                        return;
                    }
                    var imagePath = data['imagePath'];
                    console.log(data['noteJson']);
                    originNotes = data['noteJson'];
                    imageDbId = data['id'];
                    var noteJson = jsonWrapper(data['noteJson']);
//                var modifiedNoteJson = jsonWrapper(noteJson);
//                console.log(modifiedNoteJson);
//                    console.log(noteJson);
                    nextPictureWithPara(imagePath, noteJson);
                });

            });
        }
        
        nextPictureWithPara = function (imagePath, noteJson) {
            var logo = document.getElementById('toAnnotate');
            $('#toAnnotate').attr("src", basePath + imagePath);
            logo.onload = function () {
                do_finish_once(noteJson);
            };
        }

        jsonWrapper = function (noteJson) {
            var jsonObj = JSON.parse(noteJson);
            var bodies = jsonObj['bodies'];
            var jsonArr = [];
            for (var idx in bodies) {
                var len = bodies[idx]['joints'].length;
//                var jsonArrOnePerson = [];
                for (var jdx = 0; jdx < len; jdx = jdx + 3) {
                    jsonArr.push({
                        left: bodies[idx]['joints'][jdx],
                        top: bodies[idx]['joints'][jdx + 1],
                        disable: bodies[idx]['joints'][jdx + 2] == 0, // need to consider == & ===
                        groupId: idx,
                        partId: jdx / 3,
                    });
                }
//                jsonArr.push(jsonArrOnePerson);
            }
            noteJson = jsonArr;
            return noteJson;
        }
        
        jsonRevWrapper = function (revNoteJson) {

            var res = clone(JSON.parse(originNotes));
            for (var idx in revNoteJson) {
                var top = revNoteJson[idx]['top'];
                var left = revNoteJson[idx]['left'];
                var groupId = revNoteJson[idx]['groupId'];
                var partId = revNoteJson[idx]['partId'];
                var top = revNoteJson[idx]['top'];
                res['bodies'][groupId]['joints'][partId * 3] = left;
                res['bodies'][groupId]['joints'][partId * 3 + 1] = top;
            }
            return JSON.stringify(res);
        }

        $(window).load(function () {
            initOuterTop_Left();
            nextPicture();
            var html5Canvas = $('#drawCanvas');
            var canvasDiv = $('#overlay');

            if (html5Canvas.length > 0) {
                html5Canvas[0].width = canvasDiv.width();
                html5Canvas[0].height = canvasDiv.height();
            }


        });


        function clone(obj) {
            var copy;

            // Handle the 3 simple types, and null or undefined
            if (null == obj || "object" != typeof obj) return obj;

            // Handle Date
            if (obj instanceof Date) {
                copy = new Date();
                copy.setTime(obj.getTime());
                return copy;
            }

            // Handle Array
            if (obj instanceof Array) {
                copy = [];
                for (var i = 0, len = obj.length; i < len; i++) {
                    copy[i] = clone(obj[i]);
                }
                return copy;
            }

            // Handle Object
            if (obj instanceof Object) {
                copy = {};
                for (var attr in obj) {
                    if (obj.hasOwnProperty(attr)) copy[attr] = clone(obj[attr]);
                }
                return copy;
            }

            throw new Error("Unable to copy obj! Its type isn't supported.");
        }


    </script>
</head>

<body>
<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <a class="navbar-brand" href="#">Label Image</a>
        <ul class="nav navbar-nav">
        </ul>
    </div>
</nav>

<div class="container">

    <div class="row">
        {{--<div class="jumbotron">--}}
            <img class="annotateMyClass" id="toAnnotate" src="#" alt="Waiting for loading"/>
            <p></p>
            <p>
                <button class="btn btn-primary btn-sm" style="margin: 5px" href="#" role="button" id="nextPerson_btn"
                        onclick="nextPerson()">Next
                </button>
                <button class="btn btn-primary btn-sm" style="margin: 5px" href="#" role="button" id="ok_btn"
                        onclick="nextPicture()">Ok
                </button>
                <button class="btn btn-primary btn-sm" href="#" role="button" onclick="print()">Print</button>

                <button class="btn btn-danger btn-sm" href="#" role="button" onclick="alarm()">Alarm</button>

            </p>
        {{--</div>--}}
    </div>
    <div class="row">
        <div class="col-xs-4">
            <!--              <input type="file" name="file" id="file">
-->
        </div>
    </div>

</div>
<!--  <div>
    <div style="padding: 2px">
        <button id="nextPerson_btn" onclick="nextPerson()">Next</button>
    </div>

    <div style="padding: 2px">
        <button id="ok_btn" onclick="nextPicture()">OK</button>
    </div>

    <div>
        <button onclick="print()">Print</button>
    </div>
</div> -->
</body>



</html>
