<template>
    <div>
        <!-- div con el modal -->
        <div class="modal" :class="{shows:modal}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{tituloModal}}</h5>
                        <button type="button" @click="cerrarModal();" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="contenido">Contenido</label>
                            <input v-model="elPosteo.contenido" type="text" class="form-control" name="" id="contenido" placeholder="Contenido">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="cerrarModal();" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button @click="guardar();" type="button" class="btn btn-success">Subir</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- div con el post -->
        <div class="row" style="padding: 10px 0 0 0;">
            <div class="col-2"></div>
                <div class="col-8 border border-5" style="padding:0;border-radius: 5px 5px 5px 5px;">
                    <div class="">
                        <div class="d-flex" style="background: lightgrey;">
                            <div class="col-4"><i class="far fa-user-circle">{{usuario.name}}</i></div>
                            <div class="col-8 d-flex" style="justify-content: flex-end;">
                                <button v-if="usuario.id == logeado.id" @click="abrirModal(elPosteo);" style="float: right; border-radius: 5px 5px 5px 5px;">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button v-if="usuario.id == logeado.id" @click="eliminar(elPosteo);" style="margin:0 0 0 10px; float: right;" class="btn btn-danger border border-dark">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        {{elPosteo.contenido}}
                        <br>
                        <button class="btn" :class="{
                            'btn-secondary' : !meGustasPosteosID.includes(elPosteo.id),
                            'btn-like' : meGustasPosteosID.includes(elPosteo.id)
                            }" @click="darLike(elPosteo)">
                            <i class="fa-heart" :class="{
                            'far': !meGustasPosteosID.includes(elPosteo.id),
                            'fas': meGustasPosteosID.includes(elPosteo.id)
                            }"> {{elPosteo.like}}</i>
                        </button>
                        <br>
                    </div>
                <div class="col-2"></div>
            </div>
        </div>
        <!-- div con los comentarios -->
        <div class="row" style="padding: 10px 0 0 0;" v-for="comentario in comentarios" :key="comentario.id">
            <div class="col-3"></div>
                <div class="col-7 border border-5" style="padding:0;border-radius: 5px 5px 5px 5px;">
                    <div class="" >
                        <div v-for="usuarioComent in usuarios" :key="usuarioComent.id" class="d-flex" style="background: lightgrey;">
                            <div class="col-4"><i class="far fa-user-circle" v-if="usuarioComent.id == comentario.userID" >{{usuarioComent.name}}</i></div>
                            <div class="col-8 d-flex" style="justify-content: flex-end;">
                                <button v-if="usuarioComent.id == logeado.id" @click="eliminarComentario(comentario.id)" style="margin:0 0 0 10px; float: right;" class="btn btn-danger border border-dark">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        {{comentario.contenido}}
                    </div>
                <div class="col-2"></div>
            </div>
        </div>
        <!-- agregar nuevo comentario -->
        <div class="row" style="padding: 10px 0 0 0;">
            <div class="col-3"></div>
                <div class="col-7 d-flex" style="justify-content: flex-end;">
                    <input type="text" name="coment" id="coment" placeholder="Escriba un comentario" value="">
                    <input type="submit" value="Subir" @click="subirComentario();">
                </div>
            <div class="col-2"></div>
        </div>
    </div>
</template>

<script>
export default{
    data() {
        return{
            modal: 0,
            elPosteo:[],
            usuarioID: {},
            usuario: [],
            usuarios: [],
            logeado: [],
            tituloModal: 'Editar posteo',
            id: 0,
            meGustasPosteosID:[],
            meGustas: [],
            meGusta:{
                    userID: 0,
                    posteoID: 0,
                    liked: false
            },
            comentarios: [],
            comentario: {
                userID: 0,
                posteoID: 0,
                contenido: '',
            },
            todosLosLikes:[],
        }
    },
    methods: {
        async listarPosteo(){
            const valor = window.location.search;
            const idPosteo = valor.slice(1);
            const res = await axios.get('/posteos/'+idPosteo);
            this.elPosteo = res.data;
            this.usuarioID = res.data.userID;
        },

        async listarUsuario(){
                const res = await axios.get('/users/');
                this.usuarios = res.data;
                this.usuario = this.usuarios.find(u => u.id == this.usuarioID);                
        },

        async usuarioLogeado(){
                const res = await axios.get('/user/');
                this.logeado = res.data;
        },

        async eliminar(data){
            if(confirm('Estas seguro de eliminar este posteo')){
                this.comentarios.map(comentario => axios.delete('/comentarios/' +comentario.id));
                let eliminar = this.meGustas.filter(like => like.posteoID == data.id);
                eliminar.map(lik => axios.delete('/meGustas/' + lik.id));
                    const res = await axios.delete('/posteos/' + data.id);
                    this.listarPosteo();
                    window.location.href = '/home';
            }else{
                return;
            }
        },

        async abrirModal(data={}){
            this.modal = 1;
            this.id = data.id;
            this.elPosteo.contenido = data.contenido;
        },

        async cerrarModal(){
                this.modal = 0;
        },

        async guardar(){
            const res = await axios.put('/posteos/'+ this.id, this.elPosteo);
            window.alert('Se edito con exito el posteo!')
            this.cerrarModal();
            this.listarPosteo();
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
            this.elPosteo.userID = data.userID;
            this.elPosteo.like = data.like;
            this.elPosteo.contenido = data.contenido;
            const res = await axios.put('/posteos/'+ this.id, this.elPosteo);
        },

        async cargarLike(){
            const res = await axios.get('/meGustas');
            this.todosLosLikes = res.data;
            this.meGustas = this.todosLosLikes.filter(like => like.userID == this.logeado.id);
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

        async listarComentarios(){
            const res = await axios.get('/comentarios');
            const todosLosComentarios = res.data;
            this.comentarios = todosLosComentarios.filter(comentario => comentario.posteoID == this.elPosteo.id);                
        },

        async eliminarComentario(id){
                const res = await axios.delete('/comentarios/' +id);
                this.listarComentarios();
        },

        async subirComentario(){
            let theText = coment.value;
            if (theText === "") {
                return
            }else{
                this.comentario.userID = this.logeado.id;
                this.comentario.posteoID = this.elPosteo.id;
                this.comentario.contenido = theText;
                const res = await axios.post('/comentarios', this.comentario);
                console.log(this.comentario);
                this.listarComentarios();
                coment.value = '';
            }
        }

    },
    created(){
        this.cargarLike();
    },
    mounted(){
        this.cargarLike();
        this.listarPosteo();
        this.usuarioLogeado();
        this.listarUsuario();
        this.listarComentarios();
        this.usuarioLogeado();
        this.cargarLike();
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
