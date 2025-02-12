(()=>{
  return{
    data(){
      return {
        buttonsAction:[
          {
            text: "cancel",
            icon: 'nf nf-fa-ban',
            action: ()=>{ this.$refs.form_versions_fromGithub.cancel() },
          },{
            text: "Import",
            icon: 'nf nf-fa-download',
            action: ()=>{ this.$refs.form_versions_fromGithub.submit() },
          }
        ],
        git_id_ver:"",
      }
    },
    methods:{
      success(ele){
        if ( ele.data ){
          this.closest('bbn-popup').close();
          this.getPopup({
            height: '500px',
            width: '600px',
            label: bbn._('Add version to this library') + " " + this.source.folder,
            component:'appui-cdn-management-library_edit',
            source:{
              row: bbn.fn.extend(ele.data.folders_versions[0], {github: ele.data.github}),
              name: this.source.folder
            }
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
      lastVersion(){
        let i = bbn.fn.search(this.source.versions, 'is_latest', true);
        if ( i === -1 ){
          return false;
        }
        return this.source.versions[i]['id'];
      },
      versionName(){
        return bbn.fn.getField(this.source.versions, 'version', 'id', this.git_id_ver);
      },
      dataPost(){
        return {
          folder: this.source.folder,
          git_repo: this.source.git_repo,
          git_user: this.source.git_user,
          git_id_ver: this.git_id_ver,
          git_latest_ver: this.lastVersion,
          version: this.versionName,
          tags: this.source.tags
        }
      }
    }
  }
})();
