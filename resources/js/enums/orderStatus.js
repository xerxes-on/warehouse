export const OrderStatus = {
    CART: 0,
    ORDERED: 1,
    DELIVERED: 2,

    toString(status) {
        const labels = {
            0: 'In Cart',
            1: 'Ordered',
            2: 'Delivered'
        };
        return labels[status];
    }
};
