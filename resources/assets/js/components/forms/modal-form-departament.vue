<template>
  <modal id="ModalDepartament" w="md">

    <template slot="modal-title">
      <span :class="'glyphicon glyphicon-'+formData.ico"></span>
      {{ formData.title }}
    </template>

    <div class="row" slot="modal-body">
      <div class="col-md-10 col-md-offset-1">
        <form id="departamentForm" class="form-horizontal" @keyup.enter="registrar">
          <spinner v-if="!formData.ready"></spinner>
          <div v-else>
            <div class="" v-for="input in entries">
              <v-input :name="input" required="true" v-model="formData.departament[input.id]" :msg="msg[input.id]" @input="formData.departament[input.id] = arguments[0]"></v-input>
            </div>
          </div>
        </form>
      </div>
    </div>

    <template slot="modal-btn">
      <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> Cerrar</button>
      <button type="button" class="btn btn-primary" @click="registrar" v-if="formData.cond === 'delete'"><span class="fa fa-close"></span> Eliminar</button>
      <button type="button" class="btn btn-primary" @click="registrar" v-else><span class="glyphicon glyphicon-saved"></span> Guardar</button>
    </template>

  </modal>
</template>

<script>
import Modal from './../partials/modal.vue';
import Input from './../partials/input.vue';

export default {
  name: 'modal-form-departament',
  components: {
    'modal': Modal,
    'v-input': Input,
  },
  props: ['formData'],
  data () {
    return {
      entries: [
      {label: 'Nombre', id: 'name', icon: 'fa fa-cube'},
      {label: 'Alias', id: 'slug', icon: 'fa fa-cube'},
      ],
      msg: {
        name: 'Nombre del departamento.',
        slug: 'Alias único del departamento.',
      }
    };
  },
  methods: {
    registrar: function () {
      this.restoreMsg(this.msg);
      if (this.formData.cond === 'create') {
        axios.post(this.formData.url, this.formData.departament)
        .then(response => {
          toastr.success('Departamento Creado');
          $('#ModalDepartament').modal('toggle');
          this.$emit('update');
        });
      } else if (this.formData.cond === 'edit') {
        axios.put(this.formData.url, this.formData.departament)
        .then(response => {
          toastr.success('Departamento Editado');
          $('#ModalDepartament').modal('toggle');
          this.$emit('update');
        });
      } else {
        axios.delete(this.formData.url)
        .then(response => {
          toastr.success('Departamento Eliminado');
          $('#ModalDepartament').modal('toggle');
          this.$emit('update');
        });
      }
    }
  }
}
</script>
