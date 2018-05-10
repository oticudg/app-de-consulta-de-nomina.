<template>
    <div class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Lista de Departamentos: </h3>
                <button type="button"
                class="btn btn-default btn-xs"
                data-tool="tooltip"
                ModalDepartament
                data-placement="top"
                title="Registrar departamento"
                @click="openform('create')"
                ><span class="fa fa-cube"></span></button>
                <v-modal-form :formData="formData" @update="getDepartament"></v-modal-form>
                <v-modal-form-2 :formData="formData" @update="getDepartament"></v-modal-form-2>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <div class="col-xs-12 col-md-4">
                    <v-select v-model="departament" :options="departaments" @change="get(1)"></v-select>
                </div>
                <div class="col-xs-12 col-md-8" style="padding-top:5px;" v-if="departament > 0">
                    <div class="col-md-3 col-lg-2 text-center">
                        <button type="button" class="btn btn-default btn-xs" data-tool="tooltip" data-placement="top" title="Editar departamento" @click="openform('edit')"><span class="fa fa-edit"></span></button>
                        <button type="button" class="btn btn-default btn-xs" data-tool="tooltip" data-placement="top" title="Eliminar departamento" @click="openform('delete')"><span class="fa fa-close"></span></button> |
                        <button type="button" class="btn btn-default btn-xs" data-tool="tooltip" data-placement="top" title="Registrar division" @click="openformD('create')"><span class="fa fa-cubes"></span></button>
                    </div>
                    <div class="col-xs-12 col-md-9 col-lg-10">
                        <header-table :n="num" :s="search" :a="[9,12,15,18]" @changeN="num = arguments[0]" @changeS="search = arguments[0]"></header-table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12" style="min-height:350px">
            <div v-for="d in divisions" class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon" style="height: 95px;"><i class="fa fa-cubes"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">{{ d.name }}</span>
                        <span class="info-box-number"> 5,200 </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 50%"></div>
                        </div>
                        <span class="progress-description">
                            50%
                            <span class="pull-right">
                                <button type="button" class="btn btn-default btn-xs" data-tool="tooltip" data-placement="top" title="Registrar division" ><span class="fa fa-edit"></span></button>
                                <button type="button" class="btn btn-default btn-xs" data-tool="tooltip" data-placement="top" title="Registrar division" ><span class="fa fa-close"></span></button>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12" v-if="departament > 0">
            <pagination @changePage="get(arguments[0])" :data="pagination"></pagination>
        </div>
    </div>
</template>

<script>
import ModalForm from './forms/modal-form-departament.vue';
import ModalForm2 from './forms/modal-form-division.vue';
import Pagination from './partials/pagination.vue';
import headerTable from './partials/header-table.vue';
import Select2 from 'v-select2-component';

export default {
    components: {
        'v-modal-form': ModalForm,
        'v-modal-form-2': ModalForm2,
        'pagination': Pagination,
        'header-table': headerTable,
        'v-select': Select2,
    },
    data() {
        return {
            departaments: [], // ['op1'] or [{id: key, text: value}]
            departament: 0,
            divisions: [],
            formData: {
                ready: false,
                cond: '',
                url: '',
                departament: {},
                division: {},
                permissions: []
            },
            pagination: {},
            search: '',
            num: 9
        };
    },
    mounted() {
        this.getDepartament();
    },
    watch: {
        search: _.debounce(function () {this.get();}, 500),
        num: function () {this.get();}
    },
    methods: {
        action: function (accion) {return this.can(accion, this.permissions); },
        getDepartament: function () {
            this.divisions = [];
            this.departament = 0;
            axios.get('/departamentos')
            .then(response => {
                let departament = response.data.departament;
                let options = [];
                options.push({id:0, text:'Seleccione un departamento'});
                for (let i in departament) {
                    options.push({id:i,text:departament[i]});
                }
                this.departaments = options;
            });
        },
        get: function (page = null) {
            let url =  '/departamentos/' + this.departament + '/divisiones?page=' + ((page) ? page : 1);
            url += '&num=' + this.num;
            if (this.search) {url += '&search=' + this.search;}
            axios.get(url)
            .then(response => {
                this.divisions = response.data.division;
                this.pagination = response.data.pagination;
                this.permissions = response.data.permissionsUser;
            })
            .then(() => {
                $('[data-tool="tooltip"]').tooltip();
            });
        },
        openform: function (cond) {
            this.formData.ready = false;
            $('form#departamentForm')[0].reset();
            if (cond == 'create') {
                this.formData.url = '/departamentos';
                this.formData.title = 'Registrar Departamento.';
                this.formData.ico = 'plus';
                this.formData.departament = {
                    name: '',
                    slug: '',
                };
                this.formData.ready = true;
            } else {
                if (! this.departament) {
                    toastr.warning('Seleccione un departamento');
                    return;
                }
                this.formData.url = '/departamentos/' + this.departament;
                axios.get('/departamentos/' + this.departament)
                .then(response => {
                    if (cond == 'edit') {
                        this.formData.ico = 'edit';
                        this.formData.title = 'Editar Departamento: ' + response.data.name + '.';
                    } else {
                        this.formData.ico = ' fa fa-cube';
                        this.formData.title = 'Eliminar Departamento: ' + response.data.name + '.';
                    }
                    this.formData.departament = response.data;
                    this.formData.ready = true;
                });
            }
            $('#ModalDepartament').modal('toggle');
            this.formData.cond = cond;
        },
        openformD: function (cond) {
            // departamentos/divisiones
            this.formData.ready = false;
            $('form#divisionForm')[0].reset();
            if (cond == 'create') {
                this.formData.url = '/departamentos/divisiones';
                this.formData.title = 'Registrar Division.';
                this.formData.ico = 'plus';
                this.formData.division = {
                    name: '',
                    slug: '',
                };
                this.formData.ready = true;
            } else {
            // if (! this.departament) {
            //     toastr.warning('Seleccione un departamento');
            //     return;
            // }
            // this.formData.url = '/departamentos/' + this.departament;
            // axios.get('/departamentos/' + this.departament)
            // .then(response => {
            //     if (cond == 'edit') {
            //         this.formData.ico = 'edit';
            //         this.formData.title = 'Editar Departamento: ' + response.data.name + '.';
            //     } else {
            //         this.formData.ico = ' fa fa-cube';
            //         this.formData.title = 'Eliminar Departamento: ' + response.data.name + '.';
            //     }
            //     this.formData.departament = response.data;
            //     this.formData.ready = true;
            // });
            }
            $('#ModalDivision').modal('toggle');
            this.formData.cond = cond;
        }
    }
}
</script>
