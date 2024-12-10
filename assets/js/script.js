document.addEventListener("DOMContentLoaded", () => {
    const deleteButtons = document.querySelectorAll("button.delete");

    deleteButtons.forEach(button => {
        button.addEventListener("click", () => {
            if (!confirm("Are you sure you want to delete this?")) {
                event.preventDefault();
            }
        });
    });
});
