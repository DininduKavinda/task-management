<x-app-layout>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>

        <div>
            <table id="tblData">
                <thead>
                    <tr>
                        <th>#</th>
                        <tH>Titile</tH>
                        <th>Description</th>
                        <th>Due</th>
                        <th>Priority</th>
                        <th>Complete</th>
                        <th>Paid</th>
                        <th>Edit</th>
                    </tr>
                </thead>
            </table>
        </div>

        <script>
            var dataTable;
            $(document).ready(function() {
                loadDataTable();
            });

            function loadDataTable() {
                dataTable = $('#tblData').DataTable({
                    "ajax": {
                        url: '/api/tasks'
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
                    <div class="w-75 btn-group" role="group">
                    <a onClick=complete('/api/tasks/${data}/complete') class="btn btn-success mx-2">Complete</a>
                    </div>
                    `;
                            }
                        },
                        {
                            data: 'id',
                            render: function(data) {
                                return `
                    <div class="w-75 btn-group" role="group">
                    <a onClick=pay('/api/tasks/${data}/pay') class="btn btn-warning mx-2">Pay</a>
                    </div>
                    `;
                            }
                        },
                        {
                            data: 'id',
                            render: function(data) {
                                return `
                    <div class="w-75 btn-group" role="group">
                    <a onClick=Delete('/api/tasks/${data}') class="btn btn-danger mx-2">Delete</a>
                    </div>
                    `;
                            }
                        },
                        {
                            data: 'id',
                            render: function(data) {
                                return `
                    <div class="w-75 btn-group" role="group">
                    <a href="/api/tasks/${data}" data-id="${data}" data-toggle="modal" data-target="#exampleModal" class="btn btn-warning mx-2  edit-button">Edit</a>  
                    </div>
                    `;
                            }
                        }

                    ]
                });
            }

            function Delete(url) {
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
                            type: 'delete',
                            success: function(data) {
                                dataTable.ajax.reload();
                                toastr.success(data.message);
                            }
                        })
                    }
                })
            }

            function complete(url) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Make Complete!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'post',
                            success: function(data) {
                                dataTable.ajax.reload();
                                toastr.success(data.message);
                            }
                        })
                    }
                })
            }

            function pay(url) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Make Complete!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'post',
                            success: function(data) {
                                dataTable.ajax.reload();
                                toastr.success(data.message);
                            }
                        })
                    }
                })
            }

            $(document).on('click', '.edit-button', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/tasks/getById?id=' + id,
                    type: 'GET',
                    success: function(result) {
                        $("#exampleModal input[name='Customer.Id']").val(result.data.id);
                     
                        $("#exampleModal").modal({
                            backdrop: 'static'
                        });
                    },
                    error: function(error) {
                        console.log(error);
                        $("#exampleModal").modal('close');
                    }
                });
            });

            
        </script>

    </x-slot>

</x-app-layout>
