<script lang="ts" setup>
import axios from "axios";
import { gridHeader } from "src/components/DataGrid.vue";

const router = useRouter();
const rows = ref([]);

const {
   execute: executeRoles,
   data: dataProfiles,
   abort,
   error,
   isFetching,
} = useFetch("http://127.0.0.1:8000/api/profiles").json();
const roles = ref([]);

onMounted(async () => {
   await executeRoles();
   roles.value = dataProfiles.value.map((role: any) => {
      return {
         display: role.name,
         value: role.id,
      };
   });
});

const tableHeaders: gridHeader[] = [
   { name: "id/token", align: "center", size: "5%" },
   { name: "Logradouro", align: "start", size: "35%" },
   { name: "CEP", align: "center", size: "13%" },
   { name: "Opções", align: "center", size: "10%" },
];

const addAddress = (form: HTMLFormElement) => {
   const NewAddress = Object.fromEntries(new FormData(form));
   rows.value.push({ id: + new Date(), ...NewAddress });
};

const deleteItem = (id: number) => {
   rows.value = rows.value.filter(x => x.id != id)
}

const CreateUser = (form: HTMLFormElement) => {
   const NewUser = Object.fromEntries(new FormData(form));
   const User = {
      ...NewUser,
      addresses: rows.value
   };

   axios.post(`http://127.0.0.1:8000/api/user`, User)
      .then(x => {
         alert("usuário criado com sucesso");
         router.push("/users");
      }).catch(x => {
         alert(x.response.data.message)
      })
};
</script>
<template>
   <main>
      <span class="title">Novo Cadastro</span>
      <div class="section1">
         <form class="formMain" @submit.prevent="CreateUser($event.target as HTMLFormElement)">
            <div class="section1-1">
               <FormInput class="input1" placeholder="Nome completo" name="name" />
               <FormInput class="input1" placeholder="Email" name="email" />
               <FormInput class="input2" mask="###.###.###-##" placeholder="CPF" name="cpf" />
               <FormSelect class="input2" name="role_id" :options="roles" placeholder="Perfil" />
            </div>
            <input type="submit" class="btn-save" value="Salvar usuário" />
         </form>
      </div>
      <div class="section2">
         <h2>Endereço</h2>
         <div class="section2-1">
            <form class="form" @submit.prevent="addAddress($event.target as HTMLFormElement)">
               <FormInput class="inputLog" placeholder="Logradouro" name="logradouro" />
               <FormInput class="inputCep" mask="#####-###" placeholder="CEP" name="cep" />
               <input type="submit" class="btn-add" value="Adicionar Endereço" />
            </form>
         </div>
      </div>
      <DataGrid :OptionsShow="{ details: false, delete: true, edit: false }" :headers="tableHeaders"
         @deleteItem="deleteItem" :rows="rows" :loading="false" />
   </main>
</template>

<style lang="scss" scoped>
main {

   .formMain {
      height: 290px;
   }

   .btn-save {
      margin: 10px;
      font-family: "Raleway", sans-serif;
      font-size: 1rem;
      font-weight: 600;
      color: #fff;
      background-color: $SubmitPrimary;
      border-radius: 5px;
      border: 1px solid $SubmitSecondary;
      width: 130px;
      height: 40px;
      cursor: pointer;
   }

   width: 100%;
   height: 100%;
   box-sizing: border-box;
   padding: 0 100px;
   display: flex;
   flex-direction: column;

   .title {
      font-family: "Raleway", sans-serif;
      font-weight: 600;
      font-size: 2rem;
      padding: 2rem;
      padding-left: 0;
   }

   .section1 {
      font-family: "Raleway", sans-serif;
      width: 100%;
      height: 130px;
      display: flex;
      flex-direction: column;

      .section1-1 {
         display: flex;
         flex-direction: row;
      }

      .input1 {
         margin: 0 10px;
         width: 450px;
      }

      .input2 {
         width: 250px;
         margin: 0 10px;
      }

   }

   .section2 {
      font-family: "Raleway", sans-serif;
      width: 100%;
      height: 150px;
      display: flex;
      flex-direction: column;

      h2 {
         font-family: "Raleway", sans-serif;
         margin: 0px 0px 20px 0px;
      }

      .section2-1 form {
         display: flex;
         flex-direction: row;


         .inputLog {
            width: 450px;
            margin: 0 10px;
         }

         .inputCep {
            width: 300px;
            margin: 0 10px;
         }

      }

      .btn-add {
         font-family: "Raleway", sans-serif;
         margin-top: 5px;
         font-size: 1rem;
         font-weight: 600;
         color: #fff;
         background-color: $SubmitPrimary;
         border-radius: 5px;
         border: 1px solid $SubmitSecondary;
         width: 180px;
         height: 40px;
         cursor: pointer;
      }
   }
}
</style>
