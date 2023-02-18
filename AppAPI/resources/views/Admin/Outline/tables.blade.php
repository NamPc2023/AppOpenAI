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
        <h1>General Tables</h1>
        <button type="button" class="btn btn-success">
            <a style="color: aliceblue" href="/dashboard/post/outline">Add</a>
        </button>
        @if(count($errors) > 0)
            @foreach ($errors->all() as $error)
                <p style="color:rgb(0, 255, 76)">{{ $error }}</p>
            @endforeach
        @endif
      </div><!-- End Page Title -->
  
      <section class="section">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Table with stripped rows</h5>

                <!-- Table with stripped rows -->
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Title</th>
                      <th scope="col">Content</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(count($data) > 0)
                        @foreach ($data as $k => $value)
                            <tr>
                                <th scope="row">{{++$k}}</th>
                                <td>{{$value->title}}</td>
                                <td>{{$value->content}}</td>
                                <td>
                                    {{-- <button type="button" class="btn btn-primary">
                                        <a style="color: aliceblue" href="/dashboard/web-edit/{{$value->id}}">Edit</a>
                                    </button> --}}
                                    <button type="button" class="btn btn-danger">
                                        <a style="color: aliceblue" href="/dashboard/post-delete/{{$value->id}}">Delete</a>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    
                
                  </tbody>
                </table>
                <!-- End Table with stripped rows -->
  
              </div>
            </div>
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