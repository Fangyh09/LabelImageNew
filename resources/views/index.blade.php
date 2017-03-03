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
    <script type="text/javascript" src="{{asset('static/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/js/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/js/jquery.annotate.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/js/bootstrap.min.js')}}"></script>
    <script language="javascript">
        filterId = 0;
        //offset changed, so temp solution.
        var outerTop = 0;
        var outerLeft = 0;
        var myPicNames = new Array("images/pic1.jpg", "images/pic2.png", "images/pic3.png");
        var tmpNote1 = JSON.parse('[{"top":22,"left":22,"text":"a","groupId":0,"partId":0},{"top":22,"left":28,"text":"b","groupId":0,"partId":1},{"top":18,"left":22,"text":"c","groupId":1,"partId":0},{"top":67,"left":16,"text":"d","groupId":1,"partId":5}]');
        var tmpNote2 = JSON.parse('[{"top":82,"left":22,"text":"a","groupId":0,"partId":0},{"top":22,"left":68,"text":"b","groupId":0,"partId":1},{"top":58,"left":22,"text":"c","groupId":1,"partId":0},{"top":67,"left":76,"text":"d","groupId":1,"partId":1}]');
        var tmpNote3 = JSON.parse('[{"top":42,"left":22,"text":"a","groupId":0,"partId":0},{"top":52,"left":28,"text":"b","groupId":0,"partId":1},{"top":78,"left":22,"text":"c","groupId":1,"partId":0},{"top":67,"left":36,"text":"d","groupId":1,"partId":5}]');
        var myNotes = new Array(tmpNote1, tmpNote2, tmpNote3);
        var arrIdx = 0;
        var readFileName = "D:/software/xampp/htdocs/caffe_rtpose/input_json/filenames.txt";

        initOuterTop_Left = function () {
            outerTop = $("#toAnnotate").offset().top;
            outerLeft = $("#toAnnotate").offset().left;
        }

        nextPerson = function () {
            //to show next person
            filterId = parseInt(filterId) + 1;
            //reload annotations
            result = $("#toAnnotate").annotateImage.reload();
            if (!result) {
                $('#nextPerson_btn').prop('disabled', true);
                $('#ok_btn').prop('disabled', false);
            }
        };

        print = function () {
            var annotations = $.fn.annotateImage.getAnnotations();
            console.log(annotations);
            alert(annotations);
        }

        do_finish_once = function () {
            filterId = 0;
            $('#nextPerson_btn').prop('disabled', false);
            $('#ok_btn').prop('disabled', true);
            $.fn.annotateImage.removeCanvas();
            $("#toAnnotate").annotateImage({
                notes: myNotes[arrIdx],
                top: outerTop,
                left: outerLeft,
                filterId: filterId

            });
            console.log($.fn.annotateImage.getAnnotations());
        }

        getNextPictureData = function () {

        }

        nextPicture = function () {
            arrIdx++;
            arrIdx %= 3;
            //nextPicName, nextNote = getNextPictureData();
            // wait the pic loaded
            var logo = document.getElementById('toAnnotate');
            $('#toAnnotate').attr("src", myPicNames[arrIdx]);
            logo.onload = function () {
                do_finish_once();
            };

        }

        $(window).load(function () {
            initOuterTop_Left();
            do_finish_once();
        });
    </script>
</head>

<body>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <a class="navbar-brand" href="#">Label Image</a>
        <ul class="nav navbar-nav">
            <!-- <li class="active">
                <a href="#"></a>
            </li>
            <li>
                <a href="#"></a>
            </li> -->
        </ul>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="jumbotron">
            <p>
                <br>
            </p>
            <img class="annotateMyClass" id="toAnnotate" src="images/pic1.jpg" alt="Trafalgar Square"/>
            <p></p>
            <p>
                <button class="btn btn-primary btn-sm" style="margin: 5px" href="#" role="button" id="nextPerson_btn"
                        onclick="nextPerson()">Next
                </button>
                <button class="btn btn-primary btn-sm" style="margin: 5px" href="#" role="button" id="ok_btn"
                        onclick="nextPicture()">Ok
                </button>
                <button class="btn btn-primary btn-sm" href="#" role="button" onclick="print()">Print</button>
            </p>
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

    document.getElementById('file').onchange = function () {
        var file = this.files[0];
        var reader = new FileReader();
        reader.onload = function (progressEvent) {
            // Entire file
            console.log(this.result);
            // By lines
            var lines = this.result.split('\n');
            for (var line = 0; line < lines.length; line++) {
                console.log(lines[line]);
            }
        };
        reader.readAsText(file);
    };
</script>


</html>
