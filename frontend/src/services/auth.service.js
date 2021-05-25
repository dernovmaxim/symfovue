import axios from 'axios';

// const API_URL = 'http://localhost:8080/api/auth/';
const API_URL = 'http://sym.local/auth/';

class AuthService {
    login(user) {
        const formData = new FormData();
        formData.append('email', user.email );
        formData.append('password', user.password );
        return axios
            .post(
                API_URL + 'login',
                formData
            )
            .then(response => {
                if (response.data.accessToken) {
                    localStorage.setItem('user', JSON.stringify(response.data));
                }

                return response.data;
            });
    }

    logout() {
        localStorage.removeItem('user');
    }

    register(user) {
        return axios.post(API_URL + 'signup', {
            username: user.username,
            email: user.email,
            password: user.password
        });
    }
}

export default new AuthService();
