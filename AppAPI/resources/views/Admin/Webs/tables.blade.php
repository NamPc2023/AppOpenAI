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
        <h1>Danh sách trang web</h1>
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

                <!-- Table with stripped rows -->
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th scope="col">STT</th>
                      <th scope="col">Tên</th>
                      <th scope="col">Link đăng nhập</th>
                      <th scope="col">Link tạo bài viết</th>
                      <th scope="col">Link lưu bài viết</th>
                      <th scope="col">Admin</th>
                      <th scope="col">Password</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(count($data) > 0)
                        @foreach ($data as $k => $value)
                            <tr>
                                <th scope="row">{{++$k}}</th>
                                <td>{{$value->name}}</td>
                                <td>{{$value->login_url}}</td>
                                <td>{{$value->post_new_url}}</td>
                                <td>{{$value->post_save_url}}</td>
                                <td>{{$value->admin}}</td>
                                <td>{{$value->password}}</td>
                                <td>
                                    <button type="button" class="btn btn-primary">
                                        <a style="color: aliceblue" href="/dashboard/web-edit/{{$value->id}}">Edit</a>
                                    </button>
                                    <button type="button" class="btn btn-danger">
                                        <a style="color: aliceblue" href="/dashboard/web-delete/{{$value->id}}">Delete</a>
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