<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Age Card</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #ec4899 0%, #f97316 100%);
            padding: 0;
        }
        .card-wrapper {
            width: 1000px;
            background: white;
            border-radius: 40px;
            padding: 60px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header-title {
            font-size: 28px;
            color: #ec4899;
            font-weight: 600;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header-date {
            font-size: 16px;
            color: #9ca3af;
            margin: 8px 0 0 0;
        }
        .age-display {
            background: linear-gradient(135deg, #fdf2f8 0%, #fed7aa 100%);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 40px;
            text-align: center;
            border-left: 8px solid #ec4899;
        }
        .age-years {
            font-size: 80px;
            font-weight: bold;
            color: #1f2937;
            margin: 0;
        }
        .age-unit {
            font-size: 32px;
            color: #6b7280;
            margin: 0;
        }
        .age-detail {
            font-size: 24px;
            color: #374151;
            margin-top: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: #f9fafb;
            padding: 24px;
            border-radius: 16px;
            text-align: center;
        }
        .stat-card:first-child {
            border-left: 4px solid #ec4899;
        }
        .stat-card:nth-child(2) {
            border-left: 4px solid #f97316;
        }
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            margin: 0;
            text-transform: uppercase;
        }
        .stat-value {
            font-size: 36px;
            font-weight: bold;
            color: #1f2937;
            margin: 10px 0 0 0;
        }
        .progress-section {
            margin-bottom: 40px;
        }
        .progress-label {
            font-size: 14px;
            color: #374151;
            font-weight: 600;
            margin-bottom: 12px;
        }
        .progress-bar {
            width: 100%;
            height: 16px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ec4899 0%, #f97316 100%);
            border-radius: 10px;
        }
        .progress-text {
            font-size: 18px;
            color: #1f2937;
            font-weight: bold;
            margin-top: 12px;
        }
        .birthday-banner {
            text-align: center;
            background: #d1fae5;
            border-left: 8px solid #10b981;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 40px;
        }
        .birthday-emoji {
            font-size: 32px;
            margin: 0;
        }
        .birthday-text {
            font-size: 24px;
            font-weight: 600;
            color: #047857;
            margin: 10px 0 0 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            margin-top: 40px;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="card-wrapper">
        {{-- Header --}}
        <div class="header">
            <p class="header-title">My Age</p>
            <p class="header-date">Calculated on {{ now()->format('F j, Y') }}</p>
        </div>

        {{-- Age Display --}}
        <div class="age-display">
            <p class="age-years">{{ $result['years'] }}</p>
            <p class="age-unit">Years</p>
            <p class="age-detail">{{ $result['months'] }} Months, {{ $result['days'] }} Days</p>
        </div>

        {{-- Stats --}}
        <div class="stats-grid">
            <div class="stat-card">
                <p class="stat-label">Total Days</p>
                <p class="stat-value">{{ number_format($result['total_days']) }}</p>
            </div>
            <div class="stat-card">
                <p class="stat-label">Next Birthday</p>
                <p class="stat-value" style="font-size: 20px;">{{ $result['next_birthday_fmt'] }}</p>
            </div>
        </div>

        {{-- Progress Bar --}}
        @if (!$result['is_birthday_today'])
            <div class="progress-section">
                <p class="progress-label">Days Until Next Birthday</p>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ min(100, (365 - $result['days_until_next']) / 365 * 100) }}%"></div>
                </div>
                <p class="progress-text">{{ $result['days_until_next'] }} days left</p>
            </div>
        @else
            <div class="birthday-banner">
                <p class="birthday-emoji">🎉</p>
                <p class="birthday-text">Happy Birthday Today!</p>
            </div>
        @endif

        {{-- Footer --}}
        <p class="footer">ToolsHub Age Calculator • {{ config('app.url') }}</p>
    </div>
</body>
</html>
