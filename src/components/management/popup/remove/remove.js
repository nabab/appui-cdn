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
          appui.success(bbn._('Library deleted'));
        }
        else{
          appui.success(bbn._('Version deleted'));
        }
        this.management.refreshManagement();
      },
      failure(){
        if ( this.source.deleteLib ){
          appui.error(bbn._("Error while deleting library"));
        }
        else{
          appui.error(bbn._("Error while deleting version"));
        }
      }
    },
    mounted(){
      //case delete library
      if ( this.source.deleteLib ){
        this.deleteElement = bbn._("Do you really want delete the library") +  " " +  this.source.title + "?";
        this.deleteFolderElement = "(" +  bbn._("and delete the folder" ) + " " +   this.source.name + ")";
      }
      //case delete version
      else{
        this.deleteElement = bbn._("Do you really want to delete the version") + ' ' + this.source.name  +"?";
        this.deleteFolderElement = "(" +  bbn._("and delete the version folder" ) + " " +   this.source.name + ")";
      }
    }
  }
})();
