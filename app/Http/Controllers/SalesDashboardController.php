<?php

namespace App\Http\Controllers;

use App\Models\SalesDashboard;
use App\Models\SaleForm;
use App\Models\User;
use App\Models\Booking;
use App\Http\Requests\StoreSalesDashboardRequest;
use App\Http\Requests\UpdateSalesDashboardRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalesDashboardRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesDashboard $salesDashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalesDashboardRequest $request, SalesDashboard $salesDashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesDashboard $salesDashboard)
    {
        //
    }
    public function SalesStats($from, $to)
    {
        $startDate = Carbon::parse($from);
        $endDate = Carbon::parse($to);

        // Calculate number of days between dates
        $daysCount = $startDate->diffInDays($endDate) + 1;

        // Calculate comparison period (previous identical duration)
        $comparisonStartDate = $startDate->copy()->subDays($daysCount);
        $comparisonEndDate = $endDate->copy()->subDays($daysCount);

        // Get sales data for both periods
        $sales = SaleForm::whereBetween('entry_date', [$startDate, $endDate])->get();
        $comparisonSales = SaleForm::whereBetween('entry_date', [$comparisonStartDate, $comparisonEndDate])->get();

        // Initialize totals for current period
        $totalRevenue = 0;
        $totalReceived = 0;
        $totalMargin = 0;
        $customerCard = 0;
        
        foreach ($sales as $sale) {
            $totalRevenue += ($sale->gross_supplier ?? 0) + ($sale->markup ?? 0);
            $customerCard += $sale->customer_card ?? 0;
            $totalMargin += ($sale->markup ?? 0) + ($sale->commission ?? 0);
            
            $totalReceived += (
                ($sale->customer_mc ?? 0) +
                ($sale->customer_vi ?? 0) +
                ($sale->debit ?? 0) +
                ($sale->cash ?? 0) +
                ($sale->cheque_draft ?? 0) +
                ($sale->etransfer ?? 0) +
                ($sale->past_credit ?? 0) +
                ($sale->gift_card ?? 0) +
                ($sale->amex_payment ?? 0) +
                ($sale->other_payment ?? 0)
            );
        }

        $balance = $totalRevenue - $totalReceived - $customerCard;
        $marginPercentage = $totalRevenue > 0 ? ($totalMargin / $totalRevenue) * 100 : 0;

        // Initialize totals for comparison period
        $comparisonTotalRevenue = 0;
        $comparisonTotalReceived = 0;
        $comparisonTotalMargin = 0;
        $comparisonCustomerCard = 0;
        
        foreach ($comparisonSales as $compSale) {
            $comparisonTotalRevenue += ($compSale->gross_supplier ?? 0) + ($compSale->markup ?? 0);
            $comparisonCustomerCard += $compSale->customer_card ?? 0;
            $comparisonTotalMargin += ($compSale->markup ?? 0) + ($compSale->commission ?? 0);
            
            $comparisonTotalReceived += (
                ($compSale->customer_mc ?? 0) +
                ($compSale->customer_vi ?? 0) +
                ($compSale->debit ?? 0) +
                ($compSale->cash ?? 0) +
                ($compSale->cheque_draft ?? 0) +
                ($compSale->etransfer ?? 0) +
                ($compSale->past_credit ?? 0) +
                ($compSale->gift_card ?? 0) +
                ($compSale->amex_payment ?? 0) +
                ($compSale->other_payment ?? 0)
            );
        }

        $comparisonBalance = $comparisonTotalRevenue - $comparisonTotalReceived - $comparisonCustomerCard;
        $comparisonMarginPercentage = $comparisonTotalRevenue > 0 ? ($comparisonTotalMargin / $comparisonTotalRevenue) * 100 : 0;

        // Calculate projections
        $calculateProjection = function($current, $previous) {
            if ($previous == 0) return 0;
            return (($current - $previous) / abs($previous)) * 100;
        };

        $projectionBalance = $calculateProjection($balance, $comparisonBalance);
        $projectionMargin = $calculateProjection($totalMargin, $comparisonTotalMargin);
        $projectionRevenue = $calculateProjection($totalRevenue, $comparisonTotalRevenue);
        $projectionMarginPercentage = $calculateProjection($marginPercentage, $comparisonMarginPercentage);

        // Format numbers
        $formatNumber = function($value, $isCurrency = true) {
            $formatted = number_format($value, 2, '.', ',');
            return $isCurrency ? $formatted : rtrim(rtrim($formatted, '0'), '.');
        };

        return response()->json([
            // Original variables
            'totalRevenue' => $formatNumber($totalRevenue),
            'total_received' => $formatNumber($totalReceived),
            'outstanding_balance' => $formatNumber($balance),
            'total_margin' => $formatNumber($totalMargin),
            'margin_percentage' => $formatNumber($marginPercentage, false) . '%',
            
            // Comparison data
            'comparison_total_revenue' => $formatNumber($comparisonTotalRevenue),
            'comparison_total_received' => $formatNumber($comparisonTotalReceived),
            'comparison_balance' => $formatNumber($comparisonBalance),
            'comparison_total_margin' => $formatNumber($comparisonTotalMargin),
            'comparison_margin_percentage' => $formatNumber($comparisonMarginPercentage, false) . '%',
            
            // Projections
            'projection_balance' => $formatNumber($projectionBalance, false) . '%',
            'projection_margin' => $formatNumber($projectionMargin, false) . '%',
            'projection_revenue' => $formatNumber($projectionRevenue, false) . '%',
            'projection_margin_percentage' => $formatNumber($projectionMarginPercentage, false) . '%',
            
            // Date info
            'days_count' => $daysCount,
            'date_range' => [
                'current' => [$startDate->toDateString(), $endDate->toDateString()],
                'previous' => [$comparisonStartDate->toDateString(), $comparisonEndDate->toDateString()]
            ]
        ]);
    }

    public function SaleformInquiryTypeCounts($from, $to)
    {
        $startDate = Carbon::parse($from)->startOfDay();
        $endDate = Carbon::parse($to)->endOfDay();
        $types = ['flights', 'vacations', 'cruises'];
        $result = [
            'types' => $types,
            'markup_sums' => [],
            'gross_supplier_sums' => [],
            'total_sums' => []
        ];

        foreach ($types as $type) {
            $sums = SaleForm::whereHas('enquiryDetails', function($query) use ($type) {
                    $query->where('booking_type', $type);
                })
                ->whereBetween('sale_forms.entry_date', [$startDate, $endDate])
                ->selectRaw('COALESCE(SUM(sale_forms.markup), 0) as markup_sum')
                ->selectRaw('COALESCE(SUM(sale_forms.gross_supplier), 0) as gross_supplier_sum')
                ->join('bookings', 'sale_forms.inquiry_no', '=', 'bookings.inquiry_code')
                ->first();

            $result['markup_sums'][] = $sums->markup_sum;
            $result['gross_supplier_sums'][] = $sums->gross_supplier_sum;
            $result['total_sums'][] = $sums->markup_sum + $sums->gross_supplier_sum;
        }

        return response()->json($result);
    }

    public function SaleformLineChartCounts($date)
    {
        $date = date('Y-m-d', strtotime($date));
        
        // Get all sale forms for the given date
        $sales = SaleForm::whereDate('entry_date', $date)
            ->select([
                'entry_date',
                'markup',
                'gross_supplier'
            ])
            ->get();

        // Initialize arrays to store hourly sums
        $hourlyData = [];
        $result = [
            'times' => [],
            'markup_sums' => [],
            'gross_supplier_sums' => [],
            'total_sums' => []
        ];

        foreach ($sales as $sale) {
            // Round time to nearest hour
            $time = Carbon::parse($sale->entry_date);
            $roundedHour = $time->minute >= 30 ? $time->addHour()->startOfHour() : $time->startOfHour();
            $hourKey = $roundedHour->format('g:i a'); // e.g. "3:00 am"
            
            // Initialize hour if not exists
            if (!isset($hourlyData[$hourKey])) {
                $hourlyData[$hourKey] = [
                    'markup' => 0,
                    'gross_supplier' => 0
                ];
            }
            
            // Sum values for this hour
            $hourlyData[$hourKey]['markup'] += $sale->markup;
            $hourlyData[$hourKey]['gross_supplier'] += $sale->gross_supplier;
        }

        // Sort by time and prepare result arrays
        ksort($hourlyData);
        foreach ($hourlyData as $hour => $data) {
            $result['times'][] = $hour;
            $result['markup_sums'][] = $data['markup'];
            $result['gross_supplier_sums'][] = $data['gross_supplier'];
            $result['total_sums'][] = $data['markup'] + $data['gross_supplier'];
        }

        return response()->json($result);
    }

    public function SupplierSalesCounts($id = null,$from, $to)
    {
        $startDate = Carbon::parse($from)->startOfDay();
        $endDate = Carbon::parse($to)->endOfDay();
        $query = SaleForm::with('supplier')
        ->whereBetween('entry_date', [$startDate, $endDate]);
        // return $id;
        // Filter by supplier if ID is provided
        if ($id !== null && $id !== "null") {
            $query->where('supplier', $id);
        }

         // Get all sales records with their suppliers
        $sales = $query->get();

        // Group by supplier and calculate sums
        $grouped = $sales->groupBy(function ($item) {
            return $item->supplier instanceof \Illuminate\Database\Eloquent\Model 
                ? $item->supplier->id 
                : $item->supplier; // Fallback if relation fails
        });

        // Prepare response arrays
        $supplierNames = [];
        $markupSums = [];
        $grossSupplierSums = [];
        $totalSums = [];

        foreach ($grouped as $supplierId => $salesGroup) {
            // Get the first sale to access supplier info
            $firstSale = $salesGroup->first();
            
            // Handle supplier name - check if relation loaded properly
            if ($firstSale->relationLoaded('supplier') && $firstSale->supplier instanceof \Illuminate\Database\Eloquent\Model) {
                $supplierName = $firstSale->supplier->first_name . ' ' . $firstSale->supplier->last_name;
            } else {
                // Fallback - you might want to fetch the supplier separately here
                $supplier = User::find($supplierId);
                $supplierName = $supplier ? $supplier->first_name . ' ' . $supplier->last_name : "Supplier $supplierId";
            }

            $supplierNames[] = $supplierName;
            $markupSums[] = $salesGroup->sum('markup');
            $grossSupplierSums[] = $salesGroup->sum('gross_supplier');
            $totalSums[] = $salesGroup->sum('markup') + $salesGroup->sum('gross_supplier');
        }

        return response()->json([
            'supplier_names' => $supplierNames,
            'markup_sums' => $markupSums,
            'gross_supplier_sums' => $grossSupplierSums,
            'total_sums' => $totalSums
        ]);
    }

    public function AgentSalesCounts($id = null,$from, $to)
    {
        $startDate = Carbon::parse($from)->startOfDay();
        $endDate = Carbon::parse($to)->endOfDay();
        $query = SaleForm::with('agent')
        ->whereBetween('entry_date', [$startDate, $endDate]);
        // Filter by agent if ID is provided
        if ($id !== null && $id !== "null") {
            $query->where('agent', $id);
        }

         // Get all sales records with their agents
        $sales = $query->get();

        // Group by agent and calculate sums
        $grouped = $sales->groupBy(function ($item) {
            return $item->agent instanceof \Illuminate\Database\Eloquent\Model 
                ? $item->agent->id 
                : $item->agent; // Fallback if relation fails
        });

        // Prepare response arrays
        $agentNames = [];
        $markupSums = [];
        $grossSupplierSums = [];
        $totalSums = [];

        foreach ($grouped as $agentId => $salesGroup) {
            // Get the first sale to access agent info
            $firstSale = $salesGroup->first();
            
            // Handle agent name - check if relation loaded properly
            if ($firstSale->relationLoaded('agent') && $firstSale->agent instanceof \Illuminate\Database\Eloquent\Model) {
                $agentName = $firstSale->agent->first_name . ' ' . $firstSale->agent->last_name;
            } else {
                // Fallback - you might want to fetch the agent separately here
                $agent = User::find($agentId);
                $agentName = $agent ? $agent->first_name . ' ' . $agent->last_name : "Agent $agentId";
            }

            $agentNames[] = $agentName;
            $markupSums[] = $salesGroup->sum('markup');
            $grossSupplierSums[] = $salesGroup->sum('gross_supplier');
            $totalSums[] = $salesGroup->sum('markup') + $salesGroup->sum('gross_supplier');
        }

        return response()->json([
            'agent_names' => $agentNames,
            'markup_sums' => $markupSums,
            'gross_supplier_sums' => $grossSupplierSums,
            'total_sums' => $totalSums
        ]);
    }

    public function SaleformPaymentBreakdown($from, $to)
    {
        $startDate = Carbon::parse($from)->startOfDay();
        $endDate = Carbon::parse($to)->endOfDay();

        $saleForm = SaleForm::query()
        ->whereBetween('entry_date', [$startDate, $endDate])
        ->selectRaw('
            SUM(customer_mc) as Customer_MC,
            SUM(customer_vi) as Customer_VI,
            SUM(debit) as debit,
            SUM(cash) as cash,
            SUM(cheque_draft) as cheque_draft,
            SUM(etransfer) as etransfer,
            SUM(past_credit) as past_credit,
            SUM(gift_card) as gift_card,
            SUM(amex_payment) as amex_payment,
            SUM(other_payment) as other_payment
        ')
        ->first();
        

        $paymentBreakdown = [
        $saleForm->Customer_MC, 
        $saleForm->Customer_VI, 
        $saleForm->debit,
        $saleForm->cash,
        $saleForm->cheque_draft, 
        $saleForm->etransfer,
        $saleForm->past_credit,
        $saleForm->gift_card,
        $saleForm->amex_payment,
        $saleForm->other_payment
        ];

        return response()->json($paymentBreakdown);
    }

    public function CruiseRegionPaymentBreakdown($from, $to)
    {
        $startDate = Carbon::parse($from)->startOfDay();
        $endDate = Carbon::parse($to)->endOfDay();

        // Get payment breakdown grouped by cruise destination region
        $results = SaleForm::with(['enquiryDetails.cruisesDestination'])
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->whereHas('enquiryDetails.cruisesDestination')
            ->get()
            ->groupBy(function ($saleForm) {
                return $saleForm->enquiryDetails->cruisesDestination->region ?? 'Unknown';
            })
            ->map(function ($groupedSales, $region) {
                return [
                    'region' => $region,
                    'payment_breakdown' => [
                        'Customer_MC' => $groupedSales->sum('customer_mc'),
                        'Customer_VI' => $groupedSales->sum('customer_vi'),
                        'debit' => $groupedSales->sum('debit'),
                        'cash' => $groupedSales->sum('cash'),
                        'cheque_draft' => $groupedSales->sum('cheque_draft'),
                        'etransfer' => $groupedSales->sum('etransfer'),
                        'past_credit' => $groupedSales->sum('past_credit'),
                        'gift_card' => $groupedSales->sum('gift_card'),
                        'amex_payment' => $groupedSales->sum('amex_payment'),
                        'other_payment' => $groupedSales->sum('other_payment'),
                        'total' => $groupedSales->sum(function ($sale) {
                            return $sale->customer_mc + $sale->customer_vi + $sale->debit + 
                                $sale->cash + $sale->cheque_draft + $sale->etransfer + 
                                $sale->past_credit + $sale->gift_card + 
                                $sale->amex_payment + $sale->other_payment;
                        })
                    ]
                ];
            })
            ->values();

        return response()->json($results);
    }
}
