// Authentication Service - Arab-Med Platform

class AuthService {
    constructor() {
        this.tokenKey = 'arab_med_token';
        this.userKey = 'arab_med_user';
        this.refreshTokenKey = 'arab_med_refresh_token';
        this.tokenExpiryKey = 'arab_med_token_expiry';
        
        this.user = null;
        this.token = null;
        this.refreshToken = null;
        this.tokenExpiry = null;
        
        this.loadFromStorage();
        this.setupTokenRefresh();
    }

    // Load authentication data from localStorage
    loadFromStorage() {
        try {
            this.token = localStorage.getItem(this.tokenKey);
            this.refreshToken = localStorage.getItem(this.refreshTokenKey);
            this.tokenExpiry = localStorage.getItem(this.tokenExpiryKey);
            
            const userData = localStorage.getItem(this.userKey);
            if (userData) {
                this.user = JSON.parse(userData);
            }
        } catch (error) {
            console.error('Error loading auth data from storage:', error);
            this.clearStorage();
        }
    }

    // Save authentication data to localStorage
    saveToStorage() {
        try {
            if (this.token) {
                localStorage.setItem(this.tokenKey, this.token);
            }
            if (this.refreshToken) {
                localStorage.setItem(this.refreshTokenKey, this.refreshToken);
            }
            if (this.tokenExpiry) {
                localStorage.setItem(this.tokenExpiryKey, this.tokenExpiry);
            }
            if (this.user) {
                localStorage.setItem(this.userKey, JSON.stringify(this.user));
            }
        } catch (error) {
            console.error('Error saving auth data to storage:', error);
        }
    }

    // Clear all authentication data
    clearStorage() {
        localStorage.removeItem(this.tokenKey);
        localStorage.removeItem(this.userKey);
        localStorage.removeItem(this.refreshTokenKey);
        localStorage.removeItem(this.tokenExpiryKey);
        
        this.user = null;
        this.token = null;
        this.refreshToken = null;
        this.tokenExpiry = null;
    }

