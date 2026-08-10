
<script>
    
        const track = document.getElementById('carouselTrack');
        const dots = document.querySelectorAll('#carouselDots button');
        const prevBtn = document.getElementById('prevSlide');
        const nextBtn = document.getElementById('nextSlide');
        let currentIndex = 0;
        const totalSlides = 3;
        let autoSlideInterval;

        function updateCarousel(index) {
            currentIndex = (index + totalSlides) % totalSlides;
            if (track) track.style.transform = `translateX(-${currentIndex * 100}%)`;
            dots.forEach((dot, i) => {
                dot.className = i === currentIndex ? 'w-3 h-3 rounded-full bg-white transition-all scale-125' : 'w-3 h-3 rounded-full bg-white/50 transition-all hover:bg-white';
            });
        }

        function startAutoSlide() {
            clearInterval(autoSlideInterval);
            autoSlideInterval = setInterval(() => updateCarousel(currentIndex + 1), 5000);
        }

        if (prevBtn && nextBtn) {
            prevBtn.addEventListener('click', () => { updateCarousel(currentIndex - 1); startAutoSlide(); });
            nextBtn.addEventListener('click', () => { updateCarousel(currentIndex + 1); startAutoSlide(); });
            dots.forEach((dot, index) => dot.addEventListener('click', () => { updateCarousel(index); startAutoSlide(); }));
            startAutoSlide();
        }

        const productSlider = document.getElementById('productSlider');
        const prodPrev = document.getElementById('prodPrev');
        const prodNext = document.getElementById('prodNext');

        if (productSlider && prodPrev && prodNext) {
            const scrollStep = () => productSlider.querySelector('.snap-start')?.offsetWidth + 16 || 300;
            prodNext.addEventListener('click', () => productSlider.scrollBy({ left: scrollStep(), behavior: 'smooth' }));
            prodPrev.addEventListener('click', () => productSlider.scrollBy({ left: -scrollStep(), behavior: 'smooth' }));
        }

        const farmerSlider = document.getElementById('farmerSlider');
        const farmerPrev = document.getElementById('farmerPrev');
        const farmerNext = document.getElementById('farmerNext');

        if (farmerSlider && farmerPrev && farmerNext) {
            farmerNext.addEventListener('click', () => farmerSlider.scrollBy({ left: 400, behavior: 'smooth' }));
            farmerPrev.addEventListener('click', () => farmerSlider.scrollBy({ left: -400, behavior: 'smooth' }));
        }

        document.addEventListener("DOMContentLoaded", () => {
            // Mobile menu toggle logic
            const mobileMenuBtn = document.getElementById("mobile-menu-btn");
            const closeMenuBtn = document.getElementById("close-menu-btn");
            const mobileMenu = document.getElementById("mobile-menu");
            const menuDrawer = mobileMenu ? mobileMenu.querySelector("div") : null;

            function openMenu() {
                if (mobileMenu && menuDrawer) {
                    mobileMenu.classList.remove("pointer-events-none");
                    mobileMenu.classList.add("opacity-100");
                    menuDrawer.classList.remove("translate-x-full");
                }
            }

            function closeMenu() {
                if (mobileMenu && menuDrawer) {
                    mobileMenu.classList.add("pointer-events-none");
                    mobileMenu.classList.remove("opacity-100");
                    menuDrawer.classList.add("translate-x-full");
                }
            }

            if (mobileMenuBtn) mobileMenuBtn.addEventListener("click", openMenu);
            if (closeMenuBtn) closeMenuBtn.addEventListener("click", closeMenu);
            if (mobileMenu) {
                mobileMenu.addEventListener("click", (e) => {
                    if (e.target === mobileMenu) closeMenu();
                });
            }
        });

// document.addEventListener('DOMContentLoaded', function() {
//     const slider = document.getElementById('productSlider');
//     const btnPrev = document.getElementById('prodPrev');
//     const btnNext = document.getElementById('prodNext');
//     const dots = document.querySelectorAll('#prodDots button');

//     if (!slider) return;

//     // Écart entre les cartes
//     const gap = parseInt(window.getComputedStyle(slider).gap) || 16;
    
//     // ---FONCTIONNALITÉ AUTOPLAY (DÉFILEMENT AUTO) ---
//     let autoPlayInterval;

