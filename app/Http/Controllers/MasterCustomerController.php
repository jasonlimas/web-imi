<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterCustomer;

class MasterCustomerController extends Controller
{
    public function index()
    {
        $customers = MasterCustomer::all();
        confirmDelete('Hapus', 'Hapus Customer?');
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cust_code' => 'required|max:10|unique:master_customers,cust_code',
            'cust_desc' => 'required|max:25',
            'cust_addr' => 'required|max:255',
        ]);

        MasterCustomer::create($request->all());
        return redirect()->route('customers.index')->withSuccessMessage('Item berhasil ditambahkan');
    }

    public function show(MasterCustomer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(MasterCustomer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, MasterCustomer $customer)
    {
        $request->validate([
            'cust_code' => 'required|max:10|unique:master_customers,cust_code,' . $customer->id,
            'cust_desc' => 'required|max:25',
            'cust_addr' => 'required|max:255',
        ]);

        $customer->update($request->all());
        return redirect()->route('customers.index')->with('success', 'Customer berhasil diupdate.');
    }

    public function destroy(MasterCustomer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus.');
    }
}
