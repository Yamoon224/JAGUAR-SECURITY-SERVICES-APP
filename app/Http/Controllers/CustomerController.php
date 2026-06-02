<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::withCount(['affectations' => fn ($stmt) => $stmt->with(['employee' => fn ($employee) => $employee->where('deleted', 0)])->whereDate('end', '>=', date('Y-m-d'))->whereDate('begin', '<=', date('Y-m-d'))])
        ->where('deleted', 0)
        ->get();
        return view('admin.customers', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $data = $request->except('_token');
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('public/customers');
            $data['logo'] = substr($data['logo'], 7);
        }
        Customer::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $customer = Customer::find($id);
        return view('admin.customer', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, int $id)
    {
        $customer = Customer::find($id);
        $data = $request->except('_token');
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('public/customers');
            $data['logo'] = substr($data['logo'], 7);
            File::delete("storage/".$customer->file_path);
        }
        $customer->update($data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $customer)
    {
        $customer = Customer::find($customer);
        $customer->update(['deleted'=>1]);
        return back();
    }
}
