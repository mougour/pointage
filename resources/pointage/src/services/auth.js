import api from '../lib/axios';

const API_URL = 'http://localhost:8000/api';

// Initialize CSRF protection
export const initializeCsrf = async () => {
    try {
        await api.get('/sanctum/csrf-cookie');
    } catch (error) {
        console.error('CSRF initialization failed:', error);
        throw error;
    }
};

// Register User
export const register = async (name, email, password) => {
    try {
        await initializeCsrf();
        
        const response = await api.post(`${API_URL}/register`, {
            name,
            email,
            password,
            password_confirmation: password,
        });
        
        if (response.data.token) {
            localStorage.setItem('auth_token', response.data.token);
            localStorage.setItem('user', JSON.stringify(response.data.user));
        }
        
        return response.data;
    } catch (error) {
        if (error.response?.data?.errors) {
            throw new Error(Object.values(error.response.data.errors).flat().join('\n'));
        }
        throw error;
    }
};

// Login User
export const login = async (email, password) => {
    try {
        await initializeCsrf();
        
        const response = await api.post(`${API_URL}/login`, {
            email,
            password,
            device_name: window.navigator.userAgent // Using user agent as device name
        });
        
        if (response.data.token) {
            localStorage.setItem('auth_token', response.data.token);
            localStorage.setItem('user', JSON.stringify(response.data.user));
            api.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
        }
        
        return response.data;
    } catch (error) {
        if (error.response?.data?.errors) {
            throw new Error(Object.values(error.response.data.errors).flat().join('\n'));
        }
        throw error;
    }
};

// Logout User
export const logout = async () => {
    try {
        await api.post(`${API_URL}/logout`);
    } catch (error) {
        console.error('Logout failed:', error);
    } finally {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
        delete api.defaults.headers.common['Authorization'];
    }
};

// Get authenticated user
export const getUser = async () => {
    try {
        const response = await api.get(`${API_URL}/user`);
        return response.data;
    } catch (error) {
        if (error.response?.status === 401) {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
        }
        throw error;
    }
};

// Get stored user data
export const getStoredUser = () => {
    const userStr = localStorage.getItem('user');
    return userStr ? JSON.parse(userStr) : null;
};

// Check if user is authenticated
export const isAuthenticated = () => {
    return !!localStorage.getItem('auth_token');
}; 