//     function startAutoPlay() {
//         // On nettoie l'ancien timer par sécurité
//         clearInterval(autoPlayInterval);
        
//         // Défilement toutes les 3.5 secondes
//         autoPlayInterval = setInterval(() => {
//             const card = slider.querySelector('.product-card');
//             if (!card) return;
            
//             // Si on est arrivé tout au bout à droite (avec une petite marge d'erreur de 10px)
//             if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
//                 // On retourne doucement au tout début
//                 slider.scrollTo({ left: 0, behavior: 'smooth' });
//             } else {
//                 // Sinon on avance d'une carte
//                 slider.scrollBy({ left: card.offsetWidth + gap, behavior: 'smooth' });
//             }
//         }, 3500); 
//     }

//     function stopAutoPlay() {
//         clearInterval(autoPlayInterval);
//     }

//     // On lance le défilement automatique au chargement
//     startAutoPlay();

//     // On met en pause quand l'utilisateur survole ou touche la section entière
//     const section = slider.closest('section');
//     if (section) {
//         section.addEventListener('mouseenter', stopAutoPlay);
//         section.addEventListener('mouseleave', startAutoPlay);
//         section.addEventListener('touchstart', stopAutoPlay, {passive: true});
//         section.addEventListener('touchend', startAutoPlay, {passive: true});
//     }
//     // ----------------------------------------------------


//     // --- Clics manuels sur les flèches ---
//     if (btnNext) {
//         btnNext.addEventListener('click', () => {
//             const card = slider.querySelector('.product-card');
//             if (card) slider.scrollBy({ left: card.offsetWidth + gap, behavior: 'smooth' });
//         });
//     }

//     if (btnPrev) {
//         btnPrev.addEventListener('click', () => {
//             const card = slider.querySelector('.product-card');
//             if (card) slider.scrollBy({ left: -(card.offsetWidth + gap), behavior: 'smooth' });
//         });
//     }

//     // --- Clics manuels sur les petits ronds ---
//     dots.forEach((dot, index) => {
//         dot.addEventListener('click', () => {
//             const cards = slider.querySelectorAll('.product-card');
//             if (cards[index]) {
//                 const scrollPos = cards[index].offsetLeft - slider.offsetLeft;
//                 slider.scrollTo({ left: scrollPos, behavior: 'smooth' });
//             }
//         });
//     });

//     // --- Mise à jour visuelle des petits ronds ---
//     slider.addEventListener('scroll', () => {
//         const cards = slider.querySelectorAll('.product-card');
//         let activeIndex = 0;
//         let minDistance = Infinity;

//         cards.forEach((card, index) => {
//             const cardCenter = card.offsetLeft - slider.offsetLeft + (card.offsetWidth / 2);
//             const sliderCenter = slider.scrollLeft + (slider.offsetWidth / 2);
//             const distance = Math.abs(cardCenter - sliderCenter);

//             if (distance < minDistance) {
//                 minDistance = distance;
//                 activeIndex = index;
//             }
//         });

//         dots.forEach((dot, i) => {
//             if (i === activeIndex) {
//                 dot.classList.remove('bg-outline-variant');
//                 dot.classList.add('bg-primary', 'w-4'); 
//             } else {
//                 dot.classList.remove('bg-primary', 'w-4');
//                 dot.classList.add('bg-outline-variant');
//             }
//         });
//     });
// });


