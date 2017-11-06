import select2 from 'select2';

var PostType = function(className) {

    /**
     * postType variable is created to represent PostType instance.
     */
    var postType = this;

    /**
     * element variable is created to be a master element of the PostType.
     */
    var element = $(className);

    /**
     * selectElement attribute is created to represent select input of the PostType.
     */
    this.selectElement = element.find('.post-types-select');

    /**
     * Initialize actions when create a PostType instance.
     */
    this._init = function() {
        
    }

    /**
     * Get current value of select input.
     */
    this.getValue = function() {
        return postType.selectElement.val();
    }

    /**
     * Delete the PostType.
     */ 
    this.delete = function() {
        /* Remove the post type element. */
        element.remove();
    }

    postType._init();
}

export default PostType;