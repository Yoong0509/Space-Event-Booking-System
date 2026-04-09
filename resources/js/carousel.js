// resources/js/carousel.js
document.addEventListener("DOMContentLoaded", function() {
    const slides = document.querySelectorAll(".slide");
    const dots = document.querySelectorAll(".dot");
    const carousel = document.querySelector(".carousel");
    let currentIndex = 0;
    let interval = setInterval(nextSlide, 5000);

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle("active", i === index);
            slide.style.transform = "";
        });
        dots.forEach((dot, i) => dot.classList.toggle("active", i === index));
        currentIndex = index;
    }

    function nextSlide() {
        showSlide((currentIndex + 1) % slides.length);
    }

    dots.forEach(dot => {
        dot.addEventListener("click", () => {
            showSlide(parseInt(dot.dataset.index));
        });
    });

    // mouse drag functionality
    let startX = 0;
    let startTime = 0;
    let isDragging = false;

    carousel.addEventListener("mousedown", e => {
        startX = e.clientX;
        startTime = Date.now();
        isDragging = true;
        clearInterval(interval);
    });

    carousel.addEventListener("mousemove", e => {
        if (!isDragging) return;
        let diff = e.clientX - startX;

        slides[currentIndex].style.transform = `translateX(${diff}px)`;

        let nextIndex = (currentIndex + 1) % slides.length;
        let prevIndex = (currentIndex - 1 + slides.length) % slides.length;

        if (diff < 0) {
            slides[nextIndex].style.transform = `translateX(${diff + carousel.offsetWidth}px)`;
            slides[nextIndex].classList.add("active");
        } else {
            slides[prevIndex].style.transform = `translateX(${diff - carousel.offsetWidth}px)`;
            slides[prevIndex].classList.add("active");
        }
    });

    carousel.addEventListener("mouseup", e => {
        if (!isDragging) return;
        let diff = e.clientX - startX;
        let elapsed = Date.now() - startTime;
        let velocity = diff / elapsed;

        if (diff < -100 || velocity < -0.5) {
            nextSlide();
        } else if (diff > 100 || velocity > 0.5) {
            let newIndex = (currentIndex - 1 + slides.length) % slides.length;
            showSlide(newIndex);
        } else {
            slides.forEach(slide => {
                slide.style.transition = "transform 0.3s ease";
                slide.style.transform = "";
                setTimeout(() => slide.style.transition = "", 300);
            });
        }

        isDragging = false;
        interval = setInterval(nextSlide, 5000);
    });

    carousel.addEventListener("mouseleave", () => {
        if (isDragging) {
            slides.forEach(slide => slide.style.transform = "");
            isDragging = false;
        }
    });
});