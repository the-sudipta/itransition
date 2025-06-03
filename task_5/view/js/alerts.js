/**
 * alert.js
 *
 * Modular, animated alert-box system.
 *
 * How to use:
 * 1. Add the following PHP code at the top of the file:
 * <pre><code>
 * $info    = isset($_GET['message'])          ? htmlspecialchars($_GET['message'])          : '';
 * $success = isset($_GET['success_message'])  ? htmlspecialchars($_GET['success_message'])  : '';
 * $warning = isset($_GET['warning_message'])  ? htmlspecialchars($_GET['warning_message'])  : '';
 * $error   = isset($_GET['error_message'])    ? htmlspecialchars($_GET['error_message'])    : '';
 * </code></pre>
 *
 * 2. Include this following CSS file in the header section:
 * <pre><code>
 * <link rel="stylesheet" href="../css/alert_box_css.css">
 * </code></pre>
 *
 * 3. Ensure your page body has the following div:
 * <pre><code>
 * <!-- Alerts placeholder -->
 * <div id="alerts-container"></div>
 * </code></pre>
 *
 * 4. Include this following JS file and JS code at the end of the body tag:
 * <pre><code>
 * <script src="../js/alerts.js"></script>
 * <script>
 *     window.onload = function() {
 *         initAlerts({
 *             info:    "<?php echo addslashes($info); ?>",
 *             success: "<?php echo addslashes($success); ?>",
 *             warning: "<?php echo addslashes($warning); ?>",
 *             error:   "<?php echo addslashes($error); ?>"
 *         });
 *     };
 * </script>
 * </code></pre>
 *
 * Supported types: 'info', 'success', 'warning', 'error'
 * Default durations (ms): info=6000, success=5000, warning=7000, error=8000
 */


    // Default durations for each alert type
const defaultDurations = {
        success: 5000,
        info:    6000,
        warning: 7000,
        error:   8000
    };

/**
 * Return the icon for a given alert type.
 * @param {string} type — 'success'|'info'|'warning'|'error'
 * @returns {string} emoji or empty string
 */
function getIcon(type) {
    switch (type) {
        case 'success': return '✔️';
        case 'info':    return 'ℹ️';
        case 'warning': return '⚠️';
        case 'error':   return '❌';
        default:        return '';
    }
}

/**
 * Create, display, and auto‐dismiss an alert.
 * @param {string} type — alert variant ('info', 'success', 'warning', 'error')
 * @param {string} message — HTML‐safe content to show
 * @param {number} [duration] — milliseconds before auto‐dismissal
 */
function showAlert(type, message, duration) {
    // fallback to default if no duration passed
    duration = duration || defaultDurations[type] || 5000;
    const container = document.getElementById('alerts-container');
    if (!container) return;

    // Build alert element
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} slide-in`;
    alert.setAttribute('role','alert');
    alert.innerHTML = `
    <span class="icon">${getIcon(type)}</span>
    <div class="content">${message}</div>
    <div class="progress"></div>
    <button class="close-btn" aria-label="Close">&times;</button>
  `;
    container.appendChild(alert);

    // Progress-bar animation
    const progress = alert.querySelector('.progress');
    progress.style.animation = `progressBar ${duration}ms linear forwards`;

    // Auto-dismiss timer
    let timer = setTimeout(() => removeAlert(alert), duration);

    // Manual close
    alert.querySelector('.close-btn').addEventListener('click', () => {
        clearTimeout(timer);
        removeAlert(alert);
    });
}

/**
 * Animate slide-out and then remove the alert from DOM.
 * @param {HTMLElement} alert
 */
function removeAlert(alert) {
    alert.classList.remove('slide-in');
    alert.classList.add('slide-out');
    alert.addEventListener('animationend', () => alert.remove());
}

/**
 * Initialize alerts from a messages object.
 * @param {Object} msgs
 * @param {string} [msgs.info]
 * @param {string} [msgs.success]
 * @param {string} [msgs.warning]
 * @param {string} [msgs.error]
 */
function initAlerts(msgs) {
    if (!msgs) return;
    if (msgs.info    && msgs.info.trim())    showAlert('info',    msgs.info);
    if (msgs.success && msgs.success.trim()) showAlert('success', msgs.success);
    if (msgs.warning && msgs.warning.trim()) showAlert('warning', msgs.warning);
    if (msgs.error   && msgs.error.trim())   showAlert('error',   msgs.error);
}

// Expose functions globally so they can be called from inline scripts
window.showAlert   = showAlert;
window.initAlerts = initAlerts;
