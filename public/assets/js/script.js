document.addEventListener('DOMContentLoaded', () => {
    // Select all reset buttons with type="button" and class "btn-danger"
    const clearBtns = document.querySelectorAll('.btn-danger[type="button"]');
    
    clearBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Find the closest form to the button and reset it
            const form = this.closest('form');
            if (form) {
                form.reset();
            }
        });
    });
});
