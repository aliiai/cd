<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير حالات الديون</title>
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
            width: 16.66%;
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
        <h1>تقرير حالات الديون</h1>
        <div class="header-info">
            <div>اسم المالك: {{ $owner->name }}</div>
            <div>البريد الإلكتروني: {{ $owner->email }}</div>
            <div>تاريخ التوليد: {{ $generated_at }}</div>
        </div>
    </div>

    <div class="stats-grid">
        @foreach($debtStatusReport as $report)
        <div class="stat-card">
            <div class="stat-header">{{ $report['status_text'] }}</div>
            <div class="stat-value">{{ number_format($report['count']) }}</div>
            <div class="stat-label">عدد المديونين</div>
            <div style="margin-top: 6px; font-size: 12px; font-weight: bold; color: #059669;">
                {{ number_format($report['total_amount'], 2) }} س.ر
            </div>
            <div class="stat-label">إجمالي المبلغ</div>
        </div>
        @endforeach
    </div>

    <div class="footer">
        <div>تم توليد هذا التقرير تلقائياً من نظام إدارة الديون</div>
        <div>جميع الحقوق محفوظة © {{ date('Y') }}</div>
    </div>
</body>
</html>