document.addEventListener('DOMContentLoaded', function() {
    
    function initSlider(sliderId, prevBtnId, nextBtnId, cardSelector, dotsSelector = null, autoPlayDelay = 3500) {
        const slider = document.getElementById(sliderId);
        const btnPrev = document.getElementById(prevBtnId);
        const btnNext = document.getElementById(nextBtnId);
        const dots = dotsSelector ? document.querySelectorAll(dotsSelector) : [];

        if (!slider) return;

        const cards = slider.querySelectorAll(cardSelector);
        if (cards.length <= 1) return;

        let autoPlayInterval;

        // Fonction pour trouver l'index en autoplay
        function getIndexActuel() {
            let activeIndex = 0;
            let minDistance = Infinity;
            cards.forEach((card, index) => {
                const cardCenter = card.offsetLeft - slider.offsetLeft + (card.offsetWidth / 2);
                const sliderCenter = slider.scrollLeft + (slider.offsetWidth / 2);
                const distance = Math.abs(cardCenter - sliderCenter);
                if (distance < minDistance) {
                    minDistance = distance;
                    activeIndex = index;
                }
            });
            return activeIndex;
        }

        // Défilement ciblé
        function allerA(index) {
            if (index >= cards.length) index = 0;
            if (index < 0) index = cards.length - 1;
            const scrollPos = cards[index].offsetLeft - slider.offsetLeft;
            slider.scrollTo({ left: scrollPos, behavior: 'smooth' });
        }

        function startAutoPlay() {
            clearInterval(autoPlayInterval);
            autoPlayInterval = setInterval(() => {
                allerA(getIndexActuel() + 1);
            }, autoPlayDelay); 
        }

        function stopAutoPlay() {
            clearInterval(autoPlayInterval);
        }

        // Lancement initial
        startAutoPlay();

        slider.addEventListener('mouseenter', stopAutoPlay);
        slider.addEventListener('mouseleave', startAutoPlay);
        slider.addEventListener('touchstart', stopAutoPlay, {passive: true});
        slider.addEventListener('touchend', startAutoPlay, {passive: true});
        
        // --- CLICS MANUELS SÉCURISÉS ---
        if (btnNext) {
            btnNext.addEventListener('mouseenter', stopAutoPlay);
            btnNext.addEventListener('mouseleave', startAutoPlay);
            btnNext.addEventListener('click', (e) => {
                e.preventDefault(); // Empêche tout rechargement inattendu
                
                const gap = parseInt(window.getComputedStyle(slider).gap) || 16;
                // Si on est à la toute fin, on retourne au début
                if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                    slider.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    // Sinon on pousse mathématiquement de la largeur d'une carte
                    slider.scrollBy({ left: cards[0].offsetWidth + gap, behavior: 'smooth' });
                }
            });
        }

        if (btnPrev) {
            btnPrev.addEventListener('mouseenter', stopAutoPlay);
            btnPrev.addEventListener('mouseleave', startAutoPlay);
            btnPrev.addEventListener('click', (e) => {
                e.preventDefault();
                
                const gap = parseInt(window.getComputedStyle(slider).gap) || 16;
                // Si on est au début, on va à la toute fin
                if (slider.scrollLeft <= 10) {
                    slider.scrollTo({ left: slider.scrollWidth, behavior: 'smooth' });
                } else {
                    slider.scrollBy({ left: -(cards[0].offsetWidth + gap), behavior: 'smooth' });
                }
            });
        }

        // Gestion des ronds (dots)
        if (dots.length > 0) {
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => allerA(index));
            });

            slider.addEventListener('scroll', () => {
                const activeIndex = getIndexActuel();
                dots.forEach((dot, i) => {
                    if (i === activeIndex) {
                        dot.classList.remove('bg-outline-variant');
                        dot.classList.add('bg-primary', 'w-4');
                    } else {
                        dot.classList.remove('bg-primary', 'w-4');
                        dot.classList.add('bg-outline-variant');
                    }
                });
            });
        }
    }

    // Lancement de vos 2 carrousels :
    initSlider('productSlider', 'prodPrev', 'prodNext', '.product-card', '#prodDots button', 3500);
    initSlider('farmerSlider', 'farmerPrev', 'farmerNext', '.snap-start', null, 4500);
        // 3. Carrousel des Publicités (Classe: .promo-card | Vitesse: 5s car il y a du texte à lire)
    initSlider('promoSlider', 'promoPrev', 'promoNext', '.promo-card', '#promoDots button', 5000);


});


document.addEventListener('DOMContentLoaded', function () {
    const mainImage = document.getElementById('main-image');
    const thumbnails = document.querySelectorAll('#thumbnails-container .thumbnail');

    thumbnails.forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            const newSrc = thumb.getAttribute('data-src');
            if (newSrc && mainImage) {
                mainImage.src = newSrc;
            }

            // Met à jour la bordure active (déplace border-primary sur la miniature cliquée)
            thumbnails.forEach(function (t) {
                t.classList.remove('border-primary');
                t.classList.add('border-outline-variant');
            });
            thumb.classList.remove('border-outline-variant');
            thumb.classList.add('border-primary');
        });
    });
});


</script>