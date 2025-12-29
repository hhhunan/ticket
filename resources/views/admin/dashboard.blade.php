@extends('layouts.admin')
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
@endsection
