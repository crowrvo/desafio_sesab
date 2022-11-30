<script lang="ts" setup>
import axios from "axios";
import { gridHeader } from "src/components/DataGrid.vue";

const route = useRouter();


const { execute: execUsers, data: dataUsers, abort: abortUsers, error: errorUsers, isFetching: isFetchingUsers } = useFetch("http://127.0.0.1:8000/api/users").json()
const { execute: execRoles, data: dataRoles, abort: abortRoles, error: errorRoles, isFetching: isFetchingRoles } = useFetch("http://127.0.0.1:8000/api/users").json()
const rows = ref()
onMounted(() => {
  loadData()
})

const loadData = async () => {
  await execUsers()
  rows.value = toRaw(dataUsers.value).map((row: any) => {
    return {
      id: row.id,
      name: row.name,
      cpf: row.cpf,
      email: row.email,
      perfil: row.roles.name,
      dataCadastro: new Date(row.created_at).toLocaleDateString('pt-BR')
    }
  })
}

const filter = async (e: HTMLFormElement) => {
  let params = Object.fromEntries(new FormData(e));
  await loadData()
  let tempFilter = toRaw(rows.value);
  for (let i = 0; i < Object.keys(params).length; i++) {
    const key = Object.keys(params)[i];
    tempFilter = tempFilter.filter((f: any) => {
      if (!params[key]) return true
      if (key == 'data_inicial') return new Date(f['dataCadastro'].split('/').reverse().join('-')) >= new Date((params[key] as string).split('/').reverse().join('-'))
      if (key == 'data_final') return new Date(f['dataCadastro'].split('/').reverse().join('-')) <= new Date((params[key] as string).split('/').reverse().join('-'))
      if (key == 'name') return (f['name'] as string).indexOf(params[key] as string) != -1;
      if (key == 'cpf') return (f['cpf'] as string).indexOf(params[key] as string) != -1;
      return true
    })
  }
  rows.value = tempFilter
  console.log(rows.value);
}


const tableHeaders: gridHeader[] = [
  { name: "id", align: "center", size: "5%" },
  { name: "Nome Completo", align: "start", size: "15%" },
  { name: "CPF", align: "center", size: "5%" },
  { name: "E-mail", align: "start", size: "5%" },
  { name: "perfil", align: "start", size: "5%" },
  { name: "Data de Cadastro", align: "center", size: "5%" },
  { name: "Opções", align: "center", size: "10%" }
]

const deleteItem = (id: number) => {
  axios.delete(`http://127.0.0.1:8000/api/user/${id}`,
    { method: "DELETE", headers: { "content-type": "application/json" } }).then(x => {
      alert(x.data);
      loadData();
    })
}

const showDetails = (id: number) => {
  route.push(`/user/${id}/details`)
}

const editItem = (id: number) => {
  route.push(`/user/${id}/edit`)
}
</script>
<template>
  <main>
    <span class="title">Pesquisa</span>
    <form @submit.prevent="filter($event.target as HTMLFormElement)">
      <div class="section1">
        <FormInput class="input1" placeholder="Nome completo" name="name" />
        <FormInput class="input1" mask="###.###.###-##" placeholder="CPF" name="cpf" />
      </div>
      <div class="section2">
        <h2>Período de cadastro:</h2>
        <div class="section-form">
          <FormDate class="input2" placeholder="Início" name="data_inicial" />
          <FormDate class="input2" placeholder="Fim" name="data_final" />
          <div class="buttons">
            <div class="icon-btn">
              <input type="submit" class="btn filter" value="filtrar" />
              <i class="i-filter"></i>
            </div>
            <RouterLink to="/user/create">
              <input type="button" class="btn submit" value="Novo Cadastro">
            </RouterLink>
          </div>
        </div>
      </div>
    </form>
    <DataGrid :headers="tableHeaders" :rows="rows" :loading="isFetchingUsers" @details-item="showDetails"
      @delete-item="deleteItem" @update-item="editItem" />
  </main>
</template>

