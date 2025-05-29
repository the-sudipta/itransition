/**
 * Validates a form’s fields by name, regardless of their HTML type.
 * Call it with: `<form onsubmit="return validateForm(this)">`
 */

function validateForm(form) {
    // Clear previous error messages
    form.querySelectorAll('.error-message').forEach(el => el.textContent = '');

    let isValid = true;
    const fields = form.querySelectorAll('input, select, textarea');

    // 1. Collect password field values up front
    const passwordFields = {
        current_password: form.querySelector('input[name="current_password"]')?.value.trim() ?? '',
        password: form.querySelector('input[name="password"]')?.value.trim() ?? '',
        confirm_password: form.querySelector('input[name="confirm_password"]')?.value.trim() ?? ''
    };

    // 2. Determine if a password update is being attempted
    // Determine if any of the existing password fields are filled
    const isPasswordUpdate = Object.entries(passwordFields)
        .some(([name, value]) => form.querySelector(`input[name="${name}"]`) && value !== '');

    fields.forEach(field => {
        if (field.type === 'file' || field.type === 'hidden') return;  // skip files and hidden fields

        const val = field.value.trim();
        const name = field.name;

        // 3. Skip password fields entirely if not updating password
        if (['current_password', 'password', 'confirm_password'].includes(name) && !isPasswordUpdate) {
            return;  // all password fields are blank, skip validation for them
        }

        // 4. General required field check (applies now to password fields if updating)
        if (!val) {
            showError(field, 'This field is required.');
            isValid = false;
            return;
        }

        // 5. Field-specific validation (existing rules)
        switch (name) {
            case 'username':
                if (!/^[a-zA-Z0-9_]{3,16}$/.test(val)) {
                    showError(field, 'Username must be 3–16 characters and contain only letters, numbers, or underscores.');
                    isValid = false;
                }
                break;

            case 'email':
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                    showError(field, 'Please enter a valid email address.');
                    isValid = false;
                }
                break;

            case 'current_password':
                if (val.length < 6) {
                    showError(field, 'Current password must be at least 6 characters.');
                    isValid = false;
                }
                break;

            case 'password':
                if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/.test(val)) {
                    showError(field, 'New password must be at least 8 characters and include uppercase, lowercase, a number, and a special character.');
                    isValid = false;
                }
                break;

            case 'confirm_password':
                if (val !== passwordFields.password) {
                    showError(field, 'Passwords do not match.');
                    isValid = false;
                }
                break;
        }
    });

    return isValid;
}

function clearForm(buttonOrForm) {
    // Get the form if a button inside it was passed
    const form = buttonOrForm.nodeName === 'FORM' ? buttonOrForm : buttonOrForm.closest('form');
    if (!form || form.nodeName !== 'FORM') return;

    // Clear text-ish inputs
    const textTypes = [
        'text','password','email','number','url','tel',
        'search','color','date','datetime-local','month','time','week'
    ];
    form.querySelectorAll('input').forEach(input => {
        const t = input.type.toLowerCase();
        if (textTypes.includes(t)) {
            input.value = '';
        }
        else if (t === 'checkbox' || t === 'radio') {
            input.checked = false;
        }
        else if (t === 'file') {
            input.value = '';
            if (input.files) input.files = new DataTransfer().files;
        }
    });

    // Clear textareas
    form.querySelectorAll('textarea').forEach(ta => ta.value = '');

    // Clear selects
    form.querySelectorAll('select').forEach(sel => {
        if (sel.multiple) {
            Array.from(sel.options).forEach(opt => opt.selected = false);
        } else {
            sel.selectedIndex = -1;
        }
    });

    // Move focus back to first field
    const first = form.querySelector('input, select, textarea');
    if (first) first.focus();
}



/** helper to show a red error message right after any field */
function showError(field, message) {
    // Skip handling file inputs entirely
    if (field.type === 'file') return;
    // Use existing error container if present, otherwise create one
    let err = field.nextElementSibling;
    if (!err || !err.classList.contains('error-message')) {
        err = document.createElement('div');
        err.className = 'error-message';
        field.parentNode.insertBefore(err, field.nextSibling);
    }
    // Apply styling and set the error message
    err.style.color = 'red';
    err.style.fontSize = '0.875rem';
    err.style.marginTop = '4px';
    err.style.fontWeight = '500';
    err.textContent = message;
}

/**
 * download_table
 * This function helps to download any table as Excel format currently
 */

function download_table(data, fileName, columns, format = 'xlsx') {
    const headers = columns.map(c => c.header);
    const keys    = columns.map(c => c.key);
    const aoa     = [
        headers,
        ...data.map(row => keys.map(k => row[k] != null ? row[k] : ''))
    ];

    if (format === 'csv') {
        const csvRows = aoa.map(r =>
            r.map(cell => `"${String(cell).replace(/"/g,'""')}"`).join(',')
        );
        const blob = new Blob([csvRows.join('\n')], { type: 'text/csv' });
        const url  = URL.createObjectURL(blob);
        const a    = document.createElement('a');
        a.href     = url;
        a.download = fileName.endsWith('.csv') ? fileName : fileName + '.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);

    } else if (format === 'xlsx') {
        const ws = XLSX.utils.aoa_to_sheet(aoa);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
        XLSX.writeFile(wb, fileName.endsWith('.xlsx') ? fileName : fileName + '.xlsx');
    } else {
        console.error('download_table: unsupported format', format);
    }
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





