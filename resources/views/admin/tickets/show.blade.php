@extends('layouts.admin')

@section('title', 'Заявка #' . $ticket->id)

@push('styles')
    <style>
        .ticket-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            color: white;
            padding: 30px;
            margin-bottom: 30px;
        }

        .message-box {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            border-left: 4px solid var(--primary);
            white-space: pre-wrap;
            font-family: 'SF Mono', Monaco, 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
        }

        .file-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            transition: all 0.3s;
            height: 100%;
        }

        .file-card:hover {
            border-color: var(--primary);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .file-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .file-icon.pdf {
            background: #fee2e2;
            color: #dc2626;
        }

        .file-icon.image {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .file-icon.doc {
            background: #f0f9ff;
            color: #0369a1;
        }

        .file-icon.other {
            background: #f3f4f6;
            color: #6b7280;
        }

        .customer-info {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .customer-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            margin: 0 auto 20px;
        }

        .timeline-item {
            position: relative;
            padding-left: 30px;
            margin-bottom: 20px;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary);
        }

        .timeline-item:after {
            content: '';
            position: absolute;
            left: 5px;
            top: 17px;
            bottom: -20px;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-item:last-child:after {
            display: none;
        }
    </style>
@endpush

@section('content')
    <!-- Заголовок -->
    <div class="ticket-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="h3 mb-2">Заявка #{{ $ticket->id }}</h1>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-clock me-1"></i>
                    Создана {{ $ticket->created_at->format('d.m.Y в H:i') }}
                    @if($ticket->reply_date)
                        | Ответ дан {{ $ticket->reply_date->format('d.m.Y в H:i') }}
                    @endif
                </p>
            </div>
            <div class="text-end">
            <span class="status-badge status-{{ $ticket->status->value }} fs-6">
                @switch($ticket->status->value)
                    @case('new')
                        Новая
                        @break
                    @case('in_progress')
                        В работе
                        @break
                    @case('processed')
                        Обработана
                        @break
                @endswitch
            </span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Основная информация -->
        <div class="col-lg-8">
            <!-- Тема и сообщение -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Информация о заявке</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Тема</h6>
                        <h4>{{ $ticket->subject }}</h4>
                    </div>

                    <div>
                        <h6 class="text-muted mb-2">Сообщение</h6>
                        <div class="message-box">
                            {{ $ticket->message }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Файлы -->
            @if($ticket->media->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Прикрепленные файлы</h5>
                        <span class="badge bg-primary">{{ $ticket->media->count() }} файлов</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($ticket->media as $media)
                                <div class="col-md-4 col-sm-6">
                                    <div class="file-card">
                                        <div class="file-icon {{ $media->mime_type }}">
                                            @if(str_starts_with($media->mime_type, 'image/'))
                                                <i class="fas fa-image"></i>
                                            @elseif($media->mime_type == 'application/pdf')
                                                <i class="fas fa-file-pdf"></i>
                                            @elseif(str_contains($media->mime_type, 'document'))
                                                <i class="fas fa-file-word"></i>
                                            @else
                                                <i class="fas fa-file"></i>
                                            @endif
                                        </div>

                                        <h6 class="mb-2" title="{{ $media->file_name }}">
                                            {{ Str::limit($media->file_name, 30) }}
                                        </h6>

                                        <p class="small text-muted mb-2">
                                            {{ number_format($media->size / 1024, 1) }} KB
                                        </p>

                                        <div class="d-flex gap-2">
                                            <a href="{{ $media->getUrl() }}"
                                               target="_blank"
                                               class="btn btn-sm btn-outline-primary flex-fill">
                                                <i class="fas fa-eye me-1"></i> Просмотр
                                            </a>
                                            <a href="{{ $media->getUrl() }}"
                                               download="{{ $media->file_name }}"
                                               class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- История изменений -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">История заявки</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="text-muted small">{{ $ticket->created_at->format('d.m.Y H:i') }}</div>
                            <div class="fw-medium">Заявка создана</div>
                            <p class="mb-0">Клиент отправил заявку через форму обратной связи</p>
                        </div>

                        @if($ticket->reply_date)
                            <div class="timeline-item">
                                <div class="text-muted small">{{ $ticket->reply_date->format('d.m.Y H:i') }}</div>
                                <div class="fw-medium">Заявка обработана</div>
                                <p class="mb-0">Менеджер ответил клиенту и изменил статус</p>
                            </div>
                        @endif

                        <div class="timeline-item">
                            <div class="text-muted small">{{ $ticket->updated_at->format('d.m.Y H:i') }}</div>
                            <div class="fw-medium">Последнее обновление</div>
                            <p class="mb-0">Статус:
                                <span class="status-badge status-{{ $ticket->status->value }}">
                                @switch($ticket->status->value)
                                        @case('new')
                                            Новая
                                            @break
                                        @case('in_progress')
                                            В работе
                                            @break
                                        @case('processed')
                                            Обработана
                                            @break
                                    @endswitch
                            </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Боковая панель -->
        <div class="col-lg-4">
            <!-- Информация о клиенте -->
            <div class="customer-info mb-4">
                <div class="customer-avatar">
                    {{ strtoupper(substr($ticket->customer->name, 0, 1)) }}
                </div>

                <h5 class="text-center mb-3">{{ $ticket->customer->name }}</h5>

                <div class="mb-3">
                    <h6 class="text-muted mb-1"><i class="fas fa-phone me-2"></i> Телефон</h6>
                    <p class="mb-0">
                        <a href="tel:{{ $ticket->customer->phone }}" class="text-decoration-none">
                            {{ $ticket->customer->phone }}
                        </a>
                    </p>
                </div>

                @if($ticket->customer->email)
                    <div class="mb-3">
                        <h6 class="text-muted mb-1"><i class="fas fa-envelope me-2"></i> Email</h6>
                        <p class="mb-0">
                            <a href="mailto:{{ $ticket->customer->email }}" class="text-decoration-none">
                                {{ $ticket->customer->email }}
                            </a>
                        </p>
                    </div>
                @endif

                <div class="mb-3">
                    <h6 class="text-muted mb-1"><i class="fas fa-calendar me-2"></i> Дата регистрации</h6>
                    <p class="mb-0">{{ $ticket->customer->created_at->format('d.m.Y') }}</p>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <a href="mailto:{{ $ticket->customer->email }}?subject=Re: {{ $ticket->subject }}"
                       class="btn btn-primary w-100 {{ !$ticket->customer->email ? 'disabled' : '' }}">
                        <i class="fas fa-reply me-2"></i> Ответить клиенту
                    </a>
                </div>
            </div>

            <!-- Управление статусом -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Управление статусом</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.tickets.update-status', $ticket) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Текущий статус</label>
                            <div class="d-flex align-items-center">
                            <span class="status-badge status-{{ $ticket->status->value }} me-3">
                                @switch($ticket->status->value)
                                    @case('new')
                                        Новая
                                        @break
                                    @case('in_progress')
                                        В работе
                                        @break
                                    @case('processed')
                                        Обработана
                                        @break
                                @endswitch
                            </span>
                                @if($ticket->status->value === 'processed')
                                    <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i> Завершена
                                </span>
                                @endif
                            </div>
                        </div>

                        @if($ticket->status->value !== 'processed')
                            <div class="mb-3">
                                <label class="form-label">Изменить статус на</label>
                                <select name="status" class="form-select" required>
                                    <option value="">Выберите статус</option>
                                    <option value="new" {{ $ticket->status->value == 'new' ? 'disabled' : '' }}>Новая</option>
                                    <option value="in_progress" {{ $ticket->status->value == 'in_progress' ? 'disabled' : '' }}>В работе</option>
                                    <option value="processed">Обработана</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-sync-alt me-2"></i> Обновить статус
                            </button>
                        @else
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                Заявка обработана {{ $ticket->updated_at->format('d.m.Y в H:i') }}
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Быстрые действия -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Действия</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Вернуться к списку
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
