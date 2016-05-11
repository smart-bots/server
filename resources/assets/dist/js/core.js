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
    afterTime: function (sec, callback) {
        that = $(this);
        setTimeout(function () {
            callback.call(that);
        }, sec);
        return this;
    }
});

$.SmartBots = {};
// Show Alert Box
// $.SmartBots.alert($('.box-body'),'Yee hah','success',true,5000);
$.SmartBots.alert = function (dom, html, alertType, dismissible = true, duration = 5000, insertionType = 'prepend', additionClass = '') {
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
	var alertHtml = [
		'<div class="alert alert-'+alertType+(dismissible ? ' alert-dismissible':'')+(additionClass != '' ? ' '+additionClass:'')+'" role="alert" id="inserted-alert">',
			(dismissible ? '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>':''),
			'<i class="fa '+icon+'" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;',
			html,
		'</div>'
	].join('');
	if (duration != 0) {
		switch (insertionType) {
			case 'append':
				$(alertHtml).appendTo(dom).afterTime(duration, function () { $(this).slideUp('fast', function () { this.remove() }); });
				break;
			case 'prepend':
				$(alertHtml).prependTo(dom).afterTime(duration, function () { $(this).slideUp('fast', function () { this.remove() }); });
				break;
			case 'before':
				$(alertHtml).insertBefore(dom).afterTime(duration, function () { $(this).slideUp('fast', function () { this.remove() }); });
				break;
			case 'after':
				$(alertHtml).insertAfter(dom).afterTime(duration, function () { $(this).slideUp('fast', function () { this.remove() }); });
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

// Focus + Pulsate + ScrollTo if input error
$.fn.fps = function () {
	$.scrollTo(this.offset().top-100,1000);
	this.pulsate({
		color: '#dd4b39',
		reach: 20,            // how far the pulse goes in px
		speed: 1000,          // how long one pulse takes in ms
		pause: 0,             // how long the pause between pulses is in ms
		glow: true,           // if the glow should be shown too
		repeat: 3,         // will repeat forever if true, if given a number will repeat for that many times
		onHover: false        // if true only pulsate if user hovers over the element});
	});
	this.focus();
}

// Has
$.fn.haz = function (hasType, message = '') {
	if (hasType == 'nothing') {
		this.parents('div.form-group').removeClass('has-error has-success has-warning');
		this.parents('.col-sm-10').find('span.help-block').remove();
	} else {
		var messageHtml = ['<span class="help-block margin-bottom-none">',message,'</span>',].join('');
		this.parents('div.form-group').removeClass('has-error has-success has-warning').addClass('has-'+hasType);
		this.parents('.col-sm-10').find('span.help-block').remove();
		if (message != '') this.parents('.col-sm-10').append(messageHtml);
		//if (hasType == 'error') this.fps();
	}
}
