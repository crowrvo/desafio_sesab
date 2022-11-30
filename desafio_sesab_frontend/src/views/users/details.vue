<script lang="ts" setup>
import { gridHeader } from 'src/components/DataGrid.vue';
const route = useRoute();

const user = reactive({
   name: '',
   cpf: '',
   email: '',
   role: '',
   addresses: []
})

const { execute: executeRoles, data, abort, error, isFetching } = useFetch("http://127.0.0.1:8000/api/profiles").json()
const { execute: executeUser, data: dataUser } = useFetch(`http://127.0.0.1:8000/api/user/${route.params.id}`).json()
const roles = ref([])

onMounted(async () => {
   await executeRoles();
   await executeUser();
   roles.value = data.value.map((role: any) => {
      return {
         display: role.name,
         value: role.id,
      };
   })
   user.addresses = dataUser.value.addresses.map((address: any) => {
      return {
         logradouro: address.logradouro,
         cep: address.cep
      }
   })
   user.name = dataUser.value.name
   user.cpf = dataUser.value.cpf
   user.email = dataUser.value.email
   user.role = dataUser.value.role_id

})

const tableHeaders: gridHeader[] = [
   { name: "Logradouro", align: "start", size: "35%" },
   { name: "CEP", align: "center", size: "30%" },
]

</script>
<template>
   <main>
      <span class="title">Detalhes</span>
      <form>
         <div class="section1">
            <FormInput v-model="user.name" class="input1" placeholder="Nome completo" name="name" />
            <FormInput v-model="user.email" class="input1" placeholder="Email" name="email" />
         </div>
         <div class="section2">
            <FormInput v-model="user.cpf" class="input2" mask="###.###.###-##" placeholder="CPF" name="role_id" />
            <FormSelect :disabled="true" v-model="user.role" class="input2" name="role_id" :options="roles"
               placeholder="Perfil" />
         </div>
      </form>
      <h2>Endereços</h2>
      <DataGrid :headers="tableHeaders" :rows="user.addresses" :options="false" />
   </main>
</template>

<style lang="scss" scoped>
main {

   h2 {
      font-family: 'Raleway', sans-serif;
   }

   width: 100%;
   height: 100%;
   box-sizing: border-box;
   padding: 0 100px;
   display: flex;
   flex-direction: column;

   .title {
      font-family: 'Raleway', sans-serif;
      font-weight: 600;
      font-size: 2rem;
      padding: 2rem;
      padding-left: 0;
   }

   .section1 {
      font-family: 'Raleway', sans-serif;
      width: 100%;
      height: 20%;
      display: flex;
      flex-direction: row;

      .input1 {
         margin: 0 10px;
         width: 450px;
      }
   }


   .section2 {
      font-family: 'Raleway', sans-serif;
      margin-top: 40px;
      width: 100%;
      height: 20%;
      display: flex;
      justify-content: start;

      .input2 {
         width: 450px;
         margin: 0 10px;
      }

   }

   .section3 {
      font-family: 'Raleway', sans-serif;
      width: 100%;
      height: 20%;
      display: flex;
      flex-direction: row;

      .inputCity,
      .inputNeighborhood,
      .inputCep {
         width: 450px;
         margin: 0 10px;
      }

      .btn-add {
         margin-left: auto;
         font-family: 'Raleway', sans-serif;
         font-size: 20px;
         font-weight: 600;
         color: #fff;
         background-color: $SubmitPrimary;
         border-radius: 5px;
         border: 1px solid $SubmitSecondary;
         width: 130px;
         height: 40px;
         cursor: pointer;
      }
   }

   .inputLog {
      width: 450px;
      margin: 0 10px;
   }
}



// .btn-add {
//   font-family: 'Raleway', sans-serif;
//   font-size: 20px;
//   font-weight: 600;
//   color: #fff;
//   background-color: $SubmitPrimary;
//   border-radius: 5px;
//   border: 1px solid $SubmitSecondary;
//   width: 100px;
//   height: 40px;
//   position: absolute;
//   right: 0;
//   top: 20px;
//   cursor: pointer;
// }
</style>
