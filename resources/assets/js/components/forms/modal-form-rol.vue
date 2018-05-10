<template>
  <modal id="ModalRol" w="lg">

    <template slot="modal-title">
      <span :class="'glyphicon glyphicon-'+formData.ico"></span>
      {{ formData.title }}
    </template>

    <template slot="modal-body">
      <div class="row">
        <form id="RolesForm" class="form-horizontal" @keyup.enter="registrar()">
          <div class="col-md-10 col-md-offset-1">

            <spinner v-if="!formData.ready"></spinner>
            <div v-else>
              <div class="col-md-5">
                <template v-for="input in entries.izq">
                  <v-input :name="input" required="true"
                          v-model="formData.rol[input.id]"
                          :msg="msg[input.id]"
                          @input="formData.rol[input.id] = arguments[0]">
                  </v-input>
                </template>
              </div>

              <div class="col-md-5 col-md-offset-1">
                <template v-for="input in entries.der">
                  <v-input :name="input" required="true"
                          v-model="formData.rol[input.id]"
                          :msg="msg[input.id]"
                          @input="formData.rol[input.id] = arguments[0]">
                  </v-input>
                </template>

              <v-select name="special"
                      v-model="formData.rol.special"
                      @input="formData.rol.special = arguments[0]">
                <span slot="text">
                  <span class="glyphicon glyphicon-edit"></span>
                  Caracteristica especial
                </span>
                <template slot="options">
                  <option value="">Ninguna</option>
                  <option value="all-access">Acceso total</option>
                  <option value="no-access">Sin acceso</option>
                </template>
                <span slot="help" v-text="msg.special"></span>
              </v-select>
              </div>

              <v-checkbox-p v-if="!formData.rol.special"
                        :user="formData.rol.permissions"
                        @check="formData.rol.permissions = arguments[0]"
                        ></v-checkbox-p>

            </div>

          </div>
        </form>
      </div>
    </template>

    <template slot="modal-btn">
      <button type="button" class="btn btn-default" data-dismiss="modal" @click="close"><span class="fa fa-close"></span> Cerrar</button>
      <button type="button" class="btn btn-primary" @click="registrar"><span class="glyphicon glyphicon-saved"></span> Guardar</button>
    </template>

  </modal>
</template>

<script>
  import Modal from './../partials/modal.vue';
  import Input from './../partials/input.vue';
  import Select from './../partials/select.vue';
  import Checkbox from './../partials/input-checkbox-permissions.vue';

  export default {
    name: 'modal-form-rol',
    components: {
      'modal': Modal,
      'v-input': Input,
      'v-select': Select,
      'v-checkbox-p': Checkbox
    },
    props: ['formData'],
    data () {
      return {
        entries: {
          izq: [
          {label: 'Nombre', id: 'name', icon: 'glyphicon glyphicon-compressed'},
          {label: 'Alias', id: 'slug', icon: 'edit'},
          {label: 'Descripción', id: 'description', icon: 'edit'},
          ],
          der: [
          {label: 'Hora a comenzar la actividad', id: 'from_at', icon: 'edit'},
          {label: 'Hora a finalizar la actividad', id: 'to_at', icon: 'edit'},
          ],
        },
        msg: {
          name: 'Nombre del rol.',
          slug: 'Alias del rol.',
          description: 'Descripción del rol.',
          from_at: 'Hora de actividad inicial.',
          to_at: 'Hora de actividad final.',
          special: 'Acceso privilegiado.',
          permission: 'Permisos del rol'
        }
      };
    },
    methods: {
      close: function () {this.$emit('update'); },
      registrar: function () {
        this.restoreMsg(this.msg);
        if (this.formData.cond === 'create') {
          axios.post(this.formData.url, this.formData.rol)
          .then(response => {
            console.log('Rol Creado Exitosamente');
            $('#ModalRol').modal('toggle');
            this.$emit('update');
          });
        } else {
          axios.put(this.formData.url, this.formData.rol)
          .then(response => {
            console.log('Rol Editado Exitosamente');
            $('#ModalRol').modal('toggle');
            this.$emit('update');
          });
        }
      }
    }
  }
</script>
