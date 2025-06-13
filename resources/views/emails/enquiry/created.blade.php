<!DOCTYPE html>
<html>
<head>
    <title>New Enquiry</title>
</head>
<body>
    <h1>New Enquiry Created</h1>
    <p>Name: {{ $enquiry->name }}</p>
    <p>Phone: {{ $enquiry->phone }}</p>
    <p>Email: {{ $enquiry->email }}</p>
    <p>Interest: {{ $enquiry->interest }}</p>
    <p>Message: {{ $enquiry->message }}</p>
    <p>Status: {{ $enquiry->status }}</p>
</body>
</html>
