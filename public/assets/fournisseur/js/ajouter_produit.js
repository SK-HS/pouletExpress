document.addEventListener('DOMContentLoaded', () => {
    // Dropzone micro-interactions
    const dropZone = document.getElementById('drop-zone');
    
    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('drop-zone-active'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('drop-zone-active'), false);
        });

        dropZone.addEventListener('click', () => {
            const input = dropZone.querySelector('input');
            if(input) input.click();
        });
    }
});
