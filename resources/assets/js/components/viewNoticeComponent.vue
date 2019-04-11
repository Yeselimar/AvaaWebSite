<template>
    <div class="fondo d-flex" @click.stop="afuera(1)" >
        <div class="d-flex w-100" @click.stop="afuera(2)" >
            
            <div class="w-100 bg-light rounded position-relative">
                 <h1 v-if="title" class="text-center absolute-title">{{title}}</h1>
                 <div class="cerrar p-2 bg-light rounded cursor" @click="$emit('close2')" >
                    <i class="fa fa-close f-24"> </i>
                </div>
               
                <div class="container d-flex justify-content-start w-100 pab" >
                    <div v-html="content2"></div>
                </div>            
            </div>

        </div>
    </div>
</template>

<script>

export default {
    name:'view-notice',
    props:{
        content2:{
            type: String,
            require:true
        },
        title: {
            type: String,
            require:false
        }
    },
    data(){
        return{
        }
    },

    
    methods:{
        afuera(val){
            console.log(val)
            if (val===1) {
                this.$emit('close2')
            }

        }
        
    },
    mounted(){
         for (let i = 0; i < document.getElementsByTagName("img").length; i++) {
            document.getElementsByTagName("img")[i].classList.add('img-fluid')
        }
        window.addEventListener("keyup",(e) => {
            if(e.keyCode==27) {
                this.$emit('close2')
            }
        });
        var cond = $('#main_body').hasClass('overflowM')
        var element = document.getElementById("main_body")
        if(!cond) {
            element.classList.add('overflowM')
        }

    }
}
</script>

<style lang="scss" scoped>
.absolute-title {
    position: relative;
    top: 70px;
}
.bg-light {
    background-color: rgba(0, 0, 0, 0.01) !important;
}
.fondo{
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-color: rgba(0,0,0,.7);
    z-index: 10001;
    overflow: auto;
}
.f-24{
   font-size: 24px;
   color: white;
}
.pab {
    background-color: white;
    padding-top: 6em;
    margin-top: 1em;
    margin-bottom: 1em;
    min-height: -webkit-fill-available;
    width: -webkit-fill-available !important;
}
.cerrar{
    
    position: absolute;
    top: 0;
    right: 0;
    z-index: 100;
}
.position{
    top: 50%;
    // background-color: firebrick
}
</style>