<template>
  <table class="table">
    <thead>
      <slot name="columns">
        <th v-for="column in columns">{{ column.as }}</th>
      </slot>
    </thead>
    <tbody>
      <tr v-for="item in data">
        <slot :row="item">
          <td v-for="column in columns">{{ itemValue(item, column.column) }}</td>
        </slot>
      </tr>
    </tbody>
  </table>
</template>
<script>
  export default {
    name: 'Table',
    props: {
      columns: {},
      data: Array,
    },
    data: () => {
      return {
        num: 1,
      };
    },
    methods: {
      hasValue (item, column) {
        return item[column.toLowerCase()] !== 'undefined'
      },
      itemValue (item, column) {
        return item[column.toLowerCase()];
      }
    }
  }
</script>
