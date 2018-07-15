<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card card-default">
                    <button v-on:click = "showForm()" v-show="" type="button" class="btn btn-secondary ">Agregar Tarea</button>
                    <div class="card-header">Nueva Tarea</div>

                    <div class="card-body">

                        <form role="form" >
                            <div class="form-group">
                                <label>Prioridad</label>
                                <select v-model="task.priority" class="form-control">
                                    <option>Urgente</option>
                                    <option>Importante</option>
                                    <option>Normal</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nombre</label>
                                <input v-model="task.id" class="form-control" type="hidden">
                                <input v-on:keyup.enter="setTask()" v-model="task.name" class="form-control" type="text">
                            </div>
                            <div class="row justify-content-center">
                                <div class="btn-group">
                                    <button v-on:click = "setTask()" type="button" class="btn btn-primary ">{{ btnStatus }}</button>
                                    <button v-on:click = "cleanTaskParameters()" type="button" class="btn btn-secundary ">Cancelar</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card card-default">
                    <div class="card-header">Tareas</div>

                    <div class="card-body">
                       <div v-for="(task, index) in tasks.data" :class="getClass(task.priority)" role="alert">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-1">
                                    <form>
                                        <div class="form-group form-check">
                                            <input type="checkbox" v-model="task.state" @click.prevent="updateState(task.id,task.state,index)" class="form-check-input">
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-8">
                                    <p v-if="task.state == 0">{{ task.name }}</p>
                                    <p v-else><del>{{ task.name }}</del></p>
                                </div>
                                <div class = "col-md-3">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-info" @click.prevent="setTaskParameters(index,task.id)">
                                            <input type="radio" name="options" autocomplete="off"> Editar
                                        </label>
                                        <label class="btn btn-warning" @click.prevent="deleteTask(task.id)">
                                            <input type="radio" name="options" autocomplete="off" > Eliminar
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li v-if="pagination.current_page > 1" class="page-item">
                                    <a class="page-link" href="#" @click.prevent = "changePage(pagination.current_page - 1)" >Atras</a>
                                </li>

                                <li v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'active': '']" class="page-item">
                                    <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                                </li>

                                <li v-if="pagination.current_page < pagination.last_page" class="page-item">
                                    <a class="page-link" href="#" @click.prevent = "changePage(pagination.current_page + 1)">Siguiente</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
</template>

<script>

    import axios from 'axios'
    import toastr from 'toastr'
    import swal from 'sweetalert'

    export default {
        data(){
            return{
                task:{
                    id:"",
                    name:"",
                    priority:"",
                },
                task2:{
                    id:"",
                    state:"",
                },
                btnStatus:"Agregar",
                tasks:[],
                pagination:{
                    'total':0,
                    'current_page':0,
                    'per_page':0,
                    'last_page':0,
                    'from':0,
                    'to':0,
                },
                offset: 3,
            }
        },
        created: function() {
            this.getTasks();
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function(){
                if (!this.pagination.to) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset
                if (from < 1) {
                    from = 1;
                }
                var to = from + 2*this.offset;
                if (to > this.pagination.last_page) {
                    to = this.pagination.last_page
                }
                var pagesArray = [];

                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods:{
            getTasks(page){
                var url = 'tasks/get?page=' + page
                axios.get(url).then(response => {
                    this.tasks = response.data.tasks
                    this.pagination = response.data.pagination
                });
            },
            setTaskParameters(index,id){
                this.task.id = id
                this.task.name = this.tasks.data[index].name
                this.task.priority = this.tasks.data[index].priority
                this.btnStatus = "Actualizar"
            },
            setTaskState(index,id){
                this.task2.id = id
                this.task2.state = this.tasks.data[index].state == 0 ? 1 : 0;
            },
            cleanTaskParameters(){
                this.task.id = ""
                this.task.name = ""
                this.task.priority = ""
                this.btnStatus = "Agregar"
            },
            setTask(){
                if (this.task.id == "") {
                    this.createTask();
                }else{
                    this.updateTask();
                }
            },
            createTask(){
                var url = 'tasks/create'

                axios.post(url, this.task ).then( response=> {
                    if (response.data.mensaje == 'Tarea guardada') {
                        this.getTasks();
                        this.cleanTaskParameters();
                        toastr.success(response.data.mensaje);
                    }else{
                        toastr.error("Ha ocurrido un error");
                    }
                    
                });
            },
            updateState(id,state, index){
                var url = 'tasks/updateState'
                this.setTaskState(index,id);
                axios.post(url, this.task2).then( response=> {
                    console.log(response.data.mensaje)
                    if (response.data.mensaje == 'Estado Actualizado') {
                        this.getTasks();
                        toastr.success(response.data.mensaje);
                    }else{
                        toastr.error("Ha ocurrido un error");
                    }
                    
                });
            },
            updateTask(){
                var url = 'tasks/update'

                axios.post(url, this.task ).then( response=> {
                    if (response.data.mensaje == 'Tarea Actualizada') {
                        this.getTasks();
                        this.cleanTaskParameters();
                        toastr.success(response.data.mensaje);
                    }else{
                        toastr.error("Ha ocurrido un error");
                    }
                    
                });
            },
            deleteTask(id){
                var url = 'tasks/delete?id=' + id;
                swal({
                    title: "¿Estás seguro?",
                    text: "Una vez eliminada la tarea no se podrá recuperar la información",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        axios.get(url).then(response => {
                            this.getTasks(this.pagination.current_page); 
                            toastr.success(response.data);
                        }).catch(error =>{
                            toastr.error("Ha ocurrido un error");
                        });
                    } else {
                        swal("La tarea no fue eliminada");
                    }
                });
            },
            getClass:function(priority){
                if (priority == "Urgente") {
                    return "alert alert-danger";
                }else if (priority =="Importante") {
                    return "alert alert-secondary";
                }else if (priority == "Normal"){
                    return "alert alert-primary";
                }
            },
            changePage: function(page){
                this.pagination.current_page = page;
                this.getTasks(page);
            },
        }
    }
</script>
