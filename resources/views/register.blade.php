@extends('components.main')
@section('content')
    <form>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Tên</label>
            <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
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
        <button type="button" name="submit" class="btn btn-primary">Submit</button>
    </form>
    <hr>
    Bạn đã có tài khoản? <a href="{{ route('login_page') }}">Đăng nhập</a>
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
                        window.location.href = '/user/login';
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
