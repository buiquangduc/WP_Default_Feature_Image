import progressbar from 'progressbar.js';

var GFImage = function() {

	var $ = jQuery;

	var gFImage = this;

	var element = $('#generate_fimage_wrapper');

	var postNoFImageIds = element.data('post_no_fimage_ids');

	var totalPostNoFImage = postNoFImageIds.length;

	var securityToken = element.data('nonce');

	var informationWrapper = $('.generate-fimage-information');

	var progressBarID = '#generate_fimage_progressbar';

	var progressBar;

	var generateButton = $('#generate_fimage_button');

	var saveButton = $('#save_form_button');

	this.init = function() {
		gFImage._progressBarInit();
		gFImage._onClickGButton();
	}	

	/**
	 * Initialize progress bar.
	 */
	this._progressBarInit = function() {
		var ProgressBar = require('progressbar.js');
		progressBar = new ProgressBar.Line(progressBarID, {
	        easing: 'easeInOut'
	    });
	}

	/** 
	 * Actions after generate button is clicked.
	 */
	this._onClickGButton = function() {
		generateButton.click(function(event){
			event.preventDefault();
			var warningResult = gFImage._warning();
			if(warningResult) {
				gFImage._toggleButton([generateButton, saveButton], 'disable');
				gFImage._ajaxUpdateFImage(postNoFImageIds[0]);
			}
		});
	}

	/**
	 * Enable, disable a list of buttons.
	 */
	this._toggleButton = function(arrayElement, status) {
		arrayElement.forEach(function(el){
			switch(status) {
				case 'disable':
					el.prop('disabled', true);
					break;
				case 'enable':
					el.prop('disabled', false);
					break;
				default:
					el.prop('disabled', false);
			}
		});
	}

	/**
	 * Regenerate feature image for a post.
	 */
	this._ajaxUpdateFImage = function(postId) {
		$.ajax({
            url: '/wp-admin/admin-ajax.php?action=wpdfi_generate_feature_image',
            method: 'POST',
            data: {
                post_id: postId,
                security: securityToken
            },
            success: function(res) {

            	var response = JSON.parse(res);
            	gFImage._updateLogAfterAjax(response);
            	var arrayIndex = postNoFImageIds.indexOf(postId);
            	gFImage._updateProgressBar(arrayIndex);
            	gFImage._continueAjax(arrayIndex);

            }
        })
	}

	/**
	 * Update the progress bar. Progress bar will be 100% if all the posts are regenerated feature image.
	 */
	this._updateProgressBar = function(arrayIndex) {
		/* Javascript array index start at 0, so we need to plus 1 to get the correct value for divide purpose. */
		var realIndex = arrayIndex + 1;
		var currentPercent = realIndex/totalPostNoFImage;
		var currentPercentText = (currentPercent*100).toFixed(2) + '%';
		progressBar.set(currentPercent);
		progressBar.setText(currentPercentText);
	}

	/**
	 * Check if there is a post which is not regenerated feature image yet.
	 */
	this._continueAjax = function(arrayIndex) {
		/* Javascript array index start at 0, so we need to minus 1 from total posts to get the last index. */
		var lastIndex = totalPostNoFImage - 1;
		var isLastIndex = (arrayIndex == lastIndex);
		/* If the current index is not the last index, continue run Ajax request on the next index. */
		if(!isLastIndex) {
			gFImage._ajaxUpdateFImage(postNoFImageIds[arrayIndex + 1]);
		/* If the current index is the last index, enable save button. */
		} else {

			gFImage._updateLogAfterAjax({
				status: 'complete',
				text: 'Complete!'
			});
			gFImage._toggleButton([saveButton], 'enable');

		}
	}

	/**
	 * Update log message after the generate button is clicked.
	 */
	this._updateLogAfterAjax = function(response) {
		switch(response.status) {
    		case true:
    			informationWrapper.append('<p>' + response.namePT + ' with ID ' + response.postId +' is updated feature image successfully</p>');
    			break;
    		case false:
    			informationWrapper.append('<p>' + response.namePT + ' with ID ' + response.postId +' because conditions are not match.</p>');
    			break;
    		case 'complete':
    			informationWrapper.append('<p>' + response.text + '</p>');
    			break;
    		default:
    			informationWrapper.append('<p>' + response.namePT + ' with ID ' + response.postId +' has something wrong!</p>');
    	}
	}

	/**
	 * Display alert box whenever the generate button is clicked.
	 */
	this._warning = function() {
		var warningText = 'Are you sure you want to generate all feature image with the values in the "DFIs" tab? Make sure to backup your database before click "OK".';
		return confirm(warningText);
	}

}

export default GFImage;