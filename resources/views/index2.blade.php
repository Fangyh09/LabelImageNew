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
    <style>
        canvas
        {
            background-color: #8a6d3b;
            position: absolute;
            z-index: -10;
            /* control height and width in code to prevent stretching */
        }
    </style>
    <script type="text/javascript" src="{{asset('static/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/js/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/js/jquery.annotate.index2.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            readjustHTML5CanvasHeight = function () {
                //clear the canvas by readjusting the width/height
                var html5Canvas = $('#html5CanvasId');
                var canvasDiv = $('#divCanvasId');

                if (html5Canvas.length > 0) {
                    html5Canvas[0].width = canvasDiv.width();
                    html5Canvas[0].height = canvasDiv.height();
                }
            };

            drawLineBetweenElements= function (sourceElement, targetElement) {

                //draw from/to the centre, not the top left
                //don't use .position()
                //that will be relative to the parent div and not the page
                var sourceX = sourceElement.offset().left + sourceElement.width() / 2;
                var sourceY = sourceElement.offset().top + sourceElement.height() / 2;

                var targetX = targetElement.offset().left + sourceElement.width() / 2;
                var targetY = targetElement.offset().top + sourceElement.height() / 2;

                var canvas = $('#html5CanvasId');

                //you need to draw relative to the canvas not the page
                var canvasOffsetX = canvas.offset().left;
                var canvasOffsetY = canvas.offset().top;

                var context = canvas[0].getContext('2d');

                //draw line
                context.beginPath();
                context.moveTo(sourceX - canvasOffsetX, sourceY - canvasOffsetY);
                context.lineTo(targetX - canvasOffsetX, targetY - canvasOffsetY);
                context.closePath();
                //ink line
                context.lineWidth = 2;
                context.strokeStyle = "#000"; //black
                context.stroke();
            };


            drawLines =  function () {
                //reset the canvas
                $().yourExt._readjustHTML5CanvasHeight();

                var elementsToDrawLinesBetween;
                //you must create an object that holds the start and end of the line
                //and populate a collection of them to iterate through
                elementsToDrawLinesBetween.each(function (i, startEndPair) {
                    //dot notation used, you will probably have a different method
                    //to access these elements
                    var start = startEndPair.start;
                    var end = startEndPair.end;

                    $().yourExt._drawLineBetweenElements(start, end);
                });
            }


    </script>

</head>

<body>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <a class="navbar-brand" href="#">Label Image</a>
        <ul class="nav navbar-nav">
        </ul>
    </div>
</nav>
<div class="container">
    <div class="row">

        <div id="divCanvasId" class="divCanvasClass">
            <img class="annotateMyClass" id="toAnnotate" src="{{asset("caffe_rtpose/input/000001163.jpg")}}" alt="Trafalgar Square"/>
        </div>
        <canvas id="html5CanvasId"></canvas>
        {{--<div class="jumbotron">--}}
            {{--<p>--}}
                {{--<br>--}}
            {{--</p>--}}


            {{--<p></p>--}}
            {{--<p>--}}
                {{--<button class="btn btn-primary btn-sm" style="margin: 5px" href="#" role="button" id="nextPerson_btn"--}}
                        {{--onclick="nextPerson()">Next--}}
                {{--</button>--}}
                {{--<button class="btn btn-primary btn-sm" style="margin: 5px" href="#" role="button" id="ok_btn"--}}
                        {{--onclick="nextPicture()">Ok--}}
                {{--</button>--}}
                {{--<button class="btn btn-primary btn-sm" href="#" role="button" onclick="print()">Print</button>--}}
            {{--</p>--}}
        </div>
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
<script type="text/javascript">
    // $(":file").filestyle({buttonName: "btn-primary"});

    //    document.getElementById('file').onchange = function () {
    //        var file = this.files[0];
    //        var reader = new FileReader();
    //        reader.onload = function (progressEvent) {
    //            // Entire file
    //            console.log(this.result);
    //            // By lines
    //            var lines = this.result.split('\n');
    //            for (var line = 0; line < lines.length; line++) {
    //                console.log(lines[line]);
    //            }
    //        };
    //        reader.readAsText(file);
    //    };
</script>


</html>
