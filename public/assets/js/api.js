document.addEventListener("DOMContentLoaded", function() {
    // Select all forms that are marked as API forms
    const apiForms = document.querySelectorAll('.api-form');
    
    apiForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault(); // Prevent traditional page reload
            
            // 1. Collect form data
            const formData = new FormData(this);
            const dataObj = {};
            formData.forEach((value, key) => {
                dataObj[key] = value;
            });
            
            // 2. Get destination URL and HTTP Method
            const url = this.getAttribute('action');
            const method = this.getAttribute('method') || 'POST';
            
            // 3. Prevent submission to '#' (unimplemented route fallback)
            if (url === '#' || url.endsWith('#')) {
                alert('Route is not yet defined by the backend team.');
                return;
            }
            
            // 4. Get CSRF Token
            const csrfToken = document.querySelector('input[name="_token"]')?.value || '';
            
            // 5. Provide visual feedback (Disable button, show loading)
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Submit';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...';
            }
            
            try {
                // 6. Send the JSON payload via Fetch API
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(dataObj)
                });
                
                // Read JSON response from backend
                const result = await response.json().catch(() => ({})); 
                
                if (response.ok) {
                    // Success logic
                    alert('Success! JSON data has been sent to the backend.');
                    
                    // Reset the form
                    this.reset();
                    
                    // Automatically close Bootstrap Modal if the form is inside one
                    const modal = this.closest('.modal');
                    if (modal && typeof bootstrap !== 'undefined') {
                        const modalInstance = bootstrap.Modal.getInstance(modal);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    }
                } else {
                    // Validation or Server Error Logic
                    alert('Backend Error: ' + (result.message || 'Request failed. Check network tab for details.'));
                    console.error('API Error Response:', result);
                }
            } catch (error) {
                // Network or CORS Error Logic
                console.error('Network/CORS Error:', error);
                alert('Network error. Check if the backend API is running and CORS is configured properly.');
            } finally {
                // Restore the submit button state
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            }
        });
    });
});
