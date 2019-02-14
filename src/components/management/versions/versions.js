(() => {
  let infoVersions= "";
  return {
    data(){
      return {
        link: 'cdn/data/versions',
        management: this.closest("bbns-tab").getComponent(),
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
            icon: 'fas fa-info',
            title: bbn._('info'),
            notext: true
          },{
            text: 'Edit',
            command:(row, col, idx)=>{
              this.management.actions('editVers');
              this.editVersion(row, col, idx);
            },
            icon: 'fas fa-edit',
            title: bbn._('Edit'),
            notext: true
          },{
            text: 'Scripts',
            command: (row, col, idx) =>{
              this.viewPackageJson(row, col, idx)
            },
            icon: 'fas fa-play',
            title: 'scripts',
            notext: true
          },{
           text: 'Delete',
           command:(row, col, idx)=>{
             this.deleteVersion(row, col, idx);
           },
           icon: 'fas fa-trash',
           title: 'delete',
           notext: true,
           disabled: this.versionsInfo.length !== 1 ? false : true
          }
        ];
      },
      viewPackageJson(row, col, idx){
        bbn.fn.post(appui.plugins['appui-cdn'] + '/data/version/get_file',
          {
            file: 'package.json',
            library: row.library,
            version: row.name
          },
          d => {
            if ( d.success ){
              let listScripts = [];
              if ( d.data['scripts'] !== undefined ){
                Object.keys(d.data['scripts']).forEach( key => {
                  listScripts.push({
                    text: key,
                    value: d.data['scripts'][key]
                  })
                });
              }
              this.getPopup().open({
                width: 550,
                height: 200,
                title: bbn._("Script package.json"),
                component: this.$options.components.packgeJson,
                source: {
                  scripts: listScripts
                }
              });
            }
            else{
              appui.error(bbn._('Error'));
            }
          }
        );
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
                icon: 'fas fa-folder',
                items: []
              };
            if ( infos.content[i].length ){
              infos.content[i].forEach((file,id)=>{
                obj.items.push({
                  text: file,
                  num: 0,
                  numchildren: 0,
                  icon: 'fas fa-file'
                })
              });
            }
            arr.push(obj);
          }
        }
        infos.content = arr;
        this.getPopup().open({
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
        bbn.vue.closest(this, "bbns-tab").popup().confirm(bbn._('Are you sure you want to delete?'), ()=>{
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
        this.getPopup().open({
          width: 500,
          height: 200,
          title: bbn._("Remove version in library") +  " " + this.source.title,
          component: 'appui-cdn-management-popup-remove',
          source: $.extend(row, {titleLibrary: this.source.title, action:this.management.source.root + 'actions/version/delete'})
        })
      },
    },
    mounted(){
      bbn.fn.post(this.link, {id_lib: this.source.name}, d => {
        if ( d.data.success ){
          this.versionsInfo = d.data.versions
        }
      });
    },
    created(){
      infoVersions = this;
    },
    components: {
      'packgeJson':{
        template:
        ` <div class="bbn-full-screen">
            <div v-if="source.scripts.length > 0" class="bbn-flex-height">
              <div class="bbn-padded bbn-grid-fields"
                   style="grid-auto-rows: max-content max-content max-content max-content auto"
              >
               <span v-text="_('Scripts:')" class="bbn-l bbn-b"></span>
               <bbn-dropdown :source="source.scripts" v-model="contentScript"></bbn-dropdown>
              </div>
              <div v-if="contentScript.length" class="bbn-flex-fill bbn-w-100 bbn-middle">
               <div class="w3-card bbn-padded">
                <span class="bbn-b" v-text="contentScript"></span>
               </div>
              </div>
            </div>
            <div v-else class="bbn-middle bbn-b bbn-h-100 bbn-xl" v-text="_('No Script')"></div>
          </div>`,
        props:['source'],
        data(){
          return {
            contentScript: ''
          }
        }
      },
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
        template: `<bbn-button icon="fas fa-plus" @click="add"></bbn-button>`,
        props:['source'],
        data(){
          return {
            //tableVersions: this.closest("appui-cdn-management-versions")
            tableVersions:{
              source: infoVersions.source,
              management: infoVersions.management
            }
          }
        },
        methods:{
          add(){
            this.tableVersions.management.actions("addVers");
            bbn.fn.post(this.tableVersions.management.source.root +'data/version/add', {
              folder: this.tableVersions.source.name
            }, d => {
              if( d.data && (d.data.folders_versions !== undefined) ){
                this.getPopup().open({
                  height: '200px',
                  width: '450px',
                  title: bbn._('Exsist folders version') + " " + this.tableVersions.source.name,
                  component:'appui-cdn-management-popup-list_folders',
                  source: {
                    folders: d.data.folders_versions,
                    name: this.tableVersions.source.name,
                    github: d.data.github
                  }
                });
              }
              else if ( d.data.github && (d.data.folders_versions === undefined) ){
                this.confirm( this.tableVersions.management.source.lng.versionGithubImport, ()=>{
                  bbn.fn.post( this.tableVersions.management.source.root + 'github/versions', {url: d.data.github}, ele => {
                    this.getPopup().open({
                      height: '25%',
                      width: '50%',
                      title: this.tableVersions.management.source.lng.githubVersion,
                      component:'appui-cdn-management-popup-versions_from_github',
                      source: $.extend({}, ele.data, {folder: this.tableVersions.source.name, versions: ele.data.versions})
                    });
                  });
                });
              }
              else{
                this.alert(this.tableVersions.management.source.lng.noNewVersion);
              }
            });
          }
        }
      }
    }
  }
})();
