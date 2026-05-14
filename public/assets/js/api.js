document.addEventListener("DOMContentLoaded", function() {
    // Select all forms that are marked as API forms
    const apiForms = document.querySelectorAll('.api-form');
    
    apiForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // 1. Collect form data
            const formData = new FormData(this);
            const dataObj = {};
            formData.forEach((value, key) => {
                // Remove dots from money inputs before sending to backend
                if (key === 'amount' || key === 'balance' || key === 'target_amount') {
                    value = String(value).replace(/\./g, '');
                }
                dataObj[key] = value;
            });
            
            // 2. Get destination URL and HTTP Method
            const url = this.getAttribute('action');
            const method = (this.getAttribute('method') || 'POST').toUpperCase();
            
            // 3. Prevent submission to '#' (unimplemented route fallback)
            if (!url || url === '#' || url.endsWith('#')) {
                alert('Route belum didefinisikan.');
                return;
            }
            
            // 4. Get CSRF Token
            const csrfToken = document.querySelector('input[name="_token"]')?.value || '';
            
            // 5. Visual feedback — disable button, show spinner
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnHTML = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';
            }
            
            try {
                // 6. Send JSON payload via Fetch
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(dataObj)
                });
                
                const result = await response.json().catch(() => ({}));
                
                if (response.ok) {
                    // ── Sukses ──
                    const msg = result.message || 'Berhasil disimpan!';

                    // Tutup modal dulu
                    const modal = this.closest('.modal');
                    if (modal && typeof bootstrap !== 'undefined') {
                        const modalInstance = bootstrap.Modal.getInstance(modal);
                        if (modalInstance) modalInstance.hide();
                    }

                    // Reset form
                    this.reset();

                    // Jika backend kirim flag reload=true (mis. Add Wallet),
                    // atau selalu reload agar saldo/data terbaru tampil
                    if (result.reload || true) {
                        // Tampilkan toast singkat lalu reload
                        showToast(msg, 'success');
                        setTimeout(() => window.location.reload(), 1200);
                    }

                } else {
                    // ── Error dari backend ──
                    const errMsg = result.message || result.errors
                        ? (result.message || Object.values(result.errors || {}).flat().join('\n'))
                        : 'Terjadi kesalahan. Coba lagi.';
                    showToast(errMsg, 'danger');
                    console.error('API Error:', result);
                }
            } catch (error) {
                console.error('Network/CORS Error:', error);
                showToast('Gagal terhubung ke server. Pastikan backend berjalan.', 'danger');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHTML;
                }
            }
        });
    });

    // ── Toast helper ─────────────────────────────────────────────────────────
    function showToast(message, type = 'success') {
        // Buat container jika belum ada
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;min-width:280px;';
            document.body.appendChild(container);
        }

        const colors = {
            success: 'linear-gradient(135deg,#10B981,#059669)',
            danger:  'linear-gradient(135deg,#EF4444,#DC2626)',
            info:    'linear-gradient(135deg,#6A4CFF,#5B3FCC)',
        };

        const toast = document.createElement('div');
        toast.style.cssText = `
            background: ${colors[type] || colors.info};
            color: white;
            padding: 14px 20px;
            border-radius: 14px;
            margin-bottom: 10px;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        `;
        const icon = type === 'success' ? '✅' : type === 'danger' ? '❌' : 'ℹ️';
        toast.innerHTML = `<span style="font-size:1.1rem">${icon}</span><span>${message}</span>`;

        container.appendChild(toast);

        // Auto-hapus setelah 3 detik
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.4s ease';
            setTimeout(() => toast.remove(), 400);
        }, 3000);
    }

    // ── Keyframe untuk animasi toast ─────────────────────────────────────────
    if (!document.getElementById('toast-anim-style')) {
        const style = document.createElement('style');
        style.id = 'toast-anim-style';
        style.textContent = `
            @keyframes slideIn {
                from { opacity: 0; transform: translateX(60px); }
                to   { opacity: 1; transform: translateX(0); }
            }
        `;
        document.head.appendChild(style);
    }
});
