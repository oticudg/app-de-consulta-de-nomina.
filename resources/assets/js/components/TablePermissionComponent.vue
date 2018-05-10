<template>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tabla de Permisos:</h3>
            <v-modal-form @update="get(pagination.current_page)" :formData="formData" v-if="action('update')"></v-modal-form>
        </div>
        <div v-if="action('index')" class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <header-table :n="num" :s="search" :a="[10,20,30,50]" @changeN="num = arguments[0]" @changeS="search= arguments[0]"></header-table>
                    <table class="table table-bordered table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th class="text-center">Activo</th>
                            </tr>
                        </thead>
                        <tbody v-if="permisos === []">
                            <spinner></spinner>
                        </tbody>
                        <tbody v-else>
                            <tr v-for="permission in permisos"
                                data-tool="tooltip"
                                title="Doble click para editar"
                                :class="{'info': permission.id == formData.permission.id}"
                                @click="formData.permission = permission"
                                @dblclick="openform('edit', permission)">
                                <th>{{ permission.id }}</th>
                                <td>{{ permission.name | capitalize }}</td>
                                <td>{{ permission.description | capitalize }}</td>
                                <td class="text-center" v-if="action('desActived')">
                                    <a href="#" @click.prevent="restore(permission)" v-if="permission.deleted_at"><span class="glyphicon glyphicon-unchecked"></span></a>
                                    <a href="#" @click.prevent="destroy(permission)" v-else><span class="glyphicon glyphicon-check"></span></a>
                                </td>
                                <td v-else>
                                    <span v-if="permission.deleted_at">Desactivado</span>
                                    <span v-else>Activo</span>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td class="text-center" colspan="5">Cargando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <pagination @changePage="get(arguments[0])" :data="pagination"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
import ModalForm from './forms/modal-form-permission.vue';
import Pagination from './partials/pagination.vue';
import headerTable from './partials/header-table.vue';

export default {
    components: {
        'v-modal-form': ModalForm,
        'pagination': Pagination,
        'header-table': headerTable
    },
    data() {
        return {
            permisos: [],
            permissions: [],
            formData: {
                ready: false,
                cond: '',
                url: '',
                permission: {},
                permissions: []
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
        action: function (accion) {return this.can(accion, this.permissions); },
        get: function (page = null) {
            let url =  '/admin/permisos?page=' + ((page) ? page : 1);
            if (this.search) {url += '&search=' + this.search; }
            if (this.num) {url += '&num=' + this.num; }
            axios.get(url)
            .then(response => {
                this.permisos = response.data.permissions;
                this.permissions = response.data.permissionsUser;
                this.pagination = response.data.pagination;
            })
            .then(() => {
                $('[data-tool="tooltip"]').tooltip();
            });
        },
        openform: function (cond, permission = null) {
            this.formData.ready = false;
            if (cond == 'edit') {
                $('#ModalPermission').modal('toggle')
                this.formData.url = '/admin/permisos/' + permission.id;
                axios.get(this.formData.url)
                .then(response => {
                    this.formData.permission = response.data;
                    this.formData.title = 'Editar Permiso: ' + response.data.name + '.';
                    this.formData.ready = true;
                });
            }
            this.formData.cond = cond;
        },
        destroy: function (permission) {
            axios.delete('/admin/permisos/' + permission.id)
            .then(response => {
                toastr.success('Permiso desactivado.');
                permission.deleted_at = true;
            });
        },
        restore: function (permission) {
            axios.post('/admin/restore-permission/' + permission.id)
            .then(response => {
                toastr.success('Permiso restaurado.');
                permission.deleted_at = false;
            });
        }
    }
}
</script>
