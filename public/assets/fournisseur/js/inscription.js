document.addEventListener('DOMContentLoaded', () => {
    let currentStep = 1;
    const totalSteps = 3;
    const formSteps = document.querySelectorAll('.form-step');
    const indicators = document.querySelectorAll('.step-indicator');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');

    if (!nextBtn || !prevBtn || formSteps.length === 0) return;

    function updateStep() {
        formSteps.forEach((step, idx) => {
            step.classList.toggle('active', idx + 1 === currentStep);
        });

        indicators.forEach((ind, idx) => {
            ind.classList.remove('active', 'completed');
            if (idx + 1 === currentStep) {
                ind.classList.add('active');
            } else if (idx + 1 < currentStep) {
                ind.classList.add('completed');
                ind.innerHTML = '<span class="material-symbols-outlined text-lg">check</span>';
            } else {
                ind.innerHTML = idx + 1;
            }
        });

        prevBtn.classList.toggle('hidden', currentStep === 1);
        
        if (currentStep === totalSteps) {
            nextBtn.innerText = "Finaliser l'inscription";
            nextBtn.classList.add('bg-status-success');
            nextBtn.classList.remove('bg-primary');
        } else {
            nextBtn.innerText = "Suivant";
            nextBtn.classList.remove('bg-status-success');
            nextBtn.classList.add('bg-primary');
        }
    }

    nextBtn.addEventListener('click', () => {
        if (currentStep < totalSteps) {
            currentStep++;
            updateStep();
        } 
        else {
            // Handle submission
            nextBtn.disabled = true;
            nextBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> Traitement...';
            // setTimeout(() => {
            //     alert("Votre dossier est en cours de validation. Vous recevrez une confirmation par SMS d'ici 24h.");
            //     window.location.href = 'index.html';
            // }, 2000);
            document.getElementById('regForm').submit();
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            updateStep();
        }
    });
});
