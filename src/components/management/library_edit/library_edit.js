(() => {
  var editLib;
  return {
    data(){
      return {
        licencesList: [],
        checkedFile: [],
        referenceNodeTree: [],
        copyItemsTree: [],
        dataVersion: {
          dependencies: [],
          dependencies_html: "",
          files_tree : [],
          internal: [],
          languages_tree: [],
          themes_tree: [],
          lib_ver : [],
          slave_dependencies : [],
          version: ""
        },
        listLib:[],
        //for edit
        newName: "",
        //for tables languages file and theme of library
        data:{
          languages: [],
          themes: [],
          latest: true,
          internal: 0,
          versions:[]
        },
        fileMove:"",
        //languages:[{path:" "}],
        configuratorLibrary: false,
        //for tree file at click next
        identification: 0,
        actionsPath:{
          library:{
            edit: 'cdn/actions/library/edit',
            add: 'cdn/actions/library/add'
          },
          version:{
            edit: 'cdn/actions/version/edit',
            add: 'cdn/actions/version/add'
          }
        },
        table: '',
        listNoUpdate:[],
      }
    },
    computed:{
      management(){
        return this.closest("bbns-tab").getComponent()
      },
      // for source colum of the table depandancies
      list(){
        if ( bbn.fn.count(this.dataVersion.dependencies) > 0 ){
          let d = this.dataVersion.dependencies.slice();
          return this.listLib.filter( lib => {
            let exists = false;
            for ( let obj of d ){
              if ( obj.lib_name === lib.value ){
                exists = true;
              }
            }
            return !exists;
          });
        }
        else{
          return this.listLib;
        }
      },
      //disabled or no input name version
      disabledEditVersion(){
        return ( this.management.action.editVers || this.management.action.addVers ) ? true : false
      },
      //for button form
      currentButton(){
        if ( this.management.action ){
          // case for add library of the toolbar first form
          if ( this.management.action.addLib && !this.configuratorLibrary ) {
            return [
              'cancel',
              {
                text: "Next",
                title: "next",
                class:"k-primary",
                icon: 'far fa-arrow-circle-right',
                disabled: (!this.source.row.title && !this.source.row.name)  ? true : false,
                command: ()=>{ this.next() }
              }
            ];
          }
          // case for add library and click next for configurator library before saving
          if ( this.management.action.addLib  && this.configuratorLibrary ){
            return [
              {
                text: "Prev",
                title: "Prev",
                icon: 'far fa-arrow-circle-left',
                command: ()=>{ this.configuratorLibrary = false }
              },
              'cancel',
              {
                text: "Save",
                icon: 'far fa-check-circle',
                class:"k-primary",
                command: ()=>{
                  if ( this.referenceNodeTree.length ){
                    this.$refs.form_library.submit()
                  }
                  else{
                    alert(bbn._("No selected files"));
                  }
                }
              }
            ]
          }// case for edit table cdn managment or edit and add versions
          if ( this.management.action.addVers || this.management.action.editVers || this.management.action.editLib){
            return [
              'cancel',
              {
                text: "Save",
                class:"k-primary",
                icon: 'far fa-check-circle',
                command: ()=>{
                  //case edit library
                  if ( this.management.action.editLib ){
                    if ( this.source.row.title.length || this.source.row.name.length ){
                      this.$refs.form_library.submit()
                    }
                    else{
                      bbn.vue.closest(this, 'bbn-popup').alert(bbn._("No name library or no name folder library"));
                    }
                  }//case edit or sdd version
                  else{
                    if ( this.referenceNodeTree.length ){
                      this.$refs.form_library.submit()
                    }
                    else{
                      bbn.vue.closest(this, 'bbn-popup').alert(bbn._("No file order"));
                    }
                  }
                },
              }
            ]
          }
        }
        return ['cancel']
      },
    //for tree order files
      treeOrderSource(){
        if ( this.referenceNodeTree.length ){
          let arr = [];
          for( let i in this.referenceNodeTree ){
            arr.push( this.referenceNodeTree[i] );
          }
          return arr
        }
        this.fileMove = "";
        return [];
      },
      //for first insert lib or no
      abilitationCheckedLatest(){
       return  !!this.data.latest
      },
      //for form senction 2 first of save library
      complementaryData(){
        return {
          vname: this.dataVersion.version,
          files: this.treeOrderSource,
          languages: this.data.languages,
          themes: this.data.themes,
          new_name: this.newName,
          dependencies: this.dataVersion.dependencies,
          no_update_dependents: this.listNoUpdate,
          last_dependencies: this.dataVersion.slave_dependencies,
          name: this.source.name || "",
          is_latest : this.data.latest
        }
      },
    },
    methods:{
      getInfo(){
        bbn.fn.post(this.management.source.root +"github/info", {
            url: this.source.row.git,
            only_info: true
          },
          (d) => {
            if ( d.data ){
              for (var prop in d.data){
                if ( (prop !== 'name') && (prop !== 'latest') && (this[prop] !== undefined) ){
                  if ( prop === 'licence' ){
                    let lic = bbn.fn.get_field(data.licences, 'name', d.data[prop], 'licence');
                    if ( !lic ){
                      lic = bbn.fn.get_field(data.licences, 'licence', d.data[prop], 'licence');
                    }
                    if ( lic ){
                      this.source.row.licence = lic;
                    }
                  }
                }
              }
            }
          }
        );
      },
      success(){
        this.management.refreshManagement();
        this.$nextTick(()=>{
          bbn.vue.closest(this, "bbn-popup").close();
          appui.success(bbn._('Success!'));
        });
      },
      failure(){
        appui.error(bbn._('A problem has occurred'));
      },//// only case of add library for info and select the files
      next(){
        //let's check if you do it next for the first time or not
        if ( this.management.action.addLib &&
           this.source.row.name &&
           this.source.row.title /*&&
           $.isEmptyObject(this.dataVersion)*/
          ){
            bbn.fn.post(this.management.source.root +'data/version/add', {
              folder: this.source.row.name,
              git_id_ver: this.source.row.git_id,
              git_user:  this.source.row.user !== undefined ? this.source.row.user : false,
              git_repo: this.source.row.repo !== undefined ? this.source.row.repo : false,
              git_latest_ver: this.source.row.latest !== undefined ? this.source.row.latest : false
            }, d => {
                if ( d.data ){
                  for(let i in this.dataVersion){
                    this.dataVersion[i] = (d.data && d.data[i]) ? d.data[i] : []
                    if (i === "themes_tree"){
                      this.dataVersion[i] = d.data.files_tree ?  d.data.files_tree : [];
                    }
                  }
                  this.configuratorLibrary = true;
                  //for dropdown list library in table depanadancies
                  if ( d.data.lib_ver.length ){
                    for ( let val of d.data.lib_ver){
                      if ( bbn.fn.search(this.listLib, 'text', val.lib_title) < 0 ){
                        this.listLib.push({text: val.lib_title, value: val.lib_name});
                      }
                    };
                  }
                }
              }
            );
        }
        else{
          if ( !$.isEmptyObject(this.dataVersion) ){
            this.configuratorLibrary = true;
          }
        }
      },
        /* FIRST INSERT LIBRARY */
      //for map tree in section "next"
      treeFiles(ele){
        if ( ele.items ){
          ele.items.forEach((item, idx) => {
            ele.items[idx] = this.treeFiles(item);
          });
        }
        return {
          data: ele,
          path: ele.path,
          items: ele.items || [],
          icon: ele.items ? 'fas fa-folder' : 'fas fa-file',
          text: ele.text,
          num: ele.items ? ele.items.length : 0,
          numChildren: ele.items ? ele.items.length : 0
        }
      },
      checkFile(){
        this.copyItemsTree = $.extend({},this.$refs.filesListTree.items);
        this.referenceNodeTree = this.$refs.filesListTree.checked;
      },
      uncheckFile(){
        this.referenceNodeTree = this.$refs.filesListTree.checked;
      },
      // return button delete in the table
      buttonDeleteLanguages(){
        return [{
          text: "destroy",
          icon: "fas fa-trash",
          command: (row, col, id )=>{
            return this.$refs.tableLanguages.delete(id, bbn._("Are you sure you want to delete?"));
          },
          notext: true
        }]
      },
      buttonDeleteThemes(){
        return [{
          text: "destroy",
          icon: "fas fa-trash",
          command: (row, col, id )=>{
            return this.$refs.tableThemes.delete(id, bbn._("Are you sure you want to delete?"));
          },
          notext: true
        }]
      },
      // for order list file
      selectFileMove(file){
        this.fileMove = file;
      },
      //FOR MOVE
      moveDown(){
        let sourceOrder = this.treeOrderSource,
            idx;
        this.treeOrderSource.forEach((file, i) => {
          if( this.fileMove === file){
            idx = i;
          }
        });
        if ( this.treeOrderSource[idx + 1] !== undefined ){
          let support = this.treeOrderSource[idx + 1];
          sourceOrder[idx + 1] = sourceOrder[idx];
          sourceOrder[idx] = support;
          this.referenceNodeTree = sourceOrder;
        }
        else{
          let support = this.treeOrderSource[0];
          sourceOrder[0] = sourceOrder[idx];
          sourceOrder[idx] = support;
          this.referenceNodeTree = sourceOrder;
        }
      },
      moveUp(){
        let sourceOrder = this.treeOrderSource,
            idx;
        this.treeOrderSource.forEach((file, i) => {
          if( this.fileMove === file){
            idx = i;
          }
        });
        if ( this.treeOrderSource[idx - 1] !== undefined ){
          let support = this.treeOrderSource[idx - 1];
          sourceOrder[idx - 1] = sourceOrder[idx];
          sourceOrder[idx] = support;
          this.referenceNodeTree = sourceOrder;
        }
        else{
          let max = bbn.fn.count(this.treeOrderSource)-1,
              support = this.treeOrderSource[max];

          sourceOrder[max] = sourceOrder[idx];
          sourceOrder[idx] = support;
          this.referenceNodeTree = sourceOrder;
        }
      },
      //DEPANDANCIES TABLE
      //buttons table  depandacies
      buttonsTableDepandencies(row, col, idx){
        return [
          {
           text: 'Delete',
           command: (row, col, id )=>{
             return this.$refs.tableDependecies.delete(id, bbn._("Are you sure you want to delete?"));
           },
           icon: 'fas fa-trash',
           title: 'delete',
           notext: true
          }
        ];
      },
      //at select a version depandacies render in this function for show at number version and not your id
      showVersion(ele){
        return bbn.fn.get_field(this.dataVersion.lib_ver, "id_ver", ele.id_ver, "version");
      },
      //at click editline table dependencies
      saveDependencies(row, col, idx){
        //error in case no lib or version for dependencies
        if ( !row.id_ver || !row.lib_name ){
          appui.error(bbn._("ID_VERSION or LIBRARY NAME is missing"));
        }
         //error in case no lib or version or existing lib  in list of dependencies
        else if ( bbn.fn.search(this.dataVersion.dependencies, 'lib_name', row.lib_name) >= 0 ){
          appui.error(bbn._("Dependencies already inserted"));
        }
        //if we insert a value that is not an integer as the order number
        else if ( !Number.isInteger(row.order) ){
          appui.error(bbn._("The order is not an integer"));
        }
        else {
          this.$refs.tableDependecies.add(row);
          this.$refs.tableDependecies.updateData();
          this.$refs.tableDependecies._removeTmp();
        }
      },//for to display dependency names in the table because the source that is attributed to the column is a computed and updates every time we insert a new dependency
      renderLibName(row){
        let idx =  bbn.fn.search(this.listLib, 'value', row.lib_name);
        if ( idx > -1 ){
          return this.listLib[idx]['value'];
        }
      },
      //for edit version
      checkedNode(){
        if (  this.management.action.editVers ){
          this.$refs.filesListTree.checked= this.referenceNodeTree;
        }
      },
      //is called to the mounted in case of edit version
      editVersion(){
        this.configuratorLibrary = true;
        bbn.fn.post("cdn/data/version/edit", {version: this.source.row.id, library: this.source.row.library}, d => {
          if ( d.data !== undefined ){
            this.dataVersion.dependencies = d.data.dependencies;
            this.dataVersion.files_tree = d.data.files_tree;
            this.dataVersion.version = this.source.row.name;
            let arr = [];
            for( let obj of d.data.files ){
              arr.push(obj.path);
            }
            this.dataVersion.languages_tree = d.data.languages_tree;
            this.dataVersion.themes_tree = d.data.themes_tree;
            this.referenceNodeTree = arr;
            this.data.themes = d.data.themes;
            this.data.languages = d.data.languages;
            this.dataVersion.lib_ver = d.data.lib_ver;
            this.data.versions = d.data.versions;
            this.data.internal = this.source.row.internal;
            this.data.latest = this.source.row.is_latest;
            delete this.source.row.is_latest;
            delete this.source.row.files_tree;
            // for dropdown list dependencies
            for ( let val of d.data.lib_ver){
              if ( bbn.fn.search(this.listLib, 'text', val.lib_title) < 0 ){
                this.listLib.push({text: val.lib_title, value: val.lib_name});
              }
            }
          }
        });
      },
      addVersion(){
        this.configuratorLibrary = true;
        this.dataVersion = this.source.row;
        this.data.versions = this.source.versions;
        this.dataVersion.themes_tree = this.source.row.files_tree;
        // for dropdown list dependencies
        for ( let val of this.source.row.lib_ver){
          if ( bbn.fn.search(this.listLib, 'text', val.lib_title) < 0 ){
            this.listLib.push({text: val.lib_title, value: val.lib_name});
          }
        }
      },
      showTable(type){
        this.table = (type === this.table) ? '' : type;
      },
      buttonUploadDepandance(ele){
        let i = bbn.fn.search(this.listNoUpdate, 'name', ele.name)
        if ( i > -1 ){
          this.listNoUpdate.splice(i,1);
        }
      },
      buttonNoUpload(ele){
        let i = bbn.fn.search(this.listUpdate, 'name', ele.name)
        if ( i === -1 ){
          this.listNoUpdate.push(ele);
        }
      },
        /*return [
          {
            text: bbn._('Update last version'),
            command: ( row, col, id)=>{
              let i = bbn.fn.search(this.listUpdate, 'name', row.name)
              if ( i > -1 ){
                this.listUpdate.splice(i,1);
              }
              this.listUpdate.push(row);
            },
            icon: 'zmdi zmdi-thumb-up',
            title: bbn._('Update last version'),
            notext: true,

            style:"width:50%; color: green"
          },{
          text: bbn._('no update'),
          command: ( row, col, id)=>{
            this.listNoUpdate.push(row);
          },
          icon: 'fas fa-ban',
          title: bbn._('No update'),
          notext: true,
          style:"width:50%; color: red"
        }]*/
      //},
      getDependent(){
        this.showTable('dependent');
      }
    },
    created(){
      editLib = this;
    },
    mounted(){
      let licences = this.management.source.licences;
      if ( licences.length ){
        for(let ele of licences ){
          this.licencesList.push({
            text: ele.name,
            value: ele.licence
          })
        }
      }
      //if edit version
      if ( this.management.action.editVers ){
        this.editVersion();
      }//if add version
      if ( this.management.action.addVers ){
        this.addVersion();
      }
      if ( this.management.action.editLib ){
        this.newName = this.source.row.name;
      }
      if ( this.management.action.addLib ){
/*        let popup = bbn.vue.closest(this, 'bbns-tab').popup(),
            id_popup = bbn.fn.count(popup.popups)-2;
        popup.close(id_popup);*/
      }
    },
    components: {
      'update':{
        template:`<bbn-switch v-model="update"
                              :value="true"
                              :novalue="false"
                              :noIcon="false"
                              offIcon="fas fa-ban"
                              onIcon="far fa-check-circle"                               
                  ></bbn-switch>`,
        props: ['source'],
        data(){
          return {
            update: true
          }
        },
        watch:{
          update(val){
            if ( val === false ){
              editLib.buttonNoUpload(this.source);
            }
            else{
              editLib.buttonUploadDepandance(this.source);
            }
          }
        }
      },
      //button in title column grid add file language
      'languages':{
        template:`<bbn-button @click="openTreeLanguage" :title="titleButton" icon="fas fa-plus"></bbn-button>`,
        props: ['source'],
        data(){
          return{
            titleButton: bbn._('Add file language'),
            sourceTree:  editLib.dataVersion.languages_tree,
          }
        },
        methods:{
          openTreeLanguage(){
            this.getPopup().open({
              height: '70%',
              width: '30%',
              title: bbn._("Files:"),
              component:'appui-cdn-management-popup-tree_files',
              source: {tree: this.sourceTree, table: bbn.vue.closest(this,"bbn-form").$parent.data.languages},
              onClose: () =>{
                bbn.vue.closest(this,'bbn-table').updateData()
              }
            });
          },
        }
      },
      //button in title column grid add themes language
      'themes':{
        template:`<bbn-button @click="openTreeThemes" :title="titleButton" icon="fas fa-plus"></bbn-button>`,
        props: ['source'],
        data(){
          return{
            titleButton: bbn._('Add theme'),
            sourceTree:  editLib.dataVersion.themes_tree,
          }
        },
        methods:{
          openTreeThemes(){
            this.getPopup().open({
              height: '70%',
              width: '30%',
              title: bbn._("Files:"),
              component:'appui-cdn-management-popup-tree_files',
              source: {
                tree: this.sourceTree,
                table: bbn.vue.closest(this,"bbn-form").$parent.data.themes
              },
              onClose: () =>{
                bbn.vue.closest(this,'bbn-table').updateData()
              }
            });
          },
        }
      },
      'versions':{
        template:`<bbn-dropdown :source="listVersion" @change="getVersion"></bbn-dropdown>`,
        props: ['source'],
        data(){
          return {
            lib_ver: editLib.dataVersion.lib_ver
          }
        },
        methods:{
          getVersion(version){
            this.source.id_ver = version;
          }
        },
        computed:{
          listVersion(){
            if ( this.source.lib_name ){
              let arr =[];
              for ( let val of this.lib_ver ){
                if ( val.lib_name === this.source.lib_name ){
                  arr.push({text: val.version, value: val.id_ver});
                }
              }
              return arr
            }
            return []
          }
        }
      }
    }
  }
})();