    // Login user
    async login(credentials) {
        try {
            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify(credentials)
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Login failed');
            }

            this.setAuthData(data);
            this.dispatchAuthEvent('login', this.user);
            
            return data;
        } catch (error) {
            console.error('Login error:', error);
            throw error;
        }
    }

    // Register user
    async register(userData) {
        try {
            const response = await fetch('/api/auth/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify(userData)
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Registration failed');
            }

            this.setAuthData(data);
            this.dispatchAuthEvent('register', this.user);
            
            return data;
        } catch (error) {
            console.error('Registration error:', error);
            throw error;
        }
    }

    // Logout user
    async logout() {
        try {
            if (this.token) {
                await fetch('/api/auth/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${this.token}`,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken()
                    }
                });
            }
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            this.clearStorage();
            this.dispatchAuthEvent('logout');
        }
    }

    // Refresh authentication token
    async refreshAuthToken() {
        if (!this.refreshToken) {
            throw new Error('No refresh token available');
        }

        try {
            const response = await fetch('/api/auth/refresh', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.refreshToken}`,
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Token refresh failed');
            }

            this.setAuthData(data);
            return data;
        } catch (error) {
            console.error('Token refresh error:', error);
            this.logout();
            throw error;
        }
    }

    // Set authentication data
    setAuthData(data) {
        this.token = data.access_token || data.token;
        this.refreshToken = data.refresh_token;
        this.user = data.user;
        
        // Calculate token expiry (default to 1 hour if not provided)
        const expiresIn = data.expires_in || 3600;
        this.tokenExpiry = Date.now() + (expiresIn * 1000);
        
        this.saveToStorage();
    }

    // Check if user is authenticated
    isAuthenticated() {
        return !!(this.token && this.user && !this.isTokenExpired());
    }

    // Check if token is expired
    isTokenExpired() {
        if (!this.tokenExpiry) return true;
        return Date.now() >= this.tokenExpiry;
    }

    // Get current user
    getUser() {
        return this.user;
    }

    // Get authentication token
    getToken() {
        if (this.isTokenExpired()) {
            return null;
        }
        return this.token;
    }

    // Get refresh token
    getRefreshToken() {
        return this.refreshToken;
    }

    // Update user profile
    updateUser(userData) {
        if (this.user) {
            this.user = { ...this.user, ...userData };
            this.saveToStorage();
            this.dispatchAuthEvent('userUpdated', this.user);
        }
    }

    // Check user permissions
    hasPermission(permission) {
        if (!this.user || !this.user.permissions) {
            return false;
        }
        return this.user.permissions.includes(permission);
    }

    // Check user role
    hasRole(role) {
        if (!this.user || !this.user.roles) {
            return false;
        }
        return this.user.roles.some(userRole => userRole.name === role);
    }

    // Check if user is admin
    isAdmin() {
        return this.hasRole('admin') || this.hasRole('super_admin');
    }

    // Check if user is instructor
    isInstructor() {
        return this.hasRole('instructor') || this.isAdmin();
    }

    // Check if user is student
    isStudent() {
        return this.hasRole('student');
    }

    // Get CSRF token from meta tag
    getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    // Setup automatic token refresh
    setupTokenRefresh() {
        // Check token expiry every minute
        setInterval(() => {
            if (this.token && this.isTokenExpired()) {
                if (this.refreshToken) {
                    this.refreshAuthToken().catch(() => {
                        // If refresh fails, logout user
                        this.logout();
                    });
                } else {
                    this.logout();
                }
            }
        }, 60000); // 1 minute
    }

    // Dispatch authentication events
    dispatchAuthEvent(type, data = null) {
        const event = new CustomEvent(`auth:${type}`, {
            detail: data
        });
        window.dispatchEvent(event);
    }

    // Password reset
    async forgotPassword(email) {
        try {
            const response = await fetch('/api/auth/forgot-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({ email })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Password reset request failed');
            }

            return data;
        } catch (error) {
            console.error('Forgot password error:', error);
            throw error;
        }
    }

    // Reset password
    async resetPassword(token, email, password, passwordConfirmation) {
        try {
            const response = await fetch('/api/auth/reset-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({
                    token,
                    email,
                    password,
                    password_confirmation: passwordConfirmation
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Password reset failed');
            }

            return data;
        } catch (error) {
            console.error('Reset password error:', error);
            throw error;
        }
    }

    // Change password
    async changePassword(currentPassword, newPassword, newPasswordConfirmation) {
        try {
            const response = await fetch('/api/user/change-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.token}`,
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({
                    current_password: currentPassword,
                    password: newPassword,
                    password_confirmation: newPasswordConfirmation
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Password change failed');
            }

            return data;
        } catch (error) {
            console.error('Change password error:', error);
            throw error;
        }
    }

    // Verify email
    async verifyEmail(token) {
        try {
            const response = await fetch(`/api/auth/verify-email/${token}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Email verification failed');
            }

            if (this.user) {
                this.user.email_verified_at = new Date().toISOString();
                this.saveToStorage();
                this.dispatchAuthEvent('emailVerified', this.user);
            }

            return data;
        } catch (error) {
            console.error('Email verification error:', error);
            throw error;
        }
    }

    // Resend email verification
    async resendEmailVerification() {
        try {
            const response = await fetch('/api/auth/resend-verification', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.token}`,
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to resend verification email');
            }

            return data;
        } catch (error) {
            console.error('Resend verification error:', error);
            throw error;
        }
    }

    // Two-factor authentication
    async enableTwoFactor() {
        try {
            const response = await fetch('/api/user/two-factor-authentication', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.token}`,
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to enable two-factor authentication');
            }

            return data;
        } catch (error) {
            console.error('Enable 2FA error:', error);
            throw error;
        }
    }

    async disableTwoFactor() {
        try {
            const response = await fetch('/api/user/two-factor-authentication', {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${this.token}`,
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to disable two-factor authentication');
            }

            return data;
        } catch (error) {
            console.error('Disable 2FA error:', error);
            throw error;
        }
    }

    // Social login
    async socialLogin(provider, token) {
        try {
            const response = await fetch(`/api/auth/social/${provider}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({ token })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Social login failed');
            }

            this.setAuthData(data);
            this.dispatchAuthEvent('socialLogin', this.user);
            
            return data;
        } catch (error) {
            console.error('Social login error:', error);
            throw error;
        }
    }
}

// Create and export singleton instance
export const authService = new AuthService();
export default authService;
