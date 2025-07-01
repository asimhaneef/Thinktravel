<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InquiriesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Booking::with(['bookingFlight', 'member', 'user', 'agent', 'vacationOrigin', 'vacationDestination', 'cruisesOrigin', 'cruisesDestination'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Inquiry Code',
            'Booking Type',
            'Flight Type',
            'Cruising Origin',
            'Cruising Location',
            'Cruising Month',
            'Add Hotel',
            'Hotel Checkin Date', 
            'Hotel Checkout Date',
            'Travel Doc', 
            'Travel Doc Nationality',
            'Travel Doc Other',
            'Additional Notes',
            'Adults',
            'Children',
            'Infants',
            'Vacation Origin',
            'Vacation Destination',
            'Vacation Total Days',
            'Vacation Flexibility',
            'Vacation Preferred Airline',
            'Vacation Departing Date',
            'Member ID',
            'USA Connection',
            'Payment Mode',
            'Agent Name', 
            'Agree On Terms', 
            'Contact By Email',
            'Booking Status', 
            'Customer Identification', 
            'Quote Submitted',
            'Customer Contacted', 
            'Booking Ref',
            'Payment Received', 
            'Insurance Purchased', 
            'Ticket Issued', 
            'Reservation Made', 
            'User ID',
            'Created At',
            'Updated At'
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->inquiry_code ?? 'N/A',
            $booking->booking_type ?? 'N/A',
            $booking->flight_type ?? 'N/A',
            $booking->cruisesOrigin->airport_name ?? 'N/A',
            $booking->cruisesDestination->region ?? 'N/A',
            $booking->cruising_month ?? 'N/A',
            $booking->add_hotel == 1 ? 'Yes' : 'No',
            $booking->hotel_checkin_date ?? 'N/A',
            $booking->hotel_checkout_date ?? 'N/A',
            $booking->travel_doc ?? 'N/A',
            $booking->travel_doc_nationality ?? 'N/A',
            $booking->travel_doc_other ?? 'N/A',
            $booking->additional_notes ?? 'N/A',
            $booking->adults ?? 0,
            $booking->children ?? 0,
            $booking->infants ?? 0,
            $booking->vacationOrigin->airport_name ?? 'N/A',
            $booking->vacationDestination->country ?? 'N/A',
            $booking->vacation_total_days ?? 0,
            $booking->vacation_flexibility ?? 'N/A',
            $booking->vacation_preferred_airline ?? 'N/A',
            $booking->vacation_departing_date ?? 'N/A',
            $booking->member->user_name ?? 'N/A',
            $booking->usa_connection == 1 ? 'Yes' : 'No',
            $booking->payment_mode ?? 'N/A',
            $booking->agent->username ?? 'N/A',
            $booking->agree_on_terms == 1 ? 'Yes' : 'No', 
            $booking->contact_by_email == 1 ? 'Yes' : 'No',
            $booking->booking_status ?? 'N/A',
            $booking->customer_identification ?? 'N/A',
            $booking->quote_submitted == 1 ? 'Yes' : 'No',
            $booking->customer_contacted == 1 ? 'Yes' : 'No',
            $booking->booking_ref ?? 'N/A',
            $booking->payment_received == 1 ? 'Yes' : 'No',
            $booking->insurance_purchased == 1 ? 'Yes' : 'No',
            $booking->ticket_issued == 1 ? 'Yes' : 'No', 
            $booking->reservation_made == 1 ? 'Yes' : 'No', 
            $booking->user->username ?? 'N/A',
            $booking->created_at ?? 'N/A',
            $booking->updated_at ?? 'N/A'
        ];
    }
}
