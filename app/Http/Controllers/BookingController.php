<?php

namespace App\Http\Controllers;

use App\Models\BookingFlight;
use App\Models\BookingAgentComments;
use App\Models\Booking;
use App\Models\Member;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class BookingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:bookings.view', only: ['index']),
            new Middleware('permission:bookings.edit', only: ['edit', 'update']),
            new Middleware('permission:bookings.add', only: ['add', 'store']),
            new Middleware('permission:bookings.delete', only: ['destroy']),
        ];
    }
    protected static ?string $password;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $request = request();
        $query = Booking::with('bookingFlight.flightFrom','bookingFlight.flightTo','member','user','agent');
        
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
    public function flightInquiry()
    {
        //
        $request = request();
        $query = Booking::with('bookingFlight.flightFrom','bookingFlight.flightTo','member','user','agent','agentComments.agent','files')->where('booking_type','=','flights');

        // Filtering
        if ($request->has('filters')) {
            $filters = json_decode($request->filters, true);
            foreach ($filters as $filter => $value) {
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
    public function vacationInquiry()
    {
        //
        $request = request();
        $query = Booking::with('vacationOrigin','vacationDestination','member','user','agent','agentComments.agent','files')->where('booking_type','=','vacations');

        // Filtering
        if ($request->has('filters')) {
            $filters = json_decode($request->filters, true);
            foreach ($filters as $filter => $value) {
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
    public function cruiseInquiry()
    {
        //
        $request = request();
        $query = Booking::with('cruisesOrigin','cruisesDestination','member','user','agent','agentComments.agent','files')->where('booking_type','=','cruises');

        // Filtering
        if ($request->has('filters')) {
            $filters = json_decode($request->filters, true);
            foreach ($filters as $filter => $value) {
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
    public function store(StoreBookingRequest $request)
    {
        try {
            DB::beginTransaction();

            // Validate date range
            if ($request->has(['checkin_date', 'checkout_date'])) {
                $checkin = Carbon::parse($request->checkin_date)->startOfDay();
                $checkout = Carbon::parse($request->checkout_date)->startOfDay();
                
                if ($checkin->greaterThan($checkout)) {
                    throw new \Exception('Check-in date must be before check-out date.');
                }
            }

            // Handle booking creation/update
            $booking = isset($request->id) && $request->id > 0
                ? $this->updateBooking($request, $request->id)
                : $this->createBooking($request);

            DB::commit();

            return response()->json([
                'message' => $request->has('id') 
                    ? 'Booking updated successfully.' 
                    : 'Booking created successfully.',
                'data' => $booking
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'error' => 'Booking processing failed: ' . $e->getMessage()
            ], 400);
        }
    }

    protected function createBooking($request)
    {
        $member_code =  substr($request->first_name,0,1).substr($request->last_name,0,1).substr($request->contact_no,-4);
        $member = Member::create([
            'member_code' => $member_code,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_no' => $request->contact_no,
            'email' => $request->member_email,
            'user_name' => $member_code,
            'password' => static::$password ??= Hash::make('password'),
            'gender' => $request->gender,
            'country_id' => $request->country_id,
            'user_id' => auth()->id()
        ]);
        if(!$member){
            throw new \Exception('Member not created.');                    
        }  
        $inquiry_code = $this->getInquiryCode();
        $booking = Booking::create([ 
            'inquiry_code'=> $inquiry_code ?? null,             
            'booking_type'=> $request->booking_type ?? null, 
            
            'booking_ref'=> $request->booking_ref ?? null,
            'booking_status'=> $request->booking_status ?? 'Open',
            'flight_type'=> $request->flying_type ?? null, 

            'cruising_location'=> $request->cruising_location ?? null,
            'cruising_month'=> $request->cruising_month ?? null,
            'cruising_origin'=> $request->cruising_origin ?? null,

            'add_hotel'=> $request->add_hotel === 'true' ? 1 : 0, 
            'hotel_checkin_date'=> $request->checkin_date ?? null, 
            'hotel_checkout_date'=> $request->checkout_date ?? null, 

            'travel_doc'=> $request->travel_doc ?? null, 
            'travel_doc_nationality'=> $request->travel_doc_nationality ?? null, 
            'travel_doc_other'=> $request->travel_doc_other ?? null, 
            'additional_notes'=> $request->additional_notes ?? null,

            'vacation_origin'=> $request->vacation_origin ?? null,
            'vacation_destination'=> $request->vacation_destination ?? null,
            'vacation_total_days'=> $request->vacation_total_days ?? null,
            'vacation_flexibility'=> $request->vacation_flexibility ?? null,
            'vacation_preferred_airline'=> $request->vacation_preferred_airline ?? null,
            'vacation_departing_date'=> $request->vacation_departing_date ?? null,

            'adults'=> $request->adults ?? null, 
            'children'=> $request->children ?? null, 
            'infants'=> $request->infants ?? null, 
            'member_id'=> $member->id ?? null, 
            'usa_connection'=> $request->usa_connection === 'true'  ? 1 : 0, 
            'payment_mode'=> $request->payment_mode ?? null,
            'agree_on_terms'=> $request->agree_on_terms ? 1 : 0, 
            'contact_by_email'=> $request->contact_by_email ? 1 : 0,             
            
            'customer_contacted'=> $request->customer_contacted ,
            'quote_submitted'=> $request->quote_submitted ,
            'reservation_made'=> $request->reservation_made,
            'payment_received'=> $request->payment_received ,
            'ticket_issued'=> $request->ticket_issued ,
            'insurance_purchased'=> $request->insurance_purchased ,

            'customer_identification'=> $request->customer_identification ?? null,
            'agent_name' => $request->agent_name ?? null,
            'userid' => auth()->id(),
        ]);
        if(!$booking){
            throw new \Exception('Booking not created.');                    
        }
        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $this->uploadFile($file, 'bookings', $booking);
        }
        $flightDetails = json_decode($request->booking_flight, true);
        $agentComments = json_decode($request->agent_comments, true);
        if(isset($flightDetails)){
            foreach ($flightDetails as $detail) {
            //check for flight dates
            if(isset($detail['departing_date']) && isset($detail['returning_date']) && $detail['departing_date'] > $detail['returning_date']){
                throw new \Exception('Start Date should be smaller than End Date.');
            }
                BookingFlight::create([
                    'booking_id'=> $booking->id, 
                    'userid'=> $request->agent_name, 
                    'flying_from'=> $detail['flying_from'],
                    'flying_to'=> $detail['flying_to'], 
                    'departing_date'=> $detail['departing_date'], 
                    'returning_date'=> $detail['returning_date'],
                    'flexibility'=> $detail['flexibility'], 
                    'preferred_airline'=> $detail['preferred_airline']
                ]);
            }
        }
        if(isset($agentComments)){
            foreach ($agentComments as $agentComment) {                        
                BookingAgentComments::create([
                    'booking_id'=> $booking->id, 
                    'agent_id'=> $agentComment['agent_id'], 
                    'comment'=> $agentComment['comment'], 
                ]);
            }
        }

        return $booking;
    }


    protected function updateBooking($request, $id)
    {
        // Find the booking
        $booking = Booking::find($id);
        if (!$booking) {
            throw new \Exception('Booking not found.');
        }

        // Update booking details
        $booking->update([
            'booking_type' => $request->booking_type ?? $booking->booking_type,
            'booking_ref'=> $request->booking_ref ?? $booking->booking_ref,
            'booking_status'=> $request->booking_status ?? $booking->booking_status,
            'flight_type' => $request->flying_type ?? $booking->flight_type,

            'cruising_location' => $request->cruising_location ?? $booking->cruising_location,
            'cruising_month' => $request->cruising_month ?? $booking->cruising_month,
            'cruising_origin' => $request->cruising_origin ?? $booking->cruising_origin,
            
            'add_hotel' => $request->add_hotel === 'true' ? 1 : 0,
            'hotel_checkin_date' => $request->checkin_date ?? $booking->hotel_checkin_date,
            'hotel_checkout_date' => $request->checkout_date ?? $booking->hotel_checkout_date,
            'travel_doc' => $request->travel_doc ?? $booking->travel_doc,
            'travel_doc_nationality' => $request->travel_doc_nationality ?? $booking->travel_doc_nationality,
            'travel_doc_other' => $request->travel_doc_other ?? $booking->travel_doc_other,
            'additional_notes' => $request->additional_notes ?? $booking->additional_notes,

            'vacation_origin' => $request->vacation_origin ?? $booking->vacation_origin,
            'vacation_destination' => $request->vacation_destination ?? $booking->vacation_destination,
            'vacation_total_days' => $request->vacation_total_days ?? $booking->vacation_total_days,
            'vacation_flexibility' => $request->vacation_flexibility ?? $booking->vacation_flexibility,
            'vacation_preferred_airline' => $request->vacation_preferred_airline ?? $booking->vacation_preferred_airline,
            'vacation_departing_date' => $request->vacation_departing_date ?? $booking->vacation_departing_date,

            'adults' => $request->adults ?? $booking->adults,
            'children' => $request->children ?? $booking->children,
            'infants' => $request->infants ?? $booking->infants,
            'usa_connection' => $request->usa_connection === 'true' ? 1 : 0,
            'payment_mode' => $request->payment_mode ?? $booking->payment_mode,
            'agree_on_terms' => $request->agree_on_terms ? 1 : 0,
            'contact_by_email' => $request->contact_by_email ? 1 : 0,
            'customer_contacted' => $request->customer_contacted ,
            'quote_submitted' => $request->quote_submitted ,
            'reservation_made' => $request->reservation_made ,
            'payment_received' => $request->payment_received ,
            'ticket_issued' => $request->ticket_issued ,
            'insurance_purchased' => $request->insurance_purchased ,
            'customer_identification' => $request->customer_identification ?? $booking->customer_identification,
            'agent_name' => $request->agent_name,
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $this->uploadFile($file, 'bookings', $booking);
        }

        // Handle flight details
        $flightDetails = json_decode($request->booking_flight, true);
        if ($flightDetails) {
            // Delete existing flight details
            BookingFlight::where('booking_id', $booking->id)->delete();

            foreach ($flightDetails as $detail) {
                if (isset($detail['departing_date']) && isset($detail['returning_date']) && $detail['returning_date'] !== "" && $detail['departing_date'] > $detail['returning_date']) {
                    throw new \Exception('Departing Date should be smaller than Returning Date.');
                }
                BookingFlight::create([
                    'booking_id' => $booking->id,
                    'userid' => $request->agent_name ?? auth()->id(),
                    'flying_from' => $detail['flying_from'] ?? null,
                    'flying_to' => $detail['flying_to'] ?? null,
                    'departing_date' => $detail['departing_date'] !== "" ? date('Y-m-d', strtotime($detail['departing_date'])) : null,
                    'returning_date' => $detail['returning_date'] !== "" ? date('Y-m-d', strtotime($detail['returning_date'])) : null,
                    'flexibility' => $detail['flexibility'] ?? null,
                    'preferred_airline' => $detail['preferred_airline'] ?? null,
                ]);
            }
        }

        // Handle agent comments
        $agentComments = json_decode($request->agent_comments, true);
        if ($agentComments) {
            // Delete existing agent comments
            BookingAgentComments::where('booking_id', $booking->id)->delete();

            foreach ($agentComments as $agentComment) {
                BookingAgentComments::create([
                    'booking_id' => $booking->id,
                    'agent_id' => $agentComment['agent_id'],
                    'comment' => $agentComment['comment'],
                ]);
            }
        }

        return $booking;
    }
    //
    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }

    private function getInquiryCode()
    {
        // Get the latest inquiry_code from the database
        $latestInquiry = DB::table('bookings')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestInquiry || trim($latestInquiry->inquiry_code) == '') {
            // If no record exists or inquiry_code is empty, generate the first code
            $maxRefNo = str_pad(date('m'), 2, '0', STR_PAD_LEFT) 
                    . str_pad(date('d'), 2, '0', STR_PAD_LEFT) 
                    . date('y') 
                    . '001';
        } else {
            // Extract parts of the inquiry_code
            $monthPart = substr($latestInquiry->inquiry_code, 0, 2);
            $dayPart   = substr($latestInquiry->inquiry_code, 2, 2);
            $yearPart  = substr($latestInquiry->inquiry_code, 4, 2);
            $numberPart = substr($latestInquiry->inquiry_code, 6, 3);

            // Check if the date parts match the current date
            if ($monthPart != str_pad(date('m'), 2, '0', STR_PAD_LEFT) 
                || $dayPart != str_pad(date('d'), 2, '0', STR_PAD_LEFT) 
                || $yearPart != date('y')) {
                $nextNumber = '001';
            } else {
                // Increment the number part
                $nextNumber = intval($numberPart) + 1;
                $nextNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }

            // Generate the new inquiry_code
            $maxRefNo = str_pad(date('m'), 2, '0', STR_PAD_LEFT) 
                    . str_pad(date('d'), 2, '0', STR_PAD_LEFT) 
                    . date('y') 
                    . $nextNumber;
        }

        return $maxRefNo;
    }
    public function saveInquiry(StoreBookingRequest $request)
    {
        try {
            
            DB::transaction(function () use ($request) {
                
                //check for hotel days
                if(isset($request->checkin_date) && isset($request->checkout_date) && $request->checkin_date > $request->checkout_date){
                    throw new \Exception('Start Date should be smaller than End Date.');
                }
                $member_code =  substr($request->first_name,0,1).substr($request->last_name,0,1).substr($request->contact_no,-4);
                $member = Member::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone_no' => $request->contact_no,
                    'email' => $request->member_email,
                    'member_code' => $member_code,
                    'user_name' => $member_code,
                    'password' => static::$password ??= Hash::make('password'),
                    'gender' => $request->gender,
                    'country_id' => $request->country_id
                ]);
                if(!$member){
                    throw new \Exception('Member not created.');                    
                }  
                $inquiry_code = $this->getInquiryCode();
                $booking = Booking::create([ 
                    'inquiry_code'=> $inquiry_code ?? null,             
                    'booking_type'=> $request->booking_type ?? null, 
                    'flight_type'=> $request->flying_type ?? null, 
                    'booking_ref'=> $request->booking_ref ?? null,

                    'cruising_location'=> $request->cruising_location ?? null,
                    'cruising_month'=> $request->cruising_month ?? null,
                    'cruising_origin'=> $request->cruising_origin ?? null,

                    'add_hotel'=> $request->add_hotel ?? 0, 
                    'hotel_checkin_date'=> $request->checkin_date ?? null, 
                    'hotel_checkout_date'=> $request->checkout_date ?? null, 

                    'travel_doc'=> $request->travel_doc ?? null, 
                    'travel_doc_nationality'=> $request->travel_doc_nationality ?? null, 
                    'travel_doc_other'=> $request->travel_doc_other ?? null, 
                    'additional_notes'=> $request->additional_notes ?? null,

                    'vacation_origin'=> $request->vacation_origin ?? null,
                    'vacation_destination'=> $request->vacation_destination ?? null,
                    'vacation_total_days'=> $request->vacation_total_days ?? null,
                    'vacation_flexibility'=> $request->vacation_flexibility ?? null,
                    'vacation_preferred_airline'=> $request->vacation_preferred_airline ?? null,

                    'adults'=> $request->adults ?? null, 
                    'children'=> $request->children ?? null, 
                    'infants'=> $request->infants ?? null, 
                    'member_id'=> $member->id ?? null, 
                    'usa_connection'=> $request->usa_connection ?? 0, 
                    'payment_mode'=> $request->payment_mode ?? null,
                    'agree_on_terms'=> $request->agree_on_terms ?? 0, 
                    'contact_by_email'=> $request->contact_by_email ?? 0,             
                    
                    'customer_identification'=> $request->customer_identification ?? null,
                    'userid' => $request->agent_name ?? null,
                ]);
                if(!$booking){
                    throw new \Exception('Booking not created.');                    
                }
                if(isset($request->booking_flight)){
                    foreach ($request->booking_flight as $detail) {
                    //check for flight dates
                    if(isset($detail['departing_date']) && isset($detail['returning_date']) && $detail['departing_date'] > $detail['returning_date']){
                        throw new \Exception('Start Date should be smaller than End Date.');
                    }
                        BookingFlight::create([
                            'booking_id'=> $booking->id, 
                            'userid'=> $request->agent_name, 
                            'flying_from'=> $detail['flying_from'],
                            'flying_to'=> $detail['flying_to'], 
                            'departing_date'=> $detail['departing_date'], 
                            'returning_date'=> $detail['returning_date'],
                            'flexibility'=> $detail['flexibility'], 
                            'preferred_airline'=> $detail['preferred_airline']
                        ]);
                    }
                }
                
        });
            return response()->json(['message' => 'Inquiry sent successfully.'], 201);
        } catch (\Exception $e) {
            // Catch any exceptions and return error response
            return response()->json(['error' => $e->getMessage()], 400);
        }
        

        
    }
}
