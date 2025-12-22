<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير مقدمي الخدمة</title>
    <style>
        * {
            font-family: 'dejavusans', 'DejaVu Sans', Arial, sans-serif;
            unicode-bidi: embed;
            text-align: right;
        }
        body {
            direction: rtl;
        }
        @page { 
            size: A4 landscape; 
            margin: 10mm;
        }
        body {
            margin: 0;
            padding: 8px;
            font-size: 8px;
            color: #333;
        }
        .header {
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 8px;
            margin-bottom: 10px;
            page-break-after: avoid;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            color: #1e40af;
        }
        .header-info {
            margin-top: 5px;
            font-size: 8px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 7px;
            page-break-inside: auto;
        }
        thead {
            display: table-header-group;
        }
        tbody {
            display: table-row-group;
        }
        tr {
            page-break-inside: auto;
            page-break-after: auto;
        }
        th {
            background: #f3f4f6;
            padding: 4px;
            border: 1px solid #d1d5db;
            font-weight: bold;
            text-align: right;
            font-size: 7px;
        }
        td {
            padding: 3px;
            border: 1px solid #e5e7eb;
            text-align: right;
            font-size: 7px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 0;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .footer {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            font-size: 7px;
            color: #6b7280;
            text-align: center;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير مقدمي الخدمة - الأدمن</h1>
        <div class="header-info">
            <div>تاريخ التوليد: {{ $generated_at }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>اسم مقدم الخدمة</th>
                <th>البريد الإلكتروني</th>
                <th>الباقة الحالية</th>
                <th>عدد المديونين</th>
                <th>عدد الرسائل المرسلة</th>
                <th>نسبة التحصيل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($providers as $provider)
            <tr>
                <td>{{ $provider->name }}</td>
                <td>{{ $provider->email }}</td>
                <td>{{ $provider->activeSubscription->subscription->name ?? 'غير محدد' }}</td>
                <td>{{ number_format($provider->debtors_count ?? 0) }}</td>
                <td>{{ number_format($provider->messages_sent ?? 0) }}</td>
                <td>{{ number_format($provider->collection_rate ?? 0, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div>تم توليد هذا التقرير تلقائياً من نظام إدارة الديون</div>
        <div>جميع الحقوق محفوظة © {{ date('Y') }}</div>
    </div>
</body>
</html>

