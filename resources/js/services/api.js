// API Service - Arab-Med Platform
import axios from 'axios';
import { authService } from './auth.js';

class ApiService {
    constructor() {
        this.baseURL = window.location.origin;
        this.apiClient = this.createApiClient();
        this.setupInterceptors();
    }

    createApiClient() {
        return axios.create({
            baseURL: `${this.baseURL}/api`,
            timeout: 30000,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    }

    setupInterceptors() {
        // Request interceptor
        this.apiClient.interceptors.request.use(
            (config) => {
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    config.headers['X-CSRF-TOKEN'] = csrfToken;
                }

                // Add auth token
                const token = authService.getToken();
                if (token) {
                    config.headers.Authorization = `Bearer ${token}`;
                }

                // Add loading state
                this.showLoading();

                return config;
            },
            (error) => {
                this.hideLoading();
                return Promise.reject(error);
            }
        );

        // Response interceptor
        this.apiClient.interceptors.response.use(
            (response) => {
                this.hideLoading();
                return response;
            },
            (error) => {
                this.hideLoading();
                return this.handleError(error);
            }
        );
    }

    handleError(error) {
        if (error.response) {
            const { status, data } = error.response;

            switch (status) {
                case 401:
                    authService.logout();
                    window.location.href = '/login';
                    break;
                case 403:
                    this.showNotification('Access denied', 'error');
                    break;
                case 404:
                    this.showNotification('Resource not found', 'error');
                    break;
                case 422:
                    // Validation errors
                    if (data.errors) {
                        this.handleValidationErrors(data.errors);
                    }
                    break;
                case 429:
                    this.showNotification('Too many requests. Please try again later.', 'warning');
                    break;
                case 500:
                    this.showNotification('Server error. Please try again later.', 'error');
                    break;
                default:
                    this.showNotification(data.message || 'An error occurred', 'error');
            }
        } else if (error.request) {
            this.showNotification('Network error. Please check your connection.', 'error');
        } else {
            this.showNotification('An unexpected error occurred', 'error');
        }

        return Promise.reject(error);
    }

    handleValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('error');
                const errorElement = input.parentNode.querySelector('.field-error');
                if (errorElement) {
                    errorElement.textContent = errors[field][0];
                }
            }
        });
    }

    showLoading() {
        const loader = document.querySelector('.global-loader');
        if (loader) {
            loader.style.display = 'flex';
        }
    }

    hideLoading() {
        const loader = document.querySelector('.global-loader');
        if (loader) {
            loader.style.display = 'none';
        }
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;

        // Add to page
        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);

        // Close button handler
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.remove();
        });
    }

    // Generic HTTP methods
    async get(url, config = {}) {
        try {
            const response = await this.apiClient.get(url, config);
            return response.data;
        } catch (error) {
            throw error;
        }
    }

    async post(url, data = {}, config = {}) {
        try {
            const response = await this.apiClient.post(url, data, config);
            return response.data;
        } catch (error) {
            throw error;
        }
    }

    async put(url, data = {}, config = {}) {
        try {
            const response = await this.apiClient.put(url, data, config);
            return response.data;
        } catch (error) {
            throw error;
        }
    }

    async patch(url, data = {}, config = {}) {
        try {
            const response = await this.apiClient.patch(url, data, config);
            return response.data;
        } catch (error) {
            throw error;
        }
    }

    async delete(url, config = {}) {
        try {
            const response = await this.apiClient.delete(url, config);
            return response.data;
        } catch (error) {
            throw error;
        }
    }

    // File upload
    async uploadFile(url, file, onProgress = null) {
        const formData = new FormData();
        formData.append('file', file);

        const config = {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        };

        if (onProgress) {
            config.onUploadProgress = (progressEvent) => {
                const percentCompleted = Math.round(
                    (progressEvent.loaded * 100) / progressEvent.total
                );
                onProgress(percentCompleted);
            };
        }

        return this.post(url, formData, config);
    }

    // Batch requests
    async batch(requests) {
        try {
            const promises = requests.map(request => {
                const { method, url, data, config } = request;
                return this[method](url, data, config);
            });

            return await Promise.all(promises);
        } catch (error) {
            throw error;
        }
    }

    // Pagination helper
    async paginate(url, page = 1, perPage = 15, filters = {}) {
        const params = {
            page,
            per_page: perPage,
            ...filters
        };

        return this.get(url, { params });
    }

    // Search helper
    async search(url, query, filters = {}) {
        const params = {
            q: query,
            ...filters
        };

        return this.get(url, { params });
    }
}

// API endpoints for Arab-Med platform
export class ArabMedApi extends ApiService {
    // Authentication
    async login(credentials) {
        return this.post('/auth/login', credentials);
    }

