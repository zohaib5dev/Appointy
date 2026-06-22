// resources/js/stores/auth.js
import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')) || null,
        token: localStorage.getItem('token') || null,
        isLoading: false
    }),
    getters: {
        isAuthenticated: (state) => !!state.token,
        userRole: (state) => state.user?.role
    },
    actions: {
        async login(credentials) {
            this.isLoading = true;
            try {
                const response = await axios.post('/api/login', credentials);
                this.token = response.data.token;
                this.user = response.data.user;
                localStorage.setItem('token', this.token);
                localStorage.setItem('user', JSON.stringify(this.user));
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                this.isLoading = false;
            }
        },
        async register(userData) {
            this.isLoading = true;
            try {
                const response = await axios.post('/api/register', userData);
                this.token = response.data.token;
                this.user = response.data.user;
                localStorage.setItem('token', this.token);
                localStorage.setItem('user', JSON.stringify(this.user));
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                this.isLoading = false;
            }
        },
        async logout() {
            try {
                await axios.post('/api/logout');
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                this.token = null;
                this.user = null;
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                delete axios.defaults.headers.common['Authorization'];
                window.location.href = '/';
            }
        },
        checkAuth() {
            const token = localStorage.getItem('token');
            const user = localStorage.getItem('user');
            if (token && user) {
                this.token = token;
                this.user = JSON.parse(user);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            }
        }
    }
});