// FarmDialogue Frontend Scripts

// Smooth scroll to section
function scrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
    }
}

function ensureToastContainer() {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        container.setAttribute('aria-live', 'polite');
        container.setAttribute('aria-atomic', 'true');
        document.body.appendChild(container);
    }
    return container;
}

function showToast(message, type = 'success', options = {}) {
    const config = typeof options === 'number'
        ? { duration: options }
        : options;

    const duration = config.duration ?? 3500;
    const title = config.title || ({
        success: 'Success',
        error: 'Error',
        warning: 'Warning',
        info: 'Information',
    })[type] || 'Notice';

    const icons = {
        success: '✓',
        error: '!',
        warning: '!',
        info: 'i',
    };

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.setAttribute('role', type === 'error' ? 'alert' : 'status');
    toast.innerHTML = `
        <div class="toast-icon">${icons[type] || 'i'}</div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button type="button" class="toast-close" aria-label="Dismiss notification">&times;</button>
    `;

    const container = ensureToastContainer();
    container.appendChild(toast);

    const removeToast = () => {
        toast.style.animation = 'toastOut 0.22s ease-in forwards';
        setTimeout(() => toast.remove(), 220);
    };

    toast.querySelector('.toast-close').addEventListener('click', removeToast);
    window.setTimeout(removeToast, duration);
    return toast;
}

function showNotification(message, type = 'success', duration = 3000) {
    return showToast(message, type, { duration });
}

function formatCounterValue(value, element) {
    const prefix = element.dataset.prefix || '';
    const suffix = element.dataset.suffix || '';
    const format = element.dataset.format || 'plain';

    if (format === 'currency') {
        return `${prefix}${value.toLocaleString('en-US')}${suffix}`;
    }

    if (format === 'compact') {
        const compact = value >= 1000000
            ? `${(value / 1000000).toFixed(value >= 10000000 ? 0 : 1)}M`
            : value >= 1000
                ? `${(value / 1000).toFixed(value >= 10000 ? 0 : 1)}K`
                : `${value}`;
        return `${prefix}${compact}${suffix}`;
    }

    return `${prefix}${value.toLocaleString('en-US')}${suffix}`;
}

function animateCounter(element, duration = 1600) {
    if (element.dataset.countAnimated === 'true') {
        return;
    }

    const target = Number(element.dataset.count);
    if (Number.isNaN(target)) {
        return;
    }

    const startTime = performance.now();
    element.dataset.countAnimated = 'true';

    const step = (currentTime) => {
        const progress = Math.min((currentTime - startTime) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const value = Math.round(target * eased);
        element.textContent = formatCounterValue(value, element);

        if (progress < 1) {
            requestAnimationFrame(step);
        }
    };

    requestAnimationFrame(step);
}

function initializeCounters() {
    const counters = document.querySelectorAll('[data-count]');
    if (!counters.length) {
        return;
    }

    const runAllCounters = () => {
        counters.forEach(counter => animateCounter(counter));
    };

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.35 });

        counters.forEach(counter => observer.observe(counter));
    } else {
        runAllCounters();
    }

    const triggerAllCountersOnce = () => {
        runAllCounters();
        document.removeEventListener('pointerdown', triggerAllCountersOnce);
        document.removeEventListener('keydown', triggerAllCountersOnce);
        window.removeEventListener('scroll', triggerAllCountersOnce);
    };

    document.addEventListener('pointerdown', triggerAllCountersOnce, { once: true });
    document.addEventListener('keydown', triggerAllCountersOnce, { once: true });
    window.addEventListener('scroll', triggerAllCountersOnce, { once: true, passive: true });
}

// Set active nav link based on current page
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-link');
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });

    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (menuToggle && navMenu) {
        const closeMenu = () => {
            navMenu.classList.remove('open');
            menuToggle.setAttribute('aria-expanded', 'false');
        };

        menuToggle.setAttribute('aria-label', 'Toggle navigation menu');
        menuToggle.setAttribute('aria-expanded', 'false');

        menuToggle.addEventListener('click', function (event) {
            event.stopPropagation();
            const isOpen = navMenu.classList.toggle('open');
            menuToggle.setAttribute('aria-expanded', String(isOpen));
        });

        navMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', closeMenu);
        });

        document.addEventListener('click', function (event) {
            if (!navMenu.contains(event.target) && !menuToggle.contains(event.target)) {
                closeMenu();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeMenu();
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 768) {
                closeMenu();
            }
        });
    }

    initializeCounters();
});

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('error');
            isValid = false;
        } else {
            input.classList.remove('error');
        }
    });

    return isValid;
}

