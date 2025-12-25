// API Configuration
const API_BASE_URL = 'http://localhost:8000/api';

// Get token from localStorage
const getToken = () => {
    return localStorage.getItem('token');
};

// Set token to localStorage
const setToken = (token) => {
    localStorage.setItem('token', token);
};

// Remove token from localStorage
const removeToken = () => {
    localStorage.removeItem('token');
};

// Get user info from localStorage
const getUser = () => {
    const userStr = localStorage.getItem('user');
    return userStr ? JSON.parse(userStr) : null;
};

// Set user info to localStorage
const setUser = (user) => {
    localStorage.setItem('user', JSON.stringify(user));
};

// Remove user info from localStorage
const removeUser = () => {
    localStorage.removeItem('user');
};

// API Request helper
const apiRequest = async (url, options = {}) => {
    const token = getToken();
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers,
    };

    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const response = await fetch(`${API_BASE_URL}${url}`, {
        ...options,
        headers,
    });

    const data = await response.json();

    if (!response.ok) {
        throw new Error(data.message || 'An error occurred');
    }

    return data;
};

// Auth API
export const authAPI = {
    register: async (name, email, password) => {
        const data = await apiRequest('/auth/register', {
            method: 'POST',
            body: JSON.stringify({ name, email, password }),
        });
        return data;
    },

    login: async (email, password) => {
        const response = await fetch(`${API_BASE_URL}/auth/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ email, password }),
        });

        const data = await response.json();
        if (!response.ok) {
            const errorMessage = data.message || data.error || 'Login failed';
            throw new Error(errorMessage);
        }

        if (data.access_token) {
            setToken(data.access_token);
            // Get user info with token
            const token = data.access_token;
            const userResponse = await fetch(`${API_BASE_URL}/auth/me`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                },
            });
            const userData = await userResponse.json();
            setUser(userData);
            return { ...data, user: userData };
        }
        return data;
    },

    logout: async () => {
        try {
            await apiRequest('/auth/logout', {
                method: 'POST',
            });
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            removeToken();
            removeUser();
            window.location.href = '/login.html';
        }
    },

    me: async () => {
        const data = await apiRequest('/auth/me');
        return data;
    },
};

// Perfume API
export const perfumeAPI = {
    getAll: async () => {
        const data = await apiRequest('/perfumes');
        return data.data || [];
    },

    getById: async (id) => {
        const data = await apiRequest(`/perfumes/${id}`);
        return data.data;
    },

    create: async (perfumeData) => {
        const formData = new FormData();
        Object.keys(perfumeData).forEach(key => {
            if (key === 'file' && perfumeData[key]) {
                formData.append('file', perfumeData[key]);
            } else if (perfumeData[key] !== null && perfumeData[key] !== undefined) {
                formData.append(key, perfumeData[key]);
            }
        });

        const token = getToken();
        const response = await fetch(`${API_BASE_URL}/perfumes`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
            body: formData,
        });

        const data = await response.json();
        if (!response.ok) {
            // Handle validation errors
            if (response.status === 422 && data.errors) {
                const errorMessages = Object.values(data.errors).flat().join(', ');
                throw new Error(errorMessages);
            }
            throw new Error(data.message || data.error || 'An error occurred');
        }
        return data;
    },

    update: async (id, perfumeData) => {
        const formData = new FormData();
        // Always send all fields for update
        const requiredFields = ['name', 'brand', 'description', 'price', 'stock', 'size_ml', 'category'];
        
        requiredFields.forEach(key => {
            if (perfumeData[key] !== null && perfumeData[key] !== undefined) {
                // Convert to string for FormData (numbers need to be strings)
                const value = perfumeData[key];
                formData.append(key, typeof value === 'number' ? value.toString() : value);
            }
        });
        
        // Handle file separately
        if (perfumeData.file) {
            formData.append('file', perfumeData.file);
        }
        
        // Debug: log FormData contents
        console.log('FormData contents:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        const token = getToken();
        
        // For Laravel, we might need to use POST with _method=PATCH for FormData
        // Add _method to FormData for method spoofing
        formData.append('_method', 'PATCH');
        
        const response = await fetch(`${API_BASE_URL}/perfumes/${id}`, {
            method: 'POST', // Use POST for FormData
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                // Don't set Content-Type - let browser set it automatically for FormData
            },
            body: formData,
        });

        const data = await response.json();
        if (!response.ok) {
            // Handle validation errors
            if (response.status === 422 && data.errors) {
                const errorMessages = Object.values(data.errors).flat().join(', ');
                throw new Error(errorMessages);
            }
            throw new Error(data.message || data.error || 'An error occurred');
        }
        return data;
    },

    delete: async (id) => {
        const data = await apiRequest(`/perfumes/${id}`, {
            method: 'DELETE',
        });
        return data;
    },
};

// Transaction API
export const transactionAPI = {
    getAll: async () => {
        const data = await apiRequest('/transactions');
        return data.data || [];
    },

    getById: async (id) => {
        const data = await apiRequest(`/transactions/${id}`);
        return data.data;
    },

    checkout: async (perfumeId, quantity) => {
        const data = await apiRequest('/transactions', {
            method: 'POST',
            body: JSON.stringify({ perfume_id: perfumeId, quantity }),
        });
        return data;
    },
};

// Export utility functions
export { getToken, setToken, removeToken, getUser, setUser, removeUser };

