import client from '@/api/client.js'
const getShipment = (id) => {
    return client
        .get(`/shipments/${id}`)
        .then((response) => response)
        .catch((error) => error.response)
}
const index = (data) => {
    return client
        .post(`/shipments`, data)
        .then((response) => response)
        .catch((error) => error.response)
}
const update = (id, data) => {
    return client
        .patch(`/shipment/update/${id}`, data)
        .then((response) => response)
        .catch((error) => error.response)
}
const calculations = (order_id) => {
    return client
        .get(`/calculations/${order_id}`)
        .then((response) => response)
        .catch((error) => error.response)
}
const shipmentsAPi = {
    getShipment,
    index,
    update,
    calculations
}
export default shipmentsAPi
