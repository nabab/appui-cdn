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
          edit: 'cdn/actions/library/edit',
          add:  'cdn/actions/library/add',
        },//construct buttons for general form
        btns:{
          preCompile:[{
            text: "cancel",
            icon: 'fa fa-ban',
            command: ()=>{ this.$refs.form_library.cancel() },
          },{
            text: "next",
            title: "next",
            icon: 'fa fa-arrow-circle-o-right',
            command: ()=>{ this.next() }
          }],
          next:[{
            text: "before",
            title: "before",
            icon: 'fa fa-arrow-circle-o-left',
            command: ()=>{ this.configuratorLibrary = false }
          },{
              text: "cancel",
              icon: 'fa fa-ban',
              command: ()=>{ this.$refs.form_library.cancel() },
          },{
              text: "Save",
              icon: 'fa fa-check-circle-o',
              command: ()=>{ this.$refs.form_library.submit() },
            }
          ]
        }
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
                    e.model.set(prop, d.data[prop]);
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
        if ( this.source.addLibrary &&
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
                   this.dataVersion[i] = (d.data && d.data[i].length) ? d.data[i] : []
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
      /*TODO*/
      buttonsTableDepandencies(row, col, idx){
        return [
          {
            text: 'Edit',
            command:()=>{
              this.editDepandancie(row, col, idx);
            },
            icon: 'fa fa-edit',
            title: 'edit',
            notext: true
          },
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
      },/*TODO*/
      /*editDepandancie(row, col, idx){
        let libraries = [];
        this.dataVersion.lib_ver.each( (val, i)=>{
          if ( bbn.fn.search(libraries, 'lib_name', row.lib_name) < 0 ){
            libraries.push(row);
          }
        });
        return this.$refs.tableDependecies.edit(row,  {
          title: bbn._("Edit Depandancie"),
          height: '95%',
          width: '85%'
        }, idx);
      },*/
      saveDependencies(row, col, idx){
        bbn.fn.log("SAVE", row, col, idx)
        if ( !row.id_ver || !row.lib_name || (bbn.fn.search(this.dataVersion.dependencies, 'order', row.order) > 0) ){
          appui.error(bbn._("error information to add to the addiction"));
        }
        else {
          this.$refs.tableDependecies.add(row);
          this.$refs.tableDependecies.updateData();
          this.$refs.tableDependecies._removeTmp();
          //this.$refs.tableDependecies.editedRow = false;
        }
      }
    },
    computed:{
      //for button form
      currentButton(){
        // case for edit table cdn managment
        if (  !this.source.addLibrary && $.isEmptyObject(this.dataVersion) ){
          return ['submit', 'cancel'];
        }
        // case for add library of the toolbar first form
        if ( this.source.addLibrary && !this.configuratorLibrary ) {
          return this.btns.preCompile
        }
        // case for add library and click next for configurator library before saving
        if ( this.source.addLibrary && this.configuratorLibrary ){
          return this.btns.next
        }
      },
      //for action form
      currentAction(){
        return this.source.addLibrary ? this.actionsPath.add :  this.actionsPath.edit
      },//for tree order files
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
      },//for first isert lib or no
      checkedLatest(){
        return  bbn.fn.count(this.sourceInternal) === 1 ? true : false
      },
      //for form senction 2 first of save library
      complementaryData(){
        return {
          vname: this.dataVersion.version,
          files: this.treeOrderSource,
          languages: this.data.languages,
          themes: this.data.themes,
          new_name: this.newName,
          dependencies: this.dataVersion.dependencies
        }
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
            sourceTree:  editLib.dataVersion.languages_tree,
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
