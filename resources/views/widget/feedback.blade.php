<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Форма обратной связи</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Reset and Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            line-height: 1.6;
            color: #333;
            background: transparent;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Widget Container */
        .widget-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header */
        .widget-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 12px 12px 0 0;
            text-align: center;
        }

        .widget-icon {
            font-size: 40px;
            margin-bottom: 12px;
            display: block;
        }

        .widget-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .widget-subtitle {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 400;
        }

        /* Form Container */
        .widget-body {
            background: white;
            padding: 30px;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2d3748;
            font-size: 14px;
        }

        .form-label i {
            color: #718096;
            width: 16px;
            text-align: center;
        }

        .required::after {
            content: "*";
            color: #e53e3e;
            margin-left: 4px;
        }

        /* Input Fields */
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f7fafc;
        }

        .form-input:focus {
            outline: none;
            border-color: #4299e1;
            background: white;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
        }

        .form-input:hover {
            border-color: #cbd5e0;
        }

        .form-input.error {
            border-color: #fc8181;
            background: #fff5f5;
        }

        .form-input.valid {
            border-color: #48bb78;
            background: #f0fff4;
        }

        /* Phone Input */
        .phone-input-wrapper {
            position: relative;
        }

        .country-code {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
            font-weight: 600;
            pointer-events: none;
            user-select: none;
        }

        .phone-input {
            padding-left: 40px;
            font-family: 'SF Mono', Monaco, 'Courier New', monospace;
        }

        .phone-preview {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 6px;
            font-size: 12px;
        }

        .preview-label {
            color: #718096;
        }

        .e164-preview {
            font-family: 'SF Mono', Monaco, 'Courier New', monospace;
            background: #edf2f7;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 500;
            color: #2d3748;
        }

        .e164-preview.valid {
            background: #c6f6d5;
            color: #22543d;
        }

        .e164-preview.invalid {
            background: #fed7d7;
            color: #742a2a;
        }

        /* Error Messages */
        .error-message {
            color: #e53e3e;
            font-size: 13px;
            margin-top: 6px;
            display: none;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }

        .error-message.show {
            display: flex;
        }

        .error-icon {
            font-size: 14px;
        }

        /* File Upload */
        .file-upload {
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f7fafc;
        }

        .file-upload:hover {
            border-color: #4299e1;
            background: #ebf8ff;
        }

        .upload-icon {
            font-size: 28px;
            color: #718096;
            margin-bottom: 10px;
        }

        .upload-text {
            color: #4a5568;
            margin-bottom: 6px;
            font-weight: 500;
        }

        .upload-hint {
            font-size: 12px;
            color: #a0aec0;
        }

        .dragover {
            border-color: #4299e1 !important;
            background: #ebf8ff !important;
        }

        /* File List */
        .file-list {
            margin-top: 10px;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background: #edf2f7;
            border-radius: 6px;
            margin-bottom: 6px;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-icon {
            color: #718096;
        }

        .file-name {
            font-weight: 500;
            color: #2d3748;
            font-size: 14px;
        }

        .file-size {
            font-size: 12px;
            color: #718096;
        }

        .file-remove {
            background: #fc8181;
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .file-remove:hover {
            background: #f56565;
            transform: scale(1.1);
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .loading-spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Alerts */
        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            animation: slideDown 0.3s ease;
            border-left: 4px solid;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert.success {
            background: #c6f6d5;
            color: #22543d;
            border-left-color: #48bb78;
            display: block !important;
        }

        .alert.error {
            background: #fed7d7;
            color: #742a2a;
            border-left-color: #f56565;
            display: block !important;
        }

        .alert-icon {
            margin-right: 8px;
            font-size: 16px;
        }

        /* Success Overlay */
        .success-overlay {
            text-align: center;
            padding: 30px 20px;
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .success-overlay.visible {
            display: block;
        }

        .success-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #48bb78, #38a169);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 24px;
        }

        .success-title {
            font-size: 22px;
            font-weight: 700;
            color: #22543d;
            margin-bottom: 10px;
        }

        .success-message {
            color: #718096;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .reset-btn {
            background: #4299e1;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .reset-btn:hover {
            background: #3182ce;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(49, 130, 206, 0.3);
        }

        /* Footer */
        .widget-footer {
            padding: 15px 30px;
            background: #f7fafc;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            border-radius: 0 0 12px 12px;
        }

        .footer-text {
            color: #718096;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .secure-icon {
            color: #48bb78;
        }

        /* Character Counter */
        .character-count {
            text-align: right;
            font-size: 12px;
            color: #718096;
            margin-top: 4px;
        }

        /* Utility Classes */
        .hidden {
            display: none !important;
        }

        .visible {
            display: block !important;
        }

        /* Responsive */
        @media (max-width: 600px) {
            body {
                padding: 15px;
            }

            .widget-header {
                padding: 20px;
            }

            .widget-body {
                padding: 20px;
            }

            .form-input {
                padding: 11px 14px;
                font-size: 14px;
            }

            .submit-btn {
                padding: 13px;
            }
        }
    </style>
</head>
<body>
<div class="widget-container">
    <!-- Header -->
    <div class="widget-header">
        <span class="widget-icon">📞</span>
        <h1 class="widget-title">Обратная связь</h1>
        <p class="widget-subtitle">Мы ответим вам в течение 30 минут</p>
    </div>

    <!-- Body with Form -->
    <div class="widget-body">
        <!-- Alert Container -->
        <div id="alert" class="alert hidden"></div>

        <!-- Form -->
        <form id="feedbackForm" enctype="multipart/form-data">
            <!-- Name Field -->
            <div class="form-group">
                <label class="form-label required" for="name">
                    <i class="fas fa-user"></i> Имя
                </label>
                <input type="text" id="name" name="name" class="form-input"
                       placeholder="Введите ваше имя" required>
                <div class="error-message" id="name-error">
                    <i class="fas fa-exclamation-circle error-icon"></i>
                    <span class="error-text"></span>
                </div>
            </div>

            <!-- Phone Field -->
            <div class="form-group">
                <label class="form-label required" for="phone">
                    <i class="fas fa-phone"></i> Телефон
                </label>
                <div class="phone-input-wrapper">
                    <span class="country-code">+7</span>
                    <input type="tel" id="phone" name="phone" class="form-input phone-input"
                           placeholder="9991234567" required
                           pattern="\+7\d{10}"
                           title="Формат: +7XXXXXXXXXX">
                </div>
                <div class="phone-preview">
                    <span class="preview-label">Формат E.164:</span>
                    <span class="e164-preview" id="e164Preview">+79991234567</span>
                </div>
                <div class="error-message" id="phone-error">
                    <i class="fas fa-exclamation-circle error-icon"></i>
                    <span class="error-text"></span>
                </div>
            </div>

            <!-- Email Field -->
            <div class="form-group">
                <label class="form-label" for="email">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input type="email" id="email" name="email" class="form-input"
                       placeholder="example@domain.com">
                <div class="error-message" id="email-error">
                    <i class="fas fa-exclamation-circle error-icon"></i>
                    <span class="error-text"></span>
                </div>
            </div>

            <!-- Subject Field -->
            <div class="form-group">
                <label class="form-label required" for="subject">
                    <i class="fas fa-tag"></i> Тема
                </label>
                <input type="text" id="subject" name="subject" class="form-input"
                       placeholder="Тема обращения" required>
                <div class="error-message" id="subject-error">
                    <i class="fas fa-exclamation-circle error-icon"></i>
                    <span class="error-text"></span>
                </div>
            </div>

            <!-- Message Field -->
            <div class="form-group">
                <label class="form-label required" for="message">
                    <i class="fas fa-comment"></i> Сообщение
                </label>
                <textarea id="message" name="message" class="form-input"
                          rows="4" placeholder="Опишите вашу проблему или вопрос"
                          required maxlength="2000"></textarea>
                <div class="error-message" id="message-error">
                    <i class="fas fa-exclamation-circle error-icon"></i>
                    <span class="error-text"></span>
                </div>
                <div class="character-count" id="charCount">0/2000 символов</div>
            </div>

            <!-- File Upload -->
            <div class="form-group">
                <label class="form-label" for="attachments">
                    <i class="fas fa-paperclip"></i> Прикрепленные файлы
                </label>
                <div class="file-upload" id="fileUploadArea">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text">
                        Нажмите или перетащите файлы сюда
                    </div>
                    <div class="upload-hint">
                        Максимум 5 файлов, до 10MB каждый<br>
                        Разрешенные форматы: JPG, JPEG, PNG, GIF, PDF, DOC, DOCX, TXT, ZIP
                    </div>
                </div>
                <input type="file" id="attachments" name="attachments[]"
                       multiple style="display: none;"
                       accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.zip">
                <div class="file-list" id="fileList"></div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="submit-btn" id="submitBtn">
                <i class="fas fa-paper-plane btn-icon"></i>
                <span id="btnText">Отправить заявку</span>
                <div id="btnLoading" class="hidden">
                    <span class="loading-spinner"></span>
                    Отправка...
                </div>
            </button>
        </form>

        <!-- Success Overlay -->
        <div id="successOverlay" class="success-overlay hidden">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h2 class="success-title">Заявка отправлена!</h2>
            <p class="success-message">
                Спасибо за обращение. Наш менеджер свяжется с вами<br>
                в течение 30 минут по указанному телефону.
            </p>
            <button type="button" id="resetFormBtn" class="reset-btn">
                <i class="fas fa-plus"></i> Новая заявка
            </button>
        </div>
    </div>

    <!-- Footer -->
    <div class="widget-footer">
        <div class="footer-text">
            <span class="secure-icon"><i class="fas fa-shield-alt"></i></span>
            Ваши данные защищены
        </div>
    </div>
</div>

<script>
    class E164PhoneInput {
        constructor() {
            this.phoneInput = document.getElementById('phone');
            this.e164Preview = document.getElementById('e164Preview');

            if (this.phoneInput && this.e164Preview) {
                this.init();
            }
        }

        init() {
            this.bindEvents();
            this.updatePreview();
        }

        bindEvents() {
            if (!this.phoneInput) return;

            this.phoneInput.addEventListener('input', (e) => {
                this.formatInput(e.target);
                this.updatePreview();
            });

            this.phoneInput.addEventListener('blur', () => {
                this.validate();
            });

            this.phoneInput.addEventListener('keydown', (e) => {
                this.handleKeyDown(e);
            });
        }

        formatInput(input) {
            let value = input.value.replace(/[^\d+]/g, '');

            if (!value.startsWith('+7')) {
                value = '+7' + value.replace(/^\+?7?/, '');
            }

            if (value.length > 12) {
                value = value.substring(0, 12);
            }

            input.value = value;
        }

        updatePreview() {
            if (!this.e164Preview) return;

            const value = this.phoneInput ? this.phoneInput.value : '';

            if (!value) {
                this.e164Preview.textContent = '+79991234567';
                this.e164Preview.className = 'e164-preview';
                return;
            }

            this.e164Preview.textContent = value;

            if (this.isValid(value)) {
                this.e164Preview.className = 'e164-preview valid';
                if (this.phoneInput) {
                    this.phoneInput.classList.add('valid');
                    this.phoneInput.classList.remove('error');
                }
            } else {
                this.e164Preview.className = 'e164-preview invalid';
                if (this.phoneInput) {
                    this.phoneInput.classList.remove('valid');
                }
            }
        }

        isValid(phone) {
            return /^\+7\d{10}$/.test(phone);
        }

        validate() {
            if (!this.phoneInput) return false;

            const value = this.phoneInput.value;

            if (!value) {
                this.showError('Телефон обязателен для заполнения');
                return false;
            }

            if (!this.isValid(value)) {
                this.showError('Введите номер в формате: +7XXXXXXXXXX');
                return false;
            }

            const digits = value.substring(2);

            if (digits.length !== 10) {
                this.showError('Номер должен содержать 10 цифр');
                return false;
            }

            if (digits.charAt(0) !== '9') {
                this.showError('Мобильный номер должен начинаться с 9');
                return false;
            }

            this.clearError();
            return true;
        }

        showError(message) {
            const errorElement = document.getElementById('phone-error');
            if (errorElement) {
                errorElement.querySelector('.error-text').textContent = message;
                errorElement.classList.add('show');
            }

            if (this.phoneInput) {
                this.phoneInput.classList.add('error');
                this.phoneInput.classList.remove('valid');
            }
        }

        clearError() {
            const errorElement = document.getElementById('phone-error');
            if (errorElement) {
                errorElement.classList.remove('show');
            }

            if (this.phoneInput) {
                this.phoneInput.classList.remove('error');
            }
        }

        handleKeyDown(e) {
            if ([46, 8, 9, 27, 13].includes(e.keyCode) ||
                (e.ctrlKey && [65, 67, 86, 88].includes(e.keyCode)) ||
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }

            if (e.key === '+' && this.phoneInput.selectionStart === 0) {
                return;
            }

            if ((e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        }

        getCleanPhone() {
            return this.phoneInput ? this.phoneInput.value : '';
        }

        reset() {
            if (this.phoneInput) {
                this.phoneInput.value = '';
                this.phoneInput.classList.remove('valid', 'error');
            }
            this.updatePreview();
            this.clearError();
        }
    }

    class FeedbackWidget {
        constructor() {
            this.form = document.getElementById('feedbackForm');
            this.alertDiv = document.getElementById('alert');
            this.submitBtn = document.getElementById('submitBtn');
            this.btnText = document.getElementById('btnText');
            this.btnLoading = document.getElementById('btnLoading');
            this.successOverlay = document.getElementById('successOverlay');
            this.resetFormBtn = document.getElementById('resetFormBtn');
            this.fileInput = document.getElementById('attachments');
            this.fileUploadArea = document.getElementById('fileUploadArea');
            this.fileList = document.getElementById('fileList');
            this.charCount = document.getElementById('charCount');
            this.messageInput = document.getElementById('message');

            if (!this.form) {
                console.error('Form not found');
                return;
            }

            this.phoneHandler = new E164PhoneInput();
            this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            this.files = [];
            this.allowedTypes = [
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/gif',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'text/plain',
                'application/zip'
            ];

            this.init();
        }

        init() {
            this.bindEvents();
            this.initCharacterCounter();
        }

        bindEvents() {
            // Form submission
            this.form.addEventListener('submit', (e) => this.handleSubmit(e));

            // Reset form button
            if (this.resetFormBtn) {
                this.resetFormBtn.addEventListener('click', () => this.resetForm());
            }

            // File upload handling
            if (this.fileInput) {
                this.fileInput.addEventListener('change', (e) => this.handleFileSelect(e));
            }

            if (this.fileUploadArea) {
                this.fileUploadArea.addEventListener('click', () => {
                    if (this.fileInput) this.fileInput.click();
                });

                // Drag and drop
                this.fileUploadArea.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.fileUploadArea.classList.add('dragover');
                });

                this.fileUploadArea.addEventListener('dragleave', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.fileUploadArea.classList.remove('dragover');
                });

                this.fileUploadArea.addEventListener('drop', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.fileUploadArea.classList.remove('dragover');

                    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                        if (this.fileInput) {
                            this.fileInput.files = e.dataTransfer.files;
                            this.handleFileSelect({ target: this.fileInput });
                        }
                    }
                });
            }

            // Field validation on blur
            document.querySelectorAll('.form-input').forEach(input => {
                if (input.name !== 'phone') {
                    input.addEventListener('blur', (e) => this.validateField(e.target));
                    input.addEventListener('input', (e) => this.clearFieldError(e.target));
                }
            });
        }

        initCharacterCounter() {
            if (this.messageInput && this.charCount) {
                this.messageInput.addEventListener('input', (e) => {
                    const length = e.target.value.length;
                    this.charCount.textContent = `${length}/2000 символов`;

                    if (length > 1800) {
                        this.charCount.style.color = '#ed8936';
                    } else if (length > 1950) {
                        this.charCount.style.color = '#f56565';
                    } else {
                        this.charCount.style.color = '#718096';
                    }
                });
            }
        }

        handleFileSelect(e) {
            const newFiles = Array.from(e.target.files);

            // Check file limits
            if (this.files.length + newFiles.length > 5) {
                this.showAlert('Максимум 5 файлов', 'error');
                e.target.value = '';
                return;
            }

            newFiles.forEach(file => {
                if (this.validateFile(file)) {
                    this.files.push(file);
                }
            });

            this.updateFileList();
            e.target.value = '';
        }

        validateFile(file) {
            const maxSize = 10 * 1024 * 1024; // 10MB
            const fileExtension = file.name.split('.').pop().toLowerCase();
            const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'zip'];

            if (file.size > maxSize) {
                this.showAlert(`Файл "${file.name}" превышает 10MB`, 'error');
                return false;
            }

            if (!this.allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
                this.showAlert(`Файл "${file.name}" имеет недопустимый формат. Разрешены: JPG, JPEG, PNG, GIF, PDF, DOC, DOCX, TXT, ZIP`, 'error');
                return false;
            }

            return true;
        }

        updateFileList() {
            if (!this.fileList) return;

            this.fileList.innerHTML = '';

            if (this.files.length === 0) {
                return;
            }

            this.files.forEach((file, index) => {
                const div = document.createElement('div');
                div.className = 'file-item';
                div.innerHTML = `
                    <div class="file-info">
                        <i class="fas fa-file file-icon"></i>
                        <div>
                            <div class="file-name">${file.name}</div>
                            <div class="file-size">${this.formatFileSize(file.size)}</div>
                        </div>
                    </div>
                    <button type="button" class="file-remove" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                this.fileList.appendChild(div);
            });

            // Add event listeners to remove buttons
            this.fileList.querySelectorAll('.file-remove').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const index = parseInt(e.currentTarget.getAttribute('data-index'));
                    this.removeFile(index);
                });
            });
        }

        removeFile(index) {
            this.files.splice(index, 1);
            this.updateFileList();
        }

        formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' Б';
            else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' КБ';
            else return (bytes / 1048576).toFixed(1) + ' МБ';
        }

        validateField(field) {
            const value = field.value.trim();
            const fieldName = field.name;

            switch(fieldName) {
                case 'name':
                    if (!value) {
                        this.showFieldError(field, 'Имя обязательно для заполнения');
                        return false;
                    }
                    if (value.length < 2) {
                        this.showFieldError(field, 'Имя должно содержать минимум 2 символа');
                        return false;
                    }
                    break;

                case 'email':
                    if (value && !this.isValidEmail(value)) {
                        this.showFieldError(field, 'Введите корректный email');
                        return false;
                    }
                    break;

                case 'subject':
                    if (!value) {
                        this.showFieldError(field, 'Тема обязательна для заполнения');
                        return false;
                    }
                    if (value.length < 3) {
                        this.showFieldError(field, 'Тема должна содержать минимум 3 символа');
                        return false;
                    }
                    break;

                case 'message':
                    if (!value) {
                        this.showFieldError(field, 'Сообщение обязательно для заполнения');
                        return false;
                    }
                    if (value.length < 10) {
                        this.showFieldError(field, 'Сообщение должно содержать минимум 10 символов');
                        return false;
                    }
                    break;
            }

            this.clearFieldError(field);
            return true;
        }

        isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        showFieldError(field, message) {
            field.classList.add('error');
            const errorElement = document.getElementById(`${field.name}-error`);
            if (errorElement) {
                errorElement.querySelector('.error-text').textContent = message;
                errorElement.classList.add('show');
            }
        }

        clearFieldError(field) {
            field.classList.remove('error');
            const errorElement = document.getElementById(`${field.name}-error`);
            if (errorElement) {
                errorElement.classList.remove('show');
            }
        }

        clearAllErrors() {
            document.querySelectorAll('.form-input').forEach(input => {
                this.clearFieldError(input);
            });
        }

        showAlert(message, type = 'error') {
            if (!this.alertDiv) return;

            this.alertDiv.className = `alert ${type}`;
            this.alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} alert-icon"></i>
                ${message}
            `;
            this.alertDiv.style.display = 'block';

            if (type === 'success') {
                setTimeout(() => {
                    this.alertDiv.style.display = 'none';
                }, 5000);
            }
        }

        async handleSubmit(e) {
            e.preventDefault();
            this.clearAllErrors();

            // Validate all fields
            let isValid = true;
            const fields = ['name', 'subject', 'message'];

            fields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field && !this.validateField(field)) {
                    isValid = false;
                }
            });

            // Validate phone
            if (this.phoneHandler && !this.phoneHandler.validate()) {
                isValid = false;
            }

            // Validate email if provided
            const emailField = document.getElementById('email');
            if (emailField && emailField.value.trim() && !this.validateField(emailField)) {
                isValid = false;
            }

            if (!isValid) {
                this.showAlert('Пожалуйста, исправьте ошибки в форме', 'error');
                return;
            }

            // Prepare form data
            const formData = new FormData();
            formData.append('name', document.getElementById('name').value.trim());
            formData.append('phone', this.phoneHandler.getCleanPhone());
            formData.append('email', document.getElementById('email').value.trim() || '');
            formData.append('subject', document.getElementById('subject').value.trim());
            formData.append('message', document.getElementById('message').value.trim());

            // Check file types before sending
            const invalidFiles = [];
            this.files.forEach((file, index) => {
                const fileExtension = file.name.split('.').pop().toLowerCase();
                const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'zip'];

                if (!this.allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
                    invalidFiles.push(file.name);
                } else {
                    formData.append('attachments[]', file);
                }
            });

            if (invalidFiles.length > 0) {
                this.showAlert(`Недопустимые форматы файлов: ${invalidFiles.join(', ')}. Разрешены: JPG, JPEG, PNG, GIF, PDF, DOC, DOCX, TXT, ZIP`, 'error');
                return;
            }

            // Show loading state
            this.showLoading(true);

            try {
                const response = await this.sendRequest(formData);

                if (response.success) {
                    this.showSuccess();
                } else {
                    throw new Error(response.message || 'Ошибка при отправке');
                }
            } catch (error) {
                this.handleError(error);
            } finally {
                this.showLoading(false);
            }
        }

        async sendRequest(formData) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();

                xhr.open('POST', '/api/tickets', true);
                xhr.setRequestHeader('X-CSRF-TOKEN', this.csrfToken);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const data = JSON.parse(xhr.responseText);
                            resolve(data);
                        } catch (e) {
                            reject(new Error('Ошибка обработки ответа сервера'));
                        }
                    } else {
                        try {
                            const errorData = JSON.parse(xhr.responseText);
                            let errorMessage = errorData.message || `Ошибка ${xhr.status}`;

                            // Handle specific 422 error from your screenshot
                            if (xhr.status === 422 && errorData.errors) {
                                if (errorData.errors.attachments) {
                                    errorMessage = errorData.errors.attachments.join(', ');
                                } else {
                                    errorMessage = Object.values(errorData.errors).flat().join(', ');
                                }
                            }

                            reject(new Error(errorMessage));
                        } catch (e) {
                            reject(new Error(`HTTP ошибка ${xhr.status}`));
                        }
                    }
                };

                xhr.onerror = function() {
                    reject(new Error('Ошибка сети. Проверьте подключение к интернету'));
                };

                xhr.ontimeout = function() {
                    reject(new Error('Время ожидания ответа истекло'));
                };

                xhr.timeout = 30000; // 30 seconds

                xhr.send(formData);
            });
        }

        showLoading(show) {
            if (show) {
                this.btnText.classList.add('hidden');
                this.btnLoading.classList.remove('hidden');
                this.submitBtn.disabled = true;
            } else {
                this.btnText.classList.remove('hidden');
                this.btnLoading.classList.add('hidden');
                this.submitBtn.disabled = false;
            }
        }

        showSuccess() {
            if (this.form) this.form.style.display = 'none';
            if (this.successOverlay) this.successOverlay.style.display = 'block';
            this.showAlert('Заявка успешно отправлена!', 'success');
        }

        handleError(error) {
            console.error('Submission error:', error);

            if (error.message.includes('attachments') || error.message.includes('file') || error.message.includes('type')) {
                this.showAlert(`Ошибка загрузки файлов: ${error.message}`, 'error');
            } else {
                this.showAlert(error.message, 'error');
            }
        }

        resetForm() {
            // Reset form
            if (this.form) {
                this.form.reset();
                this.form.style.display = 'block';
            }

            // Clear files
            this.files = [];
            this.updateFileList();
            if (this.fileInput) {
                this.fileInput.value = '';
            }

            // Clear errors
            this.clearAllErrors();

            // Hide success overlay
            if (this.successOverlay) {
                this.successOverlay.style.display = 'none';
            }

            // Reset phone input
            if (this.phoneHandler) {
                this.phoneHandler.reset();
            }

            // Reset character counter
            if (this.charCount) {
                this.charCount.textContent = '0/2000 символов';
                this.charCount.style.color = '#718096';
            }

            // Hide alert
            if (this.alertDiv) {
                this.alertDiv.style.display = 'none';
            }

            // Focus on name field
            document.getElementById('name')?.focus();
        }
    }

    // Initialize widget when DOM is loaded
    let widget;

    function initWidget() {
        widget = new FeedbackWidget();
        window.widget = widget;
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWidget);
    } else {
        initWidget();
    }
</script>
</body>
</html>
