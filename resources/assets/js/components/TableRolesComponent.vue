<template>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tabla de Roles:</h3>
            <button type="button"
                class="btn btn-default btn-xs"
                data-tool="tooltip"
                data-placement="top"
                title="Registrar Rol"
                v-if="action('create') || action('update')"
                @click="openform('create')"><span class="glyphicon glyphicon-plus"></span></button>
            <button href="#"
                class="btn btn-default btn-xs"
                data-tool="tooltip"
                data-placement="top"
                :data-original-title="((formData.rol.name) ? 'Borrar Rol' : 'Seleccione un rol')"
                @click="destroy(formData.rol)"
                v-if="action('destroy')"><span class="glyphicon glyphicon-trash"></span></button>
            <v-modal-fom @update="get(pagination.current_page)" :formData="formData" v-if="action('create') || action('update')"></v-modal-fom>
        </div>
        <div v-if="action('index')" class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <header-table :n="num" :s="search" :a="[10,20,30,50]" @changeN="num = arguments[0]" @changeS="search= arguments[0]"></header-table>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">N°</th>
                                <th width="25%">Nombre</th>
                                <th width="15%">Comienzo de actividad</th>
                                <th width="15%">Final de actividad</th>
                                <th width="20%">Caracteristica Especial</th>
                            </tr>
                        </thead>
                        <tbody v-if="roles === []">
                            <spinner></spinner>
                        </tbody>
                        <tbody v-else>
                            <tr v-for="rol in roles"
                                data-tool="tooltip"
                                title="Doble click para editar"
                                @click="formData.rol = rol"
                                @dblclick="openform('edit', rol)"
                                v-if="rol.name">
                                <td class="text-center"><strong>{{ rol.id }}</strong></td>
                                <td>{{ rol.name }}</td>
                                <td>{{ rol.from_at }}</td>
                                <td>{{ rol.to_at }}</td>
                                <td>{{ rol.special }}</td>
                            </tr>
                            <tr class="text-center" v-else>
                                <td colspan="5">
                                    Rol Eliminado con exito. Desea deshacer el cambio?
                                    <a href="#"
                                    class="btn btn-info btn-xs"
                                    @click.prevent="restore(rol)"
                                    v-if="action('restore')">click aqui</a>
                                    <a href="#"
                                    class="btn btn-success btn-xs"
                                    @click.prevent="get">Continuar</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row justify-content-center">
                        <pagination @changePage="get(arguments[0])" :data="pagination"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ModalForm from './forms/modal-form-rol.vue';
import Pagination from './partials/pagination.vue';
import headerTable from './partials/header-table.vue';

export default {
    components: {
        'v-modal-fom': ModalForm,
        'pagination': Pagination,
        'header-table': headerTable
    },
    data() {
        return {
            roles: false,
            permissions: [],
            formData: {
                ready: false,
                url: '',
                rol: {}
            },
            pagination: {},
            search: '',
            num: 10
        };
    },
    mounted() {
        this.get();
    },
    watch: {
        search: _.debounce(function () {this.get(); }, 500),
        num: function () {this.get(); }
    },
    methods: {
        action: function (accion) {
            return this.can(accion, this.permissions);
        },
        get: function (page = null) {
            let url =  '/admin/roles?page=' + ((page) ? page : 1);
            if (this.search) {url += '&search=' + this.search;}
            if (this.num) {url += '&num=' + this.num;}
            axios.get(url)
            .then(response => {
                this.roles = response.data.roles;
                this.pagination = response.data.pagination;
                this.permissions = response.data.permissionsUser;
            })
            .then(() => {
                $('[data-tool="tooltip"]').tooltip();
                this.formData.rol = {};
                $('tbody tr').click(function () {
                    $('tbody tr').removeClass('info');
                    $(this).addClass('info');
                });
                $('tbody tr').dblclick(function () {
                    $('tbody tr').removeClass('info');
                });
            });
        },
        openform: function (cond, rol = null) {
            this.formData.ready = false;
            $('form#RolesForm')[0].reset();
            if (cond == 'create') {
                this.formData.url = '/admin/roles';
                this.formData.title = 'Registrar Rol.';
                this.formData.ico = 'plus';
                this.formData.rol.permissions = [];
                this.formData.rol = {
                    name: '',
                    slug: '',
                    description: '',
                    from_at: '',
                    to_at: '',
                    special: '',
                    permissions: []
                };
                this.formData.ready = true;
            } else if (cond == 'edit') {
                this.formData.url = '/admin/roles/' + rol.id;
                this.formData.ico = 'edit';
                this.formData.title = 'Editar Rol ' + rol.name + '.';
                axios.get('/admin/roles/' + rol.id)
                .then(response => {
                    this.formData.rol = response.data;
                    this.formData.ready = true;
                });
            }
            $('#ModalRol').modal('toggle');
            this.formData.cond = cond;
        },
        destroy: function (rol) {
            if (rol.id === 1 || rol.id == undefined) return;
            axios.delete('/admin/roles/' + rol.id)
            .then(response => {
                console.log('Rol borrado de forma exitosa.');
                this.formData.rol.name = false;
            });
        },
        restore: function (rol) {
            axios.post('/admin/restore-rol/' + rol.id)
            .then(response => {
                console.log('Rol restaurado de forma exitosa.');
                rol.name = response.data;
            });
        }
    }
}
</script>
