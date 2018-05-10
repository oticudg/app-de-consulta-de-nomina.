<template>
    <div class="row">
        <div class="col-sm-1 pull-left">
            <select class="form-control" style="width: 75px;" v-model="num" @change="updateN(num)">
                <option v-for="i in a" :value="i">{{ i }}</option>
            </select>
        </div>
        <div class="col-xs-5 col-sm-4 pull-right">
            <input type="text" class="form-control" v-model="search" placeholder="Busqueda" @keyup="updateS(search)">
        </div>
    </div>
</template>

<script>
    export default {
        name: 'header-table',
        props: ['n', 's', 'a'],
        data () {
            return {
                num: this.n,
                search: this.s,
            };
        },
        methods: {
            updateN: function (value) {
                this.$emit('changeN', value)
            },
            updateS: function (value) {
                this.$emit('changeS', value)
            }
        },
    }
</script>