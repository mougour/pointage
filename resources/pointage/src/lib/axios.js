import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000',
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }
});

// Function to get CSRF cookie
const getCsrfToken = async () => {
    try {
        await axios.get('http://localhost:8000/sanctum/csrf-cookie', {
            withCredentials: true
        });
    } catch (error) {
        console.error('Error fetching CSRF token:', error);
    }
};

// Request interceptor
api.interceptors.request.use(async (config) => {
    // Get CSRF token before non-GET requests
    if (!['get', 'head', 'options'].includes(config.method?.toLowerCase())) {
        await getCsrfToken();
    }
    return config;
});

export default api; 