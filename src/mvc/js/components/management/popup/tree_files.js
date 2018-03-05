(()=>{
  return{
    methods:{
      mapMenu(ele){
        return {
          data: ele,
          path: ele.path,
          items: ele.items || [],
          icon: ele.items ? 'fa-fa-folder' : 'fa fa-file',
          text: ele.text,
          num: ele.items ? ele.items.length : 0,
          numChildren: ele.items ? ele.items.length : 0
        }
      },
      selectElement(){
        alert("selectttttt");
      }
    }
  }
})();
