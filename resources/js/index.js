function scrollToServices() {
    const el = document.getElementById('services');
    if (el) {
        el.scrollIntoView({ behavior: 'smooth' });
    }
}

if (window.showFormOnLoad) {
    const form = document.querySelector('.appointment-form-section');
    if (form) form.classList.add('show');
}
