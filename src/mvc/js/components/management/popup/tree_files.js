(()=>{
  return{
    data(){
      return {
        element: ""
      }
    },
    methods:{
      mapMenu(ele){
        if ( ele.items ){
          ele.items.forEach((item, idx) => {
            ele.items[idx] = this.mapMenu(item);
          });
        }
        return {
          data: ele,
          path: ele.path,
          items: ele.items || [],
          icon: ele.items ? 'fa fa-folder' : 'fa fa-file',
          file: ele.items ? false : true,
          text: ele.text,
          num: ele.items ? ele.items.length : 0,
          numChildren: ele.items ? ele.items.length : 0
        }
      },
      addLanguage(){
        this.source.table.push({path: this.element});
        bbn.vue.closest(this, "bbn-popup").close();
      },
      selectElement(node){
        bbn.fn.log("selectElement", node);
        if ( node.data.file ){
          this.element = node.data.path;
          //this.$set(this, "element", node.data.path);
        }
      }
    }
  }
})();
