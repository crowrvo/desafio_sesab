<script setup lang="ts">
import { promiseTimeout } from "@vueuse/core";
import masker from "../../utils/mask";
const text = ref<Date>();
const calendarContainerOpened = ref(false);
const backdrop = ref(false)
const prop = defineProps<{
  modelValue?: string
  placeholder?: string
  name?: string
}>();


const openContainer = () => {
  calendarContainerOpened.value = true
  backdrop.value = true
}

const closeContainer = async () => {
  calendarContainerOpened.value = false
  backdrop.value = false
}

const emit = defineEmits<{
  (e: "update:modelValue", value: string): void
}>()


</script>

<template>
  <div class="form-date" :class="$attrs.class">
    <div class="container">
      <input :name="name" readonly :value="text?.toLocaleDateString('pt-BR') ?? ''" placeholder="" type="text">
      <span @click="openContainer" class="i-calendar"></span>
    </div>
    <label :class="{ 'filled': text }" for="name">{{ placeholder }}</label>
    <FormCalendar class="calendar" :class="{ show: calendarContainerOpened }" @update:model-value="closeContainer"
      v-model="text" />
  </div>
  <Teleport to="body">
    <div class="backdrop" :class="{ show: backdrop }" @click="closeContainer">
    </div>
  </Teleport>
</template>

<style lang="scss" scoped>
.form-date {
  position: relative;
  height: 50px;
  display: flex;
  flex-direction: column;

  .container {
    display: flex;
    flex-direction: row;

    .i-calendar {
      text-align: center;
      color: #fff;
      background: $SubmitPrimary;
      height: 30px;
      width: 30px;
      margin: 9px 0;
      border: solid 1px $Outline;
      cursor: pointer;
      line-height: 30px;
      // font-size: .9rem;
      border-radius: 0px 10px 10px 0;
    }

  }

  .calendar {
    position: absolute;
    top: -130px;
    left: 60px;
    background: #fcfcfc;
    transform: scale(.05);
    opacity: 0;
    transition: all .3s ease-in-out;
    pointer-events: none;

    &.show {
      pointer-events: all;
      display: block;
      opacity: 1;
      transform: scale(1);
    }
  }


  label {
    pointer-events: none;
    position: absolute;
    color: $FgPrimary;
    top: 30%;
    font-size: 1.2rem;
    transition: all .3s ease-in-out;
    padding-left: .6rem;

    &.filled {
      top: -30%;
      color: $FgSecondary;
    }
  }

  input {
    box-sizing: border-box;
    height: 30px;
    text-align: center;
    border: none;
    padding-left: .6rem;
    color: $Outline;
    border-radius: 5px;
    border: 1px solid $Outline;
    background-color: $BgSecondary;
    margin: 10px 0;
    outline: none;
    font-size: .9rem;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;

    &:focus+label {
      color: $FgSecondary;
      top: -30%;
    }

    &:focus &:hover,
    &:focus {
      border: 1px solid $FgSecondary;
      transition: all .3s ease-in-out;
    }

    &::placeholder {
      opacity: 0;
      font-size: 1rem;
      transition: all .3s ease-in-out;

    }
  }

}

.backdrop {
  top: 0;
  left: 0;
  position: absolute;
  height: 100vh;
  width: 100vw;
  z-index: 2;
  display: none;

  &.show {
    display: block;
  }
}
</style>