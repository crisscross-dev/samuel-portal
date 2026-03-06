function scrollToServices() {
    const el = document.getElementById("services");
    if (el) {
        el.scrollIntoView({ behavior: "smooth" });
    }
}

if (window.showFormOnLoad) {
    const form = document.querySelector(".appointment-form-section");
    if (form) form.classList.add("show");
}

// ── Navbar Dropdowns ─────────────────────────────────────
document.querySelectorAll(".nav-dropdown-toggle").forEach((btn) => {
    btn.addEventListener("click", (e) => {
        e.stopPropagation();
        const dropdown = btn.closest(".nav-dropdown");
        const isOpen = dropdown.classList.contains("open");
        document
            .querySelectorAll(".nav-dropdown.open")
            .forEach((d) => d.classList.remove("open"));
        if (!isOpen) dropdown.classList.add("open");
    });
});

document.addEventListener("click", () => {
    document
        .querySelectorAll(".nav-dropdown.open")
        .forEach((d) => d.classList.remove("open"));
});
