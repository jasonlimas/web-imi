<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MasterItemResource;
use App\Models\MasterItem;
use Illuminate\Http\Request;

class ItemAPIController extends Controller
{
    public function getItemApi (Request $request)
    {
        //dd($request->all());
        $data = MasterItem::query();

        // Item Code
        if ($request->code) {
            $data->where('item_code', '=', $request->code);
        }

        // Item Desc
        if ($request->desc) {
            $data->where('item_desc', 'like', '%' . $request->desc . '%');
        }

        // Item Price
        $priceFrom = $request->input('from', 0); // Set default value to 0 if not provided
        $priceTo = $request->input('to', PHP_INT_MAX); // Set default value to max if not provided

        if ($priceFrom !== null && $priceTo !== null) {
            $data->whereBetween('item_price', [$priceFrom, $priceTo]);
        }

        $data = $data->get();

        return MasterItemResource::collection($data);
    }
}
