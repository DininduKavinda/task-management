<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>

        <div class="d-flex flex-row mb-3">
            <form action="{{ route('tasks.index') }}" class="d-flex flex-row" method="get">
                <input type="text" class="form-control" id="priority" name="priority" placeholder="Search by Priority">
                <button type="submit" class="btn btn-primary mx-2">Submit</button>
            </form>
            <button class="btn btn-primary"  data-toggle="modal" data-target="#taskModal" id="createTaskButton">
                <i class="bi bi-plus-circle"></i> Add New
            </button>
        </div>

        <table id="tblData" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due</th>
                    <th>Priority</th>

                    <th>Payment</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="taskForm">
                        <div class="modal-body">
                            <input type="hidden" name="task_id" id="taskId">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="userId" name="user_id" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <select class="form-control" id="priority" name="priority" required>

                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveTaskButton"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <script>
            let dataTable;


            $(document).ready(function() {
                loadDataTable();
                // Open modal for creating a new task
                $('#createTaskButton').click(function() {
                    $('#taskModalLabel').text('Create Task');
                    $('#saveTaskButton').text('Create');
                    $('#taskForm')[0].reset(); // Clear the form
                    $('#taskId').val('');
                    // Ensure task_id is empty
                });

                // Open modal for editing a task
                $(document).on('click', '.edit-button', function() {
                    const taskId = $(this).data('id');
                    $.ajax({
                        url: `/api/tasks/${taskId}`,
                        type: 'GET',
                        success: function(response) {
                            $('#taskModalLabel').text('Edit Task');
                            $('#saveTaskButton').text('Update');
                            $('#userId').val(response.task.user_id);
                            $('#taskId').val(response.task.id);
                            $('#title').val(response.task.title);
                            $('#description').val(response.task.description);
                            $('#priority').val(response.task.priority);
                            $('#due_date').val(response.task.due_date);
                            $('#taskModal').modal('show');
                        },
                        error: function() {
                            alert('Failed to load task details.');
                        }
                    });
                });

                // Handle form submission (create or update)
                $('#taskForm').submit(function(e) {
                    e.preventDefault();

                    const taskId = $('#taskId').val();
                    const url = taskId ? `/api/tasks/${taskId}` : '/api/tasks';
                    const method = taskId ? 'PUT' : 'POST';
                    const formData = $(this).serialize();

                    $.ajax({
                        url: url,
                        type: method,
                        data: formData,
                        success: function(response) {
                            $('#taskModal').modal('hide');
                            alert(taskId ? 'Task updated successfully!' :
                                'Task created successfully!');
                            $('#tblData').DataTable().ajax.reload();
                        },
                        error: function() {
                            alert('Failed to save task.');
                        }
                    });
                });
            });

            function loadDataTable() {
                dataTable = $('#tblData').DataTable({
                    ajax: {
                        url: '/api/tasks',
                        type: 'GET'
                    },
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'title'
                        },
                        {
                            data: 'description'
                        },
                        {
                            data: 'due_date'
                        },
                        {
                            data: 'priority'
                        },
                        {
                            data: 'id',
                            render: function(data) {
                                return `
        <div class="btn-group" role="group">
            <a href="/tasks/${data}/pay" class="btn btn-warning mx-2">Pay</a>
        </div>
    `;
                            }
                        },

                        {
                            data: 'id',
                            render: function(data, type, row) {
                                return `
                           <button class="btn btn-warning mx-2 edit-button" data-id="${data}" data-toggle="modal" data-target="#taskModal">Edit</button>
                            <button onClick="deleteTask('/api/tasks/${data}')" class="btn btn-danger btn-sm">Delete</button>
                        `;
                            }
                        }
                    ]
                });
            }

            function openEditModal(id) {
                $.ajax({
                    url: `/api/tasks/${id}`,
                    method: 'GET',
                    success: function(response) {
                        const task = response.data;
                        $('#editTaskModal input[name="title"]').val(task.title);
                        $('#editTaskModal textarea[name="description"]').val(task.description);
                        $('#editTaskModal input[name="due_date"]').val(task.due_date);
                        $('#editTaskModal select[name="priority"]').val(task.priority);
                        $('#editTaskId').val(task.id);
                        $('#editTaskModal').modal('show');
                    },
                    error: function() {
                        alert('Unable to fetch task data.');
                    }
                });
            }

            function deleteTask(url) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: function(data) {
                                dataTable.ajax.reload();
                                alert(data.message);
                            },
                            error: function() {
                                alert('Failed to delete task.');
                            }
                        });
                    }
                });
            }
        </script>

    </x-slot>

    <!-- Add Task Modal -->

</x-app-layout>
