<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Settings | Ghar Ka Baker</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      rel="stylesheet"
    />
    <link href="/assets/css/style.css" rel="stylesheet" />
  </head>
  <body>
    <div class="app-shell">
      <aside id="sidebar" class="sidebar"></aside>
      <main class="main-content">
        <header class="topbar">
          <div class="d-flex gap-2">
            <button class="icon-button mobile-menu" onclick="toggleSidebar()">
              <i class="bi bi-list"></i>
            </button>
            <div>
              <h1 class="page-title">Settings</h1>
              <p class="page-subtitle">
                Update your business and app preferences.
              </p>
            </div>
          </div>
          <div class="top-actions">
            <select class="form-select language-select">
              <option>English</option>
              <option>Urdu</option>
              <option>Roman Urdu</option></select
            ><button class="theme-button" type="button"></button>
          </div>
        </header>

        @if(session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="row g-4">
          <div class="col-lg-8">
            <form class="demo-form" action="/settings" method="POST">
              @csrf
              @method('PUT')
              <div class="card mb-4">
                <div class="card-body">
                  <h2 class="form-section-title">Business Profile</h2>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Business Name</label
                      ><input class="form-control" name="business_name" value="{{ $bakery->business_name }}" required />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Owner Name</label
                      ><input class="form-control" name="owner_name" value="{{ $bakery->owner_name }}" required />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Business Phone</label
                      ><input class="form-control" name="business_phone" value="{{ $bakery->business_phone }}" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Business City</label
                      ><select class="form-select" name="business_city">
                        @foreach(['Lahore', 'Karachi', 'Islamabad', 'Rawalpindi'] as $city)
                          <option value="{{ $city }}" {{ $bakery->business_city == $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12">
                      <label class="form-label">Business Address</label
                      ><textarea class="form-control" name="business_address" rows="2">{{ $bakery->business_address }}</textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card mb-4">
                <div class="card-body">
                  <h2 class="form-section-title">Payment Details</h2>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">JazzCash Number</label
                      ><input class="form-control" name="jazzcash_number" value="{{ $bakery->jazzcash_number }}" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Easypaisa Number</label
                      ><input class="form-control" name="easypaisa_number" value="{{ $bakery->easypaisa_number }}" />
                    </div>
                    <div class="col-12">
                      <label class="form-label">Bank Transfer Details</label
                      ><textarea
                        class="form-control"
                        name="bank_details"
                        rows="2"
                        placeholder="Bank name, account title and account number"
                      >{{ $bakery->bank_details }}</textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <h2 class="form-section-title">Order Preferences</h2>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Default Currency</label
                      ><select class="form-select" name="default_currency">
                        <option value="PKR" {{ $bakery->default_currency == 'PKR' ? 'selected' : '' }}>PKR — Pakistani Rupee / روپے</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Default Order Status</label
                      ><select class="form-select" name="default_order_status">
                        <option value="New" {{ $bakery->default_order_status == 'New' ? 'selected' : '' }}>New</option>
                        <option value="Confirmed" {{ $bakery->default_order_status == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                      </select>
                    </div>
                    <div class="col-12">
                      <div class="form-check form-switch">
                        <input
                          class="form-check-input"
                          type="checkbox"
                          role="switch"
                          name="show_payment_reminders"
                          id="paymentReminder"
                          {{ $bakery->show_payment_reminders ? 'checked' : '' }}
                        /><label class="form-check-label" for="paymentReminder"
                          >Show remaining payment reminders on dashboard</label
                        >
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-gradient mt-4">
                <i class="bi bi-check2"></i> Save Settings
              </button>
            </form>
          </div>
          <div class="col-lg-4">
            <div class="card mb-4">
              <div class="card-body">
                <h2 class="section-title">Appearance</h2>
                <p class="section-note mb-3">
                  Choose a comfortable view for your workspace.
                </p>
                <button class="theme-button me-2" type="button"></button
                ><span class="small text-muted-custom"
                  >Toggle light / dark mode</span
                >
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <h2 class="section-title">Language</h2>
                <p class="section-note mb-3">
                  Choose the app language for this device.
                </p>
                <select class="form-select language-select">
                  <option>English</option>
                  <option>Urdu</option>
                  <option>Roman Urdu</option>
                </select>
                <p class="small text-muted-custom mt-3 mb-0">
                  Language selection is a frontend demo in this prototype.
                </p>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div id="appToast" class="toast border-0">
        <div class="toast-body bg-dark text-white rounded">
          <span id="toastText"></span>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
  </body>
</html>