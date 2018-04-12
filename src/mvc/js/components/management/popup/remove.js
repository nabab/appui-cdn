(() => {
  return {
    props: ['source'],
    data(){
      return{
        deleteElement: "",
        deleteFolderElement: "",
        infoForDelete: {
          removeFolder: false,
          library: this.source.deleteLib ? "" : this.source.library,
          id_ver:  this.source.deleteLib ? "" : this.source.id,
          name: this.source.name
        }
      }
    },
    methods:{
      success(){
        bbn.vue.closest(this,'bbn-popup').close();
        if ( this.source.deleteLib ){
          appui.success(bbn._('delete libreray'));
        }
        else{
          appui.success(bbn._('delete version'));
        }
        this.management.refreshManagement();
      },
      failure(){
        if ( this.source.deleteLib ){
          appui.error(bbn._("Error delete libray"));
        }
        else{
          appui.error(bbn._("Error delete version"));
        }
      }
    },
    mounted(){
      //case delete library
      if ( this.source.deleteLib ){
        this.deleteElement = bbn._("Do you want delete library") +  " " +  this.source.title + " ?";
        this.deleteFolderElement = "( " +  bbn._("Delete the folder" ) + " " +   this.source.name + " " +  bbn._("in cdn") + " )";
      }
      //case delete version
      else{
        this.deleteElement = bbn._("Do you want delete version ") +  this.source.name  +" ?";
        this.deleteFolderElement = "( " +  bbn._("Delete the folder version" ) + " " +   this.source.name + " " +  bbn._("in cdn") + " )";
      }
    }
  }
})();
