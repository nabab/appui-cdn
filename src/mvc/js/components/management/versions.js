(() => {
  var tableVersions;
  return {
    data(){
      return {
        link: 'cdn/data/versions',
        versionsInfo: []
      }
    },
    methods:{
      buttonsTable(row, col, idx){
        return [
          {
            text: "Info",
            command:(row, col, idx) => {
              this.infoVersion(row, col, idx);
            },
            icon: 'fa fa-info',
            title: 'info',
            notext: true
          },
          {
            text: 'Edit',
            command:(row, col, idx)=>{
              this.management.actions('editVers');
              this.editVersion(row, col, idx);
            },
            icon: 'fa fa-edit',
            title: 'edit',
            notext: true
          },
          {
           text: 'Delete',
           command:(row, col, idx)=>{
             this.deleteVersion(row, col, idx);
           },
           icon: 'fa fa-trash',
           title: 'delete',
           notext: true,
           disabled: this.versionsInfo.length !== 1 ? false : true
          }
        ];
      },
      infoVersion(row, col, idx){
        let infos = row;
        let arr = [];
        infos.content = JSON.parse(infos.content);
        for(let i in infos.content) {
          if ( (i === "files") || (i === "lang") || (i === "theme_files") ){
            let obj ={
                text: i ,
                numChildren: infos.content[i].length,
                num: infos.content[i].length,
                icon: 'fa fa-folder',
                items: []
              };
            if ( infos.content[i].length ){
              infos.content[i].forEach((file,id)=>{
                obj.items.push({
                  text: file,
                  num: 0,
                  numchildren: 0,
                  icon: 'fa fa-file'
                })
              });
            }
            arr.push(obj);
          }
        };
        infos.content = arr;
        bbn.vue.closest(this, 'bbn-tab').popup().open({
          width: 365,
          height: 380,
          title: bbn._("Info version "),
          component: this.$options.components.info,
          source:infos
        })
      },
      editVersion(row, col, idx){
        return this.$refs.tableVersionsLib.edit(row,{
          title: bbn._('Edit the version of the') + ' ' + row.library + ' ' + bbn._('library'), //this.management.source.lng.edit_library,
          height: '98%',
          width: '85%',
        }, idx)
      },
      deleteVersion(row, col, id){/*
        bbn.vue.closest(this, "bbn-tab").popup().confirm(bbn._('Are you sure you want to delete?'), ()=>{
          if ( (row.id !== undefined) && (row.library !== undefined) ){
            bbn.fn.post( this.management.source.root + 'actions/version/delete', {
              id_ver: row.id,
              library: row.library
            }, d => {
              if ( d.data.success ){
                this.management.refreshManagement();
                appui.success(bbn._("Delete version"));
              }
              else{
                appui.error(bbn._("Error delete version"));
              }
            });
          }
        });*/
        bbn.vue.closest(this, 'bbn-tab').popup().open({
          width: 500,
          height: 200,
          title: bbn._("Remove version in library") +  " " + this.source.title,
          component: 'appui-cdn-management-popup-remove',
          source: $.extend(row, {titleLibrary: this.source.title, action:this.management.source.root + 'actions/version/delete'})
        })
      },
    },
    created(){
      tableVersions = this;
    },
    mounted(){
      bbn.fn.post(this.link, {id_lib: this.source.name}, d => {
        if ( d.data.success ){
          this.versionsInfo = d.data.versions
        }
      });
    },
    components: {
      //button info version
      'info':{
        template:
        ` <div class="bbn-full-screen">
            <div class="bbn-padded bbn-h-100 bbn-grid-fields"
                 style="grid-auto-rows: max-content max-content max-content max-content auto"
             >
              <span v-text="_('Id:')" class="bbn-l bbn-b"></span>
              <bbn-input v-text="source.id"></bbn-input>
              <span v-text="_('Name:')" class="bbn-l bbn-b"></span>
              <bbn-input v-text="source.name" class="bbn-l bbn-b"></bbn-input>
              <span v-text="_('Library')" class="bbn-l bbn-b"></span>
              <bbn-input v-text="source.library"></bbn-input>
              <span v-text="_('Date added')" class="bbn-r bbn-b"></span>
              <bbn-input v-text="source.date_added" readonly></bbn-input>
              <span v-text="_('Content:')" class="bbn-l bbn-b" v-if="source.content"></span>
              <bbn-tree :source="source.content"  v-if="source.content">
              </bbn-tree>
            </div>
          </div>`,
        props:['source']
      },
      'addVersions' : {
        template: `<bbn-button icon="fa fa-plus" @click="add"></bbn-button>`,
        props:['source'],
        methods:{
          add(){
            tableVersions.management.actions("addVers");
            bbn.fn.post("cdn/data/version/add", {folder: tableVersions.source.name}, (d)=>{
              if ( d.data.github ){
                bbn.fn.confirm( tableVersions.management.source.lng.versionGithubImport, ()=>{
                  bbn.fn.post( tableVersions.management.source.root + 'github/versions', {url: d.data.github}, ele => {
                    bbn.vue.closest(this, 'bbn-tab').popup().open({
                      height: '25%',
                      width: '50%',
                      title: tableVersions.management.source.lng.githubVersion,
                      component:'appui-cdn-management-popup-versions_from_github',
                      source: $.extend({}, ele.data, {folder: tableVersions.source.name, versions: tableVersions.versionsInfo})
                    });
                  });
                });
              }
              else if( d.data &&
                d.data.files_tree &&
                d.data.languages_tree
              ){
                bbn.vue.closest(this, 'bbn-tab').popup().open({
                  height: '95%',
                  width: '85%',
                  title: bbn._('Add version library') + " " + tableVersions.source.name,
                  component:'appui-cdn-management-library_edit',
                  source: $.extend({row: d.data}, {name: tableVersions.source.name, versions: tableVersions.versionsInfo})
                });
              }
              else{
                bbn.fn.alert(tableVersions.management.source.lng.noNewVersion);
              }
            });
          }
        }
      }
    }
  }
})();
