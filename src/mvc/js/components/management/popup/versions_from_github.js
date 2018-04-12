(()=>{
  return{
    data(){
      return {
        buttonsAction:[
          {
            text: "Import",
            icon: 'fa fa-download',
            command: ()=>{ this.$refs.form_versions_fromGithub.submit() },
          },
          {
            text: "cancel",
            icon: 'fa fa-ban',
            command: ()=>{ this.$refs.form_versions_fromGithub.cancel() },
          }
        ],
        git_id_ver:"",
      }
    },
    methods:{
      success(ele){
        if ( ele.data ){
          bbn.vue.closest(this, 'bbn-popup').close();
          bbn.vue.closest(this, 'bbn-tab').popup().open({
            height: '95%',
            width: '85%',
            title: bbn._('Add version library') + " " + this.source.folder,
            component:'appui-cdn-management-library_edit',
            source: $.extend({row: ele.data}, {name: this.source.folder})
          });
        }
      }
    },
    computed:{
      versions(){
        if ( this.source.versions ){
          let arr = [];
          for(let ele of this.source.versions ){
            arr.push({
              text: ele.text,
              value: ele.id
            })
          }
          return arr;
        }
      },
      dataPost(){
        return {
          folder: this.source.folder,
          git_repo: this.source.git_repo,
          git_user: this.source.git_user,
          git_id_ver: this.git_id_ver
        }
      }
    }
  }
})();
