(() => {
  return {
    data(){
      return {
        licencesList:[],
        dataVersion: {},
        data:{
          languages: []
        },
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
      },//for dragdrop file in tree
      orderFiles(){
        bbn.fn.warning(" ORDER FILES");
        bbn.fn.log(" ORDER FILES", arguments);
      },
      checkFile(){
        bbn.fn.warning(" check FILES");
        bbn.fn.log(" check FILES", arguments);
      },
      uncheckFile(){
        bbn.fn.warning(" uncheck FILES");
        bbn.fn.log(" uncheck FILES", arguments);
      },
      buttonLanguageCols(){
        return [{
          text: "destroy",
          icon: "fa fa-trash",
          command: ()=>{ alert("delete") },
          notext: true
        }]
      },
      //for map tree in section "next"
      treeFiles(ele){
        return {
          data: ele,
          path: ele.path,
          items: ele.items || [],
          icon: ele.items ? 'fa-fa-folder' : 'fa fa-file',
          text: ele.text,
          id: this.identification++,
          num: ele.items ? ele.items.length : 0,
          numChildren: ele.items ? ele.items.length : 0
        }
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
      },


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
      'appui-cdn-management-btn-language-add-file':{
        name: 'appui-cdn-management-btn-language-add-file',
        template:`<bbn-button @click="openTreeLanguage" :title="titleButton" icon="fa fa-plus"></bbn-button>`,
        props: ['source'],
        data(){
          return{
            titleButton: bbn._('Add file language'),
            sourceTree:  bbn.vue.closest(this,"bbn-form").$parent.dataVersion.files_tree,            
          }
        },
        methods:{
          openTreeLanguage(){
            bbn.vue.closest(this, 'bbn-tab').popup().open({
              height: '80%',
              width: '40%',
              title: bbn._("Tree Files"),
              component:'appui-cdn-management-popup-tree_files',
              source: {tree: this.sourceTree}
            });
          },
        }
      }
    }
  }
})();
