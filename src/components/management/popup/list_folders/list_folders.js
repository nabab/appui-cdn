(()=>{
  return{
    data(){
      return {
        // buttonsAction:[
        //   {
        //     text: bbn._("cancel"),
        //     icon: 'nf nf-fa-ban',
        //     action: ()=>{ this.$refs.form.cancel() },
        //   }, {
        //     text: bbn._("Skip"),
        //     icon: 'nf nf-fa-angle_double_right',
        //     action: this.skipListVersionsGitHub
        //   }, {
        //     text: bbn._("Import"),
        //     icon: 'nf nf-fa-download',
        //     action: ()=>{ this.$refs.form.submit() },
        //     disabled: this.folder !== undefined
        //   }
        // ],
        folderId:"",

      }
    },
    methods:{
      submit(){
        if ( this.folder !== undefined ){
          this.getPopup().open({
            height: 950,
            width: 850,
            title: bbn._('Add version to this library') + " " + this.source.name,
            component:'appui-cdn-management-library_edit',
            source: {
              row: this.folder,
              name: this.source.name
            }
          });
        }
      },
      skipListVersionsGitHub(){
        this.closest('bbn-popup').close();
        this.post('cdn/github/versions', {
          url: this.source.github
        }, ele => {
          this.getPopup().open({
            height: '25%',
            width: '50%',
            title: bbn._("List versions from repository"),
            component:'appui-cdn-management-popup-versions_from_github',
            source: bbn.fn.extend({}, ele.data, {folder: this.source.name, versions: ele.data.versions})
          });
        });
      },
      deleteFolder(){
        if ( (this.folder !== undefined) && (this.folder.version !== undefined) ){
          this.post(appui.plugins['appui-cdn']+'/actions/folder_version/remove', {
            folder: this.source.name,
            version_folder: this.folder.version
          }, d => {
            if ( d.success ){
              appui.success('Folder delete');
              if ( d.folders && d.folders.length ){
                this.folderId = "";
                this.$set(this.source, 'folders', d.folders);
              }
              else{
                this.closest('bbn-popup').close();
              }
            }
            else{
              appui.errror(bbn._("Error while deleting"))
            }
          });
        }
      }
    },
    computed:{
      folders(){
        if ( this.source.folders && this.source.folders.length ){
          return this.source.folders.map( (folder, idx) => {
            return {
              text: folder.version,
              value: idx
            }
          })
        }
        return [];
      },
      folder(){
        return this.source.folders[this.folderId];
      },
      buttonsAction(){
        return [
          {
            text: bbn._("cancel"),
            icon: 'nf nf-fa-ban',
            action: ()=>{ this.$refs.form.cancel() },
          }, {
            text: bbn._("Skip"),
            icon: 'nf nf-fa-angle_double_right',
            action: this.skipListVersionsGitHub
          }, {
            text: bbn._("Import"),
            icon: 'nf nf-fa-download',
            action: ()=>{ this.submit() },
            disabled: ()=>{return this.folder === undefined;}
          }
        ];
      }
    }
  }
})();
