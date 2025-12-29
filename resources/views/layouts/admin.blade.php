<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - CRM Admin</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #6b7280;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background-color: #f9fafb;
        }

        .sidebar {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
            position: fixed;
            width: 250px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            border-radius: 8px;
            padding: 10px 16px;
            margin: 4px 12px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link.active {
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }

        .navbar-top {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-new {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-in_progress {
            background: #fef3c7;
            color: #92400e;
        }

        .status-processed {
            background: #d1fae5;
            color: #065f46;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            border-left: 4px solid var(--primary);
        }

        .stat-card .icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.05);
        }

        .badge-count {
            background: var(--danger);
            color: white;
            border-radius: 50%;
            padding: 4px 10px;
            font-size: 14px;
            position: absolute;
            top: -1px;
            right: -5px;
        }

        .file-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .file-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
        }
    </style>

    @stack('styles')
</head>
<body>
<!-- Sidebar -->
<div class="sidebar d-none d-lg-block">
    <div class="p-4">
        <!-- Logo -->
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-white text-decoration-none mb-5">
            <div class="bg-primary p-3 rounded me-3">
                <i class="fas fa-ticket-alt fa-lg"></i>
            </div>
            <div>
                <span class="fs-4 fw-bold">Ticket CRM</span>
                <div class="text-muted small">Админ панель</div>
            </div>
        </a>

        <!-- Navigation -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Дашборд
                </a>
            </li>
            @role('manager')
            <li class="nav-item">
                <a href="{{ route('admin.tickets.index') }}"
                   class="nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt me-2"></i> Заявки
                    @php
                        $newTickets = \App\Models\Ticket::where('status', 'new')->count();
                    @endphp
                    @if($newTickets > 0)
                        <span class="badge-count">{{ $newTickets }}</span>
                    @endif
                </a>
            </li>
            @endrole
        </ul>

        <!-- User Section -->
        <div class="mt-5 pt-5 border-top border-secondary">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-primary bg-opacity-25 p-3 rounded-circle">
                        <i class="fas fa-user text-primary"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="text-white fw-medium">{{ auth()->user()->name }}</div>
                    <div class="text-muted small">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <div class="mt-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light w-100">
                        <i class="fas fa-sign-out-alt me-1"></i> Выход
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-light bg-white d-lg-none mb-4 rounded shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-ticket-alt text-primary me-2"></i>
                <span class="fw-bold">Ticket CRM</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMobile">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMobile">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Дашборд</a>
                    </li>
                    @role('manager')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.tickets.index') }}">Заявки</a>
                    </li>
                    @endrole
                </ul>
                <div class="d-flex align-items-center">
                    <span class="me-3">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            Выход
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $.fn.dataTable.ext.classes.sWrapper = 'dataTables_wrapper dt-bootstrap4';

    $(document).ready(function () {
        $('#ticketsTable').DataTable({
            language: {
                "processing": "Подождите...",
                "search": "Поиск:",
                "lengthMenu": "Показать _MENU_ записей",
                "info": "Записи с _START_ по _END_ из _TOTAL_ записей",
                "infoEmpty": "Записи с 0 по 0 из 0 записей",
                "infoFiltered": "(отфильтровано из _MAX_ записей)",
                "infoPostFix": "",
                "loadingRecords": "Загрузка записей...",
                "zeroRecords": "Записи отсутствуют.",
                "emptyTable": "В таблице отсутствуют данные",
                "paginate": {
                    "first": "Первая",
                    "previous": "Предыдущая",
                    "next": "Следующая",
                    "last": "Последняя"
                },
                "aria": {
                    "sortAscending": ": активировать для сортировки столбца по возрастанию",
                    "sortDescending": ": активировать для сортировки столбца по убыванию"
                }
            },
            pageLength: 15,
            order: [[0, 'desc']]
        });

        function filterTable() {
            $.ajax({
                url: '/api/tickets',
                data: {
                    status: $('#statusFilter').val(),
                    search: $('#searchFilter').val()
                },
                success: function (response) {
                    if ($.fn.DataTable.isDataTable('#ticketsTable')) {
                        $('#ticketsTable').DataTable().destroy();
                        $('#ticketsTable').empty(); // очищаем содержимое
                    }
                    if (response.data && response.data.length > 0) {
                        if ($('#ticketsTable thead').length === 0) {
                            $('#ticketsTable').html('<thead>...</thead><tbody>...</tbody>');
                        }
                        fillTable(response.data);
                        $('#ticketsTable').DataTable({
                            language: {
                                url: '/static/lang/ru.json'
                            }
                        });
                        console.table([{
                            'ID': firstTicket.id,
                            'Клиент': firstTicket.client_name,
                            'Контакты': firstTicket.contacts,
                            'Тема': firstTicket.subject,
                            'Статус': firstTicket.status,
                            'Дата создания': firstTicket.created_at,
                            'Файлы': firstTicket.media_count
                        }]);
                    } else {
                        $('#ticketsTable').html(
                            '<tr><td colspan="4" class="text-center">' +
                            'По вашему запросу ничего не найдено</td></tr>'
                        );
                    }
                }
            });

            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            flatpickr('.datepicker', {
                dateFormat: 'Y-m-d',
                locale: 'ru'
            });

            $('.confirm-action').on('click', function (e) {
                e.preventDefault();
                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Вы уверены?',
                    text: "Это действие нельзя отменить!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Да, продолжить!',
                    cancelButtonText: 'Отмена'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });
</script>

@stack('scripts')
</body>
</html>
