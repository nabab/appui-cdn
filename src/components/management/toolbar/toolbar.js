(() => {
  return {
    data(){
      return {
        updateList: [],
        totalUpdateList: 0,
        searchNameLibrary:""
      }
    },
    methods:{
      add(){
        this.management.actions('addLib');
        this.getPopup({
          width: "70%",
          height: "15%",
          label: bbn._("GitHub link"),
          component: 'appui-cdn-management-popup-toolbar-library_add',
          //source: this.updateList
        });
      },
      checkUpdate(){
        appui.confirm(this.management.source.lng.checkUpdates, ()=>{
          this.post(this.management.source.root + 'github/updates', {}, d =>{
            if ( d.data.updates.length  ){
              this.updateList = d.data.updates;
              this.totalUpdateList = d.data.total;
            }
          });
        });
      },
      showUpdate(){
        if (this.updateList.length && !this.disabledButton ){
          this.getPopup({
            width: 700,
            height: 500,
            label: bbn._("GitHub updates") + ' ' + this.totalUpdateList + ' ' + bbn._("libraries"),
            component: 'appui-cdn-management-popup-toolbar-update',
            source: {
              list: this.updateList
            }
          });
        }
      },
      searchLibrary(ele){
        let name = '';
        this.management.searchContent = [];
        for(let lib of this.management.source.all_lib){
          name = lib.name.toLowerCase();
          if ( name.indexOf(ele.toLowerCase()) === 0 ){
            this.management.searchContent.push(lib);
          }
        }
        this.management.search = true;
      }
    },
    computed:{
      management(){
        return this.closest("bbn-container").getComponent()
      },
      //for disable o no in button on toolbar "update"
      disabledButton(){
        if ( this.updateList.length && (this.totalUpdateList > 0) ){
          return false;
        }
        return true;
      }
    },
    watch:{
      searchNameLibrary(val, oldVal){
        if( val.length ){
          this.management.search = true;
        }
        else{
          this.management.search = false;
        }
        this.searchLibrary(val);
      }
    }
  }
})();
