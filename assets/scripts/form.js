import DFI from './dfi.js';
import Layout from './layout.js';
import Sortable from 'sortablejs';

var Form = function(id) {

    /**
     * form variable is created to represent Form instance.
     */
    var form = this;

    /**
     * element variable is created to be a master element of the Form, all the variables will be found via element variable
     * Eg: element.find('.btn-add')
     */
    var element = $(id);

    /**
     * addButton variable is created to represent Add DFI Button of the Form.
     */
    var addButton = element.find('#add_dfi_button');

    /**
     * saveButton variable is created to represent Save Button of the Form.
     */
    var saveButton = element.find('#save_form_button');

    /**
     * dfis variable is created to represent all dfis exist on the Form.
     */
    var dfis = [];

    /**
     * dfiWrapper variable is created to represent wrapper element of all dfis exist on Form.
     */
    var dfiWrapper = element.find('#dfi_wrapper');

    /**
     * errorWrapper variable is created to represent wrapper element of errors all exist on Form.
     */
    var errorWrapper = element.find('#error_wrapper');

    /**
     * errors variable is created to temporary store all the errors of the Form.
     */
    var errors = [];

    /**
     * dfiClass variable is created to represent dfi class, for better readable.
     */
    var dfiClass = '.item-option';

    /** 
     * layout variable is created to handle all rendering layout stuff for the Form.
     */
    var layout = new Layout();

    /**
     * Initialize actions when create a single instance of Form.
     */
    this.init = function() {
        /* Reindex all the dfis (if needed). */
        form._reindexDFIs();
        /* Assign all exist dfis to dfis variable. */
        form._assignExistDFIs();
        /* Initialize Sortable JS to reorder form's dfis. */
        form._initilizeSortable();
        /* Initialize Tooltip. */
        form._initilizeTooltip();
        /* Validate all the data when submit the Form. */
        form._validate();
        /* Bind add new dfi feature when click on Add DFI Button. */
        form._addNewDFI();
    }

    /**
     * Add new dfi actions when user click on Add DFI Button.
     */
    this._addNewDFI = function() {

        addButton.click(function(e) {
            e.preventDefault();
            /* First: count total dfis, assign it to a temporary variable totalDFI. */
            var totalDFI = form._countTotalDFIs();
            /* Second: disable add button, don't let user click multiple times. */
            form._disableAddButton();
            /* Third: 
             *    Call an AJAX to get a default layout of a dfi .
             *    Display the default layout we just received in the bottom of the dfi wrapper.
             *    Initialize new dfi.
             *    Update Form's dfi variable.
             *    Enable add button.
             */
            layout.getDefaultDFILayout(totalDFI + 1).done(function(html){

                form._addNewLayout(html);

                var dfi = new DFI(totalDFI+1, form);
                dfi.init();

                form._updateDFIVariable(dfi);

                form._enableAddButton();

                form._initilizeTooltip();

            });
            

        });

    }

    /**
     * Validate all the data when submit the Form
     */
    this._validate = function() {

        saveButton.click(function(e){
            e.preventDefault();
            
            /* Check if form input is valid or not. */
            if(form._validateDFIs()) {
                /* If yes: submit the form. */
                form._submit();
            } else {
                /**
                 * If no:
                 *    Remove all exist errors on FE.
                 *    Add new errors on FE.
                 *    Truncate errors variable after display error on FE.
                 */
                form._removeExistErrors();
                form._addNewErrors();
                form._truncateErrorsVariable();
            }
            
        });
    }

    /**
     * Validate data from all the dfis
     */
    this._validateDFIs = function() {
        /**
         * Create a loop to loop through all exist dfis.
         * Validate the dfis, return false if any dfi is invalid.
         * Store all errors of all dfis.
         * Truncate the current errors of all dfis
         */
        var flag = true;
        dfis.forEach(function(dfi){
            if(!dfi.validate())  {
                flag = false;
                form._updateErrors(dfi.getErrors());
                dfi.truncateErrors();
            }   
        });

        return flag;
    }

    /**
     * Remove all current exits errors on FE.
     */
    this._removeExistErrors = function() {

        errorWrapper.empty();
    
    }

    /**
     * Truncate errors variables
     */
    this._truncateErrorsVariable = function() {
        errors = [];
    }

    /**
     * Add new error on FE
     */
    this._addNewErrors = function() {
        /**
         * Create a loop to loop through all exist errors.
         * For each error, create a list item to display the error.
         * Display the new errors in error wrapper on FE.
         */
        /* Get error listing wrapper */
        var errorListingWrapper = layout.getErrorListingWrapperLayout();
        var errorListing = '';
        /* Get error listing */
        errors.forEach(function(error){
            errorListing += layout.getErrorSingleListingLayout(error);
        });
        /* Get errors layout by replace markup in error listing wrapper with error listing */
        var errorsLayout = errorListingWrapper.replace('%error-list-markup%', errorListing);
        /* Display errors layout */
        errorWrapper.append(errorsLayout);
    }

    /**
     * Update value for errors variable of the Form.
     */
    this._updateErrors = function(error) {
        error.forEach(function(err){

            errors.push(err);

        });

    }

    /**
     * Add new layout to the Form
     */
    this._addNewLayout = function(html) {
        /* Display the layout passed via parameter to the bottom of dfi wrapper. */
        $(JSON.parse(html)).appendTo(dfiWrapper);

    }

    /**
     * Assign all exist dfi to dfi variable
     */
    this._assignExistDFIs = function() {

        element.find(dfiClass).each(function() {

            var dfiIndex = $(this).attr('data-index');
            var dfi = new DFI(dfiIndex, form);
            dfi.init();

            form._updateDFIVariable(dfi);

        });

    }

    /**
     * Initialize Sortable to reorder form's dfis.
     */
    this._initilizeSortable = function() {
        var sortableWrapper = dfi_wrapper;
        Sortable.create(sortableWrapper, { 

            animation: 150,
            // Element dragging ended
            onEnd: function (evt) {
                var droppedIndex = evt.oldIndex;
                var draggedIndex = evt.newIndex;
                var indexDifferent = (droppedIndex != draggedIndex);
                /* Check if old index is not the same as old index. */
                if(indexDifferent) {
                    /* Swap two element. */
                    form._sortDFIsVariable();
                    form._reindexDFIs();

                    /** 
                     * Check if the draggedIndex or droppedIndex equal to 0.
                     * It mean that draggedDFIElement or droppedDFIElement is the first dfi. 
                     * Add delete button on the new first dfi and remove delete button on the opposite dfi.
                     * Bind delete event on the new first dfi.
                     */
                    var draggElFirst = (draggedIndex == 0);
                    var droppElFirst = (droppedIndex == 0);

                    if(draggElFirst || droppElFirst) {

                        if(draggElFirst) {
                            /**
                             * 0 - 1 - 2
                             * swap 2 with 0.
                             * 0 -> 1, 2 -> 0
                             * Remove delete button on 0, add delete button on 1.
                             */
                            dfis[0].removeDeleteButton();
                            dfis[1].addDeleteButton();
                            dfis[1].onDelete();

                        } else if (droppElFirst) {
                            /**
                             * 0 - 1 - 2
                             * swap 0 with 2.
                             * 1 -> 0, 0 -> 2
                             * Remove delete button on 0, add delete button on 2.
                             */
                            dfis[0].removeDeleteButton();
                            dfis[draggedIndex].addDeleteButton();
                            dfis[draggedIndex].onDelete();

                        }

                    } 
                
                }
            
            },

        });

    }

    this._initilizeTooltip = function() {
        $('[data-toggle="tooltip"]').tooltip();
    }

    /**
     * Count total number of dfis.
     */
    this._countTotalDFIs = function() {

        return dfis.length;

    }

    /**
     * Update value for dfis variable of the Form.
     */
    this._updateDFIVariable = function(dfi) {

        dfis.push(dfi);

    }

    /**
     * Submit the Form.
     */
    this._submit = function() {

        element.submit();

    }

    /**
     * Remove a dfi from the Form.
     */
    this.removeDFI = function(dfiIndex) {

        /**
         * First: we need to remove the dfi from dfis variable.
         * Second: we need to reindex all the dfis element of the Form (Html).
         * Third: we need to reindex all the dfis object of the Form (Javascript Object).
         */
        form._rmDFIInDFIsVar(dfiIndex);
        form._reindexDFIs();

    }

    /**
     * Remove a dfi from dfis variable.
     */
    this._rmDFIInDFIsVar = function(dfiIndex) {

        dfis.splice(dfiIndex, 1);

    }

    /**
     * Check and reindex all dfis.
     */
    this._reindexDFIs = function() {

        /**
         * Loop through all exist dfi element.
         * Reindex the dfi via index of the element.
         */
        dfis.forEach(function(dfi, index){
            
            var dfiIndex = index + 1;
            var indexDifferent = (dfiIndex != dfi.getIndex());
            if(indexDifferent) {
                dfi.reindex(dfiIndex);
            }

        })

    }

    /**
     * Disable add button.
     */
    this._disableAddButton = function() {

        addButton.css('pointer-events', 'none');

    }

    /**
     * Enable add button.
     */
    this._enableAddButton = function() {

        addButton.css('pointer-events', 'visible');

    }

    /**
     * Sort dfi variable after drag and drop.
     */
    this._sortDFIsVariable = function() {

        var tempDFI = [];
        element.find(dfiClass).each(function(elementIndex){

            var dfiElement = $(this);
            var oldIndex = dfiElement.attr('data-index') - 1;
            var newIndex = elementIndex;
            var indexDifferent = (oldIndex != newIndex);
            if(indexDifferent) {
                tempDFI[newIndex] = dfis[newIndex];
                if(typeof tempDFI[oldIndex] == 'undefined') {
                    dfis[newIndex] = dfis[oldIndex];
                } else {
                    dfis[newIndex] = tempDFI[oldIndex];
                }
            }

        });

    }

}

export default Form;