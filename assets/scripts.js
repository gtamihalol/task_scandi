        function handleTypeSwitcherChange() {
            const typeSwitcher = document.getElementById("productType");
            const selectedType = typeSwitcher.value;

            // Hide all type-specific fields
            document.querySelectorAll(".type-specific").forEach(el => {
                el.style.display = "none";
            });

            // Show the fields for the selected type
            if (selectedType) {
                const typeFields = document.getElementById(selectedType);
                if (typeFields) {
                    typeFields.style.display = "block";
                }
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            handleTypeSwitcherChange(); // Trigger on page load
            document.getElementById("productType").addEventListener("change", handleTypeSwitcherChange);
        });
