var Layout = function() {

	/**
     * Get default section layout.
     */
    this.getDefaultSectionLayout = function(index) {
        return $.ajax({
            url: '/wp-admin/admin-ajax.php?action=wpdfi_get_default_layout',
            method: 'POST',
            data: {
                index: index,
                include_delete: true
            },
        })
    }

    /**
     * Get related layout of a section, related layout include taxonomies, image upload and image size.
     */
    this.getRelatedSectionLayout = function(sectionIndex, postType) {
        return $.ajax({
            url: '/wp-admin/admin-ajax.php?action=wpdfi_get_related_layout',
            method: 'POST',
            data: {
                section_index: sectionIndex,
                post_type: postType
            },
        })
    }

    /**
     * Get errors wrapper layout.
     */
    this.getErrorListingWrapperLayout = function() {
        return '<ul class="error-list">%error-list-markup%</ul>';
    }

    /**
     * Get single error listing layout.
     */
    this.getErrorSingleListingLayout = function(errorContent) {
        return '<li class="single-error">' + errorContent + '</li>';
    }

}

export default Layout;