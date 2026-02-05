document.addEventListener('DOMContentLoaded', () => {
    const dropdowns = document.querySelectorAll('.dropdown-toggle');

    dropdowns.forEach(toggle => {
        toggle.addEventListener('click', () => {
            // Obtener el contenedor padre (nav-group)
            const parent = toggle.parentElement;
            
            // Cerrar otros dropdowns (opcional, si quieres tipo acordeón)
            document.querySelectorAll('.nav-group').forEach(group => {
                if (group !== parent) {
                    group.classList.remove('active');
                }
            });

            // Alternar el actual
            parent.classList.toggle('active');
        });
    });
});