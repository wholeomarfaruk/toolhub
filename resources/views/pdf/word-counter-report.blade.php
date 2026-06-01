<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Word Counter Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #1e40af;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 12px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            border-bottom: 2px solid #dbeafe;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .metrics-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .metric-row {
            display: table-row;
        }
        .metric-label {
            display: table-cell;
            padding: 8px;
            width: 50%;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            color: #374151;
        }
        .metric-value {
            display: table-cell;
            padding: 8px;
            width: 50%;
            border-bottom: 1px solid #e5e7eb;
            text-align: right;
            color: #2563eb;
            font-weight: bold;
        }
        .text-preview {
            background-color: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 12px;
            font-size: 12px;
            line-height: 1.6;
            color: #4b5563;
            max-height: 150px;
            overflow: hidden;
            margin: 10px 0;
        }
        .keyword-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 10px 0;
        }
        .keyword-badge {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .density-bar {
            display: table;
            width: 100%;
            margin: 8px 0;
        }
        .density-label {
            display: table-cell;
            width: 30%;
            font-size: 11px;
            font-weight: 600;
            vertical-align: middle;
        }
        .density-bar-bg {
            display: table-cell;
            width: 60%;
            padding: 0 10px;
            vertical-align: middle;
        }
        .density-bar-fill {
            background-color: #3b82f6;
            height: 8px;
            border-radius: 4px;
        }
        .density-value {
            display: table-cell;
            width: 10%;
            text-align: right;
            font-size: 11px;
            font-weight: 600;
            color: #2563eb;
        }
        .footer {
            border-top: 1px solid #d1d5db;
            padding-top: 15px;
            margin-top: 25px;
            font-size: 11px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Word Counter Analysis Report</h1>
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    {{-- Text Preview --}}
    <div class="section">
        <div class="section-title">Analyzed Text</div>
        <div class="text-preview">{{ substr($text, 0, 500) }}{{ strlen($text) > 500 ? '...' : '' }}</div>
    </div>

    {{-- Basic Metrics --}}
    <div class="section">
        <div class="section-title">Basic Metrics</div>
        <div class="metrics-grid">
            <div class="metric-row">
                <div class="metric-label">Words</div>
                <div class="metric-value">{{ $result['words'] }}</div>
            </div>
            <div class="metric-row">
                <div class="metric-label">Characters (with spaces)</div>
                <div class="metric-value">{{ $result['characters'] }}</div>
            </div>
            <div class="metric-row">
                <div class="metric-label">Characters (without spaces)</div>
                <div class="metric-value">{{ $result['characters_no_spaces'] }}</div>
            </div>
            <div class="metric-row">
                <div class="metric-label">Sentences</div>
                <div class="metric-value">{{ $result['sentences'] }}</div>
            </div>
            <div class="metric-row">
                <div class="metric-label">Paragraphs</div>
                <div class="metric-value">{{ $result['paragraphs'] }}</div>
            </div>
        </div>
    </div>

    {{-- Time Estimates --}}
    <div class="section">
        <div class="section-title">Time Estimates</div>
        <div class="metrics-grid">
            <div class="metric-row">
                <div class="metric-label">Reading Time (200 WPM)</div>
                <div class="metric-value">{{ $result['reading_time_minutes'] }} minute{{ $result['reading_time_minutes'] !== 1 ? 's' : '' }}</div>
            </div>
            <div class="metric-row">
                <div class="metric-label">Speaking Time (130 WPM)</div>
                <div class="metric-value">{{ $result['speaking_time_minutes'] }} minute{{ $result['speaking_time_minutes'] !== 1 ? 's' : '' }}</div>
            </div>
        </div>
    </div>

    {{-- Averages --}}
    <div class="section">
        <div class="section-title">Averages</div>
        <div class="metrics-grid">
            <div class="metric-row">
                <div class="metric-label">Average Word Length</div>
                <div class="metric-value">{{ $result['avg_word_length'] }} characters</div>
            </div>
            <div class="metric-row">
                <div class="metric-label">Average Sentence Length</div>
                <div class="metric-value">{{ $result['avg_sentence_length'] }} words</div>
            </div>
        </div>
    </div>

    {{-- Readability Analysis --}}
    @if ($result['readability_score'])
        <div class="section">
            <div class="section-title">Readability Analysis</div>
            <div class="metrics-grid">
                <div class="metric-row">
                    <div class="metric-label">Flesch Reading Ease</div>
                    <div class="metric-value">{{ $result['readability_score']['flesch_reading_ease'] }}/100</div>
                </div>
                <div class="metric-row">
                    <div class="metric-label">Difficulty Level</div>
                    <div class="metric-value">{{ $result['readability_score']['difficulty'] }}</div>
                </div>
                <div class="metric-row">
                    <div class="metric-label">Flesch-Kincaid Grade</div>
                    <div class="metric-value">{{ $result['readability_score']['flesch_kincaid_grade'] }}</div>
                </div>
            </div>
        </div>
    @endif

    {{-- Top Keywords --}}
    @if ($result['top_keywords'] && count($result['top_keywords']) > 0)
        <div class="section">
            <div class="section-title">Top Keywords</div>
            <div class="keyword-list">
                @foreach ($result['top_keywords'] as $keyword)
                    <div class="keyword-badge">{{ ucfirst($keyword) }}</div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Keyword Density --}}
    @if ($result['keyword_density'] && count($result['keyword_density']) > 0)
        <div class="section">
            <div class="section-title">Keyword Density</div>
            @foreach ($result['keyword_density'] as $keyword => $data)
                <div class="density-bar">
                    <div class="density-label">{{ ucfirst($keyword) }}</div>
                    <div class="density-bar-bg">
                        <div class="density-bar-fill" style="width: {{ $data['percentage'] }}%"></div>
                    </div>
                    <div class="density-value">{{ $data['percentage'] }}%</div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="footer">
        <p>ToolHub Word Counter | Generated by {{ config('app.name', 'ToolHub') }}</p>
    </div>
</body>
</html>
