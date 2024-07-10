@extends('layouts.test')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <form method="POST" action="{{ route('sales-orders.store') }}" id="soForm">
        @csrf
        <!-- Card for Form Inputs -->
        <div class="card col-6">
            <div class="card-header">
                <h5>Create New Sales Order</h5>
            </div>
            <div class="card-body">
                <!-- Form elements like text inputs and selects -->
                <label for="so_nbr" class="form-label">Kode Order:</label>
                <input type="text" id="so_nbr" name="so_nbr" value="{{ $nextOrderNumber }}" readonly class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                <label for="so_cust" class="form-label">Kode Customer:</label>
                <select id="so_cust" name="so_cust" class="form-select mb-2" onchange="updateCustomerInfo()">
                    <option value="">Select Customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" data-name="{{ $customer->cust_desc }}">{{ $customer->cust_code }}</option>
                    @endforeach
                </select>

                <label for="customer_name" class="form-label">Nama Customer:</label>
                <input type="text" id="customer_name" name="customer_name" class="form-control mb-2" readonly>

                <label for="so_ord_date" class="form-label">Tanggal Order:</label>
                <input class="form-control mb-2" type="date" id="so_ord_date" name="so_ord_date" value="{{ date('Y-m-d') }}" required>

                <label for="so_due_date" class="form-label">Jatuh Tempo:</label>
                <input class="form-control mb-2" type="date" id="so_due_date" name="so_due_date" value="{{ date('Y-m-d', strtotime('+1 week')) }}" required>

                <button type="submit" class="btn btn-primary mt-2" id="submitButton">Save Order</button>
                <button type="button" onclick="window.location='{{ route('sales-orders.index') }}'" class="btn btn-secondary mt-2" id="backButton">Cancel</button>
            </div>
        </div>
        <!-- Separate Table Outside the Card -->
        <div class="mt-4 card">
            <h5 class="card-header">Line Items</h5>
            <div class="table-responsive text-nowrap">
                <table class="table" id="line_items_table">
                    <thead>
                        <tr>
                            <th>Line</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="line_items_container">
                        {{-- JavaScript will dynamically add rows here --}}
                    </tbody>
                </table>
                <button type="button" class="btn btn-secondary mt-2 mb-4 mx-4" onclick="window.addNewItemLine()">+</button>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.addNewItemLine = function() {
            const lineItemsContainer = document.getElementById('line_items_container');
            const newItemIndex = lineItemsContainer.children.length;
            const newItemLine = document.createElement('tr');
            newItemLine.innerHTML = `
                <td class="line-number">${newItemIndex + 1}</td>
                <td>
                    <select id="line_items_${newItemIndex}_item_id" name="line_items[${newItemIndex}][item_id]" class="form-select" onchange="updateItemInfo(${newItemIndex})">
                        <option value="">Select Item</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" data-name="{{ $item->item_desc }}" data-price="{{ $item->item_price }}">{{ $item->item_code }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" id="line_items_${newItemIndex}_item_name" name="line_items[${newItemIndex}][item_name]" class="form-control" disabled>
                </td>
                <td>
                    <input type="number" id="line_items_${newItemIndex}_price" name="line_items[${newItemIndex}][price]" class="form-control" onchange="updateTotal(${newItemIndex})" required>
                </td>
                <td>
                    <input type="number" id="line_items_${newItemIndex}_qty" name="line_items[${newItemIndex}][qty]" class="form-control" onchange="updateTotal(${newItemIndex})" required>
                </td>
                <td>
                    <input type="number" id="line_items_${newItemIndex}_total" name="line_items[${newItemIndex}][total]" class="form-control" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger" onclick="removeItemLine(this)">X</button>
                </td>
            `;
            lineItemsContainer.appendChild(newItemLine);
        };

        window.updateTotal = function(index) {
            const qty = document.querySelector(`input[name='line_items[${index}][qty]']`).value;
            const price = document.querySelector(`input[name='line_items[${index}][price]']`).value;
            const totalInput = document.querySelector(`input[name='line_items[${index}][total]']`);
            totalInput.value = (qty * price).toFixed(2);
        };

        window.removeItemLine = function(button) {
            button.closest('tr').remove();
            updateLineNumbers();
        };

        window.updateLineNumbers = function() {
            const rows = document.querySelectorAll('#line_items_container tr');
            rows.forEach((row, index) => {
                row.querySelector('.line-number').innerText = index + 1;
            });
        };

        window.updateCustomerInfo = function() {
            const selectedCustomer = document.querySelector('#so_cust').selectedOptions[0];
            const customerName = selectedCustomer.getAttribute('data-name');
            document.getElementById('customer_name').value = customerName;
        };

        window.updateItemInfo = function(index) {
            const selectedItem = document.querySelector(`#line_items_${index}_item_id`).selectedOptions[0];
            const itemName = selectedItem.getAttribute('data-name');
            const itemPrice = selectedItem.getAttribute('data-price');

            document.getElementById(`line_items_${index}_item_name`).value = itemName;
            document.getElementById(`line_items_${index}_price`).value = itemPrice;
            updateTotal(index);
        };
    });

    document.getElementById('soForm').addEventListener('submit', function(event) {
            document.getElementById('submitButton').disabled = true;
            var backButton = document.getElementById('backButton');
            backButton.classList.add('disabled');
            backButton.removeAttribute('button');
            document.getElementById('loading').style.display = 'block';
        });
</script>
@endpush

@push('styles')
    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.6;
            text-decoration: none;
        }
    </style>
@endpush
