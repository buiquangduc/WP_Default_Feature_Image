import Section from './section.js';
import Layout from './layout.js';

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
     * layout variable is created to handle all rendering layout stuff for the Form.
     */
    var layout = new Layout();

    /**
     * Initialize actions when create a single instance of Form.
     */
    this.init = function() {
        /* First: Assign all exist sections to sections variable. */
        form._assignExistSections();
        /* Second: Validate all the data when submit the Form. */
        form._validate();
        /* Third: Bind add new section feature when click on Add Section Button. */
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
            /* Second: 
             *    Call an AJAX to get a default layout of a section .
             *    Display the default layout we just received in the bottom of the section wrapper.
             *    Initialize new section.
             *    Update Form's section variable.
             */
            layout.getDefaultSectionLayout(totalSection + 1).done(function(html){
                form._addNewLayout(html);

                var section = new Section(totalSection+1, form);
                section.init();

                form._updateSectionVariable(section);
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

            if(!section.validate()) 
                flag = false;
                form._updateErrors(section.getErrors());
                section.truncateErrors();
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
        $(JSON.parse(html)).appendTo(sectionWrapper).slideDown('300');

    }

    /**
     * Assign all exist section to section variable
     */
    this._assignExistSections = function() {

        element.find('.item-option').each(function(index) {

            /* Need to + 1 to sync with section length, because index start at 0 */
            var section = new Section(index+1, form);
            section.init();

            form._updateSectionVariable(section);

        });

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
     * Submit the Form
     */
    this._submit = function() {

        element.submit();

    }

    this.removeSection = function(sectionIndex) {

        sections.splice(sectionIndex, 1);

    }

}

export default Form;