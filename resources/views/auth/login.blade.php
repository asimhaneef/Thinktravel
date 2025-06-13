<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Admin| Log in</title>
      <link href="{{asset(config('constants.ASSETS_PATH').'/themecss.css')}}" rel="stylesheet" />
      <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/fontawesome-free/css/all.min.css">
      <style>
         body {
            background-image: url('{{asset(config('constants.IMAGES_PATH').'/main-bg.jpg')}}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
         }
         .logo {
            width: 350px;
            height: auto;
            margin-top: -100px
         }
         .login-logo a {
            font-size: 24px
         }
         .login-box-msg {
            font-size: 18px
         }
         /* 2FA specific styles */
         .two-factor-box {
            display: none;
            margin-top: 20px;
         }
         .recovery-code-toggle {
            cursor: pointer;
            color: #007bff;
            margin-top: 10px;
            display: inline-block;
         }
      </style>
   </head>
   <body class="hold-transition login-page">
      <div class="login-box">
         <div class="login-logo-1">
            <img src="{{ asset(config('constants.IMAGES_PATH') . '/logo.png') }}" alt="Admin Logo" class="logo">
         </div>
         <div class="card rounded-lg p-3">
            <div class="card-body login-card-body">
               <!-- Regular Login Form -->
               <p class="login-box-msg">Sign in to start your session</p>
               <form id="loginForm" action="{{ route('login') }}" method="post">
                  @csrf
                  <div class="input-group mb-3">
                     <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Email" required autofocus>
                     <div class="input-group-append">
                        <div class="input-group-text">
                           <span class="fas fa-envelope"></span>
                        </div>
                     </div>
                     @error('email')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                     @enderror
                  </div>
                  <div class="input-group mb-3">
                     <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                     <div class="input-group-append">
                        <div class="input-group-text">
                           <span class="fas fa-lock"></span>
                        </div>
                     </div>
                     @error('password')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                     @enderror
                  </div>
                  <div class="row">
                     <div class="col-8">
                        <div class="icheck-primary">
                           <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                           <label for="remember">
                           Remember Me
                           </label>
                        </div>
                     </div>
                     <div class="col-4">
                        <button type="submit" class="btn btn-success btn-block">Sign In</button>
                     </div>
                  </div>
               </form>

               <!-- 2FA Challenge Form (hidden by default) -->
               <div id="twoFactorBox" class="two-factor-box">
                  <p class="login-box-msg">Enter your 2FA code</p>
                  <form id="twoFactorForm" action="{{ url('/two-factor-challenge') }}" method="POST">
                     @csrf
                     <div class="input-group mb-3">
                        <input type="text" name="code" class="form-control" placeholder="Authentication Code" required autofocus>
                        <div class="input-group-append">
                           <div class="input-group-text">
                              <span class="fas fa-key"></span>
                           </div>
                        </div>
                     </div>
                     
                     <div id="recoveryCodeSection" style="display: none;">
                        <div class="input-group mb-3">
                           <input type="text" name="recovery_code" class="form-control" placeholder="Recovery Code">
                           <div class="input-group-append">
                              <div class="input-group-text">
                                 <span class="fas fa-life-ring"></span>
                              </div>
                           </div>
                        </div>
                     </div>
                     
                     <div class="row">
                        <div class="col-8">
                           <span id="toggleRecovery" class="recovery-code-toggle">
                              Use recovery code instead
                           </span>
                        </div>
                        <div class="col-4">
                           <button type="submit" class="btn btn-primary btn-block">Verify</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>

      <!-- Add jQuery and custom script -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script>
         $(document).ready(function() {
            // Handle login form submission
            $('#loginForm').on('submit', function(e) {
               e.preventDefault();
               
               $.ajax({
                  url: $(this).attr('action'),
                  method: 'POST',
                  data: {
                           _token: $('meta[name="csrf-token"]').attr('content'),
                           code: $('input[name="code"]').val(),
                           recovery_code: $('input[name="recovery_code"]').val()
                        },
                  success: function(response) {
                     if (response.two_factor) {
                        // Show 2FA form
                        $('#loginForm').hide();
                        $('#twoFactorBox').show();
                     } else {
                        // Redirect to dashboard
                        window.location.href = response.redirect || '/dashboard';
                     }
                  },
                  error: function(xhr) {
                     // Handle errors (existing error handling will work)
                     $('#loginForm').unbind('submit').submit();
                  }
               });
            });

            // Toggle between code and recovery code
            $('#toggleRecovery').on('click', function() {
               const recoverySection = $('#recoveryCodeSection');
               const isRecoveryVisible = recoverySection.is(':visible');
               
               recoverySection.toggle();
               $('input[name="code"]').prop('required', !isRecoveryVisible);
               $('input[name="recovery_code"]').prop('required', isRecoveryVisible);
               
               $(this).text(isRecoveryVisible ? 'Use recovery code instead' : 'Use authentication code instead');
            });
         });
      </script>
   </body>
</html>