@if($show == 'true')
    <!-- mobile search -->
    <div class="dropdown d-inline-block d-lg-none ms-2">
        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ri-search-line"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
            aria-labelledby="page-header-search-dropdown">

            <form class="p-3">
                <div class="mb-3 m-0">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search ...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif