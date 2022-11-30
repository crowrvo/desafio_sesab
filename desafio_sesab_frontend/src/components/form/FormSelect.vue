<script lang="ts" setup>
import { promiseTimeout } from '@vueuse/core';

const selected = ref<any>(undefined);
const optContainerOpened = ref(false);
const prop = withDefaults(defineProps<
  {
    messages?: { "no-options": string }
    disabled?: boolean
    placeholder?: string
    options?: { display: any, value: any }[]
    modelValue?: any
  }>(), {
  messages: () => ({ "no-options": "Sem opções disponível" }),
  disabled: false,
  options: () => [],
});

onMounted(() => {
  if (prop.modelValue && prop.options.length > 0) {
    const item = prop.options.filter(x => x.value == prop.modelValue)
    selected.value = item[0]
  }
})

watch(() => prop.modelValue, (newValue: any) => {
  const item = prop.options.filter(x => x.value == newValue)
  selected.value = item[0]
})

const openContainer = () => {
  if (!prop.disabled && prop.options.length > 0) optContainerOpened.value = true
}

const closeContainer = async () => {
  await promiseTimeout(100);
  optContainerOpened.value = false
}

const emit = defineEmits<{
  (e: "update:modelValue", value: string): void;
}>();

const updateValue = (value: any) => {
  selected.value = value;
  emit("update:modelValue", value.value);
};
</script>

<template>
  <div class="content" :class="{ opened: optContainerOpened }">
    <input type="hidden" :name="($attrs.name as string ?? '')" :value="(selected?.value ?? '')">
    <input type="text" readonly :value="(selected?.display ?? '')" @focusin="openContainer"
      @focusout="closeContainer" />
    <span class="select-icon i-arrow-bottom"></span>
    <div class="opts-container">
      <span v-for="opt in options" @click="opt.value != modelValue ? updateValue(opt) : ''" class="select-opt"
        :class="{ selected: opt.value == modelValue }">{{ opt.display }}</span>
    </div>
    <label :class="{ 'filled': selected != undefined }">{{ options.length > 0 ? placeholder : messages['no-options']
    }}</label>
  </div>
</template>

<style lang="scss" scoped>
.content {
  position: relative;
  display: flex;
  height: 50px;

  &.opened {

    .select-icon {
      transform: rotate(180deg) translateY(50%);
    }

    label {
      color: $FgSecondary;
      top: -30%;
    }

    .opts-container {
      max-height: 200px;
      opacity: 1;
      pointer-events: all;
    }
  }


  .select-icon {
    padding-top: 5px;
    vertical-align: bottom;
    color: $FgSecondary;
    pointer-events: none;
    border: none;
    position: absolute;
    right: 5%;
    top: 50%;
    transition: all .3s ease-in-out;
    transform: translateY(-50%);
  }

  input {
    cursor: pointer;
    box-sizing: border-box;
    height: 30px;
    border: none;
    padding-left: 0.6rem;
    color: $Outline;
    border-radius: 5px;
    border: 1px solid $Outline;
    background-color: $BgSecondary;
    margin: 10px 0;
    font-size: 0.9rem;
    width: 100%;

    &:focus &:hover,
    &:focus {
      outline: none;
    }
  }

  .opts-container {
    background: #fff;
    padding-top: 5px;
    display: flex;
    opacity: 0;
    pointer-events: none;
    flex-direction: column;
    border: 1px solid $Outline;
    border-top: none;
    position: absolute;
    width: calc(100% - 2px);
    max-height: 0;
    top: 39px;
    overflow-y: auto;
    transition: all 0.3s ease-in-out;

    .select-opt {
      cursor: pointer;
      box-sizing: border-box;
      padding: 3px;
      font-family: "Raleway", sans-serif;
      display: block;
      width: 100%;
      border: 1px solid transparent;

      &:hover {
        border-radius: 5px;
        border: 1px solid $Outline;
      }

      &.selected {
        border-radius: 5px;
        background: $SubmitPrimary;
        color: #fff;
      }
    }
  }

  label {
    display: block;
    pointer-events: none;
    position: absolute;
    color: $FgPrimary;
    top: 30%;
    font-size: 1.1rem;
    transition: all 0.3s ease-in-out;
    padding-left: 0.6rem;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    width: 90%;

    &.filled {
      top: -30%;
      color: $FgSecondary;
    }
  }
}
</style>
