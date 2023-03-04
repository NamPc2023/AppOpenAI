<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Tables / Data - NiceAdmin Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  @include('Admin.layout.header');
</head>


<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
  
      <div class="d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
          <img src="assets/img/logo.png" alt="">
          <span class="d-none d-lg-block">NiceAdmin</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
      </div><!-- End Logo -->
  
      <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="POST" action="#">
          <input type="text" name="query" placeholder="Search" title="Enter search keyword">
          <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
      </div><!-- End Search Bar -->
  
      @include('Admin.layout.nav');
  
    </header><!-- End Header -->
  
    <!-- ======= Sidebar ======= -->
    @include('Admin.layout.sidebar');
  
    <main id="main" class="main">
  
      <div class="pagetitle">
        <h1>Tạo outline</h1>

      </div><!-- End Page Title -->
  
      <section class="section">
        <div class="row">
          <div class="col-lg-12">
              <form action="/dashboard/outline/create" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                @if(session('postName'))
                    <div class="mb-3">
                        <label for="" class="form-label">Tên outline</label>
                        <input type="text" name="postName" value="{{ session('postName') }}" class="form-control">
                        @error('postName')
                          <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @else
                    <div class="mb-3">
                        <label for="" class="form-label">Tên outline</label>
                        <input type="text" name="postName" class="form-control">
                        @error('postName')
                          <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                 @if(session('outline'))
                    <div class="mb-3">
                        <label for="" class="form-label">Nội dung</label>
                        <textarea class="form-control" name="outline" rows="10" cols="70">{{ session('outline') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Tạo lại</button>
                @else
                    <button type="submit" class="btn btn-primary">Tạo</button>
                @endif

              </form>
          </div>
        </div>
      </section>
  
    </main><!-- End #main -->
  
    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
      <div class="copyright">
        &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </footer><!-- End Footer -->
  
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    @include('Admin.layout.footer');
  
  </body>
  
</html>