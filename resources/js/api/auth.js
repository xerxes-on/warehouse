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
const authAPI = {
    login,
    register,
}
export default authAPI
