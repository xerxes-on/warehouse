// $(document).ready(function () {
//     $('#simple-search').on('keyup', function () {
//         let needle = $(this).val();
//
//         if (needle.length < 2) {
//             $('#search-results').addClass('hidden');
//             return;
//         }
//
//         $.ajax({
//             url: window.indexProducts.routes.search,
//             type: 'GET',
//             data: {needle: needle},
//             success: function (response) {
//                 let resultsHtml = '';
//                 if (response.data.length > 0) {
//                     resultsHtml += '<ul class="py-1 text-sm text-gray-200 divide-y divide-gray-600">';
//                     $.each(response.data, function (index, product) {
//                         resultsHtml += `<li><a href="/products/${product.id}" class="block px-4 py-2 hover:bg-gray-600">${product.name}</a></li>`;
//                     });
//                     resultsHtml += '</ul>';
//                     $('#search-results').html(resultsHtml);
//                     $('#search-results').removeClass('hidden');
//                 } else {
//                     $('#search-results').addClass('hidden');
//                 }
//             },
//             error: function (error) {
//                 console.error('Error:', error);
//                 $('#search-results').addClass('hidden');
//             }
//         });
//     });
// });
//
// $(document).on('click', '.increment-button', function () {
//     let input = $(this).siblings('.quantity-field');
//     let currentVal = parseInt(input.val());
//     let maxVal = parseInt(input.attr('max'));
//
//     if (currentVal < maxVal) {
//         input.val(currentVal + 1);
//     }
// });
//
// $(document).on('click', '.decrement-button', function () {
//     let input = $(this).siblings('.quantity-field');
//     let currentVal = parseInt(input.val());
//     if (currentVal > 1) {
//         input.val(currentVal - 1);
//     }
// });
//
// $(document).on('click', '.add-to-cart', function (e) {
//     e.preventDefault();
//     let button = $(this);
//     let productId = button.data('product-id');
//
//     let quantityInput = button.closest('.product-card').find('.quantity-field');
//     let quantity = quantityInput.val();
//
//     $.ajax({
//         url: window.indexProducts.routes.addToCart,
//         type: 'POST',
//         data: {
//             id: productId,
//             quantity: quantity,
//             _token: window.indexProducts.vars.csrf,
//         },
//         success: function (response) {
//             $('#total-products').text(response.data)
//             $('#cart-' + productId).toggleClass('animate-flip');
//         },
//         error: function (e) {
//             alert("Error adding product to cart: " + (e.responseJSON.message || "Unknown error"));
//         }
//     });
// });
