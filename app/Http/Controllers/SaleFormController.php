<?php

namespace App\Http\Controllers;

use App\Models\SaleForm;
use App\Models\Booking;
use App\Http\Requests\StoreSaleFormRequest;
use App\Http\Requests\UpdateSaleFormRequest;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SaleFormController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:sale-forms.view', only: ['index']),
            new Middleware('permission:sale-forms.edit', only: ['edit', 'update']),
            new Middleware('permission:sale-forms.add', only: ['add', 'store']),
            new Middleware('permission:sale-forms.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $request = request();
        $query = SaleForm::with('agent', 'secondaryAgent', 'supplier', 'enquiry', 'user', 'customerDetails.member');
        
        // Date Range Filtering
        if ($request->has('dateFrom') && $request->has('dateTo')) {
            $startDate = Carbon::parse($request->dateFrom)->startOfDay();
            $endDate = Carbon::parse($request->dateTo)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        // Filtering
        if ($request->has('filters')) {
            $filters = json_decode($request->filters, true);
            foreach ($filters as $filter => $value) {
                if($filter == 'agent.username') {
                    $query->whereHas('agent', function($q) use ($value) {
                        if (!empty($value['value'])) {
                            $q->where('username', 'like', '%' . $value['value'] . '%');
                        }
                    });
                } elseif ($filter == 'supplier.username') {
                    $query->whereHas('supplier', function($q) use ($value) {
                        if (!empty($value['value'])) {
                            $q->where('username', 'like', '%' . $value['value'] . '%');
                        }
                    });
                } elseif ($filter == 'secondaryAgent.username') {
                    $query->whereHas('secondaryAgent', function($q) use ($value) {
                        if (!empty($value['value'])) {
                            $q->where('username', 'like', '%' . $value['value'] . '%');
                        }
                    });
                } else{
                    // For other string fields
                    if (!empty($value['value'])) {
                        $query->where($filter, 'like', '%' . $value['value'] . '%');
                    }
                }
        }

        // Sorting
        if ($request->has('sortField') && $request->has('sortOrder')) {
            if ($request['sortOrder'] == 1) {
                $order = 'asc';
            } else {
                $order = 'desc';
            }
            $query->orderBy($request['sortField'], $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $resources = $query->paginate($request->rows);

        return response()->json($resources);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleFormRequest $request)
    {
        //
        SaleForm::create([
                'entry_date' => $request->entry_date,
                'pnr' => $request->pnr,
                'sale_type' => $request->sale_type,
                'invoice_date' => $request->invoice_date,
                'inquiry_no' => $request->inquiry_no,
                'location' => $request->location,
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'no_of_pax' => $request->no_of_pax,
                'ticket_no' => $request->ticket_no,
                'gross_supplier' => $request->gross_supplier,
                'commission' => $request->commission,
                'net_cost' => $request->net_cost,
                'markup' => $request->markup,
                'remarks_booking' => $request->remarks_booking,
                'customer_mc' => $request->customer_mc,
                'customer_vi' => $request->customer_vi,
                'remarks_payment' => $request->remarks_payment,
                'other_payment' => $request->other_payment,
                'amex_payment' => $request->amex_payment,
                'gift_card' => $request->gift_card,
                'past_credit' => $request->past_credit,
                'etransfer' => $request->etransfer,
                'cheque_draft' => $request->cheque_draft,
                'cash' => $request->cash,
                'debit' => $request->debit,
                'other_supplier' => $request->other_supplier,
                'company_master' => $request->company_master,
                'company_visa' => $request->company_visa,
                'gst' => $request->gst,
                'company_emax' => $request->company_emax,
                'customer_card' => $request->customer_card,
                'gds' => $request->gds,
                'web_phone' => $request->web_phone,
                'remarks_supplier' => $request->remarks_supplier,
                'return_date' => $request->return_date,
                'departure_date' => $request->departure_date,
                'other_remarks' => $request->other_remarks,
                'secondary_agent' => $request->secondary_agent,
                'secondary_agent_share' => $request->secondary_agent_share,
                'agent' => $request->agent,
                'supplier' => $request->supplier,
                'userid' => auth()->id()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($saleForm)
    {
        //
        $query = Booking::with('bookingFlight.flightFrom','bookingFlight.flightTo','member','user','agent')
            ->where('id', $saleForm)
            ->first();
        if (!$query) {
            return response()->json(['message' => 'SaleForm not found'], 404);
        }
        return response()->json($query);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleFormRequest $request, SaleForm $saleForm)
    {
        //
        $query = SaleForm::find($request->id);
        $query->update([
            'entry_date' => $request->entry_date,
            'pnr' => $request->pnr,
            'sale_type' => $request->sale_type,
            'invoice_date' => $request->invoice_date,
            'inquiry_no' => $request->inquiry_no,
            'location' => $request->location,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'no_of_pax' => $request->no_of_pax,
            'ticket_no' => $request->ticket_no,
            'gross_supplier' => $request->gross_supplier,
            'commission' => $request->commission,
            'net_cost' => $request->net_cost,
            'markup' => $request->markup,
            'remarks_booking' => $request->remarks_booking,
            'customer_mc' => $request->customer_mc,
            'customer_vi' => $request->customer_vi,
            'remarks_payment' => $request->remarks_payment,
            'other_payment' => $request->other_payment,
            'amex_payment' => $request->amex_payment,
            'gift_card' => $request->gift_card,
            'past_credit' => $request->past_credit,
            'etransfer' => $request->etransfer,
            'cheque_draft' => $request->cheque_draft,
            'cash' => $request->cash,
            'debit' => $request->debit,
            'other_supplier' => $request->other_supplier,
            'company_master' => $request->company_master,
            'company_visa' => $request->company_visa,
            'gst' => $request->gst,
            'company_emax' => $request->company_emax,
            'customer_card' => $request->customer_card,
            'gds' => $request->gds,
            'web_phone' => $request->web_phone,
            'remarks_supplier' => $request->remarks_supplier,
            'return_date' => $request->return_date,
            'departure_date' => $request->departure_date,
            'other_remarks' => $request->other_remarks,
            'secondary_agent' => $request->secondary_agent,
            'secondary_agent_share' => $request->secondary_agent_share,
            'agent' => $request->agent,
            'supplier' => $request->supplier
        ]);

        return true;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleForm $saleForm)
    {
        //
    }
}
