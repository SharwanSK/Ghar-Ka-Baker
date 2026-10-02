<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | Ghar Ka Baker</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      rel="stylesheet"
    />
    <link href="assets/css/style.css" rel="stylesheet" />
  </head>
  <body>
    <main class="auth-page">
      <div class="auth-wrap">
        <section class="auth-welcome">
          <div>
            <a href="/login" class="brand px-0 pb-4"
              ><span class="brand-icon"><i class="bi bi-cake2"></i></span>Ghar
              Ka Baker</a
            >
            <h1>Your sweet business, simply managed.</h1>
            <p class="opacity-75 mt-3">
              A friendly order manager made for Pakistani home bakers, cake
              artists and caterers.
            </p>
          </div>
          <div class="auth-features">
            <div class="auth-feature">
              <i class="bi bi-receipt-cutoff me-2"></i>Track orders and delivery
              dates
            </div>
            <div class="auth-feature">
              <i class="bi bi-wallet2 me-2"></i>Never forget a remaining payment
            </div>
            <div class="auth-feature">
              <i class="bi bi-whatsapp me-2"></i>Send order details on WhatsApp
            </div>
          </div>
          <small class="opacity-75"
            >Made with love for Pakistan's small food businesses.</small
          >
        </section>

        <section class="auth-panel">
          <div class="d-flex justify-content-end mb-4">
            <button
              class="theme-button"
              type="button"
              aria-label="Change theme"
            ></button>
          </div>
          <h2>Welcome back!</h2>
          <p class="text-muted-custom mb-4">
            Login to manage today's delicious orders.
          </p>
          <!-- for error -->
          @if ($errors->any())
               <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                       @foreach($errors->all() as $error)
                       <li>{{ $error}}</li>
                       @endforeach
                    </ul>
               </div>
          @endif
          <!-- for error -->

          <form class="demo-form" action="/login" method="POST">
            @csrf
            <div class="mb-3">
              <label class="form-label">Email address</label>
              <input
                class="form-control"
                type="email"
                name="email"
                placeholder="you@example.com"
                required
              />
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <div class="input-group">
                <input
                  class="form-control"
                  type="password"
                  name="password"
                  placeholder="Enter your password"
                  required
                /><span class="input-group-text bg-transparent border-start-0"
                  ><i class="bi bi-eye text-muted-custom"></i
                ></span>
              </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
              <label class="small text-muted-custom"
                ><input class="form-check-input me-1" type="checkbox" />
                Remember me</label
              >
              <a href="#" class="small-link small">Forgot password?</a>
            </div>
           <button type="submit" class="btn btn-gradient w-100 py-2">
              Login <i class="bi bi-arrow-right ms-1"></i>
           </button>
          </form>
          <p class="text-center text-muted-custom mt-4 mb-0">
            New to Ghar Ka Baker?
            <a href="/register" class="small-link">Create account</a>
          </p>
        </section>
      </div>
    </main>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div id="appToast" class="toast border-0" role="alert">
        <div class="toast-body bg-dark text-white rounded">
          <i class="bi bi-check-circle me-1"></i> <span id="toastText"></span>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
  </body>
</html>
