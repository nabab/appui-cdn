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
          internal: 0
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
        },//construct buttons for general form
        btnsNext:[{
          text: "prev",
          title: "prev",
          icon: 'fa fa-arrow-circle-o-left',
          command: ()=>{ this.configuratorLibrary = false }
        },{
            text: "cancel",
            icon: 'fa fa-ban',
            command: ()=>{ this.$refs.form_library.cancel() },
        },{
            text: "Save",
            icon: 'fa fa-check-circle-o',
            command: ()=>{
              if ( this.referenceNodeTree.length ){
                this.$refs.form_library.submit()
              }
              else{
                alert(bbn._("No file order"));
              }
            },
          }
        ]
      }
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
                  /*else {
                    e.model.set(prop, d.data[prop]);f
                  }*/
                }
              }
            }
          }
        );
      },
      success(){
        bbn.vue.find(bbn.vue.closest(this, 'bbn-tab'), 'bbn-table').updateData();
        appui.success(bbn._('Success!'));
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
            }, d =>{
               if( d.data ){
                 for(let i in this.dataVersion){
                   this.dataVersion[i] = (d.data && d.data[i]) ? d.data[i] : []
                   if (i === "themes_tree"){
                     this.dataVersion[i] = d.data.files_tree ?  d.data.files_tree : [];
                   }
                 }
                 this.configuratorLibrary = true;
                 //for dropdown list library in table depanadancies
                 for ( let val of d.data.lib_ver){
                   if ( bbn.fn.search(this.listLib, 'text', val.lib_name) < 0 ){
                     this.listLib.push({text: val.lib_name, value: val.lib_name});
                   }
                 };
               }
            });
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
          icon: ele.items ? 'fa fa-folder' : 'fa fa-file',
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
          icon: "fa fa-trash",
          command: (row, col, id )=>{
            return this.$refs.tableLanguages.delete(id, bbn._("Are you sure you want to delete?"));
          },
          notext: true
        }]
      },
      buttonDeleteThemes(){
        return [{
          text: "destroy",
          icon: "fa fa-trash",
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
           icon: 'fa fa-trash',
           title: 'delete',
           notext: true
          }
        ];
      },
      //at select a version depandacies render in this function for show at number version and not your id
      showVersion(ele){
        return bbn.fn.get_field(this.dataVersion.lib_ver, "id_ver", ele.id_ver, "version");
      },
      saveDependencies(row, col, idx){
        if ( !row.id_ver || !row.lib_name || (bbn.fn.search(this.dataVersion.dependencies, 'order', row.order) > 0) ){
          appui.error(bbn._("error information to add to the addiction"));
        }
        else {
          this.$refs.tableDependecies.add(row);
          this.$refs.tableDependecies.updateData();
          this.$refs.tableDependecies._removeTmp();
          //this.$refs.tableDependecies.editedRow = false;
        }
      },//for edit version
      checkedNode(){
        if (  this.management.action.editVers ){
          this.$refs.filesListTree.checked= this.referenceNodeTree;
        }
      },
      //is called to the mounted in case of edit version
      editVersion(){
        this.configuratorLibrary = true;
        bbn.fn.post("cdn/data/version/edit", {version: this.source.row.id}, d => {
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
            // for dropdown list dependencies
            for ( let val of d.data.lib_ver){
              if ( bbn.fn.search(this.listLib, 'text', val.lib_name) < 0 ){
                this.listLib.push({text: val.lib_name, value: val.lib_name});
              }
            }
          }
        });
      },
      addVersion(){
        this.configuratorLibrary = true;
        this.dataVersion = this.source.row;
        this.dataVersion.themes_tree = this.source.row.files_tree;
        // for dropdown list dependencies
        for ( let val of this.source.row.lib_ver){
          if ( bbn.fn.search(this.listLib, 'text', val.lib_name) < 0 ){
            this.listLib.push({text: val.lib_name, value: val.lib_name});
          }
        }
      }
    },
    computed:{
      //disabled or no input name version
      editAddVersion(){
        return ( this.management.action.editVers || this.management.action.addVers ) ? true : false
      },
      //for button form
      currentButton(){
        if( this.management.action ){
          // case for edit table cdn managment
          if ( this.management.action.editLib  /*&& $.isEmptyObject(this.dataVersion)*/ ){
            return ['cancel', 'submit'];
          }
              // case for add library of the toolbar first form
          if ( this.management.action.addLib && !this.configuratorLibrary ) {
            return this.preCompileButtons
          }
          // case for add library and click next for configurator library before saving
          if ( (this.management.action.addLib || this.management.action.addVers || this.management.action.editVers) && this.configuratorLibrary ){
            return this.btnsNext
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
        return [];
      },
      //source for dropdown list internal at click latest checkbox
      sourceInternal(){
        if ( this.dataVersion.internal.length ){
          return this.dataVersion.internal
        }
        else{
          return [
            {
              text: '0',
              value: '0'
            }
          ]
        }
      },//for first insert lib or no
      checkedLatest(){
        return  bbn.fn.count(this.sourceInternal) === 1 && !this.management.action.editVers ? true : false
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
          name: this.source.name || "",
          latest : this.data.latest
        }
      },
      //case manualy or no library abilitation button next
      preCompileButtons(){
        if ( !this.source.row.title && !this.source.row.name ){
          return [{
            text: "cancel",
            icon: 'fa fa-ban',
            command: ()=>{ this.$refs.form_library.cancel() },
          },{
            text: "next",
            title: "next",
            icon: 'fa fa-arrow-circle-o-right',
            disabled: true,
            command: ()=>{ this.next() }
          }];
        }
        else{
          return [{
            text: "cancel",
            icon: 'fa fa-ban',
            command: ()=>{ this.$refs.form_library.cancel() },
          },{
            text: "next",
            title: "next",
            icon: 'fa fa-arrow-circle-o-right',
            disabled: false,
            command: ()=>{ this.next() }
          }];
        }
      },
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
    },
    components: {
      //button in title column grid add file language
      'languages':{
        template:`<bbn-button @click="openTreeLanguage" :title="titleButton" icon="fa fa-plus"></bbn-button>`,
        props: ['source'],
        data(){
          return{
            titleButton: bbn._('Add file language'),
            sourceTree:  editLib.dataVersion.languages_tree,
          }
        },
        methods:{
          openTreeLanguage(){
            /*let dataTree = []
            for ( let ob of this.sourecTree ){
              if ( (ob.path.indexOf(.js) > -1) || ob.items ){
                dataTree.push(ob)
              }
            }*/
            bbn.vue.closest(this, 'bbn-tab').popup().open({
              height: '60%',
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
        template:`<bbn-button @click="openTreeThemes" :title="titleButton" icon="fa fa-plus"></bbn-button>`,
        props: ['source'],
        data(){
          return{
            titleButton: bbn._('Add theme'),
            sourceTree:  editLib.dataVersion.themes_tree,
          }
        },
        methods:{
          openTreeThemes(){
            bbn.vue.closest(this, 'bbn-tab').popup().open({
              height: '60%',
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
