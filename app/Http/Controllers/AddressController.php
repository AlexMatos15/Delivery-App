<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of user's addresses.
     */
    public function index()
    {
        $addresses = auth()->user()->addresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new address.
     */
    public function create()
    {
        return view('addresses.create');
    }

    /**
     * Store a newly created address.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|size:2',
            'zip_code' => 'required|string|max:9',
            'reference' => 'nullable|string|max:500',
            'is_default' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_default'] = $request->has('is_default');

        // If setting as default, unset other default addresses
        if ($validated['is_default']) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        // If this is the first address, make it default
        if (auth()->user()->addresses()->count() === 0) {
            $validated['is_default'] = true;
        }

        Address::create($validated);

        return redirect()->route('addresses.index')
            ->with('status', __('Address added successfully!'));
    }

    /**
     * Show the form for editing the specified address.
     */
    public function edit(Address $address)
    {
        // Verify address belongs to user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        return view('addresses.edit', compact('address'));
    }

    /**
     * Update the specified address.
     */
    public function update(Request $request, Address $address)
    {
        // Verify address belongs to user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|size:2',
            'zip_code' => 'required|string|max:9',
            'reference' => 'nullable|string|max:500',
            'is_default' => 'boolean',
        ]);

        $validated['is_default'] = $request->has('is_default');

        // If setting as default, unset other default addresses
        if ($validated['is_default']) {
            auth()->user()->addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        // Prevent unsetting default if it's the only address
        if (!$validated['is_default'] && auth()->user()->addresses()->count() === 1) {
            $validated['is_default'] = true;
        }

        $address->update($validated);

        return redirect()->route('addresses.index')
            ->with('status', __('Address updated successfully!'));
    }

    /**
     * Remove the specified address.
     */
    public function destroy(Address $address)
    {
        // Verify address belongs to user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        // Prevent deleting the last address if user has orders
        if (auth()->user()->addresses()->count() === 1 && auth()->user()->orders()->count() > 0) {
            return back()->with('error', __('Cannot delete your only address. Add another address first.'));
        }

        $wasDefault = $address->is_default;
        $address->delete();

        // If deleted address was default, make another one default
        if ($wasDefault) {
            $firstAddress = auth()->user()->addresses()->first();
            if ($firstAddress) {
                $firstAddress->update(['is_default' => true]);
            }
        }

        return redirect()->route('addresses.index')
            ->with('status', __('Address deleted successfully!'));
    }

    /**
     * Set an address as default.
     */
    public function setDefault(Address $address)
    {
        // Verify address belongs to user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        // Unset all other defaults
        auth()->user()->addresses()->update(['is_default' => false]);

        // Set this one as default
        $address->update(['is_default' => true]);

        return back()->with('status', __('Default address updated!'));
    }
}
