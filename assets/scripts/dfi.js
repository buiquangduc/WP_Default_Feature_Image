import ImageUpload from './imageupload.js';
import Taxonomy from './taxonomy.js';
import PostType from './posttype.js';
import Layout from './layout.js';

var DFI = function(dfiIndex, parentForm) {

    /**
     * dfi variable is created to represent DFI instance.
     */
    var dfi = this;

    /**
     * index variable is created to represent DFI index.
     */
    var index = dfiIndex;

    /**
     * id variable is create to represent id of the DFI
     */
    var id = '#item-option-origin-' + index;

    /**
     * element variable is created to be a master element of the DFI, all the variable will be found via element variable.
     */
    var element = $(id);

    /**
     * postType variable is created to represent Post Type part of the DFI.
     */
    var postType;

    /**
     * taxonomies variable is created to represent all Taxonomy parts of the DFI.
     */
    var taxonomies;

    /**
     * imageUpload variable is created to represent ImageUpload part of the DFI.
     */
    var imageUpload;

    /**
     * deleteButton variable is created to represent Delete Buton of the DFI.
     */
    var deleteButton;

    /**
     * errors variable is temporary variable to store all errors of the DFI.
     */ 
    var errors = [];

    /** 
     * layout variable is created to handle all rendering layout stuff for the DFI.
     */
    var layout = new Layout();

    /**
     * Initialize actions when create a instance of the DFI.
     */
    this.init = function() {
        /* Assign all exist layout to corresponding variable. */
        dfi._checkLayoutAndAssignVariable();
        /**
         * Check if the DFI has a Post Type part.
         *    If yes, Bind on selected feature when user changed the value of the post type.
         */
        if(typeof postType !== 'undefined') { dfi._onSelectedPostType(); }
        /* 
         * Check if the DFI has a Delete Button.
         *    If yes, Bind delete feature when user click on Delete Button.
         */
        if(typeof deleteButton !== 'undefined') { dfi.onDelete(); }
    }

    /**
     * Check all exist parts and assign it to corresponding variable.
     */
    this._checkLayoutAndAssignVariable = function() {
        /* First: Check if PostType part exist.
         *    If yes, assign PostType part to postType variable.
         */
        if(dfi._checkLayoutExist('.post-type-row')) {
            postType = new PostType(id+' > .post-type-row');
        }

        /* Second: Check if Taxonomy parts exist.
         *    If yes, assign Taxonomy part to taxonomies variable.
         */
        if(dfi._checkLayoutExist('.taxonomy-row')) {
            var _taxonomies = [];
            $(id+' > .taxonomy-row').each(function(index, value){
                _taxonomies.push(new Taxonomy(value));
            });      
            taxonomies = _taxonomies;
        }
            

        /* Third: Check if ImageUpload part exist.
         *    If yes, assign ImageUpload part to imageUpload variable.
         */
        if(dfi._checkLayoutExist('.image-upload-row')) {
            imageUpload = new ImageUpload(id+' > .image-upload-row');
        }

        /* Fourth: Check if Delete Button exist.
         *    If yes, assign deleteButton variable to Delete Button.
         */
        if(dfi._checkLayoutExist('.btn-remove')) {
            deleteButton = $(id+' > .btn-remove');
        }
    }

    /**
     * Validate input data of the DFI.
     */
    this.validate = function() {
        /**
         * Check if PostType value is exist.
         *    If PostType value is exist, check if ImageUpload value is exist.
         *       If ImageUpload value is not exist, store an error in errors variable, then return false.
         *    If PostType value is not exist, store an error in errors variable, then return false.
         */
        if(!postType.getValue()) {
            dfi._storeError('Post Type value on DFI ' + index + ' must be not empty');
            return false;
        } else {
            if(!imageUpload.getId() || !imageUpload.getSource()) {
                dfi._storeError('Uploaded image value on DFI ' + index + ' must be not empty');
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
     * Truncate errors variable to store new errors of the DFI.
     */
    this.truncateErrors = function() {
    
        errors = [];
    
    }

    /**
     * Return the errors of the DFI.
     */
    this.getErrors = function() {
        /* Return the errors variable. */
        return errors;
    }


    /**
     * Bind delete feature when click on Delete Button.
     */
    this.onDelete = function() {
        deleteButton.click(function(event){
            /* Delete the dfi element. */
            event.preventDefault();
            element.slideUp( '300', function() {
                element.remove();
                /** 
                 * Remove dfi from parent form.
                 * Note that javascript array index start from 0, so we need to minus 1
                 */
                parentForm.removeDFI(index - 1);
            });
        });
    }

    /**
     * Actions when user select a post type.
     */
    this._onSelectedPostType = function() {
        /* Bind actions when user select a post type. */
        postType.selectElement.change(function() { 
            /* First: Delete related layout. */
            dfi._deleteLayouts([taxonomies, imageUpload]);
            /* Second: Truncate related variables. */
            dfi._truncateVariables(['taxonomies', 'imageUpload']);
            /**
             * Third: 
             *    Check if selected value is not the blank value
             *    If yes:
             *        Get related layout.
             *        Add layout just received to the bottom of the dfi.
             *        Redo checkLayoutAndAssignVariable.
             */
            if(postType.getValue()) {
                layout.getRelatedDFILayout(index, postType.getValue())
                      .done(dfi._addLayout)
                      .then(dfi._checkLayoutAndAssignVariable);
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
     * Add new layout to the bottom of the DFI.
     */
    this._addLayout = function(layout) {
        element.append(JSON.parse(layout));
    }

    /**
     * Truncate DFI Variables.
     */
    this._truncateVariables = function(variables) {
        /**
         * We will need to modify the variable of the object, so we need to do something like pointer in this situation.
         * Unfortunately, there is no pointer in javascript. So I will use eval() function in this situation.
         */

        /* Loop through each element of variables array. */
        variables.forEach(function(_var) {
            /* Check if the variable is not undefined. */
            if(eval("typeof " + _var + " != 'undefined'")) {
                eval(_var + ' = null');
            }
            
        });
    } 

    /**
     * Delete DFI Layouts.
     */
    this._deleteLayouts = function(variables) {

        /* Loop through each element of variables array. */
        variables.forEach(function(_var) {
            /* Check if the variable is not undefined. */
            if(typeof _var !== 'undefined' && _var) {
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
        if(element.find(className).length > 0) { return true; }
        return false;
    } 

    /**
     * Get ID of the dfi.
     */
    this.getId = function() {

        return id;

    }

    /**
     * Get data index of the dfi.
     */
    this.getIndex = function() {

        return index;

    }

    /**
     * Remove delete button.
     */ 
    this.removeDeleteButton = function() {

        dfi._removeDeleteButtonEl();

    }

    /** 
     * Remove delete button element.
     */
    this._removeDeleteButtonEl = function() {

        deleteButton.remove();
        dfi._truncateVariables(['deleteButton']);

    }

    /**
     * Add new delete button.
     */
    this.addDeleteButton = function() {

        dfi._addDeleteButtonEl();
        dfi._updateDeleteButtonVar();

    }

    /**
     * Add new delete button element to the dfi.
     */
    this._addDeleteButtonEl = function() {

        var newDeleteButton = layout.getDeleteButtonLayout();
        console.log(newDeleteButton);
        element.append(newDeleteButton);

    }

    /**
     * Update delete button variable.
     */
    this._updateDeleteButtonVar = function() {

        deleteButton = element.find('.btn-remove');
    
    }

    this.reindex = function(newIndex) {
        var oldIndex = index;
        index = newIndex;
        dfi._updateID();
        dfi._updateElement();
        dfi._updateDataIndex();
        dfi._updateInput(oldIndex, newIndex);
        dfi._updateSelect(oldIndex, newIndex);
        dfi._updateLabel();
        dfi._truncateVariables(['postType', 'taxonomies', 'imageUpload', 'deleteButton']);
        dfi._checkLayoutAndAssignVariable();

    }

    /**
     * Update ID of the dfi.
     */
    this._updateID = function() {

        id = '#item-option-origin-' + index;

    }

    /**
     * Update Element of the dfi.
     */
    this._updateElement = function() {
        var idVal = id.replace('#', '');
        element.attr('id', idVal);
        element = $(id);

    }

    /**
     * Update attribute data-index of the dfi.
     */
    this._updateDataIndex = function() {

        element.attr('data-index', index);

    }

    /**
     * Reindex input of the dfi. 
     */
    this._updateInput = function(oldIndex, newIndex) {

        element.find('input').each(function(){
            var oldName = $(this).attr('name');
            var validName = (typeof oldName != 'undefined');
            /* Need to check name is valid or not before update the new name for the input. */
            if(validName) {
                /* Get the new name of the input by replace old index with new index. */
                var newName = oldName.replace(oldIndex, newIndex);
                /* Update new name for the input. */
                $(this).attr('name', newName);
            }

        });

    }

    /**
     * Reindex select input of the dfi. 
     */
    this._updateSelect = function(oldIndex, newIndex) {

        element.find('select').each(function(){

            var oldName = $(this).attr('name');
            var validName = (typeof oldName != 'undefined');
            /* Need to check name is valid or not before update the new name for the select input. */
            if(validName) {
                /* Get the new name of the select input by replace old index with new index. */
                var newName = oldName.replace(oldIndex, newIndex);
                /* Update new name for the select input. */
                $(this).attr('name', newName);
            }
        });

    };

    /**
     * Update dfi label with new index.
     */
    this._updateLabel = function() {
        var labelClass = '.dfi-label';
        var label = element.find(labelClass);
        /* Remove current label. */
        label.empty();
        /* Update new label. */ 
        label.text('DFI ' + index);

    }
}

export default DFI;

