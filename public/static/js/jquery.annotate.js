/**
 * Created by Catherine Fang on 2017/6/5.
 */

(function ($) {

    //this.inputJSON obj
    //this.personArr
    //this.circleRadius = opts.circleRadius;
    //this.alpha = opts.alpha;


    // var canvas = document.getElementById("canvas");
    // var context = canvas.getContext("2d");
    // var canvas = new Object();
    // var context = new Object();
    //var canvas = new Object();

    //visible
    //0: not visible; 1: visible; 2: not exists


    var resultNotes;
    var image = new Image();
    // var enviroment
    environment = new Object();
    var debug = true;
    var filterId = 0;

    var colors = [[255, 0, 0], [255, 85, 0], [255, 170, 0], [255, 255, 0], [170, 255, 0], [85, 255, 0], [0, 255, 0],
        [0, 255, 85], [0, 255, 170], [0, 255, 255], [0, 170, 255], [0, 85, 255], [0, 0, 255], [85, 0, 255],
        [170, 0, 255], [255, 0, 255], [255, 0, 170], [255, 0, 85], [0, 0, 0], [0, 0, 0]];

    var limbSeq = [[2, 3], [2, 6], [3, 4], [4, 5], [6, 7], [7, 8], [2, 9], [9, 10],
        [10, 11], [2, 12], [12, 13], [13, 14], [2, 1], [1, 15], [15, 17],
        [1, 16], [16, 18]];

    var limbColors = [[255, 127.5, 0], [170, 170, 0], [255, 212.5, 0], [212.5, 255, 0], [42.5, 255, 0], [0, 255, 42.5], [127.5, 170, 85],
        [0, 255, 212.5], [0, 212.5, 255], [127.5, 85, 127.5], [0, 42.5, 255], [42.5, 0, 255], [255, 42.5, 0],
        [212.5, 0, 127.5], [212.5, 0, 212.5], [255, 0, 127.5], [255, 0, 170], [255, 85, 85], [170, 127.5, 42.5]];


    var jointNameOld = [
        "Nose",
        "Neck",
        "RShoulder",
        "RElbow",
        "RWrist",

        "LShoulder",
        "LElbow",
        "LWrist",
        "RHip",
        "RKnee",

        "RAnkle",
        "LHip",
        "LKnee",
        "LAnkle",
        "REye",

        "LEye",
        "REar",
        "LEar",
        "bbox",
        "bbox"
    ];

    var jointName = [
        "鼻子",
        "颈部",
        "右肩",
        "右手肘",
        "右手腕",

        "左肩",
        "左手肘",
        "左手腕",
        "右臀",
        "右膝盖",

        "右脚踝",
        "左臀",
        "左膝盖",
        "左脚踝",
        "右眼",

        "左眼",
        "右耳",
        "左耳",
        "bbox",
        "bbox"
    ];


    var personTemplate = [140.553,44.3634,1,132.702,76.9427,1,97.6008,78.2369,1,78.1039,129.159,1,88.3636474609375,142.72727966308594,1,170.392,75.6219,1,178.3636474609375,104.72727966308594,1,176.3636474609375,141.72727966308594,1,117.063,169.527,1,92.3532,204.816,1,101.489,255.657,1,158.705,166.958,1,150.3636474609375,201.72727966308594,1,163.3636474609375,258.72727966308594,1,134,36.5294,1,147.3636474609375,33.72727966308594,1,119.653,37.8165,1,157.3636474609375,45.72727966308594,1];
    var unifyPersonTemplate = {
        "joints":[63.4491,11.636120336914061,1,
                  55.5981,44.215420336914065,1,20.49690000000001,
            45.50962033691407,1,1,96.43172033691405,1,
            11.259747460937504,110,1,
            93.2881,42.89462033691406,1,

            101.2597474609375,72,1,
            99.2597474609375,109,1,
            39.95910000000001,136.79972033691405,1,
            15.249300000000005,172.08872033691407,1,
            24.38510000000001,222.92972033691407,1,

            81.60110000000002,134.23072033691406,1,
            73.2597474609375,169,1,
            86.2597474609375,226,1,
            56.896100000000004,3.802120336914065,1,
            70.2597474609375,1,1,

            42.54910000000001,5.08922033691406,1,
            80.2597474609375, 13,1,
            5,70,1]
    };

    $.fn.getResultNote = function () {
        var bodies = resultNotes['bodies'];
        for (var idx in bodies) {
            var len = bodies[idx]['joints'].length;
            for (var jdx = 0; jdx < len; jdx = jdx + 3) {
                if (!$.fn.checkPointInCanvas(bodies[idx]['joints'][jdx], bodies[idx]['joints'][jdx + 1])) {
                    bodies[idx]['joints'][jdx] = bodies[idx]['joints'][jdx + 1] = 0;
                }
            }
        }


        return resultNotes;
    };

    $.fn.addBBOX = function (inputJSON) {
        var bodies = inputJSON['bodies'];
        for (var idx in bodies) {
            var len = bodies[idx]['joints'].length;

            var bboxObj = {
                left1: -1,
                top1: -1,
                left2: -1,
                top2: -1
            };

            for (var jdx = 0; jdx < len; jdx = jdx + 3) {
                if (bodies[idx]['joints'][jdx] > 0 && bodies[idx]['joints'][jdx + 1] > 0) {
                    bboxObj.left1 = bboxObj.left1 == -1 ? bodies[idx]['joints'][jdx] : Math.min(bboxObj.left1, bodies[idx]['joints'][jdx]);
                    bboxObj.left2 = bboxObj.left2 == -1 ? bodies[idx]['joints'][jdx] : Math.max(bboxObj.left2, bodies[idx]['joints'][jdx]);

                    bboxObj.top1 = bboxObj.top1 == -1 ? bodies[idx]['joints'][jdx + 1] : Math.min(bboxObj.top1, bodies[idx]['joints'][jdx + 1]);
                    bboxObj.top2 = bboxObj.top2 == -1 ? bodies[idx]['joints'][jdx + 1] : Math.max(bboxObj.top2, bodies[idx]['joints'][jdx + 1]);
                }

            }

           // bodies[filterId]['joints'][partId * 3]
            bodies[idx]['joints'].push(bboxObj.left1);
            bodies[idx]['joints'].push(bboxObj.top1);
            bodies[idx]['joints'].push(1);

            bodies[idx]['joints'].push(bboxObj.left2);
            bodies[idx]['joints'].push(bboxObj.top2);
            bodies[idx]['joints'].push(1);
        }
        return inputJSON;
    };


    $.fn.getPersonArr = function (inputJSON) {
        var bodies = inputJSON['bodies'];
        var jsonArr = [];
        var cnt = 0;
        for (var idx in bodies) {
            var len = bodies[idx]['joints'].length;
            var jsonInnerArr = [];


            for (var jdx = 0; jdx < len; jdx = jdx + 3) {
                jsonInnerArr.push({
                    left: bodies[idx]['joints'][jdx],
                    top: bodies[idx]['joints'][jdx + 1],
                    groupId: idx,
                    partId: jdx / 3,
                    textId: idx + "_" + (jdx / 3),
                    idx: cnt,
                    isZero: parseInt(bodies[idx]['joints'][jdx]) == 0 && parseInt(bodies[idx]['joints'][jdx + 1]) == 0,
                    visible: 1,
                    leftPercentage: parseFloat(bodies[idx]['joints'][jdx]) / parseFloat(environment.imageWidth), // not use
                    topPercentage: parseFloat(bodies[idx]['joints'][jdx + 1]) / parseFloat(environment.imageHeight) // not use

                });

                cnt++;
            }

            environment.bboxPartId1 = (len - 3) / 3;
            environment.bboxPartId2 = len / 3;
            jsonArr.push(jsonInnerArr);
        }

        return jsonArr;
    };
    
    $.fn.checkPointInCanvas = function (left, top) {
        var intleft = parseInt(left);
        var inttop = parseInt(top);
        if (parseFloat(intleft) > 0 && intleft <= parseInt(parseFloat(environment.imageWidth) + 0.5) && parseFloat(inttop) > 0 && inttop <= parseInt(parseFloat(environment.imageHeight + 0.5))) {
            return true;
        }
        return false;
    };

    $.fn.getMax = function(x, newx) {
        if (parseInt(x) == -1) {
            return newx;
        }
        return Math.max(parseInt(x), parseInt(newx));
    };

    $.fn.getMin = function(x, newx) {
        if (parseInt(x) == -1) {
            return newx;
        }

        if (parseInt(newx) == 0) {
            return x;
        }
        return Math.min(parseInt(x), parseInt(newx));
    };

    $.fn.test = function () {
        this.cicleRadius = 20;
        this.area = $('<div style="background-color: aqua"><canvas class="joint" id="jointCanvas' + 1 + '" width="' + (this.circleRadius * 2) + '" height="' + (this.circleRadius * 2) + '"></canvas></div>');
        var tmp = $('<div style="background-color: aqua"></div>');
        canvas.append(tmp);
    };

    $.fn.showPerson = function (personId) {
        var maxLen = environment.personArr.length;
        if (personId < maxLen) {
            var singlePerson = environment.personArr[personId];
            for (var idx in singlePerson) {
                $.fn.drawJoint(singlePerson[idx]);
            }
        }

    };


    /**
     * @param singlePersonJSON
     * {"left":554.098,"top":131.141,"groupId":"0","partId":15,"textId":"0_15","idx":15}
     */
    $.fn.drawJoint = function (singlePersonJSON) {
        var tmpNode = $('<div style="position: absolute;"><canvas class="joint" style="background-color: black" id="jointCanvas' + singlePersonJSON.textId + '" width="' + (environment.circleRadius * 2) + '" height="' + (environment.circleRadius * 2) + '"></canvas></div>');
        var windowPos = canvasToWindow(singlePersonJSON.left, singlePersonJSON.top);
        tmpNode.css('left', windowPos.left + 'px');
        tmpNode.css('top', windowPos.top + 'px');
        console.log("singlePerson:" + singlePersonJSON.partId + "-" + windowPos.left + "-" + windowPos.top);
        environment.parent().append(tmpNode);
    };


    $.fn.calcLimbColors = function () {
        limbColors = [];
        for (var idx in limbSeq) {
            var color = "#";
            for (var jdx = 0; jdx < 3; jdx++) {
                var mid = Math.round(parseInt(colors[limbSeq[idx][0] - 1][jdx]) + parseInt(colors[limbSeq[idx][1] - 1][jdx]) / 2);
                var mid16 = mid.toString(16).toUpperCase();
                var sub = ('0' + mid16).slice(-2);
                color += sub;
            }

            limbColors.push(color);
        }
    };


    $.fn.clearAll = function () {
        filterId = 0;
        $('#canvas-wrap').remove();
    };


    $.fn.labelImage = function (options) {
        $.fn.clearAll();
        $.fn.calcLimbColors();
        environment = this;
        // canvas = this[0];
        // context = canvas.getContext("2d");

        var opts = $.extend({}, $.fn.labelImage.defaults, options);
        environment.circleRadius = opts.circleRadius;
        environment.alpha = opts.alpha;

        opts.inputJSON = $.fn.addBBOX(opts.inputJSON);
        resultNotes = opts.inputJSON;
        environment.inputJSON = opts.inputJSON;
        environment.inputJSON = $.fn.getVisible(environment.inputJSON);
        environment.personArr = new Array();

        environment.bboxPartId1 = -1;
        environment.bboxPartId2 = -1;
        console.log(environment.inputJSON);


        environment.personArr = $.fn.getPersonArr(opts.inputJSON);

        image.src = opts.inputImage;
        image.onload = function () {
            environment.imageWidth = image.width;
            environment.imageHeight = image.height;

            var innerStr1 = '<div id="overlay" class="image-annotate-canvas"><div class="image-annotate-view"></div></div>';
            var innerStr2 = '<canvas id="drawCanvas" width="' + environment.imageWidth + '" height="' + environment.imageHeight + '"></canvas>';
            var totalStr = '<div id="canvas-wrap" style="margin: 100px;">' + innerStr1 + innerStr2 + '</div>';
            // this.canvas = $('<div id="myCanvasAll" class="image-annotate-canvas"><div class="image-annotate-view"></div><div class="image-annotate-edit"><div class="image-annotate-edit-area"></div></div></div>');
            var parentCanvas = $(totalStr);
            var overlay = parentCanvas.children().first();
            console.log(overlay);
            environment.overlay = overlay;
            environment.after(parentCanvas);
            environment.canvasContext = document.getElementById('drawCanvas').getContext('2d');
            environment.canvas = $('#drawCanvas');

            overlay.height(environment.imageHeight);
            overlay.width(environment.imageWidth);

            $('#drawCanvas').css('background-repeat', 'no-repeat');
            $('#drawCanvas').css('background-image', 'url("' + image.src + '")');
            environment.hide();

            $.fn.labelImage.loadJoint(parseInt(filterId));
            $.fn.drawLines(parseInt(filterId));
        };

    };

    $.fn.addPerson = function () {

        resultNotes = $.fn.getResultNote();
        var bodies = resultNotes['bodies'];
        bodies.push({
            "joints":[63.4491,11.636120336914061,1,
                55.5981,44.215420336914065,1,
                20.49690000000001, 45.50962033691407,1,
                1,96.43172033691405,1,
                11.259747460937504,110,1,

                93.2881,42.89462033691406,1,
                101.2597474609375,72,1,
                99.2597474609375,109,1,
                39.95910000000001,136.79972033691405,1,
                15.249300000000005,172.08872033691407,1,

                24.38510000000001,222.92972033691407,1,
                81.60110000000002,134.23072033691406,1,
                73.2597474609375,169,1,
                86.2597474609375,226,1,
                56.896100000000004,3.802120336914065,1,

                70.2597474609375,1,1,
                42.54910000000001,5.08922033691406,1,
                80.2597474609375, 13,1,
                2,5,1,
                105,230,1
                ]
        });

        var randomFloatX = Math.round(Math.random() * 100);
        var randomFloatY = Math.round(Math.random() * 100);
        var bodyArr = bodies[bodies.length - 1];
        for (var idx = 0; idx < bodyArr['joints'].length; idx += 3) {
            bodyArr['joints'][idx] += randomFloatX;
            bodyArr['joints'][idx + 1] += randomFloatY;
        }


        environment.personArr = $.fn.getPersonArr(resultNotes);
        filterId = resultNotes['bodies'].length - 1;
        $('.image-annotate-view-subdiv').remove();
        $.fn.labelImage.loadJoint(parseInt(filterId));
        $.fn.drawLines(parseInt(filterId));
    };


    //pass-by-value || pass-by-ref will be ok
    $.fn.getVisible = function (inputJSON) {
        var bodies = inputJSON['bodies'];
        for (var idx in bodies) {
            var len = bodies[idx]['joints'].length;
            for (var jdx = 0; jdx < len; jdx = jdx + 3) {
                bodies[idx]['joints'][jdx + 2] = 1;
            }
        }
        return inputJSON;
    };



    $.fn.getPersonNum = function () {
        return environment.personArr.length;
    };

    $.fn.getFilterId = function () {
        return filterId;
    };

    $.fn.increaseFilterId = function () {
        if (parseInt(filterId) + 1 < environment.personArr.length) {
            filterId = parseInt(filterId) + 1;
            return true;
        }
        return false;
    };
    

    $.fn.decreaseFilterId = function () {
        if (parseInt(filterId) - 1 >= 0) {
            filterId = parseInt(filterId) - 1;
            return true;
        }
        return false;
    };

    $.fn.showNextPerson = function () {
        if ($.fn.increaseFilterId()) {
            $('.image-annotate-view-subdiv').remove();
            $.fn.labelImage.loadJoint(parseInt(filterId));
            $.fn.drawLines(parseInt(filterId));
        }
    };

    $.fn.showPrevPerson = function () {
        if ($.fn.decreaseFilterId()) {
            $('.image-annotate-view-subdiv').remove();
            $.fn.labelImage.loadJoint(parseInt(filterId));
            $.fn.drawLines(parseInt(filterId));
        }
    };


    $.fn.showAllPerson = function () {
        $('.image-annotate-view-subdiv').remove();
        for (var filterId = 0; filterId < environment.personArr.length; filterId ++) {
            $.fn.labelImage.loadJoint(parseInt(filterId));
            $.fn.drawLinesWithoutClear(parseInt(filterId));
        }
        $( ".image-annotate-view-subdiv" ).draggable({
            disabled: true
        });

    };

    $.fn.labelImage.loadJoint = function (fileterId) {

        var len = environment.personArr.length;
        if (fileterId < len) {
            var singlePersonArr = environment.personArr[fileterId];
            for (var idx in singlePersonArr) {
                $.fn.annotateView(environment, singlePersonArr[idx]);
            }
        }
    };

    $.fn.drawFillCircle = function (context, partId, radius) {
        context.fillStyle = $.fn.getCSSColor(colors[partId]);
        context.beginPath();
        context.globalAlpha = $.fn.labelImage.defaults['alpha'];
        context .arc(radius, radius, radius, 0, Math.PI * 2, true);
        context.closePath();
        context.fill();
    };

    $.fn.drawStrokeCircle = function (context, partId, radius) {
        context.beginPath();
        context.lineWidth = 2;
        context.strokeStyle = $.fn.getCSSColor(colors[partId]);
        context .arc(radius, radius, radius, 0, Math.PI * 2, true);
        context.closePath();
        context.stroke();
    };

    $.fn.drawCircle = function (context, partId, radius, visible) {
        if (parseInt(visible) == 0 || parseInt(visible) == 1) {
            $.fn.drawFillCircle (context, partId, radius);
        }
        else {
            $.fn.drawStrokeCircle(context, partId, radius);
        }
    };

    $.fn.annotateView = function (environment, jointJSON) {
        var radius = environment.circleRadius;
        var twoRadius = parseInt(radius) * 2;

        var area = $('<div class="image-annotate-view-subdiv" style="width: ' + twoRadius + 'px; height: ' + twoRadius + 'px"><canvas class="labedCircle" id="jointCanvas' + jointJSON.textId + '" width="' + (twoRadius) + '" height="' + (twoRadius) + '"></canvas></div>');
        environment.overlay.children('.image-annotate-view').prepend(area);

        this.area = area;
        this.jointJSON = jointJSON;

        var jointCanvas = document.getElementById('jointCanvas' + jointJSON.textId);
        var context = jointCanvas.getContext("2d");
        var partId = jointJSON.partId;

        $.fn.drawCircle(context, partId, radius, jointJSON.visible);

        var jointNote = $('<div class="image-annotate-note" id="image-annotate-note' + jointJSON.textId + '">' +  jointName[jointJSON.partId] + '</div>');
        jointNote.hide();
        environment.overlay.children('.image-annotate-view').append(jointNote);


        area.css('left', (parseInt(this.jointJSON.left) - parseInt(environment.circleRadius)) + 'px');
        area.css('top', (parseInt(this.jointJSON.top) - parseInt(environment.circleRadius)) + 'px');

        jointNote.css('left', (parseInt(this.jointJSON.left) - parseInt(environment.circleRadius) + 15) + 'px');
        jointNote.css('top', (parseInt(this.jointJSON.top) - parseInt(environment.circleRadius) + 9) + 'px');


        var tmpJoint = $('#' + 'jointCanvas' + jointJSON.textId);
        tmpJoint.hover(function() {
            jointNote.show();
        }, function() {
            jointNote.hide();
        });

        tmpJoint.dblclick(function () {
            console.log("click");
            var bodies = resultNotes['bodies'];
            var visible = environment.personArr[filterId][partId].visible;
            if (parseInt(visible) > 0) {
                if (parseInt(visible) == 1) {
                    bodies[filterId]['joints'][partId * 3 + 2] = 2;
                    environment.personArr[filterId][partId].visible = 2;
                }
                else {
                    bodies[filterId]['joints'][partId * 3 + 2] = 1;
                    environment.personArr[filterId][partId].visible = 1;
                }
            }

            $.fn.clearCanvas(jointCanvas);
            $.fn.drawCircle(context, partId, radius, jointJSON.visible);
        });

        var partId = jointJSON.partId;
        area.draggable({
            drag: function (e, ui) {
                $.fn.drawLines(parseInt(filterId));
                jointNote.css('left', (parseInt(tmpJoint.offset().left) - environment.canvas.offset().left + environment.circleRadius + 15) + 'px');
                jointNote.css('top', (parseInt(tmpJoint.offset().top) - environment.canvas.offset().top + environment.circleRadius + 9) + 'px');

                var bodies = resultNotes['bodies'];
                if (filterId < environment.personArr.length) {
                    //left
                    bodies[filterId]['joints'][partId * 3] = parseInt(tmpJoint.offset().left) - environment.canvas.offset().left + environment.circleRadius;
                    //top
                    bodies[filterId]['joints'][partId * 3 + 1] = parseInt(tmpJoint.offset().top) - environment.canvas.offset().top + environment.circleRadius;
                    // console.log(JSON.stringify(resultNotes));


                    //update personArr
                    environment.personArr[filterId][partId].left = bodies[filterId]['joints'][partId * 3];
                    environment.personArr[filterId][partId].top = bodies[filterId]['joints'][partId * 3 + 1];
                    //environment.personArr[filterId][partId].isZero = parseInt(environment.personArr[filterId][partId].left) == 0 && parseInt(nvironment.personArr[filterId][partId].top) == 0;

                    var preVisible = environment.personArr[filterId][partId].visible;
                    if ($.fn.checkPointInCanvas(environment.personArr[filterId][partId].left, environment.personArr[filterId][partId].top)) {
                        bodies[filterId]['joints'][partId * 3 + 2] = Math.max(1, parseInt(bodies[filterId]['joints'][partId * 3 + 2]));
                        environment.personArr[filterId][partId].visible = Math.max(1, parseInt(bodies[filterId]['joints'][partId * 3 + 2]));
                    }
                    else {
                        bodies[filterId]['joints'][partId * 3 + 2] = 0;
                        environment.personArr[filterId][partId].visible = 0;
                    }
                    if (parseInt(preVisible) != parseInt(environment.personArr[filterId][partId].visible)) {
                        $.fn.clearCanvas(jointCanvas);
                        $.fn.drawCircle(context, partId, radius, jointJSON.visible);
                    }

                }
            },

            stop: function (e, ui) {
                var bodies = resultNotes['bodies'];
                if (filterId < environment.personArr.length) {
                    //left
                    bodies[filterId]['joints'][partId * 3] = parseInt(tmpJoint.offset().left) - environment.canvas.offset().left + environment.circleRadius;
                    //top
                    bodies[filterId]['joints'][partId * 3 + 1] = parseInt(tmpJoint.offset().top) - environment.canvas.offset().top + environment.circleRadius;
                    // console.log(JSON.stringify(resultNotes));


                    //update personArr
                    environment.personArr[filterId][partId].left = bodies[filterId]['joints'][partId * 3];
                    environment.personArr[filterId][partId].top = bodies[filterId]['joints'][partId * 3 + 1];

                    console.log("point:" + bodies[filterId]['joints'][partId * 3] + "-" + bodies[filterId]['joints'][partId * 3 + 1]);
                }
            }
        });

    };

    $.fn.deletePerson = function () {
        if (parseInt(filterId) < parseInt(environment.personArr.length)) {
     
            if (filterId > -1) {
                resultNotes['bodies'].splice(filterId, 1);
                environment.personArr.splice(filterId, 1);
            }
    

            if (environment.personArr.length > 0) {
                if (parseInt(filterId) >= parseInt(environment.personArr.length)) {
                    filterId = 0;
                }

                $('.image-annotate-view-subdiv').remove();
                $.fn.labelImage.loadJoint(parseInt(filterId));
                $.fn.drawLines(parseInt(filterId));
                return true;
            }
            else {
                $('.image-annotate-view-subdiv').remove();
                $.fn.clearBackgroundCanvas();
            }
        }
        return false;
    };

    $.fn.getCSSColor = function (color) {
        var textColor = "" + color[0] + "," + color[1] + "," + color[2];
        return 'rgb(' + textColor + ')';
    };

    $.fn.drawLinesWithoutClear = function (filterId) {
        var groupId = filterId;
        var cicleRadius = $.fn.labelImage.defaults['cicleRadius'];
        var len = environment.personArr.length;

        if (groupId < len) {
            for (var idx in limbSeq) {
                var a = parseInt(limbSeq[idx][0]) - 1;
                var b = parseInt(limbSeq[idx][1]) - 1;
                // var aId = groupId + "_" + a;
                // var bId = groupId + "_" + b;
                var aId = environment.personArr[groupId][a].textId;
                var bId = environment.personArr[groupId][b].textId;

    

                if ($.fn.checkPointInCanvas(environment.personArr[groupId][a].left, environment.personArr[groupId][a].top) &&
                    $.fn.checkPointInCanvas(environment.personArr[groupId][b].left, environment.personArr[groupId][b].top)) {
                    $.fn.drawLineBetweenElements($('#jointCanvas' + aId), $('#jointCanvas' + bId), colors[Math.min(colors.length - 1, idx)]);

                }



            }
            //draw bbox
            var len = environment.personArr[groupId].length;
            var aId = environment.personArr[groupId][len - 2].textId;
            var bId = environment.personArr[groupId][len - 1].textId;

            $.fn.drawRectangleBetweenElements($('#jointCanvas' + aId), $('#jointCanvas' + bId), [0, 0, 0]);
        }
    };

    $.fn.drawLines = function (filterId) {
        $.fn.clearBackgroundCanvas();
        $.fn.drawLinesWithoutClear (filterId);

    };

    $.fn.drawRectangleBetweenElements = function (sourceElement, targetElement, color) {
        //draw from/to the centre, not the top left
        //don't use .position()
        //that will be relative to the parent div and not the page
        var sourceX = sourceElement.offset().left + sourceElement.width() / 2;
        var sourceY = sourceElement.offset().top + sourceElement.height() / 2;

        var targetX = targetElement.offset().left + sourceElement.width() / 2;
        var targetY = targetElement.offset().top + sourceElement.height() / 2;

        var minX = Math.min(sourceX, targetX);
        var minY = Math.min(sourceY, targetY);
        var maxX = Math.max(sourceX, targetX);
        var maxY = Math.max(sourceY, targetY);

        var canvas = $('#drawCanvas');

        //you need to draw relative to the canvas not the page
        var canvasOffsetX = canvas.offset().left;
        var canvasOffsetY = canvas.offset().top;

        var context = document.getElementById('drawCanvas').getContext('2d');

        //draw line

        context.beginPath();
        context.strokeRect(minX - canvasOffsetX, minY - canvasOffsetY, maxX - minX , maxY - minY);
        context.closePath();

        //ink line
        context.lineWidth = 2;
        context.strokeStyle = $.fn.getCSSColor(color);  //black

        context.stroke();
    };

    $.fn.drawLineBetweenElements = function (sourceElement, targetElement, color) {
        //draw from/to the centre, not the top left
        //don't use .position()
        //that will be relative to the parent div and not the page
        var sourceX = sourceElement.offset().left + sourceElement.width() / 2;
        var sourceY = sourceElement.offset().top + sourceElement.height() / 2;

        var targetX = targetElement.offset().left + sourceElement.width() / 2;
        var targetY = targetElement.offset().top + sourceElement.height() / 2;

        var canvas = $('#drawCanvas');

        //you need to draw relative to the canvas not the page
        var canvasOffsetX = canvas.offset().left;
        var canvasOffsetY = canvas.offset().top;

        var context = document.getElementById('drawCanvas').getContext('2d');

        //draw line

        context.beginPath();
        context.moveTo(sourceX - canvasOffsetX, sourceY - canvasOffsetY);
        context.lineTo(targetX - canvasOffsetX, targetY - canvasOffsetY);
        context.closePath();

        //ink line
        context.lineWidth = 2;
        context.strokeStyle = $.fn.getCSSColor(color);  //black

        context.stroke();
    };


    $.fn.labelImage.defaults = {
        inputImage: 'image-src.png',
        inputJSON: new Object(),
        circleRadius: 5,
        alpha: 0.8
    };

    $.fn.clearBackgroundCanvas = function () {
        var canvas = document.getElementById("drawCanvas");
        $.fn.clearCanvas(canvas);
    };

    $.fn.clearCanvas = function (canvas) {
        //var canvas = document.getElementById("drawCanvas");
        var cxt = canvas.getContext("2d");
        canvas.height = canvas.height;
    };

    $.fn.printResultJSON = function () {
        console.log(resultNotes);
        var len = personTemplate.length;
        var minX = 0x3f3f3f3f;
        var minY = 0x3f3f3f3f;
        console.log(personTemplate);

        for (var i = 0;i < len; i += 3) {
            if (personTemplate[i] > 0)
            minX = Math.min(minX, personTemplate[i]);
            if (personTemplate[i + 1] > 0)
            minY = Math.min(minY, personTemplate[i + 1]);
        }
        for (var i = 0;i < len; i += 3) {
            if (personTemplate[i] != 0 && personTemplate[i + 1] != 0) {
                personTemplate[i] -= minX - 1;
                personTemplate[i + 1] -= minY - 1;
            }
        }

        console.log(JSON.stringify(personTemplate));

        var resultNotes = $.fn.getResultNote();
        //resultNotes = JSON.parse(resultNotes);
        console.log(resultNotes);
        console.log(JSON.stringify(resultNotes));
        var bodies = resultNotes['bodies'];
        var ret = [];
        for (var idx in bodies) {
            var tmpret = {};
            var len = bodies[idx]['joints'].length;
            for (var jdx = 0; jdx < len; jdx = jdx + 3) {
                tmpret[jointName[jdx / 3]] = bodies[idx]['joints'][jdx + 2];
            }
            ret.push(tmpret);
        }
        console.log(ret);

    };


})(jQuery);
