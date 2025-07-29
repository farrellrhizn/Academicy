// auth-check.js (versi sederhana tanpa token)
document.addEventListener('DOMContentLoaded', function() {
    // Cek authentication melalui API
    checkAuthentication();
});

// Fungsi untuk cek authentication
async function checkAuthentication() {
    // Jika di halaman login, skip check
    if (window.location.pathname.includes('/login')) {
        return;
    }
    
    try {
        const response = await fetch('/api/auth/check', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success && !result.is_authenticated) {
            // Jika di halaman yang butuh auth dan belum login
            if (window.location.pathname.includes('/dashboard')) {
                window.location.href = '/login';
            }
        }
        
        // Simpan info user ke localStorage jika login
        if (result.success && result.is_authenticated) {
            localStorage.setItem('user_type', result.user_type);
            localStorage.setItem('user_id', result.user_id);
        }
        
    } catch (error) {
        console.error('Auth check error:', error);
        
        // Jika error dan di halaman protected, redirect ke login
        if (window.location.pathname.includes('/dashboard')) {
            window.location.href = '/login';
        }
    }
}

// Fungsi logout sederhana
async function logout() {
    try {
        const response = await fetch('/api/auth/logout', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        // Clear localStorage
        localStorage.removeItem('user_type');
        localStorage.removeItem('user_id');
        localStorage.removeItem('user_data');
        localStorage.removeItem('auth_token');
        
        // Redirect ke login
        window.location.href = '/login';
        
    } catch (error) {
        console.error('Logout error:', error);
        
        // Tetap redirect meskipun ada error
        localStorage.clear();
        window.location.href = '/login';
    }
}

// Fungsi untuk mendapatkan data user dari localStorage
function getCurrentUser() {
    const userData = localStorage.getItem('user_data');
    return userData ? JSON.parse(userData) : null;
}

// Fungsi untuk mendapatkan user type
function getUserType() {
    return localStorage.getItem('user_type');
}

// Fungsi sederhana untuk cek login
function isAuthenticated() {
    return localStorage.getItem('user_type') !== null;
}