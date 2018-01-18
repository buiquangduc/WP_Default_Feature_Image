import styles from './wpdfi.scss';
import bootstrap from 'bootstrap';
import Form from './scripts/form.js';
import GFImage from './scripts/gfimage.js';

$(document).ready(function(){
	var form = new Form('#wpdfi-form');
	form.init();
	var genFImageEl = $('#generate_fimage_wrapper');
	var genFImageElExist = (genFImageEl.length != 0);
	if(genFImageElExist) {
		var gFImage = new GFImage();
		gFImage.init();
	}
});

