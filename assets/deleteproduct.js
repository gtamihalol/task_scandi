document.addEventListener('DOMContentLoaded', function() {
    const deleteButton = document.getElementById('delete-product-btn');
    const cancelButton = document.getElementById('cancel-delete-btn');
    
    if (deleteButton) {
        deleteButton.addEventListener('click', function () {
            if (confirm("Are you sure you want to delete selected products?")) {
                document.getElementById('product-list-form').submit();
            }
        });
    }

    if (cancelButton) {
        cancelButton.addEventListener('click', function () {
        });
    }
});

