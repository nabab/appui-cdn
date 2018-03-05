(()=>{
  return {
    data(){
      return{}
    },
    methods:{
      buttons(){
        return [
          {
           text: 'Delete',
           command: ()=>{
             this.removeList(row, col, idx);
           },
           icon: 'fa fa-trash',
           title: 'update',
           notext: true
         },/*{
            text: 'Update',
            command: ()=>{
              this.updateLib(row, col, idx);
            },
            icon: 'fa fa-cogs',
            title: 'update',
            notext: true
          }*/
        ]
      },
      removeList(row, col, idx){
        alert();
      },
      //updateLib(row, col, idx){}
    }
  }
})();
