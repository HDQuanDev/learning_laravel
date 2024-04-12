@extends('components.main')
@section('content')
    <style>
        #chat1 .form-outline .form-control~.form-notch div {
            pointer-events: none;
            border: 1px solid;
            border-color: #eee;
            box-sizing: border-box;
            background: transparent;
        }

        #chat1 .form-outline .form-control~.form-notch .form-notch-leading {
            left: 0;
            top: 0;
            height: 100%;
            border-right: none;
            border-radius: .65rem 0 0 .65rem;
        }

        #chat1 .form-outline .form-control~.form-notch .form-notch-middle {
            flex: 0 0 auto;
            max-width: calc(100% - 1rem);
            height: 100%;
            border-right: none;
            border-left: none;
        }

        #chat1 .form-outline .form-control~.form-notch .form-notch-trailing {
            flex-grow: 1;
            height: 100%;
            border-left: none;
            border-radius: 0 .65rem .65rem 0;
        }

        #chat1 .form-outline .form-control:focus~.form-notch .form-notch-leading {
            border-top: 0.125rem solid #39c0ed;
            border-bottom: 0.125rem solid #39c0ed;
            border-left: 0.125rem solid #39c0ed;
        }

        #chat1 .form-outline .form-control:focus~.form-notch .form-notch-leading,
        #chat1 .form-outline .form-control.active~.form-notch .form-notch-leading {
            border-right: none;
            transition: all 0.2s linear;
        }

        #chat1 .form-outline .form-control:focus~.form-notch .form-notch-middle {
            border-bottom: 0.125rem solid;
            border-color: #39c0ed;
        }

        #chat1 .form-outline .form-control:focus~.form-notch .form-notch-middle,
        #chat1 .form-outline .form-control.active~.form-notch .form-notch-middle {
            border-top: none;
            border-right: none;
            border-left: none;
            transition: all 0.2s linear;
        }

        #chat1 .form-outline .form-control:focus~.form-notch .form-notch-trailing {
            border-top: 0.125rem solid #39c0ed;
            border-bottom: 0.125rem solid #39c0ed;
            border-right: 0.125rem solid #39c0ed;
        }

        #chat1 .form-outline .form-control:focus~.form-notch .form-notch-trailing,
        #chat1 .form-outline .form-control.active~.form-notch .form-notch-trailing {
            border-left: none;
            transition: all 0.2s linear;
        }

        #chat1 .form-outline .form-control:focus~.form-label {
            color: #39c0ed;
        }

        #chat1 .form-outline .form-control~.form-label {
            color: #bfbfbf;
        }
    </style>
    <section style="background-color: #eee;">
        <div class="container py-5">

            <div class="row d-flex justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">

                    <div class="card" id="chat1" style="border-radius: 15px;">
                        <div class="card-header d-flex justify-content-between align-items-center p-3 bg-info text-white border-bottom-0"
                            style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                            <i class="fas fa-angle-left"></i>
                            <p class="mb-0 fw-bold">Live chat</p>
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="card-body">
                            <div id="chatContainer">
                            </div>


                            <div data-mdb-input-init class="form-outline">
                                <textarea class="form-control" id="textAreaExample" rows="4"></textarea>
                                <label class="form-label" for="textAreaExample">Type your message</label>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
    <script>
        document.getElementById('textAreaExample').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                event.preventDefault();
                const message = this.value;

                fetch('/api/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            username: 'quan',
                            message: message
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                this.value = '';
            }
        });

        function createChatElement(username, message, isUser) {
            var chatDiv = document.createElement('div');
            chatDiv.className = isUser ? 'd-flex flex-row justify-content-end mb-4' :
                'd-flex flex-row justify-content-start mb-4';

            var img = document.createElement('img');
            img.src = isUser ? 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava2-bg.webp' :
                'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava1-bg.webp';
            img.alt = 'avatar';
            img.style.width = '45px';
            img.style.height = '100%';

            var messageDiv = document.createElement('div');
            messageDiv.className = isUser ? 'p-3 me-3 border' : 'p-3 ms-3';
            messageDiv.style.borderRadius = '15px';
            messageDiv.style.backgroundColor = isUser ? 'rgba(57, 192, 237,.2)' : '#fbfbfb';

            var messageP = document.createElement('p');
            messageP.className = 'small mb-0';
            messageP.innerText = message;

            messageDiv.appendChild(messageP);

            if (isUser) {
                chatDiv.appendChild(messageDiv);
                chatDiv.appendChild(img);
            } else {
                chatDiv.appendChild(img);
                chatDiv.appendChild(messageDiv);
            }
            return chatDiv;
        }

        function loadChat() {
            fetch('/api/chat/get', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        var chatContainer = document.getElementById('chatContainer');
                        chatContainer.innerHTML = '';
                        data.messages.forEach(chat => {
                            chatContainer.appendChild(createChatElement(chat.username, chat.message, chat
                                .username === 'quan'));
                        });
                    }
                });
        }
        setInterval(() => {
            loadChat();
        }, 1000);
    </script>
@endsection
