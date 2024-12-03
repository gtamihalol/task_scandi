document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('productType').addEventListener('change', function () {
        const typeSpecificFields = document.getElementById('type-specific-fields');
        typeSpecificFields.innerHTML = ''; // Очистить текущие поля

        const type = this.value;

        console.log(type); // Добавьте вывод в консоль для отладки

        if (type === 'DVD') {
            typeSpecificFields.innerHTML = `
                <label for="size">Size (MB)</label>
                <input type="number" id="size" name="size" required>
                <p>Product description: Please provide the size of the DVD in megabytes.</p>
            `;
        } else if (type === 'Book') {
            typeSpecificFields.innerHTML = `
                <label for="weight">Weight (KG)</label>
                <input type="number" id="weight" name="weight" required>
                <p>Product description: Please provide the weight of the book in kilograms.</p>
            `;
        } else if (type === 'Furniture') {
            typeSpecificFields.innerHTML = `
                <label for="height">Height (CM)</label>
                <input type="number" id="height" name="height" required>

                <label for="width">Width (CM)</label>
                <input type="number" id="width" name="width" required>

                <label for="length">Length (CM)</label>
                <input type="number" id="length" name="length" required>
                <p>Product description: Please provide dimensions in HxWxL format.</p>
            `;
        }
    });
});
