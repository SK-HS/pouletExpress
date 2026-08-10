document.addEventListener('DOMContentLoaded', () => {
    // Add micro-interactions for inputs across the catalog or globally
    document.querySelectorAll('input, select, textarea').forEach(input => {
        input.addEventListener('focus', () => {
            const label = input.parentElement.querySelector('label');
            if(label) label.classList.add('text-primary');
        });
        input.addEventListener('blur', () => {
            const label = input.parentElement.querySelector('label');
            if(label) label.classList.remove('text-primary');
        });
    });
});
