<script lang="ts" setup>
import axios from "axios";
import { gridHeader } from "src/components/DataGrid.vue";

const rows = ref([]);
const router = useRouter();
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

const roles = ref([]);

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
         id: address.id,
         logradouro: address.logradouro,
         cep: address.cep
      }
   })
   rows.value = user.addresses
   user.name = dataUser.value.name
   user.cpf = dataUser.value.cpf
   user.email = dataUser.value.email
   user.role = dataUser.value.role_id

})

const tableHeaders: gridHeader[] = [
   { name: "id/token", align: "center", size: "5%" },
   { name: "Logradouro", align: "start", size: "35%" },
   { name: "CEP", align: "center", size: "13%" },
   { name: "Opções", align: "center", size: "10%" },
];

const addAddress = (form: HTMLFormElement) => {
   const NewAddress = {
      id: + new Date(),
      ...Object.fromEntries(new FormData(form))
   }
   rows.value.push(NewAddress);
};

const deleteItem = (id: number) => {
   axios.delete(`http://127.0.0.1:8000/api/address/${id}`,
      { method: "DELETE", headers: { "content-type": "application/json" } }).then(async x => {
         alert(x.data);
         await executeUser();
         user.addresses = dataUser.value.addresses.map((address: any) => {
            return {
               id: address.id,
               logradouro: address.logradouro,
               cep: address.cep
            }
         })
         rows.value = user.addresses
      }).catch(() => {
         console.log("a");
         rows.value = rows.value.filter(x => x.id != id)
      })
}



const EditUser = (form: HTMLFormElement) => {
   const NewUser = Object.fromEntries(new FormData(form));
   const User = {
      ...NewUser,
      addresses: rows.value
   };

   axios.put(`http://127.0.0.1:8000/api/user/${route.params.id}`, User)
      .then(x => {
         alert("usuário editado com sucesso");
         router.push("/users");
      }).catch(x => {
         alert(x.response.data.message)
      })
};
</script>
<template>
   <main>
      <span class="title">Editar Cadastro</span>
      <div class="section1">
         <form class="formMain" @submit.prevent="EditUser($event.target as HTMLFormElement)">
            <input type="hidden" name="id" :value="route.params.id">
            <div class="section1-1">
               <FormInput v-model="user.name" class="input1" placeholder="Nome completo" name="name" />
               <FormInput v-model="user.email" class="input1" placeholder="Email" name="email" />
               <FormInput v-model="user.cpf" class="input2" mask="###.###.###-##" placeholder="CPF" name="cpf" />
               <FormSelect v-model="user.role" class="input2" name="role_id" :options="roles" placeholder="Perfil" />
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
      <DataGrid :OptionsShow="{ details: false, delete: true, edit: false }" @deleteItem="deleteItem"
         :headers="tableHeaders" :rows="rows" :loading="false" />
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
