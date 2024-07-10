@extends('layouts.test')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card col-6">
            <div class="card-header">
                <h5>
                    Master Item
                </h5>
            </div>
            <div class="card-body">
                <div>
                    <form method="POST" action="{{ route('items.store') }}" id="itemForm">
                        @csrf

                        <label for="item_code" class="form-label">Kode Item:</label>
                        <input type="text" id="item_code" name="item_code" required maxlength="4" class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                        <label for="item_desc" class="form-label">Nama Item:</label>
                        <input type="text" id="item_desc" name="item_desc" required maxlength="15" class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                        <label for="item_price" class="form-label">Harga:</label>
                        <input type="number" id="item_price" name="item_price" required maxlength="15" class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                        <button type="submit" class="btn btn-outline-primary" id="submitButton">Save</button>
                        <a href="{{ route('items.index') }}" class="btn btn-outline-primary mr-4" id="backButton">Back</a>
                    </form>
                    <div id="loading" style="display: none;">
                        <p>Loading...</p>
                    </div>
                </div>
            </div>
        </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('itemForm').addEventListener('submit', function(event) {
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
