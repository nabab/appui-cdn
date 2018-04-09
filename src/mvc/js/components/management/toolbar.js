(() => {
  return {
    data(){
      return {
        updateList: [],
        countList: false,
        searchNameLibrary:""
      }
    },
    methods:{
      add(){
        this.management.actions('addLib');
        bbn.vue.closest(this, 'bbn-tab').popup().open({
          width: "80%",
          height: "20%",
          title: bbn._("GitHub Link:"),
          component: 'appui-cdn-management-popup-toolbar-library_add',
          //source: this.updateList
        });
      },
      checkUpdate(){
        bbn.fn.confirm(this.management.source.lng.checkUpdates, ()=>{
          bbn.fn.post(this.management.source.root + 'github/updates', {}, d =>{
            if ( d.data && d.data.length ){
              this.updateList = d.data;
            }
          });
        });
      },
      showUpdate(){
        if (this.updateList.length && !this.disabledButton ){
          bbn.vue.closest(this, 'bbn-tab').popup().open({
            width: 700,
            height: 500,
            title: bbn._("GitHub updates:"),
            component: 'appui-cdn-management-popup-toolbar-update',
            source: this.updateList
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
      //for disable o no in button on toolbar "update"
      disabledButton(){
        if ( this.updateList.length ){
          this.countList = this.updateList.length;
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
