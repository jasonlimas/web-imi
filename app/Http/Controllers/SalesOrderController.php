<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\MasterCustomer;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SalesOrderController extends Controller
{
    public function index()
    {
        $orders = SalesOrder::with('customer')->get();
        confirmDelete('Hapus', 'Hapus Sales Order?');
        return view('sales-orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = MasterCustomer::all();
        $items = MasterItem::all();
        $nextOrderNumber = $this->generateNextOrderNumber();

        return view('sales-orders.create', compact('customers', 'items', 'nextOrderNumber'));
    }

    private function generateNextOrderNumber()
    {
        $currentYear = now()->year;
        $prefix = 'PR' . ($currentYear % 100);

        // Find the highest existing order number for the current year, including soft-deleted records
        $lastOrder = SalesOrder::withTrashed()
            ->where('so_nbr', 'like', "$prefix%")
            ->orderBy('so_nbr', 'desc')
            ->first();

        if ($lastOrder) {
            // Extract the numeric part of the order number and increment it
            $lastOrderNumber = intval(substr($lastOrder->so_nbr, strlen($prefix)));
            $nextOrderNumber = $prefix . sprintf('%04d', $lastOrderNumber + 1);
        } else {
            // Start from 0001 if no orders exist
            $nextOrderNumber = $prefix . '0001';
        }

        return $nextOrderNumber;
    }

    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'so_nbr' => 'required|unique:sales_orders,so_nbr',
            'so_cust' => 'required|exists:master_customers,id',
            'so_ord_date' => 'required|date',
            'so_due_date' => 'required|date',
            'line_items' => 'required|array',
            'line_items.*.item_id' => 'required|exists:master_items,id',
            'line_items.*.qty' => 'required|integer|min:1',
            'line_items.*.price' => 'required|numeric|min:0',
        ]);

        // Proceed with creating the sales order
        try {
            $order = SalesOrder::create([
                'so_nbr' => $request->so_nbr,
                'so_cust' => $request->so_cust,
                'so_ord_date' => $request->so_ord_date,
                'so_due_date' => $request->so_due_date,
                'so_status' => 'active',
                'created_by' => 1, // Ensure a logged-in user ID is being used here
            ]);

            Log::info('SalesOrder created: ' . $order->id);

            foreach ($request->line_items as $index => $lineItem) {
                Log::info('Creating SalesOrderDetail for line ' . ($index + 1));
                $order->details()->create([
                    'line' => $index + 1,
                    'item_id' => $lineItem['item_id'],
                    'qty' => $lineItem['qty'],
                    'price' => $lineItem['price'],
                    'total' => $lineItem['qty'] * $lineItem['price'],
                ]);
            }

            Log::info('SalesOrderDetails created');

            return redirect()->route('sales-orders.index')->withSuccessMessage('Item berhasil ditambahkan');
        } catch (\Exception $e) {
            dd($e);
            Log::error('Error creating SalesOrder: ' . $e->getMessage());
            return back()->with('error', 'Failed to create sales order. Please try again.');
        }
    }

    public function edit(SalesOrder $sales_order)
    {
        $customers = MasterCustomer::all();
        $items = MasterItem::all();
        $sales_order->load('details');

        return view('sales-orders.edit', compact('sales_order', 'customers', 'items'));
    }

    public function update(Request $request, SalesOrder $sales_order)
    {
        //dd($request->all());

        $validatedData = $request->validate([
            'so_nbr' => 'required|unique:sales_orders,so_nbr,' . $sales_order->id,
            'so_cust' => 'required|exists:master_customers,id',
            'so_ord_date' => 'required|date',
            'so_due_date' => 'required|date',
            'line_items' => 'required|array',
            'line_items.*.item_id' => 'required|exists:master_items,id',
            'line_items.*.qty' => 'required|integer|min:1',
            'line_items.*.price' => 'required|numeric|min:0',
        ]);

        $sales_order->update([
            'so_nbr' => $request->so_nbr,
            'so_cust' => $request->so_cust,
            'so_ord_date' => $request->so_ord_date,
            'so_due_date' => $request->so_due_date,
            'so_status' => $request->so_status,
        ]);

        $sales_order->details()->delete();
        foreach ($request->line_items as $index => $lineItem) {
            $sales_order->details()->create([
                'line' => $index + 1,
                'item_id' => $lineItem['item_id'],
                'qty' => $lineItem['qty'],
                'price' => $lineItem['price'],
                'total' => $lineItem['qty'] * $lineItem['price'],
            ]);
        }

        return redirect()->route('sales-orders.index')->with('success', 'Sales order updated successfully.');
    }

    public function show(SalesOrder $sales_order)
    {
        $sales_order->load('details');
        return view('sales-orders.show', compact('sales_order'));
    }

    public function destroy(SalesOrder $sales_order)
    {
        $sales_order->delete();
        return redirect()->route('sales-orders.index')->with('success', 'Sales order deleted successfully.');
    }
}
