var Layout = function() {

	/**
     * Get default dfi layout.
     */
    this.getDefaultDFILayout = function(index) {
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
     * Get related layout of a dfi, related layout include taxonomies, image upload and image size.
     */
    this.getRelatedDFILayout = function(dfiIndex, postType) {
        return $.ajax({
            url: '/wp-admin/admin-ajax.php?action=wpdfi_get_related_layout',
            method: 'POST',
            data: {
                dfi_index: dfiIndex,
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

    /**
     * Get delete button layout.
     */
    this.getDeleteButtonLayout = function() {
        return '<a href="#" class="btn-remove">-</a>';
    }

}

export default Layout;