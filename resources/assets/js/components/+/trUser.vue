<template>
    <div>
        <tr v-for="user in users">
            <td>{{ user.id }}</td>
            <td>{{ user.name }} {{ user.last_name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.module.module }}</td>
            <td>
                <a href="#"
                class="btn btn-default btn-xs"
                data-toggle="modal"
                data-target="#ModalUser"
                @click.prevent="openform('edit', user)">Editar</a>
            </td>
            <td>
                <a href="$"
                class="btn btn-default btn-xs"
                @click.prevent="destroy(user)">Eliminar</a>
            </td>
        </tr>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
