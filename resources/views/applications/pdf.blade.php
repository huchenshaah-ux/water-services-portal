<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application {{ $application->entry_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { color: #007bff; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f4f4f4; width: 35%; }
    </style>
</head>
<body>
    <h1>Water Services Application Form</h1>
    <p><strong>Entry No:</strong> {{ $application->entry_no }}</p>
    <table>
        <tr><th>Application Date</th><td>{{ $application->application_date->format('d M Y') }}</td></tr>
        <tr><th>Applicant Name</th><td>{{ $application->applicant_name }}</td></tr>
        <tr><th>ID Number</th><td>{{ $application->id_number }}</td></tr>
        <tr><th>Contact</th><td>{{ $application->contact_number }}</td></tr>
        <tr><th>Address</th><td>{{ $application->address }}</td></tr>
        <tr><th>Service Address</th><td>{{ $application->service_address }}</td></tr>
        <tr><th>Billing Address</th><td>{{ $application->billing_address }}</td></tr>
        <tr><th>Service Category</th><td>{{ $application->service_category }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($application->status) }}</td></tr>
        <tr><th>Supervised By</th><td>{{ $application->supervisor?->name }}</td></tr>
        <tr><th>Fenaka ID</th><td>{{ $application->fenaka_id }}</td></tr>
        <tr><th>Remarks</th><td>{{ $application->remarks }}</td></tr>
    </table>
</body>
</html>
