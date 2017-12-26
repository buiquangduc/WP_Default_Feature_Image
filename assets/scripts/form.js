import Section from './section.js';
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
     * addButton variable is created to represent Add Section Button of the Form.
     */
    var addButton = element.find('#add_section_button');

    /**
     * saveButton variable is created to represent Save Button of the Form.
     */
    var saveButton = element.find('#save_form_button');

    /**
     * sections variable is created to represent all sections exist on the Form.
     */
    var sections = [];

    /**
     * sectionWrapper variable is created to represent wrapper element of all sections exist on Form.
     */
    var sectionWrapper = element.find('#section_wrapper');

    /**
     * errorWrapper variable is created to represent wrapper element of errors all exist on Form.
     */
    var errorWrapper = element.find('#error_wrapper');

    /**
     * errors variable is created to temporary store all the errors of the Form.
     */
    var errors = [];

    /**
     * sectionClass variable is created to represent section class, for better readable.
     */
    var sectionClass = '.item-option';

    /** 
     * layout variable is created to handle all rendering layout stuff for the Form.
     */
    var layout = new Layout();

    /**
     * Initialize actions when create a single instance of Form.
     */
    this.init = function() {
        /* Reindex all the sections (if needed). */
        form._reindexSections();
        /* Assign all exist sections to sections variable. */
        form._assignExistSections();
        /* Initialize Sortable JS to reorder form's sections. */
        form._initilizeSortable();
        /* Initialize Tooltip. */
        form._initilizeTooltip();
        /* Validate all the data when submit the Form. */
        form._validate();
        /* Bind add new section feature when click on Add Section Button. */
        form._addNewSection();
    }

    /**
     * Add new section actions when user click on Add Section Button.
     */
    this._addNewSection = function() {

        addButton.click(function(e) {
            e.preventDefault();
            /* First: count total sections, assign it to a temporary variable totalSection. */
            var totalSection = form._countTotalSections();
            /* Second: disable add button, don't let user click multiple times. */
            form._disableAddButton();
            /* Third: 
             *    Call an AJAX to get a default layout of a section .
             *    Display the default layout we just received in the bottom of the section wrapper.
             *    Initialize new section.
             *    Update Form's section variable.
             *    Enable add button.
             */
            layout.getDefaultSectionLayout(totalSection + 1).done(function(html){

                form._addNewLayout(html);

                var section = new Section(totalSection+1, form);
                section.init();

                form._updateSectionVariable(section);

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
            if(form._validateSections()) {
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
     * Validate data from all the sections
     */
    this._validateSections = function() {
        /**
         * Create a loop to loop through all exist sections.
         * Validate the sections, return false if any section is invalid.
         * Store all errors of all sections.
         * Truncate the current errors of all sections
         */
        var flag = true;
        sections.forEach(function(section){
            if(!section.validate())  {
                flag = false;
                form._updateErrors(section.getErrors());
                section.truncateErrors();
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
        /* Display the layout passed via parameter to the bottom of section wrapper. */
        $(JSON.parse(html)).appendTo(sectionWrapper);

    }

    /**
     * Assign all exist section to section variable
     */
    this._assignExistSections = function() {

        element.find(sectionClass).each(function() {

            var sectionIndex = $(this).attr('data-index');
            var section = new Section(sectionIndex, form);
            section.init();

            form._updateSectionVariable(section);

        });

    }

    /**
     * Initialize Sortable to reorder form's sections.
     */
    this._initilizeSortable = function() {
        var sortableWrapper = section_wrapper;
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
                    form._sortSectionsVariable();
                    form._reindexSections();

                    /** 
                     * Check if the draggedIndex or droppedIndex equal to 0.
                     * It mean that draggedSectionElement or droppedSectionElement is the first section. 
                     * Add delete button on the new first section and remove delete button on the opposite section.
                     * Bind delete event on the new first section.
                     */
                    var draggElFirst = (draggedIndex == 0);
                    var droppElFirst = (droppedIndex == 0);
                    if(draggElFirst || droppElFirst) {

                        if(draggElFirst) {

                            sections[draggedIndex].removeDeleteButton();
                            sections[droppedIndex].addDeleteButton();
                            sections[droppedIndex].onDelete();

                        } else if (droppElFirst) {

                            sections[droppedIndex].removeDeleteButton();
                            sections[draggedIndex].addDeleteButton();
                            sections[draggedIndex].onDelete();

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
     * Count total number of sections.
     */
    this._countTotalSections = function() {

        return sections.length;

    }

    /**
     * Update value for sections variable of the Form.
     */
    this._updateSectionVariable = function(section) {

        sections.push(section);

    }

    /**
     * Submit the Form.
     */
    this._submit = function() {

        element.submit();

    }

    /**
     * Remove a section from the Form.
     */
    this.removeSection = function(sectionIndex) {

        /**
         * First: we need to remove the section from sections variable.
         * Second: we need to reindex all the sections element of the Form (Html).
         * Third: we need to reindex all the sections object of the Form (Javascript Object).
         */
        form._rmSectionInSectionsVar(sectionIndex);
        form._reindexSections();

    }

    /**
     * Remove a section from sections variable.
     */
    this._rmSectionInSectionsVar = function(sectionIndex) {

        sections.splice(sectionIndex, 1);

    }

    /**
     * Check and reindex all sections.
     */
    this._reindexSections = function() {

        /**
         * Loop through all exist section element.
         * Reindex the section via index of the element.
         */
        sections.forEach(function(section, index){
            
            var sectionIndex = index + 1;
            var indexDifferent = (sectionIndex != section.getIndex());
            if(indexDifferent) {
                section.reindex(sectionIndex);
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
     * Sort section variable after drag and drop.
     */
    this._sortSectionsVariable = function() {

        var tempSection = [];
        element.find(sectionClass).each(function(elementIndex){

            var sectionElement = $(this);
            var oldIndex = sectionElement.attr('data-index') - 1;
            var newIndex = elementIndex;
            var indexDifferent = (oldIndex != newIndex);
            if(indexDifferent) {
                tempSection[newIndex] = sections[newIndex];
                if(typeof tempSection[oldIndex] == 'undefined') {
                    sections[newIndex] = sections[oldIndex];
                } else {
                    sections[newIndex] = tempSection[oldIndex];
                }
            }

        });

    }

}

export default Form;