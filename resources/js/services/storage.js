// Storage Service - Arab-Med Platform

class StorageService {
    constructor() {
        this.prefix = 'arabmed_';
        this.cache = new Map();
        this.cacheExpiry = new Map();
        
        this.setupStorageEvents();
    }

    // Setup storage event listeners
    setupStorageEvents() {
        // Listen for storage changes in other tabs
        window.addEventListener('storage', (e) => {
            if (e.key && e.key.startsWith(this.prefix)) {
                const event = new CustomEvent('storageChanged', {
                    detail: {
                        key: e.key.replace(this.prefix, ''),
                        oldValue: e.oldValue,
                        newValue: e.newValue,
                        storageArea: e.storageArea
                    }
                });
                window.dispatchEvent(event);
            }
        });
    }

    // Generate storage key with prefix
    getKey(key) {
        return `${this.prefix}${key}`;
    }

    // Local Storage Methods
    setLocal(key, value, options = {}) {
        try {
            const storageKey = this.getKey(key);
            const data = {
                value: value,
                timestamp: Date.now(),
                expires: options.expires ? Date.now() + options.expires : null,
                encrypted: options.encrypt || false
            };

            if (options.encrypt) {
                data.value = this.encrypt(JSON.stringify(value));
            }

            localStorage.setItem(storageKey, JSON.stringify(data));
            return true;
        } catch (error) {
            console.error('Error setting localStorage:', error);
            return false;
        }
    }

    getLocal(key, defaultValue = null) {
        try {
            const storageKey = this.getKey(key);
            const item = localStorage.getItem(storageKey);
            
            if (!item) return defaultValue;

            const data = JSON.parse(item);

            // Check expiry
            if (data.expires && Date.now() > data.expires) {
                this.removeLocal(key);
                return defaultValue;
            }

            // Decrypt if needed
            if (data.encrypted) {
                return JSON.parse(this.decrypt(data.value));
            }

            return data.value;
        } catch (error) {
            console.error('Error getting localStorage:', error);
            return defaultValue;
        }
    }

    removeLocal(key) {
        try {
            const storageKey = this.getKey(key);
            localStorage.removeItem(storageKey);
            return true;
        } catch (error) {
            console.error('Error removing localStorage:', error);
            return false;
        }
    }

    clearLocal() {
        try {
            const keys = Object.keys(localStorage);
            keys.forEach(key => {
                if (key.startsWith(this.prefix)) {
                    localStorage.removeItem(key);
                }
            });
            return true;
        } catch (error) {
            console.error('Error clearing localStorage:', error);
            return false;
        }
    }

    // Session Storage Methods
    setSession(key, value, options = {}) {
        try {
            const storageKey = this.getKey(key);
            const data = {
                value: value,
                timestamp: Date.now(),
                encrypted: options.encrypt || false
            };

            if (options.encrypt) {
                data.value = this.encrypt(JSON.stringify(value));
            }

            sessionStorage.setItem(storageKey, JSON.stringify(data));
            return true;
        } catch (error) {
            console.error('Error setting sessionStorage:', error);
            return false;
        }
    }

    getSession(key, defaultValue = null) {
        try {
            const storageKey = this.getKey(key);
            const item = sessionStorage.getItem(storageKey);
            
            if (!item) return defaultValue;

            const data = JSON.parse(item);

            // Decrypt if needed
            if (data.encrypted) {
                return JSON.parse(this.decrypt(data.value));
            }

            return data.value;
        } catch (error) {
            console.error('Error getting sessionStorage:', error);
            return defaultValue;
        }
    }

    removeSession(key) {
        try {
            const storageKey = this.getKey(key);
            sessionStorage.removeItem(storageKey);
            return true;
        } catch (error) {
            console.error('Error removing sessionStorage:', error);
            return false;
        }
    }

    clearSession() {
        try {
            const keys = Object.keys(sessionStorage);
            keys.forEach(key => {
                if (key.startsWith(this.prefix)) {
                    sessionStorage.removeItem(key);
                }
            });
            return true;
        } catch (error) {
            console.error('Error clearing sessionStorage:', error);
            return false;
        }
    }

    // Memory Cache Methods
    setCache(key, value, ttl = 300000) { // Default 5 minutes
        this.cache.set(key, value);
        this.cacheExpiry.set(key, Date.now() + ttl);
        
        // Auto cleanup expired cache
        setTimeout(() => {
            if (this.isCacheExpired(key)) {
                this.removeCache(key);
            }
        }, ttl);
    }

