@extends('layouts.test')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h1>Sales Orders Master</h1>
    <a href="{{ route('sales-orders.create') }}" class="btn btn-primary mb-2">Create New Order</a>

    <div class="card">
        <h5 class="card-header">Sales Order</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
            <thead>
                <tr class="text-nowrap">
                    <th>ID</th>
                    <th>Order Number</th>
                    <th>Customer</th>
                    <th>Order Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->so_nbr }}</td>
                    <td>{{ $order->customer->cust_desc ?? 'No Customer' }}</td>
                    <td>{{ $order->so_ord_date }}</td>
                    <td>{{ $order->so_due_date }}</td>
                    <td>{{ $order->so_status }}</td>
                    <td>{{ $order->details->sum('total') }}</td>
                    <td>
                        <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            @if ($order->so_status === 'active')
                                <a class="dropdown-item" href="{{ route('sales-orders.edit', $order->id) }}"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                            @endif
                            <a class="dropdown-item" href="{{ route('sales-orders.show', $order->id) }}"><i class="bx bx-search me-2"></i> Details</a>
                            <a class="dropdown-item" href="{{ route('sales-orders.destroy', $order->id) }}" data-confirm-delete="true"><i class="bx bx-trash me-2"></i> Delete</a>
                        </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        </div>
    </div>
@endsection
