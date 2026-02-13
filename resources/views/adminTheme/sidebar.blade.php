<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto"><a class="navbar-brand m-0 p-0" href="{{ route('dashboard') }}">
            <img src="{{ asset($webSetting['logo']) }}" alt="Promotions247" style="height: auto;width: 100%;">
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <hr>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            
            <li class="nav-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('dashboard') }}">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboard">Dashboard</span>
                </a>
            </li>
            <li class="nav-item has-sub"><a href="#"><i class="fa fa-user-o" aria-hidden="true"></i><span class="menu-title" data-i18n="Icons">User/Payments</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ Request::is('admin/user*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('user.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">User</span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('admin/payment*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('payment.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">Payment</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-sub"><a href="#"><i class="fa fa-industry" aria-hidden="true"></i><span class="menu-title" data-i18n="Icons">Job/Industry</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ Request::is('admin/industry-job*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('industry-job.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">Job Industry</span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('admin/job*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('job.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">Job</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-sub"><a href="#"><i class="fa fa-globe" aria-hidden="true"></i><span class="menu-title" data-i18n="Icons">Country/State/City</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ Request::is('admin/country*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('country.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">Country</span>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::is('admin/state*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('state.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">State</span>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::is('admin/city*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('city.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">City</span>
                        </a>
                    </li>   
                </ul>
            </li>

            <li class="nav-item has-sub"><a href="#"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="menu-title" data-i18n="Icons">Buy/Sell Products</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ Request::is('admin/product') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('product.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">Product</span>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::is('admin/product-category*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('product-category.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Product Category">Category</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-sub"><a href="#"><i class="fa fa-music" aria-hidden="true"></i><span class="menu-title" data-i18n="Icons">Artist</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ Request::is('admin/artist') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('artist.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">Artist</span>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::is('admin/genres*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('genres.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="genres">genres</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-sub"><a href="#"><i class="fa fa-line-chart" aria-hidden="true"></i><span class="menu-title" data-i18n="Icons">On Demand</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ Request::is('admin/on-demand-category') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('on-demand-category.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">Category</span>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::is('admin/on-demand-service*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('on-demand-service.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="genres">Service</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-sub"><a href="#"><i class="fa fa-tag" aria-hidden="true"></i><span class="menu-title" data-i18n="Icons">Whole Sell</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ Request::is('admin/whole-sell-category') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('whole-sell-category.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">Category</span>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::is('admin/whole-sell-product*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('whole-sell-product.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="genres">Product</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-sub"><a href="#"><i class="fa fa-home" aria-hidden="true"></i><span class="menu-title" data-i18n="Icons">Franchise</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ Request::is('admin/franchise-category') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('franchise-category.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">Category</span>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::is('admin/franchise-business*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('franchise-business.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="genres">Product</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-sub"><a href="#"><i class="fa fa-map-signs" aria-hidden="true"></i><span class="menu-title" data-i18n="Icons">Tourism</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ Request::is('admin/tourism-category') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('tourism-category.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">Category</span>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::is('admin/tourism-business*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('tourism-business.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="genres">Tourism</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item has-sub"><a href="#"><i class="fa fa-building-o" aria-hidden="true"></i><span class="menu-title" data-i18n="Icons">Business</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ Request::is('admin/business-category*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('business-category.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">Category</span>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::is('admin/businesses*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('businesses.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="genres">Businesses</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {{ Request::is('admin/advertisement*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('advertisement.index') }}"><i class="fa fa-bullhorn" aria-hidden="true"></i><span class="menu-title text-truncate" data-i18n="Advertisement">Advertisement</span></a>
            </li>
            <li class="nav-item has-sub"><a href="#"><i class="fa fa-street-view" aria-hidden="true"></i><span class="menu-title" data-i18n="Icons">Property</span></a>
                <ul class="menu-content">
                    <li class="nav-item {{ Request::is('admin/property-category*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('property-category.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="Dashboard">Category</span>
                        </a>
                    </li>

                    <li class="nav-item {{ Request::is('admin/propertyes*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('propertyes.index') }}">
                            <i class="fa fa-circle icone-drop" aria-hidden="true"></i>
                            <span class="menu-title text-truncate" data-i18n="genres">Property</span>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- <li class="nav-item has-sub"><a href="#"><i class="fa fa-id-card-o" aria-hidden="true"></i><span class="menu-title" data-i18n="Icons">User</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('admin/operation*') ? 'active' : '' }} nav-item">
                        <a href="#"><i class="icon-user"></i><span class="menu-title title">Operation</span></a>
                    </li>
                </ul>
            </li> --}}
        </ul>
    </div>
</div>