import client from '@/api/client.js'

const updateProducts = (data) => {
    return client
        .post("/order/updated-items",  data)
        .then(response => response)
        .catch(error => error.response)
}

const removeProducts = (data) => {
    return client
        .post("/order/remove-items", data)
        .then((response) => response)
        .catch((error) => error.response)
}
const addProduct = (data) => {
    return client
        .post(`/add`, data)
        .then((response) => response)
        .catch((error) => error.response)
}
const getCart = () => {
    return client
        .get('/cart')
        .then((response) => response)
        .catch((error) => error.response)
}
const getBranches = () => {
    return client
        .get('/branches')
        .then((response) => response)
        .catch((error) => error.response)
}
const changeStatus = (id, data) => {
    return client
        .put(`/orders/${id}`, data)
        .then((response) => response)
        .catch((error) => error.response)
}
const getOrder = (id) => {
    return client
        .get(`/orders/${id}`)
        .then((response) => response)
        .catch((error) => error.response)
}
const getAll = (status) => {
    return client
        .post('/orders', status)
        .then((response) => response)
        .catch((error) => error.response)
}

const ordersApi = {
    updateProducts,
    removeProducts,
    addProduct,
    getCart,
    getBranches,
    changeStatus,
    getOrder,
    getAll
}
export default ordersApi
