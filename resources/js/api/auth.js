import client from '@/api/client.js'

const login = (credentials) => {
    return client
        .post('/login', credentials)
        .then((response) => response)
        .catch((error) => error.response)
}

const register = (credentials) => {
    return client
        .post('/register', credentials)
        .then((response) => response)
        .catch((error) => error.response)
}
const forgotPassword = (email) => {
    return client
        .post('/forgot-password', email)
        .then((response) => response)
        .catch((error) => error.response)
}
const authAPI = {
    login,
    register,
    forgotPassword
}
export default authAPI
