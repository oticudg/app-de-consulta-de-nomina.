<template>
    <form method="POST" action="login" @submit.prevent="register">

        <div class="form-group row">
            <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail:</label>
            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="" required autofocus>
                <span class="invalid-feedback">
                    <strong>saddsadasdasdasdasd</strong>
                </span>
            </div>
        </div>

        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">Contraseña:</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" required>
                <span class="invalid-feedback">
                    <strong></strong>
                </span>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6 offset-md-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    Login
                </button>
            </div>
        </div>
    </form>
</template>

<script>
    import Input from './../partials/input.vue';
    export default {
        name: 'login-form',
        components: {
            'inputC': Input
        },
        data() {
            return {
            };
        },
        methods: {
            register: function () {
                console.log('registrar')
            }
        }
    }
</script>