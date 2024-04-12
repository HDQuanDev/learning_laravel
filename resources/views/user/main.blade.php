@extends('components.main')
@section('content')
    <h2>Chào bạn: {{ $user->name }}</h2>
    <hr>
    <button type="button" class="btn btn-outline-primary" onclick="reload_list_user()">Reload</button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Tạo User Mới
    </button>
    <table id="list_user" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Password</th>
                <th>Create At</th>
                <th>Update At</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Password</th>
                <th>Create At</th>
                <th>Update At</th>
            </tr>
        </tfoot>
    </table>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">RePassword</label>
                            <input type="password" name="re_password" class="form-control" id="exampleInputPassword1">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" name="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('button[name="submit"]').addEventListener('click', function() {
            let name = document.querySelector('input[name="name"]').value;
            let username = document.querySelector('input[name="username"]').value;
            let password = document.querySelector('input[name="password"]').value;
            let re_password = document.querySelector('input[name="re_password"]').value;
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            this.innerHTML = 'Vui lòng chờ...';
            this.disabled = true;
            fetch('/api/user/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        name: name,
                        username: username,
                        password: password,
                        re_password: re_password
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Register success');
                        reload_list_user();
                    } else {
                        alert('Register failed');
                        this.innerHTML = 'Submit';
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Login failed');
                    this.innerHTML = 'Submit';
                    this.disabled = false;
                })
                .finally(() => {
                    this.innerHTML = 'Submit';
                    this.disabled = false;
                });
        });
    </script>
@endsection
