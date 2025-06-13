<!DOCTYPE html>
<html>
<head>
    <title>Receipt PDF</title>
    <style>
        @page {
            margin: 0 0; /* Space for header and footer */
        }
        body {
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 12px;
        }
        .content {
            margin-top: 5px;
        }
        .content p, .content span {
            line-height: 1.6;
        }
        .payment-mode {
            margin: 10px 0;
        }
        .payment-mode input {
            margin-right: 5px;
            vertical-align: middle;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: left;
        }
        td {
            text-align: right;
        }
        td:first-child {
            text-align: left;
        }
        .page-border {
            padding: 0 30px;
        }
        /* Footer Styling */
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 12px;
            padding-top: 5px;
        }
    </style>
</head>
<body>
        
        <table border="0"  style="border-collapse: collapse;border:none;background-color: #E6E6E6;margin-top: 0px; padding-top: 10px;">
            <tr style="border-collapse: collapse;border:none">
                <td style="border:none;padding-left: 20px">
                    <img width="100px" src="{{ public_path('images/pdf-logo.png') }}" alt="Header Image">
                </td>
                <td style="border:none;text-align: center;padding-right: 40px;">
                    <span class="header-title" style="font-size: 20px;text-transform: uppercase;"><strong>Gobind Marg Charitable Trust Foundation <br> Darbar Sri Guru Granth Sahib Ji</strong></span><br><small>{{ $charityOrganization->address }} {{ $charityOrganization->province->province_name }} {{ $charityOrganization->province->province_code }} {{ $charityOrganization->postal_code }} , Tel: {{ $charityOrganization->phone }}</small>
                </td>
            </tr>
        </table>
        <table border="0"  style="border-collapse: collapse;border:none;background-color: #E6E6E6;margin-top: 0px;padding: 0">   
            <tr style="border:none;">
                <td style="border:none;background-color: #1C3766; color: #fff;padding-left: 30px">
                    <span class="header-left"><strong>Receipt#</strong> {{ $receipt->receipt_no }}</span>  
                </td>
                <td style="border:none;padding-right: 20px">
                    <span class="header-right"><strong>Date:</strong> {{ date('Y-m-d', strtotime($receipt->receipt_date)) }}</span>
                </td>
            </tr>
        </table>
    <div class="page-border">
        <div class="content">
            <p><strong>Received with Thanks from:</strong> {{ $receipt->member->full_name }}</p>
            <p><strong>Address:</strong> {{ $receipt->member->address }}</p>
            <div class="payment-mode" style="text-align:center;">
                <strong>Payment Mode:</strong>
                @foreach ($payment_modes as $payment_mode)

                    @if ($payment_mode->payment_mode == $receipt->paymentMode->payment_mode)
                    <span style="margin:5px auto; border:1px solid #1C3766; background-color: #1C3766; color: #fff; width: 10px; height: 12px;"  >  
                        <img style="margin-top: 2px;padding-right:2px;" src="{{ public_path('images/tick.png') }}" alt="tick" width="10" height="12"> 
                    </span>
                    @else 
                    <span style=" margin-right: 5px;border:1px solid black; color: #fff; width: 20px; height: 20px;"  >  
                        &nbsp;  
                    </span>
                    @endif
                    
                    <span style="margin-right: 15px;">{{ $payment_mode->payment_mode }}</span>
                @endforeach
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <!--<th style="width:50%;">Donation</th>-->
                    <th style="width:50%;">Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($receipt->receiptDetails as $detail)
                <tr>
                    <!--<td>{{ $detail->fundType->fund_type }}</td>-->
                    <td>{{ $detail->payment_description }}</td>
                    <td>$ {{ number_format($detail->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <?php
        $isEvent = false;
        foreach ($receipt->booking->bookingDetails as $bookingDetail) {
            if ($bookingDetail->resourceRate->resource->resourceCategory->name == 'Events') {
                $isEvent = true;
            }
        }
        ?>
        <table style="border-collapse: collapse;border:none;padding-left:0;margin-left:0;">
            <tr style="border:none;padding-left:0;margin-left:0;">
                <td style="width:50%;border:none;padding-left:0;margin-left:0;font-size:11px;">
                    @if (!$isEvent)
                    This Receipt is not for tax purpose.
                    @else
                    Official Receipt For Income Tax Purposes Reg: #{{ $charityOrganization->cra_registration_number }}<br>
                    Canada Revenue Agency Website: www.cra.arc.gc.calcharities
                    
                    @endif
                </td>
                <td style="border:none;background-color: #1C3766; color: #fff;">
                    <span style="margin-top: 10px; font-size: 14px; text-align: right;">
                        <strong>Total Amount: $ {{ number_format($receipt->receiptDetails->sum('amount'), 2) }}</strong>
                    </span>
                </td>
            </tr>
        </table>
        
    </div>
</body>
</html>
