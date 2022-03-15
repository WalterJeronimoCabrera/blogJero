<template>
    <div>
        <h1 class="text-center">Posteos</h1>
        <!-- Button trigger modal -->
        <button @click="update=false; abrirModal()" class="btn btn-primary">
            Crear Posteo
        </button>

        <!-- Modal -->
        <div class="modal" :class="{shows:modal}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{tituloModal}}</h5>
                        <button @click="cerrarModal()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="contenido">Contenido</label>
                            <input v-model="posteo.contenido" type="text" class="form-control" name="" id="contenido" placeholder="Contenido">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="cerrarModal();" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button @click="guardar();" type="button" class="btn btn-success">Subir</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Div con los posteos-->
        <div class="row" style="padding: 10px 0 0 0;" v-for="posteo in posteos" :key="posteo.id">
            <div class="col-2"></div>
            <div class="col-8 border border-5" style="padding:0;border-radius: 5px 5px 5px 5px;">
                <div class="" v-for="usuario in usuarios" :key="usuario.id">
                    <div v-if="usuario.id == posteo.userID" class="d-flex" style="background: lightgrey;">
                        <div class="col-4"><i class="far fa-user-circle">{{usuario.name}}</i></div>
                        <div class="col-8 d-flex" style="justify-content: flex-end;">
                            <button v-if="usuario.id == logeado.id" @click="update=true;abrirModal(posteo);" style="float: right; border-radius: 5px 5px 5px 5px;">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button v-if="usuario.id == logeado.id" @click="eliminar(posteo.id);" style="margin:0 0 0 10px; float: right;" class="btn btn-danger border border-dark">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div> 
                {{posteo.contenido}}
                <br>
                <a :href="'posteo/?' + posteo.id">
                    <button class="btn btn-secondary">
                    <i class="far fa-comments"></i>
                    </button>
                </a>
                <button class="btn" :class="{
                    'btn-secondary' : !meGustasPosteosID.includes(posteo.id),
                    'btn-like' : meGustasPosteosID.includes(posteo.id)
                    }" @click="darLike(posteo)">
                    <i class="fa-heart" :class="{
                    'far': !meGustasPosteosID.includes(posteo.id),
                    'fas': meGustasPosteosID.includes(posteo.id)
                    }"> {{posteo.like}}</i>
                </button>
                <br>
            </div>
            <div class="col-2"></div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                posteo:{
                    contenido: '',
                    like: 0,
                    userID: 0,
                },
                meGusta:{
                    userID: 0,
                    posteoID: 0,
                    liked: false
                },
                posteos:[],
                usuarios: [],
                meGustas:[],
                meGustasPosteosID:[],
                tituloModal: 'Crear Posteo',
                modal: 0,
                update: true,
                logeado:[],
                id: 0
            }
        },
        methods: {
            async usuarioLogeado(){
                const res = await axios.get('user');
                this.logeado = res.data;
            },
            async listarPosteos(){
                const res = await axios.get('posteos');
                this.posteos = res.data;
                this.posteos.map(post => this.verificarMG(post));
            },

            async listarUsuarios(){
                const res = await axios.get('users');
                this.usuarios = res.data;
                
            },

            async eliminar(id){
                if(confirm('Estas seguro de eliminar este posteo')){
                    const resp = await axios.get('comentarios');
                    const todosLosComentarios = resp.data;
                    let comentarios = todosLosComentarios.filter(comentario => comentario.posteoID == id);

                    comentarios.map(comentario => axios.delete('/comentarios/' +comentario.id));
                    let eliminar = this.meGustas.filter(like => like.posteoID == id);
                    eliminar.map(lik => axios.delete('/meGustas/' + lik.id));
                    const res = await axios.delete('/posteos/' +id);
                    this.listarPosteos();
                }else{
                    return;
                }
            },

            async eliminarComentario(id){
                const res = await axios.delete('/comentarios/' +id);
                this.listarComentarios();
            },

            async abrirModal(data={}){
                this.modal = 1;
                if(this.update){
                    this.id = data.id;
                    this.tituloModal = 'Editar posteo';
                    this.posteo.userID = data.userID;
                    this.posteo.like=data.like;
                    this.posteo.contenido = data.contenido;
                }else{
                    this.id = 0;
                    this.tituloModal = 'Crear posteo';
                    this.posteo.userID = this.logeado.id;
                    this.posteo.like=0;
                    this.posteo.contenido = '';
                }
            },

            async cerrarModal(){
                this.modal = 0;
            },

            async guardar(){
                if(this.update){
                    const res = await axios.put('/posteos/'+ this.id, this.posteo);
                    console.log(this.posteo);
                    window.alert('Se edito con exito el posteo!');

                }else{
                    const res = await axios.post('/posteos', this.posteo);
                    window.alert('Se creo con exito el posteo!');

                }
                this.cerrarModal();
                this.listarPosteos();
            },

            async darLike(data){
                const fi =this.meGustasPosteosID.includes(data.id);
                if (fi) {
                    data.like -= 1;
                    this.eliminarLike(data.id);
                    let index = this.meGustasPosteosID.findIndex(like => like == data.id);
                    this.meGustasPosteosID.splice(index, 1);
                    let index2 = this.meGustas.findIndex(like => like == data.id);
                    this.meGustas.splice(index2, 1);
                }else{
                    data.like += 1;
                    this.guardarLike(data.id);
                }

                this.id = data.id;
                this.posteo.userID = data.userID;
                this.posteo.like = data.like;
                this.posteo.contenido = data.contenido;
                const res = await axios.put('/posteos/'+ this.id, this.posteo);
            },

            async cargarLike(){
                const res = await axios.get('meGustas');
                this.meGustas = res.data.filter(like => like.userID == this.logeado.id);
                this.meGustasPosteosID = this.meGustas.map(like => like.posteoID);
            },

            async guardarLike(id){
                this.meGusta.userID = this.logeado.id;
                this.meGusta.posteoID = id;
                this.meGusta.liked = true;
                const res = await axios.post('/meGustas', this.meGusta);
                this.cargarLike();
            },

            async eliminarLike(id){
                const likeDelete = this.meGustas.find(like => like.posteoID == id);
                const res = await axios.delete('/meGustas/' + likeDelete.id);
                this.cargarLike();
            },

            async verificarMG(data){
                return (this.meGustasPosteosID.includes(data.id));
            },
        },
        created(){
            this.cargarLike();
        },

        mounted(){
            this.cargarLike();
            this.listarPosteos();
            this.listarUsuarios();
            this.usuarioLogeado();
        },
        computed:{
            btnStatus(){
                return 
            },
        }
    }
</script>
<style>
.shows{
        display: list-item;
        opacity: 1;
        background: rgba(44, 38, 75, 0.849);
}
.btn-like{
        border-color:rgb(250, 118, 140);
        color: rgb(250, 118, 140);
    }
</style>