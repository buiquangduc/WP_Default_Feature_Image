import select2 from 'select2';

var Taxonomy = function(className) {

    /**
     * taxonomy variable is created to represent Taxonomy instance.
     */
    var taxonomy = this;

    /**
     * element variable is created to represent master element of the Taxonomy.
     */
    var element = $(className);

    /**
     * selectElement variable is created to represent select input of the Taxonomy.
     */
    var selectElement = element.find('.taxonomy-multiple-select');

    /** 
     * Initialize actions.
     */
    this._init = function() {
        /* Initialize multiple select with select2 library. */
        taxonomy._multipleSelect();
        
    }

    /**
     * Initialize multiple select with select2 library.
     */
    this._multipleSelect = function() {

        selectElement.select2({
            placeholder: 'Please choose your terms'
        });

    }

    /**
     * Delete the Taxonomy.
     */ 
    this.delete = function() {
        /* Remove the taxonomy element. */
        element.remove();
    }

    taxonomy._init();

}

export default Taxonomy;