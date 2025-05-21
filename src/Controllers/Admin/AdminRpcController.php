<?php

namespace Azuriom\Plugin\Centralcorp\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Centralcorp\Models\Option;
use Illuminate\Http\Request;

class AdminRpcController extends Controller
{
    public function show()
    {
        $options = Option::pluck('value', 'name');
        return view('centralcorp::admin.rpc', compact('options'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'rpc_activation' => 'boolean',
            'rpc_id' => 'nullable|string|max:255',
            'rpc_details' => 'nullable|string|max:255',
            'rpc_state' => 'nullable|string|max:255',
            'rpc_large_image' => 'nullable|string|max:255',
            'rpc_large_text' => 'nullable|string|max:255',
            'rpc_small_image' => 'nullable|string|max:255',
            'rpc_small_text' => 'nullable|string|max:255',
            'rpc_button1' => 'nullable|string|max:255',
            'rpc_button1_url' => 'nullable|url|max:255',
            'rpc_button2' => 'nullable|string|max:255',
            'rpc_button2_url' => 'nullable|url|max:255',
        ]);

        $data = $request->only([
            'rpc_activation',
            'rpc_id',
            'rpc_details',
            'rpc_state',
            'rpc_large_image',
            'rpc_large_text',
            'rpc_small_image',
            'rpc_small_text',
            'rpc_button1',
            'rpc_button1_url',
            'rpc_button2',
            'rpc_button2_url',
        ]);

        foreach ($data as $name => $value) {
            Option::updateOrCreate(['name' => $name], ['value' => $value]);
        }

        return redirect()->route('centralcorp.admin.rpc')->with('success', trans('centralcorp::messages.success_rpc_update'));
    }
}
