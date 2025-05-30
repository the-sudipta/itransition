function validateForm(form, config = {}) {
    const { include, exclude, rules = {} } = config;
    let isValid = true;

    // Built-in validators registry
    const V = {
        required:      v => v.trim() !== '' || 'This field is required.',
        email:         v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) || 'Please enter a valid email address.',
        integer:       v => /^\d+$/.test(v) || 'Please enter a valid integer.',
        decimal:       v => /^\d+(\.\d+)?$/.test(v) || 'Please enter a valid number.',
        passwordWeak:  v => v.length >= 8 || 'Password must be at least 8 characters.',
        passwordStrong:v => /(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}/.test(v)
            || 'Password must be 8+ chars with upper, lower, number & special char.',
        minLength:     (v, min) => v.length >= min || `Must be at least ${min} characters.`,
        maxLength:     (v, max) => v.length <= max || `Must be no more than ${max} characters.`
        // add more named validators as needed
    };

    // 1) Clear any old error messages
    form.querySelectorAll('.error-message').forEach(el => el.remove());

    // 2) Gather all candidate fields
    let fields = Array.from(form.elements)
        .filter(el => el.name && !['hidden','file','button','submit','reset'].includes(el.type));

    // 3) Apply include/exclude logic
    if (Array.isArray(include)) {
        fields = fields.filter(el => include.includes(el.name));
    } else if (Array.isArray(exclude)) {
        fields = fields.filter(el => !exclude.includes(el.name));
    }

    // 4) Validate each field against its configured rules
    fields.forEach(field => {
        const val = (field.value ?? '').toString();
        const fieldRules = rules[field.name] || ['required']; // default to required
        for (const rule of fieldRules) {
            let result;
            if (typeof rule === 'string') {
                const fn = V[rule];
                if (!fn) {
                    console.warn(`No validator named "${rule}" for field "${field.name}"`);
                    continue;
                }
                result = fn(val);
            } else if (typeof rule === 'function') {
                result = rule(val);
            } else {
                continue;
            }

            // if result is a string (error message) or false, it’s a validation failure
            if (result !== true) {
                const message = typeof result === 'string' ? result : 'Invalid value.';
                // Inline showError logic:
                const err = document.createElement('div');
                err.className = 'error-message';
                err.style.color = 'red';
                err.style.fontSize = '0.875rem';
                err.style.marginTop = '4px';
                err.style.fontWeight = '500';
                err.textContent = message;
                field.insertAdjacentElement('afterend', err);

                isValid = false;
                break;  // stop checking further rules for this field
            }
        }
    });

    return isValid;
}


/**
 * turn_off_right_click_menu
 * Disables the browser’s context menu (right-click) on the entire document.
 */
function turn_off_right_click_menu() {
    // Disable right-click
    document.addEventListener('contextmenu', function(event) {
        event.preventDefault();
    });

    // Older IE
    document.oncontextmenu = function() {
        return false;
    };

    // Try to block DevTools keys (F12, Ctrl+Shift+I/J/C, Ctrl+U)
    function blockDevKeys(event) {
        if (
            event.key === 'F12' ||
            (event.ctrlKey && event.shiftKey && ['I', 'J', 'C'].includes(event.key.toUpperCase())) ||
            (event.ctrlKey && event.key.toLowerCase() === 'u')
        ) {
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
    }

    window.addEventListener('keydown', blockDevKeys);
    window.addEventListener('keyup', blockDevKeys);

    // Detect DevTools open by window size (optional trick)
    // setInterval(function () {
    //     if (
    //         window.outerHeight - window.innerHeight > 100 ||
    //         window.outerWidth - window.innerWidth > 100
    //     ) {
    //         document.body.innerHTML = "<h1>DevTools Detected. Access Denied.</h1>";
    //     }
    // }, 1000);
}





