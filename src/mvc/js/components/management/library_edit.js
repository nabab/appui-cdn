(() => {
  var editLib;
  return {
    data(){
      return {
        licencesList: [],
        checkedFile: [],
        referenceNodeTree: [],
        copyItemsTree: [],
        dataVersion: {},
        //for tables languages file and theme of library
        data:{
          languages: [],
          themes: [],
          latest: true,
          internal: ""
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
        if ( this.source.add &&
           this.source.row.name &&
           this.source.row.title &&
           $.isEmptyObject(this.dataVersion)
          ){
            bbn.fn.post(this.management.source.root +'data/version/add', {
              folder: this.source.row.name,
              git_id_ver: this.source.row.git_id,
              git_user:  this.source.row.user !== undefined ? this.source.row.user : false,
              git_repo: this.source.row.repo !== undefined ? this.source.row.repo : false,
              git_latest_ver: this.source.row.latest !== undefined ? this.source.row.latest : false
            }, d =>{
               if( d.data ){
                  this.dataVersion = d.data;
                  this.configuratorLibrary = true;
               }
            });
        }
        else{
          if ( !$.isEmptyObject(this.dataVersion) ){
            this.configuratorLibrary = true;
          }
        }
      },
      //for tree
      selectElement(){
        alert("select");
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
      //for dragdrop file in tree
      orderFiles(){
        bbn.fn.warning(" ORDER FILES");
        bbn.fn.log(" ORDER FILES", arguments);
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
      //buttons table  depandacies
      buttonsTableDepandencies(row, col, idx){
        return [
          {
            text: 'Edit',
            command:()=>{
            //  this.editDepandancie(row, col, idx);
            },
            icon: 'fa fa-edit',
            title: 'edit',
            notext: true
          },
          {
           text: 'Delete',
           command:()=>{
            // this.removeDepandancie(row,col,idx);
           },
           icon: 'fa fa-trash',
           title: 'delete',
           notext: true
          }
        ];
      },
      /*editDepandancie(){

      },
      removeDepandancie()(

      ),*/
      //TEST
      move(file){
        bbn.fn.warning("MOveeee");
        bbn.fn.log("move", arguments);
        this.fileMove = file;
      },
      //TEST
      moveUp(){
        alert("up")
      },
      //TEST
      moveDown(){
        alert("down")
      }
    },
    computed:{
      //for button form
      currentButton(){
        // case for edit table cdn managment
        if (  !this.source.add && $.isEmptyObject(this.dataVersion) ){
          return ['submit', 'cancel'];
        }
        // case for add library of the toolbar first form
        if ( this.source.add && !this.configuratorLibrary ) {
          return this.btns.preCompile
        }
        // case for add library and click next for configurator library before saving
        if ( this.source.add && this.configuratorLibrary ){
          return this.btns.next
        }
      },
      //for action form
      currentAction(){
        return this.addLibrary ? this.actionsPath.add :  this.actionsPath.edit
      },//for tree order files
      treeOrderSource(){
        if ( this.referenceNodeTree.length ){
          let arr = [];
          for( let i in this.referenceNodeTree ){
            arr.push({
              text: this.referenceNodeTree[i],
              path: this.referenceNodeTree[i],
              icon: 'fa fa-file',
              id: this.identification++,
            });
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
      'appui-cdn-management-btn-add-languages-row-table':{
        name: 'appui-cdn-management-btn-add-languages-row-table',
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
      'appui-cdn-management-btn-add-themes-row-table':{
        name: 'appui-cdn-management-btn-add-themes-row-table',
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
      }
    }
  }
})();
