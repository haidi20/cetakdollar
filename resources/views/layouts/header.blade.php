<header class='mb-2'>
    <nav class="navbar navbar-expand navbar-light navbar-top">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                </ul>
                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-menu d-flex">
                            <div class="user-name text-end">
                                <h6 class="mb-0 text-gray-600">{{ Auth::user()->name }}</h6>
                                <p class="mb-0 text-sm text-gray-600">{{ Auth::user()->role->name }}</p>
                            </div>
                            <!-- <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="{% static 'assets/images/logo/logo-umkt.png' %}">
                                </div>
                            </div> -->
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                        style="min-width: 11rem; margin-top: 20%;">
                        <li><a class="dropdown-item" target="_blank" href="/dokumentasi/1.0/menu/dashboard"><i
                                    class="icon-mid bi bi-book me-2"></i>
                                Dokumentasi</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <!-- <a class="dropdown-item" href="logout"><i
                                    class="icon-mid bi bi-box-arrow-left me-2"></i>
                                Logout</a> -->
                            <a data-bs-toggle="modal" data-bs-target="#logout" class='dropdown-item text-danger'
                                style="cursor: pointer;">
                                <i class="icon-mid bi bi-box-arrow-left me-2 text-danger"></i>
                                <span>Keluar</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
