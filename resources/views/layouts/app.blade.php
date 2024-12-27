<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

</head>

<body>
    <div class="container my-5">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#taskTable').DataTable({
                processing: true,
                serverSide: true,
                bInfo: false,
                bPaginate: false,
                bFilter: false,
                ajax: '{{ route("tasks.index") }}',
                columns: [{
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'due_date',
                        name: 'due_date'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (row.status === 'Pending') {
                                return '<i class="bi bi-circle pending-task-icon text-warning" data-id="' + row.id + '" style="cursor: pointer; font-size: 1.5rem;"></i>';
                            } else if (row.status === 'In-progress') {
                                return '<i class="bi bi-hourglass in-progress-task-icon text-primary" data-id="' + row.id + '" style="cursor: pointer; font-size: 1.5rem;"></i>';
                            } else if (row.status === 'Completed') {
                                return '<i class="bi bi-check-circle-fill text-success" style="font-size: 1.5rem;"></i>';
                            }
                            return '';
                        }
                    }
                ],
                columnDefs: [{
                        targets: 0,
                        width: '10%'
                    },
                    {
                        targets: 1,
                        width: '50%'
                    },
                    {
                        targets: 2,
                        width: '15%'
                    },
                    {
                        targets: 3,
                        width: '15%'
                    },
                    {
                        targets: 4,
                        width: '10%'
                    }
                ]


            });

            // clicking on the pending task icons to change the status 
            $(document).on('click', '.pending-task-icon, .in-progress-task-icon', function() {
                var taskId = $(this).data('id');
                var icon = $(this);
                var row = icon.closest('tr');
                var statusCell = row.find('td:eq(2)');

                $.ajax({
                    url: '/tasks/' + taskId + '/update-task-status',
                    method: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            let newStatus = response.new_status;

                            if (newStatus === 'Pending') {
                                icon.removeClass().addClass('bi bi-circle pending-task-icon text-warning').css('cursor', 'pointer');
                                statusCell.text('Pending');
                            } else if (newStatus === 'In-progress') {
                                icon.removeClass().addClass('bi bi-hourglass in-progress-task-icon text-primary').css('cursor', 'pointer');
                                statusCell.text('In-Progress');
                            } else if (newStatus === 'Completed') {
                                icon.removeClass().addClass('bi bi-check-circle-fill text-success').css('cursor', 'default');
                                statusCell.text('Completed');
                            }
                        }

                    },
                    error: function(error) {
                        console.log('Error:', error);
                    }
                });
            });
        });

        // add new task form submission
        $("#taskForm").on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: '{{ route("tasks.store") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {

                        $('#taskTable').DataTable().ajax.reload();
                        $('#taskForm')[0].reset();
                    }
                },
                error: function(response) {
                    if (response.status === 422) {
                        let errors = response.responseJSON.errors;
                        let errorMessages = '';

                        for (let key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                errorMessages += errors[key].join('\n') + '\n';
                            }
                        }

                        alert('Validation Error(s):\n' + errorMessages);
                    } else {
                        alert('Please try again.');
                    }
                }
            });
        });
    </script>

</body>

</html>