<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الاشتراكات</title>
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
            size: A4 portrait; 
            margin: 10mm;
        }
        body {
            margin: 0;
            padding: 10px;
            font-size: 10px;
            color: #333;
        }
        .header {
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 8px;
            margin-bottom: 15px;
            page-break-after: avoid;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #1e40af;
        }
        .header-info {
            margin-top: 5px;
            font-size: 9px;
            color: #666;
        }
        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 8px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }
        .stat-header {
            background: #f3f4f6;
            padding: 6px;
            font-weight: bold;
            font-size: 10px;
            color: #1f2937;
            margin-bottom: 6px;
        }
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 3px;
        }
        .stat-label {
            font-size: 8px;
            color: #6b7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 8px;
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
            font-size: 8px;
        }
        td {
            padding: 3px;
            border: 1px solid #e5e7eb;
            text-align: right;
            font-size: 8px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 0;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            font-size: 8px;
            color: #6b7280;
            text-align: center;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير الاشتراكات - الأدمن</h1>
        <div class="header-info">
            <div>تاريخ التوليد: {{ $generated_at }}</div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">الاشتراكات النشطة</div>
            <div class="stat-value">{{ number_format($activeSubscriptions) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-header">أكثر باقة استخدامًا</div>
            <div class="stat-value">{{ $mostUsedSubscription->name ?? 'غير محدد' }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-header">معدل التجديد</div>
            <div class="stat-value">{{ number_format($renewalRate, 1) }}%</div>
        </div>
        <div class="stat-card">
            <div class="stat-header">الاشتراكات المرفوضة</div>
            <div class="stat-value">{{ number_format($rejectedRequests) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>مقدم الخدمة</th>
                <th>الباقة</th>
                <th>الحالة</th>
                <th>تاريخ البدء</th>
                <th>تاريخ الانتهاء</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $subscription)
            <tr>
                <td>{{ $subscription->user->name ?? 'غير محدد' }}</td>
                <td>{{ $subscription->subscription->name ?? 'غير محدد' }}</td>
                <td>{{ $subscription->status == 'active' ? 'نشط' : ($subscription->status == 'expired' ? 'منتهي' : 'ملغي') }}</td>
                <td>{{ $subscription->started_at ? \Carbon\Carbon::parse($subscription->started_at)->format('Y-m-d') : 'غير محدد' }}</td>
                <td>{{ $subscription->expires_at ? \Carbon\Carbon::parse($subscription->expires_at)->format('Y-m-d') : 'غير محدد' }}</td>
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