    getCache(key, defaultValue = null) {
        if (this.isCacheExpired(key)) {
            this.removeCache(key);
            return defaultValue;
        }
        
        return this.cache.has(key) ? this.cache.get(key) : defaultValue;
    }

    removeCache(key) {
        this.cache.delete(key);
        this.cacheExpiry.delete(key);
    }

    clearCache() {
        this.cache.clear();
        this.cacheExpiry.clear();
    }

    isCacheExpired(key) {
        const expiry = this.cacheExpiry.get(key);
        return expiry && Date.now() > expiry;
    }

    // Utility Methods
    exists(key, storage = 'local') {
        switch (storage) {
            case 'local':
                return localStorage.getItem(this.getKey(key)) !== null;
            case 'session':
                return sessionStorage.getItem(this.getKey(key)) !== null;
            case 'cache':
                return this.cache.has(key) && !this.isCacheExpired(key);
            default:
                return false;
        }
    }

    size(storage = 'local') {
        try {
            let count = 0;
            switch (storage) {
                case 'local':
                    Object.keys(localStorage).forEach(key => {
                        if (key.startsWith(this.prefix)) count++;
                    });
                    break;
                case 'session':
                    Object.keys(sessionStorage).forEach(key => {
                        if (key.startsWith(this.prefix)) count++;
                    });
                    break;
                case 'cache':
                    count = this.cache.size;
                    break;
            }
            return count;
        } catch (error) {
            console.error('Error getting storage size:', error);
            return 0;
        }
    }

    getStorageUsage() {
        try {
            let localUsage = 0;
            let sessionUsage = 0;

            // Calculate localStorage usage
            Object.keys(localStorage).forEach(key => {
                if (key.startsWith(this.prefix)) {
                    localUsage += localStorage.getItem(key).length;
                }
            });

            // Calculate sessionStorage usage
            Object.keys(sessionStorage).forEach(key => {
                if (key.startsWith(this.prefix)) {
                    sessionUsage += sessionStorage.getItem(key).length;
                }
            });

            return {
                localStorage: {
                    used: localUsage,
                    usedMB: (localUsage / (1024 * 1024)).toFixed(2),
                    available: 5 * 1024 * 1024 - localUsage, // Assuming 5MB limit
                    availableMB: ((5 * 1024 * 1024 - localUsage) / (1024 * 1024)).toFixed(2)
                },
                sessionStorage: {
                    used: sessionUsage,
                    usedMB: (sessionUsage / (1024 * 1024)).toFixed(2),
                    available: 5 * 1024 * 1024 - sessionUsage,
                    availableMB: ((5 * 1024 * 1024 - sessionUsage) / (1024 * 1024)).toFixed(2)
                },
                cache: {
                    items: this.cache.size
                }
            };
        } catch (error) {
            console.error('Error calculating storage usage:', error);
            return null;
        }
    }

    // Encryption/Decryption (Simple Base64 - for demo purposes)
    encrypt(text) {
        try {
            return btoa(encodeURIComponent(text));
        } catch (error) {
            console.error('Encryption error:', error);
            return text;
        }
    }

    decrypt(encryptedText) {
        try {
            return decodeURIComponent(atob(encryptedText));
        } catch (error) {
            console.error('Decryption error:', error);
            return encryptedText;
        }
    }

    // Backup and Restore
    backup(storage = 'local') {
        try {
            const data = {};
            const storageObj = storage === 'local' ? localStorage : sessionStorage;
            
            Object.keys(storageObj).forEach(key => {
                if (key.startsWith(this.prefix)) {
                    data[key] = storageObj.getItem(key);
                }
            });

            return {
                timestamp: Date.now(),
                storage: storage,
                data: data
            };
        } catch (error) {
            console.error('Backup error:', error);
            return null;
        }
    }

    restore(backupData, storage = 'local') {
        try {
            if (!backupData || !backupData.data) {
                throw new Error('Invalid backup data');
            }

            const storageObj = storage === 'local' ? localStorage : sessionStorage;
            
            Object.entries(backupData.data).forEach(([key, value]) => {
                storageObj.setItem(key, value);
            });

            return true;
        } catch (error) {
            console.error('Restore error:', error);
            return false;
        }
    }

    // Arab-Med Specific Storage Methods
    
    // User preferences
    setUserPreferences(preferences) {
        return this.setLocal('user_preferences', preferences);
    }

    getUserPreferences() {
        return this.getLocal('user_preferences', {
            language: 'ar',
            theme: 'light',
            notifications: true,
            autoplay: false,
            playbackSpeed: 1.0,
            subtitles: true
        });
    }

