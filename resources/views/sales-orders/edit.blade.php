@extends('layouts.test')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <form method="POST" action="{{ route('sales-orders.update', $sales_order->id) }}" id="soForm">
        @csrf
        @method('PUT')
        <div class="card col-6">
            <div class="card-header">
                <h5>Edit Sales Order {{ $sales_order->so_nbr }}</h5>
            </div>
            <div class="card-body">
                <label for="so_nbr" class="form-label">Kode Order:</label>
                <input type="text" id="so_nbr" name="so_nbr" value="{{ $sales_order->so_nbr }}" readonly class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                <label for="so_nbr" class="form-label">Kode Customer:</label>
                <select id="so_cust" name="so_cust" class="form-control" readonly>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $sales_order->so_cust == $customer->id ? 'selected' : '' }}>
                            {{ $customer->cust_code }}
                        </option>
                    @endforeach
                </select>

                <label for="so_nbr" class="form-label">Nama Customer:</label>
                <input type="text" id="customer_name" name="customer_name" value="{{ $sales_order->customer->cust_desc }}" readonly class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                <label for="so_nbr" class="form-label">Tanggal Order:</label>
                <input type="date" id="so_ord_date" name="so_ord_date" value="{{ $sales_order->so_ord_date }}" readonly class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                <label for="so_nbr" class="form-label">Jatuh Tempo:</label>
                <input type="date" id="so_due_date" name="so_due_date" value="{{ $sales_order->so_due_date }}" class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                <label for="so_nbr" class="form-label">Status:</label>
                <input type="text" id="so_status" name="so_status" value="{{ $sales_order->so_status }}" readonly class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                <button type="submit" class="btn btn-primary mt-2" {{ $sales_order->so_status !== 'active' ? 'disabled' : '' }} id="submitButton">Update Order</button>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales_order->details as $index => $detail)
                        <tr>
                            <td class="line-number">{{ $index + 1 }}</td>
                            <td>
                                <input type="text" class="form-control" value="{{ $detail->item->item_code }}" readonly>
                                <input type="hidden" name="line_items[{{ $index }}][item_id]" value="{{ $detail->item_id }}">
                            </td>
                            <td><input type="text" class="form-control" value="{{ $detail->item->item_desc }}" readonly></td>
                            <td><input type="number" name="line_items[{{ $index }}][price]" class="form-control" value="{{ $detail->price }}"></td>
                            <td><input type="number" name="line_items[{{ $index }}][qty]" class="form-control qty-input" data-index="{{ $index }}" value="{{ $detail->qty }}"></td>
                            <td><input type="number" class="form-control total-input" value="{{ $detail->total }}" readonly></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const priceInputs = document.querySelectorAll('input[name^="line_items"][name$="[price]"]');
        const qtyInputs = document.querySelectorAll('.qty-input');
        const totalInputs = document.querySelectorAll('.total-input');

        function updateTotal(index) {
            const qty = parseFloat(qtyInputs[index].value) || 0;
            const price = parseFloat(priceInputs[index].value) || 0;
            totalInputs[index].value = (qty * price).toFixed(2);
        }

        qtyInputs.forEach((input, index) => {
            input.addEventListener('input', () => updateTotal(index));
        });

        priceInputs.forEach((input, index) => {
            input.addEventListener('input', () => updateTotal(index));
        });
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
