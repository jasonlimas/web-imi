@extends('layouts.test')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h1 class="mx-4">Customers</h1>
        <a href="{{ route('customers.create') }}" class="btn btn-outline-primary mb-4 mx-4">Add New Customer</a>
        <div class="row mx-4">
            @foreach ($customers as $customer)

            <div class="row col-4">
                <div class="card h-100">
                    <img class="card-img-top mt-2" src="{{asset('assets/img/elements/2.jpg')}}" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">{{ $customer->cust_code }}</h5>
                        <p class="card-text">
                        {{ $customer->cust_desc }}
                        </p>
                        <div class="d-flex">
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-primary mr-4">Edit</a>
                            <a href="{{ route('customers.destroy', $customer) }}" class="btn btn-danger mr-4" data-confirm-delete="true">Delete</a>
                        </div>

                    </div>
                </div>
            </div>

            @endforeach
        </div>
    </div>
@endsection