    // Course progress
    setCourseProgress(courseId, progress) {
        const key = `course_progress_${courseId}`;
        return this.setLocal(key, progress);
    }

    getCourseProgress(courseId) {
        const key = `course_progress_${courseId}`;
        return this.getLocal(key, {
            completedVideos: [],
            completedExams: [],
            totalProgress: 0,
            lastAccessed: null
        });
    }

    // Video watch history
    setVideoProgress(videoId, progress) {
        const key = `video_progress_${videoId}`;
        return this.setLocal(key, {
            progress: progress,
            timestamp: Date.now(),
            completed: progress >= 90
        });
    }

    getVideoProgress(videoId) {
        const key = `video_progress_${videoId}`;
        return this.getLocal(key, { progress: 0, completed: false });
    }

    // Exam attempts
    setExamAttempt(examId, attempt) {
        const key = `exam_attempt_${examId}`;
        const attempts = this.getLocal(key, []);
        attempts.push({
            ...attempt,
            timestamp: Date.now()
        });
        return this.setLocal(key, attempts);
    }

    getExamAttempts(examId) {
        const key = `exam_attempt_${examId}`;
        return this.getLocal(key, []);
    }

    // Search history
    addSearchHistory(query, type = 'general') {
        const key = `search_history_${type}`;
        const history = this.getLocal(key, []);
        
        // Remove duplicate
        const filtered = history.filter(item => item.query !== query);
        
        // Add new search to beginning
        filtered.unshift({
            query: query,
            timestamp: Date.now()
        });
        
        // Keep only last 20 searches
        const limited = filtered.slice(0, 20);
        
        return this.setLocal(key, limited);
    }

    getSearchHistory(type = 'general') {
        const key = `search_history_${type}`;
        return this.getLocal(key, []);
    }

    clearSearchHistory(type = 'general') {
        const key = `search_history_${type}`;
        return this.removeLocal(key);
    }

    // Offline data
    setOfflineData(key, data, expires = 86400000) { // Default 24 hours
        return this.setLocal(`offline_${key}`, data, { expires });
    }

    getOfflineData(key) {
        return this.getLocal(`offline_${key}`);
    }

    // Form drafts
    saveDraft(formId, data) {
        const key = `draft_${formId}`;
        return this.setSession(key, {
            data: data,
            timestamp: Date.now()
        });
    }

    getDraft(formId) {
        const key = `draft_${formId}`;
        const draft = this.getSession(key);
        
        if (draft) {
            // Check if draft is older than 1 hour
            const oneHour = 60 * 60 * 1000;
            if (Date.now() - draft.timestamp > oneHour) {
                this.removeSession(key);
                return null;
            }
            return draft.data;
        }
        
        return null;
    }

    removeDraft(formId) {
        const key = `draft_${formId}`;
        return this.removeSession(key);
    }

    // Settings cache
    cacheSettings(settings) {
        return this.setCache('app_settings', settings, 600000); // 10 minutes
    }

    getCachedSettings() {
        return this.getCache('app_settings');
    }

    // API response cache
    cacheApiResponse(endpoint, data, ttl = 300000) { // 5 minutes default
        const key = `api_${endpoint.replace(/[^a-zA-Z0-9]/g, '_')}`;
        return this.setCache(key, data, ttl);
    }

    getCachedApiResponse(endpoint) {
        const key = `api_${endpoint.replace(/[^a-zA-Z0-9]/g, '_')}`;
        return this.getCache(key);
    }

    // Cleanup expired data
    cleanup() {
        try {
            // Cleanup localStorage
            const localKeys = Object.keys(localStorage);
            localKeys.forEach(key => {
                if (key.startsWith(this.prefix)) {
                    try {
                        const data = JSON.parse(localStorage.getItem(key));
                        if (data.expires && Date.now() > data.expires) {
                            localStorage.removeItem(key);
                        }
                    } catch (error) {
                        // Invalid JSON, remove it
                        localStorage.removeItem(key);
                    }
                }
            });

            // Cleanup cache
            const cacheKeys = Array.from(this.cache.keys());
            cacheKeys.forEach(key => {
                if (this.isCacheExpired(key)) {
                    this.removeCache(key);
                }
            });

            return true;
        } catch (error) {
            console.error('Cleanup error:', error);
            return false;
        }
    }
}

// Create and export singleton instance
export const storageService = new StorageService();
export default storageService;
