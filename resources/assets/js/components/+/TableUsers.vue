<template>
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>N°</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Modulo</th>
                <th colspan="2">
                    &nbsp;
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="user in users">
                <td>{{ num + 1 }}</td>
                <td>{{ user.name }} {{ user.last_name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.module.name }}</td>
                <td><a href="#" class="btn btn-default btn-xs">Editar</a></td>
                <td><a href="#" class="btn btn-default btn-xs">eliminar</a></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        users: Array,
    },
    data:() => {
        return {
            num: 0
        }
    }
}
</script>
