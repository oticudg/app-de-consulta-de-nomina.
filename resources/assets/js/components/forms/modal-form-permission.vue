<template>
  <div>
    <v-modal id="ModalPermission">

      <h4 class="card-title" slot="modal-title">
        <span class="glyphicon glyphicon-edit"></span>
        {{ formData.title }}
      </h4>

      <template slot="modal-body">
        <div class="row justify-content-center">
          <div class="col-md-10 col-md-offset-1">
            <form id="PermissionsForm" class="form-horizontal" @keyup.enter="registrar()">

              <spinner v-if="!formData.ready"></spinner>
              <div v-else>
                <template v-for="input in entries">
                  <v-input :name="input" required="true"
                          :readonly="input.readonly"
                          v-model="formData.permission[input.id]"
                          :msg="msg[input.id]"
                          @input="formData.permission[input.id] = arguments[0]">
                  </v-input>
                </template>

              </div>

            </form>
          </div>
        </div>
      </template>

      <button type="button" class="btn btn-primary" slot="modal-btn" @click="registrar()"><span class="glyphicon glyphicon-saved"></span> Guardar</button>

    </v-modal>
  </div>
</template>

<script>
  import Modal from './../partials/modal.vue';
  import Input from './../partials/input.vue';

  export default {
    name: 'modal-form-permission',
    components: {
      'v-modal': Modal,
      'v-input': Input
    },
    props: ['formData'],
    data () {
      return {
        msg: {
          name: 'Nombre del Permiso.',
          module: 'Modulo a ejecutarse.',
          action: 'Acción a Realizar.',
          description: 'Descripción a realizar.'
        },
        entries: [
          {label: 'Módulo', id: 'module', icon: 'edit', readonly: true},
          {label: 'Acción', id: 'action', icon: 'edit', readonly: true},
          {label: 'Nombre', id: 'name', icon: 'edit'},
          {label: 'Descripción', id: 'description', icon: 'edit'},
        ]
      };
    },
    methods: {
      registrar: function () {
        this.restoreMsg(this.msg);
        if (this.formData.cond === 'edit') {
          this.restoreMsg(this.msg)
          axios.put(this.formData.url, this.formData.permission)
          .then(response => {
            toastr.success('Permiso Editado.');
            $('#ModalPermission').modal('toggle');
            this.$emit('update');
          });
        }
      }
    }
  }
</script>
