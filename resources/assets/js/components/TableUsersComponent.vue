<template>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tabla de Usuarios:</h3>
            <button type="button" class="btn btn-default btn-xs" v-if="action('create')" data-tool="tooltip" title="Registrar usuario" @click="openform('create')"><span class="glyphicon glyphicon-plus"></span></button>
            <v-modal-fom @update="get(pagination.current_page)" :formData="formData" v-if="action('create') || action('update')"></v-modal-fom>
            <a href="#" id="btn-delete" 
                        class="btn btn-default btn-xs"
                        data-tool="tooltip"
                        data-placement="top"
                        :data-original-title="((formData.user.id) ? 'Eliminar usuario' : 'Seleccione un usuario')"
                        @click.prevent="destroy(formData.user)"
                        v-if="action('destroy')"
            ><span class="glyphicon glyphicon-trash"></span></a>
        </div>
        <div v-if="action('index')" class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <header-table :n="num" :s="search" :a="[10,20,30,50]" @changeN="num = arguments[0]" @changeS="search= arguments[0]"></header-table>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th scope="col">N°</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Email</th>
                                <th scope="col">Modulo</th>
                                <th scope="col" width="250px">Rol</th>
                            </tr>
                        </thead>
                        <tbody v-if="users === []">
                            <spinner></spinner>
                        </tbody>
                        <tbody v-else>
                            <tr v-for="user in users"
                                v-if="user.name"
                                :class="{'info': user.id == formData.user.id}"
                                data-tool="tooltip"
                                :title="'Seleccionar a: ' + user.name + ' ' + user.last_name"
                                @click="formData.user = user"
                                @dblclick="openform('edit', user)">
                                <th scope="row">{{ user.id }}</th>
                                <td>
                                    <a :href="'/dashboard/perfil/'+user.id"
                                    v-if="action('show')">{{ user.name+' '+user.last_name | capitalize }}</a>
                                    <template v-else>{{ user.name+' '+user.last_name | capitalize }}</template>
                                </td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.module.module }}</td>
                                <td>
                                    <span class="badge badge-pill btn-primary" v-for="rol in user.roles">
                                        {{ rol.name }}
                                    </span>
                                </td>
                            </tr>
                            <tr class="text-center" v-else>
                                <td colspan="8">
                                    Usuario Eliminado con éxito. Desea deshacer el cambio?
                                    <a href="#"
                                    class="btn btn-success btn-xs"
                                    @click.prevent="restore(user)"
                                    v-if="action('restore')">click aqui.</a>
                                    <a href="#"
                                    class="btn btn-default btn-xs"
                                    @click.prevent="get">Continuar.</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <pagination @changePage="get(arguments[0])" :data="pagination"></pagination>
            </div>
        </div>
        <pre>{{ formData.user }}</pre>
    </div>
</template>

<script>
import ModalForm from './forms/modal-form-user.vue';
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
            users: false,
            permissions: [],
            formData: {
                ready: false,
                cond: '',
                url: '',
                user: {}
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
        search: _.debounce(function (newsearch, oldsearch) {this.get();}, 500),
        num: function () {this.get();}
    },
    methods: {
        action: function (accion) {return this.can(accion, this.permissions); },
        get: function (page = null) {
            let url =  '/admin/usuarios?page=' + ((page) ? page : 1);
            if (this.search) {url += '&search=' + this.search; }
            if (this.num) {url += '&num=' + this.num; }
            axios.get(url)
            .then(response => {
                this.users = response.data.users;
                this.pagination = response.data.pagination;
                this.permissions = response.data.permissionsUser;
            })
            .then(() => {
                $('[data-tool="tooltip"]').tooltip();
                this.formData.user = {};
                $('tbody tr').click(function () {
                    $('tbody tr').removeClass('info');
                    $(this).addClass('info');
                });
            });
        },
        openform: function (cond, user = null) {
            this.formData.ready = false;
            this.formData.title = '';
            if (cond == 'create') {
                this.formData.url = '/admin/usuarios';
                this.formData.title = ' Registrar Usuario.';
                this.formData.ico = 'plus';
                this.formData.user = {
                    title: '',
                    name: '',
                    last_name: '',
                    num_id: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    roles: [],
                    module_id: ''
                };
                this.formData.ready = true;
            } else if (cond == 'edit') {
                this.formData.url = '/admin/usuarios/' + user.id;
                axios.get('/admin/usuarios/' + user.id)
                .then(response => {
                    this.formData.user = response.data;

                    let roles = response.data.roles;
                    let options = [];
                    for (let rol in roles){
                        options.push({label:roles[rol].name,values:roles[rol].id});
                    }
                    this.formData.user.roles = options;
                    this.formData.ico = 'edit';
                    this.formData.title = 'Editar Usuario: ' + response.data.user + '.';
                    this.formData.ready = true;
                });
            }
            $('#ModalUser').modal('toggle');
            this.formData.cond = cond;
        },
        destroy: function (user) {
            if (user.id == undefined) {
                toastr.warning('Debe Seleccionar un usuario.');
                return;
            }
            axios.delete('/admin/usuarios/' + user.id)
            .then(response => {
                toastr.success('Usuario borrado.');
                user.name = false;
            });
        },
        restore: function (user) {
            axios.post('/admin/restore-user/' + user.id)
            .then(response => {
                toastr.success('Usuario restaurado.');
                user.name = response.data;
            });
        }
    }
}
</script>