// Authentication check
function checkAuthStatus() {
    const token = localStorage.getItem('authToken');
    const userRole = localStorage.getItem('userRole');
    
    return {
        isAuthenticated: !!token,
        userRole: userRole,
        token: token
    };
}

// Logout function
function logout() {
    if (confirm('Are you sure you want to sign out?')) {
        localStorage.removeItem('authToken');
        localStorage.removeItem('userRole');
        localStorage.removeItem('userId');
        const pathname = window.location.pathname;
        const homePath = pathname.includes('/pages/archive/')
            ? '../../index.html'
            : pathname.includes('/pages/')
                ? '../index.html'
                : 'index.html';
        window.location.href = homePath;
    }
}

// API call helper function
async function apiCall(endpoint, method = 'GET', data = null) {
    const baseURL = 'http://localhost/farmdialogue/back/api/';
    const token = localStorage.getItem('authToken');
    
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    };

    if (data && (method === 'POST' || method === 'PUT')) {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(baseURL + endpoint, options);
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'API Error');
        }
        
        return result;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

// Enhanced login handler
async function handleLogin(event) {
    event.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const rememberMe = document.getElementById('rememberMe').checked;
    
    const submitButton = event.target.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Signing in...';
    submitButton.disabled = true;
    
    try {
        const response = await fetch('http://localhost/farmdialogue/back/api/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            // Store auth data
            localStorage.setItem('authToken', data.token);
            localStorage.setItem('userRole', data.userRole);
            localStorage.setItem('userId', data.userId);
            
            if (rememberMe) {
                localStorage.setItem('rememberEmail', email);
            }
            
            showToast('Login successful. Redirecting...', 'success', { duration: 2200 });
            
            // Redirect after brief delay for notification
            setTimeout(() => {
                window.location.href = 'dashboard.html';
            }, 500);
        } else {
            showToast(data.message || 'Login failed. Please check your credentials.', 'error');
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Connection error. Please try again.', 'error');
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    }
}

async function handleContactForm(event) {
    event.preventDefault();

    const submitButton = event.target.querySelector('button[type="submit"]');
    const originalText = submitButton ? submitButton.textContent : '';
    if (submitButton) {
        submitButton.textContent = 'Sending...';
        submitButton.disabled = true;
    }

    try {
        showToast('Message sent successfully. We will get back to you soon.', 'success', { duration: 2600, title: 'Message Sent' });
        event.target.reset();
    } catch (error) {
        console.error('Contact form error:', error);
        showToast('Unable to send your message right now. Please try again.', 'error');
    } finally {
        if (submitButton) {
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }
    }
}

function submitQuestion(event) {
    event.preventDefault();
    const title = document.getElementById('questionTitle').value;
    const category = document.getElementById('questionCategory').value;
    showToast(`Your question "${title}" about ${category} has been posted.`, 'success', { duration: 3000, title: 'Question Posted' });
    if (typeof closeAskModal === 'function') {
        closeAskModal();
    }
    event.target.reset();
}

function handleRegisterSuccess() {
    showToast('Registration successful. Please log in with your credentials.', 'success', { duration: 2800, title: 'Account Created' });
}

// Pre-fill remembered email on login page
function fillRememberedEmail() {
    const loginPage = document.getElementById('email');
    if (loginPage) {
        const rememberedEmail = localStorage.getItem('rememberEmail');
        if (rememberedEmail) {
            loginPage.value = rememberedEmail;
            document.getElementById('rememberMe').checked = true;
        }
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    .error {
        border-color: #e74c3c !important;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);

// Call pre-fill on page load
document.addEventListener('DOMContentLoaded', fillRememberedEmail);

console.log('FarmDialogue Frontend Scripts Loaded');
