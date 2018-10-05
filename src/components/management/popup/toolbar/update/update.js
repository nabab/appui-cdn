(()=>{
  var management = false;
  return {
    created(){
      management = bbn.vue.closest(this, 'bbns-tab').getComponent();
    },
    methods:{
      buttons(row, col, idx){
        return [
         {
           text: bbn._('Dependencies'),
           command: ()=>{
             this.dependencies(row, col, idx);
           },
           icon: 'fas fa-code-branch',
           title: bbn._('Dependencies'),
           notext: true,
           style:"width:30%"
         },{
           text: bbn._('Dependencies new version'),
           command: ()=>{
             this.infoNewVersion(row, col, idx);
           },
           notext: true,
           icon: 'fas fa-eye',
           title: bbn._('new'),
           style:"width:30%"
         },{
            text: bbn._('Update'),
            command: () => {
              this.updateLib(row);
            },
            icon: 'fas fa-cogs',
            title: bbn._('Update'),
            notext: true,
            style:"width:30%"
          }
        ]
      },
      dependencies(row, col, idx){
        bbn.fn.post(management.source.root+'data/dependencies',{folder: row.folder}, d => {
          if ( (d.data.depend.length > 0) || (d.data.dependent.length > 0) ){
            this.getPopup().open({
              width: 700,
              height: 500,
              title: bbn._('Dependencies library')+" "+row.folder+" "+row.local,
              component:'appui-cdn-management-popup-dependencies',
              source: {
                depend: d.data.depend,
                dependent: d.data.dependent,
                listUpdate: this.source.list
              }
            });
          }
          else{
            alert("nessuse dipendenze nel cdn");
          }
        })
      },
      popUpAddVersion(ele,lib){
        if( ele.data &&
          ele.data.files_tree &&
          ele.data.languages_tree
        ){
          this.getPopup().open({
            height: '95%',
            width: '85%',
            title: bbn._('Add version library') + " " + lib.title,
            component:'appui-cdn-management-library_edit',
            source: {row: ele.data, name: lib.title}
          });
        }
        else{
          this.alert(management.source.lng.noNewVersion);
        }
      },
      updateLib(row){
        management.actions("addVers");
        bbn.fn.post(management.source.root +'data/version/add',{folder: row.folder}, d => {
          if ( d.data.github ){
            bbn.fn.post(management.source.root +'data/version/add',{
              folder: row.folder,
              git_repo: row.git_repo,
              git_user: row.git_user,
              git_latest_ver: row.latest
            }, a => {
              this.popUpAddVersion(a, row)
            });
          }
          else{
            this.popUpAddVersion(d, row);
          }
        });
      },
      infoNewVersion(row){
        bbn.fn.post(management.source.root +'github/info',{
          git_user: row.git_user,
          git_repo: row.git_repo,
          url: row.github,
          info_package_json: true
        }, d => {
          bbn.fn.log("REsult", d);
        });
      }
    }
  }
})();
