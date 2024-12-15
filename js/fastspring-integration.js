document.addEventListener("DOMContentLoaded", () => {
    // Initialize FastSpring Storefront
    if (window.FastSpring && fastspringConfig.storeId) {
        window.FastSpring.builder = FastSpring.builder(fastspringConfig.storeId);
    }

    // Handle button clicks
    const fastSpringButtons = document.querySelectorAll("[data-fsc-item-path-value]");
    fastSpringButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            e.preventDefault(); // Prevent default link action

            const itemPath = button.getAttribute("data-fsc-item-path-value");
            const action = button.getAttribute("data-fsc-action");

            if (window.FastSpring) {
                FastSpring.builder.add(itemPath, {
                    quantity: 1,
                });

                if (action && action.toLowerCase().includes("checkout")) {
                    FastSpring.builder.checkout();
                }
            }
        });
    });
});
