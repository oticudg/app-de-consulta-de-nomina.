@extends('layouts.base.app')

@section('title', 'Sistema del ')

@section('end')
<body class="hold-transition login-page layout-top-nav">
    {{-- Site wrapper --}}
    <div id="app" class="wrapper">
        <div class="login-box">
            <div class="login-logo">
                {!! config('frontend.logo_lg') !!}
            </div>
            {{-- /.login-logo --}}
            <div class="login-box-body">
                <p class="login-box-msg">Ingresa la cédula a consultar.</p>
                <form id="cedula-form" @submit.prevent="enviar">
                    @csrf
                    <div class="form-group {{ $errors->has('cedula') ? 'has-error' : '' }} has-feedback">
                        <label for="cedula" class="control-label">Cédula:</label>
                        <input id="cedula" type="text" class="form-control" name="cedula"  placeholder="Cédula" value="" autofocus v-model="cedula">
                        <span class="glyphicon glyphicon-credit-card form-control-feedback"></span>
                        <small id="cedulaHelp" class="help-block">Ingrese el número de cédula.</small>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Consultar  </button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Trabajador: @{{ trabajador.nombres }} @{{ trabajador.apellidos }}.</h4>
                    </div>
                    <div class="modal-body">
                        <table id="records" class="table table-striped table-bordered table-hover table-condensed">
                            <tbody>
                                <tr>
                                    <th>Nombres:</th>
                                    <td>@{{ trabajador.nombres }}</td>
                                    <th>Apellidos:</th>
                                    <td>@{{ trabajador.apellidos }}</td>
                                </tr>
                                <tr>
                                    <th>Cedula:</th>
                                    <td>@{{ trabajador.cedula_a }}</td>
                                    <th>Cargo:</th>
                                    <td>@{{ trabajador.den_cargo }}</td>
                                </tr>
                                <tr>
                                    <th>Ubicación:</th>
                                    <td>@{{ trabajador.den_ubi }}</td>
                                    <th>Tipo de cargo:</th>
                                    <td>@{{ trabajador.des_age }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha de ingreso:</th>
                                    <td>@{{ trabajador.fec_ing }}</td>
                                    <th></th>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="
    glyphicon glyphicon-remove"></span> Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="entregar()"><span class="
    glyphicon glyphicon-ok"></span> Entregado</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/iCheck/square/blue.css') }}">
@endsection
