import client from '@/api/client.js'

const index = (url) => {
    return client
        .get(`/products${url}`)
        .then(response => response)
        .catch(error => error.response)
}

const show = (id) => {
    return client
        .get(`/products/${id}`)
        .then((response) => response)
        .catch((error) => error.response)
}
const search = (needle) => {
    return client
        .get(`/search/products?needle=${needle}`)
        .then((response) => response)
        .catch((error) => error.response)
}
const create = (data) => {
    return client
        .post(`/products`, data)
        .then((response) => response)
        .catch((error) => error.response)
}
const update = (id, data) => {
    return client
        .put(`/products/${id}`, data)
        .then((response) => response)
        .catch((error) => error.response)
}
const deleteProduct = (id) => {
    return client
        .delete(`/products/${id}`)
        .then((response) => response)
        .catch((error) => error.response)
}
const productsApi = {
    index,
    show,
    search,
    create,
    update,
    deleteProduct
}
export default productsApi
