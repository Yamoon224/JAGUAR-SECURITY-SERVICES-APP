<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.bills');
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
    public function store(StoreBillRequest $request)
    {
        $data = $request->except('_token', 'net');
        $data['amount'] = $request->net - $data['discount'];
        Bill::create($data);
        
        return response()->json([
            'status'=>'success', 
            'message'=>'Facturation effectuée avec succès', 
            'redirect'=>route('prints.bill', [$data['customer_id'], date('Y'), $data['month_id'], 1])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bill $bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBillRequest $request, int $id)
    {
        $data = $request->except('_token', 'net');
        $data['arrears'] = empty($data['arrears']) ? 0 : $data['arrears'];
        $data['discount'] = empty($data['discount']) ? 0 : $data['discount'];
            
        if($id == 0) {
            $data['amount'] = $request->net + $data['arrears'] - $data['discount'];
            Bill::create($data);
            return response()->json([
                'status'=>'success', 
                'message'=>'Facturation effectuée avec succès',
                'redirect'=>route('prints.bill', [$data['customer_id'], date('Y'), $data['month_id'], 1])
            ]);
        } else {
            $data['amount'] = $request->net - $data['discount'];  
            $data['amount'] += $data['arrears'];  
        
            $bill = Bill::find($id);
            $bill->update($data);
            return response()->json([
                'status'=>'success', 
                'message'=>'Facture modifiée avec succès'
            ]);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        //
    }

    public function getByMonthly(Request $request)
    {
        $customers = Customer::with(['affectations'=>fn ($stmt) => $stmt->whereDate('end', '>=', date($request->year.'-'.$request->month_id.'-d'))->whereDate('begin', '<=', date($request->year.'-'.$request->month_id.'-d'))])
            ->where('deleted', 0)
            ->get()
            ->load(['bills'=>fn ($query) => $query->where(['month_id'=>request()->month_id, 'year_id'=>$request->year])]);
        $month = $request->monthname;
        $month_id = $request->month_id;
        return view('admin.bill', compact('customers', 'month', 'month_id'));
    }
}
