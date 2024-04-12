$(document).ready(function () {
    if ($('#list_user').length) {
        $('#list_user').DataTable({
            "ajax": {
                "url": "/api/user/get/user",
                "type": "POST",
                "headers": {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                "data": function (d) {
                    d.csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                },
                "error": function (jqXHR, textStatus, errorThrown) {
                    console.log('AJAX error: ', textStatus, ' : ', errorThrown);
                }
            },
            "columns": [{
                    "data": "name"
                },
                {
                    "data": "username"
                },
                {
                    "data": "admin"
                },
                {
                    "data": "created_at"
                },
                {
                    "data": "updated_at"
                },
                {
                    "data": "id"
                }
            ],
            "columnDefs": [{
                "targets": 5,
                "render": function (data, type, row, meta) {
                    return '<button type="button" class="btn btn-primary" onclick="open_modal_create_user(' + data + ')">Chỉnh sửa</button> <button type="button" class="btn btn-danger" onclick="delete_user(' + data + ')">Xóa</button>';
                }
            }],
            "language": {
                "lengthMenu": "Hiển thị _MENU_ người dùng mỗi trang",
                "zeroRecords": "Không tìm thấy người dùng nào",
                "info": "Hiển thị trang _PAGE_ của _PAGES_",
                "infoEmpty": "Không có người dùng nào",
                "infoFiltered": "(Lọc từ _MAX_ người dùng)",
                "search": "Tìm kiếm",
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Sau",
                    "previous": "Trước"

                },
                "processing": "Đang xử lý...",
                "loadingRecords": "Đang tải...",
                "emptyTable": "Bảng trống",
                "aria": {
                    "sortAscending": ": Sắp xếp tăng dần",
                    "sortDescending": ": Sắp xếp giảm dần"
                }
            },
            "processing": true,
            "order": [
                [0, 'desc']
            ],
        });
    }
});

function reload_list_user() {
    $('#list_user').DataTable().ajax.reload();
}

let modalInstances = {};

const exampleModal = document.getElementById('edituser')
const modal = new bootstrap.Modal(exampleModal);

exampleModal.addEventListener('show.bs.modal', event => {
    const id = exampleModal.getAttribute('data-id');
    const modalTitle = exampleModal.querySelector('.modal-title')

    fetch('/api/user/get/user_by_id', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            body: JSON.stringify({
                id: id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == 'success') {
                //set title modal 
                modalTitle.textContent = "Chỉnh sửa người dùng: " + data.data.name;
                // Set value for input
                exampleModal.querySelector('#name_edit').value = data.data.name;
                exampleModal.querySelector('#username_edit').value = data.data.username;
                exampleModal.querySelector('#id_user').value = data.data.id;
            } else {
                alert(data.message);
            }
        })
        .catch((error) => {
            alert('Error: ' + error);
        });
})

function open_modal_create_user(id) {
    exampleModal.setAttribute('data-id', id);
    const modal = new bootstrap.Modal(exampleModal);
    modalInstances['edituser'] = modal; // Store the modal instance
    modal.show();
}

function close_modal(id) {
    const modal = modalInstances[id]; // Retrieve the modal instance
    if (modal) {
        modal.hide();
    }
}


function delete_user(id) {
    swal({
            title: "Bạn có chắc chắn muốn xóa người dùng này?",
            text: "Sau khi xóa, bạn sẽ không thể khôi phục lại dữ liệu!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                swal("Đang xóa...", {
                    icon: "info",
                });
                fetch('/api/user/manager/delete_user', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        },
                        body: JSON.stringify({
                            id: id
                        })
                    }).then(response => response.json())
                    .then(data => {
                        if (data.status == 'success') {
                            swal("Xóa thành công!", {
                                icon: "success",
                            });
                            reload_list_user();
                        } else {
                            swal("Xóa thất bại!", {
                                message: data.message,
                                icon: "error",
                            });
                        }
                    });
            }
        });
}
