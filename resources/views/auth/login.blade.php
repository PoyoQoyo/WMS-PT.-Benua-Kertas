<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>WMS — Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .login-container {
      width: 100%;
      max-width: 420px;
    }
    .login-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      overflow: hidden;
    }
    .login-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 30px 20px;
      text-align: center;
    }
    .login-header i {
      font-size: 48px;
      margin-bottom: 10px;
    }
    .login-header h1 {
      font-size: 28px;
      font-weight: 700;
      margin: 0;
    }
    .login-header p {
      font-size: 14px;
      opacity: 0.9;
      margin: 5px 0 0 0;
    }
    .login-body {
      padding: 40px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-label {
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
      display: block;
      font-size: 14px;
    }
    .form-control {
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      padding: 12px 15px;
      font-size: 14px;
      transition: border-color 0.3s;
    }
    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .form-control::placeholder {
      color: #999;
    }
    .btn-login {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      border-radius: 8px;
      padding: 12px;
      font-weight: 600;
      color: white;
      width: 100%;
      transition: transform 0.2s, box-shadow 0.2s;
      margin-top: 10px;
    }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
      color: white;
    }
    .btn-login:active {
      transform: translateY(0);
    }
    .alert {
      border-radius: 8px;
      border: none;
      margin-bottom: 20px;
    }
    .alert-danger {
      background-color: #fee;
      color: #c33;
    }
    .alert-success {
      background-color: #efe;
      color: #3c3;
    }
    .form-input-icon {
      position: relative;
    }
    .form-input-icon i {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
      font-size: 16px;
    }
    .form-input-icon .form-control {
      padding-left: 40px;
    }
    .footer-text {
      text-align: center;
      color: #666;
      font-size: 13px;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="card login-card">
      <div class="login-header">
        <i class="fa-solid fa-warehouse"></i>
        <h1>PT. Benua Kertas</h1>
        <p>Warehouse Management System</p>
      </div>

      <div class="login-body">
        @if($errors->any())
          <div class="alert alert-danger">
            @foreach($errors->all() as $error)
              <div><i class="fa-solid fa-circle-exclamation me-2"></i>{{ $error }}</div>
            @endforeach
          </div>
        @endif

        @if($message = Session::get('success'))
          <div class="alert alert-success">
            <i class="fa-solid fa-check-circle me-2"></i>{{ $message }}
          </div>
        @endif

        <form action="{{ route('login') }}" method="POST" novalidate>
          @csrf

          <div class="form-group">
            <label class="form-label">Username</label>
            <div class="form-input-icon">
              <i class="fa-solid fa-user"></i>
              <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                     placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
            </div>
            @error('username')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label class="form-label">Password</label>
            <div class="form-input-icon">
              <i class="fa-solid fa-lock"></i>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                     placeholder="Masukkan password" required>
            </div>
            @error('password')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn btn-login">
            <i class="fa-solid fa-sign-in-alt me-2"></i> Login
          </button>
        </form>

        <div class="footer-text">
          <i class="fa-solid fa-shield me-1"></i> Staff Gudang Only
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
