import ImageSize from './imagesize.js';
import ImageUpload from './imageupload.js';
import Taxonomy from './taxonomy.js';
import PostType from './posttype.js';
import Layout from './layout.js';

var Section = function(sectionIndex, parentForm) {

    /**
     * section variable is created to represent Section instance.
     */
    var section = this;

    /**
     * index variable is created to represent Section index.
     */
    var index = sectionIndex;

    /**
     * id variable is create to represent id of the Section
     */
    var id = '#item-option-origin-' + index;

    /**
     * element variable is created to be a master element of the Section, all the variable will be found via element variable.
     */
    var element = $(id);

    /**
     * postType variable is created to represent Post Type part of the Section.
     */
    var postType;

    /**
     * taxonomies variable is created to represent all Taxonomy parts of the Section.
     */
    var taxonomies;

    /**
     * terms variable is created to represent all Term parts of the Section.
     */
    var terms;

    /**
     * imageUpload variable is created to represent ImageUpload part of the Section.
     */
    var imageUpload;

    /** 
     * imageSize variable is created to represent ImageSize part of the Section.
     */
    var imageSize;

    /**
     * deleteButton variable is created to represent Delete Buton of the Section.
     */
    var deleteButton;

    /**
     * errors variable is temporary variable to store all errors of the Section.
     */ 
    var errors = [];

    /** 
     * layout variable is created to handle all rendering layout stuff for the Section.
     */
    var layout = new Layout();

    /**
     * Initialize actions when create a instance of the Section.
     */
    this.init = function() {
        /* First: Assign all exist layout to corresponding variable. */
        section._checkLayoutAndAssignVariable();
        /**
         * Second: check if the Section has a Post Type part.
         *    If yes, Bind on selected feature when user changed the value of the post type.
         */
        if(typeof postType !== 'undefined') section._onSelectedPostType();
        /* 
         * Third: check if the Section has a Delete Button.
         *    If yes, Bind delete feature when user click on Delete Button.
         */
        if(typeof deleteButton !== 'undefined') section._onDelete();
    }

    /**
     * Check all exist parts and assign it to corresponding variable.
     */
    this._checkLayoutAndAssignVariable = function() {
        /* First: Check if PostType part exist.
         *    If yes, assign PostType part to postType variable.
         */
        if(section._checkLayoutExist('.post-type-row')) {
            postType = new PostType(id+' > .post-type-row');
        }

        /* Second: Check if Taxonomy parts exist.
         *    If yes, assign Taxonomy part to taxonomies variable.
         */
        if(section._checkLayoutExist('.taxonomy-row')) {
            var _taxonomies = [];
            $(id+' > .taxonomy-row').each(function(index, value){
                _taxonomies.push(new Taxonomy(value));
            });      
            taxonomies = _taxonomies;
        }
            

        /* Third: Check if ImageUpload part exist.
         *    If yes, assign ImageUpload part to imageUpload variable.
         */
        if(section._checkLayoutExist('.image-upload-row')) {
            imageUpload = new ImageUpload(id+' > .image-upload-row');
        }

        /* Fourth: Check if ImageSize part exist.
         *    If yes, assign ImageSize part to imageSize variable.
         */
        if(section._checkLayoutExist('.image-size-row')) {
            imageSize = new ImageSize(id+' > .image-size-row');
        }

        /* Fifth: Check if Delete Button exist.
         *    If yes, assign deleteButton variable to Delete Button.
         */
        if(section._checkLayoutExist('.btn-remove')) {
            deleteButton = $(id+' > .btn-remove');
        }
    }

    /**
     * Validate input data of the Section.
     */
    this.validate = function() {
        /**
         * Check if PostType value is exist.
         *    If PostType value is exist, check if ImageUpload value is exist.
         *       If ImageUpload value is not exist, store an error in errors variable, then return false.
         *    If PostType value is not exist, store an error in errors variable, then return false.
         */
        if(!postType.getValue()) {
            console.log(index);
            section._storeError('Post Type value on Section ' + index + ' must be not empty');
            return false;
        } else {
            if(!imageUpload.getId() || !imageUpload.getSource()) {
                section._storeError('Uploaded image value on Section ' + index + ' must be not empty');
                return false;
            }
        }
        return true;
    }

    /**
     * Store error in errors variable.
     */
    this._storeError = function(error) {

        errors.push(error);
    
    }

    /**
     * Truncate errors variable to store new errors of the Section.
     */
    this.truncateErrors = function() {
    
        errors = [];
    
    }

    /**
     * Return the errors of the Section.
     */
    this.getErrors = function() {
        /* Return the errors variable. */
        return errors;
    }


    /**
     * Bind delete feature when click on Delete Button.
     */
    this._onDelete = function() {
        deleteButton.click(function(event){
            /* Delete the section element. */
            event.preventDefault();
            element.slideUp( '300', function() {
                element.remove();
                /** 
                 * Remove section from parent form.
                 * Note that javascript array index start from 0, so we need to minus 1
                 */
                parentForm.removeSection(index - 1);
            });
        });
    }

    /**
     * Actions when user select a post type.
     */
    this._onSelectedPostType = function() {
        /* Bind actions when user select a post type. */
        postType.selectElement.change(function(e) { 
            /* First: Delete related layout. */
            section._deleteLayouts([taxonomies, imageUpload, imageSize]);
            /* Second: Truncate related variables. */
            section._truncateVariables([taxonomies, imageUpload, imageSize]);
            /**
             * Third: 
             *    Check if selected value is not the blank value
             *    If yes:
             *        Get related layout.
             *        Add layout just received to the bottom of the section.
             *        Redo checkLayoutAndAssignVariable.
             */
            if(postType.getValue()) {
                layout.getRelatedSectionLayout(index, postType.getValue())
                      .done(section._addLayout)
                      .then(section._checkLayoutAndAssignVariable);
            }
        });
    }

    /**
     * Get Related Layout, related layout include taxonomies, image upload and image size.
     */
    this._getRelatedLayout = function() {
        return $.ajax({
            url: '/wp-admin/admin-ajax.php?action=wpdfi_get_related_layout',
            method: 'POST',
            data: {
                post_type: postType.getValue()
            },
        })
    }

    /**
     * Add new layout to the bottom of the Section.
     */
    this._addLayout = function(layout) {
        element.append(JSON.parse(layout));
    }

    /**
     * Truncate Section Variables.
     */
    this._truncateVariables = function(variables) {
        /* Loop through each element of variables array. */
        variables.forEach(function(_var) {
            /* Check if the variable is not undefined. */
            if(typeof _var !== 'undefined') {
                /* Truncate variable */
                _var = null;
            }
        });
    } 

    /**
     * Delete Section Layouts.
     */
    this._deleteLayouts = function(variables) {
        /* Loop through each element of variables array. */
        variables.forEach(function(_var) {
            /* Check if the variable is not undefined. */
            if(typeof _var !== 'undefined') {
                /* Delete part */
                /* Check if variable can be looped or not. */
                if(typeof _var.length !== 'undefined') {
                    /* If yes, loop through each element and call to delete function. */
                    _var.forEach(function(objectElement) {
                        objectElement.delete();
                    })
                /* If variable can not be looped, call to delete function. */
                } else {
                    _var.delete();
                }   
            }
        });
    }

    /**
     * Check if layout exist or not via class name.
     */
    this._checkLayoutExist = function(className) {
        if(element.find(className).length > 0) return true;
        return false;
    } 

    /**
     * Update index of the section whenever another section is deleted.
     */
    this.updateIndex = function() {

        index = section.getActualIndex();

    }

    /**
     * Get current index of the section.
     */
    this.getCurrentIndex = function() {

        return index;

    }

    /**
     * Get actual index of the section.
     */
    this.getActualIndex = function() {

        return element.attr('data-index');

    }

}

export default Section;

