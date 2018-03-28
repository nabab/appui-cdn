(()=>{
  let management,
      tableVersions;
  return {
    mixins: [bbn.vue.localStorageComponent],
    created(){
      management = this;
      let mixins = [{
        data(){
          return {
            management: management
          }
        },
      }];
      bbn.vue.setComponentRule('cdn/components/management', 'appui-cdn-management');
      //for button in colums action
      bbn.vue.addComponent('popup/info_lib', mixins);
      bbn.vue.addComponent('popup/toolbar/update', mixins);
      bbn.vue.addComponent('popup/tree_files', mixins);
      bbn.vue.addComponent('popup/toolbar/library_add', mixins);
      bbn.vue.addComponent('popup/versions_from_github', mixins);
      bbn.vue.addComponent('library_edit', mixins);
      bbn.vue.addComponent('libraries_toolbar', mixins);
      bbn.vue.unsetComponentRule();
    },
    data(){
      return{
        searchContent: [],
        search: false,
        action:{
          post: "",
          addLib: false,
          addVers: false,
          editLib: false,
          editVers: false
        }
      }
    },
    methods:{
      buttons(row, col, idx){
        return [
          {
            text: "Info",
            command:()=>{
              this.info(row, col, idx);
            },
            icon: 'fa fa-info',
            title: 'info',
            notext: true
          },
          {
            text: 'Edit',
            command:()=>{
              /*
              this.action.post = this.source.root + 'actions/library/edit'
              this.action.addLib = false;
              this.action.addVers = false;
              this.action.editLib =  true;
              this.action.editVers =  false;*/
              this.actions('editLib');
              this.editLibrary(row, col, idx);
            },
            icon: 'fa fa-edit',
            title: 'edit',
            notext: true
          },
          {
           text: 'Delete',
           command:()=>{
             this.removeLib(row,col,idx);
           },
           icon: 'fa fa-trash',
           title: 'delete',
           notext: true
          }
        ];
      },

      /* this function is activated at the click of the info button and
       *  its task is to display a popup with all the information related to the selected library
       */
      info(row, col, idx){
        let obj = {
          info : row,
          versions: []
        };
        bbn.fn.post(this.source.root + 'data/versions', {id_lib: row.name}, d =>{
          if ( d.data.success ){
            if ( d.data.versions ){
              for ( let ele of d.data.versions ){
                obj.versions.push({
                  name: ele.name,
                  date: moment(ele.date_added).format('DD/MM/YYYY')
                });
              }
            }
            bbn.vue.closest(this, 'bbn-tab').popup().open({
              width: 480,
              height: 700,
              title: bbn._("Info") + ': '+ row.name,
              component: 'appui-cdn-management-popup-info_lib',
              source: obj
            })
          }
        });
      },
      editLibrary(row, col, idx){
        return this.$refs.cdn_management.edit(row,  {
          title: this.source.lng.editLib,
          height: '95%',
          width: '85%'
        }, idx);
      },
      /*edit(row, col, idx){
        return this.$refs.table.edit(row, bbn._("Edit Library"), idx);
      },*/
      actions(type){
        switch(type){
          case 'addVers':
            this.action.post = this.source.root + 'actions/version/add';
            break;
          case 'editVers':
            this.action.post = this.source.root + 'actions/version/edit';
          break;
          case 'addLib':
            this.action.post = this.source.root + 'actions/library/add';
            break;
          case 'editLib':
            this.action.post = this.source.root + "actions/library/edit";
            break;
        }
        for(let typeAction in this.action){
          if ( typeAction === type ){
            this.action[typeAction] = true;
          }
          else{
            if (typeAction !== 'post'){
              this.action[typeAction] = false;
            }
          }
        }
      },
      removeLib(row, col, idx){
        bbn.fn.log("delete", row, col, idx)
        bbn.fn.confirm(bbn._("Delete library") + " "+  row.name + "?" , ()=>{
          bbn.fn.post(this.source.root + 'actions/library/delete', {name: row.name}, (d) => {
            if ( d.data.success ){
              appui.success(bbn._("Delete"));
              bbn.vue.find(bbn.vue.closest(this, 'bbn-tab'), 'bbn-table').updateData();
            }
            else{
              appui.error(bbn._("Error"));
            }
          });
        });
      },
      //for render
      showIconAuthor(ele){
        return ( ele.author && ele.author.length ) ? '<div class="bbn-c"><i class="fa fa-user bbn-xl" title="' + ele.author + '"></i></div>' : '';
      },
      showIconLicense(ele){
        return ( ele.licence && ele.licence.length ) ? "<div class='bbn-c'><i class='fa fa-copyright'></i></div>" : '';
      },
      showIconWeb(ele){
        return ( ele.website && ele.website.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele.website + "'" + "target='_blank'>" + "<i class='fa fa-globe'</i></a></div>" : '';
      },
      showIconDownload(ele){
        return ( ele.download_link && ele.download_link.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele.download_link + "'" + "target='_blank'>" + "<i class='fa fa-download'</i></a></div>" : '';
      },
      showIconDoc(ele){
        return ( ele.doc_link && ele.doc_link.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele.doc_link + "'" + "target='_blank'>" + "<i class='fa fa-book'</i></a></div>" : '';
      },
      showIconGit(ele){
        return ( ele.git && ele.git.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele.git + "'" + "target='_blank'>" + "<i class='fa fa-github'</i></a></div>" : '';
      },
      showIconSupportLink(ele){
        return ( ele.support_link && ele.support_link.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele.support_link + "'" + "target='_blank'>" + "<i class='fa fa-ambulance'</i></a></div>" : '';
      },
    },
    computed:{
      sourceTable(){
        if ( this.searchContent.length ){
          return this.searchContent
        }
        return this.source.all_lib
      }
    },
    watch:{
        sourceTable(val){
          if ( val && this.search ){
            this.$nextTick(()=>{
              this.$refs.cdn_management.updateData()
            });
          }
        }
    },
    components: {
      'cdn-management-table-lib-versions':{
        template: '#apst-cdn-management-table-lib-versions',
        name:'cdn-management-table-lib-versions',
        created (){
          tableVersions = this;
        },
        props: ['source'],
        data(){
          return{
            link: management.source.root + 'data/versions',
            versionsInfo: []
          }
        },
        methods:{
          buttonsVersion(row, col, idx){
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
                  management.actions('editVers');
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
               notext: true
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
              title: this.source.lng.edit_library,
              height: '95%',
              width: '85%'
            }, idx)
          },
          deleteVersion(row, col, id){
            if ( (row.id !== undefined) && (row.library !== undefined) ){
              bbn.fn.post( management.source.root + 'actions/version/delete', {
                id_ver: row.id,
                library: row.library
              }, d => {
                if ( d.data.success ){
                  this.$refs.tableVersionsLib.updateData();
                  appui.success(bbn._("Delete version"));
                }
                else{
                  appui.error(bbn._("Error delete version"));
                }
              });
            }
          },
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
                management.actions("addVers");
                bbn.fn.post("cdn/data/version/add", {folder: tableVersions.source.name}, (d)=>{
                  if ( d.data.github ){
                    bbn.fn.confirm( management.source.lng.versionGithubImport, ()=>{
                      bbn.fn.post( management.source.root + 'github/versions', {url: d.data.github}, ele => {
                        bbn.vue.closest(this, 'bbn-tab').popup().open({
                          height: '25%',
                          width: '50%',
                          title: management.source.lng.githubVersion,
                          component:'appui-cdn-management-popup-versions_from_github',
                          source: $.extend({}, ele.data, {folder: tableVersions.source.name})
                        });
                      });
                    });
                  }
                  else if( d.data &&
                    d.data.files_tree &&
                    d.data.files_tree &&
                    d.data.languages_tree
                  ){
                    bbn.fn.log("guarrrrrrrrrda", d);
                    bbn.vue.closest(this, 'bbn-tab').popup().open({
                      height: '95%',
                      width: '85%',
                      title: bbn._('Add version library') + " " + tableVersions.source.name,
                      component:'appui-cdn-management-library_edit',
                      source: $.extend({row: d.data}, {name: tableVersions.source.name})
                    });
                  }
                  else{
                    bbn.fn.alert(management.source.lng.noNewVersion);
                  }
                });
              }
            }
          }
        }
      }
    }
  }
})();
