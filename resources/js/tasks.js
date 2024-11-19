var dataTable;
$(document).ready(function () {
    loadDataTable();
});

function loadDataTable() {
    dataTable = $('#tblData').DataTable({
        "ajax": {
            url: '/tasks'
        },
        columns: [
            { data: 'id' },
            { data: 'customer_Shop_Name' },
            { data: 'customer_Name' },
            { data: 'customer_Address' },
            { data: 'customer_Contact_No' },
            {
                data: 'id',
                render: function (data) {
                    return `
                    <div class="w-75 btn-group" role="group">
                    <a href="/user/customer/upsert?id=${data}" data-id="${data}" data-toggle="modal" data-target="#exampleModal" class="btn btn-warning mx-2  edit-button">Edit</a>
                    <a onClick=Delete('/user/customer/delete?id=${data}') class="btn btn-danger mx-2">Delete</a>
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
                type: 'DELETE',
                success: function (data) {
                    dataTable.ajax.reload();
                    toastr.success(data.message);
                }
            })
        }
    })
}
$(document).on('click', '.edit-button', function () {
    var id = $(this).data('id');
    $.ajax({
        url: '/user/customer/getById?id=' + id,
        type: 'GET',
        success: function (result) {
            $("#exampleModal input[name='Customer.Id']").val(result.data.id);
            $("#exampleModal input[name='Customer.Customer_Name']").val(result.data.customer_Name);
            $("#exampleModal input[name='Customer.Customer_Shop_Name']").val(result.data.customer_Shop_Name);
            $("#exampleModal input[name='Customer.Customer_Contact_No']").val(result.data.customer_Contact_No);
            $("#exampleModal input[name='Customer.Customer_Address']").val(result.data.customer_Address);
            $("#exampleModal input[name='Customer.Customer_Road']").val(result.data.customer_Road);
            $("#exampleModal select[name='Customer.Customer_CityId']").val(result.data.customer_CityId);
            $("#exampleModal").modal({ backdrop: 'static' });
        },
        error: function (error) {
            console.log(error);
            $("#exampleModal").modal('close');
        }
    });
});