    async register(userData) {
        return this.post('/auth/register', userData);
    }

    async logout() {
        return this.post('/auth/logout');
    }

    async refreshToken() {
        return this.post('/auth/refresh');
    }

    async forgotPassword(email) {
        return this.post('/auth/forgot-password', { email });
    }

    async resetPassword(data) {
        return this.post('/auth/reset-password', data);
    }

    // User management
    async getProfile() {
        return this.get('/user/profile');
    }

    async updateProfile(data) {
        return this.put('/user/profile', data);
    }

    async changePassword(data) {
        return this.post('/user/change-password', data);
    }

    async uploadAvatar(file) {
        return this.uploadFile('/user/avatar', file);
    }

    // Courses
    async getCourses(filters = {}) {
        return this.paginate('/courses', filters.page, filters.per_page, filters);
    }

    async getCourse(id) {
        return this.get(`/courses/${id}`);
    }

    async createCourse(data) {
        return this.post('/courses', data);
    }

    async updateCourse(id, data) {
        return this.put(`/courses/${id}`, data);
    }

    async deleteCourse(id) {
        return this.delete(`/courses/${id}`);
    }

    async enrollCourse(courseId) {
        return this.post(`/courses/${courseId}/enroll`);
    }

    async unenrollCourse(courseId) {
        return this.delete(`/courses/${courseId}/enroll`);
    }

    // Videos
    async getVideos(courseId, filters = {}) {
        return this.paginate(`/courses/${courseId}/videos`, filters.page, filters.per_page, filters);
    }

    async getVideo(courseId, videoId) {
        return this.get(`/courses/${courseId}/videos/${videoId}`);
    }

    async createVideo(courseId, data) {
        return this.post(`/courses/${courseId}/videos`, data);
    }

    async updateVideo(courseId, videoId, data) {
        return this.put(`/courses/${courseId}/videos/${videoId}`, data);
    }

    async deleteVideo(courseId, videoId) {
        return this.delete(`/courses/${courseId}/videos/${videoId}`);
    }

    async markVideoWatched(courseId, videoId, progress = 100) {
        return this.post(`/courses/${courseId}/videos/${videoId}/progress`, { progress });
    }

    // Exams
    async getExams(courseId, filters = {}) {
        return this.paginate(`/courses/${courseId}/exams`, filters.page, filters.per_page, filters);
    }

    async getExam(courseId, examId) {
        return this.get(`/courses/${courseId}/exams/${examId}`);
    }

    async createExam(courseId, data) {
        return this.post(`/courses/${courseId}/exams`, data);
    }

    async updateExam(courseId, examId, data) {
        return this.put(`/courses/${courseId}/exams/${examId}`, data);
    }

    async deleteExam(courseId, examId) {
        return this.delete(`/courses/${courseId}/exams/${examId}`);
    }

    async submitExam(courseId, examId, answers) {
        return this.post(`/courses/${courseId}/exams/${examId}/submit`, { answers });
    }

    async getExamResults(courseId, examId) {
        return this.get(`/courses/${courseId}/exams/${examId}/results`);
    }

    // Categories
    async getCategories() {
        return this.get('/categories');
    }

    async createCategory(data) {
        return this.post('/categories', data);
    }

    async updateCategory(id, data) {
        return this.put(`/categories/${id}`, data);
    }

    async deleteCategory(id) {
        return this.delete(`/categories/${id}`);
    }

    // Dashboard & Analytics
    async getDashboardStats() {
        return this.get('/dashboard/stats');
    }

    async getAnalytics(period = '30d') {
        return this.get('/analytics', { params: { period } });
    }

    async getReports(type, filters = {}) {
        return this.get(`/reports/${type}`, { params: filters });
    }

    // Notifications
    async getNotifications(filters = {}) {
        return this.paginate('/notifications', filters.page, filters.per_page, filters);
    }

    async markNotificationRead(id) {
        return this.patch(`/notifications/${id}/read`);
    }

    async markAllNotificationsRead() {
        return this.post('/notifications/mark-all-read');
    }

    // Settings
    async getSettings() {
        return this.get('/settings');
    }

    async updateSettings(data) {
        return this.put('/settings', data);
    }

    // Search
    async globalSearch(query, filters = {}) {
        return this.search('/search', query, filters);
    }

    async searchCourses(query, filters = {}) {
        return this.search('/courses/search', query, filters);
    }

    async searchUsers(query, filters = {}) {
        return this.search('/users/search', query, filters);
    }
}

// Create and export singleton instance
export const apiService = new ArabMedApi();
export default apiService;
