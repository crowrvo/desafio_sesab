<script lang="ts" setup>
import { months, prepareCalendar, years } from '../../utils/calendar';

const prop = defineProps<{ modelValue?: Date }>();
const selected = ref<Date>(prop.modelValue ?? new Date);
const dates = ref(prepareCalendar(selected.value.getMonth(), selected.value.getFullYear()));
const currentMount = ref(selected.value.getMonth());
const currentYear = ref(selected.value.getFullYear());

const optYears = years().map((x) => ({ display: x, value: x }))
const optMonths = months.map((x, i) => ({ display: x, value: i }))

const emit = defineEmits<{
  (e: "update:modelValue", value: Date): void
}>()

const updateValue = (dayCell: {
  value: number | string;
  isToday: boolean;
  isSelected: boolean;
}) => {
  if (dayCell.value == "") return
  dates.value.forEach(date => date.forEach(x => x.isSelected = false));
  dayCell.isSelected = true;
  selected.value = new Date(currentYear.value, currentMount.value, dayCell.value as number)
  emit('update:modelValue', selected.value);
}

const update = () => {
  let newDates = prepareCalendar(currentMount.value, currentYear.value);
  newDates.forEach(date => date.forEach(x => {
    if (x.value == selected.value.getDate() &&
      currentMount.value == selected.value.getMonth() &&
      currentYear.value == selected.value.getFullYear())
      x.isSelected = true
  }));
  dates.value = newDates
}

const updateMonth = (value: any): void => {
  currentMount.value = parseInt(value);
  update()
}
const updateYear = (value: any): void => {
  currentYear.value = parseInt(value);
  update()
}

const nextMonth = () => {
  if (currentMount.value == 11) {
    currentMount.value = 0
    currentYear.value++
  } else currentMount.value++
  update()
}
const prevMonth = () => {
  if (currentMount.value == 0) {
    currentMount.value = 11
    currentYear.value--
  } else currentMount.value--
  update()
}

</script>

<template>
  <div class="modal">
    <table>
      <col />
      <colgroup></colgroup>
      <thead>
        <tr>
          <td scope="colgroup" colspan="1">
            <span class="i-arrow-left" @click="prevMonth"></span>
          </td>
          <td scope="colgroup" colspan="5">
            <div class="formDate">
              <FormSelect class="formDateSelect" :options="optMonths" v-model="currentMount"
                @update:model-value="updateMonth" style="width: 50%;" />
              <FormSelect class="formDateSelect" :options="optYears" v-model="currentYear"
                @update:model-value="updateYear" style="width: 50%;" />
            </div>
          </td>
          <td scope="colgroup" colspan="1">
            <span class="i-arrow-right" @click="nextMonth"></span>
          </td>
        </tr>
        <tr>
          <th scope="col">Dom</th>
          <th scope="col">Seg</th>
          <th scope="col">Ter</th>
          <th scope="col">Qua</th>
          <th scope="col">Qui</th>
          <th scope="col">Sex</th>
          <th scope="col">Sab</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="week in dates">
          <td v-for="day in week" @click="updateValue(day)"
            :class="{ empty: !day.value, today: day.isToday, selected: day.isSelected }">{{
            day.value
            }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style lang="scss" scoped>
.modal {
  z-index: 10;
}

table {

  table-layout: fixed;
  border-spacing: 0;
  font-family: 'Raleway', sans-serif;
  width: 300px;
  height: 300px;
  border: 1px solid #fff;
  padding-bottom: 5px;

  tr {
    text-align: center;

    th {
      width: 14.999999%;
      height: 16.66666%;
    }
  }

  thead {
    tr {
      &:nth-child(1) {
        height: 16%;
        border-radius: 5px;
        background-color: $SubmitPrimary;
      }

      &:nth-child(2) {
        th {
          height: 8%;
          font-size: 12px;
        }
      }
    }
  }

  th {
    border-bottom: 1px solid #fff;
  }

  tbody {
    tr {
      td {
        border-radius: 5px;
        border: 1px solid transparent;

        &:hover:not(.empty) {
          border: 1px solid $SubmitPrimary;
        }

        &.today {
          border: 1px solid $SubmitPrimary;
        }

        &.selected {
          background-color: $SubmitPrimary;
          color: #fff;
        }

        text-align:center;
      }
    }
  }
}

.i-arrow-left,
.i-arrow-right {
  font-size: 0.7rem;
  width: 20px;
  height: 20px;
  box-sizing: border-box;
  padding: 3px;
  cursor: pointer;
  text-align: center;
  border-radius: 4px;
  border: 1px solid #fff;
  background-color: $SubmitPrimary;
  color: #fff;
}

.formDate {
  display: flex;
  flex-direction: row;

  .formDateSelect {
    margin: 0px 5px;
  }

}
</style>