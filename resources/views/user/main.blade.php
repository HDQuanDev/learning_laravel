@extends('components.main')
@section('content')
    <h2>Chào bạn: {{ $user->name }}</h2>
    <hr>
    <button type="button" class="btn btn-outline-primary" onclick="reload_datatable('list_note')">Reload</button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Viết Ghi Chú Mới
    </button>
    <table id="list_note" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID NOTE</th>
                <th>Title</th>
                <th>View</th>
                <th>Create At</th>
                <th>Update At</th>
                <th>Manager</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>ID NOTE</th>
                <th>Title</th>
                <th>View</th>
                <th>Create At</th>
                <th>Update At</th>
                <th>Manager</th>
            </tr>
        </tfoot>
    </table>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Viết Ghi Chú Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Tiêu Đề</label>
                            <input type="text" name="title" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp">
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Nội Dung</label>
                            <textarea id="myTextEditor" name="content" class="form-control" rows="10"></textarea>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" name="submit" class="btn btn-primary">Lưu Lại</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var simplemde = new SimpleMDE({
            element: document.getElementById("myTextEditor")
        });
        document.querySelector('button[name="submit"]').addEventListener('click', function() {
            let title = document.querySelector('input[name="title"]').value;
            let content = simplemde.value();
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            this.innerHTML = 'Vui lòng chờ...';
            this.disabled = true;
            fetch("{{ route('create_note') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        title: title,
                        content: content
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        swal("Good job!", "Thêm ghi chú thành công!", "success");
                        reload_datatable('list_note');
                    } else {
                        swal("Error!", "Thêm ghi chú thất bại!", "error");
                        this.innerHTML = 'Lưu Lại';
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    swal("Error!", "Thêm ghi chú thất bại!", "error");
                    alert('Login failed');
                    this.innerHTML = 'Lưu Lại';
                    this.disabled = false;
                })
                .finally(() => {
                    this.innerHTML = 'Lưu Lại';
                    this.disabled = false;
                });
        });
    </script>
@endsection
