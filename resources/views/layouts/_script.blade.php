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

        document.addEventListener('DOMContentLoaded', function () {
    const slider = document.getElementById('productSlider');
    const prevBtn = document.getElementById('prodPrev');
    const nextBtn = document.getElementById('prodNext');
    const dotsContainer = document.getElementById('prodDots');

    if (!slider || !prevBtn || !nextBtn) return;

    const cards = slider.querySelectorAll('.product-card');
    const dots = dotsContainer ? dotsContainer.querySelectorAll('button') : [];

    function getCardWidth() {
        if (!cards.length) return 320;
        const card = cards[0];
        const style = window.getComputedStyle(slider);
        const gap = parseInt(style.columnGap || style.gap || 16);
        return card.offsetWidth + gap;
    }

    prevBtn.addEventListener('click', function () {
        slider.scrollBy({ left: -getCardWidth(), behavior: 'smooth' });
    });

    nextBtn.addEventListener('click', function () {
        slider.scrollBy({ left: getCardWidth(), behavior: 'smooth' });
    });

    // Clic sur un dot -> scroll vers la carte correspondante
    dots.forEach((dot, index) => {
        dot.addEventListener('click', function () {
            const card = cards[index];
            if (card) {
                slider.scrollTo({ left: card.offsetLeft, behavior: 'smooth' });
            }
        });
    });

    // Met à jour le dot actif pendant le scroll
    let scrollTimeout;
    slider.addEventListener('scroll', function () {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function () {
            let closestIndex = 0;
            let closestDistance = Infinity;

            cards.forEach((card, index) => {
                const distance = Math.abs(card.offsetLeft - slider.scrollLeft);
                if (distance < closestDistance) {
                    closestDistance = distance;
                    closestIndex = index;
                }
            });

            dots.forEach((dot, index) => {
                if (index === closestIndex) {
                    dot.classList.remove('bg-outline-variant');
                    dot.classList.add('bg-primary');
                } else {
                    dot.classList.remove('bg-primary');
                    dot.classList.add('bg-outline-variant');
                }
            });
        }, 100);
    }, { passive: true });
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