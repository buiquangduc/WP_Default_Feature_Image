var Helper = function() {

	/**
	 * Reindex input of the section. 
	 */
	this.reindexSectionInput = function(sectionElement, oldIndex, newIndex) {

		sectionElement.find('input').each(function(){
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
	
	};	

	/**
	 * Reindex select input of the section. 
	 */
	this.reindexSectionSelect = function(sectionElement, oldIndex, newIndex) {

		sectionElement.find('select').each(function(){

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
	 * Update new id for the Section.
	 */
	this.updateSectionId = function(sectionElement, oldIndex, newIndex) {

		var oldId = sectionElement.attr('id');
		var validId = (typeof oldId != 'undefined');
		/* Need to check id is valid or not before update the new id. */
		if(validId) {
			/* Get the new id of the section by replace old index with new index. */
			var newId = oldId.replace(oldIndex, newIndex);
			/* Update the new id of the section */
			sectionElement.attr('id', newId);
		}
	}

	/**
	 * Update section label with new index.
	 */
	this.updateSectionLabel = function(sectionElement, newIndex) {

		var labelClass = '.section-label';
		var label = sectionElement.find(labelClass);
		/* Remove current label. */
		label.empty();
		/* Update new label. */ 
		label.text('Section ' + newIndex);

	}
	
	/**
	 * Update new index for the Section.
	 */
	this.updateSectionIndex = function(sectionElement, newIndex) {

		sectionElement.attr('data-index', newIndex);

	}

}

export default Helper;