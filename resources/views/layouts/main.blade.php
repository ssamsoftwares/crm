@include('layouts.head')

    <body data-topbar="dark">

        <!-- Begin page -->
        <div id="layout-wrapper">

           @include('layouts.header')

            <!-- ========== Left Sidebar Start ========== -->
           @include('layouts.sidemenu')
            <!-- Left Sidebar End -->


            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">@stack('heading')</h4>
                                    
                                    <div class="page-title-right">
                                        @stack('heading-right')
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <!-- page content -->
                        @yield('content')

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                @include('layouts.footer')

            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        @include('layouts.footer-scripts')
    

    </body>
</html>
