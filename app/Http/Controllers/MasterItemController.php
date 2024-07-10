<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterItem;

class MasterItemController extends Controller
{
    public function index()
    {
        $items = MasterItem::all();
        confirmDelete('Hapus', 'Hapus Item?');
        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_code' => 'required|max:4|unique:master_items,item_code',
            'item_desc' => 'required|max:255',
            'item_price' => 'required|numeric|min:0',
        ]);

        $newItem = new MasterItem([
            'item_code' => $request->item_code,
            'item_desc' => $request->item_desc,
            'item_price' => $request->item_price,
        ]);
        $newItem->save();

        return redirect()->route('items.index')->withSuccessMessage('Item berhasil ditambahkan');
    }


    public function edit(MasterItem $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, MasterItem $item)
    {
        $request->validate([
            'item_code' => 'required|max:4|unique:master_items,item_code,' . $item->id,
            'item_desc' => 'required|max:255',
            'item_price' => 'required|numeric|min:0',
        ]);

        $item->update($request->all());
        return redirect()->route('items.index')->with('success', 'Item berhasil diupdate.');
    }

    public function destroy(MasterItem $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus.');
    }
}
