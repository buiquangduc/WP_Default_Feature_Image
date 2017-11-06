var ImageUpload = function(className) {
    
    /**
     * imageUpload variable is created to represent ImageUpload instance.
     */
    var imageUpload = this;

    /**
     * element variable is created to represent master element of the ImageUpload.
     */
    var element = $(className);

    /**
     * wrapperImage variable is created to represent image wrapper div of the ImageUpload.
     */
    var wrapperImage = element.find('.wrapper-image');

    /**
     * uploadButton variable is created to represent upload button of the ImageUpload.
     */
    var uploadButton = element.find('.upload-btn');

    /**
     * image variable is created to represent image uploaded of the ImageUpload.
     */
    var image = element.find('.image');

    /**
     * imageInputId variable is created to represent image input id field of the ImageUpload.
     */
    var imageInputId = element.find('.image-input-id');

    /**
     * imageInputSource variable is created to represent image input source field of the ImageUpload.
     */
    var imageInputSource = element.find('.image-input-source');

    /**
     * Initialize actions.
     */
    this.init = function() {
        /* Popup Default WordPress build in system to import or select image. */
        imageUpload._popupMedia();
    }

    /** 
     * Popup Default WordPress build in system to import or select image.
     */
    this._popupMedia = function() {
        uploadButton.click(function(e) {
            e.preventDefault();
            var _image = wp.media({ 
                title: 'Upload Image',
                // mutiple: true if you want to upload multiple files at once.
                multiple: false
            }).open().on('select', function(e){
                // This will return the selected image from the Media Uploader, the result is an object.
                var _uploaded_image = _image.state().get('selection').first();
                // We convert uploaded_image to a JSON object to make accessing it easier.
                var _image_url = _uploaded_image.toJSON().url;
                // Display the image, save the value to the input id and input source.
                wrapperImage.css('display', 'block');
                image.attr('src', _image_url);
                imageInputSource.val(_image_url);
                imageInputId.val(_uploaded_image.id);
            });
        });
    }

    /**
     * Return the ID of uploaded image
     */
    this.getId = function() {
        return imageInputId.val();
    }

    /**
     * Return the image source of uploaded image
     */
    this.getSource = function() {
        return imageInputSource.val();
    }

    /**
     * Delete the ImageUpload.
     */ 
    this.delete = function() {
        /* Remove the image upload element. */
        element.remove();
    }

    imageUpload.init();
}

module.exports = ImageUpload;
