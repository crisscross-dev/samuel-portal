const POLL_INTERVAL_MS = 30000;

const toCount = (value) => {
    const parsed = Number.parseInt(value, 10);
    return Number.isFinite(parsed) && parsed > 0 ? parsed : 0;
};

const setBadge = (key, count) => {
    const badges = document.querySelectorAll(`[data-sidebar-badge="${key}"]`);

    badges.forEach((badge) => {
        if (count > 0) {
            badge.textContent = count > 99 ? "99+" : String(count);
            badge.classList.remove("d-none");
            return;
        }

        badge.textContent = "0";
        badge.classList.add("d-none");
    });
};

const updateBadges = (counts) => {
    Object.entries(counts).forEach(([key, value]) => {
        setBadge(key, toCount(value));
    });
};

const fetchSidebarBadges = async (url) => {
    const response = await window.axios.get(url);
    updateBadges(response?.data?.counts ?? {});
};

const initSidebarBadges = () => {
    const sidebarBadges = document.querySelectorAll("[data-sidebar-badge]");

    if (!sidebarBadges.length) {
        return;
    }

    const badgeUrl = document.querySelector(
        'meta[name="sidebar-badge-url"]',
    )?.content;

    if (!badgeUrl) {
        return;
    }

    fetchSidebarBadges(badgeUrl).catch(() => {
        // Keep UI stable when badge endpoint is temporarily unavailable.
    });

    window.setInterval(() => {
        fetchSidebarBadges(badgeUrl).catch(() => {
            // Ignore transient polling failures.
        });
    }, POLL_INTERVAL_MS);
};

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initSidebarBadges);
} else {
    initSidebarBadges();
}
