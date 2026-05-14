document.addEventListener("DOMContentLoaded", function() {

    // ── Balance Hide/Show Toggle ──────────────────────────────────────────────
    const toggleEye = document.querySelector('.bi-eye-slash-fill, .bi-eye-fill');
    if (toggleEye) {
        toggleEye.addEventListener('click', function() {
            const balanceElement = this.closest('.balance-card').querySelector('h1');
            if (this.classList.contains('bi-eye-slash-fill')) {
                this.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
                balanceElement.setAttribute('data-original', balanceElement.innerText);
                balanceElement.innerText = 'Rp ••••••';
            } else {
                this.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
                balanceElement.innerText = balanceElement.getAttribute('data-original');
            }
        });
    }

    // ── Auto-format Money Inputs (titik ribuan gaya Indonesia) ───────────────
    // Input amount di modal transaction sekarang tipe=number, jadi formatter
    // hanya diaktifkan untuk input balance (Add Wallet) dan target_amount (Saving).
    const moneyInputs = document.querySelectorAll(
        'input[name="balance"], input[name="target_amount"]'
    );
    moneyInputs.forEach(input => {
        input.setAttribute('type', 'text');
        input.setAttribute('inputmode', 'numeric');

        input.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9]/g, '');
            if (value) {
                this.value = parseInt(value, 10)
                    .toLocaleString('id-ID')
                    .replace(/,/g, '.');
            } else {
                this.value = '';
            }
        });
    });

});

