document.addEventListener("DOMContentLoaded", function () {
    // Inisialisasi metismenu
    new MetisMenu("#side-menu");

    // Tambahkan logika mobile close-on-click
    document
        .querySelectorAll("#side-menu > li > a")
        .forEach(function (menuLink) {
            menuLink.addEventListener("click", function (e) {
                const windowIsMobile = window.innerWidth < 992;
                const parentLi = this.closest("li");

                if (parentLi && parentLi.querySelector("ul.sub-menu")) {
                    e.preventDefault();

                    if (windowIsMobile) {
                        document
                            .querySelectorAll("#side-menu li.mm-active")
                            .forEach(function (li) {
                                if (li !== parentLi) {
                                    li.classList.remove("mm-active");
                                    const submenu =
                                        li.querySelector("ul.sub-menu");
                                    if (submenu) {
                                        submenu.classList.remove("mm-show");
                                        submenu.style.height = "0px";
                                        submenu.style.overflow = "hidden";
                                    }
                                }
                            });
                    }

                    parentLi.classList.toggle("mm-active");
                    const submenu = parentLi.querySelector("ul.sub-menu");
                    if (submenu) {
                        submenu.classList.toggle("mm-show");
                        if (submenu.classList.contains("mm-show")) {
                            submenu.style.height = "auto";
                            submenu.style.overflow = "visible";
                        } else {
                            submenu.style.height = "0px";
                            submenu.style.overflow = "hidden";
                        }
                    }
                }
            });
        });
});
