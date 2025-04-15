<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('values')->paginate(10);
        return view('admin.attributes.index', compact('attributes'));
    }
    
    public function create()
    {
        return view('admin.attributes.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:attributes',
            'type' => 'required|in:select,radio,checkbox,text',
            'is_filterable' => 'nullable|boolean',
            'is_required' => 'nullable|boolean',
            'values' => 'required_unless:type,text|array|min:1',
            'values.*' => 'required|string|max:255',
        ]);
        
        $attribute = new Attribute([
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'is_filterable' => $request->has('is_filterable'),
            'is_required' => $request->has('is_required'),
        ]);
        
        $attribute->save();
        
        // Save attribute values
        if ($request->type !== 'text' && is_array($request->values)) {
            foreach ($request->values as $value) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                ]);
            }
        }
        
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute created successfully');
    }
    
    public function edit(Attribute $attribute)
    {
        $attribute->load('values');
        return view('admin.attributes.edit', compact('attribute'));
    }
    
    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:attributes,code,' . $attribute->id,
            'type' => 'required|in:select,radio,checkbox,text',
            'is_filterable' => 'nullable|boolean',
            'is_required' => 'nullable|boolean',
            'values' => 'required_unless:type,text|array|min:1',
            'values.*' => 'required|string|max:255',
        ]);
        
        $attribute->name = $request->name;
        $attribute->code = $request->code;
        $attribute->type = $request->type;
        $attribute->is_filterable = $request->has('is_filterable');
        $attribute->is_required = $request->has('is_required');
        
        $attribute->save();
        
        // Update attribute values
        if ($request->type !== 'text') {
            // Delete existing values
            $attribute->values()->delete();
            
            // Add new values
            foreach ($request->values as $value) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                ]);
            }
        }
        
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute updated successfully');
    }
    
    public function destroy(Attribute $attribute)
    {
        // Check if attribute is used in products
        if ($attribute->products()->count() > 0) {
            return redirect()->route('admin.attributes.index')->with('error', 'Cannot delete attribute that is used in products');
        }
        
        // Delete attribute values
        $attribute->values()->delete();
        
        // Delete attribute
        $attribute->delete();
        
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute deleted successfully');
    }
}