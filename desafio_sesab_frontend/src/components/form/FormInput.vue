<script setup lang="ts">
import masker from "../../utils/mask";
const text = ref("");
const prop = defineProps<{
  modelValue?: string
  mask?: string
  placeholder: string
}>();



watch(() => prop.modelValue, (newValue: any) => {
  text.value = newValue
})

const emit = defineEmits<{
  (e: "update:modelValue", value: string): void
}>()

const updateValue = (e: Event) => {
  const { value } = e.target as HTMLInputElement;
  text.value = value;
  if (prop?.mask) text.value = masker(value, prop.mask);
  emit('update:modelValue', value);
}


</script>

<template>
  <div class="form-input">
    <input :maxlength="mask?.length" @input="updateValue" :name="($attrs.name as string ?? '')" :value="text"
      type="text">
    <label :class="{
      'filled': text != ''
    }" for="name">{{ placeholder }}</label>
  </div>
</template>

<style lang="scss" scoped>
.form-input {
  position: relative;
  height: 50px;
  display: flex;
  flex-direction: column;


  label {
    pointer-events: none;
    position: absolute;
    color: $FgPrimary;
    top: 30%;
    font-size: 1.1rem;
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
    border: none;
    padding-left: .6rem;
    color: $Outline;
    border-radius: 5px;
    border: 1px solid $Outline;
    background-color: $BgSecondary;
    margin: 10px 0;
    outline: none;
    transition: all 1s ease-in-out;
    font-size: .9rem;

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
</style>