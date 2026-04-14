// FarmDialogue Frontend Scripts

// Smooth scroll to section
function scrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
    }
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
        window.location.href = 'index.html';
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

// Enhanced notification system
function showNotification(message, type = 'success', duration = 3000) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 2rem;
        background-color: ${type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : '#f39c12'};
        color: white;
        border-radius: 8px;
        z-index: 1000;
        animation: slideIn 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        font-weight: 500;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, duration);
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
            
            showNotification('Login successful! Redirecting...', 'success', 2000);
            
            // Redirect after brief delay for notification
            setTimeout(() => {
                window.location.href = 'dashboard.html';
            }, 500);
        } else {
            showNotification(data.message || 'Login failed. Please check your credentials.', 'error');
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Connection error. Please try again.', 'error');
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    }
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
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
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
