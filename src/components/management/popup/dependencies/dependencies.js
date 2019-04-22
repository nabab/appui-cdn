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
          return `<div class='bbn-c'><i title='` + this.source.listUpdate[id]['latest'] +  `' class='nf nf-fa-upload bbn-bg-red bbn-white bbn-xxl'></i></div>`;
        }
        return '';
      }
    },
    created(){
      management = bbn.vue.closest(this, 'bbn-container').getComponent();
    },
  }
})();
