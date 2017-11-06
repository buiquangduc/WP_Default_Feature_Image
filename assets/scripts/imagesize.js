var ImageSize = function(className) {
    
    /**
     * imageSize variable is created to represent ImageSize instance.
     */
    var imageSize = this;

    /**
     * element variable is created to represent master element of the ImageSize.
     */
    var element = $(className);

    /**
     * selectElement variable is created to represent select input of the PostType.
     */
    var selectElement = element.

    /**
     * Initialize actions when create a ImageSize instance.
     */
    this.init = function() {
        // Initialize Select2 for select input.
        imageSize.initializeSelect2();
    }

    /**
     * Initialize Select2 for select input.
     */
    this.initializeSelect2 = function() {
        selectElement.select2();
    }

    /**
     * Delete the ImageSize.
     */ 
    this.delete = function() {
        /* Remove the image size element. */
        element.remove();
    }

    imageSize.init();
}

module.exports = ImageSize;