<style lang="scss" scoped>
main {
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
    height: 33%;
    display: flex;
    flex-direction: row;

    .input1 {
      margin: 0 10px;

      &:nth-child(1),
      &:nth-child(2) {
        width: 450px;
      }

      &:nth-child(3) {
        width: 150px
      }
    }
  }

  .section2 {

    h2 {
      margin-left: 10px;
    }

    font-family: 'Raleway',
    sans-serif;
    width: 100%;
    height: 33%;
    display: flex;
    flex-direction: column;


    .section-form {
      width: 1100px;
      font-family: 'Raleway', sans-serif;
      display: flex;
      flex-direction: row;
      position: relative;

      .buttons {
        display: flex;
        flex-direction: row;
        margin-left: auto;
        align-self: center;

        .icon-btn {
          position: relative;

          i {
            position: absolute;
            top: 35%;
            left: 75%;
            color: #fff;
            cursor: pointer;
            pointer-events: none;
          }

        }

      }

      .btn {
        font-family: 'Raleway', sans-serif;
        font-size: 20px;
        float: right;
        font-weight: 600;
        color: #fff;
        border-radius: 5px;
        width: 200px;
        height: 40px;
        cursor: pointer;
        margin: 10px;
        border: 1px solid $ActionSecondary;
        background-color: $ActionPrimary;
        transition: all 0.1s;

        &.filter {
          background-color: $ActionPrimary;
          border: 1px solid $ActionSecondary;

          &:hover {
            border: 2px solid #7553da;
            font-size: 18px;
          }
        }

        &.submit {
          border: 1px solid $SubmitPrimary;
          background-color: $SubmitPrimary;

          &:hover {
            border: 2px solid #3a7ed6;
            font-size: 18px;
          }

        }
      }
    }
  }

  .section3 {
    font-family: 'Raleway', sans-serif;
    width: 100%;
    height: 33%;
    display: flex;
    flex-direction: row;

    div:nth-child(1) {
      margin-top: 30px;
      font-weight: 700;
      font-family: 'Raleway', sans-serif;
      font-size: 16px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #fff;
      height: 30px;
      width: 80px;
      background: $DetailPrimary;
      border: 1px solid $DetailSecondary;
      border-radius: 10px 0 0 0;
    }

    div:nth-child(2) {
      margin-top: 30px;
      font-weight: 700;
      font-family: 'Raleway', sans-serif;
      font-size: 16px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #fff;
      height: 30px;
      width: 280px;
      background: $DetailPrimary;
      border: 1px solid $DetailSecondary;
    }

    div:nth-child(3) {
      margin-top: 30px;
      font-weight: 700;
      font-family: 'Raleway', sans-serif;
      font-size: 16px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #fff;
      height: 30px;
      width: 250px;
      background: $DetailPrimary;
      border: 1px solid $DetailSecondary;
    }

    div:nth-child(4) {
      margin-top: 30px;
      font-weight: 700;
      font-family: 'Raleway', sans-serif;
      font-size: 16px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #fff;
      height: 30px;
      width: 310px;
      background: $DetailPrimary;
      border: 1px solid $DetailSecondary;
    }

    div:nth-child(5) {
      margin-top: 30px;
      font-weight: 700;
      font-family: 'Raleway', sans-serif;
      font-size: 16px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #fff;
      height: 30px;
      width: 170px;
      background: $DetailPrimary;
      border: 1px solid $DetailSecondary;
    }

    div:nth-child(6) {
      margin-top: 30px;
      font-weight: 700;
      font-family: 'Raleway', sans-serif;
      font-size: 16px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #fff;
      height: 30px;
      width: 300px;
      background: $DetailPrimary;
      border: 1px solid $DetailSecondary;
      border-radius: 0 10px 0 0;
    }
  }
}

.section4 {
  font-family: 'Raleway', sans-serif;
  width: 100%;
  height: 33%;
  display: flex;
  flex-direction: row;
}
</style>

<style lang="scss">
.input2 {
  margin: 0px 10px;
}
</style>
