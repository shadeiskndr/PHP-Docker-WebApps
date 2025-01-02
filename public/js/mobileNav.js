document.addEventListener('DOMContentLoaded', () => {
    const sideBar = document.getElementById("mobile-nav");
    const openSideBar = document.getElementById("openSideBar");
    const closeSideBar = document.getElementById("closeSideBar");

    // Initialize sidebar position
    sideBar.style.transform = "translateX(-260px)";

    // Open sidebar handler
    openSideBar.addEventListener('click', () => {
        sideBar.style.transform = "translateX(0px)";
        openSideBar.classList.add("hidden");
        closeSideBar.classList.remove("hidden");
    });

    // Close sidebar handler
    closeSideBar.addEventListener('click', () => {
        sideBar.style.transform = "translateX(-260px)";
        closeSideBar.classList.add("hidden");
        openSideBar.classList.remove("hidden");
    });

    // Close sidebar when clicking outside
    document.addEventListener('click', (e) => {
        if (!sideBar.contains(e.target) && 
            !openSideBar.contains(e.target) && 
            !closeSideBar.contains(e.target) && 
            window.getComputedStyle(sideBar).transform !== 'matrix(1, 0, 0, 1, -260, 0)') {
            sideBar.style.transform = "translateX(-260px)";
            closeSideBar.classList.add("hidden");
            openSideBar.classList.remove("hidden");
        }
    });
});
