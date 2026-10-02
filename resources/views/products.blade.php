<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Products | Ghar Ka Baker</title>
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
    <div class="app-shell">
      <aside id="sidebar" class="sidebar"></aside>
      <main class="main-content">
        <header class="topbar">
          <div class="d-flex gap-2">
            <button class="icon-button mobile-menu" onclick="toggleSidebar()">
              <i class="bi bi-list"></i>
            </button>
            <div>
              <h1 class="page-title">Products</h1>
              <p class="page-subtitle">Your menu, prices and availability.</p>
            </div>
          </div>
          <div class="top-actions">
            <select class="form-select language-select">
              <option>English</option>
              <option>Urdu</option>
              <option>Roman Urdu</option>
              </select>
            <button class="theme-button" type="button"></button
            >
            <button
              class="btn btn-gradient"
              data-bs-toggle="modal"
              data-bs-target="#productModal"
                onclick="openAddModal()">
              <i class="bi bi-plus-lg"></i> Add Product
            </button>
          </div>
        </header>
        <!-- =================Filter Section==================================== -->
       <section class="d-flex flex-wrap gap-2 mb-4" id="categoryFilters">
              <button type="button" class="btn btn-gradient" data-category="All" onclick="filterProducts(this)">All Products</button>
              <button type="button" class="btn btn-soft" data-category="Cakes" onclick="filterProducts(this)">Cakes</button>
              <button type="button" class="btn btn-soft" data-category="Cupcakes" onclick="filterProducts(this)">Cupcakes</button>
              <button type="button" class="btn btn-soft" data-category="Brownies" onclick="filterProducts(this)">Brownies</button>
              <button type="button" class="btn btn-soft" data-category="Cookies" onclick="filterProducts(this)">Cookies</button>
              <button type="button" class="btn btn-soft" data-category="Desserts" onclick="filterProducts(this)">Desserts</button>
              <button type="button" class="btn btn-soft" data-category="Catering" onclick="filterProducts(this)">Catering</button>
         </section>
        <!-- =================Filter Section==================================== -->


        <!-- product show  -->
        <section class="row g-4">
           @foreach($products as $product)

          <div class="col-sm-6 col-lg-4 col-xl-3 product-item"  data-category="{{ $product->category }}">

            <article class="card product-card h-100 opacity-75">
              @if($product->image_url)
                  <img class="product-image" src="{{asset('storage/'.$product->image_url)}}" alt="{{$product->name}}">               
               @endif

              <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span class="badge badge-soft-info">
                    {{$product->category}}
                    </span>
                    @if($product->is_available)
                    <span class="badge badge-soft-success">
                    Available
                   </span>
                   @else
                   <span class="badge badge-soft-danger">
                    Unavailable
                   </span>
                   @endif


                </div>
                <h3 class="h6 fw-bold mt-3 mb-1">{{$product->name}}</h3>
                <p class="small text-muted-custom">
                  {{$product->description}}
                </p>
                <div class="d-flex justify-content-between align-items-center">
                   <span class="price">
                    PKR {{$product->price}}/ lb
                    </span>

                 <button type="button" class="btn btn-sm btn-soft"
                     data-bs-toggle="modal" data-bs-target="#productModal"

                     data-id="{{ $product->id }}"
                     data-name="{{ $product->name }}"
                     data-category="{{ $product->category }}"
                     data-price="{{ $product->price }}"
                     data-description="{{ $product->description }}"
                     data-available="{{ $product->is_available ? '1' : '0' }}"
                     data-image="{{ $product->image_url ? asset('storage/'.$product->image_url) : '' }}"
                     onclick="openEditModal(this)">
                   <i class="bi bi-pencil"></i>
             </button>

              <form action="{{ route('product.destroy', $product->id) }}" method="POST"
                 onsubmit="return confirm('Are you sure want to delete?');">
                  @csrf
                  @method('DELETE')
                 <button type="submit" class="btn btn-sm btn-soft text-danger">
                       <i class="bi bi-trash"></i>
                </button>
                </form>

                </div>
              </div>
            </article>
          </div>
          @endforeach
          <div class="col-sm-6 col-lg-4 col-xl-3">
            <button
              class="card border-2 border-dashed h-100 w-100 text-center bg-transparent"
              style="min-height: 260px"
              data-bs-toggle="modal"
              data-bs-target="#productModal"
              onclick="openAddModal()"
            >
              <div
                class="card-body d-flex flex-column align-items-center justify-content-center"
              >
                <span class="metric-icon"><i class="bi bi-plus-lg"></i></span
                ><strong class="mt-3">Add a new product</strong
                ><span class="small text-muted-custom mt-1"
                  >Keep your menu fresh</span
                >
              </div>
            </button>
          </div>
        </section>
      </main>
    </div>
    <div class="modal fade" id="productModal" tabindex="-1">
      <div class="modal-dialog">
        <form class="modal-content demo-form" id="productForm" action="/products" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="_method" id="formMethod" value="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="productModalTitle" >Add Product</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Product Name</label
              ><input
                class="form-control"
                name ="name"
                placeholder="e.g. Vanilla Celebration Cake"
                required
              />
            </div>
            <div class="mb-3">
                <label class="form-label">Product Image</label>
                <input type="file" class="form-control" name="image" accept="image/*">
          </div>
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="form-label">Category</label
                ><select class="form-select" name="category">
                  <option>Cakes</option>
                  <option>Cupcakes</option>
                  <option>Brownies</option>
                  <option>Cookies</option>
                  <option>Desserts</option>
                  <option>Catering</option>
                </select>
              </div>
              <div class="col-sm-6">
                <label class="form-label">Price (PKR)</label
                ><input type="number" name="price" class="form-control" placeholder="2500" />
              </div>
            </div>
            <div class="mt-3">
              <label class="form-label">Description</label
              ><textarea
                class="form-control"
                name="description"
                rows="3"
                placeholder="Short product description"
              ></textarea>
            </div>
            <div class="form-check mt-3">
              <input
                class="form-check-input"
                name="is_available"
                type="checkbox"
                checked
                id="available"
              /><label class="form-check-label" for="available"
                >Available to order</label
              >
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
              Cancel</button
            ><button type="submit" class="btn btn-gradient">Save Product</button>
          </div>
        </form>
      </div>
    </div>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div id="appToast" class="toast border-0">
        <div class="toast-body bg-dark text-white rounded">
          <span id="toastText"></span>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
  </body>
</html>
