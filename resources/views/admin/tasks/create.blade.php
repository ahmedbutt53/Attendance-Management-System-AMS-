<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign New Task | Admin Portal</title>
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
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .header-section {
            margin-bottom: 30px;
        }

        .header-section h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 6px;
            background: linear-gradient(to right, #ffffff, #a1a1aa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .panel {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: var(--font-outfit);
            font-size: 14.5px;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
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

        .ck.ck-button:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
        }

        .ck-icon {
            color: var(--text-primary) !important;
        }

        /* Checkbox grid with search */
        .student-selector {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: var(--input-bg);
            padding: 16px;
            max-height: 250px;
            overflow-y: auto;
        }

        .search-students {
            margin-bottom: 12px;
        }

        .student-checkbox-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .student-checkbox-item:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        .student-checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #6366f1;
            cursor: pointer;
        }

        .student-checkbox-label {
            font-size: 14px;
            color: var(--text-primary);
            cursor: pointer;
        }

        .student-checkbox-sub {
            font-size: 12px;
            color: var(--text-secondary);
            margin-left: auto;
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

        .error-message {
            color: #f87171;
            font-size: 13px;
            margin-top: 6px;
        }

        .selection-actions {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 12.5px;
        }

        .btn-select-all {
            background: none;
            border: none;
            color: #6366f1;
            font-family: var(--font-outfit);
            font-weight: 600;
            cursor: pointer;
        }

        .btn-select-all:hover {
            text-decoration: underline;
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
        <div class="header-section">
            <h2>Assign New Task</h2>
            <p>Create a task outline and select the students assigned to complete it.</p>
        </div>

        <div class="panel">
            <form action="{{ route('admin.tasks.store') }}" method="POST">
                @csrf

                <!-- Task Title -->
                <div class="form-group">
                    <label class="form-label" for="title">Task Title</label>
                    <input type="text" id="title" name="title" class="form-input" placeholder="e.g. Complete Laravel CRUD Dashboard" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Task Description (CKEditor) -->
                <div class="form-group">
                    <label class="form-label" for="editor">Task Details & Description</label>
                    <textarea name="description" id="editor" placeholder="Write description or instructions here...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Due Date -->
                <div class="form-group">
                    <label class="form-label" for="due_date">Due Date</label>
                    <input type="date" id="due_date" name="due_date" class="form-input" min="{{ date('Y-m-d') }}" value="{{ old('due_date') }}">
                    @error('due_date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Assigned Students -->
                <div class="form-group">
                    <label class="form-label">Assign To Students</label>
                    
                    <input type="text" id="student-search" class="form-input search-students" placeholder="Search students by name...">
                    
                    <div class="selection-actions">
                        <button type="button" class="btn-select-all" onclick="toggleSelectAll(true)">Select All</button>
                        <span style="color: var(--border-color)">|</span>
                        <button type="button" class="btn-select-all" onclick="toggleSelectAll(false)">Deselect All</button>
                    </div>

                    <div class="student-selector" id="student-list">
                        @foreach($students as $student)
                            <label class="student-checkbox-item">
                                <input type="checkbox" name="students[]" value="{{ $student->id }}" {{ (is_array(old('students')) && in_array($student->id, old('students'))) ? 'checked' : '' }}>
                                <span class="student-checkbox-label">{{ $student->name }}</span>
                                <span class="student-checkbox-sub">{{ $student->email }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('students')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">Assign & Publish Task</button>
            </form>
        </div>
    </main>

    <script>
        // Initialize CKEditor 5
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
            })
            .catch(error => {
                console.error(error);
            });

        // Student checklist search functionality
        document.getElementById('student-search').addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.student-checkbox-item');
            
            items.forEach(function(item) {
                const labelName = item.querySelector('.student-checkbox-label').innerText.toLowerCase();
                const labelEmail = item.querySelector('.student-checkbox-sub').innerText.toLowerCase();
                
                if (labelName.includes(query) || labelEmail.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Toggle Select All checkboxes
        function toggleSelectAll(select) {
            const checkboxes = document.querySelectorAll('#student-list input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {
                // Only toggle checkboxes that are currently visible (not filtered out)
                const parent = checkbox.closest('.student-checkbox-item');
                if (parent.style.display !== 'none') {
                    checkbox.checked = select;
                }
            });
        }
    </script>
</body>
</html>
