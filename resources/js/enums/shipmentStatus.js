export const ShipmentStatus = {
    IN_WAREHOUSE: 0,
    DELIVERING: 1,
    DELIVERED: 2,
    RETURNED: 3,

    toString(status) {
        const labels = {
            0: 'In Warehouse',
            1: 'Being delivered',
            2: 'Delivered',
            3: 'Returned'
        };
        return labels[status];
    }
};
