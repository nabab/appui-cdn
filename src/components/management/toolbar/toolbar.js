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
        this.getPopup().open({
          width: "80%",
          height: "20%",
          title: bbn._("GitHub Link:"),
          component: 'appui-cdn-management-popup-toolbar-library_add',
          //source: this.updateList
        });
      },
      checkUpdate(){
        appui.confirm(this.management.source.lng.checkUpdates, ()=>{
          bbn.fn.post(this.management.source.root + 'github/updates', {}, d =>{
            if ( d.data.updates.length  ){
              this.updateList = d.data.updates;
              this.totalUpdateList = d.data.total;
            }
          });
        });
      },
      showUpdate(){
        if (this.updateList.length && !this.disabledButton ){
          this.getPopup().open({
            width: 700,
            height: 500,
            title: bbn._("GitHub updates") + ' ' + this.totalUpdateList + ' ' + bbn._("libraries"),
            component: 'appui-cdn-management-popup-toolbar-update',
            source: {
              list: this.updateList
            }
          });
        }
      },
      searchLibrary(ele){
        this.management.searchContent = [];
        for(let lib of this.management.source.all_lib){
          if ( lib.name.indexOf(this.searchNameLibrary.toLowerCase()) > -1 ){
            this.management.searchContent.push(lib);
          }
        }
        this.management.search = true;
      }
    },
    computed:{
      management(){
        return this.closest("bbns-tab").getComponent()
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
        this.searchLibrary();
      }
    }
  }
})();