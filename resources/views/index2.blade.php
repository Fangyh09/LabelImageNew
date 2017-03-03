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
//            arrIdx++;
//            arrIdx %= 3;
            //nextPicName, nextNote = getNextPictureData();
            // wait the pic loaded
            ///ajax
            var logo = document.getElementById('toAnnotate');
            $('#toAnnotate').attr("src", myPicNames[arrIdx]);
            logo.onload = function () {
                do_finish_once();
            };

        }

        $(window).load(function () {
            var imagePath;
            var noteJson;
            $.getJSON( "http://localhost/LabelImagePhp/public/getData", function( data ) {
                var items = [];
                $.each( data, function( key, val ) {
                    items.push( "<li id='" + key + "'>" + val + "</li>" );
                });

                $( "<ul/>", {
                    "class": "my-new-list",
                    html: items.join( "" )
                }).appendTo( "body" );
            });
//            $.ajax({
//                type: "POST",
//                dataType: 'json',
//                url: 'http://localhost/LabelImagePhp/public/getData',
//                data: {imagePath: imagePath, noteJson: noteJson},
//                success: function( msg ) {
//                    $("#ajaxResponse").append("<div>"+msg+"</div>");
//                }
//            });
//            console.log(imagePath);
//            console.log(noteJson);
//            $.get('getData', function(){
//                console.log("!!!!!!!!!!!!!!!!");
//                console.log('response');
//            });
//            initOuterTop_Left();
//            do_finish_once();
        });

    </script>
</head>

<body>
{{--<nav class="navbar navbar-default navbar-fixed-top" role="navigation">--}}
    {{--<div class="container">--}}
        {{--<a class="navbar-brand" href="#">Label Image</a>--}}
        {{--<ul class="nav navbar-nav">--}}
            {{--<!-- <li class="active">--}}
                {{--<a href="#"></a>--}}
            {{--</li>--}}
            {{--<li>--}}
                {{--<a href="#"></a>--}}
            {{--</li> -->--}}
        {{--</ul>--}}
    {{--</div>--}}
{{--</nav>--}}

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
