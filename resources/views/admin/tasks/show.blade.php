<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details & Review | Admin Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 12px;
            color: #10b981;
            margin-bottom: 24px;
            font-size: 14.5px;
        }

        .layout-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
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
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
            color: #ffffff;
        }

        /* Task Details styling */
        .task-detail-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #ffffff;
        }

        .task-meta-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
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
            font-size: 14px;
            font-weight: 600;
        }

        .task-description {
            color: var(--text-primary);
            line-height: 1.6;
            font-size: 15px;
        }

        /* Description HTML Content Adjustments */
        .task-description p { margin-bottom: 12px; }
        .task-description ul, .task-description ol { margin-left: 20px; margin-bottom: 12px; }
        .task-description blockquote {
            border-left: 3px solid #6366f1;
            padding-left: 14px;
            font-style: italic;
            color: var(--text-secondary);
            margin: 16px 0;
        }

        /* Submission List Table */
        .student-list-table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-list-table th {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            text-align: left;
        }

        .student-list-table td {
            padding: 14px 12px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }

        .student-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .student-email {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 600;
        }

        .status-pill.pending {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-secondary);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .status-pill.submitted {
            background: rgba(99, 102, 241, 0.1);
            color: #818cf8;
            border: 1px solid rgba(99, 102, 241, 0.2);
        }

        .status-pill.approved {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-pill.rejected {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .btn-action-small {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 10px;
            background: rgba(99, 102, 241, 0.1);
            color: #818cf8;
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 6px;
            font-family: var(--font-outfit);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-action-small:hover {
            background: rgba(99, 102, 241, 0.2);
            transform: translateY(-1px);
        }

        /* Review Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 200;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: #121214;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            width: 90%;
            max-width: 650px;
            max-height: 85vh;
            overflow-y: auto;
            padding: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .close-modal {
            position: absolute;
            top: 18px;
            right: 18px;
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close-modal:hover {
            color: #ffffff;
        }

        .modal-header {
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
        }

        .modal-header h3 {
            font-size: 20px;
            font-weight: 700;
        }

        .response-container {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;
            max-height: 200px;
            overflow-y: auto;
            color: var(--text-primary);
            font-size: 14.5px;
            line-height: 1.6;
        }

        /* Review Form */
        .review-option {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .review-radio {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .review-radio input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: #6366f1;
            cursor: pointer;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-textarea {
            width: 100%;
            height: 100px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: var(--font-outfit);
            font-size: 14px;
            outline: none;
            resize: none;
            margin-bottom: 20px;
            transition: border-color 0.3s;
        }

        .form-textarea:focus {
            border-color: #6366f1;
        }

        .btn-submit-review {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-family: var(--font-outfit);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit-review.approve {
            background: var(--success-gradient);
            color: #ffffff;
        }

        .btn-submit-review.reject {
            background: var(--danger-gradient);
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <!-- Admin Navbar -->
    <header class="navbar">
        <div class="nav-logo">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 28px; height: 28px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
            </svg>
            <span>AMS Admin Portal</span>
        </div>
        <a href="{{ route('admin.tasks.index') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to List
        </a>
    </header>

    <main class="container">
        <!-- Toast Notification -->
        @if(session('success'))
            <div class="toast">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="layout-grid">
            
            <!-- Left: Task Details -->
            <div class="panel">
                <div class="panel-title">Task Overview</div>
                
                <h3 class="task-detail-title">{{ $task->title }}</h3>
                
                <div class="task-meta-grid">
                    <div class="meta-item">
                        <h5>Due Date</h5>
                        <p style="color: {{ ($task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast()) ? '#ef4444' : 'var(--text-primary)' }}">
                            {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'No deadline' }}
                        </p>
                    </div>
                    <div class="meta-item">
                        <h5>Assigned On</h5>
                        <p>{{ $task->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="task-description">
                    {!! $task->description !!}
                </div>
            </div>

            <!-- Right: Submissions Directory -->
            <div class="panel">
                <div class="panel-title">Student Submissions</div>

                <table class="student-list-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($task->users as $student)
                            @php
                                $resp = $responses->get($student->id);
                                $status = $resp ? $resp->status : 'pending';
                            @endphp
                            <tr>
                                <td>
                                    <div class="student-name">{{ $student->name }}</div>
                                    <div class="student-email">{{ $student->email }}</div>
                                </td>
                                <td>
                                    <span class="status-pill {{ $status }}">
                                        {{ $status === 'pending' ? 'Pending submission' : $status }}
                                    </span>
                                </td>
                                <td>
                                    @if($status !== 'pending')
                                        <button class="btn-action-small" onclick="openReviewModal('{{ $student->name }}', '{{ $resp->id }}', `{!! addslashes(str_replace(["\r", "\n"], '', $resp->response)) !!}`, '{{ $resp->status }}', '{{ addslashes($resp->feedback) }}')">
                                            Review Submission
                                        </button>
                                    @else
                                        <span style="color: var(--text-secondary); font-size: 12px; font-weight: 500;">No actions</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <!-- Review Modal -->
    <div id="reviewModal" class="modal">
        <div class="modal-content">
            <button class="close-modal" onclick="closeReviewModal()">&times;</button>
            <div class="modal-header">
                <h3>Review Submission</h3>
                <p style="color: var(--text-secondary); font-size: 13.5px; margin-top: 4px;" id="student-modal-name"></p>
            </div>

            <label class="form-label">Submitted Response</label>
            <div class="response-container" id="response-content"></div>

            <form id="reviewForm" action="" method="POST">
                @csrf
                
                <label class="form-label">Review Status</label>
                <div class="review-option">
                    <label class="review-radio" onclick="setSubmitButtonClass('approve')">
                        <input type="radio" name="status" value="approved" id="status-approve" checked>
                        <span style="color: #10b981; font-weight: 600;">Approve Submission</span>
                    </label>
                    <label class="review-radio" onclick="setSubmitButtonClass('reject')">
                        <input type="radio" name="status" value="rejected" id="status-reject">
                        <span style="color: #ef4444; font-weight: 600;">Reject (Needs changes)</span>
                    </label>
                </div>

                <label class="form-label" for="feedback">Review Comments / Feedback</label>
                <textarea id="feedback" name="feedback" class="form-textarea" placeholder="Add guidance, feedback or approval notes for the student..."></textarea>

                <button type="submit" class="btn-submit-review approve" id="btn-submit-review">Submit Review</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('reviewModal');
        
        function openReviewModal(studentName, responseId, responseHtml, status, feedback) {
            document.getElementById('student-modal-name').innerText = "Student: " + studentName;
            document.getElementById('response-content').innerHTML = responseHtml;
            document.getElementById('feedback').value = feedback;

            // Set radio value
            if (status === 'rejected') {
                document.getElementById('status-reject').checked = true;
                setSubmitButtonClass('reject');
            } else {
                document.getElementById('status-approve').checked = true;
                setSubmitButtonClass('approve');
            }

            // Set Form action
            document.getElementById('reviewForm').action = "/admin/task-responses/" + responseId + "/review";

            modal.style.display = 'flex';
        }

        function closeReviewModal() {
            modal.style.display = 'none';
        }

        function setSubmitButtonClass(type) {
            const btn = document.getElementById('btn-submit-review');
            if (type === 'reject') {
                btn.className = 'btn-submit-review reject';
                btn.innerText = 'Reject & Request Correction';
            } else {
                btn.className = 'btn-submit-review approve';
                btn.innerText = 'Approve Submission';
            }
        }

        // Close on overlay click
        window.onclick = function(event) {
            if (event.target == modal) {
                closeReviewModal();
            }
        }
    </script>
</body>
</html>
