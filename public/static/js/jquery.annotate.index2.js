// (function($) {
//     ///resultNotes is the result Annotations
//     resultNotes = {};
//     ///to store the origin picture top & left
//     originImageTop = 0;
//     originImageLeft = 0;
//     /// to show to annotations with specific person whose id is filterId
//     filterId = 0;
//     ///to image node itself, need to improve...
//     imageTmpVar = {};
//     const totalPoints = 18;
//     outerCanvas = null;
//     colors = [[255, 0, 0], [255, 85, 0], [255, 170, 0], [255, 255, 0], [170, 255, 0], [85, 255, 0], [0, 255, 0],
//         [0, 255, 85], [0, 255, 170], [0, 255, 255], [0, 170, 255], [0, 85, 255], [0, 0, 255], [85, 0, 255],
//         [170, 0, 255], [255, 0, 255], [255, 0, 170], [255, 0, 85]];
//
//     limbSeq = [[2,3], [2,6], [3,4], [4,5], [6,7], [7,8], [2,9], [9,10],
//            [10,11], [2,12], [12,13], [13,14], [2,1], [1,15], [15,17],
//            [1,16], [16,18], [3,17], [6,18]];
//     $.fn.annotateImage = function(options) {
//         var opts = $.extend({}, $.fn.annotateImage.defaults, options);
//         var image = this;
//
//         imageTmpVar = image;
//         this.image = this;
//
//         //this.mode = 'view';
//
//         originImageLeft = options.left;
//         originImageTop = options.top;
//
//         console.log(this.resultNotes);
//
//         // editable: true,
//         // useAjax: false,
//         opts.useAjax = false;
//         opts.editable = true;
//
//         // Assign defaults
//         this.getUrl = opts.getUrl;
//         this.saveUrl = opts.saveUrl;
//         this.deleteUrl = opts.deleteUrl;
//         this.editable = opts.editable;
//         this.useAjax = opts.useAjax;
//         this.notes = opts.notes;
//         //record the component pointer, using it to destory old component
//         this.memory = opts.memory;
//         filterId = options.filterId;
//
//
//         for (idx in this.notes) {
//             this.notes[idx]['top'] = this.notes[idx]['top'] - $.fn.annotateImage.defaults['cicleRadius'];
//             this.notes[idx]['left'] = this.notes[idx]['left'] - $.fn.annotateImage.defaults['cicleRadius'];
//         }
//
//
//         for (idx in this.notes) {
//             this.notes[idx]['id'] = this.notes[idx]['groupId'] + "_" + this.notes[idx]['partId'];
//             this.notes[idx]['idxInArr'] = idx;
//         }
//
//         for (idx in this.notes) {
//             this.notes[idx]['text'] = this.notes[idx]['groupId'] + "_" + this.notes[idx]['partId'];
//         }
//
//         resultNotes = this.notes;
//         console.log(resultNotes);
//
//         var x = JSON.stringify(resultNotes);
//         console.log(x);
//
//         this.canvas = $('<div id="myCanvasAll" class="image-annotate-canvas"><div class="image-annotate-view"></div><div class="image-annotate-edit"><div class="image-annotate-edit-area"></div></div></div>');
//         this.canvas.children('.image-annotate-edit').hide();
//         this.canvas.children('.image-annotate-view').hide();
//         this.image.after(this.canvas);
//
//
//         this.canvas.height(this.height());
//         this.canvas.width(this.width());
//         //this.canvas.css('background-size','100% 100%');
//         this.canvas.css('background-image', 'url("' + this.attr('src') + '")');
//         this.canvas.children('.image-annotate-view, .image-annotate-edit').height(this.height());
//         this.canvas.children('.image-annotate-view, .image-annotate-edit').width(this.width());
//
//         this.canvas.hover(function() {
//             $(this).children('.image-annotate-view').show();
//
//         }, function() {
//             $(this).children('.image-annotate-view').hide();
//         });
//
//
//         this.canvas.children('.image-annotate-view').hover(function() {
//             $(this).show();
//         }, function() {
//             $(this).hide();
//         });
//
//
//         if (this.useAjax) {
//             $.fn.annotateImage.ajaxLoad(this);
//         } else {
//             $.fn.annotateImage.load(this);
//         }
//
//
//
//         this.hide();
//         return this;
//     }
//     $.fn.drawLines = function (groupId) {
//         // var points = new Array(totalPoints);
//         // for (var idx in note) {
//         //     var left = totalPoints['left'];
//         //     var top = totalPoints['top'];
//         //     //var groupId = totalPoints['groupId'];
//         //     var partId = totalPoints['partId'];
//         //     points[parseInt(partId)] = {};
//         //     points[parseInt(partId)]['left'] = left;
//         //     points[parseInt(partId)]['top'] = top;
//         // }
//
//         for (var idx in limbSeq) {
//             var a = parseInt(limbSeq[idx][0]) - 1;
//             var b = parseInt(limbSeq[idx][1]) - 1;
//             var aId = groupId + "_" + a;
//             var bId = groupId + "_" + b;
//             $.fn.drawLineBetweenElements($('#myCanvasAreaDiv' + aId),$('#myCanvasAreaDiv' + bId));
//         }
//
//     }
//
//     $.fn.annotateImage.removeCanvas = function() {
//         $('#myCanvasAll').remove();
//     }
//
//     $.fn.annotateImage.defaults = {
//         getUrl: 'your-get.rails',
//         saveUrl: 'your-save.rails',
//         deleteUrl: 'your-delete.rails',
//         editable: true,
//         useAjax: true,
//         notes: new Array(),
//         memory: new Array(),
//         cicleRadius: 5,
//         alpha: 0.8,
//
//     }
//     $.fn.annotateImage.getAnnotations = function() {
//         tmpresultNotes = $.fn.clone(resultNotes);
//         for (var idx in tmpresultNotes) {
//             tmpresultNotes[idx]['top'] = parseFloat(tmpresultNotes[idx]['top']) + $.fn.annotateImage.defaults['cicleRadius'];
//             tmpresultNotes[idx]['left'] = parseFloat(tmpresultNotes[idx]['left']) + $.fn.annotateImage.defaults['cicleRadius'];
//         }
//         var jsonAnnotations = JSON.stringify(tmpresultNotes);
//
//         return jsonAnnotations;
//         //console.log(jsonAnnotations);
//     }
//     $.fn.annotateImage.clear = function(image) {
//         for (var i = 0; i < image.memory.length; i++) {
//             image.memory[i].destroy();
//         }
//         image.memory = new Array();
//     };
//
//     $.fn.annotateImage.reload = function() {
//         return $.fn.annotateImage.load(imageTmpVar);
//     }
//
//
//     $.fn.annotateImage.ajaxLoad = function(image) {
//
//     }
//
//     //前置条件：gid 是连续的
//     $.fn.annotateImage.load = function(image) {
//         addNew = false;
//         $.fn.annotateImage.clear(image);
//         for (var i = 0; i < image.notes.length; i++) {
//             if (parseInt(image.notes[i]['groupId']) == parseInt(filterId)) {
//                 image.notes[image.notes[i]] = new $.fn.annotateView(image, image.notes[i]);
//                 image.memory.push(image.notes[image.notes[i]]);
//                 addNew = true;
//             }
//         }
//         if (addNew) {
//             // $.fn.drawLines(parseInt(filterId));
//         }
//
//         return addNew;
//     }
//
//     $.fn.annotateView = function(image, note) {
//         this.r = $.fn.annotateImage.defaults['cicleRadius'];
//         this.alpha = $.fn.annotateImage.defaults['alpha'];
//         this.image = image;
//         this.note = note;
//         this.editable = false;
//         console.log(note.id);
//
//         this.area = $('<div id="myCanvasAreaDiv' + note.id + '" class="image-annotate-area' + (this.editable ? ' image-annotate-area-editable' : '') + '"><div><canvas class="labedCircle" id="myCanvas' + note.id + '" width="' + (this.r * 2) + '" height="' + (this.r * 2) + '"></canvas></div></div>');
//
//         image.canvas.children('.image-annotate-view').prepend(this.area);
//         console.log(this.area);
//
//         var c = document.getElementById('myCanvas' + note.id);
//         console.log('!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');
//         console.log(note);
//         var noteId = parseInt(note.partId);
//         var noteColor = "" + colors[noteId][0] + "," + colors[noteId][1] + "," + colors[noteId][2];
//         var cxt = c.getContext("2d");
//         cxt.fillStyle = 'rgb(' + noteColor + ')';
//         cxt.beginPath();
//         cxt.globalAlpha = this.alpha;
//         cxt.arc(this.r, this.r, this.r, 0, Math.PI * 2, true);
//         cxt.closePath();
//         cxt.fill();
//
//         this.form = $('<div class="image-annotate-note">' + note.text + '</div>');
//         this.form.hide();
//         image.canvas.children('.image-annotate-view').append(this.form);
//         this.form.children('span.actions').hide();
//
//         this.setPosition();
//
//         var annotation = this;
//
//         this.circle = $('#myCanvas' + note.id);
//         this.circle.hover(function() {
//             annotation.show();
//         }, function() {
//             annotation.hide();
//         });
//
//         this.setDrawable();
//
//         return this;
//     }
//
//
//     $.fn.annotateView.prototype.setDrawable = function() {
//         this.r = $.fn.annotateImage.defaults['cicleRadius'] * 2;
//         var tmpr = $.fn.annotateImage.defaults['cicleRadius']
//         var area = this.area;
//         var form = this.form;
//         var image = this.image;
//         var note = this.note;
//         var tmpc = $('#' + 'myCanvas' + this.note.id);
//
//         area.draggable({
//             containment: image.canvas,
//             //containment: tmpc,
//             drag: function(e, ui) {
//                 form.css('left', (parseFloat(area.offset().left) - originImageLeft) + 'px');
//                 form.css('top', (parseFloat(area.offset().top) - originImageTop + parseInt(area.height()) + 7) + 'px');
//             },
//             stop: function(e, ui) {
//                 form.css('left', (parseFloat(area.offset().left) - originImageLeft) + 'px');
//                 form.css('top', (parseFloat(area.offset().top) - originImageTop + parseFloat(area.height()) + 7) + 'px');
//                 //resultNotes[note["idxInArr"]]['left'] = parseInt(tmpc.offset().left) - originImageLeft + tmpr;
//                 //resultNotes[note["idxInArr"]]['top'] = parseInt(tmpc.offset().top) - originImageTop + tmpr;
//
//                 resultNotes[note["idxInArr"]]['left'] = parseFloat(tmpc.offset().left) - originImageLeft + tmpr;
//                 resultNotes[note["idxInArr"]]['top'] = parseFloat(tmpc.offset().top) - originImageTop + tmpr;
//                 console.log(area.offset());
//                 var x = JSON.stringify(resultNotes);
//                 console.log(x);
//             }
//         });
//     }
//
//
//     $.fn.annotateView.prototype.setPosition = function() {
//         this.r = $.fn.annotateImage.defaults['cicleRadius'] * 2;
//         this.area.children('div').height((parseFloat(this.r)) + 'px');
//         this.area.children('div').width((parseFloat(this.r)) + 'px');
//         this.area.css('left', (this.note.left) + 'px');
//         this.area.css('top', (this.note.top) + 'px');
//         this.form.css('left', (this.note.left) + 'px');
//         this.form.css('top', (parseFloat(this.note.top) + parseFloat(this.r) + 7) + 'px');
//     }
//
//     $.fn.annotateView.prototype.show = function() {
//         /// <summary>
//         ///     Highlights the annotation
//         /// </summary>
//         this.form.fadeIn(10);
//         if (!this.editable) {
//             this.area.addClass('image-annotate-area-hover');
//         } else {
//             this.area.addClass('image-annotate-area-editable-hover');
//         }
//     };
//
//     $.fn.annotateView.prototype.hide = function() {
//         /// <summary>
//         ///     Removes the highlight from the annotation.
//         /// </summary>
//         this.form.fadeOut(10);
//         this.area.removeClass('image-annotate-area-hover');
//         this.area.removeClass('image-annotate-area-editable-hover');
//     };
//     $.fn.annotateView.prototype.destroy = function() {
//         /// <summary>
//         ///     Destroys the annotation.
//         /// </summary>
//         this.area.remove();
//         this.form.remove();
//     };
//     $.fn.clone = function (obj) {
//         var copy;
//
//         // Handle the 3 simple types, and null or undefined
//         if (null == obj || "object" != typeof obj) return obj;
//
//         // Handle Date
//         if (obj instanceof Date) {
//             copy = new Date();
//             copy.setTime(obj.getTime());
//             return copy;
//         }
//
//         // Handle Array
//         if (obj instanceof Array) {
//             copy = [];
//             for (var i = 0, len = obj.length; i < len; i++) {
//                 copy[i] = clone(obj[i]);
//             }
//             return copy;
//         }
//
//         // Handle Object
//         if (obj instanceof Object) {
//             copy = {};
//             for (var attr in obj) {
//                 if (obj.hasOwnProperty(attr)) copy[attr] = clone(obj[attr]);
//             }
//             return copy;
//         }
//
//         throw new Error("Unable to copy obj! Its type isn't supported.");
//     };
//
//     $.fn.drawLineBetweenElements = function (sourceElement, targetElement) {
//
//         //draw from/to the centre, not the top left
//         //don't use .position()
//         //that will be relative to the parent div and not the page
//         var sourceX = sourceElement.offset().left + sourceElement.width() / 2;
//         var sourceY = sourceElement.offset().top + sourceElement.height() / 2;
//
//         var targetX = targetElement.offset().left + sourceElement.width() / 2;
//         var targetY = targetElement.offset().top + sourceElement.height() / 2;
//
//         var canvas = $('#myCanvasAll');
//
//         //you need to draw relative to the canvas not the page
//         var canvasOffsetX = canvas.offset().left;
//         var canvasOffsetY = canvas.offset().top;
//
//         var context = canvas[0].getContext('2d');
//
//         //draw line
//         context.beginPath();
//         context.moveTo(sourceX - canvasOffsetX, sourceY - canvasOffsetY);
//         context.lineTo(targetX - canvasOffsetX, targetY - canvasOffsetY);
//         context.closePath();
//         //ink line
//         context.lineWidth = 2;
//         context.strokeStyle = "#000"; //black
//         context.stroke();
//     }
//
// })(jQuery);
