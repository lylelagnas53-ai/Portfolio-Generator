document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
    toggle.addEventListener('click', () => {
        const input = document.getElementById(toggle.dataset.passwordToggle);

        if (!input) {
            return;
        }

        const isVisible = input.type === 'text';
        input.type = isVisible ? 'password' : 'text';
        toggle.textContent = isVisible ? 'Show' : 'Hide';
        toggle.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
        toggle.setAttribute('aria-pressed', String(!isVisible));
    });
});

document.querySelectorAll('[data-auth-form]').forEach((form) => {
    form.addEventListener('submit', () => {
        if (!form.reportValidity()) {
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');

        if (!submitButton) {
            return;
        }

        submitButton.disabled = true;
        submitButton.textContent = 'Please wait…';
    });
});
