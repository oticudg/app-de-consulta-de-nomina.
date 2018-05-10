<template>
  <modal id="ModalUser" w="lg">

    <template slot="modal-title">
      <span :class="'glyphicon glyphicon-'+formData.ico"></span>
      {{ formData.title }}
    </template>

    <template slot="modal-body">
      <div class="row">
        <form id="UserForm" class="form-horizontal" @keyup.enter="registrar()">
          <div class="col-sm-10 col-sm-offset-1">

            <spinner v-if="!formData.ready"></spinner>
            <div v-else>
              <div class="col-sm-5">
                <template v-for="input in entries.izq">
                  <v-input :name="input" required="true"
                          v-model="formData.user[input.id]"
                          :msg="msg[input.id]"
                          @input="formData.user[input.id] = arguments[0]">
                  </v-input>
                </template>
              </div>

              <div class="col-sm-5 col-sm-offset-1">
                <template v-for="input in entries.der">
                  <v-input :name="input" required="true"
                          type="password"
                          v-model="formData.user[input.id]"
                          :msg="msg[input.id]"
                          @input="formData.user[input.id] = arguments[0]">
                  </v-input>
                </template>

                <div class="form-group">
                  <label for="roles" class="control-label">
                    <span class="glyphicon glyphicon-inbox"></span> Módulo:
                  </label>
                  <select name="module_id"
                          id="module_id"
                          required="true"
                          class="form-control"
                          v-model="formData.user.module_id">
                    <option value="">Seleccione el Módulo</option>
                    <option v-for="(module, key, index) in modules" :value="key">{{ module }}</option>
                  </select>
                  <small id="module_idHelp" class="form-text text-muted">
                    <span v-text="msg.module_id"></span>
                  </small>
                </div>

                <!-- <div class="form-group">
                  <label for="roles" class="control-label">
                    <span class="glyphicon glyphicon-compressed"></span> Roles:
                  </label>
                  <v-select multiple v-model="formData.user.roles" :options="roles"></v-select>
                  <small id="rolesHelp" class="form-text text-muted">
                    <span v-text="msg.roles"></span>
                  </small>
                </div> -->

                <!-- 
<select class="js-example-basic-multiple" name="states[]" multiple="multiple">
  <option value="AL">Alabama</option>
    ...
  <option value="WY">Wyoming</option>
</select>
                 -->
              </div>

            </div>

          </div>
        </form>
      </div>
    </template>

    <template slot="modal-btn">
      <button type="button" class="btn btn-default" data-dismiss="modal" @click="close"><span class="fa fa-close"></span> Cerrar</button>
      <button type="button" class="btn btn-primary" @click="registrar()"><span class="glyphicon glyphicon-saved"></span> Guardar</button>
    </template>

  </modal>
</template>

<script>
import Modal from './../partials/modal.vue';
import Input from './../partials/input.vue';
import Select from './../partials/select.vue';
// import VSelect from 'vue-select';

export default {
  name: 'modal-form-user',
  components: {
    'modal': Modal,
    'v-input': Input,
    'v-selectR': Select,
    // 'v-select': VSelect,
  },
  props: ['formData'],
  data () {
    return {
      entries: {
        izq: [
        {label: 'Usuarios', id: 'user', icon: 'fa fa-id-card-o'},
        {label: 'Nombre', id: 'name', icon: 'fa fa-user'},
        {label: 'Apellido', id: 'last_name', icon: 'fa fa-user-o'},
        {label: 'Cédula', id: 'num_id', icon: 'fa fa-id-card-o'},
        {label: 'E-Mail', id: 'email', icon: 'fa fa-envelope'},
        ],
        der: [
        {label: 'Contraseña', id: 'password', icon: 'fa fa-unlock-alt'},
        {label: 'Confirmatión de Contraseña', id: 'password_confirmation', icon: 'fa fa-unlock-alt'},
        ],
      },
      modules: [],
      roles: [],
      msg: {
        user: 'Usuario unico.',
        name: 'Nombre del usuario.',
        last_name: 'Apellido del usuario.',
        num_id: 'Cedula de identidad.',
        email: 'Correo electronico.',
        password: 'Contraseña.',
        password_confirmation: 'Confirmacion de Contraseña.',
        roles: 'Rol a desempeñar.',
        module_id: 'Modulo a desempeñar.'
      }
    };
  },
  mounted: function () {
    axios.post('/admin/get-data-users')
    .then(response => {
      this.modules = response.data.modules;
      let roles = response.data.roles;
      let options = [];
      for (let i in roles) {
        options.push({label:roles[i], values:i});
      }
      this.roles = options;
    });
  },
  methods: {
    close: function () {this.$emit('update'); },
    registrar: function () {
      this.restoreMsg(this.msg);
      if (this.formData.cond === 'create') {
        axios.post(this.formData.url, this.formData.user)
        .then(response => {
          toastr.success('Usuario Creado Exitosamente');
          $('#ModalUser').modal('toggle');
          this.$emit('update');
        });
      } else {
        axios.put(this.formData.url, this.formData.user)
        .then(response => {
          toastr.success('Usuario Editado Exitosamente');
          $('#ModalUser').modal('toggle');
          this.$emit('update');
        });
      }
    }
  }
}
</script>
