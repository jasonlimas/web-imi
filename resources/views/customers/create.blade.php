@extends('layouts.test')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card col-6">
            <div class="card-header">
                <h5>
                    Add Customer
                </h5>
            </div>
            <div class="card-body">
                <div>
                    <form method="POST" action="{{ route('customers.store') }}" id="custForm">
                        @csrf

                        <label for="item_code" class="form-label">Kode Customer:</label>
                        <input type="text" id="cust_code" name="cust_code" required maxlength="10" class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                        <label for="item_desc" class="form-label">Nama:</label>
                        <input type="text" id="cust_desc" name="cust_desc" required maxlength="255" class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                        <label for="item_price" class="form-label">Alamat:</label>
                        <input type="text" id="cust_addr" name="cust_addr" required maxlength="255" class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                        <button type="submit" class="btn btn-outline-primary" id="submitButton">Save</button>
                        <a href="{{ route('customers.index') }}" class="btn btn-outline-primary mr-4" id="backButton">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('custForm').addEventListener('submit', function(event) {
            document.getElementById('submitButton').disabled = true;
            var backButton = document.getElementById('backButton');
            backButton.classList.add('disabled');
            backButton.removeAttribute('href');
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
