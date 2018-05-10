{{-- sidebar menu: : style can be found in sidebar.less --}}
<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menú de Navegación</li>
    <li>
        <a href="{{ route('dashboard::index') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-laptop"></i>
            <span>Administración</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ route('admin::usuarios.index') }}"><i class="fa fa-users"></i> Usuarios</a></li>
            <li><a href="{{ route('admin::roles.index') }}"><i class="glyphicon glyphicon-compressed"></i> Roles</a></li>
            <li><a href="{{ route('admin::permisos.index') }}"><i class="glyphicon glyphicon-th text-info"></i> Permisos</a></li>
        </ul>
    </li>
    <li>
        <a href="{{ route('departaments::index') }}">
            <i class="fa fa-dashboard"></i> <span>Departamentos</span>
        </a>
    </li>
</ul>
