<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            {{-- <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="index.html"><img src="{{asset('assets/img/logo.png')}}" alt="Logo" srcset=""></a>
                    <!-- <h4>HRIS - KPT</h4> -->
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div> --}}
            <div class=" d-flex flex-column align-items-center logo">
                <div>
                    <img src="{{ asset('assets/img/logo.png') }}" alt="" style="height: 5rem !important;">
                </div>
                <div class="fw-bold mt-2 ms-3" style="font-size: 1.5rem;">HRIS-KPT</div>
                <span style="font-size: 10px;">Human Resource Information System</span>
            </div>
            <div class="theme-toggle d-flex gap-2  align-items-center mt-2 d-none">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                    role="img" class="iconify iconify--system-uicons" width="20" height="20"
                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                    <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                            opacity=".3"></path>
                        <g transform="translate(-210 -1)">
                            <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                            <circle cx="220.5" cy="11.5" r="4"></circle>
                            <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                        </g>
                    </g>
                </svg>
                <div class="form-check form-switch fs-6">
                    <input class="form-check-input  me-0" type="checkbox" id="toggle-dark">
                    <label class="form-check-label"></label>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                    role="img" class="iconify iconify--mdi" width="20" height="20"
                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                    </path>
                </svg>
            </div>
            <div class="sidebar-toggler x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                @php
                    $allPermissionMenus = ['lihat dashboard', 'lihat proyek', 'lihat job order', 'lihat roster', 'lihat absensi'];
                @endphp
                @can($allPermissionMenus)
                    <li class="sidebar-title">Menu</li>
                @endcan
                @can('lihat dashboard')
                    <li class="sidebar-item {{ isActive('dashboard') }} ">
                        <a href="{{ route('dashboard.index') }}" class='sidebar-link'>
                            <i class="bi bi-file-bar-graph"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endcan
                @can('lihat proyek')
                    <li class="sidebar-item {{ isActive('project') }} ">
                        <a href="{{ route('project.index') }}" class='sidebar-link'>
                            <i class="bi bi-pencil"></i>
                            <span>Proyek</span>
                        </a>
                    </li>
                @endcan
                @can('lihat job order')
                    <li class="sidebar-item {{ isActive('job-order') }} ">
                        <a href="{{ route('jobOrder.index') }}" class='sidebar-link'>
                            <i class="bi bi-pencil-square"></i>
                            <span>Job Order</span>
                        </a>
                    </li>
                @endcan
                @can('lihat kasbon')
                    <li class="sidebar-item {{ isActive('salary-advance') }} ">
                        <a href="{{ route('salaryAdvance.index') }}" class='sidebar-link'>
                            <i class="bi bi-receipt"></i>
                            <span>Kasbon</span>
                        </a>
                    </li>
                @endcan
                @can('lihat cuti')
                    <li class="sidebar-item {{ isActive('vacation') }} ">
                        <a href="{{ route('vacation.index') }}" class='sidebar-link'>
                            <i class="bi bi-arrow-up-left-circle"></i>
                            <span>Cuti</span>
                        </a>
                    </li>
                @endcan
                {{-- @can('lihat surat perintah lembur')
                    <li class="sidebar-item {{ isActive('overtime') }} ">
                        <a href="{{ route('overtime.index') }}" class='sidebar-link'>
                            <i class="bi bi-cloud-moon"></i>
                            <span>Surat Perintah Lembur</span>
                        </a>
                    </li>
                @endcan --}}
                @can('lihat roster')
                    <li class="sidebar-item {{ isActive('roster') }} ">
                        <a href="{{ route('roster.index') }}" class='sidebar-link'>
                            <i class="bi bi-list-check"></i>
                            <span>Roster</span>
                        </a>
                    </li>
                @endcan
                @can('lihat absensi')
                    <li class="sidebar-item {{ isActive('attendance') }} ">
                        <a href="{{ route('attendance.index') }}" class='sidebar-link'>
                            <i class="bi bi-fingerprint"></i>
                            <span>Absensi</span>
                        </a>
                    </li>
                @endcan
                @php
                    $allPermissionSalary = ['lihat periode gaji', 'lihat slip gaji karyawan', 'lihat penggajian', 'lihat penyesuaian gaji'];
                @endphp
                @canany($allPermissionSalary)
                    <li
                        class="sidebar-item {{ isActive('period_payroll') || isActive('payslip') || isActive('payroll') || isActive('salary-adjustment') }} has-sub">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-cash-coin"></i>
                            <span>Gaji</span>
                        </a>
                        <ul class="submenu {{ isActive('period_payroll') || isActive('payslip') || isActive('payroll') || isActive('salary-adjustment') }}"
                            style="{{ Request::is('payslip') || Request::is('payroll') || Request::is('salary-adjustment') ? 'display: block;' : 'display: none;' }}">
                            @can('lihat periode gaji')
                                <li class="submenu-item {{ isActive('period_payroll') }}">
                                    <a href="{{ route('period_payroll.index') }}">Periode Gaji</a>
                                </li>
                            @endcan
                            @can('lihat slip gaji')
                                <li class="submenu-item {{ isActive('payslip') }}">
                                    <a href="{{ route('payslip.index') }}">Slip Gaji Karyawan</a>
                                </li>
                            @endcan
                            @can('lihat penggajian')
                                <li class="submenu-item {{ isActive('payroll') }}">
                                    <a href="{{ route('payroll.monthly') }}">Penggajian Bulanan</a>
                                </li>
                            @endcan
                            @can('lihat penyesuaian gaji')
                                <li class="submenu-item {{ isActive('salary-adjustment') }}">
                                    <a href="{{ route('salaryAdjustment.index') }}">Penyesuaian Gaji</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @php
                    $allPermissionReports = ['lihat laporan job order', 'lihat laporan kasbon', 'lihat surat perintah lembur'];
                @endphp
                @canany($allPermissionReports)
                    <li class="sidebar-title has-sub">Laporan</li>
                @endcanany
                @can('lihat laporan job order')
                    <li class="sidebar-item {{ isActive('job-order-report') }} ">
                        <a href="{{ route('report.jobOrder.index') }}" class='sidebar-link'>
                            <i class="bi bi-file-earmark-bar-graph"></i>
                            <span>Laporan Job Order</span>
                        </a>
                    </li>
                @endcan
                @can('lihat laporan kasbon')
                    <li class="sidebar-item {{ isActive('salary-advance-report') }} ">
                        <a href="{{ route('report.salaryAdvance.index') }}" class='sidebar-link'>
                            <i class="bi bi-file-earmark-bar-graph"></i>
                            <span>Laporan Kasbon</span>
                        </a>
                    </li>
                @endcan
                @can('lihat laporan surat perintah lembur')
                    <li class="sidebar-item {{ isActive('overtime-report') }} ">
                        <a href="{{ route('report.overtime.index') }}" class='sidebar-link'>
                            {{-- <i class="bi bi-cloud-moon"></i> --}}
                            <i class="bi bi-file-earmark-bar-graph"></i>
                            <span>Laporan Surat Perintah Lembur</span>
                        </a>
                    </li>
                @endcan
                @can('lihat laporan cuti')
                    <li class="sidebar-item {{ isActive('vacation-report') }} ">
                        <a href="{{ route('report.vacation.index') }}" class='sidebar-link'>
                            {{-- <i class="bi bi-cloud-moon"></i> --}}
                            <i class="bi bi-file-earmark-bar-graph"></i>
                            <span>Laporan Cuti</span>
                        </a>
                    </li>
                @endcan
                @php
                    $allPermissionMains = ['lihat departemen', 'lihat departemen', 'lihat jabatan', 'lihat karyawan', 'lihat jenis karyawan'];
                @endphp
                @canany($allPermissionMains)
                    <li class="sidebar-title has-sub">Utama</li>
                    <li
                        class="sidebar-item {{ Request::is('master/employee') || Request::is('master/employee-type') || Request::is('master/departmen') || Request::is('master/position') ? 'active' : '' }} has-sub">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-people"></i>
                            <span>Karyawan</span>
                        </a>
                        <ul class="submenu {{ Request::is('master/employee') || Request::is('master/employee-type') || Request::is('master/departmen') || Request::is('master/position') ? 'active' : '' }}"
                            style="{{ Request::is('master/employee') || Request::is('master/employee-type') || Request::is('master/departmen') || Request::is('master/position') ? 'display: block;' : 'display: none;' }}">
                            @can('lihat karyawan')
                                <li class="submenu-item {{ isActive('master/employee') }}">
                                    <a href="{{ route('master.employee.index') }}">Daftar Karyawan</a>
                                </li>
                            @endcan
                            @can('lihat jenis karyawan')
                                <li class="submenu-item {{ isActive('master/employee-type') }}">
                                    <a href="{{ route('master.employeeType.index') }}">Jenis Karyawan</a>
                                </li>
                            @endcan
                            @can('lihat departemen')
                                <li class="submenu-item {{ isActive('master/departmen') }}">
                                    {{-- <a href="{{ route('master.departmen.index') }}">Jabatan</a> --}}
                                    <a href="{{ route('master.departmen.index') }}">Departemen</a>
                                </li>
                            @endcan
                            @can('lihat jabatan')
                                <li class="submenu-item {{ isActive('master/position') }}">
                                    {{-- <a href="{{ route('master.position.index') }}">Jabatan</a> --}}
                                    <a href="{{ route('master.position.index') }}">Jabatan</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @php
                    $allPermissionWork = ['lihat jenis pekerjaan', 'lihat jam kerja'];
                @endphp
                @canany($allPermissionWork)
                    <li
                        class="sidebar-item {{ Request::is('master/job') || Request::is('master/schedule') || Request::is('master/working-hour') ? 'active' : '' }} has-sub">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-folder-check"></i>
                            <span>Pekerjaan</span>
                        </a>

                        <ul class="submenu {{ Request::is('master/job') || Request::is('master/schedule') || Request::is('master/working-hour') ? 'active' : '' }}"
                            style="{{ Request::is('master/job') || Request::is('master/schedule') || Request::is('master/working-hour') ? 'display: block;' : 'display: none;' }}">
                            @can('lihat jenis pekerjaan')
                                <li class="submenu-item {{ isActive('master/job') }}">
                                    <a href="{{ route('master.job.index') }}">Jenis Pekerjaan</a>
                                </li>
                            @endcan
                            {{-- @can('lihat jadwal kerja')
                                <li class="submenu-item {{ isActive('master/schedule') }}">
                                    <a href="{{ route('master.schedule.index') }}">Jadwal Kerja</a>
                                </li>
                            @endcan --}}
                            @can('lihat jam kerja')
                                <li class="submenu-item {{ isActive('master/working-hour') }}">
                                    <a href="{{ route('master.workingHour.index') }}">Jam Kerja</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @can('lihat perusahaan')
                    <li class="sidebar-item {{ isActive('master/company') }}">
                        <a href="{{ route('master.company.index') }}" class='sidebar-link'>
                            <i class="bi bi-building"></i>
                            <span>Perusahaan</span>
                        </a>
                    </li>
                @endcan
                @can('lihat kapal')
                    <li class="sidebar-item {{ isActive('master/barge') }}">
                        <a href="{{ route('master.barge.index') }}" class='sidebar-link'>
                            <i class="bi bi-wrench-adjustable-circle"></i>
                            <span>Kapal</span>
                        </a>
                    </li>
                @endcan
                @can('lihat pelanggan')
                    <li class="sidebar-item {{ isActive('master/customer') }}">
                        <a href="{{ route('master.customer.index') }}" class='sidebar-link'>
                            <i class="bi bi-person-check"></i>
                            <span>Pelanggan</span>
                        </a>
                    </li>
                @endcan
                {{-- @can('lihat bahan')
                    <li class="sidebar-item {{ isActive('master/material') }}">
                        <a href="{{ route('master.material.index') }}" class='sidebar-link'>
                            <i class="bi bi-box-seam"></i>
                            <span>Bahan</span>
                        </a>
                    </li>
                @endcan --}}
                @can('lihat lokasi')
                    <li class="sidebar-item {{ isActive('master/location') }}">
                        <a href="{{ route('master.location.index') }}" class='sidebar-link'>
                            <i class="bi bi-geo-alt"></i>
                            <span>Lokasi</span>
                        </a>
                    </li>
                @endcan
                @can('lihat alat finger')
                    <li class="sidebar-item {{ isActive('master/finger-tool') }}">
                        <a href="{{ route('master.fingerTool.index') }}" class='sidebar-link'>
                            <i class="bi bi-clipboard2-check"></i>
                            <span>Alat Absen</span>
                        </a>
                    </li>
                @endcan
                @php
                    $allPermissionUser = ['lihat pengguna', 'lihat grup pengguna'];
                    $allPermissionSetting = ['lihat penyesuaian gaji', 'lihat jam kerja', 'lihat pengguna', 'lihat grup pengguna', 'lihat fitur'];
                @endphp
                @canany($allPermissionSetting)
                    <li class="sidebar-title has-sub">Pengaturan</li>
                @endcanany
                @canany($allPermissionUser)
                    <li
                        class="sidebar-item {{ Request::is('setting/user') || Request::is('setting/role') ? 'active' : '' }} has-sub">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-people"></i>
                            <span>Pengguna</span>
                        </a>
                        <ul class="submenu {{ Request::is('setting/user') || Request::is('setting/role') ? 'active' : '' }}"
                            style="{{ Request::is('setting/user') || Request::is('setting/role') ? 'display: block;' : 'display: none;' }}">
                            @can('lihat pengguna')
                                <li class="submenu-item {{ isActive('setting/user') }}">
                                    <a href="{{ route('setting.user.index') }}">Pengguna</a>
                                </li>
                            @endcan
                            @can('lihat grup pengguna')
                                <li class="submenu-item {{ isActive('setting/role') }}">
                                    <a href="{{ route('setting.role.index') }}">Grup Pengguna</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @php
                    $allPermissionWork = ['lihat fitur', 'lihat perhitungan bpjs', 'lihat dasar upah bpjs'];
                @endphp
                @canany($allPermissionWork)
                    <li
                        class="sidebar-item {{ Request::is('setting/feature') || Request::is('setting/bpjs-calculation') || Request::is('setting/base-wages-bpjs') ? 'active' : '' }} has-sub">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-gear"></i>
                            <span>Pengaturan</span>
                        </a>

                        <ul class="submenu {{ Request::is('setting/feature') || Request::is('setting/bpjs-calculation') || Request::is('setting/base-wages-bpjs') ? 'active' : '' }}"
                            style="{{ Request::is('setting/feature') || Request::is('setting/bpjs-calculation') || Request::is('setting/base-wages-bpjs') ? 'display: block;' : 'display: none;' }}">
                            @can('lihat fitur')
                                <li class="submenu-item {{ isActive('setting/feature') }}">
                                    <a href="{{ route('setting.feature.index') }}">Fitur</a>
                                </li>
                            @endcan
                            @can('lihat dasar upah bpjs')
                                <li class="submenu-item {{ isActive('setting/base-wages-bpjs') }}">
                                    <a href="{{ route('setting.baseWagesBpjs.index') }}">Dasar Upah BPJS</a>
                                </li>
                            @endcan
                            @can('lihat perhitungan bpjs')
                                <li class="submenu-item {{ isActive('setting/bpjs-calculation') }}">
                                    <a href="{{ route('setting.bpjsCalculation.index') }}">Perhitungan BPJS</a>
                                </li>
                            @endcan
                            @can('lihat tingkat persetujuan')
                                <li class="submenu-item {{ isActive('setting/approvalLevel') }}">
                                    <a href="{{ route('setting.approvalLevel.index') }}">Tingkat Persetujuan</a>
                                </li>
                            @endcan
                            @can('lihat log')
                                <li class="submenu-item {{ isActive('setting/log') }}">
                                    <a href="{{ route('setting.log.index') }}">Log</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
            </ul>
        </div>
    </div>
</div>
