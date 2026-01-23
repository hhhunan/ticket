<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход | {{ config('app.name', 'Админ-панель') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .login-header i {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .login-header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .login-header p {
            opacity: 0.9;
            font-size: 14px;
            margin: 0;
        }

        .login-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .form-label i {
            margin-right: 8px;
            color: #6b7280;
        }

        .form-control {
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .input-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 0;
            font-size: 16px;
        }

        .password-toggle:hover {
            color: #374151;
        }

        .btn-login {
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: linear-gradient(90deg, #4338ca, #6d28d9);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .form-check {
            margin-top: 15px;
        }

        .form-check-input:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .form-check-label {
            font-size: 14px;
            color: #6b7280;
            cursor: pointer;
        }

        .alert {
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #fef2f2;
            border-color: #fee2e2;
            color: #dc2626;
        }

        .alert-success {
            background-color: #f0fdf4;
            border-color: #dcfce7;
            color: #16a34a;
        }

        .login-footer {
            text-align: center;
            padding: 20px 30px;
            border-top: 1px solid #f3f4f6;
            color: #6b7280;
            font-size: 13px;
        }

        .login-footer a {
            color: #4f46e5;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        /* Анимации */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Темная тема */
        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            }

            .login-card {
                background: #1f2937;
                color: #e5e7eb;
            }

            .form-control {
                background-color: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
            }

            .form-label {
                color: #d1d5db;
            }

            .login-footer {
                border-color: #374151;
                color: #9ca3af;
            }
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-card fade-in">
        <!-- Заголовок -->
        <div class="login-header">
            <i class="fas fa-lock"></i>
            <h1>Административный доступ</h1>
            <p>{{ config('app.name', 'Система управления') }}</p>
        </div>

        <!-- Тело формы -->
        <div class="login-body">
            <!-- Сообщения об ошибках -->
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Форма входа -->
            <form method="POST" action="{{ route('admin.login') }}" id="loginForm">
                @csrf

                <!-- Поле Email -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-envelope"></i> Email адрес
                    </label>
                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="your.email@example.com"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           autofocus>

                    @error('email')
                    <div class="invalid-feedback d-block mt-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Поле Пароль -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-key"></i> Пароль
                    </label>
                    <div class="input-group">
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Введите пароль"
                               required
                               autocomplete="current-password">
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    @error('password')
                    <div class="invalid-feedback d-block mt-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Запомнить меня -->
                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           name="remember"
                           id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Запомнить меня на этом устройстве
                    </label>
                </div>

                <!-- Кнопка входа -->
                <button type="submit" class="btn-login" id="loginButton">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Войти в систему
                </button>
            </form>
        </div>

        <!-- Футер -->
        <div class="login-footer">
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                    <i class="fas fa-question-circle me-1"></i>
                    Забыли пароль?
                </a>
                <span class="mx-2">•</span>
            @endif

            @if(Route::has('register'))
                <a href="{{ route('register') }}">
                    <i class="fas fa-user-plus me-1"></i>
                    Создать аккаунт
                </a>
            @endif

            <div class="mt-3">
                <small>
                    <i class="fas fa-shield-alt me-1"></i>
                    Защищено SSL шифрованием
                </small>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Переключение видимости пароля
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            passwordInput.type = 'password';
            icon.className = 'fas fa-eye';
        }
    });

    // Валидация формы
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const loginButton = document.getElementById('loginButton');

        // Простая валидация
        if (!email || !password) {
            e.preventDefault();
            alert('Пожалуйста, заполните все поля');
            return;
        }

        // Проверка email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Пожалуйста, введите корректный email адрес');
            return;
        }

        // Блокировка кнопки на время отправки
        loginButton.disabled = true;
        loginButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Выполняется вход...';

        // Авторазблокировка через 10 секунд (на случай ошибки)
        setTimeout(() => {
            loginButton.disabled = false;
            loginButton.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i> Войти в систему';
        }, 10000);
    });

    // Автофокус на поле email если оно пустое
    document.addEventListener('DOMContentLoaded', function() {
        const emailField = document.getElementById('email');
        if (!emailField.value) {
            emailField.focus();
        }
    });

    // Обработка нажатия Enter в поле пароля
    document.getElementById('password').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('loginForm').submit();
        }
    });

    // Динамическая проверка email при вводе
    document.getElementById('email').addEventListener('input', function(e) {
        const email = e.target.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email && !emailRegex.test(email)) {
            e.target.style.borderColor = '#ef4444';
        } else {
            e.target.style.borderColor = '';
        }
    });
</script>
</body>
</html>
