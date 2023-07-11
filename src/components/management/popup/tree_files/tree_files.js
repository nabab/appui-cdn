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
        let obj = {
          data: ele,
          path: ele.path,
          items: ele.items || [],
          icon: 'nf nf-fa-file',
          file: true,
          text: ele.text,
          num: ele.items ? ele.items.length : 0,
          numChildren: ele.items ? ele.items.length : 0
        };
        if ( obj.items.length > 0 ){
          obj.file = false;
          obj.icon = 'nf nf-fa-folder';
        }
        return obj;
        // return {
        //   data: ele,
        //   path: ele.path,
        //   items: ele.items || [],
        //   icon: ele.items.length !== 0 'nf nf-fa-folder' : 'nf nf-fa-file',
        //   file: ele.items.length !== 0 ? false : true,
        //   text: ele.text,
        //   num: ele.items ? ele.items.length : 0,
        //   numChildren: ele.items ? ele.items.length : 0
        // }
      },
      addLanguage(){
        this.source.table.push({path: this.element});
        this.closest("bbn-popup").close();
      },
      selectElement(node){
        if ( node.data.file ){
          this.element = node.data.path;
        }
      }
    }
  }
})();
