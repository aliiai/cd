<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الاشتراك</title>
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
        .info-section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            padding: 6px;
            background: #f9fafb;
            border-right: 2px solid #3b82f6;
        }
        .info-label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
            color: #374151;
            font-size: 9px;
        }
        .info-value {
            display: table-cell;
            color: #111827;
            font-size: 9px;
        }
        .usage-bar {
            width: 100%;
            height: 18px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 3px;
        }
        .usage-fill {
            height: 100%;
            background: linear-gradient(to left, #3b82f6, #2563eb);
            text-align: center;
            line-height: 18px;
            color: white;
            font-weight: bold;
            font-size: 9px;
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
        <h1>تقرير الاشتراك والاستهلاك</h1>
        <div class="header-info">
            <div>المالك: {{ $owner->name }}</div>
            <div>البريد الإلكتروني: {{ $owner->email }}</div>
            <div>تاريخ التوليد: {{ $generated_at }}</div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">الباقة الحالية:</div>
            <div class="info-value">{{ $activeSubscription?->subscription->name ?? 'لا يوجد اشتراك نشط' }}</div>
        </div>
        @if($activeSubscription)
        <div class="info-row">
            <div class="info-label">تاريخ البدء:</div>
            <div class="info-value">{{ $activeSubscription->started_at->format('Y-m-d') }}</div>
        </div>
        @if($activeSubscription->expires_at)
        <div class="info-row">
            <div class="info-label">تاريخ الانتهاء:</div>
            <div class="info-value">{{ $activeSubscription->expires_at->format('Y-m-d') }}</div>
        </div>
        @endif
        @endif
    </div>

    <div class="info-section">
        <h2 style="font-size: 18px; margin-bottom: 15px; color: #1e40af;">مؤشرات الاستهلاك</h2>
        
        <div style="margin-bottom: 20px;">
            <div class="info-label" style="margin-bottom: 5px;">عدد المديونين:</div>
            <div style="font-size: 16px; color: #111827; margin-bottom: 5px;">
                {{ number_format($debtorsCount) }} / {{ $maxDebtors > 0 ? number_format($maxDebtors) : 'غير محدود' }}
            </div>
            @if($maxDebtors > 0)
            <div class="usage-bar">
                <div class="usage-fill" style="width: {{ min($debtorsUsage, 100) }}%;">
                    {{ number_format($debtorsUsage, 1) }}%
                </div>
            </div>
            @endif
        </div>

        <div>
            <div class="info-label" style="margin-bottom: 5px;">الرسائل المرسلة:</div>
            <div style="font-size: 16px; color: #111827; margin-bottom: 5px;">
                {{ number_format($messagesSent) }} / {{ $maxMessages > 0 ? number_format($maxMessages) : 'غير محدود' }}
            </div>
            @if($maxMessages > 0)
            <div class="usage-bar">
                <div class="usage-fill" style="width: {{ min($messagesUsage, 100) }}%;">
                    {{ number_format($messagesUsage, 1) }}%
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <div>تم توليد هذا التقرير تلقائياً من نظام إدارة الديون</div>
        <div>جميع الحقوق محفوظة © {{ date('Y') }}</div>
    </div>
</body>
</html>

