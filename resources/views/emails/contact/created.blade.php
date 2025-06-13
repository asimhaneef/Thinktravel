<!DOCTYPE html>
<html>
<head>
    <title>New Contact Us Form Enquiry</title>
</head>
<body>
    <h1>New Contact Us Form Enquiry Created</h1>
    <p>First Name: {{ $contact->first_name }}</p>
    <p>Last Name: {{ $contact->last_name }}</p>
    <p>Phone: {{ $contact->contact_phone }}</p>
    <p>Email: {{ $contact->contact_email }}</p>
    <p>Message: {{ $contact->message }}</p>
</body>
</html>
