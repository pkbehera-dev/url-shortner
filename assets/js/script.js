document.addEventListener('DOMContentLoaded', function() {
    const shortenForm = document.getElementById('shortenForm');
    const resultBox = document.getElementById('resultBox');
    const errorBox = document.getElementById('errorBox');
    const shortUrlInput = document.getElementById('shortUrl');
    const copyBtn = document.getElementById('copyBtn');

    const successMessage = document.getElementById('successMessage');

    if (shortenForm) {
        shortenForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const longUrl = document.getElementById('longUrl').value;
            
            // Basic validation
            if (!longUrl) return;

            // Reset UI
            errorBox.classList.add('d-none');
            if (successMessage) successMessage.classList.add('d-none');
            resultBox.style.display = 'none';
            
            // Send request
            const formData = new FormData();
            formData.append('url', longUrl);

            fetch('api/shorten.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    shortUrlInput.value = data.short_url;
                    resultBox.style.display = 'block';
                    
                    if (data.message && successMessage) {
                        successMessage.textContent = data.message;
                        successMessage.classList.remove('d-none');
                    }
                } else {
                    errorBox.textContent = data.message;
                    errorBox.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorBox.textContent = 'An unexpected error occurred. Please try again.';
                errorBox.classList.remove('d-none');
            });
        });
    }

    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            shortUrlInput.select();
            shortUrlInput.setSelectionRange(0, 99999); /* For mobile devices */
            
            navigator.clipboard.writeText(shortUrlInput.value).then(() => {
                const originalHtml = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="bi bi-check"></i> Copied!';
                copyBtn.classList.remove('btn-outline-secondary');
                copyBtn.classList.add('btn-success');
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalHtml;
                    copyBtn.classList.remove('btn-success');
                    copyBtn.classList.add('btn-outline-secondary');
                }, 2000);
            });
        });
    }
});
