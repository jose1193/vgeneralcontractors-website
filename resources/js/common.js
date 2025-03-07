// Fade In Observer
export function initFadeInObserver() {
    const observerOptions = {
        root: null,
        rootMargin: "0px",
        threshold: 0.1,
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("is-visible");
            }
        });
    }, observerOptions);

    document.querySelectorAll(".fade-in-section").forEach((section) => {
        observer.observe(section);
    });
}

// Initialize all common functions
document.addEventListener("DOMContentLoaded", function () {
    initFadeInObserver();
});
