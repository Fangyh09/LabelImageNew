<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Image Annotations</title>
    <link href="{{asset('static/css/bootstrap.min.css')}}" rel="stylesheet">
    {{--<style type="text/css" media="all">--}}
        {{--@import "{{asset('static/css/annotation.css')}}";--}}
    {{--</style>--}}

    <style type="text/css">

        #canvas-wrap { position:relative } /* Make this a positioned parent */
        #overlay    { position:absolute; top:0px; left:0px; z-index:99999}
        #drawCanvas {position:absolute; top:0px; left:0px;}
        .image-annotate-view {
            position:absolute; top:0px; left:0px;
        }

        .image-annotate-view-subdiv {
            position:absolute; top:0px; left:0px;
        }

        .labedCircle:hover{
            border: 1px solid #000000;
            position:absolute;
        }

        .labedCircle {
            margin: 0;
            position: absolute;
        }

        .image-annotate-note {
            background: #E7FFE7 none repeat scroll 0 0;
            border: solid 1px #397F39;
            color: #000;
            font-family: Verdana, Sans-Serif;
            font-size: 12px;
            max-width: 200px;
            padding: 3px 7px;
            position: absolute;
            /*display: none;*/
        }


        .image-annotate-note .actions {
            display: block;
            font-size: 80%;
        }

    </style>
    <script type="text/javascript" src="{{asset('static/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/js/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/js/jquery.annotate.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/js/bootstrap.min.js')}}"></script>


    <script language="javascript">
        var simple = '<?php echo $_SERVER['SERVER_ADDR']; ?>';
        var basePath =  "http://" + simple + ":20023/";
        basePath = "http://202.121.182.216:20023/";
        var imageDbId;
        //--------------------------------------------
        window.onload = function () {

            $.getJSON( basePath + "getData", function( data ) {
//                var ok = data['ok'];
//                if (parseInt(ok) == 0) {
//                    alert("图片加载完毕。");
//                    return;
//                }
                var imgSrc = data['imagePath'];
                var inputJSON = data['noteJson'];
                inputJSON = JSON.parse(inputJSON);

                imageDbId = data['id'];

                $("#toAnnotate").labelImage({
                    inputImage: imgSrc,
                    inputJSON: inputJSON
                });
                updateScoreBoard();
            });
        };

        nextPerson = function () {
            $.fn.showNextPerson();
            updateScoreBoard();
        };

        prevPerson = function () {
            $.fn.showPrevPerson();
            updateScoreBoard();
        };

        nextPicture = function () {

            $.ajax({
                url: basePath + "delData",
                type: "post",
                data: {
                    id: imageDbId,
                    data: JSON.stringify($.fn.getResultNote())
                }
            }).then(function() {
                $.getJSON( basePath + "getData", function( data ) {
//                var ok = data['ok'];
//                if (parseInt(ok) == 0) {
//                    alert("图片加载完毕。");
//                    return;
//                }
                    var imgSrc = data['imagePath'];
                    var inputJSON = data['noteJson'];
                    inputJSON = JSON.parse(inputJSON);

                    imageDbId = data['id'];

                    $("#toAnnotate").labelImage({
                        inputImage: imgSrc,
                        inputJSON: inputJSON
                    });
                });


            });
            updateScoreBoard();

        };
        showAll = function () {
            $.fn.showAllPerson();
        };

        deletePerson = function () {
            $.fn.deletePerson();
            updateScoreBoard();
        };

        updateScoreBoard = function () {
            var curScore = parseInt($.fn.getFilterId()) + 1;
            var totalScore = $.fn.getPersonNum();
            if (parseInt(totalScore) == 0) {
                curScore = 0;
            }
            $('#ScoreBoardBtn').text(curScore + "/" + totalScore);
        };

        printRes = function () {
            $.fn.printResultJSON();
        };

        document.oncontextmenu = function(event) {
            return false;
        };

        addPerson = function () {
            $.fn.addPerson();
            updateScoreBoard();
        };


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
        <div class="col-xs-4 col-sm-4">
        </div>
        <button class="btn btn-primary btn-md" href="#" role="button" onclick="addPerson()">增加一个人</button>

        <button class="btn btn-danger btn-md" href="#" role="button" onclick="deletePerson()">删除这个人</button>



        <button style="margin-left: 40px" class="btn btn-warning btn-md" style="margin: 5px" href="#" role="button" id="ok_btn"
                onclick="nextPicture()">下一张图片
        </button>


    </div>
    <div class="row">
        <div class="col-xs-4 col-sm-4">
        </div>

        <button class="btn btn-primary btn-md" style="margin: 5px" href="#" role="button" id="nextPerson_btn"
                onclick="prevPerson()">上一个人
        </button>

        <button id="ScoreBoardBtn" class="btn btn-info btn-md" href="#" role="button" > / </button>
        <button class="btn btn-primary btn-md" style="margin: 5px" href="#" role="button" id="nextPerson_btn"
                onclick="nextPerson()">下一个人
        </button>
        <button style="margin-left: 18px" class="btn btn-primary btn-md" href="#" role="button" onclick="showAll()">显示所有人</button>



        {{--<div class="jumbotron">--}}
            <img class="annotateMyClass" id="toAnnotate" src="#" alt="Waiting for loading"/>


        {{--</div>--}}
    </div>
    <div class="row" style="margin-top: 600px">

        <div class="col-xs-4">
            <p></p>
            <p></p>
            <p></p>
        </div>
    </div>

</div>

</body>

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.2.1/vue.js"></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.2.1/vue-resource.js"></script>--}}

    {{--<script type="text/javascript">--}}
        {{--new Vue({--}}
            {{--el: "#app",--}}
            {{--data: {--}}
                {{--message: 'Hello vue'--}}
            {{--}--}}
        {{--});--}}
    {{--</script>--}}


</html>
