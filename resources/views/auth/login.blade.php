<body class="animsition" style="overflow: hidden; height: 100%;">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content" style="margin-top: 15% !important">
                        <div class="login-logo">
                            <a href="#">
                                <img src="{{ asset('images/icon/logo.png') }}" alt="CoolAdmin">
                            </a>
                        </div>

                        <div class="login-form">
                            {{-- Notifikasi Gagal Login --}}
                            @if(session('loginError'))
                                <div class="alert alert-danger">
                                    {{ session('loginError') }}
                                </div>
                            @endif

                            <form action="{{ route('login.process') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label>Username atau Email</label>
                                    <input
                                        class="au-input au-input--full @error('login') is-invalid @enderror"
                                        type="text"
                                        name="login"
                                        placeholder="Masukkan username (NIM/NIK/admin) atau email"
                                        value="{{ old('login') }}"
                                        autocomplete="username"
                                        autofocus
                                    >
                                    @error('login')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input
                                        class="au-input au-input--full @error('password') is-invalid @enderror"
                                        type="password"
                                        name="password"
                                        placeholder="Password"
                                        autocomplete="current-password"
                                    >
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="login-checkbox m-b-20">
                                    <label>
                                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                                        Ingat saya
                                    </label>
                                </div>

                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">
                                    Sign In
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
