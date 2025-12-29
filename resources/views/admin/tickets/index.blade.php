@extends('layouts.admin')

@section('title', 'Заявки')

@push('styles')
    <style>
        .filter-card .form-control, .filter-card .form-select {
            background-color: #fff;
            border: 1px solid #e5e7eb;
        }

        .filter-card .form-control:focus, .filter-card .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .ticket-id {
            font-family: 'SF Mono', Monaco, 'Courier New', monospace;
            font-size: 12px;
            color: #6b7280;
        }

        .action-buttons .btn {
            padding: 4px 8px;
            font-size: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Заявки</h2>
            <p class="text-muted mb-0">Управление заявками от клиентов</p>
        </div>
        <div>
            <a href="{{ route('widget.feedback') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Новая заявка
            </a>
        </div>
    </div>

    <!-- Статистика -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Всего</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="icon bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Новые</h6>
                            <h3 class="mb-0">{{ $stats['new'] }}</h3>
                        </div>
                        <div class="icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">В работе</h6>
                            <h3 class="mb-0">{{ $stats['in_progress'] }}</h3>
                        </div>
                        <div class="icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-cogs"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Обработано</h6>
                            <h3 class="mb-0">{{ $stats['processed'] }}</h3>
                        </div>
                        <div class="icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Фильтры -->
    <div class="card filter-card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i> Фильтры</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tickets.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Статус</label>
                        <select name="status" class="form-select">
                            <option value="">Все статусы</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Новая</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>В работе</option>
                            <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Обработана</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Дата от</label>
                        <input type="date" name="date_from" class="form-control datepicker"
                               value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Дата до</label>
                        <input type="date" name="date_to" class="form-control datepicker"
                               value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Email клиента</label>
                        <input type="email" name="email" class="form-control"
                               placeholder="email@example.com"
                               value="{{ request('email') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Телефон клиента</label>
                        <input type="tel" name="phone" class="form-control"
                               placeholder="+79991234567"
                               value="{{ request('phone') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Поиск</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="Тема, сообщение..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-md-12">
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Сбросить
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Применить фильтры
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Таблица заявок -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Список заявок</h5>
            <div class="text-muted">
                Найдено: {{ $tickets->total() }} заявок
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="ticketsTable">
                    <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th>Клиент</th>
                        <th>Контакты</th>
                        <th>Тема</th>
                        <th>Статус</th>
                        <th>Дата создания</th>
                        <th>Файлы</th>
                        <th width="150">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>
                                <span class="ticket-id">#{{ $ticket->id }}</span>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $ticket->customer->name }}</div>
                            </td>
                            <td>
                                <div class="small">
                                    <i class="fas fa-phone fa-xs me-1 text-muted"></i>
                                    {{ $ticket->customer->phone }}
                                </div>
                                @if($ticket->customer->email)
                                    <div class="small">
                                        <i class="fas fa-envelope fa-xs me-1 text-muted"></i>
                                        {{ $ticket->customer->email }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium">{{ Str::limit($ticket->subject, 50) }}</div>
                                <div class="small text-muted">{{ Str::limit($ticket->message, 60) }}</div>
                            </td>
                            <td>
                            <span class="status-badge status-{{ $ticket->status }}">
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
                            </td>
                            <td>
                                <div>{{ $ticket->created_at->format('d.m.Y') }}</div>
                                <div class="small text-muted">{{ $ticket->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                @if($ticket->media->count() > 0)
                                    <span class="badge bg-info">
                                    <i class="fas fa-paperclip me-1"></i>{{ $ticket->media->count() }}
                                </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons d-flex gap-1">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Просмотр">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($ticket->status !== 'processed')
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-success dropdown-toggle"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    title="Сменить статус">
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @foreach(['new', 'in_progress', 'processed'] as $status)
                                                    @if($status !== $ticket->status)
                                                        <li>
                                                            <form method="POST"
                                                                  action="{{ route('admin.tickets.update-status', $ticket) }}">
                                                                @csrf
                                                                <input type="hidden" name="status" value="{{ $status }}">
                                                                <button type="submit" class="dropdown-item">
                                                                    @switch($status)
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
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-inbox fa-3x text-muted opacity-50"></i>
                                </div>
                                <h5 class="text-muted">Заявки не найдены</h5>
                                <p class="text-muted">Измените параметры фильтрации</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Пагинация -->
            @if($tickets->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Показано с {{ $tickets->firstItem() }} по {{ $tickets->lastItem() }} из {{ $tickets->total() }}
                    </div>
                    <div>
                        {{ $tickets->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
