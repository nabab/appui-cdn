(()=>{
  var management = false;
  return {
    data(){
      return{}
    },
    methods:{
      showIconUpdate(ele){
        let id = bbn.fn.search(this.source.listUpdate, 'title', ele.name);
        if ( id !== -1 ){
          return `<div class='bbn-c'><i title='` + this.source.listUpdate[id]['latest'] +  `' class='fas fa-upload w3-red bbn-xxl'></i></div>`;
        }
        return '';
      },
      showIconUpdateOfManagment(ele){
        return ele.update === true ? `<div class='bbn-c'><i title='` + ele['latest'] +  `' class='fas fa-upload w3-red bbn-xxl'></i></div>`
      }
    },
    created(){
      management = bbn.vue.closest(this, 'bbns-tab').getComponent();
    },
  }
})();
