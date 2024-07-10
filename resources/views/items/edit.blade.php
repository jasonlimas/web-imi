@extends('layouts.test')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card col-6">
            <div class="card-header">
                <h5>
                    Edit Item {{ $item->item_code }}
                </h5>
            </div>
            <div class="card-body">
                <div>
                    <form method="POST" action="{{ route('items.update', $item->id) }}" id="itemForm">
                        @csrf
                        @method('PUT')

                        <label for="item_code" class="form-label">Item Code:</label>
                        <input type="text" id="item_code" name="item_code" value="{{ $item->item_code }}" required maxlength="4" class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                        <label for="item_desc" class="form-label">Description:</label>
                        <input type="text" id="item_desc" name="item_desc" value="{{ $item->item_desc }}" required maxlength="255" class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                        <label for="item_price" class="form-label">Price:</label>
                        <input type="number" id="item_price" name="item_price" value="{{ $item->item_price }}" required step="0.01" class="form-control mb-2" aria-describedby="defaultFormControlHelp">

                        <button type="submit" class="btn btn-outline-primary" id="submitButton">Update</button>
                        <a href="{{ route('items.index') }}" class="btn btn-outline-primary mr-4" id="backButton">Back</a>
                    </form>
                </div>
            </div>
        </div>
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
