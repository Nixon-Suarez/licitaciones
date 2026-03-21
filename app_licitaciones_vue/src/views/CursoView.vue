<template>
    <v-container fluid>
        <h1>Cursos</h1>
        <v-row>
            <v-col xs="12" sm="6" md="4" lg="3" xxl="3">
                <v-text-field v-model="curso.name" label="Nombre Curso" maxlength="50" color="indigo" clearable placeholder="Nombre Curso" counter variant="outlined"></v-text-field>
                <v-textarea v-model="curso.descripcion" label="Descripcion" clearable counter maxlength="100" color="indigo" placeholder="Descripcion" variant="outlined"></v-textarea>
                <v-btn @click="registrarCurso" prepend-icon="mdi-check" color="indigo" block="">
                    Agregar
                </v-btn>
            </v-col>
            <v-col cols="9" xs="12" sm="9" md="9" xl="9" xxl="9">
                <v-card-text>
                    <!-- tabla de cursos -->
                    <v-data-table :headers="headers" :items="cursos" class="elevation-1" :items-per-page="10" :search="search"
                        >
                        <template v-slot:top> <!-- cabecara de la tabla-->
                            <v-toolbar flat>
                                <v-toolbar-title>Lista de Cursos</v-toolbar-title>
                                <v-divider class="mx-4" inset vertical></v-divider>
                                <v-text-field color="indigo" label="Buscar" clearable v-model="search" hide-details></v-text-field>
                            </v-toolbar>
                        </template> <!-- acciones de cada fila -->
                        <template  v-slot:[`item.actions`] = "{item}"> <!-- botones de la tabla -->
                            <div class="text-center">
                                <v-btn-group>
                                    <v-btn @click="obtenerCurso(item.id, 1)" prepend-icon="mdi-eye" color="indigo"></v-btn>
                                    <v-btn @click="obtenerCurso(item.id, 2)" prepend-icon="mdi-pencil" color="green"></v-btn>
                                    <v-btn @click="eliminarCurso(item.id)" prepend-icon="mdi-delete" color="red"></v-btn>
                                </v-btn-group>
                            </div>
                        </template>
                    </v-data-table>
                </v-card-text>
            </v-col>
        </v-row>
    </v-container>
    <!-- modal para ver el registro -->
    <v-dialog v-model="modal1" transition="dialog-top-transition" max-width="500px">
        <v-card title="Datos Curso">
            <v-list>
                <v-list-item title="Codigo" :subtitle="datos.id"></v-list-item>
                <v-list-item title="Nombre" :subtitle="datos.name"></v-list-item>
                <v-list-item title="Descripcion" :subtitle="datos.descripcion"></v-list-item>
            </v-list>
        </v-card>
    </v-dialog>
    <!-- Modal actualizar registro -->
    <v-dialog v-model="modal2" transition="dialog-top-transition" max-width="500px">
        <v-card title="Datos Curso">
            <!-- formulario para editar el registro -->
            <v-card-text>
                <v-text-field v-model="datos.name" label="Nombre Curso" maxlength="50" color="indigo" clearable placeholder="Nombre Curso" counter variant="outlined"></v-text-field>
                <v-textarea v-model="datos.descripcion" label="Descripcion" clearable counter maxlength="100" color="indigo" placeholder="Descripcion" variant="outlined"></v-textarea>
                <v-btn @click="actualizarCurso(datos.id)" prepend-icon="mdi-check" color="indigo" block="">
                    Actualizar
                </v-btn>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref } from 'vue';
import { useAuthStore } from '@/stores/authStore'

const authStore = useAuthStore()
const API_URL = authStore.baseUrl
export default {
  name: "CursoView",
  data(){
    return {
        curso: {},
        cursos: ref([]),
        search: '',
        headers: [
            { title: 'Codigo', value: 'id' },
            { title: 'Nombre', value: 'name' },
            { title: 'Acciones', value: 'actions', sortable: false, align: 'center' }
        ],
        modal1: false,
        modal2: false,
        datos: ref({}),
        // configuracion de la cabecera de la solicitud con el token de autenticacion
        config: {
            headers: {
                'Authorization': 'Bearer ' + authStore.token
            }
        }
    }
  },
  methods: {
    getAlert(texto){
        Swal.fire({
            title: 'Exito',
            text: texto,
            icon: 'success',
            showCancelButton: false,
            showConfirmButton: false,
            timer: 1500
          })
    },
    registrarCurso(){
        axios.post(API_URL + 'curso/insert', this.curso, this.config)
        .then(response => {
            if(response.data.code == 201){
                this.getAlert(response.data.data);
                this.curso = {};
                this.obtenerCursos();
            }
        })
        .catch(function(error){
            console.error("Error en la solicitud:", error);
            Swal.fire(
                "Error",
                "Ocurrió un error en el servidor",
                "error"
            );
        })
    },
    obtenerCursos(){
        this.cursos = [];
        axios.get(API_URL + 'curso/select', this.config)
        .then(response => {
            let res = response.data;
            if(res.code == 200){
                this.cursos = res.data;
                console.log(this.cursos);
            }else{
                Swal.fire(
                    "Error",
                    res.data,
                    "error"
                );
            }
        })
        .catch(function(error){
            console.error("Error en la solicitud:", error);
            Swal.fire(
                "Error",
                "Ocurrió un error en el servidor",
                "error"
            );
        })
    },
    obtenerCurso(id, accion){
        if(accion == 1){
            this.modal1 = true;
        }else {
            this.modal2 = true;
        }
        axios.get(API_URL + 'curso/find/' + id, this.config)
        .then(response => {
            let res = response.data;
            if(res.code == 200){
                this.datos = res.data;
                console.log(this.datos);
            }else{
                Swal.fire(
                    "Error",
                    res.data,
                    "error"
                );
            }
        }).catch(function(error){
            console.error("Error en la solicitud:", error);
            Swal.fire(
                "Error",
                "Ocurrió un error en el servidor",
                "error"
            );
        })
    },
    eliminarCurso(id){
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo!'
          }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(API_URL + 'curso/delete/' + id, this.config)
                .then(response => {
                    let res = response.data;
                    if(res.code == 200){
                        this.getAlert(res.data);
                        this.obtenerCursos();
                    }else{
                        Swal.fire(
                            "Error",
                            res.data,
                            "error"
                        );
                    }
                })
                .catch(function(error){
                    console.error("Error en la solicitud:", error);
                    Swal.fire(
                        "Error",
                        "Ocurrió un error en el servidor",
                        "error"
                    );
                })
            }
          })
    },
    actualizarCurso(id){
        console.log(id);
        axios.put(API_URL + 'curso/update/' + id, this.datos, this.config)
        .then(response => {
            let res = response.data;
            this.modal2 = false;
            if(res.code == 200){
                this.getAlert(res.data);
                this.datos = {};
                this.obtenerCursos();
                this.modal2 = false;
            }else{
                Swal.fire(
                    "Error",
                    res.data,
                    "error"
                );
            }
        }).catch(function(error){
            console.error("Error en la solicitud:", error);
            Swal.fire(
                "Error",
                "Ocurrió un error en el servidor",
                "error"
            );
        })
    }
  },
  created(){
    this.obtenerCursos();
  }
};

</script>

<style scoped>
</style>
