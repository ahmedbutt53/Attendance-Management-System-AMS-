<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task details | AMS Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <style>
        :root {
            --bg-dark: #09090b;
            --card-bg: rgba(20, 20, 25, 0.6);
            --border-color: rgba(255, 255, 255, 0.08);
            --text-primary: #f4f4f5;
            --text-secondary: #a1a1aa;
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --primary-glow: rgba(99, 102, 241, 0.2);
            --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            --font-outfit: 'Outfit', sans-serif;
            --input-bg: rgba(20, 20, 25, 0.9);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-outfit);
            background-color: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .bg-blobs {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(140px);
            opacity: 0.15;
        }

        .blob-1 {
            width: 600px;
            height: 600px;
            background: #4f46e5;
            top: -10%;
            right: -10%;
        }

        .blob-2 {
            width: 500px;
            height: 500px;
            background: #a855f7;
            bottom: -10%;
            left: -10%;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: rgba(15, 15, 20, 0.7);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .nav-logo svg {
            color: #6366f1;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateX(-2px);
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .layout-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 30px;
        }

        @media (max-width: 992px) {
            .layout-grid {
                grid-template-columns: 1fr;
            }
        }

        .panel {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .panel-title {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
            color: #ffffff;
        }

        .task-detail-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #ffffff;
        }

        .task-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.02);
            padding: 14px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        .meta-item h5 {
            font-size: 11px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .meta-item p {
            font-size: 13.5px;
            font-weight: 600;
        }

        .task-description {
            color: var(--text-primary);
            line-height: 1.6;
            font-size: 15px;
        }

        .task-description p { margin-bottom: 12px; }
        .task-description ul, .task-description ol { margin-left: 20px; margin-bottom: 12px; }

        /* Banner for reviews */
        .status-banner {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14.5px;
            line-height: 1.5;
        }

        .status-banner.approved {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .status-banner.rejected {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        .status-banner.submitted {
            background: rgba(99, 102, 241, 0.08);
            border: 1px solid rgba(99, 102, 241, 0.2);
            color: #818cf8;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* CKEditor Custom Styling for Dark Theme */
        .ck-editor__main .ck-content {
            background-color: var(--input-bg) !important;
            border-color: var(--border-color) !important;
            border-bottom-left-radius: 10px !important;
            border-bottom-right-radius: 10px !important;
            color: var(--text-primary) !important;
            min-height: 250px;
            font-family: var(--font-outfit) !important;
        }

        .ck-toolbar {
            background-color: #18181b !important;
            border-color: var(--border-color) !important;
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
        }

        .ck.ck-button {
            color: var(--text-primary) !important;
        }

        .ck-icon {
            color: var(--text-primary) !important;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-family: var(--font-outfit);
            font-size: 15.5px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px var(--primary-glow);
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        .submitted-response-box {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            line-height: 1.6;
            color: var(--text-primary);
        }

        .error-message {
            color: #f87171;
            font-size: 13px;
            margin-top: 6px;
        }
    </style>
</head>
<body>

    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <!-- Student Navbar -->
    <header class="navbar">
        <div class="nav-logo">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 28px; height: 28px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
            </svg>
            <span>AMS Portal</span>
        </div>
        <a href="{{ route('tasks.index') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to List
        </a>
    </header>

    <main class="container">
        
        <!-- Status Banners -->
        @if($response)
            @if($response->status === 'approved')
                <div class="status-banner approved">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 24px; height: 24px; flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <div style="font-weight: 700;">Task Approved</div>
                        <div style="font-size: 13.5px; margin-top: 2px;">Your submission was approved on {{ $response->reviewed_at ? $response->reviewed_at->format('M d, Y') : 'N/A' }}.</div>
                        @if($response->feedback)
                            <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid rgba(16, 185, 129, 0.2); font-weight: 500;">
                                Feedback: <span style="font-style: italic; color: var(--text-primary);">{{ $response->feedback }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($response->status === 'rejected')
                <div class="status-banner rejected">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 24px; height: 24px; flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <div>
                        <div style="font-weight: 700;">Revision Required</div>
                        <div style="font-size: 13.5px; margin-top: 2px;">The administrator requested changes. Please adjust your response and re-submit.</div>
                        @if($response->feedback)
                            <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid rgba(239, 68, 68, 0.2); font-weight: 500;">
                                Administrator Feedback: <span style="font-style: italic; color: var(--text-primary);">{{ $response->feedback }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($response->status === 'submitted')
                <div class="status-banner submitted">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 24px; height: 24px; flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <div style="font-weight: 700;">Pending Review</div>
                        <div style="font-size: 13.5px; margin-top: 2px;">Your submission has been received and is currently awaiting review by an administrator.</div>
                    </div>
                </div>
            @endif
        @endif

        <div class="layout-grid">
            
            <!-- Left: Task details -->
            <div class="panel">
                <div class="panel-title">Task Details</div>
                <h3 class="task-detail-title">{{ $task->title }}</h3>

                <div class="task-meta">
                    <div class="meta-item">
                        <h5>Due Date</h5>
                        <p style="color: {{ ($task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && (!$response || $response->status !== 'approved')) ? '#ef4444' : 'var(--text-primary)' }}">
                            {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'No deadline' }}
                        </p>
                    </div>
                    <div class="meta-item" style="margin-left: auto;">
                        <h5>Assigned By</h5>
                        <p>{{ $task->assignedBy->name }}</p>
                    </div>
                </div>

                <div class="task-description">
                    {!! $task->description !!}
                </div>
            </div>

            <!-- Right: Submission Form or View Response -->
            <div class="panel">
                @if(!$response || $response->status === 'rejected')
                    <div class="panel-title">Submit Response</div>
                    
                    <form action="{{ route('tasks.submit', $task->id) }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label class="form-label" for="editor">Your Work / Response</label>
                            <textarea name="response" id="editor" placeholder="Write your response, notes or links here...">{{ old('response', $response ? $response->response : '') }}</textarea>
                            @error('response')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-submit">
                            {{ $response ? 'Re-submit Response' : 'Submit Response' }}
                        </button>
                    </form>
                @else
                    <div class="panel-title">Your Submitted Work</div>
                    
                    <div class="submitted-response-box">
                        {!! $response->response !!}
                    </div>
                @endif
            </div>

        </div>
    </main>

    @if(!$response || $response->status === 'rejected')
    <script>
        // Initialize CKEditor 5
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    @endif
</body>
</html>
