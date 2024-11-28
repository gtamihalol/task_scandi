document.addEventListener('DOMContentLoaded', () => {
    const productType = document.getElementById('productType');
    const typeSpecificFields = document.querySelectorAll('.type-specific');

    productType.addEventListener('change', () => {
        typeSpecificFields.forEach(field => field.style.display = 'none');
        const selectedType = productType.value;
        if (selectedType) {
            document.getElementById(selectedType).style.display = 'block';
        }
    });
});
