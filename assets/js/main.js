/**
 * Main JavaScript - Web Pencarian Kost Trini
 */

document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Tooltip Bootstrap jika ada
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto format input harga di filter jika diperlukan
    const priceInputs = document.querySelectorAll('.input-currency');
    priceInputs.forEach(input => {
        input.addEventListener('input', function (e) {
            let val = this.value.replace(/\D/g, '');
            if (val) {
                this.value = parseInt(val, 10).toLocaleString('id-ID');
            }
        });
    });
});
