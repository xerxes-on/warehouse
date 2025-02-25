// $(document).ready(function () {
//     const $deleteBtn = $('#delete-selected-btn');
//     const $saveBtn = $('#save-changes-btn');
//     function finishEditing($row) {
//         const $label = $row.find('.amount-label');
//         const $input = $row.find('.amount-input');
//         $label.removeClass('hidden');
//         $input.addClass('hidden');
//     }
//
//     function updateSubtotal($row) {
//         const quantity = parseInt($row.find('.amount-input').val(), 10);
//         const price = parseFloat($row.find('td:nth-child(3)').text().replace('$', ''));
//         const newSubtotal = quantity * price;
//         $row.find('td:last-child').text('$' + newSubtotal.toFixed(2));
//     }
//
//     function refreshDeleteButtonVisibility() {
//         const anyChecked = $('.delete-checkbox:checked').length > 0;
//         if (anyChecked) {
//             $deleteBtn.removeClass('hidden').prop('disabled', false);
//         } else {
//             $deleteBtn.addClass('hidden').prop('disabled', true);
//         }
//     }
//
//     function updateOrderTotals() {
//         $.ajax({
//             url: window.showOrder.routes.refreshItems,
//             type: 'GET',
//             success: function (response) {
//                 if (response.success) {
//                     const d = response.data;
//                     $('#totals-table #subtotal-row td:last-child').text('$' + d.subtotal);
//                     $('#totals-table #subtotal-row').toggleClass('line-through', d.hasDiscount);
//
//                     if (d.hasDiscount) {
//                         $('#discount-row').removeClass('hidden');
//                         $('#discount-row td:last-child').text('$' + d.totalBeforeFees);
//                     } else {
//                         $('#discount-row').addClass('hidden');
//                     }
//                     $('#totals-table #tax-row td:last-child').text('$' + d.tax);
//                     $('#totals-table #store-fee-row td:last-child').text('$' + d.storeFee);
//                     $('#totals-table #duties-row td:last-child').text('$' + d.dutiesFee);
//                     $('#totals-table #total-row td:last-child').text('$' + d.total.toFixed(2));
//                 }
//             },
//             error: function () {
//                 alert('Error refreshing order details');
//             }
//         });
//     }
//
//     function refreshSaveButtonVisibility() {
//         let changed = false;
//         $('#order-items-table tbody tr').each(function () {
//             const $row = $(this);
//             const originalQty = parseInt($row.data('original-qty'), 10);
//
//             const currentQtyInput = $row.find('.amount-input');
//             const currentQty = currentQtyInput.val();
//
//             if (!isNaN(currentQty) && parseInt(currentQty, 10) !== originalQty) {
//                 changed = true;
//                 return false; // break
//             }
//         });
//
//         if (changed) {
//             $saveBtn.removeClass('hidden').prop('disabled', false);
//         } else {
//             $saveBtn.addClass('hidden').prop('disabled', true);
//         }
//     }
//
//     $(document).on('change', '.delete-checkbox', function () {
//         refreshDeleteButtonVisibility();
//     });
//     $(document).on('click', '.edit-quantity', function (e) {
//         e.preventDefault();
//         const $row = $(this).closest('tr');
//         const $label = $row.find('.amount-label');
//         const $input = $row.find('.amount-input');
//
//         if ($input.hasClass('hidden')) {
//             $label.addClass('hidden');
//             $input.removeClass('hidden').focus();
//         } else {
//             finishEditing($row);
//         }
//     });
//     $(document).on('input', '.amount-input', function () {
//         const $input = $(this);
//         const $row = $input.closest('tr');
//         let val = parseInt($input.val(), 10);
//
//         if ($input.val() !== '' && (val < 1 || isNaN(val))) {
//             val = 1;
//             $input.val(val);
//         }
//         $row.find('.amount-label').text($input.val());
//         updateSubtotal($row);
//         refreshSaveButtonVisibility();
//     });
//     $(document).on('click', function (e) {
//         if (!$(e.target).closest('.amount-input, .edit-quantity').length) {
//             $('.amount-input:not(.hidden)').each(function () {
//                 const $input = $(this);
//                 if ($input.val() === '' || isNaN(parseInt($input.val(), 10))) {
//                     $input.val(1);
//                 }
//                 updateSubtotal($input.closest('tr'));
//                 finishEditing($input.closest('tr'));
//             });
//         }
//     });
//
//     $deleteBtn.on('click', function () {
//         const checkedItems = $('.delete-checkbox:checked')
//             .map(function () {
//                 return $(this).val();
//             })
//             .get();
//
//         if (!checkedItems.length) return;
//
//         $.ajax({
//             url: window.showOrder.routes.removeItems,
//             type: 'POST',
//             data: {
//                 _token: window.showOrder.vars.csrf,
//                 item_ids: checkedItems
//             },
//             success: function (response) {
//                 if (response.success) {
//                     $('.delete-checkbox:checked').closest('tr').remove();
//                     refreshDeleteButtonVisibility();
//                     updateOrderTotals();
//                     refreshSaveButtonVisibility();
//                 } else {
//                     alert('Error deleting items.');
//                 }
//             },
//             error: function () {
//                 alert('Error deleting items.');
//             }
//         });
//     });
//     $saveBtn.on('click', function () {
//         let changes = [];
//         $('#order-items-table tbody tr').each(function () {
//             const $row = $(this);
//             const itemId = $row.data('product-id');
//             const originalQty = parseInt($row.data('original-qty'), 10);
//             const currentQty = parseInt($row.find('.amount-input').val(), 10);
//
//             if (currentQty !== originalQty) {
//                 changes.push({
//                     id: itemId,
//                     amount: currentQty
//                 });
//             }
//         });
//         if (!changes.length) return;
//
//         $.ajax({
//             url: window.showOrder.routes.updateItems,
//             type: 'POST',
//             data: {
//                 _token: window.showOrder.vars.csrf,
//                 products: changes
//             },
//             success: function (response) {
//                 $saveBtn.prop('disabled', true);
//                 if (response.success) {
//                     changes.forEach(change => {
//                         const $row = $('#order-items-table tbody tr[data-item-id="' + change.id + '"]');
//                         $row.data('original-qty', change.amount);
//                     });
//                     updateOrderTotals();
//                     alert('Order updated')
//                 } else {
//                     alert('Error saving changes.');
//                 }
//             },
//             error: function () {
//                 alert('Error saving changes.');
//             }
//         });
//     });
// });
