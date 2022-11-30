<script lang="ts" setup>
export type gridHeader = {
  name: string, align: "start" | "center" | "mid", size: `${number}%` | `${number}px` | `${number}rem`
};

const prop = withDefaults(defineProps<{
  headers: gridHeader[]
  rows?: any[]
  options?: boolean
  OptionsShow?: {
    details: boolean
    edit: boolean
    delete: boolean
  }
  loading: boolean
}>(), {
  rows: () => [],
  options: true,
  OptionsShow: () => ({
    details: true,
    edit: true,
    delete: true
  }),
  loading: false
})

const emit = defineEmits<{
  (e: 'deleteItem', id: number): void
  (e: 'updateItem', id: number): void
  (e: 'detailsItem', id: number): void
}
>()

</script> 
<template>
  <table>
    <thead>
      <th v-for="head in headers" :width="head.size">{{ head.name }}</th>
    </thead>
    <tbody>
      <td v-if="loading">LOADING ...</td>
      <tr v-for="row in rows">
        <td v-for="(item, i) in row" :class="headers[i]?.align ?? 'center'">{{ item }}</td>
        <td v-if="options" class="center">
          <span v-if="OptionsShow.details" @click="$emit('detailsItem', row.id)" class="i-opt"></span>
          <span v-if="OptionsShow.delete" @click="$emit('deleteItem', row.id)" class="i-delete"></span>
          <span v-if="OptionsShow.edit" @click="$emit('updateItem', row.id)" class="i-edit"></span>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style lang="scss" scoped>
table {

  .i-delete,
  .i-edit,
  .i-opt {
    box-sizing: border-box;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin: 0px 3px;
    width: 30px;
    height: 30px;
    transition: all .2s;
    text-align: center;
    cursor: pointer;
    padding-top: 5px;

    &:hover {
      background-color: $DetailPrimary;
      border: 1px solid $DetailSecondary;
      color: #fff;
      transform: scale(1.2);
    }

  }

  font-family: 'Raleway',
  sans-serif;
  border: none;
  border-collapse: separate;
  border-spacing: 0;
  table-layout: fixed;

  th {
    background: $DetailPrimary;
    border: 1px solid $DetailSecondary;
    color: #fff;

    &:first-child {
      border-radius: 10px 0 0 0;
    }

    &:last-child {
      border-radius: 0 10px 0 0;
    }
  }

  td {

    &.start {
      text-align: start;
    }

    &.center {
      text-align: center;
    }

    &.end {
      text-align: end;
    }
  }

  th,
  td {
    box-sizing: border-box;
    padding: 5px;
    border: 1px solid #ccc;
  }
}
</style>