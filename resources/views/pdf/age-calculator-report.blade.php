<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Age Calculator Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #1f2937;
            line-height: 1.6;
            background-color: #f9fafb;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            background-color: white;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 3px solid #e5447f;
        }
        .header h1 {
            font-size: 36px;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 14px;
            color: #6b7280;
        }
        .date {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 10px;
        }
        .section {
            margin-bottom: 35px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f3e8ff;
        }
        .age-display {
            background: linear-gradient(135deg, #fdf2f8 0%, #fed7aa 100%);
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            border-left: 4px solid #e5447f;
        }
        .age-display .years {
            font-size: 32px;
            font-weight: bold;
            color: #1f2937;
        }
        .age-display .unit {
            font-size: 14px;
            color: #6b7280;
        }
        .age-display .details {
            font-size: 14px;
            color: #374151;
            margin-top: 10px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid #e5447f;
        }
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #6b7280;
            font-weight: 500;
        }
        .info-value {
            color: #1f2937;
            font-weight: 600;
        }
        .zodiac-section {
            background: linear-gradient(135deg, #fef3c7 0%, #fecaca 100%);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-top: 20px;
        }
        .zodiac-emoji {
            font-size: 48px;
            margin: 10px 0;
        }
        .zodiac-sign {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }
        .birthday-today {
            background-color: #d1fae5;
            border-left: 4px solid #10b981;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }
        .birthday-today-text {
            font-size: 16px;
            font-weight: 600;
            color: #047857;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>Age Calculator Report</h1>
            <p>Comprehensive age and birthday analysis</p>
            <div class="date">Generated on {{ now()->format('F j, Y \a\t g:i A') }}</div>
        </div>

        {{-- Age Display --}}
        <div class="section">
            <div class="age-display">
                <div class="years">{{ $result['years'] }} <span class="unit">Years</span></div>
                <div class="details">{{ $result['months'] }} Months, {{ $result['days'] }} Days Old</div>
            </div>
        </div>

        {{-- Birthday Message --}}
        @if ($result['is_birthday_today'])
            <div class="birthday-today">
                <div class="birthday-today-text">🎉 Happy Birthday Today! 🎉</div>
            </div>
        @endif

        {{-- Key Metrics --}}
        <div class="section">
            <h2 class="section-title">Life Statistics</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Days Lived</div>
                    <div class="stat-value">{{ number_format($result['total_days']) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Weeks</div>
                    <div class="stat-value">{{ number_format($result['total_weeks']) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Months</div>
                    <div class="stat-value">{{ number_format($result['total_months']) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Hours</div>
                    <div class="stat-value">{{ number_format($result['total_hours']) }}</div>
                </div>
            </div>
        </div>

        {{-- Birthday Information --}}
        <div class="section">
            <h2 class="section-title">Birthday Information</h2>
            <div class="info-row">
                <span class="info-label">Date of Birth:</span>
                <span class="info-value">{{ $result['dob_formatted'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Born on a:</span>
                <span class="info-value">{{ $result['dob_weekday'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Next Birthday:</span>
                <span class="info-value">{{ $result['next_birthday_fmt'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Next Birthday on a:</span>
                <span class="info-value">{{ $result['next_bday_weekday'] }}</span>
            </div>
            @if (!$result['is_birthday_today'])
                <div class="info-row">
                    <span class="info-label">Days Until Next Birthday:</span>
                    <span class="info-value">{{ $result['days_until_next'] }} days</span>
                </div>
            @endif
        </div>

        {{-- Zodiac Sign --}}
        <div class="section">
            <h2 class="section-title">Zodiac Sign</h2>
            <div class="zodiac-section">
                <div class="zodiac-emoji">{{ $result['zodiac_emoji'] }}</div>
                <div class="zodiac-sign">{{ $result['zodiac_sign'] }}</div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>This report was generated using ToolsHub Age Calculator</p>
            <p style="margin-top: 5px;">{{ config('app.url') }}</p>
        </div>
    </div>
</body>
</html>
