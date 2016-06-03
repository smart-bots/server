'use strict';
(function($) {})(jQuery);
if (typeof jQuery === "undefined") {
    throw new Error("SmartBots requires jQuery");
}
// Jquery afterTime function
/**
 * jQuery afterTime() method is just setTimeout function that can be used to chain with jQuery selectors
 * @param  {ms} sec [the callback will excute after]
 * @param  {function} callback [the function to excute]
 * @return {jQuery selectors}
 */
jQuery.fn.extend({
    afterTime: function(sec, callback) {
        that = $(this);
        setTimeout(function() {
            callback.call(that);
        }, sec);
        return this;
    }
});
// Jquery exists function
/**
 * jQuery exists() method can check if a selector matches or not
 * @return {boolean}
 */
jQuery.fn.exists = function() {
    return this.length;
}
$.SmartBots = {};
// Show Alert Box
// $.SmartBots.alert($('.box-body'),'Yee hah','success',true,5000);
$.SmartBots.alert = function(dom, html, alertType, dismissible = true, duration = 5000, insertionType = 'prepend', additionClass = '') {
    switch (alertType) {
        case 'success':
            var icon = 'fa-check';
            break;
        case 'danger':
            var icon = 'fa-exclamation';
            break;
        case 'warning':
            var icon = 'fa-exclamation-triangle';
            break;
        case 'info':
            var icon = 'fa-info-circle';
            break;
    }
    var alertHtml = ['<div class="alert alert-' + alertType + (dismissible ? ' alert-dismissible' : '') + (additionClass != '' ? ' ' + additionClass : '') + '" role="alert" id="inserted-alert">',
        (dismissible ? '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' : ''),
        '<i class="fa ' + icon + '" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;',
        html,
        '</div>'
    ].join('');
    if (duration != 0) {
        switch (insertionType) {
            case 'append':
                $(alertHtml).appendTo(dom).afterTime(duration, function() {
                    $(this).slideUp('slow', function() {
                        this.remove()
                    });
                });
                break;
            case 'prepend':
                $(alertHtml).prependTo(dom).afterTime(duration, function() {
                    $(this).slideUp('slow', function() {
                        this.remove()
                    });
                });
                break;
            case 'before':
                $(alertHtml).insertBefore(dom).afterTime(duration, function() {
                    $(this).slideUp('slow', function() {
                        this.remove()
                    });
                });
                break;
            case 'after':
                $(alertHtml).insertAfter(dom).afterTime(duration, function() {
                    $(this).slideUp('slow', function() {
                        this.remove()
                    });
                });
                break;
        }
    } else {
        switch (insertionType) {
            case 'append':
                $(alertHtml).appendTo(dom);
                break;
            case 'prepend':
                $(alertHtml).prependTo(dom);
                break;
            case 'before':
                $(alertHtml).insertBefore(dom);
                break;
            case 'after':
                $(alertHtml).insertAfter(dom);
                break;
        }
    }
}

$.fn.pulsate = function() {
    this.pulsate({
        color: '#dd4b39',
        reach: 20, // how far the pulse goes in px
        speed: 1000, // how long one pulse takes in ms
        pause: 0, // how long the pause between pulses is in ms
        glow: false, // if the glow should be shown too
        repeat: 3, // will repeat forever if true, if given a number will repeat for that many times
        onHover: false // if true only pulsate if user hovers over the element});
    });
}

$.fn.scrollT0 = function(t0p = 100, duration = 1000) {
    var pos = this.offset().top - t0p;
    $.scrollTo(pos, duration);
}

$.fn.focusTo = function(top = 100, duration = 1000) {
    this.scrollT0(top, duration);
    this.focus();
}

$.fn.validate = function(data, except = [], successCallBack) {

    // Require Sweetalert, scrollTo

    var inputs = this.find('input[type!="hidden"]');

    except.forEach(function(item, index) {
        inputs = inputs.not('input[name="' + item + '"]');
    });

    inputs = inputs.toArray();

    if (data['success'] == true) { // SUCCESSFULLY

        successCallBack(data);

    } else if (data['global'] != null) { // Has GLOBAL error

        var errorHtml = ['<div class="alert alert-danger alert-dismissible" style="display: none;" id="error-alert">',
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">',
                    '<span aria-hidden="true">&times;</span>',
                '</button>',
                '<i class="fa fa-exclamation-circle m-r-5" aria-hidden="true"></i>&nbsp;',
                data['global'],
            '</div>'
        ].join('');

        if ($('#error-alert').exists()) {

            $('#error-alert').slideUp('slow', 'linear', function() { this.remove();});
            $(errorHtml).prependTo(this).slideDown('slow','linear').focusTo();

        } else {

            $(errorHtml).prependTo(this).slideDown('slow','linear').focusTo();
        }

        inputs.forEach(function(item, index) {
            $(item).closest('.form-group').removeClass('has-error has-success has-warning');
            $('span[for="' + $(item).attr('name') + '"]', this).remove();
        })

    } else { // Has some error

        $('#error-alert').slideUp('slow','linear', function() {
            this.remove()
        });

        var focus_to = false;

        inputs.forEach(function(item, index) {

            var input = $(item),
                input_name = input.attr('name'),
                input_error = data[input.attr('name')],
                input_field = input.closest('.form-group'),
                input_type = input.attr('type');

            $('span[for="' + input_name + '"]', this).remove();

            input_field.removeClass('has-error has-success has-warning animated shake');

            if (input_error == null) {

                input_field.addClass('has-success');

            } else {

                var errorHtml = ['<span class="help-block" for="' + input_name + '">',
                    input_error,
                '</span>'].join('');

                input_field
                    .addClass('has-error animated shake')
                    .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                        $(this).removeClass("animated shake");
                    });

                if (input_type != 'checkbox') {
                    input.after(errorHtml);
                } else {
                    input.closest('div').append(errorHtml);
                }

                if (focus_to == false) {
                    focus_to = true;
                    input.focusTo();
                }

                // console.log(input_name + ' : ' + input_error);
            }
        });
    }
}

function randStr(x){
    var s = "";
    while(s.length<x&&x>0){
        var r = Math.random();
        s+= (r<0.1?Math.floor(r*100):String.fromCharCode(Math.floor(r*26) + (r>0.5?97:65)));
    }
    return s;
}

$.fn.materialSwitch = function (labelClass = 'default', additionStyle = '', additionClass = '') {
    var elements = this.toArray();
    elements.forEach(function(item, index) {
      var input = $(item);
      if (input.attr('type') == 'checkbox') {
        if (input.attr('id') == null) {
            var id = randStr(5);
            input.attr('id',id);
        } else {
            var id = input.attr('id');
        }
        input.before('<div class="material-switch '+additionClass+'" id="'+id+'div" style="'+additionStyle+'"><label for="'+id+'" class="label-'+labelClass+'"></label></div>');
        input.prependTo($('div#'+id+'div'));
      }
    });
}

//# sourceMappingURL=jquery.custom.js.map
